<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Bundling;
use App\Models\Hero;

class CatalogController extends Controller
{
    public function index()
    {
        // 1. Ambil Banner/Hero (Opsional jika ingin ditampilkan di slider mobile)
        $hero = Hero::latest()->first();

        // 2. Ambil Kategori
        $categories = Category::select('id', 'name', 'foto_kategori')->get();

        // 3. Ambil Bundling
        $bundlings = Bundling::all();

        // 4. Ambil Produk beserta Relasi Gambarnya (Eager Loading)
        // Kita menggunakan 'with' agar API otomatis menyertakan array gambar produknya
        $products = Product::with(['images', 'category'])->latest()->get();

        // Karena Anda memiliki Accessor 'hargaPcs' di model Product, 
        // kita perlu memanggil metode append agar field buatan tersebut ikut masuk ke JSON
        $products->each->append('harga_pcs');

        return response()->json([
            'success' => true,
            'message' => 'Data katalog berhasil diambil',
            'data' => [
                'hero' => $hero,
                'categories' => $categories,
                'bundlings' => $bundlings,
                'products' => $products,
            ]
        ], 200);
    }
}