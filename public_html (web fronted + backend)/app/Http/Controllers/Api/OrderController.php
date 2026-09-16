<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        // Ambil metode pembayaran yang sedang aktif dari database
        $activePaymentCodes = \App\Models\PaymentMethod::getActiveCodes();
        if (empty($activePaymentCodes)) {
            $activePaymentCodes = ['wa', 'transfer'];
        }

        $request->validate([
            'metode_pembayaran' => ['required', 'string', \Illuminate\Validation\Rule::in($activePaymentCodes)],
            'catatan' => 'nullable|string',
            'use_points' => 'nullable|boolean',
            'alamat' => 'nullable|string',
        ], [
            'metode_pembayaran.in' => 'Metode pembayaran yang dipilih sedang dinonaktifkan oleh Admin.',
        ]);
    
        $user = Auth::user();
        $membership = $user->membership;
        
        // Tentukan alamat pengiriman (prioritaskan alamat yang dipilih di checkout)
        $alamatPengiriman = ($request->filled('alamat') && trim($request->alamat) !== '-')
            ? trim($request->alamat)
            : ($membership->alamat ?? '-');

        // Update alamat membership jika pembeli mengubah alamat di checkout
        if ($request->filled('alamat') && trim($request->alamat) !== '-' && $membership) {
            $membership->update(['alamat' => $alamatPengiriman]);
        }
        
        // Ambil isi keranjang user
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

        // Hitung Diskon Membership
        $discountData = $this->getDiscountData($membership, $cartItems, $subtotal);
        $totalAfterDiscount = $discountData['finalTotal'];

        // Hitung estimasi berat pesanan (Kg) sesuai acuan timbangan fisik & J&T
        $totalWeightKg = \App\Services\JntService::calculateCartWeight($cartItems);

        // Hitung estimasi ongkir J&T Express & evaluasi Voucher Diskon Ongkir
        $shippingCalculation = \App\Services\JntService::calculateShippingCost($totalWeightKg, $alamatPengiriman, $subtotal);
        $shippingCost = (float) ($shippingCalculation['shipping_cost'] ?? 0);
        $shippingDiscount = (float) ($shippingCalculation['shipping_discount'] ?? 0);
        $netShippingCost = (float) ($shippingCalculation['net_shipping_cost'] ?? $shippingCost);
        $voucherCode = $shippingCalculation['voucher_code'] ?? null;

        // Hitung Potongan Poin Loyalitas (1 Poin = Rp 1)
        $pointsUsed = 0;
        $potonganPoin = 0;
        $totalBeforePoints = $totalAfterDiscount + $netShippingCost;
        if ($request->boolean('use_points') && ($user->total_points ?? 0) > 0) {
            $pointsUsed = min((int)$user->total_points, (int)floor($totalBeforePoints));
            $potonganPoin = (float)$pointsUsed;
            $user->decrement('total_points', $pointsUsed);
        }
        $total = max(0, $totalBeforePoints - $potonganPoin);

        // Buat Invoice & Simpan Order
        $invoiceNumber = 'INV-' . strtoupper(uniqid());
        
        $order = Order::create([
            'user_id' => $user->id,
            'invoice_number' => $invoiceNumber,
            'total' => $total,
            'points_used' => $pointsUsed,
            'potongan_poin' => $potonganPoin,
            'shipping_courier' => 'J&T Express (EZ)',
            'total_weight_kg' => $totalWeightKg,
            'shipping_cost' => $shippingCost,
            'shipping_discount' => $shippingDiscount,
            'voucher_code' => $voucherCode,
            'shipping_zone' => $shippingCalculation['zone'] ?? 'jatim_madura',
            'shipping_status' => 'Menunggu Diproses',
            'status' => 'pending',
            'payment_method' => $request->metode_pembayaran,
            'payment_status' => 'pending',
            'payment_deadline' => ($request->metode_pembayaran === 'transfer') ? Carbon::now()->addHours(24) : null,
            'alamat' => $alamatPengiriman,
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

        $order->load(['orderItems.product.images', 'orderItems.bundling']);
    
        // Balasan JSON ke Flutter
        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil dibuat!',
            'order_id' => $order->id,
            'data' => $order,
        ], 200);
    }
    
    public function history()
    {
        $user = Auth::user();
        
        // Batalkan otomatis pesanan transfer yang telah melewati batas 24 jam
        Order::cancelExpiredOrders();

        // Menarik semua pesanan milik user beserta rincian barang dan gambarnya
        $orders = Order::where('user_id', $user->id)
                       ->with(['orderItems.product.images', 'orderItems.bundling'])
                       ->orderBy('created_at', 'desc')
                       ->get()
                       ->map(function ($order) {
                           $order->bukti_transfer_url = $order->bukti_transfer ? asset('storage/' . $order->bukti_transfer) : null;
                           return $order;
                       });

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ], 200);
    }

    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'bukti_transfer.required' => 'File bukti transfer wajib diunggah.',
            'bukti_transfer.image'    => 'File bukti transfer harus berupa gambar.',
            'bukti_transfer.mimes'    => 'Format gambar harus JPEG, PNG, JPG, atau WEBP.',
            'bukti_transfer.max'      => 'Ukuran file maksimal adalah 5MB.',
        ]);

        $user = Auth::user();

        // Auto-cancel yang kadaluarsa sebelum memproses
        Order::cancelExpiredOrders();

        $order = Order::where('user_id', $user->id)->findOrFail($id);

        if ($order->status === 'canceled') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Pesanan telah dibatalkan karena melewati batas waktu 24 jam.',
            ], 400);
        }

        if ($order->payment_status === 'paid' || $order->status === 'paid' || $order->status === 'completed') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Pesanan ini sudah terkonfirmasi lunas.',
            ], 400);
        }

        // Cek apakah sudah melewati payment_deadline
        if ($order->payment_deadline && Carbon::now()->isAfter($order->payment_deadline)) {
            $order->update([
                'status'         => 'canceled',
                'payment_status' => 'expired',
                'shipping_status'=> 'Dibatalkan Otomatis',
            ]);
            if ($order->points_used > 0) {
                $user->increment('total_points', $order->points_used);
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'Batas waktu pembayaran 24 jam telah berakhir. Pesanan otomatis dibatalkan.',
            ], 400);
        }

        // Simpan file bukti transfer
        if ($request->hasFile('bukti_transfer')) {
            if ($order->bukti_transfer && Storage::disk('public')->exists($order->bukti_transfer)) {
                Storage::disk('public')->delete($order->bukti_transfer);
            }

            $file = $request->file('bukti_transfer');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('bukti_transfer', $fileName, 'public');

            $order->update([
                'bukti_transfer'   => $path,
                'payment_status'   => 'waiting_confirmation',
                'rejection_reason' => null, // Reset alasan penolakan jika sebelumnya ditolak
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Bukti transfer berhasil diunggah! Mohon menunggu verifikasi dari Admin.',
                'data'    => [
                    'order_id'           => $order->id,
                    'invoice_number'     => $order->invoice_number,
                    'payment_status'     => $order->payment_status,
                    'bukti_transfer'     => $order->bukti_transfer,
                    'bukti_transfer_url' => asset('storage/' . $order->bukti_transfer),
                    'payment_deadline'   => $order->payment_deadline,
                ],
            ], 200);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Gagal mengunggah file bukti transfer.',
        ], 400);
    }

    public function tracking($id)
    {
        $user = Auth::user();
        Order::cancelExpiredOrders();
        $order = Order::where('user_id', $user->id)->findOrFail($id);

        $liveTracking = !empty($order->no_resi)
            ? \App\Services\JntService::trackOrder($order->no_resi, $order)
            : null;

        return response()->json([
            'status' => 'success',
            'data' => [
                'invoice_number' => $order->invoice_number,
                'shipping_courier' => $order->shipping_courier ?? 'J&T Express (EZ)',
                'no_resi' => $order->no_resi,
                'jnt_des_code' => $order->jnt_des_code,
                'shipping_status' => $order->shipping_status ?? ($order->no_resi ? 'Dalam Pengiriman' : 'Menunggu Diproses'),
                'total_weight_kg' => $order->total_weight_kg ?? 1.0,
                'shipping_cost' => $order->shipping_cost ?? 0,
                'tracking_url' => \App\Services\JntService::getTrackingUrl($order->no_resi),
                'payment_status' => $order->payment_status,
                'payment_deadline' => $order->payment_deadline,
                'bukti_transfer_url' => $order->bukti_transfer ? asset('storage/' . $order->bukti_transfer) : null,
                'rejection_reason' => $order->rejection_reason,
                'live_tracking' => $liveTracking,
            ]
        ], 200);
    }

    /**
     * Endpoint pelacakan live API J&T Express untuk Aplikasi Flutter / Client
     */
    public function liveTracking($id)
    {
        $user = Auth::user();
        Order::cancelExpiredOrders();
        $order = Order::where('user_id', $user->id)->findOrFail($id);

        if (empty($order->no_resi)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor resi pengiriman untuk pesanan ini belum diterbitkan.',
                'data' => null,
            ], 404);
        }

        $trackingData = \App\Services\JntService::trackOrder($order->no_resi, $order);

        return response()->json([
            'status' => 'success',
            'data' => $trackingData,
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