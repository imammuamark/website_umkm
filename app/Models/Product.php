<?php

namespace App\Models;

use App\Support\ArticleContentSanitizer;
use App\Support\DigitalMenuCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'image_url',
        'price',
        'stock_status',
        'is_featured',
        'is_bestseller',
        'views_count',
        'is_menu_visible',
        'menu_sort_order',
        'menu_short_description',
        'menu_badge',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_bestseller' => 'boolean',
        'views_count' => 'integer',
        'is_menu_visible' => 'boolean',
        'menu_sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (! empty($product->description)) {
                $product->description = app(ArticleContentSanitizer::class)->sanitize($product->description);
            }
        });

        static::created(function ($product) {
            DigitalMenuCache::flush();
            ActivityLog::log('create_product', "Menambahkan produk baru: {$product->name}");
        });

        static::updated(function ($product) {
            DigitalMenuCache::flush();
            ActivityLog::log('update_product', "Mengubah data produk: {$product->name}");
        });

        static::deleted(function ($product) {
            DigitalMenuCache::flush();
            ActivityLog::log('delete_product', "Menghapus produk: {$product->name}");
        });

        static::saved(fn () => DigitalMenuCache::flush());
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(300)
                    ->height(300)
                    ->sharpen(10)
                    ->nonQueued();

                $this->addMediaConversion('large')
                    ->width(800)
                    ->height(800)
                    ->nonQueued();
            });
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->where('is_primary', true);
    }

    public function resolvedImageUrl(string $conversion = 'large'): ?string
    {
        $uploaded = $this->getFirstMediaUrl('gallery', $conversion)
            ?: $this->getFirstMediaUrl('gallery', 'thumb')
            ?: $this->getFirstMediaUrl('gallery');

        return $uploaded ?: $this->safeExternalImageUrl();
    }

    private function safeExternalImageUrl(): ?string
    {
        if (! is_string($this->image_url) || ! filter_var($this->image_url, FILTER_VALIDATE_URL)) {
            return null;
        }

        return strtolower((string) parse_url($this->image_url, PHP_URL_SCHEME)) === 'https'
            ? $this->image_url
            : null;
    }

    // Scopes
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBestseller($query)
    {
        return $query->where('is_bestseller', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('stock_status', 'tersedia');
    }
}
