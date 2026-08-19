<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('memberships', function (Blueprint $table) {
            // Hapus kolom last_upgrade jika ada
            $table->dropColumn('last_upgrade');
            
            // Tambahkan kolom last_upgrade dengan tipe decimal
            $table->decimal('last_upgrade', 15, 2)->default(0); // Total pembelian terakhir
        });
    }
    
    public function down()
    {
        Schema::table('memberships', function (Blueprint $table) {
            // Kembalikan perubahan, hapus kolom baru dan tambahkan kolom lama
            $table->dropColumn('last_upgrade');
        });
    }
    
};
