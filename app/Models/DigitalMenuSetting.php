<?php

namespace App\Models;

use App\Support\DigitalMenuCache;
use Illuminate\Database\Eloquent\Model;

class DigitalMenuSetting extends Model
{
    protected $fillable = [
        'is_enabled', 'title', 'subtitle', 'layout', 'show_search', 'show_images',
        'show_descriptions', 'show_stock', 'show_badges', 'show_unavailable',
        'use_theme_colors', 'primary_color', 'accent_color', 'cta_enabled',
        'cta_label', 'cta_url', 'allow_indexing',
    ];

    protected $casts = [
        'is_enabled' => 'boolean', 'show_search' => 'boolean', 'show_images' => 'boolean',
        'show_descriptions' => 'boolean', 'show_stock' => 'boolean', 'show_badges' => 'boolean',
        'show_unavailable' => 'boolean', 'use_theme_colors' => 'boolean',
        'cta_enabled' => 'boolean', 'allow_indexing' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => DigitalMenuCache::flush());
        static::deleted(fn () => DigitalMenuCache::flush());
    }

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'title' => 'Menu Panama Corner',
            'subtitle' => 'Lihat pilihan makanan, camilan, kopi, dan minuman yang tersedia.',
        ]);
    }

    public function safeCtaUrl(): ?string
    {
        $url = trim((string) $this->cta_url);
        if ($url === '') {
            return null;
        }
        if (str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return $url;
        }

        return filter_var($url, FILTER_VALIDATE_URL) && parse_url($url, PHP_URL_SCHEME) === 'https' ? $url : null;
    }
}
