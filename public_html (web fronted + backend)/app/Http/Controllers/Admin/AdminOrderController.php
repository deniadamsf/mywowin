<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Membership;
use Carbon\Carbon;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;


class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Batalkan otomatis pesanan transfer yang telah melewati 24 jam
        Order::cancelExpiredOrders();

       // Ambil ID Admin yang sedang login
        $adminId = Auth::id();
        $adminCabang = Auth::user()->kantor_cabang;

       $query = Order::with(['user', 'orderItems'])->whereHas('user', function ($q) use ($adminId, $adminCabang) {
        $q->where('kantor_cabang', $adminCabang)
          ->where('admin_id', $adminId); // <--- Kuncinya di sini (Filter berdasarkan Klaim)
    });
    
        // Apply date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->where('created_at', '>=', $startDate);
        } elseif ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->where('created_at', '<=', $endDate);
        }
    
        // Apply order status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        // Apply payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
    
        // Apply search if present
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('nama_lengkap', 'like', "%{$search}%");
                  });
            });
        }
    
        // Get the paginated results
        $orders = $query->latest()->paginate(10);
    
        // Pass to the view
        return view('admin.orders.index', compact('orders'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'total' => 'required|numeric',
        'status' => 'required|string',
        'payment_method' => 'required|string',
        'payment_status' => 'required|string',
        'alamat' => 'required|string',
        'catatan' => 'nullable|string',
        'paid_amount' => 'nullable|numeric',
        'bukti_transfer' => 'nullable|image|max:2048',
    ]);

    // Generate Invoice Number
    function numberToLetters($number)
    {
        $letters = '';
        while ($number >= 0) {
            $letters = chr($number % 26 + 65) . $letters;
            $number = floor($number / 26) - 1;
            if ($number < 0) break;
        }
        return str_pad($letters, 4, 'A', STR_PAD_LEFT);
    }

    $tanggal = Carbon::now()->format('Ymd');
    $orderCountToday = Order::whereDate('created_at', Carbon::today())->count();
    $hurufAkhir = numberToLetters($orderCountToday);
    $invoiceNumber = 'WWN-' . $tanggal . '-' . $hurufAkhir;

     // Proses unggah gambar
     $fotoPath = null;
     if ($request->hasFile('bukti_transfer')) {
         $file = $request->file("bukti_transfer");
         $fileName = time() . "_" . $file->getClientOriginalName();
         $fotoPath = $file->storeAs('bukti_transfer', $fileName, 'public');
     }

    // Simpan ke database
    Order::create([
        'user_id' => Auth::id(),
        'invoice_number' => $invoiceNumber,
        'total' => $request->total,
        'status' => $request->status,
        'payment_method' => $request->payment_method,
        'payment_status' => $request->payment_status,
        'alamat' => $request->alamat,
        'catatan' => $request->catatan,
        'paid_amount' => $request->paid_amount,
        'bukti_transfer' => $fotoPath,
        'paid_at' => now(),
    ]);

    return redirect()->route('admin.orders.index')->with('success', 'Order berhasil dibuat.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'total' => 'required|numeric',
            'status' => 'required|string',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
            'alamat' => 'required|string',
            'catatan' => 'nullable|string',
            'paid_amount' => 'nullable|numeric',
            'bukti_transfer' => 'nullable|image|max:2048',
            'retur_botol' => 'nullable|integer|min:0',
            'retur_jerigen' => 'nullable|integer|min:0',
            'produk_rusak_id' => 'nullable|exists:products,id_product',
            'qty_rusak' => 'nullable|integer|min:0',
            'foto_kerusakan' => 'nullable|image|max:2048',
        ]);
    
        // Cari order yang akan di-update
        $order = Order::findOrFail($id);

        // 3. LOGIKA HITUNG RETUR (Ditaruh sebelum simpan order)
        $harga_botol = 1200;
        $harga_jerigen = 2500;
        $potongan_kemasan = ($request->retur_botol * $harga_botol) + ($request->retur_jerigen * $harga_jerigen);

        $potongan_rusak = 0;
        if ($request->produk_rusak_id && $request->qty_rusak > 0) {
            $produk = \App\Models\Product::find($request->produk_rusak_id);
            if ($produk) {
                // Rumus: (Harga Karton / Isi Karton) * Qty Pecah
                $harga_satuan = $produk->harga / ($produk->isi_karton ?: 1);
                $potongan_rusak = $harga_satuan * $request->qty_rusak;
            }
        }
    
        // ✅ Sekarang aman update user
        $order->user->nama_lengkap = $request->nama_lengkap;
        $order->user->save();
    
        // Cek jika ada perubahan pada file bukti transfer
        $fotoPath = $order->bukti_transfer;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $fileName = time() . "_" . $file->getClientOriginalName();
            $fotoPath = $file->storeAs('bukti_transfer', $fileName, 'public');
        }
        $fotoRusak = $order->foto_kerusakan;
        if ($request->hasFile('foto_kerusakan')) {
            $fileRusak = $request->file('foto_kerusakan');
            $nameRusak = time() . "_rusak_" . $fileRusak->getClientOriginalName();
            $fotoRusak = $fileRusak->storeAs('retur_bukti', $nameRusak, 'public');
        }
    
        // Update order dengan data baru
        $order->update([
            'total' => $request->total,
            'status' => $request->status,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'alamat' => $request->alamat,
            'catatan' => $request->catatan,
            'paid_amount' => $request->paid_amount,
            'bukti_transfer' => $fotoPath,
            'paid_at' => $request->payment_status === 'paid' ? now() : $order->paid_at,
            // --- DATA RETUR BARU ---
            'retur_botol' => $request->retur_botol ?? 0,
            'retur_jerigen' => $request->retur_jerigen ?? 0,
            'produk_rusak_id' => $request->produk_rusak_id,
            'qty_rusak' => $request->qty_rusak ?? 0,
            'foto_kerusakan' => $fotoRusak,
            'tipe_retur' => $request->tipe_retur,
            'total_potongan_retur' => $potongan_kemasan + $potongan_rusak,
        ]);
    
        return redirect()->route('admin.orders.index')->with('success', 'Order berhasil diperbarui.');
    }
    
    public function exportPdf(Request $request)
{
    $adminId = Auth::id();
$adminCabang = Auth::user()->kantor_cabang;
// --- TAMBAHKAN INI: Ambil identitas PT milik Admin yang login ---
    $branchSetting = \App\Models\BranchSetting::where('user_id', $adminId)->first();
    // Fallback jika admin belum setting identitas PT
    if (!$branchSetting) {
        $branchSetting = \App\Models\BranchSetting::where('enum_value', $adminCabang)->first();
    }
    // Apply the same filters as in the index method
   $query = Order::with(['user', 'orderItems'])->whereHas('user', function ($q) use ($adminId, $adminCabang) {
    $q->where('kantor_cabang', $adminCabang)
      ->where('admin_id', $adminId); // Filter agar hanya data yang diklaim yang masuk laporan
});
    
    // Apply date range filter
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $query->whereBetween('created_at', [$startDate, $endDate]);
    } elseif ($request->filled('start_date')) {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $query->where('created_at', '>=', $startDate);
    } elseif ($request->filled('end_date')) {
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $query->where('created_at', '<=', $endDate);
    }
    
    // Apply order status filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    // Apply payment status filter
    if ($request->filled('payment_status')) {
        $query->where('payment_status', $request->payment_status);
    }
    
    // Apply search if present
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('invoice_number', 'like', "%{$search}%")
              ->orWhereHas('user', function ($userQuery) use ($search) {
                  $userQuery->where('nama_lengkap', 'like', "%{$search}%");
              });
        });
    }
    
    // Get all filtered orders
    $orders = $query->latest()->get();
    
    // Generate title based on filters
    $title = 'Laporan Order';
    $filterInfo = [];
    
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $filterInfo[] = 'Periode: ' . Carbon::parse($request->start_date)->format('d/m/Y') . ' - ' . 
                        Carbon::parse($request->end_date)->format('d/m/Y');
    } elseif ($request->filled('start_date')) {
        $filterInfo[] = 'Dari tanggal: ' . Carbon::parse($request->start_date)->format('d/m/Y');
    } elseif ($request->filled('end_date')) {
        $filterInfo[] = 'Sampai tanggal: ' . Carbon::parse($request->end_date)->format('d/m/Y');
    }
    
    if ($request->filled('status')) {
        $statuses = [
            'pending' => 'Pending',
            'paid' => 'Dibayar',
            'shipped' => 'Dalam Pengiriman',
            'completed' => 'Selesai',
            'canceled' => 'Dibatalkan'
        ];
        $filterInfo[] = 'Status: ' . ($statuses[$request->status] ?? $request->status);
    }
    
    if ($request->filled('payment_status')) {
        $paymentStatuses = [
            'pending' => 'Belum Dibayar',
            'paid' => 'Sudah Dibayar'
        ];
        $filterInfo[] = 'Status Pembayaran: ' . ($paymentStatuses[$request->payment_status] ?? $request->payment_status);
    }
    
    // Generate PDF with filtered orders
    $pdf = PDF::loadView('admin.orders.pdf', compact('orders', 'title', 'filterInfo', 'branchSetting'));
    return $pdf->download('laporan-order-' . date('Y-m-d') . '.pdf');
}

public function exportExcel(Request $request)
{
    $adminId = Auth::id();
    $adminCabang = Auth::user()->kantor_cabang;
    // --- TAMBAHKAN BARIS INI: Ambil identitas PT milik Admin yang login ---
    $branchSetting = \App\Models\BranchSetting::where('user_id', $adminId)->first(); //
    // Fallback jika admin belum melakukan setting identitas PT
    if (!$branchSetting) { //
        $branchSetting = \App\Models\BranchSetting::where('enum_value', $adminCabang)->first(); //
    }

   $query = Order::with(['user', 'orderItems'])->whereHas('user', function ($q) use ($adminId, $adminCabang) {
        $q->where('kantor_cabang', $adminCabang)
          ->where('admin_id', $adminId); // Filter berdasarkan Klaim Member
    });
    
    // Apply date range filter
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $query->whereBetween('created_at', [$startDate, $endDate]);
    } elseif ($request->filled('start_date')) {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $query->where('created_at', '>=', $startDate);
    } elseif ($request->filled('end_date')) {
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $query->where('created_at', '<=', $endDate);
    }
    
    // Apply order status filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    // Apply payment status filter
    if ($request->filled('payment_status')) {
        $query->where('payment_status', $request->payment_status);
    }
    
    // Apply search if present
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('invoice_number', 'like', "%{$search}%")
              ->orWhereHas('user', function ($userQuery) use ($search) {
                  $userQuery->where('nama_lengkap', 'like', "%{$search}%");
              });
        });
    }
    
    // Get all filtered orders for Excel
    $orders = $query->latest()->get();
    
    // Generate filter description
   $filterDescription = 'Laporan Cabang: ' . $adminCabang;
    
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $filterDescription = 'Orders dari ' . Carbon::parse($request->start_date)->format('d/m/Y') . 
                            ' sampai ' . Carbon::parse($request->end_date)->format('d/m/Y');
    }
    
    if ($request->filled('status')) {
        $statuses = [
            'pending' => 'Pending',
            'paid' => 'Dibayar',
            'shipped' => 'Dalam Pengiriman',
            'completed' => 'Selesai',
            'canceled' => 'Dibatalkan'
        ];
        $filterDescription .= ' - Status: ' . ($statuses[$request->status] ?? $request->status);
    }
    
    if ($request->filled('payment_status')) {
        $paymentStatuses = [
            'pending' => 'Belum Dibayar',
            'paid' => 'Sudah Dibayar'
        ];
        $filterDescription .= ' - Pembayaran: ' . ($paymentStatuses[$request->payment_status] ?? $request->payment_status);
    }
    
 // --- PERBARUI BARIS INI: Kirim variabel $branchSetting ke OrdersExport ---
    return Excel::download(
        new OrdersExport($orders, $filterDescription, $branchSetting), //
        'laporan-order-' . $adminCabang . '-' . date('Y-m-d') . '.xlsx' //
    );
}

public function printInvoice($id)
{
    // Ambil order beserta user dan itemnya
    $order = Order::with('orderItems', 'user.membership')->findOrFail($id);
    
    // Cari Identitas PT berdasarkan Admin yang menangani member tersebut
    $branchSetting = \App\Models\BranchSetting::where('user_id', $order->user->admin_id)->first();

    // Fallback jika belum diatur, ambil berdasarkan kantor cabang
    if (!$branchSetting) {
        $branchSetting = \App\Models\BranchSetting::where('enum_value', $order->user->kantor_cabang)->first();
    }

    return view('admin.orders.print-invoice', compact('order', 'branchSetting'));
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if ($order->bukti_transfer) {
            Storage::disk('public')->delete($order->bukti_transfer);
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Artikel berhasil dihapus!');
    }

    public function completeOrder(Order $order)
    {
        // Langkah-langkah untuk menyelesaikan order
        $order->status = 'completed';
        $order->save();

        // Update total belanja member dan level membership
        Membership::updateTotalBelanja($order->user_id, $order->total);

        return redirect()->back()->with('success', 'Order berhasil diselesaikan');
    }


    // Tambahkan di App\Http\Controllers\Admin\AdminOrderController.php

            public function rekapRetur(Request $request)
            {
                $adminId = Auth::id();
                $adminCabang = Auth::user()->kantor_cabang;

                // Menarik data order yang punya potongan retur sesuai cabang admin yang login
                $query = Order::with(['user', 'product_rusak'])
                    ->where('total_potongan_retur', '>', 0)
                    ->whereHas('user', function ($q) use ($adminId, $adminCabang) {
                        $q->where('kantor_cabang', $adminCabang);
                    });

                // Filter Periode Tanggal
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $query->whereBetween('created_at', [
                        \Carbon\Carbon::parse($request->start_date)->startOfDay(),
                        \Carbon\Carbon::parse($request->end_date)->endOfDay()
                    ]);
                }

                $returs = $query->latest()->paginate(10);

                // Hitung Total untuk statistik di dashboard rekap
                $totalBotol = $query->sum('retur_botol');
                $totalJerigen = $query->sum('retur_jerigen');
                $totalRupiah = $query->sum('total_potongan_retur');

                return view('admin.orders.rekap_retur', compact('returs', 'totalBotol', 'totalJerigen', 'totalRupiah'));
            }

    /**
     * Terbitkan Resi J&T Express otomatis (Auto AWB)
     */
    public function generateJntAwb($id)
    {
        $order = Order::with(['orderItems.product', 'user.membership'])->findOrFail($id);
        $result = \App\Services\JntService::createOrder($order);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }
        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Batalkan Resi J&T Express
     */
    public function cancelJntAwb($id)
    {
        $order = Order::findOrFail($id);
        $result = \App\Services\JntService::cancelOrder($order);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }
        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Tampilan Cetak Label Resi Pengiriman J&T (Format Thermal / Standar)
     */
    public function printShippingLabel($id)
    {
        $order = Order::with(['orderItems.product', 'user.membership'])->findOrFail($id);
        $adminCabang = $order->user->kantor_cabang ?? Auth::user()->kantor_cabang ?? 'Trenggalek';
        $branchSetting = \App\Models\BranchSetting::where('enum_value', $adminCabang)->first();

        return view('admin.orders.shipping_label', compact('order', 'branchSetting'));
    }

    /**
     * Setujui / Konfirmasi Pembayaran Bukti Transfer (Admin Cabang)
     */
    public function confirmPayment(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'payment_status'   => 'paid',
            'status'           => ($order->status === 'pending' || $order->status === 'canceled') ? 'paid' : $order->status,
            'paid_amount'      => $order->total,
            'paid_at'          => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', "Pembayaran pesanan #{$order->invoice_number} berhasil diverifikasi dan disetujui.");
    }

    /**
     * Tolak Bukti Transfer Pembayaran (Admin Cabang)
     */
    public function rejectPayment(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ], [
            'reason.required' => 'Alasan penolakan bukti pembayaran wajib diisi.',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'payment_status'   => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        return redirect()->back()->with('warning', "Bukti transfer pesanan #{$order->invoice_number} telah ditolak.");
    }

    /**
     * Bersihkan Bukti Transfer yang berumur lebih dari 6 bulan secara manual
     */
    public function cleanupOldProofs(Request $request)
    {
        $months = (int) $request->input('months', 6);
        if ($months <= 0) {
            $months = 6;
        }

        $deletedCount = Order::cleanupOldProofs($months);

        if ($deletedCount > 0) {
            return redirect()->back()->with('success', "Berhasil membersihkan {$deletedCount} berkas bukti transfer lama (>{$months} bulan) dari server.");
        }

        return redirect()->back()->with('info', "Tidak ada berkas bukti transfer lama (>{$months} bulan) yang perlu dibersihkan.");
    }
}
