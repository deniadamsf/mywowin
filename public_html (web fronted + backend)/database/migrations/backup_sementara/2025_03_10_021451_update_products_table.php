<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Hapus foreign key dan kolom user_id
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // Tambahkan kolom baru
            $table->integer('isi_ml')->after('nama_produk');
            $table->string('no_bpom')->nullable()->after('isi_ml');
            $table->string('no_halal')->nullable()->after('no_bpom');
            $table->integer('isi_karton')->after('no_halal');
            $table->text('rekom_guna')->nullable()->after('isi_karton');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan kembali user_id jika rollback
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Hapus kolom baru saat rollback
            $table->dropColumn(['isi_ml', 'no_bpom', 'no_halal', 'isi_karton', 'rekom_guna']);
        });
    }
};
