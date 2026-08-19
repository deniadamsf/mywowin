<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_role',
        'message',
        'read_at',
    ];

    // Relasi balik ke User (Member)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}