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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id('id_membership'); // Primary Key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign Key ke users.id
            $table->string('nama_toko', 100);
            $table->text('alamat');
            $table->string('no_hp', 15);
            $table->string('nama_sales', 100)->nullable();
            $table->enum('level_membership', ['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond'])->default('Bronze');
            $table->timestamp('last_upgrade')->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
