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
        // 1. Buat tabel shipping_vouchers
        if (!Schema::hasTable('shipping_vouchers')) {
            Schema::create('shipping_vouchers', function (Blueprint $table) {
                $table->id();
                $table->string('code')->default('ONGKIR4500');
                $table->string('name')->default('Voucher Diskon Ongkir Rp 4.500');
                $table->string('description')->default('Minimal belanja Rp 10.000 (Maksimal potongan 1 Kg)');
                $table->decimal('min_purchase', 12, 2)->default(10000.00); // Min belanja Rp 10.000
                $table->decimal('discount_amount', 12, 2)->default(4500.00); // Diskon Rp 4.500
                $table->decimal('base_rate_per_kg', 12, 2)->default(4500.00); // Tarif dasar J&T se-Jawa Rp 4.500
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Insert default voucher
            DB::table('shipping_vouchers')->insert([
                'code' => 'ONGKIR4500',
                'name' => 'Voucher Diskon Ongkir Rp 4.500',
                'description' => 'Minimal belanja Rp 10.000 (Gratis Ongkir s/d 1 Kg)',
                'min_purchase' => 10000.00,
                'discount_amount' => 4500.00,
                'base_rate_per_kg' => 4500.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Tambah kolom shipping_discount dan voucher_code di tabel orders
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'shipping_discount')) {
                    $table->decimal('shipping_discount', 12, 2)->default(0)->after('shipping_cost');
                }
                if (!Schema::hasColumn('orders', 'voucher_code')) {
                    $table->string('voucher_code')->nullable()->after('shipping_discount');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_vouchers');

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'shipping_discount')) {
                    $table->dropColumn('shipping_discount');
                }
                if (Schema::hasColumn('orders', 'voucher_code')) {
                    $table->dropColumn('voucher_code');
                }
            });
        }
    }
};
