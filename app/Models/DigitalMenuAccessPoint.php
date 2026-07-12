<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DigitalMenuAccessPoint extends Model
{
    protected $fillable = ['public_id', 'label', 'type', 'category_id', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'last_scanned_at' => 'datetime', 'scans_count' => 'integer'];

    protected static function booted(): void
    {
        static::creating(function (self $point): void {
            $point->public_id ??= Str::lower(Str::random(16));
        });
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function publicUrl(): string
    {
        return route('digital-menu.index', ['t' => $this->public_id]);
    }
}
