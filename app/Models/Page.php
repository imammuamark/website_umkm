<?php

namespace App\Models;

use App\Support\ArticleContentSanitizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Page extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'pages';

    protected $fillable = [
        'title',
        'slug',
        'template',
        'eyebrow',
        'subtitle',
        'hero_source',
        'hero_image_url',
        'hero_credit',
        'hero_credit_url',
        'hero_alt',
        'content_image_alt',
        'about_values_title',
        'about_primary_label',
        'about_secondary_label',
        'content',
        'widgets',
        'meta_title',
        'meta_description',
        'status',
        'is_in_navigation',
        'sort_order',
    ];

    protected $casts = [
        'is_in_navigation' => 'boolean',
        'sort_order' => 'integer',
        'widgets' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }

            // Sanitize content from dangerous scripting / tags (secure)
            $page->content = app(ArticleContentSanitizer::class)->sanitize($page->content);

            // Generate metadata if empty
            if (empty($page->meta_title) && ! empty($page->title)) {
                $page->meta_title = Str::limit(strip_tags($page->title), 70);
            }
            if (empty($page->meta_description)) {
                $page->meta_description = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($page->content ?? ''))), 160);
            }
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero_image')
            ->singleFile()
            ->registerMediaConversions(function (Media $media): void {
                $this->addMediaConversion('hero')
                    ->width(1920)
                    ->height(900)
                    ->sharpen(8)
                    ->nonQueued();
            });

        $this->addMediaCollection('content_image')
            ->singleFile()
            ->registerMediaConversions(function (Media $media): void {
                $this->addMediaConversion('large')
                    ->width(1200)
                    ->height(900)
                    ->sharpen(6)
                    ->nonQueued();
            });
    }

    public function getResolvedHeroUrl(): ?string
    {
        $uploaded = $this->getFirstMediaUrl('hero_image', 'hero')
            ?: $this->getFirstMediaUrl('hero_image');
        $external = $this->isSafeExternalImageUrl($this->hero_image_url)
            ? $this->hero_image_url
            : null;

        return match ($this->hero_source) {
            'url' => $external ?: ($uploaded ?: null),
            default => $uploaded ?: $external,
        };
    }

    public function getSafeHeroCreditUrl(): ?string
    {
        return $this->isSafeExternalImageUrl($this->hero_credit_url)
            ? $this->hero_credit_url
            : null;
    }

    private function isSafeExternalImageUrl(?string $url): bool
    {
        if (blank($url) || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        return strtolower((string) parse_url($url, PHP_URL_SCHEME)) === 'https';
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeInNavigation($query)
    {
        return $query->where('is_in_navigation', true);
    }
}
