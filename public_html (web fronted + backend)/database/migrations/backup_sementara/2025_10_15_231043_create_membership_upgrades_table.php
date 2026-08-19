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
        Schema::create('membership_upgrades', function (Blueprint $table) {
            $table->id();

            // Siapa yang di-upgrade? Terhubung ke tabel 'users'
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('from_level'); // Level lama
            $table->string('to_level');   // Level baru
            $table->unsignedBigInteger('amount_paid'); // Biaya yang dibayar

            $table->timestamp('approved_at')->nullable(); // Kapan disetujui

            // Admin mana yang menyetujui? Terhubung ke tabel 'users'
            $table->foreignId('approved_by')->nullable()->constrained('users');

            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_upgrades');
    }
};