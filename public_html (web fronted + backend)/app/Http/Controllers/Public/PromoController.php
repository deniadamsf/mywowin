<?php

namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller; // Tambahkan ini untuk memastikan Controller dikenali
use Illuminate\Http\Request;
use App\Models\Bundling;

class PromoController extends Controller
{
    public function index()
{
    $bundlings = Bundling::all();
    return view('public.promo.index', compact('bundlings'));
} // ✅ Function `index()` sudah ditutup dengan benar

public function detail($id)
{
    $bundling = Bundling::where('id_bundling', $id)->firstOrFail();
    return view('public.promo.detail', compact('bundling'));
}
public function show($id)
{
    $bundling = Bundling::with('products')->findOrFail($id);
    return view('public.promo.detail', compact('bundling'));
}

    
}