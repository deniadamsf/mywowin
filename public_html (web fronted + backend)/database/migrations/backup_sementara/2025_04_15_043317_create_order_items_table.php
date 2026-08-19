<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel order_items.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products', 'id_product')->onDelete('cascade'); // Menyesuaikan ke id_product
            $table->string('product_name'); // Menyimpan nama produk saat transaksi
            $table->integer('quantity'); // Jumlah produk yang dibeli
            $table->decimal('price', 15, 2); // Harga satuan produk saat dibeli
            $table->timestamps();
        });
    }

    /**
     * Balikkan migrasi untuk menghapus tabel order_items.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
