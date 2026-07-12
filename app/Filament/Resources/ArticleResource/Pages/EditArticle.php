<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use App\Support\ArticleContentSanitizer;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected ?string $maxContentWidth = 'full';

    public function getTitle(): string
    {
        return 'Edit Artikel';
    }

    public function getSubheading(): ?string
    {
        return 'Perbarui konten dan media dengan kontrol versi serta workflow publikasi yang aman.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Lihat Artikel')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (): string => route('artikel.detail', $this->record->slug))
                ->openUrlInNewTab()
                ->visible(fn (): bool => $this->record->workflow_status === 'published' && $this->record->published_at?->isPast()),
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['content_html'] = $data['content'] ?? '';
        $data['content_plain'] = html_entity_decode(strip_tags(str_replace(['</p>', '<br>', '<br/>', '<br />'], "\n", $data['content'] ?? '')));
        $data['expected_lock_version'] = $data['lock_version'] ?? 1;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $currentLockVersion = (int) $this->record->fresh()->lock_version;
        $expectedLockVersion = (int) ($data['expected_lock_version'] ?? 0);

        if ($currentLockVersion !== $expectedLockVersion) {
            throw ValidationException::withMessages([
                'title' => 'Artikel telah diperbarui oleh pengguna lain. Muat ulang halaman sebelum menyimpan perubahan.',
            ]);
        }

        $workflow = $data['workflow_status'] ?? 'draft';
        if (in_array($workflow, ['scheduled', 'published', 'archived'], true)
            && ! auth()->user()?->can('publish articles')) {
            throw ValidationException::withMessages([
                'workflow_status' => 'Anda tidak memiliki izin untuk memublikasikan atau mengarsipkan artikel.',
            ]);
        }

        $sanitizer = app(ArticleContentSanitizer::class);
        $mode = $data['editor_mode'] ?? 'visual';
        $data['content'] = match ($mode) {
            'plain' => $sanitizer->fromPlainText($data['content_plain'] ?? ''),
            'html' => $sanitizer->sanitize($data['content_html'] ?? ''),
            default => $sanitizer->sanitize($data['content'] ?? ''),
        };

        if (in_array($workflow, ['published', 'scheduled'], true)) {
            $data['published_by'] = auth()->id();

            if ($this->record->workflow_status === 'in_review') {
                $data['reviewed_by'] = auth()->id();
                $data['reviewed_at'] = now();
            }
        }

        unset($data['content_plain'], $data['content_html'], $data['expected_lock_version']);

        return $data;
    }
}
