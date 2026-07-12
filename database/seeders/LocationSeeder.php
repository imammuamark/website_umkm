<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            Location::query()->delete();

            Location::create([
                'name' => 'Panama Corner',
                'address' => 'Jl. Mancasan Indah III No.1, Ngringin, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281',
                'latitude' => null,
                'longitude' => null,
                'phone' => '0878-7394-1422',
                'operating_hours' => [
                    'Senin' => '10.00–22.00',
                    'Selasa' => '10.00–22.00',
                    'Rabu' => '10.00–22.00',
                    'Kamis' => '10.00–22.00',
                    'Jumat' => '10.00–22.00',
                    'Sabtu' => '10.00–14.00',
                    'Minggu' => '18.00–22.00',
                ],
            ]);
        });
    }
}
