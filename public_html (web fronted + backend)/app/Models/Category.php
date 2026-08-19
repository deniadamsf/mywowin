<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Jika ingin mass assignment untuk kolom name
    protected $fillable = ['name', 'foto_kategori'];

    // Relasi satu category bisa punya banyak produk
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
