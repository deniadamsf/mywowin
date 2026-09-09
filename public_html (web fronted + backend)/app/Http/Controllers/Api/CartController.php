<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // 1. Mengambil Daftar Keranjang
    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())
            ->with(['product.images', 'bundling.products']) // Eager load produk bundling agar berat dapat dihitung akurat
            ->get();

        // Hitung Subtotal Keseluruhan
        $subtotal = $carts->sum(function($c) {
            return $c->price * $c->quantity; 
        });

        return response()->json([
            'success' => true,
            'data' => $carts,
            'subtotal' => $subtotal
        ]);
    }

    // 2. Menambah Produk ke Keranjang
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id_product',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|in:pcs,karton', 
        ]);

        $product = Product::findOrFail($request->product_id);
        $unit = $request->unit;
        $quantity = $request->quantity;
        $userId = Auth::id();

        // Tentukan harga berdasarkan unit
        $price = ($unit == 'karton') ? $product->harga : $product->harga_pcs;

        // Cek apakah produk dengan unit yang sama sudah ada di keranjang
        $cart = Cart::where('user_id', $userId)
                        ->where('product_id', $product->id_product)
                        ->where('unit', $unit)
                        ->whereNull('bundling_id')
                        ->first();

        if ($cart) {
            $cart->increment('quantity', $quantity);
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $product->id_product,
                'quantity' => $quantity,
                'unit' => $unit,
                'price' => $price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang.',
        ]);
    }

    // 3. Menambah Bundling/Promo ke Keranjang
    public function addBundling(Request $request)
    {
        $request->validate([
            'bundling_id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();
        $quantity = $request->quantity;

        // Ambil data harga bundling
        $bundling = \App\Models\Bundling::where('id_bundling', $request->bundling_id)->first();
        $price = $bundling ? $bundling->price : 0;

        // Cek apakah promo bundling yang sama sudah ada di keranjang
        $cart = Cart::where('user_id', $userId)
                        ->where('bundling_id', $request->bundling_id)
                        ->first();

        if ($cart) {
            $cart->increment('quantity', $quantity);
        } else {
            Cart::create([
                'user_id' => $userId,
                'bundling_id' => $request->bundling_id,
                'product_id' => null, // Dikosongkan karena ini bundling, bukan produk reguler
                'quantity' => $quantity,
                'unit' => 'paket', // Penanda bahwa ini adalah paket promo
                'price' => $price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Yeay! Promo berhasil masuk keranjang!',
        ]);
    }

    // 4. Memperbarui Kuantitas Item Keranjang (+ / -)
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->where('id', $id)->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Item keranjang tidak ditemukan.'
            ], 404);
        }

        // Jika kuantitas 0, hapus dari keranjang
        if ($request->quantity <= 0) {
            $cart->delete();
        } else {
            $cart->update(['quantity' => $request->quantity]);
        }

        $carts = Cart::where('user_id', $userId)
            ->with(['product.images', 'bundling'])
            ->get();

        $subtotal = $carts->sum(function($c) {
            return $c->price * $c->quantity;
        });

        return response()->json([
            'success' => true,
            'message' => 'Kuantitas keranjang berhasil diperbarui.',
            'data' => $carts,
            'subtotal' => $subtotal
        ]);
    }

    // 5. Menghapus Satu Item dari Keranjang
    public function destroy($id)
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->where('id', $id)->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Item keranjang tidak ditemukan.'
            ], 404);
        }

        $cart->delete();

        $carts = Cart::where('user_id', $userId)
            ->with(['product.images', 'bundling'])
            ->get();

        $subtotal = $carts->sum(function($c) {
            return $c->price * $c->quantity;
        });

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang.',
            'data' => $carts,
            'subtotal' => $subtotal
        ]);
    }

    // 6. Mengosongkan Seluruh Item Keranjang
    public function clear()
    {
        $userId = Auth::id();
        Cart::where('user_id', $userId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan.',
            'data' => [],
            'subtotal' => 0
        ]);
    }
}