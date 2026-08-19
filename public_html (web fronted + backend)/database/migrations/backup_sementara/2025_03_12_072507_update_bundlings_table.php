<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('bundlings', function (Blueprint $table) {
            // Hapus kolom waktu_diskon
            $table->dropColumn('waktu_diskon');

            // Tambahkan kolom baru setelah menghapus waktu_diskon
            $table->date('waktu_diskon_mulai')->nullable()->after('id_bundling');
            $table->date('waktu_diskon_selesai')->nullable()->after('waktu_diskon_mulai');
        });
    }

    public function down()
    {
        Schema::table('bundlings', function (Blueprint $table) {
            // Tambahkan kembali kolom waktu_diskon sebagai timestamp (kalau perlu)
            $table->timestamp('waktu_diskon')->nullable();

            // Hapus kolom baru
            $table->dropColumn(['waktu_diskon_mulai', 'waktu_diskon_selesai']);
        });
    }
};

