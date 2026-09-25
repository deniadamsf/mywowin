# My Wowin — Website & Backend API

Portal web publik, panel admin, dan REST API untuk ekosistem My Wowin. Dibangun dengan Laravel 12.

## Tech Stack

- **Framework:** Laravel 12 (PHP ^8.2)
- **Database:** MariaDB / MySQL
- **Auth API:** Laravel Sanctum
- **Frontend:** Blade + Tailwind CSS (Vite)
- **PDF:** barryvdh/laravel-dompdf
- **Excel:** maatwebsite/excel
- **Push Notification:** Firebase HTTP v1 (google/apiclient)

## Fitur

### Portal Publik & Member
- Registrasi dengan verifikasi OTP email
- Katalog produk & promo bundling
- Keranjang belanja & checkout online
- Tracking pesanan & e-nota
- Artikel SEO produk
- Sitemap XML

### Panel Admin
- **Super Admin** — akses penuh lintas cabang, ACC kemitraan, master produk, artikel, live chat
- **Admin Cabang** — kelola pesanan per cabang, cetak invoice PDF/Excel, retur barang

### REST API (untuk aplikasi Flutter)
- Auth (register, login, OTP, reset password)
- Katalog, kategori, bundling, hero banner
- Cart CRUD
- Checkout & order history
- Poin reward & claim
- Live chat
- Profil & membership

## Struktur

```
app/
├── Console/
├── Exports/
├── Http/          # Controllers, Middleware, Requests
├── Mail/
├── Models/
├── Notifications/
├── Providers/
└── Services/
routes/
├── api.php        # Endpoint REST API
├── web.php        # Route web & admin panel
└── console.php
```

## Setup Lokal

```bash
# Install PHP dependencies
composer install

# Install frontend dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Jalankan migrasi
php artisan migrate

# Symlink storage
php artisan storage:link

# Jalankan dev server + Vite
composer run dev
```

Atau pakai Docker dari root proyek:

```bash
docker compose up -d --build
docker compose exec app php artisan storage:link
# Akses → http://localhost:8000
```

