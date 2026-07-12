<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            'footer_description' => 'Tempat menikmati pilihan makanan, camilan, dan minuman dalam suasana yang nyaman.',
            'footer_show_socials' => '1', 'footer_show_navigation' => '1', 'footer_navigation_title' => 'Navigasi',
            'footer_show_legal' => '1', 'footer_legal_title' => 'Informasi Legal', 'footer_legal_limit' => '3',
            'footer_show_contact' => '1', 'footer_contact_title' => 'Kontak', 'footer_address' => 'Jl. Merdeka No. 56, Bandung',
            'footer_cta_enabled' => '0', 'footer_cta_title' => 'Ada yang ingin ditanyakan?',
            'footer_cta_description' => 'Hubungi tim kami untuk informasi menu dan pemesanan.',
            'footer_cta_button_label' => 'Hubungi Kami', 'footer_cta_button_url' => '/kontak',
            'footer_copyright' => '© {year} {business_name}. Hak cipta dilindungi.',
        ];

        foreach ($settings as $key => $value) {
            DB::table('site_settings')->updateOrInsert(['key' => $key], ['value' => $value]);
        }
    }

    public function down(): void
    {
        DB::table('site_settings')->where('key', 'like', 'footer_%')->delete();
    }
};
