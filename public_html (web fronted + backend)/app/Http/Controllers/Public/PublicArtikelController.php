<?php

namespace App\Http\Controllers\Public;

use App\Models\Artikel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublicArtikelController extends Controller
{
   public function index(Request $request)
{
    $query = Artikel::with('user');

    // Menangani logika pengurutan (Sort By)
    $sort = $request->get('sort');

    if ($sort === 'oldest') {
        $query->oldest(); // Mengurutkan dari yang terlama ke terbaru
    } elseif ($sort === 'popular') {
        // Ganti 'views' dengan nama kolom yang Anda gunakan untuk menghitung jumlah pembaca
        // Jika tidak ada kolom pembaca, biarkan latest()
        $query->orderBy('views', 'desc'); 
    } else {
        // Default (newest)
        $query->latest(); 
    }

    $artikels = $query->paginate(10); 

    // Pastikan parameter sort tetap terbawa saat ganti halaman (pagination)
    $artikels->appends(['sort' => $sort]);

    return view('public.artikels.index', compact('artikels'));
}

    public function show($uuid)
    {
        $artikel = Artikel::with('user')->where('id', $uuid)->firstOrFail();
        return view('public.artikels.show', compact('artikel'));
    }
}