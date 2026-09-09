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
            $table->string('shipping_courier')->default('J&T Express')->after('catatan');
            $table->string('no_resi')->nullable()->index()->after('shipping_courier');
            $table->string('jnt_order_id')->nullable()->index()->after('no_resi');
            $table->string('jnt_des_code')->nullable()->after('jnt_order_id');
            $table->decimal('total_weight_kg', 8, 2)->default(1.00)->after('jnt_des_code');
            $table->decimal('shipping_cost', 12, 2)->default(0)->after('total_weight_kg');
            $table->string('shipping_status')->nullable()->after('shipping_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_courier',
                'no_resi',
                'jnt_order_id',
                'jnt_des_code',
                'total_weight_kg',
                'shipping_cost',
                'shipping_status',
            ]);
        });
    }
};
