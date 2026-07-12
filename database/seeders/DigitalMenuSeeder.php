<?php

namespace Database\Seeders;

use App\Models\DigitalMenuAccessPoint;
use App\Models\DigitalMenuSetting;
use Illuminate\Database\Seeder;

class DigitalMenuSeeder extends Seeder
{
    public function run(): void
    {
        DigitalMenuSetting::current()->update([
            'title' => 'Menu Panama Corner',
            'subtitle' => 'Lihat pilihan makanan, camilan, kopi, dan minuman yang tersedia.',
            'layout' => 'balanced',
        ]);

        DigitalMenuAccessPoint::firstOrCreate(['label' => 'Menu Umum'], ['type' => 'general', 'is_active' => true]);
    }
}
