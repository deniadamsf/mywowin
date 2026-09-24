# My Wowin

Platform e-commerce dan membership resmi **PT Wowin Purnomo Putera** — produsen kecap manis, saos sambal, saos tomat, dan cuka makan dengan merek **Wowin**, **Rajaku**, dan **Jangkar**.

Website: [mywowin.com](https://mywowin.com)

---

## Struktur Proyek

```
MYWOWIN/
├── My_Wowin (flutter)/       # Aplikasi mobile (Android & iOS)
├── public_html (web fronted + backend)/  # Web + REST API (Laravel 12)
├── docker/                    # Konfigurasi Docker lokal
├── docker-compose.yml         # Orchestration container
├── LOGO/                      # Aset logo brand
├── PRODUK WOWIN/              # Foto produk kecap & saos
├── Screenshots_PlayStore/     # Screenshot untuk Google Play Store
└── deploy_hostinger.ps1       # Script deploy ke Hostinger via SSH
```

## Komponen Utama

### Aplikasi Mobile (Flutter)

Aplikasi belanja untuk mitra distributor dan pelanggan. Dibangun dengan Flutter & Riverpod.

Fitur: katalog produk, keranjang belanja, checkout, poin reward harian, live chat CS, kemitraan VIP, notifikasi push (FCM).

Detail lengkap: [`My_Wowin (flutter)/README.md`](My_Wowin%20(flutter)/README.md)

### Website & Backend API (Laravel 12)

Portal web publik + panel admin + REST API untuk aplikasi mobile. PHP 8.2+, MariaDB, Laravel Sanctum.

Fitur: registrasi OTP email, manajemen produk & kategori, promo bundling, rekap pesanan per cabang, cetak invoice PDF/Excel, artikel SEO.

Detail lengkap: [`public_html (web fronted + backend)/README.md`](public_html%20(web%20fronted%20+%20backend)/README.md)

## Menjalankan Lokal (Docker)

```bash
# Nyalakan container
docker compose up -d --build

# Buat symlink storage
docker compose exec app php artisan storage:link

# Akses:
# Web      → http://localhost:8000
# phpMyAdmin → http://localhost:8080
```

## Deploy ke Hostinger

Tersedia 2 metode:

1. **Otomatis via GitHub Actions** — push ke branch `main` otomatis sync ke server lewat FTPS.
2. **Manual via SSH** — jalankan `./deploy_hostinger.ps1` dari terminal.

## Kantor Cabang

Trenggalek · Kediri · Madiun · Solo · Jogja · Cirebon · Kudus · Bogor · Serang

## Lisensi

Proprietary — PT Wowin Purnomo Putera. Seluruh hak cipta dilindungi.
