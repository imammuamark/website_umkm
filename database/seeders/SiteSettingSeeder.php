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
            'whatsapp_number' => '6287873941422',
            'whatsapp_text_template' => 'Halo Panama Corner, saya ingin menanyakan menu {product_name}.',
            'facebook_url' => '',
            'instagram_url' => '',
            'tiktok_url' => '',
            'email_address' => '',
            'office_phone' => '0878-7394-1422',

            // SEO & Integrations
            'meta_title_default' => 'Panama Corner | Kafe di Condongcatur, Sleman',
            'meta_description_default' => 'Panama Corner menghadirkan pilihan makanan, camilan, kopi, dan minuman nonkopi di Condongcatur, Sleman.',
            'google_analytics_id' => 'G-XXXXXXXXXX',
            'meta_pixel_id' => 'XXXXXXXXXXXXXXX',
            'tiktok_pixel_id' => 'XXXXXXXXXXXXXXXX',
            'google_maps_embed' => 'https://www.google.com/maps?q=Panama%20Corner%2C%20Jl.%20Mancasan%20Indah%20III%20No.1%2C%20Condongcatur%2C%20Sleman&output=embed',
            'hero_image_url' => 'https://images.unsplash.com/photo-1511537190424-bbbab87ac5eb?q=80&w=1200&auto=format&fit=crop',
            'hero_image_upload' => null,
            'hero_image_source' => 'url',
            'hero_title' => 'Makan enak, ngopi nyaman.',
            'hero_subtitle' => 'Pilihan makanan, camilan, kopi, dan minuman nonkopi untuk makan santai, bekerja, atau berkumpul bersama.',
            'catalog_hero_source' => 'url',
            'catalog_hero_url' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?q=85&w=2000&auto=format&fit=crop',
            'catalog_hero_alt' => 'Pilihan menu dan sajian Panama Corner',
            'articles_hero_source' => 'url',
            'articles_hero_url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=85&w=2000&auto=format&fit=crop',
            'articles_hero_alt' => 'Proses menyeduh kopi untuk jurnal dan artikel',
            'contact_hero_source' => 'url',
            'contact_hero_url' => 'https://images.unsplash.com/photo-1521017432531-fbd92d768814?q=85&w=2000&auto=format&fit=crop',
            'contact_hero_alt' => 'Suasana hangat kedai kopi untuk menghubungi tim',
            'theme_favicon_upload' => null,
            'enable_whatsapp_order' => '1',
            'show_whatsapp_fab' => '1',
            'footer_description' => 'Kafe di Condongcatur dengan pilihan makanan, camilan, kopi, dan minuman nonkopi.',
            'footer_show_socials' => '1',
            'footer_show_navigation' => '1',
            'footer_navigation_title' => 'Navigasi',
            'footer_show_legal' => '1',
            'footer_legal_title' => 'Informasi Legal',
            'footer_legal_limit' => '3',
            'footer_show_contact' => '1',
            'footer_contact_title' => 'Kontak',
            'footer_address' => 'Jl. Mancasan Indah III No.1, Ngringin, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281',
            'footer_cta_enabled' => '0',
            'footer_cta_title' => 'Ada yang ingin ditanyakan?',
            'footer_cta_description' => 'Hubungi tim kami untuk informasi menu dan pemesanan.',
            'footer_cta_button_label' => 'Hubungi Kami',
            'footer_cta_button_url' => '/kontak',
            'footer_copyright' => '© {year} {business_name}. Hak cipta dilindungi.',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::set($key, $value);
        }
    }
}
