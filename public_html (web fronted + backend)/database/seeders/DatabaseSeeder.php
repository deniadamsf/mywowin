<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'Admin3',
            'email' => 'admin3@example.com',
            'password' => Hash::make('admin123'), // Gunakan password yang aman
            'role' => 'admin', // Pastikan ada kolom role di tabel users
            'nama_lengkap' => 'Didin',
            'status_aktif' => 'aktif',
        ]);
    }
}

