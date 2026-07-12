<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 2. Product Categories
        $beans = ProductCategory::updateOrCreate(
            ['name' => 'Biji Kopi Premium'],
            ['slug' => 'biji-kopi-premium', 'description' => 'Koleksi biji kopi single origin arabika dan robusta pilihan terbaik dari perkebunan nusantara.']
        );

        $bottled = ProductCategory::updateOrCreate(
            ['name' => 'Kopi Kemasan Botol'],
            ['slug' => 'kopi-kemasan-botol', 'description' => 'Kopi susu signature dan cold brew segar siap minum untuk menemani hari Anda.']
        );

        $gear = ProductCategory::updateOrCreate(
            ['name' => 'Alat Seduh Kopi'],
            ['slug' => 'alat-seduh-kopi', 'description' => 'Peralatan menyeduh kopi manual pilihan untuk hasil ekstraksi maksimal di rumah.']
        );

        // 3. Products
        Product::updateOrCreate(
            ['name' => 'Arabika Gayo Single Origin 250g'],
            [
                'slug' => 'arabika-gayo-single-origin-250g',
                'category_id' => $beans->id,
                'description' => 'Biji Kopi Arabika Gayo kami ditanam pada ketinggian 1400 MDPL di dataran tinggi Gayo, Aceh. Memiliki profil rasa yang kompleks dengan keasaman (acidity) medium yang cerah, bodi tebal, serta notes aroma herbal, cokelat hitam, dan sentuhan karamel manis di akhir sesapan. Dipanggang segar dengan profil Medium Roast, sangat cocok untuk seduhan V60 maupun Espresso.',
                'price' => 85000.00,
                'stock_status' => 'tersedia',
                'is_featured' => true,
                'is_bestseller' => true,
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Robusta Temanggung Gourmet 250g'],
            [
                'slug' => 'robusta-temanggung-gourmet-250g',
                'category_id' => $beans->id,
                'description' => 'Biji Kopi Robusta Temanggung pilihan yang diolah secara presisi. Menyajikan bodi yang sangat kuat, tingkat kepahitan yang bersih dan menyenangkan, serta aroma nutty (kacang-kacangan) dan cokelat kental (dark chocolate). Sangat ideal bagi penggemar kopi susu kekinian maupun sebagai campuran blend espresso yang seimbang.',
                'price' => 60000.00,
                'stock_status' => 'tersedia',
                'is_featured' => false,
                'is_bestseller' => true,
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Signature Cold Brew Coffee 250ml'],
            [
                'slug' => 'signature-cold-brew-coffee-250ml',
                'category_id' => $bottled->id,
                'description' => 'Kopi seduh dingin (cold brew) yang diekstraksi selama 18 jam menggunakan air dingin steril dengan biji kopi arabika blend khusus. Menghasilkan cita rasa kopi yang sangat lembut, keasaman rendah yang ramah lambung, dengan sensasi rasa fruity alami dan manis karamel tanpa pemanis buatan. Simpan dalam kulkas, nikmati dalam keadaan dingin.',
                'price' => 30000.00,
                'stock_status' => 'tersedia',
                'is_featured' => true,
                'is_bestseller' => false,
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Kopi Susu Gula Aren Signature 1L'],
            [
                'slug' => 'kopi-susu-gula-aren-signature-1l',
                'category_id' => $bottled->id,
                'description' => 'Kopi susu andalan kami dalam ukuran botol 1 Liter, dibuat segar berdasarkan pesanan. Perpaduan espresso robusta-arabika berkualitas, susu segar creamy, dan gula aren organik cair murni. Manisnya pas, rasa kopinya tetap dominan dan memanjakan lidah. Cocok untuk dinikmati bersama keluarga atau teman kantor.',
                'price' => 75000.00,
                'stock_status' => 'pre-order',
                'is_featured' => false,
                'is_bestseller' => true,
            ]
        );

        Product::updateOrCreate(
            ['name' => 'V60 Drip Glass Decanter Kit'],
            [
                'slug' => 'v60-drip-glass-decanter-kit',
                'category_id' => $gear->id,
                'description' => 'Satu set alat seduh kopi manual V60 berbahan kaca borosilikat tahan panas yang elegan. Paket penjualan sudah mencakup V60 Glass Dripper ukuran 02, Server Kaca (Decanter) kapasitas 500ml dengan grip kayu pelindung panas, sendok takar kopi, dan 10 lembar kertas saring berkualitas tinggi.',
                'price' => 245000.00,
                'stock_status' => 'tersedia',
                'is_featured' => true,
                'is_bestseller' => false,
            ]
        );

        // 4. Article Categories
        $guide = ArticleCategory::updateOrCreate(
            ['name' => 'Tips Menyeduh'],
            ['slug' => 'tips-menyeduh', 'description' => 'Panduan menyeduh kopi manual di rumah dengan berbagai metode dan teknik standar barista.']
        );

        $edu = ArticleCategory::updateOrCreate(
            ['name' => 'Edukasi Kopi'],
            ['slug' => 'edukasi-kopi', 'description' => 'Informasi menarik seputar jenis kopi, proses pasca-panen, tingkat pemanggangan, dan budaya kopi.']
        );

        // 5. Articles
        $admin = User::where('email', 'admin@panamacorner.com')->first();
        $authorId = $admin ? $admin->id : 1;

        Article::updateOrCreate(
            ['title' => 'Panduan Lengkap Menyeduh Kopi V60 Anti Gagal untuk Pemula'],
            [
                'slug' => 'panduan-lengkap-menyeduh-kopi-v60-anti-gagal-untuk-pemula',
                'category_id' => $guide->id,
                'author_id' => $authorId,
                'content' => '<p>Metode tuang (pour over) menggunakan V60 adalah salah satu cara paling populer untuk menikmati kopi single origin. Metode ini sangat baik dalam mengekstraksi aroma floral dan fruity yang halus pada kopi Arabika. Berikut adalah langkah-langkah mudah untuk menyeduh V60 yang seimbang di rumah:</p><h3>1. Persiapkan Rasio dan Suhu Air</h3><p>Gunakan rasio seduh 1:15 (misalnya 15 gram kopi untuk 225 ml air). Pastikan suhu air ideal berada di kisaran 90-93 derajat Celsius. Air mendidih yang didiamkan sekitar 1 menit biasanya mencapai suhu ini.</p><h3>2. Basahi Kertas Saring (Rinsing)</h3><p>Letakkan kertas saring pada dripper V60 dan bilas dengan air panas. Langkah ini penting untuk menghilangkan rasa kertas (papery taste) dan menghangatkan dripper serta server Anda. Buang air bilasan dari wadah penampung.</p><h3>3. Proses Pemekaran (Blooming)</h3><p>Masukkan kopi gilingan medium-coarse, lalu ratakan. Tuangkan sekitar 30-40 ml air panas secara perlahan ke seluruh permukaan kopi. Diamkan selama 30-40 detik. Proses ini melepaskan gas karbon dioksida terperangkap di dalam biji kopi (blooming), sehingga ekstraksi rasa berikutnya menjadi lebih optimal.</p><h3>4. Tuangan Utama</h3><p>Tuang sisa air dalam 2 atau 3 tahap tuangan secara spiral melingkar dari tengah ke arah luar (tanpa mengenai dinding kertas saring). Usahakan aliran air stabil dan konstan. Seluruh proses penyeduhan idealnya selesai dalam waktu 2 menit 30 detik hingga 3 menit.</p><p>Selamat mencoba! Eksperimenlah dengan tingkat kehalusan gilingan Anda untuk menemukan profil rasa terbaik.</p>',
                'excerpt' => 'Pelajari langkah-langkah mudah menyeduh kopi menggunakan metode V60 pour over untuk menghasilkan rasa kopi yang bersih, manis, dan kaya rasa.',
                'meta_title' => 'Cara Seduh Kopi V60 Rumahan Terbaik | Panduan V60 Pemula',
                'meta_description' => 'Ingin menyeduh kopi V60 yang manis dan tidak terlalu pahit? Simak panduan praktis BARISTA Panama Corner di sini.',
                'workflow_status' => 'published',
                'status' => 'published',
                'published_at' => now(),
                'reading_time' => 3,
            ]
        );

        Article::updateOrCreate(
            ['title' => 'Mengenal Proses Pasca-Panen Kopi: Wash, Honey, dan Natural'],
            [
                'slug' => 'mengenal-proses-pasca-panen-kopi-wash-honey-dan-natural',
                'category_id' => $edu->id,
                'author_id' => $authorId,
                'content' => '<p>Pernahkah Anda melihat tulisan "Full Wash", "Honey Process", atau "Natural" pada kemasan kopi pilihan Anda? Istilah-istilah ini merujuk pada metode pemrosesan buah kopi (cherry) setelah dipetik hingga menjadi biji kopi kering (green bean). Proses ini memiliki pengaruh yang luar biasa besar terhadap hasil akhir cita rasa kopi di cangkir Anda.</p><h3>1. Washed / Wet Process</h3><p>Pada proses washed, seluruh kulit luar dan daging buah kopi dihilangkan menggunakan mesin pengupas (pulper) sebelum biji kopi Difermentasikan dan dicuci bersih menggunakan air melimpah. Kopi yang dihasilkan melalui proses washed cenderung memiliki karakter rasa yang sangat bersih (clean cup), keasaman yang cerah dan tajam (bright acidity), serta bodi yang lebih ringan. Kopi washed menonjolkan karakter rasa asli bawaan biji kopi itu sendiri.</p><h3>2. Natural / Dry Process</h3><p>Metode natural adalah metode tertua dan paling sederhana. Buah kopi utuh yang baru dipetik langsung dijemur di bawah sinar matahari hingga kering kerontang. Selama penjemuran, biji kopi menyerap rasa manis dan sari buah dari daging buah yang mengering. Hasilnya, kopi proses natural memiliki aroma buah yang sangat kuat (fruity), bodi yang tebal dan creamy, serta keasaman rendah yang manis.</p><h3>3. Honey Process</h3><p>Meskipun namanya "honey", proses ini sama sekali tidak menggunakan madu. Kulit luar buah kopi dikupas, tetapi sebagian daging buah lengket (mucilage) dibiarkan tetap melekat pada biji kopi selama proses penjemuran. Mucilage yang lengket ini berwarna kuning kecokelatan seperti madu saat mengering. Kopi proses honey menawarkan jalan tengah: keasaman yang manis berpadu dengan bodi yang cukup tebal dan kebersihan rasa yang seimbang.</p><p>Kini Anda tahu perbedaannya. Manakah profil rasa proses pasca-panen yang paling sesuai dengan selera Anda?</p>',
                'excerpt' => 'Ketahui bagaimana proses pengolahan pasca-panen biji kopi mempengaruhi rasa akhir kopi Anda. Perbedaan mendasar proses wash, natural, dan honey.',
                'meta_title' => 'Pengaruh Proses Pasca Panen Kopi Terhadap Rasa | Wash vs Honey vs Natural',
                'meta_description' => 'Apakah perbedaan rasa kopi proses wash, honey, dan natural? Temukan jawabannya agar Anda tidak salah memilih biji kopi favorit.',
                'workflow_status' => 'published',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'reading_time' => 4,
            ]
        );
    }
}
