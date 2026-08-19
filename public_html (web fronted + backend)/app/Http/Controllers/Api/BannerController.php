<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hero;
use App\Models\Bundling;

class BannerController extends Controller
{
    // Mengirim gambar Hero ke Flutter
    public function getHeroes()
    {
        // Mengambil data hero terbaru
        $heroes = Hero::latest()->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $heroes
        ], 200);
    }

    // Mengirim Promo Bundling ke Flutter
    public function getBundlings()
    {
        // Mengambil data bundling promo terbaru
        // Anda juga bisa menambahkan filter where('status', 'active') jika diperlukan nanti
        $bundlings = Bundling::latest()->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $bundlings
        ], 200);
    }
}