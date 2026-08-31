<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends Model
{
    use HasFactory;

    protected $table = 'memberships';

    protected $primaryKey = 'id_membership';

    protected $fillable = [
        'user_id',
        'nama_toko',
        'alamat',
        'no_hp',
        'nama_sales',
        'level_membership',
        'last_upgrade',
        'status_acc',
    ];

    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }
    // Di dalam model User
public function orders()
{
    return $this->hasMany(Order::class, 'user_id'); // Pastikan relasi ini benar
}


    
}
