# Website Profil & Katalog Digital UMKM

Template website company profile, katalog dan menu digital, artikel, lokasi cabang, serta lead capture untuk berbagai jenis UMKM. Aplikasi dilengkapi CMS berbasis Filament agar identitas usaha, konten, pengguna, tampilan, footer, SEO, dan integrasi pemasaran dapat dikelola tanpa mengubah kode.

Template ini dapat disesuaikan untuk usaha kuliner, kerajinan, fesyen, jasa profesional, retail, agribisnis, industri kreatif, dan kebutuhan profil bisnis lainnya.

## Tujuan Template

- Menyediakan fondasi website UMKM yang siap dikembangkan
- Memisahkan pengelolaan konten dari kode aplikasi
- Mendukung identitas brand dan katalog yang berbeda
- Menyediakan alur promosi hingga konversi melalui WhatsApp dan form kontak
- Menjaga struktur aplikasi tetap modular, responsif, dan mudah dipelihara

## Fitur Utama

### Website publik

- Homepage responsif dengan katalog unggulan dan artikel terbaru
- Profil usaha, visi–misi, legalitas, dan informasi brand
- Katalog produk dengan pencarian, kategori, rentang harga, sorting, dan quick view
- Detail produk, galeri, rekomendasi, dan pemesanan melalui WhatsApp
- Mode menu digital yang dapat dibuka melalui tautan atau QR code
- Beberapa mode tampilan menu untuk desktop dan perangkat seluler
- Jurnal/artikel dengan kategori, pencarian, daftar isi, galeri gambar, video, metadata SEO, dan artikel terkait
- Direktori lokasi, jam operasional, peta, telepon, dan petunjuk arah
- Form kontak dengan validasi, honeypot, rate limiting, serta pengelolaan leads
- Halaman CMS dinamis dengan hero upload/URL dan widget konten
- Theme customizer untuk warna, font, hero, favicon, footer, dan preview aktual
- Structured data, Open Graph, dan integrasi analytics

### Admin dashboard

- Dashboard berbasis Filament dengan sistem desain responsif, light/dark mode, dan sidebar collapsible
- Global command search dengan shortcut `⌘K`/`Ctrl+K`, loading state, serta hasil lintas resource
- CRUD profil usaha, produk, kategori, artikel, lokasi, dan pesan masuk
- Galeri multi-gambar produk dengan pengurutan gambar utama
- Editor artikel visual/HTML, workflow editorial, preview, gambar, dan video
- Konfigurasi serta QR code menu digital dalam format siap cetak
- Media library dengan image conversion dan validasi upload
- Pengelolaan pengguna, role, dan permission
- Penggantian password dan autentikasi dua faktor
- Activity log
- Backup dan migrasi portabel untuk data CMS serta media bertahap
- Pengaturan tema, SEO, Google Analytics, Meta Pixel, TikTok Pixel, WhatsApp, dan Google Maps

## Teknologi

- PHP 8.3+
- Laravel 13
- Filament 3
- Laravel Fortify
- Spatie Laravel Permission
- Spatie Media Library
- Tailwind CSS 4
- Alpine.js
- Vite 8
- PHPUnit 12
- SQLite untuk lingkungan pengembangan; database produksi dapat dikonfigurasi melalui `.env`

## Persyaratan Sistem

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- Ekstensi PHP yang dibutuhkan Laravel
- Database SQLite, MySQL, atau PostgreSQL

## Instalasi Lokal

```bash
git clone https://github.com/imammuamark/website_umkm.git
cd website_umkm

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Jika perintah `composer` belum tersedia secara global tetapi repository memiliki `composer.phar`, gunakan:

```bash
php composer.phar install
```

Untuk pengembangan lokal, ubah konfigurasi berikut pada `.env` hasil salinan:

```dotenv
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
SESSION_SECURE_COOKIE=false
```

Untuk SQLite:

```bash
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
```

Bangun aset dan jalankan aplikasi:

```bash
npm run build
php artisan serve
```

Aplikasi dapat diakses melalui `http://127.0.0.1:8000`, dashboard tersedia di `/admin`, dan menu digital tersedia di `/menu` jika fitur tersebut diaktifkan.

Untuk menjalankan server aplikasi, queue, log viewer, dan Vite secara bersamaan:

```bash
composer run dev
```

## Konfigurasi Penting

Pastikan nilai berikut dikonfigurasi sesuai lingkungan:

```dotenv
APP_NAME="Nama Usaha"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=website_umkm
DB_USERNAME=database_user
DB_PASSWORD=strong_database_password

SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

Jangan menyimpan `.env`, token, password, backup, atau kredensial produksi ke repository.

## Kustomisasi untuk UMKM Baru

Setelah instalasi, sesuaikan bagian berikut melalui dashboard admin:

1. Nama usaha, deskripsi, visi, misi, dan tahun berdiri
2. Logo, favicon, warna primer, warna sekunder, dan font
3. Produk, kategori, harga, stok, deskripsi, serta galeri
4. Artikel, kategori artikel, dan metadata SEO
5. Lokasi, nomor telepon, jam operasional, dan Google Maps
6. Nomor WhatsApp dan template pesan pemesanan
7. Email, media sosial, legalitas, serta informasi kontak
8. Google Analytics, Meta Pixel, dan integrasi pemasaran lain jika digunakan
9. Mode tampilan, kategori, access point, dan QR code menu digital
10. Navigasi, halaman CMS, hero setiap halaman, dan footer website

Data contoh dari seeder ditujukan untuk pengembangan. Ganti atau hapus data tersebut sebelum aplikasi digunakan oleh usaha baru.

## Backup & Migrasi Antarserver

Menu **Sistem → Backup & Migrasi** menyediakan alur pemindahan konten dari lingkungan lokal ke server produksi:

1. Unduh satu manifest JSON data CMS dan seluruh paket `.umkm-media`.
2. Siapkan aplikasi tujuan, `.env`, database, migration, akun admin, dan storage link.
3. Masuk dengan akun admin server tujuan lalu periksa checksum manifest.
4. Gunakan mode **Gabungkan** untuk migrasi awal yang aman. Mode **Ganti data CMS** hanya digunakan jika konten tujuan memang boleh diganti.
5. Impor seluruh paket media tanpa membuka atau mengekstraknya, lalu periksa katalog, artikel, halaman, dan menu digital.

Manifest bersifat lintas database dan hanya memuat konten portable. Akun, password, sesi, cache, queue, pesan kontak, activity log, `.env`, `APP_KEY`, token, dan pengaturan yang terindikasi rahasia tidak diekspor. Paket `.umkm-media` menggunakan container ZIP tervalidasi dengan ekstensi khusus agar browser tidak mengekstraknya otomatis. Ukurannya dibatasi sekitar 7 MB per bagian untuk kompatibilitas hosting dengan batas upload rendah; setiap paket memerlukan marker versi dan menjalani validasi path, ekstensi, jumlah, serta ukuran file sebelum dipulihkan.

Simpan hasil ekspor di lokasi privat. Checksum mendeteksi kerusakan atau perubahan file, tetapi bukan pengganti enkripsi maupun tanda tangan digital.

## Pengujian

Jalankan seluruh test:

```bash
composer test
```

Pemeriksaan yang disarankan sebelum commit atau deployment:

```bash
php artisan test
npm run build
./vendor/bin/pint --test
```

Test suite mencakup route publik, security headers, form kontak, honeypot, akses admin, workflow artikel, media, halaman CMS, katalog, menu digital, footer, WhatsApp, serta validasi embed Google Maps.

Antarmuka admin memanfaatkan aset Vite terpisah di `resources/css/admin.css`. Jalankan `npm run build` setelah mengubah desain dashboard agar manifest produksi memuat stylesheet admin terbaru.

## Keamanan

Implementasi keamanan yang tersedia meliputi:

- CSRF protection pada form
- Password hashing bawaan Laravel
- Login rate limiting
- Two-factor authentication
- Role-based access control
- Security headers dan Content Security Policy
- Cookie dan session configuration melalui environment
- Validasi input dan ORM parameter binding
- Honeypot serta throttling form kontak
- Whitelist URL Google Maps tanpa merender HTML admin secara mentah
- Sanitasi HTML artikel dan halaman CMS
- Validasi URL video serta pembatasan provider embed
- Validasi MIME, ukuran, dan jumlah media upload
- Cache menu digital yang dapat diinvalidasi saat data berubah
- Activity log untuk aktivitas administratif

Untuk produksi, HTTPS wajib diaktifkan dan `APP_DEBUG` harus bernilai `false`. Lakukan audit dependency, backup terenkripsi, monitoring, dan security review secara berkala.

## Deployment Produksi

Checklist minimum:

1. Konfigurasikan `.env` produksi dan database.
2. Pasang HTTPS/TLS.
3. Jalankan migration dengan mode force.
4. Bangun aset frontend.
5. Cache konfigurasi, route, dan view.
6. Jalankan queue worker dengan process supervisor.
7. Atur scheduler Laravel melalui cron.
8. Pastikan direktori `storage` dan `bootstrap/cache` dapat ditulis oleh service aplikasi.

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build

php artisan migrate --force
php artisan optimize
php artisan storage:link
```

## Struktur Direktori

```text
app/
├── Filament/          # Resources, pages, dan widgets admin
├── Http/              # Controller dan middleware
├── Models/            # Model Eloquent
└── Policies/          # Otorisasi resource

database/
├── migrations/        # Struktur database
└── seeders/           # Data awal dan aset demo pengembangan

resources/
├── css/               # Tailwind dan design system
├── js/                # Alpine.js entry point
└── views/             # Blade templates publik dan admin

tests/
├── Feature/           # Pengujian integrasi, route, CMS, dan keamanan
└── Unit/              # Pengujian komponen pemrosesan konten
```

## Dokumentasi Produk

Kebutuhan produk dan roadmap tersedia di [`prd_website_umkm.md`](prd_website_umkm.md).

## Lisensi

Proyek menggunakan komponen open-source dengan lisensinya masing-masing. Tentukan lisensi distribusi aplikasi ini sebelum penggunaan atau distribusi publik.
