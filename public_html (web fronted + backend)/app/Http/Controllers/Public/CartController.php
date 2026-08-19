<?php

namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Bundling;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
{
   $carts = Cart::where('user_id', Auth::id())
    ->with(['product.bundlings', 'product.images', 'product.category', 'bundling'])
    ->get();


    return view('public.carts.index', compact('carts'));
}


    // Tambah produk ke dalam keranjang
public function store(Request $request)
    {
        // 1. Validasi baru: tambahkan 'unit'
        $request->validate([
            'product_id' => 'required|exists:products,id_product',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|in:pcs,karton', // <-- Validasi baru
        ]);

        $product = Product::findOrFail($request->product_id);
        $unit = $request->unit;
        $quantity = $request->quantity;
        $userId = Auth::id();

        // 2. Tentukan harga berdasarkan unit
        //    (Ini membutuhkan accessor 'hargaPcs' di model Product)
        $price = ($unit == 'karton') ? $product->harga : $product->harga_pcs;

        // 3. Cek keranjang berdasarkan product_id DAN unit
        $cart = Cart::where('user_id', $userId)
                        ->where('product_id', $product->id_product)
                        ->where('unit', $unit) // <-- Cek unit yang sama
                        ->whereNull('bundling_id') // Pastikan bukan bundling
                        ->first();

        if ($cart) {
            // 4. Jika sudah ada (misal, 2 Pcs lalu tambah 3 Pcs lagi), update quantity
            $cart->increment('quantity', $quantity);
        } else {
            // 5. Jika belum ada, buat item keranjang baru
            Cart::create([
                'user_id' => $userId,
                'product_id' => $product->id_product,
                'quantity' => $quantity,
                'bundling_id' => null, // Pastikan di-set null
                'unit' => $unit,       // <-- Simpan unit baru
                'price' => $price,     // <-- Simpan harga satuan baru
            ]);
        }

        // 6. Ganti response ke JSON (karena frontend akan pakai fetch)
        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang.',
        ]);
    }

   // Update jumlah produk dalam keranjang (AJAX compatible)
public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // 1. Cari keranjang, pastikan milik user yang login
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        // 2. Update quantity
        $cart->update(['quantity' => $request->quantity]);

        // 3. Hitung harga item BARU (PAKAI HARGA TERSIMPAN)
        // Cek jika ini bundling
        if ($cart->bundling_id) {
            $price = $cart->bundling->price; // Ambil harga bundling
        } else {
            $price = $cart->price; // <-- AMBIL HARGA DARI 'price' DI TABEL CARTS
        }
        $total = $price * $cart->quantity;

        // 4. Hitung subtotal seluruh keranjang user (LOGIKA BARU)
        $carts = Cart::where('user_id', Auth::id())->get();
        
        $subtotal = $carts->sum(function($c) {
            if ($c->bundling_id) {
                // Jika bundling, pakai harga bundling
                return ($c->bundling->price ?? 0) * $c->quantity;
            } else {
                // Jika produk biasa, PAKAI HARGA TERSIMPAN DI 'price'
                return $c->price * $c->quantity; 
            }
        });

        // 5. Kembalikan response JSON (ini sudah benar)
        return response()->json([
            'success' => true,
            'total_formatted' => number_format($total,0,',','.'),
            'subtotal_formatted' => number_format($subtotal,0,',','.')
        ]);
    }



//     public function addToCart($productId)
// {
//     // Cek apakah produk ada di database
//     $product = Product::find($productId);
//     if (!$product) {
//         return redirect()->back()->with('error', 'Produk tidak ditemukan.');
//     }

//     $user = Auth::user();

//     // Jika produk ada, tambah ke keranjang
//     $cartItem = Cart::create([
//         'user_id' => $user->id,
//         'product_id' => $product->id, // Pastikan product_id valid
//         'quantity' => 1, // Menambahkan 1 produk
//     ]);

//     return redirect()->route('carts.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
// }



    // Hapus produk dari keranjang
    public function destroy($id)
    {
        Cart::findOrFail($id)->delete();
        return redirect()->route('carts.index')->with('cd', 'Produk dihapus dari keranjang!');
    }
public function addBundling(Request $request)
{
    $request->validate([
        'bundling_id' => 'required|exists:bundlings,id_bundling',
        'quantity' => 'integer|min:1'
    ]);

    $bundlingId = $request->input('bundling_id');
    $quantity = $request->input('quantity', 1);

    $bundling = Bundling::findOrFail($bundlingId);

    // Cek apakah bundling sudah ada di keranjang user
    $cart = Cart::where('user_id', Auth::id())
        ->where('product_id', null) // misal bundling disimpan dengan product_id null dan bundling_id di kolom lain
        ->where('bundling_id', $bundlingId)
        ->first();

    if ($cart) {
        $cart->increment('quantity', $quantity);
    } else {
        Cart::create([
            'user_id' => Auth::id(),
            'bundling_id' => $bundlingId,
            'quantity' => $quantity,
            // Jika produk biasa pake product_id, bundling bisa pake bundling_id
        ]);
    }

    return redirect()->route('carts.index')->with('trigger', 'Bundling berhasil ditambahkan ke keranjang!');
}



}
