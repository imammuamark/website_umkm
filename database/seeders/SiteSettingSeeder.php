<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'theme_primary_color' => '#0F766E', // Teal 700 default
            'theme_secondary_color' => '#F59E0B', // Amber 500 default
            'theme_font_title' => 'Plus Jakarta Sans',
            'theme_font_body' => 'Inter',
            
            // Contacts
            'whatsapp_number' => '6281234567890',
            'whatsapp_text_template' => 'Halo Admin Aromatica Coffee, saya ingin bertanya/memesan produk: {product_name}',
            'facebook_url' => 'https://facebook.com/aromaticacoffee',
            'instagram_url' => 'https://instagram.com/aromaticacoffee',
            'tiktok_url' => 'https://tiktok.com/@aromaticacoffee',
            'email_address' => 'info@aromaticacoffee.com',
            'office_phone' => '+62 22 1234567',
            
            // SEO & Integrations
            'meta_title_default' => 'Aromatica Coffee | Artisan Coffee Roaster Bandung',
            'meta_description_default' => 'Biji Kopi Arabika dan Robusta Pilihan Nusantara Dipanggang Segar Secara Artisan di Bandung.',
            'google_analytics_id' => 'G-XXXXXXXXXX',
            'meta_pixel_id' => 'XXXXXXXXXXXXXXX',
            'tiktok_pixel_id' => 'XXXXXXXXXXXXXXXX',
            'google_maps_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.835848970984!2d107.61864197499641!3d-6.9101968930892285!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e63a152e0081%3A0x67db238a8e3230a1!2sBandung%20Indah%20Plaza!5e0!3m2!1sid!2sid!4v1719876543210!5m2!1sid!2sid" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::set($key, $value);
        }
    }
}
