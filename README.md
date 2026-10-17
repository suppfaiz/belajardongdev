# Belajar Yuk — Marketplace Les Privat Offline

> **“Temukan Guru Terbaik, Raih Masa Depan Cerah.”**

**Belajar Yuk** adalah platform marketplace web les privat tatap muka (offline) yang mempertemukan guru honorer berdedikasi dengan orang tua murid yang mencari pendamping belajar berkualitas untuk anak-anak di seluruh Indonesia. Guru datang langsung ke rumah murid dengan verifikasi identitas & ijazah yang ketat, pencarian berbasis radius geolokasi, penjaminan transaksi via escrow & ledger keuangan transparan, serta pencegahan double booking real-time.

---

## 1. Tampilan Visual & Desain Sistem

Desain dibangun dengan gaya modern, minimalis, profesional, dan ramah keluarga sesuai dengan acuan 9 panel desain visual:
- **Primary Color:** `#2563EB` (Royal Blue)
- **Secondary Color:** `#60A5FA` (Sky Blue)
- **Primary Gradient:** `linear-gradient(135deg, #2563EB 0%, #60A5FA 100%)`
- **Background:** `#F8FAFC`
- **Surface:** `#FFFFFF`
- **Text Utama:** `#0F172A`
- **Text Sekunder:** `#64748B`
- **Border:** `#E2E8F0`
- **Border Radius:** `12px – 16px` (Rounded cards & buttons)
- **Tipografi:** Plus Jakarta Sans / Inter

---

## 2. Arsitektur & Teknologi

- **Backend:** Laravel 12 (PHP 8.5.2)
- **Frontend:** Laravel Blade, Tailwind CSS v4, Alpine.js
- **Database:** PostgreSQL dengan koordinat geospasial & kalkulasi spherical distance
- **Autentikasi & RBAC:** Multi-role (`parent`, `teacher`, `admin`) dengan middleware terproteksi (`EnsureUserRole`)
- **Ledger Keuangan:** Sistem pencatatan ganda (*double-entry financial ledger*) yang immutable
- **Payment Adapter:** Adapter gateway pembayaran Indonesia (Virtual Account BCA/Mandiri, QRIS, e-Wallet GoPay/Dana) dengan verifikasi webhook HMAC signature & idempotency
- **Document Vault:** Penyimpanan dokumen guru privat terlindungi (`/dokumen/{document}/download`)
- **Code Formatter:** Laravel Pint (`vendor/bin/pint --format agent`)
- **Test Runner:** PHPUnit dengan suite Feature Tests

---

## 3. Fitur Utama yang Telah Selesai (Completed Features)

### A. Publik & Eksplorasi
1. **Landing Page:**
   - Hero section dengan pencarian cepat (lokasi, jenjang, mata pelajaran, radius).
   - 4 Pilar keunggulan: Guru Terverifikasi, Pencarian Radius Presisi, Berbagai Jenjang (SD, SMP, SMA, UTBK), Pembayaran Aman Rekening Bersama.
   - Alur 4 langkah cara kerja.
   - Rekomendasi guru terverifikasi dengan rating, tarif/jam, dan badge sertifikasi.
   - Ajakan bergabung untuk guru honorer dan footer lengkap.
2. **Halaman Pencarian Geospasial (`/cari-guru`):**
   - Pencarian radius interaktif (1 km, 3 km, 5 km, 10 km, 20 km).
   - Filter jenjang pendidikan (SD, SMP, SMA, Persiapan Ujian).
   - Filter mata pelajaran, rentang tarif per jam, rating bintang, dan hari ketersediaan.
   - Pengurutan: Jarak Terdekat, Rating Tertinggi, Tarif Terendah, Tarif Tertinggi.
   - Validasi batas maksimal jangkauan mengajar guru (*teacher max travel radius*).
   - Filter otomatis guru belum terverifikasi (hanya guru terverifikasi yang tayang).
3. **Halaman Detail Profil Guru (`/guru/{id}`):**
   - Profil lengkap, riwayat pendidikan, pengalaman mengajar, dan status verifikasi.
   - Kalkulasi estimasi jarak real-time dari rumah murid ke lokasi guru.
   - Slot jadwal mengajar mingguan dan ulasan rating bintang dari orang tua murid.
   - Alamat pribadi guru disembunyikan demi privasi.

### B. Alur Pemesanan & Pembayaran (Booking & Escrow)
1. **Wizard Pemesanan Multi-Langkah (`/guru/{id}/booking`):**
   - Pemilihan profil anak yang terdaftar di akun orang tua.
   - Pemilihan mata pelajaran, tanggal les, jam mulai, dan durasi (60, 90, 120 menit).
   - Input alamat rumah lokasi les.
   - Validasi jadwal & pencegahan *double-booking* otomatis.
   - Snapshot tarif dan komisi platform 10% yang dikunci pada saat pemesanan.
2. **Respon Guru:**
   - Guru dapat menerima (*Accept*) atau menolak (*Reject*) pesanan dengan alasan jelas.
3. **Checkout & Pembayaran (`/booking/{id}/checkout`):**
   - Pilihan metode: Virtual Account (BCA), QRIS, GoPay, DANA, Transfer Bank.
   - Batas waktu pembayaran 24 jam dengan status tagihan otomatis.
   - Simulator sandbox pembayaran interaktif untuk pengujian lokal.
   - Webhook callback dengan verifikasi HMAC signature & pencegahan duplikasi (*idempotency*).
4. **Penyelesaian Sesi & Review:**
   - Guru dan orang tua dapat mengonfirmasi sesi les selesai.
   - Orang tua dapat memberikan rating bintang (1-5) dan testimoni ulasan.
   - Rating dan jumlah ulasan guru ter-cache secara otomatis.

### C. Dashboard Pengguna
1. **Dashboard Orang Tua (`/parent/dashboard`):**
   - Ringkasan pemesanan aktif, total transaksi, rata-rata rating, dan anak terdaftar.
   - Manajemen profil anak (*Fitur Anak Saya*).
   - Monitoring riwayat pemesanan & status sesi les.
   - Riwayat pembayaran dan invoice.
   - Manajemen ulasan dan profil.
2. **Dashboard Guru (`/teacher/dashboard`):**
   - Ringkasan pendapatan bruto, dana dalam proses (escrow), dan saldo bersih tersedia.
   - Manajemen profil & pengunggahan berkas verifikasi KTP/Ijazah.
   - Pengaturan mata pelajaran yang diajar dan kustomisasi tarif per jam.
   - Pengaturan jadwal ketersediaan mingguan (*availability slots*).
   - Manajemen permintaan les masuk (Terima / Tolak).
   - Riwayat mengajar & konfirmasi penyelesaian sesi.
   - Rincian penghasilan & permintaan pencairan dana (*payout request*).
3. **Dashboard Admin (`/admin/dashboard`):**
   - Statistik platform: total pengguna, guru terverifikasi, total booking, total pendapatan komisi platform.
   - Verifikasi dokumen guru: peninjauan KTP & Ijazah, persetujuan verifikasi, atau penolakan dengan alasan.
   - Manajemen pengguna: penangguhan/aktivasi akun (*suspend/activate*).
   - Monitoring seluruh transaksi pemesanan & status pembayaran.
   - Monitoring pencairan dana (*payout approvals*).
   - Pengaturan persentase komisi platform dinamis.
   - Penanganan tiket bantuan & laporan keselamatan les offline.
   - Audit trail seluruh aktivitas administratif.

### D. Sistem Keuangan & Keamanan
1. **Ledger Akuntansi Ganda (*Double-Entry Ledger*):**
   - Setiap pergerakan rupiah dicatat sebagai transaksi debit/kredit yang *immutable*.
   - Saldo guru dikalkulasi secara matematis dari histori ledger (tidak dapat dimanipulasi manual).
2. **Keamanan Dokumen:**
   - Berkas identitas guru tersimpan di disk privat dengan otorisasi unduh hanya untuk admin atau pemilik akun.
3. **Pencegahan Double-Booking:**
   - Transaksi database dengan validasi tumpang tindih waktu pada level query backend.

---

## 4. Persyaratan Sistem

- **PHP:** >= 8.2 (Direkomendasikan PHP 8.5)
- **Composer:** >= 2.x
- **Node.js:** >= 20.x & npm
- **Database:** PostgreSQL >= 14
- **Web Server:** Nginx / Apache / PHP Built-in Server

---

## 5. Panduan Instalasi & Menjalankan Proyek

### Langkah 1: Clone & Masuk Direktori
```bash
cd "belajaryuk"
```

### Langkah 2: Instalasi Dependensi PHP & JavaScript
```bash
composer install
npm install
```

### Langkah 3: Konfigurasi Environment (`.env`)
Salin berkas `.env.example` ke `.env` jika belum ada:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi database PostgreSQL di `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=belajaryuk
DB_USERNAME=postgres
DB_PASSWORD=secret
```

### Langkah 4: Migrasi Database & Seeder
Jalankan migrasi dan seeder untuk membuat tabel serta data pengujian lengkap:
```bash
php artisan migrate:fresh --seed
```

### Langkah 5: Build Aset Frontend
```bash
npm run build
```
*(Atau jalankan `npm run dev` untuk hot module replacement saat pengembangan).*

### Langkah 6: Jalankan Server Aplikasi
```bash
php artisan serve
```
Akses aplikasi melalui browser di: `http://127.0.0.1:8000`

---

## 6. Akun Pengujian Demo (Seeded Credentials)

Pada halaman login (`/masuk`), tersedia tombol cepat **1-Klik Akun Demo**:

| Peran (Role) | Nama Akun | Email | Password |
|---|---|---|---|
| **Admin** | Administrator Utama | `admin@belajaryuk.id` | `password` |
| **Guru (Terverifikasi)** | Siti Rahmawati, S.Pd | `siti.rahmawati@belajaryuk.id` | `password` |
| **Guru (Terverifikasi)** | Ahmad Fauzi, S.Pd | `ahmad.fauzi@belajaryuk.id` | `password` |
| **Guru (Belum Verifikasi)** | Rizky Pratama, S.Pd | `rizky.pratama@belajaryuk.id` | `password` |
| **Orang Tua** | Budi Santoso | `budi.santoso@belajaryuk.id` | `password` |

---

## 7. Menjalankan Automated Tests

Aplikasi dilengkapi dengan rangkaian automated test yang mencakup autentikasi, otorisasi peran, pencarian geospasial, jadwal & booking, komisi & ledger keuangan, serta payment webhook idempotency:

```bash
# Jalankan seluruh test suite
php artisan test --compact

# Jalankan test per fitur
php artisan test --filter=AuthAndAuthorizationTest
php artisan test --filter=GeospatialSearchTest
php artisan test --filter=BookingAndScheduleTest
php artisan test --filter=CommissionAndLedgerTest
php artisan test --filter=PaymentWebhookTest
```

**Status Pengujian Terkini:**
```
Tests:    20 passed (80 assertions)
Duration: 1.88s
Status:   100% Passing Green
```

---

## 8. Status Integrasi Eksternal & Rencana Pengembangan

| Komponen | Status Saat Ini | Kredensial / Konfigurasi yang Dibutuhkan untuk Produksi |
|---|---|---|
| **Payment Gateway** | Adapter Aktif (`MockPaymentGateway`) mendukung VA, QRIS, e-Wallet, webhook signature HMAC, dan simulasi sandbox. | Midtrans / Xendit / Tripay: `MERCHANT_ID`, `SERVER_KEY`, `CLIENT_KEY`, `WEBHOOK_SECRET`. |
| **Peta & Geocoding** | Formula Geospasial Haversine/Spherical Distance SQL PostgreSQL presisi tinggi (km). | Google Maps API / Mapbox / OpenStreetMap Nominatim: `MAPBOX_API_KEY` untuk autocomplete alamat picker. |
| **Penyimpanan Dokumen** | Local Secure Storage disk (`storage/app/private`). | AWS S3 / Cloudflare R2: `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_BUCKET`. |
| **Notifikasi SMS/WA** | In-app Notification Feed & Alert Dashboard. | Twilio / Fonnte / WhatsApp Business API: `WA_GATEWAY_TOKEN`. |

---

## 9. Struktur Folder Penting

```
belajaryuk/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminDashboardController.php    # Panel Admin
│   │   │   ├── AuthController.php              # Login & Registrasi
│   │   │   ├── BookingProcessController.php    # Wizard Booking & Checkout
│   │   │   ├── HomeController.php              # Beranda & Landing
│   │   │   ├── ParentDashboardController.php   # Dashboard Orang Tua
│   │   │   ├── TeacherDashboardController.php  # Dashboard Guru
│   │   │   ├── TeacherSearchController.php     # Pencarian Geospasial
│   │   │   └── WebhookController.php           # Webhook Callback
│   │   └── Middleware/
│   │       └── EnsureUserRole.php              # RBAC & Proteksi Akun
│   ├── Models/                                 # 16 Model Eloquent
│   └── Services/
│       ├── Booking/BookingService.php          # Logika Booking & Double Booking
│       ├── Ledger/LedgerService.php            # Double-entry Financial Ledger
│       └── Payment/                            # Payment Gateway Adapter & Service
├── database/
│   ├── migrations/                             # Skema 16 Tabel Lengkap
│   └── seeders/DatabaseSeeder.php              # Seed Data Realistis
├── resources/
│   ├── css/app.css                             # Tailwind CSS v4 Custom Utilities
│   └── views/                                  # Tampilan Blade Konsisten & Responsif
└── tests/
    └── Feature/                                # 5 Automated Feature Test Suites
```

---

*Dikembangkan untuk kemajuan pendidikan anak Indonesia dan kesejahteraan guru honorer.*
# belajardongdev
# belajardongdev
