<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File; // Tambahan untuk cek folder

return new class extends Migration
{
    public function up(): void
    {
          // Pastikan folder penyimpanan gambar sudah ada
          $folderPath = storage_path('app/public/foto_rewards');
          if (!File::exists($folderPath)) {
              File::makeDirectory($folderPath, 0755, true);
          }
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('nama_reward');
            $table->text('deskripsi')->nullable();
            $table->integer('points_required');
            $table->string('foto_rewards')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
