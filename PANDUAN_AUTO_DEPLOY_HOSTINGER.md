# 🚀 PANDUAN LENGKAP: AUTO-DEPLOY LOCALHOST KE HOSTINGER (ZERO DATABASE IMPACT)

Dokumen ini berisi panduan lengkap untuk melakukan sinkronisasi otomatis dari **Localhost** ke **Server Hostinger** setiap kali Anda melakukan perubahan kode, **tanpa mengganggu, menimpa, atau menghapus data pengguna di database live**.

---

## 🛡️ ATURAN EMAS: MENJAGA KEAMANAN DATABASE LIVE PENGGUNA

Agar perubahan kode di localhost **100% AMAN** bagi data pengguna aktif di Hostinger:

| Komponen | Status Perlindungan | Keterangan & Rationale |
| :--- | :---: | :--- |
| **File `.env` di Server** | 🔒 **DILINDUNGI** | **JANGAN PERNAH menimpa file `.env` di hosting** dengan `.env` localhost. `.env` di hosting menyimpan kredensial database live dan `APP_KEY` produksi. |
| **Folder `storage/` & Uploads** | 🔒 **DILINDUNGI** | Folder `storage/app/public` (foto profil mitra, banner, struk) di hosting **tidak boleh ditimpa/dihapus** saat deploy. |
| **Database Migrations** | ⚠️ **HATI-HATI** | **DILARANG KERAS** menjalankan `migrate:fresh`, `migrate:reset`, atau `db:seed` di hosting karena akan menghapus seluruh data user. Cukup gunakan `php artisan migrate --force` jika ada penambahan kolom/tabel baru. |
| **File Dump SQL** | 🔒 **DILINDUNGI** | File `.sql` di localhost jangan pernah di-import ulang ke hosting karena akan menimpa data transaksi dan user terbaru. |

---

## ⚙️ METODE 1 (SANGAT DIREKOMENDASIKAN): AUTO-DEPLOY VIA GITHUB ACTIONS

Metode ini adalah standar industri: Setiap kali Anda melakukan `git push` dari komputer, GitHub Actions akan secara otomatis mengunggah **hanya file-file yang berubah** ke server Hostinger dalam hitungan detik via FTPS yang aman.

### Langkah 1: Buat Akun FTP Khusus di Hostinger (Opsional / Pakai Akun Utama)
1. Buka **hPanel Hostinger** &rarr; Menu **Files** &rarr; **FTP Accounts**.
2. Catat detail berikut:
   * **FTP Host / IP**: (contoh: `ftp.domainanda.com` atau `156.67.xxx.xxx`)
   * **FTP Username**: (contoh: `u259615093.deployer` atau username utama)
   * **FTP Password**: (password akun FTP Anda)
   * **FTP Port**: `21`

---

### Langkah 2: Daftarkan Rahasia (Secrets) di Repository GitHub
1. Buka halaman repository project Anda di **GitHub**.
2. Klik tab **Settings** &rarr; **Secrets and variables** &rarr; **Actions**.
3. Klik tombol **New repository secret**, lalu tambahkan 3 variabel rahasia ini:
   * `FTP_SERVER` : Isi dengan FTP Host Hostinger Anda (contoh: `ftp.domainanda.com`).
   * `FTP_USERNAME` : Isi dengan FTP Username Anda.
   * `FTP_PASSWORD` : Isi dengan Password FTP Anda.

---

### Langkah 3: Konfigurasi File Workflow (`.github/workflows/deploy.yml`)
File konfigurasi otomatis ini telah kami siapkan di dalam proyek Anda pada jalur:
`.github/workflows/deploy.yml`

Isinya secara otomatis **mengecualikan (ignore)** file `.env`, `storage/`, `.git/`, dan database lokal:
```yaml
name: 🚀 Auto Deploy to Hostinger

on:
  push:
    branches:
      - main  # atau 'master' sesuai branch utama Anda
    paths:
      - 'public_html (web fronted + backend)/**'

jobs:
  web-deploy:
    name: 🎉 Sync Code to Hostinger
    runs-on: ubuntu-latest
    steps:
      - name: 🚚 Get latest code
        uses: actions/checkout@v4
        with:
          fetch-depth: 2

      - name: 📂 Sync files via FTP
        uses: SamKirkland/FTP-Deploy-Action@v4.3.5
        with:
          server: ${{ secrets.FTP_SERVER }}
          username: ${{ secrets.FTP_USERNAME }}
          password: ${{ secrets.FTP_PASSWORD }}
          server-dir: /public_html/
          local-dir: ./public_html (web fronted + backend)/
          exclude: |
            **/.git*
            **/.git*/**
            **/.env
            **/.env.*
            **/storage/logs/**
            **/storage/framework/sessions/**
            **/storage/framework/cache/**
            **/storage/app/public/**
            **/tests/**
            **/*.sql
            **/node_modules/**
```

---

## ⚙️ METODE 2: AUTO-DEPLOY VIA GIT DI HPANEL HOSTINGER

Jika hosting Anda mendukung fitur **Git Integration** di hPanel:

1. Buka **hPanel Hostinger** &rarr; Menu **Advanced** &rarr; **Git**.
2. Masukkan URL Repository GitHub Anda (misal: `https://github.com/username/mywowin.git`).
3. Pilih Branch: `main`.
4. Tentukan Direktori Target: `/public_html`.
5. Salin **Webhook URL** yang diberikan oleh Hostinger.
6. Buka **GitHub** &rarr; **Settings Repository** &rarr; **Webhooks** &rarr; **Add webhook**:
   * **Payload URL**: Tempelkan Webhook URL dari Hostinger.
   * **Content type**: `application/json`.
   * Klik **Add webhook**.
7. Sekarang, setiap `git push`, Hostinger akan otomatis menarik perubahan (*auto-pull*).

---

## ⚙️ METODE 3: SINKRONISASI 1-KLIK VIA SSH KEY DARI WINDOWS TERMINAL

Komputer Anda **sudah memiliki SSH Key** yang terhubung langsung ke Hostinger (`mywowin` di `~/.ssh/config`).

Anda cukup menjalankan script yang telah kami sediakan:
```powershell
./deploy_hostinger.ps1
```
Script ini akan:
1. Mengunggah file-file yang telah diperbarui ke `~/domains/mywowin.com/public_html` via `scp`.
2. Menjaga file `.env` dan database live tetap aman 100% tanpa disentuh.
3. Otomatis membersihkan cache config, routes, dan view Laravel di server (`artisan config:clear`, `route:clear`, `view:clear`).

---

## 📋 DAFTAR PERINTAH ARTISAN (PRODUKSI vs DILARANG)

Jalankan di Terminal SSH Hostinger jika ada pembaruan:

### ✅ PERINTAH YANG AMAN:
```bash
# 1. Bersihkan dan segarkan cache konfigurasi setelah update kode
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Jalankan migrasi baru (JIKA ADA tabel baru saja, data lama tetap utuh)
php artisan migrate --force
```

### ❌ PERINTAH YANG DILARANG DI PRODUKSI:
```bash
# DILARANG: Ini akan menghapus database dan seluruh akun pengguna!
php artisan migrate:fresh
php artisan migrate:reset
php artisan db:seed
```

---

## 🎯 CARA KERJA SEHARI-HARI (WORKFLOW)

Setelah setup selesai, alur kerja harian Anda cukup seperti ini:

1. Anda mengubah kode PHP / Blade di VS Code (komputer lokal).
2. Simpan file dan tes di localhost jika diperlukan.
3. Buka terminal dan jalankan:
   ```bash
   git add .
   git commit -m "Perbaikan fitur X"
   git push origin main
   ```
4. Dalam 10-30 detik, kode di server Hostinger akan otomatis diperbarui dan langsung aktif, **sementara database dan pengguna di server tetap aman 100%**.
