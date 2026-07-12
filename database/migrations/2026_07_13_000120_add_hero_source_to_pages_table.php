<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const DEFAULT_UNSPLASH_HERO = 'https://images.unsplash.com/photo-1511537190424-bbbab87ac5eb?q=85&w=2000&auto=format&fit=crop';

    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('hero_source', 20)->default('upload')->after('subtitle');
            $table->text('hero_image_url')->nullable()->after('hero_source');
            $table->string('hero_credit', 120)->nullable()->after('hero_image_url');
            $table->text('hero_credit_url')->nullable()->after('hero_credit');
        });

        DB::table('pages')
            ->whereIn('slug', ['tentang-kopi', 'tentang-panama'])
            ->whereNull('hero_image_url')
            ->update([
                'hero_source' => 'url',
                'hero_image_url' => self::DEFAULT_UNSPLASH_HERO,
                'hero_credit' => 'Foto dari Unsplash',
                'hero_credit_url' => 'https://unsplash.com/?utm_source=website_umkm&utm_medium=referral',
            ]);
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['hero_source', 'hero_image_url', 'hero_credit', 'hero_credit_url']);
        });
    }
};
