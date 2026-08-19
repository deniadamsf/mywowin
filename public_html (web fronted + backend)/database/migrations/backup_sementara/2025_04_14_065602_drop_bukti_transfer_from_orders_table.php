<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File; // Tambahan untuk cek folder

class DropBuktiTransferFromOrdersTable extends Migration
{
    /**
     * Menjalankan migration untuk menghapus dan menambah kolom bukti_transfer.
     *
     * @return void
     */
    public function up()
    {
        // Pastikan folder penyimpanan gambar sudah ada
        $folderPath = storage_path('app/public/bukti_transfer');
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        Schema::table('orders', function (Blueprint $table) {
            // Menghapus kolom bukti_transfer
            $table->dropColumn('bukti_transfer');
        });

        Schema::table('orders', function (Blueprint $table) {
            // Menambahkan kembali kolom bukti_transfer
            $table->string('bukti_transfer')->nullable()->after('status'); // Tambahkan sesuai kebutuhan
        });
    }

    /**
     * Membatalkan perubahan jika migration dibatalkan.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menghapus kolom bukti_transfer jika migration dibatalkan
            $table->dropColumn('bukti_transfer');
        });
    }
}
