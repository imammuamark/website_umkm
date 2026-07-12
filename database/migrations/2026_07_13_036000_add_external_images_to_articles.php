<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table): void {
            $table->string('featured_image_source', 20)->default('upload')->after('featured_image');
            $table->text('featured_image_url')->nullable()->after('featured_image_source');
            $table->string('featured_image_alt', 180)->nullable()->after('featured_image_url');
            $table->string('featured_image_credit', 120)->nullable()->after('featured_image_alt');
            $table->text('featured_image_credit_url')->nullable()->after('featured_image_credit');
            $table->json('external_images')->nullable()->after('video_urls');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table): void {
            $table->dropColumn([
                'featured_image_source',
                'featured_image_url',
                'featured_image_alt',
                'featured_image_credit',
                'featured_image_credit_url',
                'external_images',
            ]);
        });
    }
};
