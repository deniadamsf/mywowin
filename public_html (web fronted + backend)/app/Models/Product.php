<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id_product';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_produk',
        'isi_ml',
        'berat',
        'no_bpom',
        'no_halal',
        'isi_karton',
        'rekom_guna',
        'harga',
        'category_id',
    ];

    // Relasi ke ProductImage (One to Many)
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id_product');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function bundlings()
    {
        return $this->belongsToMany(Bundling::class, 'bundling_product', 'product_id', 'id_bundling');
    }

    /**
     * =================================================================
     * FUNGSI BARU YANG WAJIB DITAMBAHKAN
     * =================================================================
     * Dapatkan harga per pcs secara dinamis.
     */
    protected function hargaPcs(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => 
                // Cek apakah data ada untuk menghindari error
                (isset($attributes['isi_karton']) && $attributes['isi_karton'] > 0 && isset($attributes['harga'])) 
                    // Jika ada, hitung harga pcs
                    ? $attributes['harga'] / $attributes['isi_karton'] 
                    // Jika tidak, pakai harga karton
                    : ($attributes['harga'] ?? 0),
        );
    }
    
    public function orders_retur()
{
    return $this->hasMany(Order::class, 'produk_rusak_id', 'id_product');
}

}