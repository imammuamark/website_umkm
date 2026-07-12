<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::updateOrCreate(
            ['slug' => 'tentang-kopi'],
            [
                'title' => 'Cerita Panama',
                'template' => 'about',
                'eyebrow' => 'Tentang Panama Corner',
                'subtitle' => 'Kenali Panama Corner, menu yang tersedia, dan fasilitasnya di Condongcatur.',
                'hero_source' => 'url',
                'hero_image_url' => 'https://images.unsplash.com/photo-1442512595331-e89e73853f31?q=85&w=2000&auto=format&fit=crop',
                'hero_alt' => 'Suasana kedai kopi yang hangat',
                'hero_credit' => 'Foto dari Unsplash',
                'hero_credit_url' => 'https://unsplash.com/s/photos/coffee-plantation?utm_source=website_umkm&utm_medium=referral',
                'about_values_title' => 'Yang Kami Jaga',
                'about_primary_label' => 'Tujuan Kami',
                'about_secondary_label' => 'Cara Kami Melayani',
                'content' => '<p>Panama Corner adalah kafe di kawasan Condongcatur, Sleman. Kami menyediakan makanan, camilan, kopi, dan minuman nonkopi dengan harga yang mudah dilihat melalui katalog digital.</p><p>Area duduk dapat digunakan untuk makan, bekerja ringan, atau berkumpul. Kami terus memperbaiki menu dan pelayanan berdasarkan masukan pelanggan.</p><p>Panama Corner berlokasi di Jl. Mancasan Indah III No.1, Ngringin, Condongcatur, Depok, Sleman, Daerah Istimewa Yogyakarta.</p>',
                'meta_title' => 'Cerita Panama Corner | Kafe di Condongcatur',
                'meta_description' => 'Kenali Panama Corner, kafe di Condongcatur, Sleman, dengan pilihan makanan, camilan, kopi, dan minuman nonkopi.',
                'status' => 'published',
                'is_in_navigation' => true,
                'sort_order' => 1,
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'lokasi'],
            [
                'title' => 'Lokasi Kami',
                'template' => 'locations',
                'eyebrow' => 'Temui Kami',
                'subtitle' => 'Temukan alamat, kontak, dan jam operasional Panama Corner.',
                'hero_source' => 'url',
                'hero_image_url' => 'https://images.unsplash.com/photo-1445116572660-236099ec97a0?q=85&w=2000&auto=format&fit=crop',
                'hero_alt' => 'Interior kedai kopi modern dan hangat',
                'hero_credit' => 'Foto dari Unsplash',
                'hero_credit_url' => 'https://unsplash.com/s/photos/coffee-shop?utm_source=website_umkm&utm_medium=referral',
                'content' => '',
                'status' => 'published',
                'is_in_navigation' => true,
                'sort_order' => 4,
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'tentang-panama'],
            [
                'title' => 'Profil Panama Corner',
                'template' => 'about',
                'eyebrow' => 'Tentang Kami',
                'subtitle' => 'Informasi singkat tentang Panama Corner, menu, dan cara kami melayani pelanggan.',
                'hero_source' => 'url',
                'hero_image_url' => 'https://images.unsplash.com/photo-1511537190424-bbbab87ac5eb?q=85&w=2000&auto=format&fit=crop',
                'hero_credit' => 'Foto dari Unsplash',
                'hero_credit_url' => 'https://unsplash.com/?utm_source=website_umkm&utm_medium=referral',
                'about_values_title' => 'Yang Kami Jaga',
                'about_primary_label' => 'Tujuan Kami',
                'about_secondary_label' => 'Cara Kami Melayani',
                'content' => '<p>Panama Corner adalah kafe di Condongcatur, Sleman, yang menyediakan makanan, camilan, kopi, dan minuman nonkopi.</p><p>Kami menjaga rasa menu, kebersihan tempat, dan pelayanan agar pelanggan dapat makan, bekerja ringan, atau berkumpul dengan nyaman.</p>',
                'status' => 'published',
                'is_in_navigation' => false,
                'sort_order' => 5,
                'widgets' => [],
            ]
        );
    }
}
