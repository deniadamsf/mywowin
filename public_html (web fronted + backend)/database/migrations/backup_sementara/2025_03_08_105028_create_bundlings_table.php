<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up()
    {
        Schema::create('bundlings', function (Blueprint $table) {
            $table->id('id_bundling');  // Primary key
            $table->string('gambar_paket')->nullable();  // Bisa null jika belum upload gambar
            $table->timestamp('waktu_diskon')->nullable();  // Waktu diskon
            $table->text('snk');  // Syarat & Ketentuan
            $table->decimal('price', 10, 2);  // Harga setelah diskon
            $table->decimal('price_before', 10, 2);  // Harga sebelum diskon
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Foreign key ke tabel users
            $table->timestamps();  // Kolom created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('bundlings');
    }
};

