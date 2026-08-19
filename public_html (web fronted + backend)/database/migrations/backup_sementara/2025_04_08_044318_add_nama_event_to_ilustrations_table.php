<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNamaEventToIlustrationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ilustrations', function (Blueprint $table) {
            $table->string('nama_event')->nullable()->after('gambar_login');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ilustrations', function (Blueprint $table) {
            $table->dropColumn('nama_event');
        });
    }
}
