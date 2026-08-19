<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    // Nama tabel jika tidak mengikuti default 'visitors'
    protected $table = 'visitors';

    // Kolom yang boleh diisi secara mass-assignment
    protected $fillable = [
        'ip',
        'country',
        'city',
        'user_agent',
        'visited_at',
    ];

    // Jika tidak pakai timestamps created_at dan updated_at
    public $timestamps = false;
}
