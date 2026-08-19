<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File; // Tambahan untuk cek folder

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    // Pastikan folder penyimpanan gambar sudah ada
          $folderPath = storage_path('app/public/foto_kategori');
          if (!File::exists($folderPath)) {
              File::makeDirectory($folderPath, 0755, true);
          }
    Schema::table('categories', function (Blueprint $table) {
        $table->string('foto_kategori')->nullable()->after('name');
    });
}

public function down()
{
    Schema::table('categories', function (Blueprint $table) {
        $table->dropColumn('foto_kategori');
    });
}

};
