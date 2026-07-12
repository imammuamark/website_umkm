<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pages')
            ->where('slug', 'tentang-kopi')
            ->update([
                'hero_source' => 'url',
                'hero_image_url' => 'https://images.unsplash.com/photo-1442512595331-e89e73853f31?q=85&w=2000&auto=format&fit=crop',
                'hero_alt' => 'Lanskap perkebunan kopi di dataran tinggi',
                'hero_credit' => 'Foto dari Unsplash',
                'hero_credit_url' => 'https://unsplash.com/s/photos/coffee-plantation?utm_source=website_umkm&utm_medium=referral',
            ]);

        DB::table('pages')
            ->where('slug', 'lokasi')
            ->update([
                'hero_source' => 'url',
                'hero_image_url' => 'https://images.unsplash.com/photo-1445116572660-236099ec97a0?q=85&w=2000&auto=format&fit=crop',
                'hero_alt' => 'Interior kedai kopi modern dan hangat',
                'hero_credit' => 'Foto dari Unsplash',
                'hero_credit_url' => 'https://unsplash.com/s/photos/coffee-shop?utm_source=website_umkm&utm_medium=referral',
            ]);
    }

    public function down(): void
    {
        DB::table('pages')
            ->whereIn('slug', ['tentang-kopi', 'lokasi'])
            ->update([
                'hero_source' => 'upload',
                'hero_image_url' => null,
                'hero_alt' => null,
                'hero_credit' => null,
                'hero_credit_url' => null,
            ]);
    }
};
