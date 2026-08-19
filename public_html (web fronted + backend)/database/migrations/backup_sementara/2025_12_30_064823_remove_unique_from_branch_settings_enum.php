<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('branch_settings', function (Blueprint $blueprint) {
            // 1. Hapus index unique yang lama
            $blueprint->dropUnique('branch_settings_enum_value_unique');
            
            // 2. Tambahkan index unique ke user_id (agar 1 admin = 1 PT)
            // Cek dulu apakah sudah ada, jika belum baru tambah
            $blueprint->unique('user_id');
        });
    }

    public function down()
    {
        Schema::table('branch_settings', function (Blueprint $blueprint) {
            $blueprint->dropUnique(['user_id']);
            $blueprint->unique('enum_value');
        });
    }
};