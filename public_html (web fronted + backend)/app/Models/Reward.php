<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $table = 'rewards'; // Nama tabel di DB

    protected $fillable = [
        'nama_reward',
        'deskripsi',
        'points_required',
        'foto_rewards',
    ];
}
