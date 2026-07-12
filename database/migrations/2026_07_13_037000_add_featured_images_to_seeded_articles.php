<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('articles')
            ->where('slug', 'panduan-lengkap-menyeduh-kopi-v60-anti-gagal-untuk-pemula')
            ->whereNull('featured_image_url')
            ->update([
                'featured_image_source' => 'url',
                'featured_image_url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=85&w=1600&auto=format&fit=crop',
                'featured_image_alt' => 'Proses menyeduh kopi menggunakan dripper V60',
                'featured_image_credit' => 'Unsplash',
                'featured_image_credit_url' => 'https://unsplash.com/',
            ]);

        DB::table('articles')
            ->where('slug', 'mengenal-proses-pasca-panen-kopi-wash-honey-dan-natural')
            ->whereNull('featured_image_url')
            ->update([
                'featured_image_source' => 'url',
                'featured_image_url' => 'https://images.unsplash.com/photo-1442512595331-e89e73853f31?q=85&w=1600&auto=format&fit=crop',
                'featured_image_alt' => 'Buah kopi matang pada tanaman kopi',
                'featured_image_credit' => 'Unsplash',
                'featured_image_credit_url' => 'https://unsplash.com/',
            ]);
    }

    public function down(): void
    {
        // Editorial media is intentionally retained when rolling back.
    }
};
