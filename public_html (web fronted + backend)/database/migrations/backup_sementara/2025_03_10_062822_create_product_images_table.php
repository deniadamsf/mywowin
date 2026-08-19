<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File; // Tambahan untuk cek folder

return new class extends Migration {
    public function up(): void
    {
        // Pastikan folder penyimpanan gambar sudah ada
        $folderPath = storage_path('app/public/product_images');
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        // Buat tabel product_images
        Schema::create('product_images', function (Blueprint $table) {
            $table->id('id_image'); // Primary Key
            $table->foreignId('id_product')->constrained('products')->onDelete('cascade'); // Foreign Key
            $table->string('image_url'); // Path gambar
            $table->boolean('is_primary')->default(false); // Menandai gambar utama
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
