<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bundlings', function (Blueprint $table) {
            $table->string('youtube_link')->nullable()->after('barang_bundling'); 
            // "after" menaruh kolom setelah "nama_bundling" (opsional)
        });
    }

    public function down(): void
    {
        Schema::table('bundlings', function (Blueprint $table) {
            $table->dropColumn('youtube_link');
        });
    }
};
