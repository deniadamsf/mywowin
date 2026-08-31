<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable; 

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
        'username',
        'foto_profile',
        'kantor_cabang',
        'nama_lengkap',
        'status_aktif',
        'last_login_at',
        'login_streak',
        'total_points',
        'points_today',
        'last_daily_claim_at',
        'admin_id',
        'fcm_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'last_daily_claim_at' => 'datetime',
        ];
    }

    public function membership(): HasOne
    {
        return $this->hasOne(Membership::class, "user_id");
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'user_id', 'id');
    }

    // Relasi dengan ClaimedReward
    public function claimedRewards()
    {
        return $this->hasMany(ClaimedReward::class);
    }

    // Di dalam model User
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id'); // Pastikan relasi ini benar
    }

    public function admin()
    {
        // admin_id di tabel users (member) merujuk ke id di tabel users (admin)
        return $this->belongsTo(User::class, 'admin_id');
    }
    
    public function chats()
    {
        return $this->hasMany(Chat::class, 'user_id');
    }
}