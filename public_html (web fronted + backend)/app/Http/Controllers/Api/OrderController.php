<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        // Flutter hanya perlu mengirim 2 data ini
        $request->validate([
            'metode_pembayaran' => 'required|in:wa,cod,transfer',
            'catatan' => 'nullable|string',
        ]);
    
        $user = Auth::user();
        $membership = $user->membership;
        
        // Ambil isi keranjang user (sama persis dengan logika web Anda)
        $cartItems = Cart::with(['product', 'bundling.products'])
            ->where('user_id', $user->id)
            ->get();
    
        if ($cartItems->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Keranjang Anda kosong.'], 400);
        }
    
        // Hitung Subtotal Awal
        $subtotal = $cartItems->sum(function ($item) {
            if ($item->bundling !== null) {
                return ($item->bundling->price ?? 0) * $item->quantity;
            } elseif ($item->product !== null) {
               return $item->price * $item->quantity;
            }
            return 0;
        });

        // Hitung Diskon
        $discountData = $this->getDiscountData($membership, $cartItems, $subtotal);
        $total = $discountData['finalTotal'];

        // Buat Invoice & Simpan Order
        $invoiceNumber = 'INV-' . strtoupper(uniqid());
        
        $order = Order::create([
            'user_id' => $user->id,
            'invoice_number' => $invoiceNumber,
            'total' => $total,
            'status' => 'pending',
            'payment_method' => $request->metode_pembayaran,
            'payment_status' => 'pending',
            'alamat' => $membership->alamat ?? '-',
            'catatan' => $request->catatan,
            'paid_amount' => $total,
        ]);
    
        // Pindahkan dari Keranjang ke Order Items
        foreach ($cartItems as $cart) {
            if ($cart->bundling) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'bundling_id'  => $cart->bundling->id_bundling,
                    'product_name' => 'Bundling: ' . $cart->bundling->nama_bundling,
                    'quantity'     => $cart->quantity,
                    'price'        => $cart->bundling->price ?? 0,
                ]);
            } elseif ($cart->product) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $cart->product->id_product,
                    'product_name' => $cart->product->nama_produk . ' (' . Str::ucfirst($cart->unit) . ')',
                    'quantity'     => $cart->quantity,
                    'price'        => $cart->price,
                ]);
            }
        }

        // Hapus Keranjang
        $user->cart()->delete();
    
        // Balasan JSON ke Flutter
        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil dibuat!',
            'order_id' => $order->id
        ], 200);
    }
    
    public function history()
    {
        $user = Auth::user();
        
        // Menarik semua pesanan milik user beserta rincian barang dan gambarnya
        $orders = Order::where('user_id', $user->id)
                       ->with(['orderItems.product.images', 'orderItems.bundling']) // <--- TAMBAHKAN BARIS INI
                       ->orderBy('created_at', 'desc')
                       ->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ], 200);
    }

    private function getDiscountData($membership, $cartItems, $subtotal)
    {
        // Skema diskon (Sama persis dengan web)
        $discountSchema = [
            'bronze'   => [10 => 0.015,  20 => 0.02,  30 => 0.025, 50 => 0.045],
            'silver'   => [10 => 0.0185, 20 => 0.026, 30 => 0.031, 50 => 0.051],
            'gold'     => [10 => 0.0195, 20 => 0.032, 30 => 0.037, 50 => 0.057],
            'platinum' => [10 => 0.0205, 20 => 0.033, 30 => 0.043, 50 => 0.063],
            'diamond'  => [10 => 0.0255, 20 => 0.043, 30 => 0.058, 50 => 0.083],
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

        $kartonOnlyCount = $cartItems->filter(fn($item) => $item->bundling_id === null && $item->unit === 'karton')->sum('quantity');
        $kartonOnlySubtotal = $cartItems->filter(fn($item) => $item->bundling_id === null && $item->unit === 'karton')
                                        ->sum(fn($item) => $item->price * $item->quantity);

        $percent = 0.0;
        if (isset($discountSchema[$userLevel])) {
            $levelDiscounts = $discountSchema[$userLevel];
            if ($kartonOnlyCount >= 50) $percent = $levelDiscounts[50];
            elseif ($kartonOnlyCount >= 30) $percent = $levelDiscounts[30];
            elseif ($kartonOnlyCount >= 20) $percent = $levelDiscounts[20];
            elseif ($kartonOnlyCount >= 10) $percent = $levelDiscounts[10];
        }
        
        $discountAmount = $kartonOnlySubtotal * $percent;
        return [
            'discountPercent' => $percent * 100,
            'discountAmount'  => $discountAmount,
            'finalTotal'      => $subtotal - $discountAmount,
        ];
    }
}