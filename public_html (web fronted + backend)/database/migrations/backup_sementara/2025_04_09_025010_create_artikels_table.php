<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File; // Tambahan untuk cek folder

return new class extends Migration {
    public function up(): void
    {
        // Pastikan folder penyimpanan gambar sudah ada
        $folderPath = storage_path('app/public/foto_artikel');
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }
        Schema::create('artikels', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID sebagai primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // relasi ke tabel users
            $table->string('judul');
            $table->text('isi'); // untuk konten artikel
            $table->string('foto_artikel')->nullable(); // path foto (boleh kosong)
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikels');
    }
};
