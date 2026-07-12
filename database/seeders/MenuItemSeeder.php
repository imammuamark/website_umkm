<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $aboutPage = Page::where('slug', 'tentang-kopi')->first();
        $locationPage = Page::where('slug', 'lokasi')->first();

        MenuItem::updateOrCreate(
            ['type' => 'home'],
            [
                'type' => 'home',
                'label' => 'Beranda',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        MenuItem::updateOrCreate(
            ['type' => 'page', 'page_id' => $aboutPage?->id],
            [
                'type' => 'page',
                'label' => 'Cerita Panama',
                'page_id' => $aboutPage?->id,
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        MenuItem::updateOrCreate(
            ['type' => 'catalog'],
            [
                'type' => 'catalog',
                'label' => 'Menu & Sajian',
                'sort_order' => 3,
                'is_active' => true,
            ]
        );

        MenuItem::updateOrCreate(
            ['type' => 'articles'],
            [
                'type' => 'articles',
                'label' => 'Jurnal',
                'sort_order' => 4,
                'is_active' => true,
            ]
        );

        MenuItem::where('type', 'custom')->where('url', '/menu')->delete();

        MenuItem::updateOrCreate(
            ['type' => 'page', 'page_id' => $locationPage?->id],
            [
                'type' => 'page',
                'label' => 'Lokasi',
                'page_id' => $locationPage?->id,
                'sort_order' => 5,
                'is_active' => true,
            ]
        );

        MenuItem::updateOrCreate(
            ['type' => 'contact'],
            [
                'type' => 'contact',
                'label' => 'Kontak',
                'sort_order' => 6,
                'is_active' => true,
            ]
        );
    }
}
