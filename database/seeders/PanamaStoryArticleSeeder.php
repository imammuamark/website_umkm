<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PanamaStoryArticleSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $category = ArticleCategory::updateOrCreate(
                ['slug' => 'cerita-panama'],
                [
                    'name' => 'Cerita Panama',
                    'description' => 'Cerita, suasana, dan informasi terbaru dari Panama Corner.',
                ],
            );

            $author = User::where('email', 'editor@panamacorner.com')->first()
                ?? User::where('email', 'admin@panamacorner.com')->firstOrFail();

            $article = Article::updateOrCreate(
                ['slug' => 'panama-corner-ruang-singgah-untuk-berbincang-dan-menikmati-waktu'],
                [
                    'category_id' => $category->id,
                    'author_id' => $author->id,
                    'published_by' => $author->id,
                    'title' => 'Kenalan dengan Panama Corner di Condongcatur',
                    'excerpt' => 'Lihat lokasi, pilihan menu, fasilitas, dan jam buka Panama Corner di Condongcatur.',
                    'content' => <<<'HTML'
                        <p>Panama Corner adalah kafe di Condongcatur, Sleman, dengan pilihan makanan, camilan, kopi, dan minuman nonkopi. Pengunjung dapat datang untuk makan, bekerja ringan, atau berkumpul bersama teman.</p>

                        <h2>Tempat yang Fleksibel</h2>
                        <p>Area duduk terbuka dapat digunakan untuk berbagai kebutuhan, dari makan siang dan mengerjakan tugas hingga bertemu teman dalam kelompok kecil.</p>
                        <p>Lokasinya berada di kawasan Condongcatur dan mudah ditemukan melalui petunjuk arah yang tersedia di halaman lokasi.</p>

                        <h2>Cocok untuk Berbagai Kegiatan</h2>
                        <p>Pengunjung dapat memilih meja sesuai kebutuhan. Datang sendiri, mengerjakan tugas, rapat singkat, atau makan bersama tetap nyaman dilakukan di area yang tersedia.</p>
                        <blockquote>Cek menu dan jam buka sebelum datang agar kunjungan lebih praktis.</blockquote>

                        <h2>Pilihan Menu</h2>
                        <p>Menu Panama Corner terdiri dari makanan, camilan, kopi, dan minuman nonkopi. Informasi harga dan ketersediaan dapat diperiksa melalui halaman Menu &amp; Sajian.</p>
                        <p>Daftar menu dan informasi harga dapat dilihat melalui halaman Menu &amp; Sajian sebelum berkunjung.</p>

                        <h2>Rencanakan Waktu Berkunjung</h2>
                        <p>Panama Corner berlokasi di Jl. Mancasan Indah III No.1, Ngringin, Condongcatur, Kecamatan Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281.</p>
                        <p>Kafe buka setiap hari dengan jam operasional yang berbeda pada akhir pekan. Senin sampai Jumat buka pukul 10.00–22.00, Sabtu pukul 10.00–14.00, dan Minggu pukul 18.00–22.00. Untuk memastikan informasi terbaru sebelum datang, pengunjung dapat menghubungi Panama Corner melalui nomor 0878-7394-1422.</p>
                        HTML,
                    'editor_mode' => 'visual',
                    'meta_title' => 'Kenalan dengan Panama Corner di Condongcatur',
                    'meta_description' => 'Informasi lokasi, menu, fasilitas, dan jam buka Panama Corner di Condongcatur, Sleman.',
                    'status' => 'published',
                    'workflow_status' => 'published',
                    'published_at' => now(),
                ],
            );

            $imagePath = database_path('seeders/assets/articles/ruang-singgah-panama-corner.jpg');

            if (is_file($imagePath)) {
                $article->clearMediaCollection('featured_image');
                $article->addMedia($imagePath)
                    ->preservingOriginal()
                    ->usingName('Suasana ruang singgah Panama Corner')
                    ->usingFileName('ruang-singgah-panama-corner.jpg')
                    ->withCustomProperties([
                        'alt' => 'Pengunjung berbincang di area duduk terbuka Panama Corner',
                    ])
                    ->toMediaCollection('featured_image');
            }
        });
    }
}
