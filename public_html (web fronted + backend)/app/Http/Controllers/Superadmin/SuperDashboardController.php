<?php

namespace App\Http\Controllers\superadmin;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperDashboardController extends Controller
{
   public function index()
{
    $totalAdmin = User::where('role', 'admin')->count();
    $totalMember = User::where('role', 'member')->count();
    $totalPengguna = $totalAdmin + $totalMember;

    $totalOrders = Order::count();
    $pendapatan = Order::where('payment_status', 'paid')->sum('paid_amount');

    // Aktivitas hari ini
    $today = Carbon::today();
    $orderToday = Order::whereDate('created_at', $today)->count();
    $registerToday = User::whereDate('created_at', $today)->count();
    $loginToday = User::whereDate('last_login_at', $today)->count();
    $aktivitasHariIni = $orderToday + $registerToday + $loginToday;

    // 1. Ambil data User terbaru
    $allUsers = User::latest()->limit(20)->get()->map(function ($user) {
        return [
            'type' => 'user',
            'message' => "{$user->name} bergabung sebagai {$user->role}",
            'time' => $user->created_at->diffForHumans(),
            'timestamp' => $user->created_at,
            'date' => $user->created_at->format('d M Y, H:i') // DIUBAH DISINI (dari date_detail ke date)
        ];
    });

    // 2. Ambil data Order terbaru
    $allOrders = Order::latest()->limit(20)->get()->map(function ($order) {
        return [
            'type' => 'order',
            'message' => "Pesanan #{$order->invoice_number} telah dibuat",
            'time' => $order->created_at->diffForHumans(),
            'timestamp' => $order->created_at,
            'date' => $order->created_at->format('d M Y, H:i') // DIUBAH DISINI (dari date_detail ke date)
        ];
    });

    // 3. Gabungkan dan urutkan
    $mergedActivities = collect($allUsers)->merge($allOrders)->sortByDesc('timestamp');

    $recentActivities = $mergedActivities->take(5);
    $allActivities = $mergedActivities;

    return view('superadmin.dashboard.index', compact(
        'totalAdmin',
        'totalMember',
        'totalPengguna',
        'totalOrders',
        'pendapatan',
        'aktivitasHariIni',
        'orderToday',
        'registerToday',
        'loginToday',
        'recentActivities',
        'allActivities'
    ));
}
}

