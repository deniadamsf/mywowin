<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

   protected $fillable = [
        'user_id', 
        'product_id', 
        'quantity', 
        'bundling_id',
        'unit',   // <-- Ditambahkan
        'price'   // <-- Ditambahkan
    ];

    public function product()
{
    return $this->belongsTo(Product::class, 'product_id', 'id_product');
}

public function bundling()
{
    return $this->belongsTo(Bundling::class, 'bundling_id', 'id_bundling');
}


    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
