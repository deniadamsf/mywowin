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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'points_used')) {
                $table->integer('points_used')->default(0)->after('total');
            }
            if (!Schema::hasColumn('orders', 'potongan_poin')) {
                $table->decimal('potongan_poin', 12, 2)->default(0)->after('points_used');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'potongan_poin')) {
                $table->dropColumn('potongan_poin');
            }
            if (Schema::hasColumn('orders', 'points_used')) {
                $table->dropColumn('points_used');
            }
        });
    }
};
