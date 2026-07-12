<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            'catalog_hero_source' => 'url',
            'catalog_hero_url' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?q=85&w=2000&auto=format&fit=crop',
            'catalog_hero_alt' => 'Biji kopi pilihan untuk katalog produk',
            'articles_hero_source' => 'url',
            'articles_hero_url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=85&w=2000&auto=format&fit=crop',
            'articles_hero_alt' => 'Proses menyeduh kopi untuk jurnal dan artikel',
            'contact_hero_source' => 'url',
            'contact_hero_url' => 'https://images.unsplash.com/photo-1521017432531-fbd92d768814?q=85&w=2000&auto=format&fit=crop',
            'contact_hero_alt' => 'Suasana hangat kedai kopi untuk menghubungi tim',
        ];

        foreach ($settings as $key => $value) {
            DB::table('site_settings')->updateOrInsert(['key' => $key], ['value' => $value]);
        }
    }

    public function down(): void
    {
        DB::table('site_settings')->whereIn('key', [
            'catalog_hero_source', 'catalog_hero_url', 'catalog_hero_alt',
            'articles_hero_source', 'articles_hero_url', 'articles_hero_alt',
            'contact_hero_source', 'contact_hero_url', 'contact_hero_alt',
        ])->delete();
    }
};
