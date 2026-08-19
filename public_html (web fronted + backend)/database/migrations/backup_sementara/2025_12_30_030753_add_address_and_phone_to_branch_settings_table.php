<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('branch_settings', function (Blueprint $table) {
        $table->text('alamat')->nullable()->after('nama_pt'); // Menambah kolom alamat
        $table->string('no_telp')->nullable()->after('alamat'); // Menambah kolom no telp
    });
}

public function down(): void
{
    Schema::table('branch_settings', function (Blueprint $table) {
        $table->dropColumn(['alamat', 'no_telp']);
    });
}
};
