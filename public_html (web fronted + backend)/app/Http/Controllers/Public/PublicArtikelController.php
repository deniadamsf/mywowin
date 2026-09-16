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

    public function show($identifier)
    {
        $oldSlugMap = [
            'cara-belanja-di-aplikasi-my-wowin-tutorial-order-grosir-mudah-hemat-2026' => 'cara-belanja-di-aplikasi-my-wowin-order-grosir-murah',
            'keuntungan-daftar-member-my-wowin-7-benefit-eksklusif-kemitraan-2026' => 'keuntungan-daftar-member-my-wowin-benefit-mitra-grosir',
            'cara-daftar-akun-my-wowin-panduan-lengkap-kemitraan-resmi-2026' => 'cara-daftar-akun-my-wowin-panduan-mitra-usaha-grosir',
        ];

        if (isset($oldSlugMap[$identifier])) {
            return redirect()->route('public.artikels.show', $oldSlugMap[$identifier], 301);
        }

        $artikel = Artikel::with('user')
            ->where('slug', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();

        // Jika diakses melalui UUID lama tapi artikel punya slug SEO, redirect 301 permanen ke URL slug bersih
        if ($identifier === $artikel->id && !empty($artikel->slug)) {
            return redirect()->route('public.artikels.show', $artikel->slug, 301);
        }

        return view('public.artikels.show', compact('artikel'));
    }
}