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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('config')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed data awal metode pembayaran modular
        $now = now();
        DB::table('payment_methods')->insert([
            [
                'code' => 'transfer',
                'name' => 'Transfer Bank (BCA / BRI)',
                'description' => 'Instruksi rekening resmi muncul setelah konfirmasi',
                'is_active' => true,
                'config' => json_encode([
                    'bank_accounts' => [
                        [
                            'id' => 'bca-1',
                            'bank_name' => 'Bank BCA',
                            'account_number' => '0891234567',
                            'account_holder' => 'PT WOWIN PURNOMO PUTERA',
                            'is_active' => true,
                        ],
                        [
                            'id' => 'bri-1',
                            'bank_name' => 'Bank BRI',
                            'account_number' => '0123-01-000456-53-0',
                            'account_holder' => 'PT SANKE BERSINAR TERANG',
                            'is_active' => true,
                        ]
                    ]
                ]),
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'wa',
                'name' => 'Pesan via WhatsApp',
                'description' => 'Langsung terhubung dengan Admin Wowin',
                'is_active' => true,
                'config' => json_encode([
                    'phone_number' => '6281216301220',
                ]),
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'cod',
                'name' => 'Cash on Delivery (COD)',
                'description' => 'Bayar tunai ke kurir saat barang tiba',
                'is_active' => false,
                'config' => json_encode([]),
                'sort_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
