<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ilustration extends Model
{
    use HasFactory;

    protected $table = 'ilustrations'; // Nama tabel

    protected $fillable = [
        'gambar_login', // Kolom yang bisa diisi
        'nama_event',
    ];
}
