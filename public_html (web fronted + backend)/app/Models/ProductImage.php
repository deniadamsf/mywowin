<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $table = 'product_images';
    protected $primaryKey = 'id_image';
   

    protected $fillable = [
        'product_id',
        'image_url',
        'is_primary'
    ];

    // Relasi ke Product (Many to One)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }

}
