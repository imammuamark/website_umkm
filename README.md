# Panama Corner — Website Profil & Katalog UMKM

Website company profile, katalog produk, jurnal edukasi, lokasi cabang, dan lead capture untuk UMKM Panama Corner. Aplikasi dilengkapi CMS berbasis Filament agar konten, pengguna, tampilan, SEO, serta integrasi pemasaran dapat dikelola tanpa mengubah kode.

## Fitur Utama

### Website publik

- Homepage responsif dengan katalog unggulan dan artikel terbaru
- Profil usaha, visi–misi, legalitas, dan informasi brand
- Katalog produk dengan pencarian, kategori, rentang harga, sorting, dan quick view
- Detail produk, galeri, rekomendasi, dan pemesanan melalui WhatsApp
- Jurnal/artikel dengan kategori, pencarian, metadata SEO, dan artikel terkait
- Direktori lokasi, jam operasional, peta, telepon, dan petunjuk arah
- Form kontak dengan validasi, honeypot, rate limiting, serta pengelolaan leads
- Theme customizer, structured data, Open Graph, dan integrasi analytics

### Admin dashboard

- Dashboard berbasis Filament
- CRUD profil usaha, produk, kategori, artikel, lokasi, dan pesan masuk
- Media library dengan image conversion
- Pengelolaan pengguna, role, dan permission
- Penggantian password dan autentikasi dua faktor
- Activity log
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

Untuk SQLite:

```bash
touch database/database.sqlite
php artisan migrate --seed
```

Bangun aset dan jalankan aplikasi:

```bash
npm run build
php artisan serve
```

Aplikasi dapat diakses melalui `http://127.0.0.1:8000`, sedangkan dashboard tersedia di `/admin`.

Untuk menjalankan server aplikasi, queue, log viewer, dan Vite secara bersamaan:

```bash
composer run dev
```

## Konfigurasi Penting

Pastikan nilai berikut dikonfigurasi sesuai lingkungan:

```dotenv
APP_NAME="Panama Corner"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=panama_corner
DB_USERNAME=database_user
DB_PASSWORD=strong_database_password

SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

Jangan menyimpan `.env`, token, password, backup, atau kredensial produksi ke repository.

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

Test suite mencakup route publik, security headers, form kontak, honeypot, akses admin, penanganan data profil parsial, dan validasi embed Google Maps.

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
└── seeders/           # Data awal pengembangan

resources/
├── css/               # Tailwind dan design system
├── js/                # Alpine.js entry point
└── views/             # Blade templates publik dan admin

tests/
└── Feature/           # Pengujian route dan keamanan
```

## Dokumentasi Produk

Kebutuhan produk dan roadmap tersedia di [`prd_website_umkm.md`](prd_website_umkm.md).

## Lisensi

Proyek menggunakan komponen open-source dengan lisensinya masing-masing. Tentukan lisensi distribusi aplikasi ini sebelum penggunaan atau distribusi publik.
