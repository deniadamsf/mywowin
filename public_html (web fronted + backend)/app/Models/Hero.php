<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    use HasFactory;
    protected $table = 'heros'; // Nama tabel

    protected $fillable = [
        'nama_event',
        'gambar_hero',
    ];
}
