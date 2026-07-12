<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->replaceSetting('hero_title', 'Singgah, Bersantap, dan Nikmati Waktumu.', 'Makan enak, ngopi nyaman.');
        $this->replaceSetting(
            'hero_subtitle',
            'Temukan pilihan makanan, camilan, kopi, dan minuman nonkopi untuk menemani waktu santai maupun berkumpul di Panama Corner.',
            'Pilihan makanan, camilan, kopi, dan minuman nonkopi untuk makan santai, bekerja, atau berkumpul bersama.'
        );
        $this->replaceSetting(
            'footer_description',
            'Kafe di Condongcatur dengan pilihan makanan, camilan, kopi, dan minuman nonkopi untuk menemani waktu singgah Anda.',
            'Kafe di Condongcatur dengan pilihan makanan, camilan, kopi, dan minuman nonkopi.'
        );

        DB::table('business_profile')
            ->where('description', 'Panama Corner adalah kafe di kawasan Condongcatur, Sleman, yang menghadirkan pilihan makanan, camilan, kopi, dan minuman nonkopi. Kami ingin menjadi tempat singgah yang mudah dijangkau untuk menikmati waktu sendiri, berbincang, maupun berkumpul bersama.')
            ->update(['description' => 'Panama Corner adalah kafe di Condongcatur, Sleman, dengan pilihan makanan, camilan, kopi, dan minuman nonkopi. Tempatnya cocok untuk makan, bekerja ringan, atau berkumpul bersama teman.']);

        DB::table('business_profile')
            ->where('vision', 'Menjadi ruang singgah yang dekat dengan keseharian pelanggan melalui sajian yang mudah dinikmati dan pelayanan yang hangat.')
            ->update(['vision' => 'Menyediakan menu yang enak, pelayanan yang ramah, dan tempat yang nyaman untuk berbagai kegiatan.']);

        DB::table('business_profile')
            ->where('mission', "Menyajikan pilihan makanan dan minuman secara konsisten.\nMemberikan pelayanan yang ramah dan responsif.\nMenjaga ruang yang nyaman untuk singgah dan berkumpul.\nTerus memperbaiki menu berdasarkan kebutuhan pelanggan.")
            ->update(['mission' => "Menjaga rasa dan kualitas setiap menu.\nMemberikan pelayanan yang ramah dan responsif.\nMenjaga area kafe tetap bersih dan nyaman.\nMemperbarui menu berdasarkan masukan pelanggan."]);

        DB::table('pages')->where('slug', 'tentang-kopi')->update([
            'subtitle' => 'Kenali Panama Corner, menu yang tersedia, dan fasilitasnya di Condongcatur.',
            'content' => '<p>Panama Corner adalah kafe di kawasan Condongcatur, Sleman. Kami menyediakan makanan, camilan, kopi, dan minuman nonkopi dengan harga yang mudah dilihat melalui katalog digital.</p><p>Area duduk dapat digunakan untuk makan, bekerja ringan, atau berkumpul. Kami terus memperbaiki menu dan pelayanan berdasarkan masukan pelanggan.</p><p>Panama Corner berlokasi di Jl. Mancasan Indah III No.1, Ngringin, Condongcatur, Depok, Sleman, Daerah Istimewa Yogyakarta.</p>',
        ]);

        DB::table('pages')->where('slug', 'tentang-panama')->update([
            'eyebrow' => 'Tentang Kami',
            'subtitle' => 'Informasi singkat tentang Panama Corner, menu, dan cara kami melayani pelanggan.',
            'content' => '<p>Panama Corner adalah kafe di Condongcatur, Sleman, yang menyediakan makanan, camilan, kopi, dan minuman nonkopi.</p><p>Kami menjaga rasa menu, kebersihan tempat, dan pelayanan agar pelanggan dapat makan, bekerja ringan, atau berkumpul dengan nyaman.</p>',
        ]);

        DB::table('articles')
            ->where('slug', 'panama-corner-ruang-singgah-untuk-berbincang-dan-menikmati-waktu')
            ->update([
                'title' => 'Kenalan dengan Panama Corner di Condongcatur',
                'excerpt' => 'Lihat lokasi, pilihan menu, fasilitas, dan jam buka Panama Corner di Condongcatur.',
                'content' => '<p>Panama Corner adalah kafe di Condongcatur, Sleman, dengan pilihan makanan, camilan, kopi, dan minuman nonkopi. Pengunjung dapat datang untuk makan, bekerja ringan, atau berkumpul bersama teman.</p><h2>Tempat yang Fleksibel</h2><p>Area duduk terbuka dapat digunakan untuk berbagai kebutuhan, dari makan siang dan mengerjakan tugas hingga bertemu teman dalam kelompok kecil.</p><p>Lokasinya berada di kawasan Condongcatur dan mudah ditemukan melalui petunjuk arah yang tersedia di halaman lokasi.</p><h2>Cocok untuk Berbagai Kegiatan</h2><p>Pengunjung dapat memilih meja sesuai kebutuhan. Datang sendiri, mengerjakan tugas, rapat singkat, atau makan bersama tetap nyaman dilakukan di area yang tersedia.</p><blockquote>Cek menu dan jam buka sebelum datang agar kunjungan lebih praktis.</blockquote><h2>Pilihan Menu</h2><p>Menu Panama Corner terdiri dari makanan, camilan, kopi, dan minuman nonkopi. Informasi harga dan ketersediaan dapat diperiksa melalui halaman Menu &amp; Sajian.</p><h2>Alamat dan Jam Buka</h2><p>Panama Corner berlokasi di Jl. Mancasan Indah III No.1, Ngringin, Condongcatur, Kecamatan Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281.</p><p>Senin sampai Jumat buka pukul 10.00–22.00, Sabtu pukul 10.00–14.00, dan Minggu pukul 18.00–22.00. Hubungi 0878-7394-1422 untuk memastikan informasi terbaru.</p>',
                'meta_title' => 'Kenalan dengan Panama Corner di Condongcatur',
                'meta_description' => 'Informasi lokasi, menu, fasilitas, dan jam buka Panama Corner di Condongcatur, Sleman.',
            ]);

        if (DB::getSchemaBuilder()->hasTable('digital_menu_settings')) {
            DB::table('digital_menu_settings')
                ->where('subtitle', 'Pilih makanan, camilan, kopi, dan minuman untuk menemani waktu Anda.')
                ->update(['subtitle' => 'Lihat pilihan makanan, camilan, kopi, dan minuman yang tersedia.']);
        }
    }

    public function down(): void
    {
        // Copy changes are intentionally not reverted to avoid restoring outdated public wording.
    }

    private function replaceSetting(string $key, string $oldValue, string $newValue): void
    {
        DB::table('site_settings')
            ->where('key', $key)
            ->where('value', $oldValue)
            ->update(['value' => $newValue]);
    }
};
