<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'order_id',
        'user_id',
        'product_id',
        'bundling_id',
        'kantor_cabang',
        'rating',
        'komentar',
        'tags',
        'foto',
        'is_anonymous',
        'is_hidden',
        'balasan_admin',
        'balasan_admin_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'tags' => 'array',
        'foto' => 'array',
        'is_anonymous' => 'boolean',
        'is_hidden' => 'boolean',
        'balasan_admin_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }

    public function bundling()
    {
        return $this->belongsTo(Bundling::class, 'bundling_id', 'id_bundling');
    }
}
