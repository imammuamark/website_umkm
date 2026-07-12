<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class ImportProductImages extends Command
{
    protected $signature = 'products:import-images {--replace : Replace existing product gallery images}';

    protected $description = 'Download validated product images into local media storage';

    private const ALLOWED_HOST = 'panama-menu.onrender.com';

    private const MAX_BYTES = 8 * 1024 * 1024;

    /** @var array<string, string> */
    private const EXTENSIONS = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    public function handle(): int
    {
        $products = Product::query()->whereNotNull('image_url')->orderBy('id')->get();

        if ($products->isEmpty()) {
            $this->info('Tidak ada gambar eksternal yang perlu diimpor.');

            return self::SUCCESS;
        }

        $failures = [];
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            try {
                $this->import($product);
            } catch (Throwable $exception) {
                $failures[] = "{$product->name}: {$exception->getMessage()}";
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        foreach ($failures as $failure) {
            $this->error($failure);
        }

        $imported = $products->count() - count($failures);
        $this->info("{$imported} dari {$products->count()} gambar berhasil disimpan secara lokal.");

        return $failures === [] ? self::SUCCESS : self::FAILURE;
    }

    private function import(Product $product): void
    {
        $url = $this->validatedSourceUrl($product->image_url);

        try {
            $response = Http::connectTimeout(5)
                ->timeout(20)
                ->retry(2, 300)
                ->withOptions(['allow_redirects' => false])
                ->accept('image/jpeg,image/png,image/webp')
                ->get($url);
        } catch (ConnectionException $exception) {
            throw new \RuntimeException('Server gambar tidak dapat dihubungi.', previous: $exception);
        }

        $response->throw();
        $body = $response->body();

        if ($body === '' || strlen($body) > self::MAX_BYTES) {
            throw new \RuntimeException('Ukuran gambar kosong atau melebihi 8 MB.');
        }

        $imageInfo = @getimagesizefromstring($body);
        $mime = is_array($imageInfo) ? ($imageInfo['mime'] ?? null) : null;
        $extension = is_string($mime) ? (self::EXTENSIONS[$mime] ?? null) : null;

        if (! $extension) {
            throw new \RuntimeException('Isi file bukan JPEG, PNG, atau WebP yang valid.');
        }

        if ($this->option('replace')) {
            $product->clearMediaCollection('gallery');
        }

        if (! $product->hasMedia('gallery')) {
            $product->addMediaFromString($body)
                ->usingFileName(Str::slug($product->slug).'.'.$extension)
                ->toMediaCollection('gallery');
        }

        $product->forceFill(['image_url' => null])->saveQuietly();
    }

    private function validatedSourceUrl(?string $url): string
    {
        if (! is_string($url) || ! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \RuntimeException('URL sumber tidak valid.');
        }

        $parts = parse_url($url);
        if (($parts['scheme'] ?? null) !== 'https' || strtolower($parts['host'] ?? '') !== self::ALLOWED_HOST) {
            throw new \RuntimeException('Host sumber gambar tidak diizinkan.');
        }

        return $url;
    }
}
