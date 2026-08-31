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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('bundling_id')->nullable();
            $table->string('kantor_cabang')->nullable();
            $table->tinyInteger('rating')->unsigned()->default(5); // 1-5
            $table->text('komentar')->nullable();
            $table->json('tags')->nullable(); // e.g. ["Kualitas Bagus", "Pengiriman Cepat"]
            $table->json('foto')->nullable(); // array of image paths
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->text('balasan_admin')->nullable();
            $table->timestamp('balasan_admin_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id_product')->on('products')->onDelete('cascade');
            $table->foreign('bundling_id')->references('id_bundling')->on('bundlings')->onDelete('cascade');

            // Indexes
            $table->index(['product_id', 'is_hidden', 'rating']);
            $table->index(['order_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
