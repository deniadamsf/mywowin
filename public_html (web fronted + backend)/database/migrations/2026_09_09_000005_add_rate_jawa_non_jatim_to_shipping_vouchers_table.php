<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('shipping_vouchers')) {
            Schema::table('shipping_vouchers', function (Blueprint $table) {
                if (!Schema::hasColumn('shipping_vouchers', 'rate_jawa_non_jatim')) {
                    $table->decimal('rate_jawa_non_jatim', 12, 2)->default(9500.00)->after('base_rate_per_kg');
                }
            });

            // Update existing record
            DB::table('shipping_vouchers')->update([
                'base_rate_per_kg' => 4500.00,
                'rate_jawa_non_jatim' => 9500.00,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('shipping_vouchers')) {
            Schema::table('shipping_vouchers', function (Blueprint $table) {
                if (Schema::hasColumn('shipping_vouchers', 'rate_jawa_non_jatim')) {
                    $table->dropColumn('rate_jawa_non_jatim');
                }
            });
        }
    }
};
