<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // id_user sebenarnya id primary bawaan Laravel jadi gak usah ditambah lagi
            $table->enum('role', ['admin', 'member'])->default('member');
            $table->string('username')->unique();
            $table->string('foto_profile')->nullable(); // bisa null kalo belum upload foto
            $table->string('nama_lengkap');
            $table->enum('status_aktif', ['aktif', 'tidak aktif'])->default('aktif');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'username', 'foto_profile', 'nama_lengkap', 'status_aktif']);
        });
    }
};

