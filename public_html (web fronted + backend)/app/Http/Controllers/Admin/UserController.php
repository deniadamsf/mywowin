<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

// test
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', 'admin')
        ->latest()
        ->paginate(10);
        return view('admin.users.index', compact('users'));
    }
public function masterMember(Request $request) // Tambahkan Request $request di sini
{
    $admin = Auth::user();
    $searchTerm = $request->input('search'); // Tangkap input dari form search
    
    $totalClaimedByMe = User::where('role', 'member')
        ->where('kantor_cabang', $admin->kantor_cabang)
        ->where('admin_id', $admin->id)
        ->count();

    $totalTokoAktif = User::where('role', 'member')
    ->where('kantor_cabang', $admin->kantor_cabang)
    ->where('admin_id', $admin->id)
    ->where('status_aktif', 'aktif') // Pastikan hanya yang aktif
    ->count();

    $users = User::with('membership', 'orders.orderItems.product')
        ->where('role', 'member')
        ->where('kantor_cabang', $admin->kantor_cabang)
        ->where(function($query) use ($admin) {
            $query->where('admin_id', $admin->id)
                  ->orWhereNull('admin_id');
        })
        // --- TAMBAHKAN LOGIKA SEARCH DI SINI ---
        ->when($searchTerm, function($query) use ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_lengkap', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('username', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('membership', function($mq) use ($searchTerm) {
                      $mq->where('nama_toko', 'LIKE', "%{$searchTerm}%");
                  });
            });
        })
        // ---------------------------------------
        ->latest()
        ->paginate(10)
        ->withQueryString(); // Agar saat pindah halaman (pagination), keyword search tetap terbawa

    // ... sisa kode tier membership (foreach) tetap sama ...



    // ==========================================================
    // === TAMBAHAN LOGIKA PROGRES MEMBERSHIP (SALIN DARI SINI) ===
    // ==========================================================
    
    // Definisikan tingkatan membership
    $tiers = [
        'bronze'    => ['nama' => 'Bronze',   'min' => 0,          'max' => 5000000],
        'silver'    => ['nama' => 'Silver',   'min' => 5000000,    'max' => 10000000],
        'gold'      => ['nama' => 'Gold',     'min' => 10000000,   'max' => 50000000],
        'platinum'  => ['nama' => 'Platinum', 'min' => 50000000,   'max' => 150000000],
        'diamond'   => ['nama' => 'Diamond',  'min' => 150000000,  'max' => 400000000],
    ];
    $tierOrder = ['bronze', 'silver', 'gold', 'platinum', 'diamond'];

    // Loop setiap user untuk menghitung progres mereka
    foreach ($users as $user) {
        // Hitung total belanja HANYA untuk order 'completed' di bulan dan tahun ini
        $totalBelanjaBulanIni = $user->orders()
            ->where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        $currentLevelKey = strtolower($user->membership->level_membership ?? 'bronze');
        $currentTier = $tiers[$currentLevelKey];

        // Cari tahu level berikutnya
        $nextLevelKey = null;
        $currentKeyIndex = array_search($currentLevelKey, $tierOrder);
        if ($currentKeyIndex !== false && $currentKeyIndex < (count($tierOrder) - 1)) {
            $nextLevelKey = $tierOrder[$currentKeyIndex + 1];
        }

        // Siapkan data untuk dikirim ke view
        $progressData = [
            'totalBelanja' => $totalBelanjaBulanIni,
            'levelSekarang' => $currentTier['nama'],
            'isMaxLevel' => ($nextLevelKey === null),
        ];

        if ($nextLevelKey) {
            $nextTier = $tiers[$nextLevelKey];
            $target = $nextTier['min'];
            $awal = $currentTier['min'];
            
            $range = $target - $awal;
            $dicapai = $totalBelanjaBulanIni - $awal;
            
            $percentage = ($range > 0) ? ($dicapai / $range) * 100 : 0;

            $progressData['levelBerikutnya'] = $nextTier['nama'];
            $progressData['targetBerikutnya'] = $target;
            $progressData['percentage'] = max(0, min(100, $percentage));
        }

        // 'Suntikkan' data progres ke setiap objek user
        $user->progress = (object)$progressData;
    }
    // ===================================
    // === AKHIR DARI BLOK YANG DISALIN ===
    // ===================================

    return view('admin.users.master-member', compact('users', 'totalClaimedByMe', 'totalTokoAktif'));
}




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated =  $request->validate([
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'nama_lengkap' => 'required|string|max:255',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,member,super_admin,developer',
            'status_aktif' => 'required|boolean',
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nama_toko'     => 'nullable|required_if:role,member|string|max:100',
            'alamat'        => 'nullable|required_if:role,member|string',
            'no_hp'         => 'nullable|required_if:role,member|string|max:15',
            'nama_sales'    => 'nullable|string|max:100',
            'kantor_cabang' => 'required_if:role,member,admin|in:Trenggalek,Kediri,Madiun,Solo,Jogja,Cirebon,Kudus,Bogor,Serang',

        ]);

        // dd("test");

        $fotoProfilePath = null;
        if ($request->hasFile('foto_profile')) {
            $file = $request->file("foto_profile");
            $fileName = time() . "_" . $file->getClientOriginalName();
            $fotoProfilePath = $file->storeAs('foto_profile', $fileName, 'public');
        }

        $validated["password"] = bcrypt($validated["password"]);
        // dd($validated);

        $user = User::create([
            'username' => $validated["username"],
            'nama_lengkap' => $validated["nama_lengkap"],
            'email' => $validated["email"],
            'password' => $validated["password"],
            'role' => $validated["role"],
            'status_aktif' => $validated['status_aktif'],
            'kantor_cabang' => $validated['kantor_cabang'], 
            'foto_profile' => $fotoProfilePath
        ]);

        // dd("test");

        if ($validated["role"] == "member") {
            Membership::create([
                'user_id' => $user->id,
                'nama_toko' => $validated["nama_toko"],
                'alamat' => $validated["alamat"],
                'no_hp' => $validated["no_hp"],
                'nama_sales' => $validated["nama_sales"],
                'last_upgrade' => 0, // Inisialisasi dengan nilai 0
                'level_membership' => 'bronze', // Level default
    
            ]);
            
            
        }

        return redirect()->route('admin.users.master-member')->with('success', 'Admin berhasil ditambahkan.');
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
    // --- 1. TAMBAHKAN PENJAGA VALIDASI DI SINI ---
    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        // Aturan ini akan menolak email/username kembar, KECUALI milik ID user yang sedang diedit ini
        'email' => 'required|email|unique:users,email,' . $id,
        'username' => 'required|string|unique:users,username,' . $id,
    ], [
        // Pesan error custom agar tampil rapi dan sopan
        'email.unique' => 'Maaf, Email ini sudah terdaftar pada pengguna lain.',
        'username.unique' => 'Maaf, Username ini sudah dipakai oleh pengguna lain.',
    ]);
    // ---------------------------------------------

    $user = User::findOrFail($id);

    // Awalnya pakai array biasa
    $data = [
        'nama_lengkap' => $request->nama_lengkap,
        'email' => $request->email,
        'username' => $request->username,
        'kantor_cabang' => $request->kantor_cabang, // ✅ Tambahkan ini
        'status_aktif' => $request->status_aktif, // ✅ Tambahkan ini
    ];

    // Kalau password diisi, baru ditambahkan ke array
    if ($request->filled('password')) {
        $data['password'] = bcrypt($request->password);
    }

    // Update user dengan array yang sudah lengkap
    $user->update($data);

    // Update membership jika ada
    if ($user->membership) {
        $user->membership->update([
            'nama_toko' => $request->nama_toko,
            'nama_sales' => $request->nama_sales,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);
    }

    // Upload foto
    if ($request->hasFile('foto_profile')) {
        $path = $request->file('foto_profile')->store('profile', 'public');
        $user->update(['foto_profile' => $path]);
    }

    // Redirect sesuai jenis user
    if ($user->membership) {
        return redirect()->route('admin.users.master-member')->with('success', 'Data berhasil diperbarui!');
    } else {
        return redirect()->route('admin.user.index')->with('success', 'Data admin berhasil diperbarui!');
    }
}

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $user = User::findOrFail($id);
    
    // Hapus foto profil jika ada
    if ($user->foto_profile) {
        Storage::delete('public/' . $user->foto_profile);
    }

    $user->delete();

    return redirect()->route('admin.users.master-member')->with('success', 'User berhasil dihapus');
}

public function claimMember($id)
{
    $user = User::findOrFail($id);
    
    // Set admin_id ke ID Admin yang sedang login
    $user->admin_id = Auth::id();
    $user->save();

    return back()->with('success', 'Member ' . $user->nama_lengkap . ' berhasil dikonfirmasi ke cabang Anda!');
}

public function unclaimMember($id)
{
    $user = User::findOrFail($id);
    
    // Gunakan Auth::id() untuk mengambil ID user yang sedang login
    if ($user->admin_id !== \Illuminate\Support\Facades\Auth::id()) {
        return back()->with('error', 'Otoritas ditolak.');
    }

    $user->admin_id = null;
    $user->save();

    return back()->with('success', 'Konfirmasi member berhasil dilepas.');
}

}
