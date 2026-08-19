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
        Schema::table('carts', function (Blueprint $table) {
            // Kolom baru untuk menyimpan 'pcs' atau 'karton'
            // Kita beri default 'karton' agar data lama Anda aman
            $table->string('unit')->default('karton')->after('quantity');

            // Kolom baru untuk menyimpan harga satuan saat dibeli
            // Kita buat nullable() (boleh kosong) untuk jaga-jaga jika ada data lama
            $table->decimal('price', 15, 2)->nullable()->after('unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('unit');
            $table->dropColumn('price');
        });
    }
};