<?php
namespace App\Http\Controllers\Admin;


    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Auth;
    use App\Models\Order;
    use App\Models\User;
    use App\Models\Product; // pastikan ada di atas
    use App\Models\Visitor;

    class DashboardController extends Controller
    {
        public function index()
        {
            $admin = Auth::user(); 

            // Ambil aktivitas terbaru
            $orders = Order::latest()->take(5)->get()->map(function ($order) {
                return [
                    'type' => 'order',
                    'title' => 'Pesanan Baru #' . $order->invoice_number,
                    'description' => optional($order->user)->name . ' memesan ' . $order->orderItems->count() . ' item',
                    'time' => $order->created_at,
                ];
            });

            $users = User::latest()->take(5)->get()->map(function ($user) {
                return [
                    'type' => 'user',
                    'title' => 'Pengguna Baru ' . $user->name,
                    'description' => 'Pendaftaran baru melalui website',
                    'time' => $user->created_at,
                ];
            });

            $payments = Order::where('payment_status', 'paid')->latest()->take(5)->get()->map(function ($order) {
                return [
                    'type' => 'payment',
                    'title' => 'Pembayaran Diterima Rp ' . number_format($order->paid_amount, 0, ',', '.'),
                    'description' => 'Dari Pesanan #' . $order->invoice_number,
                    'time' => $order->paid_at ?? $order->updated_at,
                ];
            });

            $activities = $orders->merge($users)->merge($payments)->sortByDesc('time')->take(5);

            // Hitung total user membership sesuai kantor cabang
               $totalMembershipUsers = User::where('role', 'member')
                ->where('kantor_cabang', $admin->kantor_cabang)
                ->where('admin_id', $admin->id) // Ganti whereNotNull menjadi ID admin login
                ->count();

                $totalOrders = Order::whereHas('user', function ($query) use ($admin) {
                    $query->where('kantor_cabang', $admin->kantor_cabang)
                        ->where('admin_id', $admin->id); // Filter spesifik ID Admin
                })->count();

                // 3. Total Pendapatan dari Member yang di-claim oleh SAYA
                $totalPendapatan = Order::where('payment_status', 'paid')
                    ->whereHas('user', function ($query) use ($admin) {
                        $query->where('kantor_cabang', $admin->kantor_cabang)
                            ->where('admin_id', $admin->id); // Filter spesifik ID Admin
                    })
                ->sum('paid_amount');
               // Hitung produk aktif sesuai kantor cabang
               $totalProducts = Product::count();

                // Ambil statistik kunjungan per negara
        $visitorStats = Visitor::selectRaw('country, COUNT(*) as total')
        ->groupBy('country')
        ->orderByDesc('total')
        ->get();

    return view('admin.dashboard.index', compact(
        'admin', 
        'activities', 
        'totalMembershipUsers', 
        'totalOrders', 
        'totalPendapatan', 
        'totalProducts',
        'visitorStats' // Kirimkan data statistik kunjungan
    ));

 }
   }


