<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateUsersTableAddKantorCabangAndEnumDeveloper extends Migration
{
    public function up()
    {
        // Tambahkan kolom kantor_cabang ke tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->string('kantor_cabang')->nullable()->after('foto_profile');
        });

        // Update enum role, tambahkan 'developer'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('member', 'admin', 'super_admin', 'developer') NOT NULL");
    }

    public function down()
    {
        // Hapus kolom kantor_cabang
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kantor_cabang');
        });

        // Rollback enum role tanpa 'developer'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('member', 'admin', 'super_admin') NOT NULL");
    }
}

