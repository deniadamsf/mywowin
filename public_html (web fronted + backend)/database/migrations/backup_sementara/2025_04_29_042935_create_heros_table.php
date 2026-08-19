<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File; // Tambahan untuk cek folder

class CreateHerosTable extends Migration
{
    public function up()
    {
        // Pastikan folder penyimpanan gambar sudah ada
        $folderPath = storage_path('app/public/gambar_hero');
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }
        Schema::create('heros', function (Blueprint $table) {
            $table->id();
            $table->string('nama_event');
            $table->string('gambar_hero');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('heros');
    }
}
