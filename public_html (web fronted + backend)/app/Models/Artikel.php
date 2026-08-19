<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Artikel extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    protected $fillable = [
        'user_id', 'judul', 'isi', 'foto_artikel'
    ];

    protected $casts = [
    'foto_artikel' => 'array',
    'isi' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
