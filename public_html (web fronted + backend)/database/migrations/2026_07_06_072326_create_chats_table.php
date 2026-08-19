<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            
            // 1. Relasi ke tabel users (Member). 
            // Dibuat NULLABLE dan nullOnDelete() agar web TIDAK DOWN jika user dihapus oleh admin.
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // 2. Siapa yang mengirim pesan? ('user' atau 'admin')
            $table->string('sender_role')->default('user');
            
            // 3. Isi pesan dibuat nullable untuk jaga-jaga (misal besok mau tambah fitur kirim gambar saja tanpa teks)
            $table->text('message')->nullable();
            
            // 4. Kapan pesan dibaca
            $table->timestamp('read_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chats');
    }
};