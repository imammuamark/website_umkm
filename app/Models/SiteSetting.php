<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    private const CACHE_KEY = 'site_settings.all';

    protected $table = 'site_settings';

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        $settings = Cache::remember(
            self::CACHE_KEY,
            now()->addMinutes(10),
            fn (): array => self::query()->pluck('value', 'key')->all()
        );

        return array_key_exists($key, $settings) ? $settings[$key] : $default;
    }

    /**
     * Set setting value by key.
     */
    public static function set(string $key, $value): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget(self::CACHE_KEY);

        return $setting;
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * Resolve a configurable system-page hero without exposing unsafe URLs.
     *
     * @return array{imageUrl: ?string, imageAlt: string, imageCredit: ?string, imageCreditUrl: ?string}
     */
    public static function pageHero(string $page): array
    {
        $source = self::get("{$page}_hero_source", 'url');
        $upload = self::get("{$page}_hero_upload");
        $externalUrl = self::safeHttpsUrl(self::get("{$page}_hero_url"));

        $safeUpload = is_string($upload)
            && ! str_contains($upload, '..')
            && ! str_starts_with($upload, '/')
            ? $upload
            : null;

        $imageUrl = $source === 'upload' && filled($safeUpload)
            ? Storage::disk('public')->url($safeUpload)
            : $externalUrl;

        if (! $imageUrl && filled($safeUpload)) {
            $imageUrl = Storage::disk('public')->url($safeUpload);
        }

        return [
            'imageUrl' => $imageUrl,
            'imageAlt' => (string) self::get("{$page}_hero_alt", ''),
            'imageCredit' => self::get("{$page}_hero_credit"),
            'imageCreditUrl' => self::safeHttpsUrl(self::get("{$page}_hero_credit_url")),
        ];
    }

    public static function homeHeroUrl(): ?string
    {
        $source = self::get('hero_image_source', 'upload');
        $upload = self::get('hero_image_upload');
        $safeUpload = is_string($upload) && ! str_contains($upload, '..') && ! str_starts_with($upload, '/')
            ? $upload
            : null;
        $external = self::safeHttpsUrl(self::get('hero_image_url'));

        if ($source === 'url') {
            return $external ?: ($safeUpload ? Storage::disk('public')->url($safeUpload) : null);
        }

        return $safeUpload ? Storage::disk('public')->url($safeUpload) : $external;
    }

    /**
     * Resolve one safe favicon URL for every public and administrative layout.
     */
    public static function faviconUrl(): string
    {
        if (! Schema::hasTable('site_settings')) {
            return asset('favicon.png');
        }

        $upload = self::get('theme_favicon_upload');
        $safeUpload = is_string($upload)
            && filled($upload)
            && ! str_contains($upload, '..')
            && ! str_starts_with($upload, '/')
            ? $upload
            : null;

        if ($safeUpload && Storage::disk('public')->exists($safeUpload)) {
            return Storage::disk('public')->url($safeUpload);
        }

        return asset('favicon.png');
    }

    private static function safeHttpsUrl(mixed $url): ?string
    {
        if (! is_string($url) || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        return strtolower((string) parse_url($url, PHP_URL_SCHEME)) === 'https' ? $url : null;
    }
}
