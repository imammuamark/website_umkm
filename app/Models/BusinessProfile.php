<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BusinessProfile extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'business_profile';

    protected $fillable = [
        'business_name',
        'description',
        'vision',
        'mission',
        'logo',
        'founded_year',
        'legal_docs',
    ];

    protected $casts = [
        'legal_docs' => 'array',
        'founded_year' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile();

        $this->addMediaCollection('legal_documents');
    }
}
