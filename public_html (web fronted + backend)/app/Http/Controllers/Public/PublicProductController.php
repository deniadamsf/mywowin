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
        $product = Product::with(['images', 'reviews.user'])->where('id_product', $id)->firstOrFail();
        
        $reviews = $product->reviews()->where('is_hidden', false)->latest()->paginate(10);
        $totalReviews = $product->reviews()->where('is_hidden', false)->count();
        $avgRating = round($product->reviews()->where('is_hidden', false)->avg('rating') ?: 5.0, 1);
        
        $ratingDistribution = [
            5 => $product->reviews()->where('is_hidden', false)->where('rating', 5)->count(),
            4 => $product->reviews()->where('is_hidden', false)->where('rating', 4)->count(),
            3 => $product->reviews()->where('is_hidden', false)->where('rating', 3)->count(),
            2 => $product->reviews()->where('is_hidden', false)->where('rating', 2)->count(),
            1 => $product->reviews()->where('is_hidden', false)->where('rating', 1)->count(),
        ];

        return view('public.products.detail', compact('product', 'reviews', 'totalReviews', 'avgRating', 'ratingDistribution'));
    }
    public function byCategory($id)
{
    $kategori = Category::findOrFail($id);
    $produk = Product::where('category_id', $id)->get();

    return view('public.products.by-category', compact('kategori', 'produk'));
}

}
