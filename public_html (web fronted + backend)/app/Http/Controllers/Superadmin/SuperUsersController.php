<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

// test
class SuperUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = User::where('role', 'admin')
            ->orderBy('created_at', 'desc');
        
        // Apply search filter if search parameter exists
        if ($search) {
            $query->where('nama_lengkap', 'LIKE', "%{$search}%");
        }
        
        // Use the $query variable to get the results
        $users = $query->paginate(10);
        
        return view('superadmin.users.index', compact('users'));
    }
   public function masterMember(Request $request)
{
    $search = $request->input('search');
    $filterCabang = $request->input('kantor_cabang');

    $query = User::with('membership', 'orders.orderItems.product')
                ->where('role', 'member')
                ->latest();

    // Filter nama jika search ada
    if ($search) {
        $query->where('nama_lengkap', 'LIKE', "%{$search}%");
    }

    // ✅ Tambahkan filter kantor cabang jika dipilih
    if ($filterCabang) {
        $query->where('kantor_cabang', $filterCabang);
    }

    $users = $query->paginate(10);

    // ✅ Ambil daftar cabang unik untuk dropdown
    $cabangs = User::where('role', 'member')
                ->select('kantor_cabang')
                ->distinct()
                ->get();
    // === TAMBAHAN LOGIKA PROGRES MEMBERSHIP DIMULAI DI SINI ===

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
        if ($currentKeyIndex < (count($tierOrder) - 1)) {
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
            $progressData['percentage'] = max(0, min(100, $percentage)); // Pastikan antara 0-100
        }

        // 'Suntikkan' data progres ke setiap objek user
        $user->progress = (object)$progressData;
    }
    // === AKHIR DARI LOGIKA PROGRES ===

    return view('superadmin.users.master-member', compact('users', 'cabangs'));
}

public function showUpgradeForm(User $user)
{
    // Definisikan semua level yang ada
    $allLevels = ['bronze', 'silver', 'gold', 'platinum', 'diamond'];
    $currentLevel = strtolower($user->membership->level_membership);

    // Cari posisi level saat ini
    $currentLevelIndex = array_search($currentLevel, $allLevels);

    // Filter untuk hanya menampilkan level yang lebih tinggi
    $availableLevels = array_slice($allLevels, $currentLevelIndex + 1);

    // Jika sudah level tertinggi, tidak ada level tersedia
    if ($currentLevelIndex === false || empty($availableLevels)) {
        return redirect()->back()->with('info', 'Member ini sudah berada di level tertinggi.');
    }

    return view('superadmin.users.upgrade-form', [
        'user' => $user,
        'availableLevels' => $availableLevels
    ]);
}

/**
 * Memproses upgrade membership manual.
 */
public function processUpgrade(Request $request, User $user)
{
    $request->validate([
        'new_level' => 'required|in:silver,gold,platinum,diamond',
        'amount_paid' => 'required|numeric|min:0',
    ]);

    $oldLevel = $user->membership->level_membership;
    $newLevel = $request->new_level;

    // 1. Simpan rekap ke tabel membership_upgrades
    \App\Models\MembershipUpgrade::create([
        'user_id' => $user->id,
        'from_level' => $oldLevel,
        'to_level' => $newLevel,
        'amount_paid' => $request->amount_paid,
        'approved_at' => now(), // Langsung diapprove karena manual oleh super admin
        'approved_by' => Auth::id(), // Simpan ID super admin yang melakukan upgrade
    ]);

    // 2. Update level membership user
    $user->membership->update([
        'level_membership' => $newLevel,
    ]);

    return redirect()->route('superadmin.users.master-member')->with('success', "Membership {$user->nama_lengkap} berhasil di-upgrade ke " . ucfirst($newLevel) . "!");
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('superadmin.users.create');
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
            'nama_toko'     => 'nullable|required_if:role,member,super_admin|string|max:100',
            'alamat'        => 'nullable|required_if:role,member,super_admin|string',
            'no_hp'         => 'nullable|required_if:role,member,super_admin|string|max:15',
            'nama_sales'    => 'nullable|string|max:100',
            'kantor_cabang' => 'required_if:role,member,admin,super_admin|in:Trenggalek,Kediri,Madiun,Solo,Jogja,Cirebon,Kudus,Bogor,Serang',

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
            ]);
        }

        return redirect()->route('superadmin.users.index')->with('success', 'Admin berhasil ditambahkan.');
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
        // Aturan ini akan menolak email kembar, KECUALI milik ID user yang sedang diedit ini
        'email' => 'required|email|unique:users,email,' . $id,
        'username' => 'required|string|unique:users,username,' . $id,
    ], [
        // Pesan error custom agar tampil sopan, bukan layar merah
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
        'status_aktif' => $request->status_aktif,
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
        return redirect()->route('superadmin.users.master-member')->with('success', 'Data berhasil diperbarui!');
    } else {
        return redirect()->route('superadmin.users.index')->with('success', 'Data super admin berhasil diperbarui!');
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

    return redirect()->route('superadmin.users.master-member')->with('success', 'User berhasil dihapus');
}

/**
     * Memproses ACC / Penolakan Diskon Member
     */
    public function accMember(Request $request, $id)
    {
        $request->validate([
            'status_acc' => 'required|in:pending,approved,rejected'
        ]);

        $user = User::findOrFail($id);
        
        if ($user->membership) {
            // PERBAIKAN: Gunakan cara simpan manual (assign) agar kebal dari blokir Mass Assignment
            $user->membership->status_acc = $request->status_acc;
            $user->membership->save();
            
            $pesan = $request->status_acc == 'approved' ? 'di-ACC! Diskon telah aktif.' : 'Ditolak/Ditangguhkan.';
            return redirect()->back()->with('success', "Status Member {$user->nama_lengkap} berhasil {$pesan}");
        }

        return redirect()->back()->with('error', 'User ini tidak memiliki data membership.');
    }

}
