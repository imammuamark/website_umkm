<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    protected $table = 'menu_items';

    protected $fillable = [
        'label',
        'type',
        'page_id',
        'url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'page_id' => 'integer',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    /**
     * Resolve the target URL for the menu link.
     */
    public function getUrl(): string
    {
        return match ($this->type) {
            'home' => route('home'),
            'catalog' => route('produk'),
            'articles' => route('artikel'),
            'contact' => route('kontak'),
            'page' => $this->page ? route('page.detail', $this->page->slug) : '#',
            'custom' => $this->safeCustomUrl(),
            default => '#',
        };
    }

    private function safeCustomUrl(): string
    {
        $url = trim((string) $this->url);

        if ((str_starts_with($url, '/') && ! str_starts_with($url, '//'))
            || (filter_var($url, FILTER_VALIDATE_URL) && parse_url($url, PHP_URL_SCHEME) === 'https')) {
            return $url;
        }

        return '#';
    }

    /**
     * Check if the current route matches this menu item.
     */
    public function isActiveRoute(): bool
    {
        return match ($this->type) {
            'home' => request()->routeIs('home'),
            'catalog' => request()->routeIs('produk*'),
            'articles' => request()->routeIs('artikel*'),
            'contact' => request()->routeIs('kontak*'),
            'page' => $this->page ? request()->fullUrlIs(route('page.detail', $this->page->slug)) : false,
            'custom' => $this->url
                ? (str_starts_with($this->url, '/')
                    ? request()->is(ltrim($this->url, '/'))
                    : request()->fullUrlIs($this->url))
                : false,
            default => false,
        };
    }
}
