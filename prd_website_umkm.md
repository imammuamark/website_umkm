# Product Requirements Document (PRD)

## Website Profil & Katalog Digital UMKM

|                    |                            |
| ------------------ | -------------------------- |
| **Versi Dokumen**  | 1.0                        |
| **Tanggal**        | 12 Juli 2026               |
| **Status**         | Draft untuk Review         |
| **Pemilik Produk** | [Diisi oleh pemilik usaha] |

---

## 1. Ringkasan Eksekutif

Website ini dibangun sebagai **media promosi digital profesional** bagi UMKM, yang berfungsi sekaligus sebagai etalase bisnis (company profile), katalog produk interaktif, pusat informasi (artikel/blog), dan kanal akuisisi pelanggan (kontak & lokasi). Produk ini tidak hanya menjadi "brosur digital", tetapi dirancang dengan menerapkan **prinsip manajemen pemasaran** (segmentasi, positioning, marketing mix, customer journey, dan pengukuran performa) agar website benar-benar menghasilkan _leads_ dan penjualan, bukan sekadar informatif.

Dua pilar teknis utama yang menjadi penekanan khusus:

1. **Admin Dashboard yang powerful** — pemilik usaha dapat mengelola seluruh konten, tampilan (termasuk warna tema), dan keamanan akun secara mandiri tanpa bantuan developer.
2. **Keamanan setara standar produksi** — mengikuti praktik industri (OWASP) agar tahan terhadap percobaan peretasan umum.

---

## 2. Latar Belakang & Masalah yang Diselesaikan

UMKM umumnya mengandalkan media sosial sebagai etalase utama, namun menghadapi kendala:

- Informasi produk tersebar dan sulit dicari (hilang di linimasa/story).
- Tidak ada kesan profesional saat calon pelanggan besar (B2B/corporate) mencari referensi.
- Minim jejak SEO sehingga sulit ditemukan di Google Search.
- Tidak ada data terukur (traffic, konversi, produk paling diminati).
- Ketergantungan penuh pada admin media sosial, sulit diwariskan/didelegasikan.

Website ini menjawab masalah tersebut dengan menjadi **pusat kanal (hub)** yang mengarahkan trafik dari media sosial, pencarian Google, dan WhatsApp ke satu tempat yang terkontrol penuh oleh pemilik usaha.

---

## 3. Tujuan Produk (Goals)

| Tujuan                                              | Indikator Keberhasilan (KPI)                                           |
| --------------------------------------------------- | ---------------------------------------------------------------------- |
| Meningkatkan kredibilitas & citra profesional brand | Bounce rate < 50%, rata-rata durasi sesi > 1,5 menit                   |
| Menghasilkan prospek (leads) baru                   | Jumlah klik "Hubungi via WhatsApp" & pengiriman form kontak per bulan  |
| Meningkatkan visibilitas organik (SEO)              | Peringkat kata kunci utama masuk halaman 1 Google dalam 3–6 bulan      |
| Memudahkan pemilik usaha mengelola konten sendiri   | Waktu untuk publish produk/artikel baru < 5 menit tanpa bantuan teknis |
| Menjamin keamanan data usaha & pelanggan            | Nol insiden kebocoran data, lulus uji keamanan dasar (checklist OWASP) |

---

## 4. Target Pengguna & Persona

| Persona                                  | Deskripsi                                                                              | Kebutuhan Utama                                                                                           |
| ---------------------------------------- | -------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------- |
| **Calon Pelanggan (Pengunjung Website)** | Individu/bisnis yang mencari produk/jasa UMKM ini via Google, medsos, atau rekomendasi | Info produk jelas, harga/estimasi, cara pemesanan cepat (WhatsApp), kepercayaan (testimoni, lokasi jelas) |
| **Pemilik Usaha (Admin Utama)**          | Pemilik UMKM, awam teknis, mengelola sendiri kontennya                                 | Dashboard mudah dipahami, tidak perlu coding, tampilan bisa disesuaikan brand                             |
| **Staf/Admin Konten**                    | Karyawan yang membantu update produk & artikel                                         | Akses terbatas sesuai peran (role-based), tidak bisa mengubah setting sensitif                            |
| **Mesin Pencari (Google Bot)**           | "Pengguna" non-manusia yang menentukan visibilitas SEO                                 | Struktur HTML semantik, meta data lengkap, kecepatan loading tinggi                                       |

---

## 5. Ruang Lingkup (Scope)

### 5.1 Termasuk dalam Fase Awal (In-Scope)

Profil usaha, produk & katalog, kontak, lokasi, artikel & kategori artikel, admin dashboard, keamanan standar produksi, integrasi dasar pemasaran digital (SEO, analytics, WhatsApp).

### 5.2 Tidak Termasuk di Fase Awal (Out of Scope — Rekomendasi Fase Lanjutan)

- Transaksi pembayaran online (checkout & payment gateway) — saat ini pemesanan diarahkan ke WhatsApp/kontak langsung.
- Aplikasi mobile native (Android/iOS).
- Sistem multi-bahasa (dapat ditambahkan di fase lanjutan bila target pasar ekspor).
- Live chat berbasis AI/chatbot otomatis.

_(Catatan: item di atas diasumsikan out-of-scope karena permintaan awal berfokus pada katalog & promosi, bukan e-commerce penuh. Ini dapat disesuaikan bila kebutuhan bisnis berubah.)_

---

## 6. Spesifikasi Fitur (Functional Requirements)

Prioritas menggunakan metode **MoSCoW**: Must have / Should have / Could have.

### 6.1 Halaman Profil Usaha (Company Profile)

| Fitur                                                                            | Prioritas |
| -------------------------------------------------------------------------------- | --------- |
| Deskripsi usaha, visi-misi, sejarah singkat (storytelling brand)                 | Must      |
| Foto/video profil usaha & tim                                                    | Must      |
| Legalitas usaha (NIB, sertifikasi halal/SNI jika ada) — meningkatkan kepercayaan | Should    |
| Statistik pencapaian (tahun berdiri, jumlah pelanggan, jumlah produk)            | Should    |
| Testimoni & rating pelanggan (social proof)                                      | Must      |
| Logo mitra/klien (jika B2B)                                                      | Could     |

> **Catatan pemasaran:** Halaman ini adalah tahap _Awareness → Interest_ dalam funnel AIDA. Storytelling brand yang kuat meningkatkan _brand trust_, faktor krusial bagi UMKM yang bersaing dengan brand besar.

### 6.2 Produk & Katalog

| Fitur                                                                          | Prioritas |
| ------------------------------------------------------------------------------ | --------- |
| Daftar produk dengan kategori & sub-kategori                                   | Must      |
| Filter & sort (kategori, harga, terbaru, terlaris) tanpa reload halaman (AJAX) | Must      |
| Detail produk: deskripsi, spesifikasi, galeri foto multi-angle                 | Must      |
| Badge dinamis: "Baru", "Terlaris", "Diskon", "Stok Terbatas"                   | Should    |
| Tombol CTA "Pesan via WhatsApp" langsung terisi nama produk otomatis           | Must      |
| Produk terkait / "Mungkin Anda Suka" (cross-selling)                           | Should    |
| Wishlist sederhana (simpan produk favorit di sesi browser)                     | Could     |

**Ide Katalog Preview Modern (sesuai permintaan):**

1. **Quick View Modal** — klik produk memunculkan pop-up detail (galeri, harga, tombol pesan) tanpa pindah halaman, mempercepat eksplorasi produk.
2. **Galeri gambar dengan zoom on hover** (desktop) dan **pinch-to-zoom** (mobile).
3. **Layout grid masonry modern** dengan _lazy loading_ & _skeleton loading_ (efek shimmer saat gambar dimuat) agar terasa premium dan cepat.
4. **Micro-interaction**: efek fade-in saat scroll, hover elevation pada kartu produk.
5. **Perbandingan produk** (opsional) — pilih 2–3 produk sejenis untuk dibandingkan spesifikasinya.
6. **Share cepat** ke WhatsApp/Instagram/Facebook langsung dari halaman produk.
7. Opsi **360°/video produk** untuk produk unggulan (fase lanjutan).

### 6.3 Kontak

| Fitur                                                                             | Prioritas |
| --------------------------------------------------------------------------------- | --------- |
| Formulir kontak (nama, email/no. HP, pesan) dengan validasi & anti-spam (captcha) | Must      |
| Tombol click-to-chat WhatsApp Business (mengambang/floating button)               | Must      |
| Tautan resmi media sosial (Instagram, Facebook, TikTok, dll)                      | Must      |
| Notifikasi email/dashboard saat ada pesan/leads baru masuk                        | Should    |
| Integrasi jam operasional (menampilkan status "Buka/Tutup" real-time)             | Could     |

### 6.4 Lokasi

| Fitur                                                              | Prioritas |
| ------------------------------------------------------------------ | --------- |
| Peta interaktif (Google Maps embed)                                | Must      |
| Alamat lengkap & petunjuk arah (tombol "Buka di Google Maps")      | Must      |
| Dukungan multi-cabang (jika usaha memiliki lebih dari satu lokasi) | Should    |
| Integrasi Google Business Profile (untuk local SEO)                | Should    |

### 6.5 Artikel / Blog (dengan Kategori)

| Fitur                                                                     | Prioritas |
| ------------------------------------------------------------------------- | --------- |
| CRUD artikel dengan editor rich-text (gambar, heading, list, embed video) | Must      |
| Kategori & tag artikel, halaman arsip per kategori                        | Must      |
| Fitur pencarian artikel                                                   | Should    |
| Related articles & artikel populer                                        | Should    |
| Estimasi waktu baca & tanggal publish/update                              | Could     |
| SEO meta per artikel (title, meta description, slug, gambar OG)           | Must      |

> **Catatan pemasaran:** Blog adalah instrumen **content marketing** untuk membangun otoritas (E-E-A-T di mata Google), menjaring trafik organik jangka panjang, dan mengedukasi calon pelanggan di tahap _awareness_ funnel — strategi yang jauh lebih berkelanjutan dibanding hanya mengandalkan iklan berbayar.

### 6.6 Admin Dashboard (Powerful CMS)

| Modul                                       | Detail Fitur                                                                                                                          | Prioritas |
| ------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------- | --------- |
| **Ringkasan (Overview)**                    | Statistik pengunjung, produk terlaris, artikel terpopuler, jumlah leads masuk                                                         | Must      |
| **Manajemen Konten**                        | CRUD penuh untuk Profil, Produk, Kategori Produk, Artikel, Kategori Artikel, Lokasi                                                   | Must      |
| **Media Library**                           | Upload gambar dengan kompresi & resize otomatis, galeri terpusat                                                                      | Must      |
| **Manajemen Leads**                         | Daftar pesan dari form kontak, status follow-up (baru/diproses/selesai)                                                               | Should    |
| **Kustomisasi Tampilan (Theme Customizer)** | Ganti warna primer/sekunder brand, upload logo & favicon, pilih font dari preset, preview perubahan secara real-time sebelum disimpan | Must      |
| **Manajemen Pengguna & Akses**              | Tambah admin/staf, atur peran (Super Admin, Editor, Staf), **ganti password**, riwayat login                                          | Must      |
| **Pengaturan Keamanan**                     | Aktivasi 2FA (verifikasi dua langkah), log aktivitas (audit trail), sesi login aktif & fitur "logout paksa" perangkat lain            | Must      |
| **SEO Tools**                               | Atur meta title/description per halaman, sitemap otomatis, integrasi Google Search Console                                            | Should    |
| **Integrasi Marketing**                     | Pasang Google Analytics 4, Meta Pixel, TikTok Pixel, dan nomor WhatsApp Business API                                                  | Should    |
| **Backup & Restore**                        | Backup data terjadwal (harian/mingguan), unduh backup manual                                                                          | Should    |
| **Notifikasi**                              | Notifikasi in-app/email untuk leads baru, error sistem, percobaan login mencurigakan                                                  | Should    |

---

## 7. Persyaratan Keamanan (Non-Negotiable — Standar Produksi)

Website wajib mengikuti praktik keamanan mengacu pada **OWASP Top 10** dan prinsip _defense in depth_:

| Kategori                   | Implementasi                                                                                                                                                                                                     |
| -------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Autentikasi**            | Password di-hash dengan algoritma modern (bcrypt/Argon2), _rate limiting_ percobaan login, penguncian akun sementara setelah beberapa kali gagal login, dukungan **2FA (Two-Factor Authentication)** untuk admin |
| **Otorisasi**              | Role-Based Access Control (RBAC) — setiap peran hanya mengakses modul sesuai izinnya                                                                                                                             |
| **Transport**              | HTTPS wajib di seluruh halaman (SSL/TLS), penerapan header HSTS                                                                                                                                                  |
| **Manajemen Sesi**         | Cookie sesi `HttpOnly`, `Secure`, `SameSite`; sesi otomatis kedaluwarsa setelah periode tidak aktif                                                                                                              |
| **Validasi Input**         | Sanitasi & validasi semua input pengguna untuk mencegah **XSS** dan **SQL Injection** (gunakan prepared statement/ORM, bukan query mentah)                                                                       |
| **Proteksi Form**          | Token **CSRF** pada semua form, CAPTCHA/anti-bot pada form kontak dan login                                                                                                                                      |
| **Keamanan File Upload**   | Validasi tipe & ukuran file, pemindaian file berbahaya, penyimpanan file di luar direktori eksekusi server                                                                                                       |
| **Header Keamanan HTTP**   | Content-Security-Policy (CSP), X-Frame-Options, X-Content-Type-Options, Referrer-Policy                                                                                                                          |
| **Pembaruan Berkala**      | Dependensi & framework diperbarui rutin, pemantauan kerentanan (CVE)                                                                                                                                             |
| **Logging & Monitoring**   | Pencatatan log login, error, dan aktivitas admin; peringatan otomatis untuk aktivitas mencurigakan                                                                                                               |
| **Backup Terenkripsi**     | Backup data terjadwal, disimpan terpisah dari server utama                                                                                                                                                       |
| **Kepatuhan Data Pribadi** | Mengikuti prinsip **UU Perlindungan Data Pribadi (UU PDP)** dalam mengelola data pelanggan dari form kontak                                                                                                      |
| **Uji Keamanan**           | Rekomendasi audit keamanan/pentest dasar sebelum go-live dan secara berkala setelahnya                                                                                                                           |

---

## 8. Strategi Pemasaran Terintegrasi dalam Produk

Website ini dirancang sebagai **instrumen pemasaran**, bukan sekadar tampilan statis, dengan menerapkan kerangka manajemen pemasaran berikut:

### 8.1 Marketing Funnel (AIDA) dipetakan ke Struktur Website

| Tahap Funnel  | Bagian Website                                         | Tujuan                           |
| ------------- | ------------------------------------------------------ | -------------------------------- |
| **Attention** | SEO, media sosial, iklan → mendarat di Beranda/Artikel | Menarik trafik ke website        |
| **Interest**  | Profil Usaha, Artikel edukatif                         | Membangun kepercayaan & minat    |
| **Desire**    | Katalog produk, testimoni, badge "Terlaris"            | Meyakinkan keunggulan produk     |
| **Action**    | CTA "Pesan via WhatsApp", Form Kontak                  | Konversi menjadi leads/transaksi |

### 8.2 Elemen Marketing Mix (7P) yang Direfleksikan di Website

- **Product** — Katalog & deskripsi produk yang jelas dan meyakinkan.
- **Price** — Estimasi harga/kisaran harga transparan (opsional sesuai kebijakan bisnis).
- **Place** — Halaman lokasi & integrasi Google Maps/Google Business Profile.
- **Promotion** — Artikel, badge diskon, media sosial terhubung.
- **People** — Halaman "Tentang Kami"/Tim menonjolkan kredibilitas SDM.
- **Process** — Alur pemesanan yang jelas (klik produk → WhatsApp → konfirmasi).
- **Physical Evidence** — Foto usaha, sertifikasi, testimoni sebagai bukti nyata kualitas.

### 8.3 SEO & Local SEO

- Struktur URL bersih dan _mobile-friendly_.
- Markup data terstruktur (schema.org): `LocalBusiness`, `Product`, `Article`.
- Optimasi kecepatan halaman (Core Web Vitals) — faktor peringkat Google.
- Integrasi Google Business Profile agar muncul di Google Maps & "Local Pack".

### 8.4 Content Marketing Berkelanjutan

- Kalender editorial artikel per kategori (misalnya: Tips Produk, Edukasi Industri, Cerita di Balik Brand) untuk menjaga konsistensi publikasi dan membangun otoritas topik dari waktu ke waktu.

### 8.5 Pengukuran & Optimasi Berkelanjutan (Data-Driven Marketing)

- Integrasi Google Analytics 4 untuk memantau sumber trafik, halaman populer, dan _conversion goals_ (klik WhatsApp, submit form).
- Integrasi Meta Pixel/TikTok Pixel untuk mendukung _retargeting_ iklan bagi pengunjung yang belum konversi.
- Dashboard admin menampilkan ringkasan performa agar pemilik usaha dapat mengambil keputusan berbasis data secara berkelanjutan (_continuous improvement cycle_).

---

## 9. Persyaratan Non-Fungsional

| Aspek                     | Target                                                                                                  |
| ------------------------- | ------------------------------------------------------------------------------------------------------- |
| **Kecepatan**             | Waktu muat halaman < 3 detik (skor Google PageSpeed ≥ 85)                                               |
| **Responsif**             | Tampilan optimal di mobile, tablet, dan desktop (mobile-first, mengingat mayoritas trafik UMKM dari HP) |
| **Aksesibilitas**         | Kontras warna memadai, alt-text gambar, navigasi ramah keyboard                                         |
| **Skalabilitas**          | Struktur data mendukung penambahan produk/artikel dalam jumlah besar tanpa penurunan performa           |
| **Ketersediaan (Uptime)** | Target uptime ≥ 99%                                                                                     |
| **SEO Teknis**            | Sitemap XML otomatis, robots.txt, meta tag lengkap                                                      |

---

## 10. Sitemap / Struktur Navigasi (Ringkas)

```
Beranda
├─ Profil Usaha (Tentang Kami)
├─ Katalog Produk
│   ├─ Kategori A / B / C ...
│   └─ Detail Produk (Quick View)
├─ Artikel
│   ├─ Kategori Artikel
│   └─ Detail Artikel
├─ Lokasi
├─ Kontak
└─ [Admin Dashboard - akses terpisah/login]
    ├─ Overview
    ├─ Produk & Kategori
    ├─ Artikel & Kategori
    ├─ Leads/Pesan Masuk
    ├─ Kustomisasi Tampilan
    ├─ Pengguna & Keamanan
    └─ Pengaturan SEO & Integrasi
```

---

## 11. Rekomendasi Fase Pengembangan (Roadmap)

| Fase                                            | Fokus                                                                                                                                                           | Estimasi Fitur          |
| ----------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------- |
| **Fase 1 — MVP**                                | Profil usaha, produk & katalog dasar, kontak, lokasi, admin dashboard dasar (CRUD, ganti password), keamanan fundamental (HTTPS, hash password, validasi input) | 4–6 minggu              |
| **Fase 2 — Konten & Branding**                  | Artikel & kategori artikel, theme customizer (ganti warna, logo), SEO dasar, integrasi Google Analytics                                                         | 2–3 minggu              |
| **Fase 3 — Katalog Modern & Keamanan Lanjutan** | Quick view modal, filter AJAX, badge produk, 2FA, audit log, integrasi pixel marketing                                                                          | 3–4 minggu              |
| **Fase 4 — Ekspansi (Opsional)**                | E-commerce (checkout/payment), multi-bahasa, program loyalitas/referral                                                                                         | Sesuai kebutuhan bisnis |

---

## 12. Risiko & Mitigasi

| Risiko                                                   | Mitigasi                                                                    |
| -------------------------------------------------------- | --------------------------------------------------------------------------- |
| Pemilik usaha kesulitan mengisi konten awal              | Sediakan template/contoh konten & panduan penggunaan dashboard (onboarding) |
| Serangan brute-force pada login admin                    | Rate limiting, penguncian akun, 2FA                                         |
| Konten artikel tidak konsisten dipublikasi               | Sediakan kalender editorial & pengingat di dashboard                        |
| Website lambat karena gambar produk beresolusi besar     | Kompresi & resize gambar otomatis saat upload                               |
| Ketergantungan pada satu admin (single point of failure) | Fitur multi-role user agar tugas dapat didelegasikan                        |

---

## 13. Metrik Keberhasilan Pasca-Peluncuran

- Pertumbuhan trafik organik bulanan (Google Analytics).
- Jumlah klik CTA "Pesan via WhatsApp" & submission form kontak per bulan.
- Peringkat kata kunci utama di Google Search Console.
- Tingkat _bounce rate_ dan rata-rata durasi sesi.
- Frekuensi publikasi artikel & engagement (page views per artikel).
- Nol insiden keamanan tercatat dalam log audit.

---

## 14. Lampiran — Rekomendasi Teknis (Opsional, Tidak Mengikat)

Sebagai referensi tim pengembang, kombinasi teknologi berikut umum digunakan untuk kebutuhan serupa (pemilihan akhir menyesuaikan kapasitas tim developer):

- **Frontend**: Framework modern berbasis komponen (mendukung _lazy loading_ & performa tinggi).
- **Backend/CMS**: Sistem dengan dukungan RBAC bawaan dan API terstruktur.
- **Database**: Basis data relasional dengan dukungan _prepared statement_.
- **Hosting**: Layanan dengan dukungan SSL otomatis, CDN, dan backup terjadwal.

---

## 15. Spesifikasi Teknis (Ditetapkan)

Berdasarkan diskusi kebutuhan (admin dashboard powerful, keamanan standar produksi, budget UMKM, ketersediaan developer lokal), stack berikut ditetapkan sebagai baseline teknis:

| Komponen                   | Pilihan                                                                   | Alasan                                                                                                 |
| -------------------------- | ------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------ |
| Bahasa & Framework Backend | **PHP 8.2+ dengan Laravel 11**                                            | Ekosistem keamanan matang (CSRF, hashing, ORM anti-SQLi bawaan), developer lokal banyak, hosting murah |
| Panel Admin                | **Laravel Filament** (atau Livewire custom bila butuh UI sangat spesifik) | Mempercepat pembuatan CRUD, tabel, form, tanpa membangun dari nol                                      |
| Frontend Halaman Publik    | **Blade + Alpine.js + Tailwind CSS**                                      | SSR sehingga SEO-friendly, tetap interaktif untuk quick view/filter tanpa reload                       |
| Database                   | **MySQL 8 / MariaDB**                                                     | Stabil, didukung luas oleh hosting lokal                                                               |
| Autentikasi                | **Laravel Fortify/Breeze**                                                | Login, reset password, 2FA siap pakai                                                                  |
| RBAC (Role & Permission)   | **Package Spatie Laravel-Permission**                                     | Standar industri untuk Super Admin/Editor/Staf                                                         |
| 2FA                        | **Laravel Fortify (TOTP)**                                                | Verifikasi dua langkah untuk admin                                                                     |
| Manajemen Media            | **Spatie Laravel-Medialibrary**                                           | Resize & kompresi gambar otomatis saat upload                                                          |
| Pencarian (opsional)       | **Laravel Scout + driver database/Meilisearch**                           | Pencarian artikel/produk lebih relevan                                                                 |
| Hosting                    | VPS atau cPanel dengan PHP-FPM, Nginx/Apache, SSL via Let's Encrypt       | Kompatibel luas, biaya terjangkau                                                                      |
| Version Control            | **Git** (wajib)                                                           | Diperlukan agar riwayat perubahan kode dari AI coding agent tetap terlacak & bisa di-rollback          |

> Stack ini bisa langsung dijadikan acuan saat menulis _prompt_ implementasi ke tools seperti Google Antigravity/Claude Code/Cursor, agar agent tidak menebak-nebak teknologi yang dipakai.

---

## 16. Skema Data (Entity Overview)

Gambaran entitas basis data utama beserta field kunci — cukup detail agar AI coding agent dapat langsung membuat migration & model tanpa banyak asumsi:

| Entitas                     | Field Kunci                                                                                                                                                               |
| --------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **users**                   | id, name, email, password (hashed), role_id, two_factor_secret, last_login_at, is_active                                                                                  |
| **roles** & **permissions** | id, name, guard_name (via Spatie Permission)                                                                                                                              |
| **business_profile**        | id, business_name, description, vision, mission, logo, founded_year, legal_docs (nullable)                                                                                |
| **product_categories**      | id, name, slug, description, parent_id (nullable, untuk sub-kategori)                                                                                                     |
| **products**                | id, category_id, name, slug, description, price (nullable/estimasi), stock_status (enum: tersedia/habis/pre-order), is_featured (bool), is_bestseller (bool), views_count |
| **product_images**          | id, product_id, image_path, is_primary, sort_order                                                                                                                        |
| **article_categories**      | id, name, slug, description                                                                                                                                               |
| **articles**                | id, category_id, author_id, title, slug, content (rich text), featured_image, excerpt, meta_title, meta_description, status (draft/published), published_at, reading_time |
| **locations**               | id, name, address, latitude, longitude, phone, operating_hours (json)                                                                                                     |
| **contact_messages**        | id, name, email, phone, message, status (baru/diproses/selesai), source_page                                                                                              |
| **site_settings**           | id, key (mis. `theme_primary_color`, `theme_secondary_color`, `logo`, `favicon`, `whatsapp_number`, `ga4_id`, `meta_pixel_id`), value                                     |
| **activity_logs**           | id, user_id, action, description, ip_address, created_at                                                                                                                  |

---

## 17. Design System Dasar (Acuan Visual Awal)

Karena warna dapat diubah admin melalui _Theme Customizer_, sistem visual dibangun berbasis **token/variable**, bukan warna hardcode, sehingga perubahan di dashboard otomatis berlaku ke seluruh halaman.

| Token                   | Nilai Default (Contoh)                     | Catatan                                                 |
| ----------------------- | ------------------------------------------ | ------------------------------------------------------- |
| `--color-primary`       | `#0F766E` (teal)                           | Bisa diganti admin, dipakai di tombol CTA & aksen utama |
| `--color-secondary`     | `#F59E0B` (amber)                          | Untuk badge/highlight (mis. "Diskon")                   |
| `--color-neutral-dark`  | `#1F2937`                                  | Teks utama                                              |
| `--color-neutral-light` | `#F9FAFB`                                  | Latar halaman                                           |
| Font Judul              | Poppins / Plus Jakarta Sans (Google Fonts) | Kesan modern, mudah dibaca                              |
| Font Isi                | Inter / Nunito Sans                        | Keterbacaan tinggi di layar kecil                       |
| Radius Komponen         | `rounded-xl` (12px)                        | Kesan modern, konsisten dengan tren UI 2026             |
| Style Kartu Produk      | Shadow lembut + hover elevation            | Selaras dengan kebutuhan katalog "modern"               |

---

## 18. Contoh User Story & Acceptance Criteria

Format _Given-When-Then_ untuk memandu pembuatan fitur oleh developer/AI agent secara presisi:

**US-01 — Quick View Produk**

> Sebagai pengunjung, saya ingin melihat detail produk tanpa berpindah halaman, agar proses menjelajah katalog lebih cepat.

- **Given** saya berada di halaman katalog
- **When** saya klik kartu produk
- **Then** modal quick view terbuka menampilkan galeri gambar, nama, kategori, deskripsi singkat, dan tombol "Pesan via WhatsApp" — tanpa reload halaman

**US-02 — Ganti Warna Tema**

> Sebagai admin, saya ingin mengganti warna utama website, agar tampilan sesuai identitas brand terbaru.

- **Given** saya login sebagai Super Admin
- **When** saya membuka menu Kustomisasi Tampilan dan memilih warna baru lalu klik "Simpan"
- **Then** seluruh elemen bertoken `--color-primary` di halaman publik berubah sesuai pilihan, dan perubahan terlihat di _preview_ sebelum disimpan permanen

**US-03 — Ganti Password**

> Sebagai admin, saya ingin mengganti password akun saya sendiri, agar keamanan akun tetap terjaga.

- **Given** saya login ke dashboard
- **When** saya membuka menu Profil > Keamanan, memasukkan password lama & password baru yang valid
- **Then** sistem memvalidasi password lama, meng-hash password baru, mencatat aktivitas di activity log, dan mengeluarkan sesi login lain (opsional force logout)

**US-04 — Publikasi Artikel**

> Sebagai admin/editor, saya ingin mempublikasikan artikel baru dengan kategori tertentu, agar strategi content marketing berjalan konsisten.

- **Given** saya memiliki draft artikel dengan judul, isi, kategori, dan gambar utama terisi
- **When** saya klik "Publikasikan"
- **Then** artikel tampil di halaman publik sesuai kategorinya, muncul di sitemap.xml, dan meta SEO otomatis terisi (fallback dari judul/excerpt jika admin tidak mengisi manual)

---

## 19. Daftar Halaman/Rute Utama (Route Map Ringkas)

```
GET  /                          → Beranda
GET  /profil                    → Profil Usaha
GET  /produk                    → Katalog Produk (dengan filter & kategori)
GET  /produk/{slug}             → Detail Produk
GET  /artikel                   → Daftar Artikel
GET  /artikel/kategori/{slug}   → Artikel per Kategori
GET  /artikel/{slug}            → Detail Artikel
GET  /lokasi                    → Halaman Lokasi
GET  /kontak                    → Form Kontak
POST /kontak                    → Submit pesan kontak

/admin/login                    → Login Admin
/admin/dashboard                → Ringkasan
/admin/produk, /admin/kategori-produk
/admin/artikel, /admin/kategori-artikel
/admin/leads
/admin/tampilan                 → Theme Customizer
/admin/pengguna                 → Manajemen User & Role
/admin/profil-saya              → Ganti Password & 2FA
/admin/pengaturan-seo
/admin/log-aktivitas
```

---

## 20. Panduan untuk AI Coding Agent (mis. Google Antigravity, Claude Code, Cursor)

PRD ini dapat langsung dilampirkan ke tools _agentic coding_ untuk mempercepat pembuatan prototipe. Agar hasil generate akurat dan sesuai standar, ikuti alur berikut:

**Langkah setelah PRD selesai:**

1. **Siapkan repo/folder proyek kosong** dan inisialisasi Git — wajib, agar setiap perubahan dari AI agent dapat ditelusuri & di-_rollback_.
2. **Lampirkan PRD ini secara utuh** ke agent (unggah file `.md`/`.docx` ini ke project folder, atau paste isinya ke prompt) — jangan hanya menjelaskan secara lisan, karena detail di Bagian 15–19 (stack, skema data, user story, route map) adalah yang membuat hasil generate agent presisi.
3. **Minta _implementation plan_ dahulu, jangan langsung eksekusi.** Gunakan _Plan Mode_ (tersedia di Antigravity, Cursor, Claude Code) dengan prompt seperti:
   > "Act as a Senior Laravel Engineer. Review the attached PRD. Create a detailed implementation plan to build this as a Laravel 11 + Filament + Tailwind application, following the tech stack, database schema, and route map specified in Section 15–19."
4. **Review rencana yang dihasilkan agent sebelum di-_approve_.** Periksa apakah struktur folder, urutan pengerjaan, dan pemilihan package sudah sesuai Bagian 15.
5. **Pecah pengerjaan menjadi task terisolasi (satu misi per task)**, jangan minta semua fitur sekaligus dalam satu prompt raksasa. Urutan yang disarankan:
   - Setup project + autentikasi + RBAC + migration dasar
   - CRUD Profil, Produk & Kategori, Lokasi
   - Katalog publik + Quick View + filter AJAX
   - Artikel & Kategori Artikel + SEO meta
   - Admin Dashboard: Theme Customizer, ganti password, 2FA, activity log
   - Integrasi marketing: GA4, Meta Pixel, sitemap, schema.org
   - Hardening keamanan sesuai Bagian 7 (security headers, rate limiting, CSRF, dsb) — lakukan sebagai task checklist terakhir sebelum go-live
6. **Minta agent memverifikasi hasil kerjanya sendiri** (menjalankan test, membuka preview di browser, screenshot alur) sebelum menandai task selesai — bukan sekadar klaim "kode sudah jalan".
7. **Uji manual & code review tetap wajib dilakukan manusia**, terutama pada modul autentikasi, RBAC, dan form input (area paling sensitif secara keamanan). Jangan langsung deploy hasil AI tanpa peninjauan.
8. **Simpan aturan/standar (Rules)** di level project — bila tools mendukung _Rules/Skills_ (seperti di Antigravity) — berisi hal permanen seperti: "gunakan Eloquent/prepared statement, jangan raw query", "semua form wajib CSRF token", "password wajib di-hash bcrypt/argon2", agar konsisten di setiap task berikutnya tanpa perlu diulang di setiap prompt.
9. **Setelah prototipe fungsional jadi**, lakukan: pengujian keamanan dasar (checklist Bagian 7), optimasi performa (Core Web Vitals), lalu baru rencanakan go-live/deployment.

---

_Dokumen ini merupakan PRD yang telah dilengkapi dengan spesifikasi teknis (stack, skema data, design system, user story, dan route map) agar dapat langsung dijadikan input bagi tim developer maupun AI coding agent (Antigravity/Claude Code/Cursor) untuk menghasilkan prototipe fungsional. Detail lanjutan seperti wireframe visual per halaman dapat dikembangkan sebagai dokumen turunan bila diperlukan._
