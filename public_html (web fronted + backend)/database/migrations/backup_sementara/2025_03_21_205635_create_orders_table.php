<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Nomor order unik
            $table->unsignedBigInteger('city_id'); // ID kota tujuan (relasi ke cities)
            $table->string('customer_name');
            $table->string('customer_address');
            $table->string('customer_phone');
            $table->decimal('total_price', 10, 2); // Harga total
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'canceled'])->default('pending');
            $table->timestamps();

            // Foreign key ke tabel cities
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
