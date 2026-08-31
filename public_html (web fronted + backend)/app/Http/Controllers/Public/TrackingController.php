<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\BranchSetting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status');
    
        $query = Order::with([
    'OrderItems.product.images',
    'OrderItems.bundling.images',
    'user.admin'

])
            ->where('user_id', $user->id);
    
        if ($status) {
            $query->where('status', $status);
        }
    
        $orders = $query->latest()->get();
        // --- TAMBAHKAN KODE INI ---
    // Mengambil data BranchSetting berdasarkan kantor cabang user yang login
    $branchSetting = BranchSetting::where('enum_value', $user->kantor_cabang)->first();
    // --------------------------
    
        return view('public.trackings.index', compact('orders', 'branchSetting'));
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
    

}

