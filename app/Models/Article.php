<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Article extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'articles';

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'content',
        'featured_image',
        'excerpt',
        'meta_title',
        'meta_description',
        'status',
        'published_at',
        'reading_time',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'reading_time' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            
            if (empty($article->excerpt) && !empty($article->content)) {
                $article->excerpt = Str::limit(strip_tags($article->content), 150);
            }

            // Estimate reading time (average 200 words per minute)
            if (!empty($article->content)) {
                $wordCount = str_word_count(strip_tags($article->content));
                $article->reading_time = (int) ceil($wordCount / 200);
            }
        });

        static::created(function ($article) {
            ActivityLog::log('create_article', "Menambahkan artikel baru: {$article->title}");
        });

        static::updated(function ($article) {
            ActivityLog::log('update_article', "Mengubah artikel: {$article->title}");
        });

        static::deleted(function ($article) {
            ActivityLog::log('delete_article', "Menghapus artikel: {$article->title}");
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(400)
                    ->height(250)
                    ->sharpen(10)
                    ->nonQueued();
                    
                $this->addMediaConversion('large')
                    ->width(1200)
                    ->height(630)
                    ->nonQueued();
            });
    }

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
