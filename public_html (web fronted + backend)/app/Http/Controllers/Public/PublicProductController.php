<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class PublicProductController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');

        if ($query) {
            // Pencarian produk berdasarkan nama
            $products = Product::with('images')
                ->where('nama_produk', 'like', '%' . $query . '%')
                ->latest()
                ->get();

            // Jika hanya ada satu hasil, redirect ke halaman detail produk
            if ($products->count() === 1) {
                return redirect()->route('products.detail', ['id' => $products->first()->id_product]);
            }

            return view('public.products.index', compact('products', 'query'));
        }

        // Jika tidak ada pencarian, tampilkan semua produk
        $products = Product::with('images')->latest()->get();
        return view('public.products.index', compact('products'));
    }

    public function detail($id)
    {
        $product = Product::with('images')->where('id_product', $id)->firstOrFail();
        return view('public.products.detail', compact('product'));
    }
    public function byCategory($id)
{
    $kategori = Category::findOrFail($id);
    $produk = Product::where('category_id', $id)->get();

    return view('public.products.by-category', compact('kategori', 'produk'));
}

}
