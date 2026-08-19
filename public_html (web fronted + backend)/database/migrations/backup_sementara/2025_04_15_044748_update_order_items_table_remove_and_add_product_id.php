<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrderItemsTableRemoveAndAddProductId extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Menghapus kolom 'products_id'
            $table->dropColumn('product_id');
            
            // Menambahkan kolom 'products_id' sebagai foreign key
            $table->unsignedBigInteger('product_id');

            // Menambahkan foreign key constraint dengan referensi ke 'id_product' di tabel 'products'
            $table->foreign('product_id')->references('id_product')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Menghapus foreign key constraint
            $table->dropForeign(['product_id']);
            // Menghapus kolom 'products_id'
            $table->dropColumn('product_id');
        });
    }
}
