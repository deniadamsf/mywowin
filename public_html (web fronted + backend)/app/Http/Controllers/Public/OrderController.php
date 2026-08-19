<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Membership;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // Fungsi untuk meng-generate No. Pesanan
    public function generateOrderNumber()
    {
        $tanggal = Carbon::now()->format('Ymd');
        $orderCountToday = Order::whereDate('created_at', Carbon::today())->count();
        $hurufAkhir = $this->numberToLetters($orderCountToday); // Panggil dengan $this->
        $orderNumber = 'O-' . $tanggal . '-' . $hurufAkhir;

        return $orderNumber;
    }

    private function numberToLetters($number)
    {
        $letters = '';
        while ($number >= 0) {
            $letters = chr($number % 26 + 65) . $letters;
            $number = floor($number / 26) - 1;
            if ($number < 0) break;
        }
        return str_pad($letters, 4, 'A', STR_PAD_LEFT);
    }




    public function index(Request $request)
    {
        $user = Auth::user(); // User yang sedang login
        $membership = Membership::where('user_id', $user->id)->first();
    
        $subtotal = 0;
        $cartItems = collect(); // Kosong dulu
    
        // 1. Cek apakah ada produk quick buy
        if (session()->has('quick_order')) {
            $productId = session('quick_order');
            $product = Product::find($productId);
    
            if ($product) {
                // Buat item seperti dari keranjang
                $cartItems->push((object)[
                    'product' => $product,
                    'quantity' => 1,
                ]);
                $subtotal = $product->harga;
            }
    
            // Hapus dari session supaya nggak muncul terus-terusan
            session()->forget('quick_order');
        } else {
            // 2. Normal: Ambil data dari keranjang user
            $cartItems = Cart::with(['product', 'bundling.products']) // pastikan relasi products ada di bundling
                ->where('user_id', $user->id)
                ->get()
                ->filter(fn($item) => $item->product !== null || $item->bundling !== null);


    
            $subtotal = $cartItems->sum(function ($item) {
    if ($item->bundling !== null) {
        return ($item->bundling->price ?? 0) * $item->quantity;
    } elseif ($item->product !== null) {
        return $item->price * $item->quantity;
    }
    return 0;
});


}



        $discountData = $this->getDiscountData($membership, $cartItems, $subtotal);
        $total = $discountData['finalTotal']; // <-- PERBAIKAN: Set $total ke finalTotal

        $orderNumber = $this->generateOrderNumber();

       
    
        return view('public.orders.index', compact(
            'user',
            'membership',
            'cartItems',
            'subtotal',
            'total',
            'orderNumber',
            'discountData',
        ));
    }
    

    
    public function create()
    {
        $orders = Order::all();
        return view('public.orders.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:wa,cod,transfer',
            'catatan' => 'nullable|string',
        ]);
    
        $user = Auth::user();
        $membership = $user->membership;
        
        // Cek jika ada produk quick buy di session
        $cartItems = collect();
        $subtotal = 0;
    
        if (session()->has('quick_order')) {
            $productId = session('quick_order');
            $product = Product::find($productId);
            
            if ($product) {
                // Jika produk ada, tambahkan ke cartItems
                $cartItems->push((object)[
                    'product' => $product,
                    'quantity' => 1,
                ]);
                $subtotal = $product->harga;
            }
    
            // Hapus session quick buy setelah digunakan
            session()->forget('quick_order');
        } else {
          $cartItems = Cart::with(['product', 'bundling.products'])
            ->where('user_id', $user->id)
            ->get();


        
    
            if ($cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'Keranjang Anda kosong.');
            }
    
          $subtotal = $cartItems->sum(function ($item) {
    if ($item->bundling !== null) {
        return ($item->bundling->price ?? 0) * $item->quantity;
    } elseif ($item->product !== null) {
       return $item->price * $item->quantity;
    }
    return 0;
});



        }
    
        // Buat nomor pesanan
        $discountData = $this->getDiscountData($membership, $cartItems, $subtotal);
        $total = $discountData['finalTotal'];

        $invoiceNumber = 'INV-' . strtoupper(uniqid());
        
    
        // Tentukan status berdasarkan metode pembayaran
        $paymentMethod = $request->input('metode_pembayaran');
        $status = 'pending'; // semua order pending
    
        // Simpan ke tabel orders
       $order = Order::create([
            'user_id' => $user->id,
            'admin_id' => $user->admin_id, // <--- TAMBAHKAN INI: Agar pesanan tahu Admin mana yang memegang PT-nya
            'invoice_number' => $invoiceNumber,
            'total' => $total,
            'status' => 'pending',
            'bukti_transfer' => null,
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'alamat' => $membership->alamat,
            'catatan' => $request->input('catatan'),
            'paid_amount' => $total,
            'paid_at' => null,
        ]);
    
foreach ($cartItems as $cart) {
    if ($cart->bundling) {
        // ✅ Produk bundling: simpan sebagai 1 baris order_items
        $bundling = $cart->bundling;

        OrderItem::create([
            'order_id'     => $order->id,
            'product_id'   => null, // bundling tidak punya product_id spesifik
            'bundling_id'  => $bundling->id_bundling,
            'product_name' => 'Bundling: ' . $bundling->nama_bundling,
            'quantity'     => $cart->quantity,
            'price'        => $bundling->price ?? 0,
        ]);
    } elseif ($cart->product) {
        // ✅ Produk biasa
        $product = $cart->product;

        OrderItem::create([
            'order_id'     => $order->id,
            'product_id'   => $product->id_product,
            'bundling_id'  => null,
            'product_name' => $product->nama_produk . ' (' . Str::ucfirst($cart->unit) . ')',
            'quantity'     => $cart->quantity,
            'price'        => $cart->price,
        ]);
    }
}


        // Kosongkan keranjang setelah pesanan
        $user->cart()->delete();
    
        // Redirect dengan sukses dan ID pesanan
        return redirect()->route('public.trackings.index', ['id' => $order->id])
            ->with('orders', 'Pesanan berhasil dibuat.');
    }
    
    public function quickBuy($productId)
    {
        // Menyimpan ID produk ke session
        session(['quick_order' => $productId]);
    
        // Redirect ke halaman selesaikan pesanan
        return redirect()->route('public.orders.index');
    }
    
    /**
     * Menghitung diskon berdasarkan skema membership.
     * (FUNGSI BARU)
     */
   private function getDiscountData($membership, $cartItems, $subtotal)
{
     $discountSchema = [
        'bronze'   => [10 => 0.015,  20 => 0.02,  30 => 0.025, 50 => 0.045],
        'silver' => [10 => 0.0185, 20 => 0.026, 30 => 0.031, 50 => 0.051],
        'gold' => [10 => 0.0195, 20 => 0.032, 30 => 0.037, 50 => 0.057],
        'platinum' => [10 => 0.0205, 20 => 0.033, 30 => 0.043, 50 => 0.063],
        'diamond' => [10 => 0.0255, 20 => 0.043, 30 => 0.058, 50 => 0.083],
    ];
    
    // --- PERBAIKAN LOGIKA DISKON (ANTI-BOBOL & ANTI-ERROR) ---
    // 1. Default: Semua orang adalah pelanggan biasa (Diskon 0%)
    $userLevel = 'reguler'; 

    // 2. Jika punya membership DAN sudah di-ACC, berikan hak diskonnya
    if ($membership && $membership->status_acc === 'approved') {
        $userLevel = strtolower($membership->level_membership);
    }
    // ------------------------------------------------------------------
    // ------------------------------------------------------------------

    // --- LOGIKA PERBAIKAN DI SINI ---
    
    // 1. Hitung Quantity HANYA untuk produk yang satuannya KARTON
    // (Bundling dilewati, Produk satuan Pcs juga dilewati)
    $kartonOnlyCount = $cartItems->filter(function($item) {
        return $item->bundling_id === null && $item->unit === 'karton';
    })->sum('quantity');

    // 2. Hitung Subtotal HANYA untuk produk yang satuannya KARTON
    // (Karena kamu bilang Pcs nggak dapet diskon, maka subtotal Pcs jangan ikut dikalikan diskon)
    $kartonOnlySubtotal = $cartItems->filter(function($item) {
        return $item->bundling_id === null && $item->unit === 'karton';
    })->sum(function($item) {
        return $item->price * $item->quantity;
    });

    // 3. Tentukan persen diskon berdasarkan jumlah KARTON
    $percent = 0.0;
    if (isset($discountSchema[$userLevel])) {
        $levelDiscounts = $discountSchema[$userLevel];
        
        if ($kartonOnlyCount >= 50) {
            $percent = $levelDiscounts[50];
        } elseif ($kartonOnlyCount >= 30) {
            $percent = $levelDiscounts[30];
        } elseif ($kartonOnlyCount >= 20) {
            $percent = $levelDiscounts[20];
        } elseif ($kartonOnlyCount >= 10) {
            $percent = $levelDiscounts[10];
        }
    }
    
    // 4. Hitung nominal diskon (Hanya memotong harga barang-barang karton)
    $discountAmount = $kartonOnlySubtotal * $percent;
    
    // 5. Total Akhir = Subtotal (Semua) - Diskon (Khusus Karton)
    $finalTotal = $subtotal - $discountAmount;
    
    return [
        'discountPercent' => $percent * 100,
        'discountAmount'  => $discountAmount,
        'finalTotal'      => $finalTotal,
    ];
}

    public function show($id)
    {
        // Implementasi untuk menampilkan detail pesanan
    }

    public function edit($id)
    {
        // Implementasi untuk form edit pesanan
    }

    public function update(Request $request, $id)
    {
        // Implementasi untuk menyimpan update pesanan
    }

    public function destroy($id)
    {
        // Implementasi untuk menghapus pesanan
    }
}
