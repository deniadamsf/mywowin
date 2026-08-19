<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesAndAddCategoryIdToProducts extends Migration
{
    public function up()
    {
        // // Buat tabel categories
        // Schema::create('categories', function (Blueprint $table) {
        //     $table->id(); // primary key id
        //     $table->string('name')->unique();
        //     $table->timestamps();
        // });

        // Tambah kolom category_id di products
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id_product')->constrained('categories')->nullOnDelete();
        });
    }

    public function down()
    {
        // Hapus kolom category_id di products dulu
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        // // Hapus tabel categories
        // Schema::dropIfExists('categories');
    }
}
