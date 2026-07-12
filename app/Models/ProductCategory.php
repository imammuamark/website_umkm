<?php

namespace App\Models;

use App\Support\DigitalMenuCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductCategory extends Model
{
    protected $table = 'product_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'is_menu_visible',
        'menu_sort_order',
        'menu_display_name',
    ];

    protected $casts = ['is_menu_visible' => 'boolean', 'menu_sort_order' => 'integer'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::saved(fn () => DigitalMenuCache::flush());
        static::deleted(fn () => DigitalMenuCache::flush());
    }

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
