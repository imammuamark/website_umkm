<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class PortableBackupService
{
    public const FORMAT = 'website-umkm-portable-backup';

    public const VERSION = 1;

    public const MAX_MANIFEST_BYTES = 8_000_000;

    public const MEDIA_PART_BYTES = 7_000_000;

    private const TABLES = [
        'business_profile',
        'site_settings',
        'product_categories',
        'products',
        'product_images',
        'article_categories',
        'articles',
        'locations',
        'pages',
        'menu_items',
        'digital_menu_settings',
        'digital_menu_access_points',
        'media',
    ];

    private const SECRET_SETTING_PATTERN = '/(?:password|secret|token|private[_-]?key|app[_-]?key|credential)/i';

    private const ALLOWED_MEDIA_EXTENSIONS = [
        'avif', 'gif', 'ico', 'jpeg', 'jpg', 'pdf', 'png', 'webp',
    ];

    /** @return array{records: int, tables: array<string, int>, media_files: int, media_bytes: int, media_parts: int, zip_available: bool} */
    public function summary(): array
    {
        $payload = $this->exportPayload()['payload'];
        $counts = collect($payload['tables'])->map(fn (array $rows): int => count($rows))->all();

        return [
            'records' => array_sum($counts),
            'tables' => $counts,
            'media_files' => (int) $payload['media']['files'],
            'media_bytes' => (int) $payload['media']['bytes'],
            'media_parts' => (int) $payload['media']['parts'],
            'zip_available' => class_exists(ZipArchive::class),
        ];
    }

    /** @return array<string, mixed> */
    public function exportPayload(): array
    {
        $tables = [];

        foreach (self::TABLES as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $rows = DB::table($table)->orderBy('id')->get()->map(fn (object $row): array => (array) $row);

            if ($table === 'site_settings') {
                $rows = $rows->reject(fn (array $row): bool => preg_match(self::SECRET_SETTING_PATTERN, (string) ($row['key'] ?? '')) === 1);
            }

            $tables[$table] = $rows->values()->all();
        }

        $payload = [
            'format' => self::FORMAT,
            'version' => self::VERSION,
            'generated_at' => now()->toIso8601String(),
            'source' => [
                'app_url' => config('app.url'),
                'database_driver' => DB::getDriverName(),
                'laravel_version' => app()->version(),
            ],
            'tables' => $tables,
            'media' => [
                'files' => count($this->mediaFiles()),
                'parts' => count($this->mediaParts()),
                'bytes' => array_sum(array_column($this->mediaFiles(), 'size')),
            ],
            'exclusions' => ['users', 'passwords', 'sessions', 'cache', 'jobs', 'contact_messages', 'activity_logs'],
        ];

        return [
            'payload' => $payload,
            'checksum' => hash('sha256', $this->canonicalJson($payload)),
        ];
    }

    public function exportJson(): string
    {
        $json = json_encode($this->exportPayload(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        if (strlen($json) > self::MAX_MANIFEST_BYTES) {
            throw new RuntimeException('Manifest melebihi batas aman 8 MB. Kurangi data atau pecah migrasi.');
        }

        return $json;
    }

    /** @return array{valid: bool, counts: array<string, int>, generated_at: string, source_driver: string, media: array<string, mixed>} */
    public function inspect(string $json): array
    {
        $document = $this->decodeAndValidate($json);
        $tables = $document['payload']['tables'];

        return [
            'valid' => true,
            'counts' => collect($tables)->map(fn (array $rows): int => count($rows))->all(),
            'generated_at' => (string) $document['payload']['generated_at'],
            'source_driver' => (string) data_get($document, 'payload.source.database_driver', 'unknown'),
            'media' => (array) ($document['payload']['media'] ?? []),
        ];
    }

    /** @return array{tables: array<string, int>, mode: string} */
    public function import(string $json, string $mode, int $actorId): array
    {
        if (! in_array($mode, ['merge', 'replace'], true)) {
            throw new RuntimeException('Mode import tidak didukung.');
        }

        $document = $this->decodeAndValidate($json);
        $tables = (array) $document['payload']['tables'];
        $counts = [];

        $this->withoutForeignKeys(function () use ($tables, $mode, $actorId, &$counts): void {
            DB::transaction(function () use ($tables, $mode, $actorId, &$counts): void {
                if ($mode === 'replace') {
                    foreach (array_reverse(self::TABLES) as $table) {
                        if (Schema::hasTable($table)) {
                            DB::table($table)->delete();
                        }
                    }
                }

                foreach (self::TABLES as $table) {
                    if (! Schema::hasTable($table) || ! isset($tables[$table]) || ! is_array($tables[$table])) {
                        continue;
                    }

                    $columns = Schema::getColumnListing($table);
                    $rows = collect($tables[$table])
                        ->filter(fn (mixed $row): bool => is_array($row))
                        ->map(function (array $row) use ($table, $columns, $actorId): array {
                            $row = array_intersect_key($row, array_flip($columns));

                            if ($table === 'site_settings' && preg_match(self::SECRET_SETTING_PATTERN, (string) ($row['key'] ?? '')) === 1) {
                                return [];
                            }

                            if ($table === 'articles') {
                                $row['author_id'] = $actorId;
                                $row['reviewed_by'] = null;
                                $row['published_by'] = in_array($row['workflow_status'] ?? null, ['published', 'scheduled'], true) ? $actorId : null;
                            }

                            return $row;
                        })
                        ->filter()
                        ->values();

                    if ($rows->isEmpty()) {
                        $counts[$table] = 0;

                        continue;
                    }

                    foreach ($rows->chunk(200) as $chunk) {
                        $first = $chunk->first();
                        $updates = array_values(array_diff(array_keys($first), ['id']));
                        DB::table($table)->upsert($chunk->all(), ['id'], $updates);
                    }

                    $counts[$table] = $rows->count();
                }
            });
        });

        Cache::flush();

        return ['tables' => $counts, 'mode' => $mode];
    }

    /** @return array<int, array<int, array{path: string, size: int}>> */
    public function mediaParts(): array
    {
        $parts = [];
        $current = [];
        $bytes = 0;

        foreach ($this->mediaFiles() as $file) {
            if ($current && ($bytes + $file['size']) > self::MEDIA_PART_BYTES) {
                $parts[] = $current;
                $current = [];
                $bytes = 0;
            }

            $current[] = $file;
            $bytes += $file['size'];
        }

        if ($current) {
            $parts[] = $current;
        }

        return $parts;
    }

    public function createMediaPart(int $part): string
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('Ekstensi PHP ZIP tidak tersedia.');
        }

        $files = $this->mediaParts()[$part - 1] ?? null;
        if (! $files) {
            throw new RuntimeException('Bagian media tidak ditemukan.');
        }

        $directory = storage_path('app/private/migration-exports');
        if (! is_dir($directory) && ! mkdir($directory, 0700, true) && ! is_dir($directory)) {
            throw new RuntimeException('Direktori export tidak dapat dibuat.');
        }

        $path = $directory.'/media-part-'.str_pad((string) $part, 2, '0', STR_PAD_LEFT).'-'.Str::random(12).'.zip';
        $zip = new ZipArchive;
        if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Arsip media tidak dapat dibuat.');
        }

        foreach ($files as $file) {
            $zip->addFile(Storage::disk('public')->path($file['path']), $file['path']);
        }
        $zip->addFromString('media-part.json', json_encode(['format' => self::FORMAT, 'version' => self::VERSION, 'part' => $part], JSON_THROW_ON_ERROR));
        $zip->close();

        return $path;
    }

    /** @return array{files: int, bytes: int} */
    public function importMediaArchive(string $path): array
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('Ekstensi PHP ZIP tidak tersedia.');
        }

        $zip = new ZipArchive;
        if ($zip->open($path) !== true) {
            throw new RuntimeException('Arsip ZIP tidak valid atau rusak.');
        }

        if ($zip->numFiles > 500) {
            $zip->close();
            throw new RuntimeException('Arsip berisi terlalu banyak file.');
        }

        $markerIndex = $zip->locateName('media-part.json', ZipArchive::FL_NOCASE);
        $marker = $markerIndex === false ? null : json_decode((string) $zip->getFromIndex($markerIndex), true);
        if (! is_array($marker)
            || ($marker['format'] ?? null) !== self::FORMAT
            || (int) ($marker['version'] ?? 0) !== self::VERSION
            || (int) ($marker['part'] ?? 0) < 1) {
            $zip->close();
            throw new RuntimeException('Arsip bukan paket media Website UMKM yang didukung.');
        }

        $entries = [];
        $total = 0;
        for ($index = 0; $index < $zip->numFiles; $index++) {
            $stat = $zip->statIndex($index);
            $name = (string) ($stat['name'] ?? '');
            if ($name === 'media-part.json' || str_ends_with($name, '/')) {
                continue;
            }

            if (! $this->isSafeMediaPath($name)) {
                $zip->close();
                throw new RuntimeException("Path media tidak aman: {$name}");
            }

            $size = (int) ($stat['size'] ?? 0);
            $total += $size;
            if ($size > 10_000_000 || $total > 9_000_000) {
                $zip->close();
                throw new RuntimeException('Ukuran isi arsip melampaui batas aman.');
            }

            $entries[] = ['index' => $index, 'name' => $name, 'size' => $size];
        }

        foreach ($entries as $entry) {
            $stream = $zip->getStream($entry['name']);
            if (! is_resource($stream)) {
                $zip->close();
                throw new RuntimeException('Salah satu file media tidak dapat dibaca.');
            }
            Storage::disk('public')->put($entry['name'], $stream);
            fclose($stream);
        }
        $zip->close();

        return ['files' => count($entries), 'bytes' => $total];
    }

    /** @return array<int, array{path: string, size: int}> */
    private function mediaFiles(): array
    {
        return collect(Storage::disk('public')->allFiles())
            ->filter(fn (string $path): bool => $this->isSafeMediaPath($path))
            ->map(fn (string $path): array => ['path' => $path, 'size' => Storage::disk('public')->size($path)])
            ->sortBy('path')
            ->values()
            ->all();
    }

    /** @return array<string, mixed> */
    private function decodeAndValidate(string $json): array
    {
        if (strlen($json) > self::MAX_MANIFEST_BYTES) {
            throw new RuntimeException('Manifest melebihi batas 8 MB.');
        }

        $document = json_decode($json, true, 128, JSON_THROW_ON_ERROR);
        if (! is_array($document) || data_get($document, 'payload.format') !== self::FORMAT || (int) data_get($document, 'payload.version') !== self::VERSION) {
            throw new RuntimeException('Format atau versi backup tidak didukung.');
        }
        if (! is_array(data_get($document, 'payload.tables'))) {
            throw new RuntimeException('Manifest tidak memiliki data tabel yang valid.');
        }

        $expected = hash('sha256', $this->canonicalJson($document['payload']));
        if (! hash_equals($expected, (string) ($document['checksum'] ?? ''))) {
            throw new RuntimeException('Checksum tidak cocok. File mungkin rusak atau telah diubah.');
        }

        return $document;
    }

    private function canonicalJson(array $payload): string
    {
        return json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    private function isSafeMediaPath(string $path): bool
    {
        $path = str_replace('\\', '/', $path);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return $path !== ''
            && ! str_starts_with($path, '/')
            && ! str_contains($path, '../')
            && ! str_contains($path, "\0")
            && preg_match('/^[A-Za-z0-9._\/-]+$/', $path) === 1
            && in_array($extension, self::ALLOWED_MEDIA_EXTENSIONS, true);
    }

    private function withoutForeignKeys(callable $callback): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        }

        try {
            $callback();
        } finally {
            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON');
            }
        }
    }
}
