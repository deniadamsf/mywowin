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
        Schema::table('artikels', function (Blueprint $table) {
            if (!Schema::hasColumn('artikels', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('judul');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            if (Schema::hasColumn('artikels', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
