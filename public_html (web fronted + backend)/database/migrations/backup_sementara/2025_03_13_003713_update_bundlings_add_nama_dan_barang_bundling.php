<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File; // Tambahan untuk cek folder


return new class extends Migration {
    public function up(): void {
         // Pastikan folder penyimpanan gambar sudah ada
         $folderPath = storage_path('app/public/barang_bundling');
         if (!File::exists($folderPath)) {
             File::makeDirectory($folderPath, 0755, true);
         }
        Schema::table('bundlings', function (Blueprint $table) {
            $table->dropColumn('gambar_paket'); // Hapus kolom lama
            $table->string('nama_bundling')->after('id_bundling'); // Tambah kolom nama bundling
            $table->string('barang_bundling')->nullable()->after('nama_bundling'); // Tambah kolom gambar
        });
    }

    public function down(): void {
        Schema::table('bundlings', function (Blueprint $table) {
            $table->string('gambar_paket')->nullable(); // Tambahkan kembali jika rollback
            $table->dropColumn(['nama_bundling', 'barang_bundling']); // Hapus kalau rollback
        });
    }
};
