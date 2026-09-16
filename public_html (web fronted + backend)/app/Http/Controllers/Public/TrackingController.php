<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\BranchSetting;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        Order::cancelExpiredOrders();
        $status = $request->query('status');
    
        $query = Order::with([
            'orderItems.product.images',
            'orderItems.bundling.images',
            'user.admin'
        ])->where('user_id', $user->id);
    
        if ($status) {
            $query->where('status', $status);
        }
    
        $orders = $query->latest()->get();
        // Mengambil data BranchSetting berdasarkan kantor cabang user yang login
        $branchSetting = BranchSetting::where('enum_value', $user->kantor_cabang)->first();
        
        // Mengambil data metode pembayaran & rekening resmi
        $activePaymentMethods = PaymentMethod::where('is_active', true)->orderBy('sort_order')->get();
        $transferMethod = $activePaymentMethods->firstWhere('code', 'transfer');
        $bankAccounts = $transferMethod ? $transferMethod->getBankAccounts(true) : [];
        if (empty($bankAccounts)) {
            $bankAccounts = [
                [
                    'id' => 'bca-1',
                    'bank_name' => 'Bank BCA',
                    'account_number' => '0891234567',
                    'account_holder' => 'PT WOWIN PURNOMO PUTERA',
                ],
                [
                    'id' => 'bri-1',
                    'bank_name' => 'Bank BRI',
                    'account_number' => '0123-01-000456-53-0',
                    'account_holder' => 'PT SANKE BERSINAR TERANG',
                ],
            ];
        }

        $waMethod = $activePaymentMethods->firstWhere('code', 'wa');
        $officialWaNumber = $waMethod ? $waMethod->getWaNumber() : '62812106600';

        // Deteksi apakah ada pesanan yang baru saja dibuat
        $newOrderId = session('new_order_id') ?? $request->query('new_order_id') ?? $request->query('id');
        $newOrder = null;
        if ($newOrderId) {
            $newOrder = $orders->firstWhere('id', (int) $newOrderId);
        }

        return view('public.trackings.index', compact(
            'orders',
            'branchSetting',
            'activePaymentMethods',
            'bankAccounts',
            'officialWaNumber',
            'newOrder'
        ));
    }

    /**
     * Upload Bukti Transfer Pembayaran (Web Frontend)
     */
    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'bukti_transfer.required' => 'Berkas bukti transfer wajib diunggah.',
            'bukti_transfer.image'    => 'Berkas bukti transfer harus berupa gambar.',
            'bukti_transfer.mimes'    => 'Format gambar yang didukung: JPG, JPEG, PNG, WEBP.',
            'bukti_transfer.max'      => 'Ukuran foto maksimal adalah 5MB.',
        ]);

        $user = Auth::user();
        if (!$user) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.'], 401);
            }
            return redirect()->route('login');
        }

        // Batalkan otomatis jika pesanan melewati batas 24 jam
        Order::cancelExpiredOrders();

        $order = Order::where('user_id', $user->id)->findOrFail($id);

        if ($order->status === 'canceled') {
            $msg = 'Pesanan telah dibatalkan karena melewati batas waktu pembayaran 24 jam.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $msg], 400);
            }
            return redirect()->back()->with('error', $msg);
        }

        if ($order->payment_status === 'paid' || $order->status === 'paid' || $order->status === 'completed') {
            $msg = 'Pesanan ini sudah terkonfirmasi lunas.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $msg], 400);
            }
            return redirect()->back()->with('info', $msg);
        }

        if ($order->payment_deadline && Carbon::now()->isAfter($order->payment_deadline)) {
            $order->update([
                'status'          => 'canceled',
                'payment_status'  => 'expired',
                'shipping_status' => 'Dibatalkan Otomatis',
            ]);
            if ($order->points_used > 0) {
                $user->increment('total_points', $order->points_used);
            }

            $msg = 'Batas waktu pembayaran 24 jam telah berakhir. Pesanan otomatis dibatalkan.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $msg], 400);
            }
            return redirect()->back()->with('error', $msg);
        }

        if ($request->hasFile('bukti_transfer')) {
            // Hapus file bukti lama jika sebelumnya ada
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

            $successMsg = 'Bukti transfer pesanan #' . $order->invoice_number . ' berhasil dikirim! Menunggu konfirmasi verifikasi Admin.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status'             => 'success',
                    'message'            => $successMsg,
                    'data'               => [
                        'order_id'           => $order->id,
                        'invoice_number'     => $order->invoice_number,
                        'payment_status'     => $order->payment_status,
                        'bukti_transfer'     => $order->bukti_transfer,
                        'bukti_transfer_url' => asset('storage/' . $order->bukti_transfer),
                    ]
                ], 200);
            }

            return redirect()->back()->with('success', $successMsg);
        }

        $errMsg = 'Gagal memproses berkas bukti transfer.';
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['status' => 'error', 'message' => $errMsg], 400);
        }
        return redirect()->back()->with('error', $errMsg);
    }
    
    
public function showNota($id)
{
    // 1. Ambil data order beserta item dan usernya
    $order = Order::with(['orderItems', 'user.membership'])->findOrFail($id);
    
    // Validasi Otorisasi Kepemilikan (Anti-IDOR)
    $currentUser = Auth::user();
    if (!$currentUser) {
        return redirect()->route('login');
    }
    if ($currentUser->role !== 'super_admin' && $currentUser->role !== 'admin' && $order->user_id !== $currentUser->id) {
        abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk melihat nota pesanan ini.');
    }
    
    // 2. Ambil BranchSetting
    $branchSetting = BranchSetting::where('user_id', $order->user->admin_id)->first();
    if (!$branchSetting) {
        $branchSetting = BranchSetting::where('enum_value', $order->user->kantor_cabang)->first();
    }

    // 3. HITUNG SUB-TOTAL dari data OrderItems yang sudah tersimpan di database
    $subtotal = $order->orderItems->sum(function($item) {
        return $item->price * $item->quantity;
    });

    // 4. HITUNG DISKON (Selisih antara Subtotal dengan Total Bayar)
    $discountAmount = $subtotal - $order->total;
    
    // Hitung Persen untuk ditampilkan di ( ... % )
    $discountPercent = ($subtotal > 0) ? round(($discountAmount / $subtotal) * 100, 1) : 0;

    $discountData = [
        'discountAmount' => $discountAmount,
        'discountPercent' => $discountPercent
    ];

    return view('public.trackings.nota', compact('order', 'branchSetting', 'subtotal', 'discountData'));
}
    
public function downloadReceipt($orderId)
{
    // 1. Ambil data order lengkap
    $order = Order::with(['orderItems', 'user.membership'])->findOrFail($orderId);
    
    // Validasi Otorisasi Kepemilikan (Anti-IDOR)
    $currentUser = Auth::user();
    if (!$currentUser) {
        return redirect()->route('login');
    }
    if ($currentUser->role !== 'super_admin' && $currentUser->role !== 'admin' && $order->user_id !== $currentUser->id) {
        abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengunduh kwitansi pesanan ini.');
    }
    
    // 2. Cari BranchSetting untuk Logo dan Alamat PT
    $branchSetting = BranchSetting::where('user_id', $order->user->admin_id)->first();
    if (!$branchSetting) {
        $branchSetting = BranchSetting::where('enum_value', $order->user->kantor_cabang)->first();
    }

    // 3. Hitung Subtotal (Harga kotor sebelum diskon)
    $subtotal = $order->orderItems->sum(function($item) {
        return $item->price * $item->quantity;
    });

    // 4. Hitung Nominal Diskon
    $discountAmount = max(0, $subtotal - $order->total);
    
    // 5. Hitung Persentase Diskon untuk ditampilkan di PDF
    $discountPercent = ($subtotal > 0) ? round(($discountAmount / $subtotal) * 100, 1) : 0;
    
    // 6. Bungkus dalam array discountData sesuai kebutuhan View
    $discountData = [
        'discountAmount' => $discountAmount,
        'discountPercent' => $discountPercent,
        'subtotal' => $subtotal
    ];

    // 7. KIRIM VARIABEL KE VIEW (Tambahkan 'subtotal' di compact)
    $pdf = PDF::loadView('public.trackings.enota', compact('order', 'branchSetting', 'discountData', 'subtotal'));
    
    // 8. Download PDF
    return $pdf->download('e-receipt-' . $order->invoice_number . '.pdf');
}


   public function show($id)
    {
        $user = Auth::user();
    
        $order = Order::with(['orderItems.product.images', 'user.membership'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // 5. Tambahkan pengambilan branchSetting jika di halaman show juga ada nota
        $branchSetting = BranchSetting::where('enum_value', $order->user->kantor_cabang)->first();
    
        return view('public.trackings.nota', compact('order', 'branchSetting'));
    }

    /**
     * Endpoint live tracking AJAX untuk halaman web publik (Anti-IDOR)
     */
    public function liveTracking($id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        $order = Order::with(['orderItems', 'user'])->findOrFail($id);

        if ($user->role !== 'super_admin' && $user->role !== 'admin' && $order->user_id !== $user->id) {
            return response()->json(['status' => 'error', 'message' => 'Akses ditolak.'], 403);
        }

        if (empty($order->no_resi)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor resi untuk pesanan ini belum diterbitkan.',
                'data' => null
            ], 404);
        }

        $trackingData = \App\Services\JntService::trackOrder($order->no_resi, $order);

        return response()->json([
            'status' => 'success',
            'data' => $trackingData
        ]);
    }
}

