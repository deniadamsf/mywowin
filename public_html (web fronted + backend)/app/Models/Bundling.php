<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundling extends Model
{
    use HasFactory;

   
    protected $primaryKey = 'id_bundling'; // Primary key
    public $timestamps = true; // Menggunakan created_at & updated_at

    protected $fillable = [
        'nama_bundling',
        'barang_bundling',
        'youtube_link',
        'waktu_diskon_mulai',
        'waktu_diskon_selesai',
        'snk',
        'price',
        'price_before',
        'berat',
    ];

    // Relasi ke tabel Users (satu bundling dimiliki oleh satu user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function products()
{
    return $this->belongsToMany(Product::class, 'bundling_product', 'id_bundling', 'product_id');
}

public function images()
{
    return $this->hasMany(ProductImage::class, 'product_id', 'id');
}


}
