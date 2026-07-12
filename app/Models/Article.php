<?php

namespace App\Models;

use App\Support\ArticleContentSanitizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Article extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $table = 'articles';

    protected $fillable = [
        'category_id',
        'author_id',
        'reviewed_by',
        'reviewed_at',
        'published_by',
        'title',
        'slug',
        'content',
        'editor_mode',
        'featured_image',
        'video_urls',
        'excerpt',
        'meta_title',
        'meta_description',
        'status',
        'workflow_status',
        'published_at',
        'reading_time',
        'revision',
        'lock_version',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'reading_time' => 'integer',
        'video_urls' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }

            $article->content = app(ArticleContentSanitizer::class)->sanitize($article->content);

            if (empty($article->excerpt) && ! empty($article->content)) {
                $article->excerpt = Str::limit(strip_tags($article->content), 150);
            }

            // Estimate reading time (average 200 words per minute)
            if (! empty($article->content)) {
                preg_match_all('/[\p{L}\p{N}\']+/u', strip_tags($article->content), $matches);
                $article->reading_time = max(1, (int) ceil(count($matches[0]) / 200));
            }

            // Intelligent SEO Metadata generation
            if (empty($article->meta_title) && ! empty($article->title)) {
                $article->meta_title = Str::limit(strip_tags($article->title), 70);
            }
            if (empty($article->meta_description)) {
                $sourceText = $article->excerpt ?: strip_tags($article->content ?? '');
                $article->meta_description = Str::limit(trim(preg_replace('/\s+/', ' ', $sourceText)), 160);
            }

            $workflow = $article->workflow_status ?: 'draft';

            if ($workflow === 'published') {
                $article->status = 'published';
                $article->published_at ??= now();
            } elseif ($workflow === 'scheduled') {
                $article->status = 'published';
            } else {
                $article->status = 'draft';

                if (in_array($workflow, ['draft', 'in_review'], true)) {
                    $article->published_at = null;
                }
            }

            if (! $article->exists) {
                $article->revision ??= 1;
                $article->lock_version ??= 1;
            } elseif ($article->isDirty(['title', 'content', 'excerpt', 'meta_title', 'meta_description'])) {
                $article->revision = ((int) ($article->getOriginal('revision') ?? 1)) + 1;
                $article->lock_version = ((int) ($article->getOriginal('lock_version') ?? 1)) + 1;
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
                    ->queued();
            });

        $this->addMediaCollection('content_images')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(480)
                    ->height(320)
                    ->sharpen(8)
                    ->nonQueued();

                $this->addMediaConversion('content')
                    ->width(1440)
                    ->sharpen(6)
                    ->queued();
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

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
