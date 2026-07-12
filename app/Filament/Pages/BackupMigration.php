<?php

namespace App\Filament\Pages;

use App\Models\ActivityLog;
use App\Support\PortableBackupService;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Throwable;

class BackupMigration extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    protected static ?string $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Backup & Migrasi';

    protected static ?string $title = 'Backup & Migrasi';

    protected static ?string $slug = 'backup-migration';

    protected static ?int $navigationSort = 90;

    protected static string $view = 'filament.pages.backup-migration';

    protected ?string $maxContentWidth = 'full';

    public ?array $data = [];

    public array $summary = [];

    public ?array $inspection = null;

    public ?array $result = null;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('manage settings') === true;
    }

    public function mount(PortableBackupService $backup): void
    {
        $this->summary = $backup->summary();
        $this->form->fill(['mode' => 'merge']);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Portabilitas Website')->tabs([
                    Forms\Components\Tabs\Tab::make('Impor Data')
                        ->icon('heroicon-o-document-arrow-up')
                        ->schema([
                            Forms\Components\Section::make('Manifest Data')
                                ->description('Periksa manifest sebelum menjalankan impor. Akun admin, kata sandi, sesi, pesan kontak, dan rahasia aplikasi tidak pernah dipindahkan.')
                                ->schema([
                                    Forms\Components\FileUpload::make('manifest')
                                        ->label('File manifest JSON')
                                        ->disk('local')
                                        ->directory('migration-imports')
                                        ->visibility('private')
                                        ->acceptedFileTypes(['application/json', 'text/json', 'text/plain'])
                                        ->maxSize(8192)
                                        ->downloadable()
                                        ->helperText('Maksimum 8 MB. Gunakan hanya manifest yang diunduh dari modul ini.')
                                        ->columnSpanFull(),
                                    Forms\Components\Radio::make('mode')
                                        ->label('Strategi impor')
                                        ->options([
                                            'merge' => 'Gabungkan — perbarui ID yang sama dan pertahankan data lain',
                                            'replace' => 'Ganti data CMS — hapus konten portable saat ini lalu pulihkan manifest',
                                        ])
                                        ->required()
                                        ->live()
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Forms\Components\Tabs\Tab::make('Impor Media')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Forms\Components\Section::make('Paket Media')
                                ->description('Unggah seluruh paket .umkm-media secara berurutan. Proses dapat diulang dengan aman apabila koneksi terputus.')
                                ->schema([
                                    Forms\Components\FileUpload::make('media_archives')
                                        ->label('Paket media (.umkm-media)')
                                        ->multiple()
                                        ->disk('local')
                                        ->directory('migration-imports')
                                        ->visibility('private')
                                        ->acceptedFileTypes(['application/octet-stream', 'application/zip', 'application/x-zip-compressed'])
                                        ->maxFiles(30)
                                        ->maxSize(9500)
                                        ->downloadable()
                                        ->helperText('Unggah file apa adanya—tidak perlu dibuka atau diekstrak. Paket lama berformat ZIP tetap didukung.')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Forms\Components\Tabs\Tab::make('Konfirmasi')
                        ->icon('heroicon-o-shield-check')
                        ->schema([
                            Forms\Components\Section::make('Otorisasi Perubahan')
                                ->description('Konfirmasi ini diwajibkan untuk operasi impor. Kata sandi tidak disimpan maupun dicatat.')
                                ->schema([
                                    Forms\Components\TextInput::make('password')
                                        ->label('Kata sandi admin saat ini')
                                        ->password()
                                        ->revealable()
                                        ->autocomplete('current-password'),
                                    Forms\Components\TextInput::make('confirmation')
                                        ->label('Ketik IMPORT untuk melanjutkan')
                                        ->placeholder('IMPORT')
                                        ->maxLength(6),
                                ])->columns(2),
                        ]),
                ])->persistTabInQueryString('migration-tab')->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadManifest')
                ->label('Unduh Data')
                ->icon('heroicon-o-document-arrow-down')
                ->url(route('admin.portability.manifest'))
                ->openUrlInNewTab(),
        ];
    }

    public function inspectManifest(PortableBackupService $backup): void
    {
        $this->validate(['data.manifest' => ['required', 'string']]);

        try {
            $this->inspection = $backup->inspect($this->readLocalFile((string) $this->data['manifest']));
            Notification::make()->title('Manifest valid')->body('Checksum dan struktur data berhasil diverifikasi.')->success()->send();
        } catch (Throwable $exception) {
            $this->inspection = null;
            report($exception);
            Notification::make()->title('Manifest tidak dapat digunakan')->body($exception->getMessage())->danger()->send();
        }
    }

    public function importData(PortableBackupService $backup): void
    {
        $validated = $this->validateImportAuthorization(['data.manifest' => ['required', 'string']]);

        try {
            $result = $backup->import(
                $this->readLocalFile((string) $this->data['manifest']),
                (string) $validated['data']['mode'],
                (int) auth()->id(),
            );
            $this->result = ['type' => 'data'] + $result;
            ActivityLog::log('import_portable_data', 'Mengimpor data CMS dengan mode '.$result['mode'].'.');
            $this->clearAuthorization();
            $this->summary = $backup->summary();
            Notification::make()->title('Data berhasil diimpor')->body('Seluruh perubahan database telah diselesaikan dalam satu transaksi.')->success()->send();
        } catch (Throwable $exception) {
            report($exception);
            Notification::make()->title('Impor dibatalkan')->body($exception->getMessage())->danger()->persistent()->send();
        }
    }

    public function importMedia(PortableBackupService $backup): void
    {
        $validated = $this->validateImportAuthorization(['data.media_archives' => ['required', 'array', 'min:1', 'max:30']]);
        $totals = ['files' => 0, 'bytes' => 0, 'archives' => 0];

        try {
            foreach ($validated['data']['media_archives'] as $storedPath) {
                $absolutePath = Storage::disk('local')->path((string) $storedPath);
                $imported = $backup->importMediaArchive($absolutePath);
                $totals['files'] += $imported['files'];
                $totals['bytes'] += $imported['bytes'];
                $totals['archives']++;
            }
            $this->result = ['type' => 'media'] + $totals;
            ActivityLog::log('import_portable_media', "Mengimpor {$totals['files']} file dari {$totals['archives']} paket media.");
            $this->clearTemporaryUploads($validated['data']['media_archives']);
            $this->data['media_archives'] = [];
            $this->clearAuthorization();
            $this->summary = $backup->summary();
            Notification::make()->title('Media berhasil dipulihkan')->body("{$totals['files']} file telah diverifikasi dan disimpan.")->success()->send();
        } catch (Throwable $exception) {
            report($exception);
            Notification::make()->title('Impor media dihentikan')->body($exception->getMessage())->danger()->persistent()->send();
        }
    }

    /** @return array<string, mixed> */
    private function validateImportAuthorization(array $extraRules): array
    {
        return $this->validate($extraRules + [
            'data.mode' => ['required', Rule::in(['merge', 'replace'])],
            'data.password' => ['required', 'current_password'],
            'data.confirmation' => ['required', Rule::in(['IMPORT'])],
        ], [
            'data.password.current_password' => 'Kata sandi admin tidak sesuai.',
            'data.confirmation.in' => 'Ketik IMPORT persis seperti contoh.',
        ]);
    }

    private function readLocalFile(string $path): string
    {
        if (! Storage::disk('local')->exists($path)) {
            throw new \RuntimeException('File upload tidak ditemukan. Silakan unggah ulang.');
        }

        return Storage::disk('local')->get($path);
    }

    private function clearAuthorization(): void
    {
        $this->data['password'] = null;
        $this->data['confirmation'] = null;
    }

    private function clearTemporaryUploads(array $paths): void
    {
        Storage::disk('local')->delete(array_map('strval', $paths));
    }
}
