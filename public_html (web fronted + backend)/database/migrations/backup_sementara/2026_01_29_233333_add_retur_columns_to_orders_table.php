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
    Schema::table('orders', function (Blueprint $table) {
        $table->integer('retur_botol')->default(0)->after('total'); 
        $table->integer('retur_jerigen')->default(0)->after('retur_botol');

        $table->unsignedBigInteger('produk_rusak_id')->nullable()->after('retur_jerigen'); 
        $table->integer('qty_rusak')->default(0)->after('produk_rusak_id');
        $table->string('foto_kerusakan')->nullable()->after('qty_rusak');
        $table->enum('tipe_retur', ['rusak_pengiriman', 'cacat_produksi'])->nullable()->after('foto_kerusakan');
        
        $table->decimal('total_potongan_retur', 15, 2)->default(0)->after('tipe_retur');

        // SESUAIKAN DI SINI: ganti 'id' menjadi 'id_product'
        $table->foreign('produk_rusak_id')->references('id_product')->on('products')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['produk_rusak_id']);
        $table->dropColumn([
            'retur_botol', 'retur_jerigen', 'produk_rusak_id', 
            'qty_rusak', 'foto_kerusakan', 'tipe_retur', 'total_potongan_retur'
        ]);
    });
}
};
