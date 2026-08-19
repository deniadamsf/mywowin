<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('cities', function (Blueprint $table) {
            $table->id(); // Primary key otomatis (auto increment)
            $table->integer('city_id')->unique(); // ID dari API RajaOngkir
            $table->string('city_name'); // Nama kota
            $table->string('province'); // Nama provinsi
            $table->string('postal_code'); // Kode pos
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down() {
        Schema::dropIfExists('cities');
    }
};
