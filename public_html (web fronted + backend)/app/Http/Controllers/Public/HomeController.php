<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hero;
use App\Models\Product;
use App\Models\Category;
use App\Models\Bundling; // Tambahkan ini agar Bundling dikenali

class HomeController extends Controller
{
    public function index()
    {
        $hero = Hero::latest()->first(); 
        $kategoriList = Category::all(); // Ambil semua kategori
        $bundlings = Bundling::all(); // Ambil semua bundling
        $products = Product::take(6)->get(); // Ambil 6 produk unggulan

        return view('public.index', compact('hero', 'kategoriList', 'bundlings', 'products'));
    }
}
