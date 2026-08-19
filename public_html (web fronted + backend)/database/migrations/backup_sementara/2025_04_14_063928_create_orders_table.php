<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Relasi ke user (member)
            $table->unsignedBigInteger('user_id');

            // Nomor invoice: WWN-YYYYMMDD-001
            $table->string('invoice_number')->unique();

            // Total belanja
            $table->decimal('total', 10, 2);

            // ENUM: status pesanan
            $table->enum('status', ['pending', 'paid', 'shipped', 'completed', 'canceled'])
                  ->default('pending');

            // ENUM: metode pembayaran
            $table->enum('payment_method', ['wa', 'transfer', 'cod'])
                  ->default('wa');

            // Alamat dan catatan
            $table->text('alamat');
            $table->text('catatan')->nullable();

            // Pembayaran manual (admin input)
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->string('bukti_transfer')->nullable();
            $table->timestamp('paid_at')->nullable();

            // Timestamps
            $table->timestamps();

            // Foreign key ke users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

