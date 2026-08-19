<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id(); // Ini primary key carts
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pastikan users memiliki 'id' sebagai PK
            $table->foreignId('product_id')->constrained('products', 'id_product')->onDelete('cascade'); // Sesuaikan dengan PK di 'products'
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};
