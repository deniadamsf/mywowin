<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateKantorCabangColumnOnUsersTable extends Migration
{
    public function up()
    {
        // Cek dulu apakah kolom sudah ada
        if (Schema::hasColumn('users', 'kantor_cabang')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('kantor_cabang');
            });
        }

        // Tambahkan kolom kantor_cabang sebagai ENUM
        DB::statement("
            ALTER TABLE users 
            ADD kantor_cabang ENUM('Trenggalek', 'Kediri', 'Madiun', 'Solo', 'Jogja', 'Cirebon', 'Kudus', 'Bogor', 'Serang') 
            NULL AFTER role
        ");
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kantor_cabang');
        });
    }
}
