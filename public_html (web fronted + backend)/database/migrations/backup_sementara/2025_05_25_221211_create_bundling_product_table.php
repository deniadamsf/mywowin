<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundlingProductTable extends Migration
{
    public function up()
    {
        Schema::create('bundling_product', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('id_bundling')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();

            $table->foreign('id_bundling')
                ->references('id_bundling')
                ->on('bundlings')
                ->onDelete('set null');

            $table->foreign('product_id')
                ->references('id_product')
                ->on('products')
                ->onDelete('set null');

            $table->timestamps(); // opsional, tapi bagus untuk tracking waktu
        });
    }

    public function down()
    {
        Schema::dropIfExists('bundling_product');
    }
}
