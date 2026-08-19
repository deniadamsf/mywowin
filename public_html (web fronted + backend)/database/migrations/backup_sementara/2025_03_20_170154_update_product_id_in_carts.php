<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            // Hapus kolom product_id lama
            $table->dropColumn('product_id');

            // Tambahkan kembali dengan foreign key yang benar
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id_product')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            // Hapus foreign key baru saat rollback
            $table->dropForeign(['product_id']);

            // Hapus kolom product_id baru
            $table->dropColumn('product_id');

            // Tambahkan kembali kolom tanpa foreign key
            $table->unsignedBigInteger('product_id');
        });
    }
};


