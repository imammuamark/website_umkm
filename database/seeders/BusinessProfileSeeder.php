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
                'description' => 'Panama Corner adalah penyedia kopi spesialti premium yang berfokus pada kurasi biji kopi kualitas tertinggi dan pengalaman seduh kopi modern. Kami menghadirkan biji kopi artisan pilihan dari perkebunan terbaik, diracik dengan dedikasi tinggi untuk menghadirkan keunikan profil rasa orisinal di setiap cangkir Anda.',
                'vision' => 'Menjadi pelopor produk kopi artisan lokal terbaik yang diakui secara nasional maupun internasional, serta berkontribusi nyata pada kesejahteraan petani kopi Indonesia.',
                'mission' => '1. Menyediakan biji kopi arabika dan robusta pilihan dengan proses kurasi yang ketat. 2. Menerapkan teknik pemanggangan modern untuk menghasilkan profil rasa kopi yang konsisten dan premium. 3. Membangun kemitraan yang adil dan berkelanjutan bersama petani kopi lokal. 4. Mengedukasi masyarakat luas tentang keragaman rasa kopi asli Indonesia.',
                'logo' => null, // Managed via media library
                'founded_year' => 2021,
                'legal_docs' => [
                    [
                        'name' => 'Nomor Induk Berusaha (NIB)',
                        'number' => '1209831920831',
                        'issuer' => 'Kementerian Investasi/BKPM',
                        'year' => '2021'
                    ],
                    [
                        'name' => 'Sertifikat Halal Indonesia',
                        'number' => 'ID32110000293810822',
                        'issuer' => 'BPJPH Kementerian Agama',
                        'year' => '2022'
                    ],
                    [
                        'name' => 'P-IRT Dinas Kesehatan',
                        'number' => '5103273010452-26',
                        'issuer' => 'Dinas Kesehatan Kota Bandung',
                        'year' => '2021'
                    ]
                ]
            ]
        );
    }
}
