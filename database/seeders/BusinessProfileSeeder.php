<?php

namespace Database\Seeders;

use App\Models\BusinessProfile;
use Illuminate\Database\Seeder;

class BusinessProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusinessProfile::updateOrCreate(
            ['id' => 1],
            [
                'business_name' => 'Panama Corner',
                'description' => 'Panama Corner adalah kafe di Condongcatur, Sleman, dengan pilihan makanan, camilan, kopi, dan minuman nonkopi. Tempatnya cocok untuk makan, bekerja ringan, atau berkumpul bersama teman.',
                'vision' => 'Menyediakan menu yang enak, pelayanan yang ramah, dan tempat yang nyaman untuk berbagai kegiatan.',
                'mission' => "Menjaga rasa dan kualitas setiap menu.\nMemberikan pelayanan yang ramah dan responsif.\nMenjaga area kafe tetap bersih dan nyaman.\nMemperbarui menu berdasarkan masukan pelanggan.",
                'logo' => null, // Managed via media library
                'founded_year' => null,
                'legal_docs' => [],
            ]
        );
    }
}
