<?php

namespace App\Http\Controllers\superadmin;

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


class SuperOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $query = Order::with(['user', 'orderItems']);
    
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
        return view('superadmin.orders.index', compact('orders'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.orders.create');
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

    return redirect()->route('superadmin.orders.index')->with('success', 'Order berhasil dibuat.');
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
        ]);
    
        // Cari order yang akan di-update
        $order = Order::findOrFail($id);
    
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
        ]);
    
        return redirect()->route('superadmin.orders.index')->with('success', 'Order berhasil diperbarui.');
    }
    
    public function exportPdf(Request $request)
{
    // Apply the same filters as in the index method
    $query = Order::with(['user', 'orderItems']);
    
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
    $pdf = PDF::loadView('admin.orders.pdf', compact('orders', 'title', 'filterInfo'));
    return $pdf->download('laporan-order-' . date('Y-m-d') . '.pdf');
}

public function exportExcel(Request $request)
{
    // Apply the same filters as in the index method
    $query = Order::with(['user', 'orderItems']);
    
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
    $filterDescription = 'Semua Order';
    
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
    
    // Create Excel export
    return Excel::download(new OrdersExport($orders, $filterDescription), 'laporan-order-' . date('Y-m-d') . '.xlsx');
}

    public function printInvoice($id)
    {
        $order = Order::with('orderItems', 'user')->findOrFail($id);
        return view('superadmin.orders.print-invoice', compact('order'));
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

        return redirect()->route('superadmin.orders.index')->with('success', 'Artikel berhasil dihapus!');
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
        $adminCabang = $order->user->kantor_cabang ?? 'Trenggalek';
        $branchSetting = \App\Models\BranchSetting::where('enum_value', $adminCabang)->first();

        return view('admin.orders.shipping_label', compact('order', 'branchSetting'));
    }

    /**
     * Terbitkan Resi J&T Express Masal (Bulk Auto AWB)
     */
    public function bulkGenerateJntAwb(Request $request)
    {
        $rawIds = $request->input('order_ids');
        $ids = is_string($rawIds) ? array_filter(explode(',', $rawIds)) : (array) $rawIds;

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal satu pesanan untuk membuat resi masal.');
        }

        $orders = Order::with(['orderItems.product', 'user.membership'])
            ->whereIn('id', $ids)
            ->whereNull('no_resi')
            ->where('status', '!=', 'canceled')
            ->get();

        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pesanan terpilih yang belum memiliki resi.');
        }

        $successCount = 0;
        $failedCount = 0;
        $failedMessages = [];

        foreach ($orders as $order) {
            $result = \App\Services\JntService::createOrder($order);
            if ($result['success']) {
                $successCount++;
            } else {
                $failedCount++;
                $failedMessages[] = "#{$order->invoice_number}: {$result['message']}";
            }
        }

        $msg = "Pembuatan Resi Masal Selesai: {$successCount} resi berhasil diterbitkan";
        if ($failedCount > 0) {
            $msg .= ", {$failedCount} gagal (" . implode('; ', array_slice($failedMessages, 0, 3)) . ")";
            return redirect()->back()->with('warning', $msg);
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Cetak Label Thermal Masal J&T (Bulk Thermal 100x150mm)
     */
    public function bulkPrintShippingLabel(Request $request)
    {
        $rawIds = $request->input('order_ids') ?? $request->input('ids');
        $ids = is_string($rawIds) ? array_filter(explode(',', $rawIds)) : (array) $rawIds;

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal satu pesanan untuk dicetak labelnya.');
        }

        $orders = Order::with(['orderItems.product', 'user.membership'])
            ->whereIn('id', $ids)
            ->get();

        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'Data pesanan terpilih tidak ditemukan.');
        }

        $branchSettings = \App\Models\BranchSetting::all()->keyBy('enum_value');

        return view('admin.orders.bulk_shipping_label', compact('orders', 'branchSettings'));
    }
}
