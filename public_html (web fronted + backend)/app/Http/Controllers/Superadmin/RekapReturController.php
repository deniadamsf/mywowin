<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class RekapReturController extends Controller
{
   public function index(Request $request)
{
    $adminId = $request->get('admin_id');
    
    $query = Order::with(['user.admin', 'product_rusak'])
        ->where('total_potongan_retur', '>', 0);

    if ($adminId) {
        $query->whereHas('user', function($q) use ($adminId) {
            $q->where('admin_id', $adminId);
        });
    }

    $rekapData = $query->latest()->paginate(10);
    
    $totalPotongan = $query->sum('total_potongan_retur');
    $totalBotol = $query->sum('retur_botol');
    $totalJerigen = $query->sum('retur_jerigen');
    
    // AMBIL DATA DARI BRANCH SETTINGS UNTUK FILTER NAMA PT
    // Kita join dengan users untuk mendapatkan admin_id nya
    $ptList = \App\Models\BranchSetting::all();

    return view('superadmin.rekap.retur', compact('rekapData', 'ptList', 'totalPotongan', 'totalBotol', 'totalJerigen'));
}
}
