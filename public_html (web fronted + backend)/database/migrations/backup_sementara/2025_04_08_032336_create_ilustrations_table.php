<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File; // Tambahan untuk cek folder

class CreateIlustrationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan folder penyimpanan gambar sudah ada
        $folderPath = storage_path('app/public/gambar_login');
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        Schema::create('ilustrations', function (Blueprint $table) {
            $table->id();
            $table->string('gambar_login');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ilustrations');
    }
}
