<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Membership;
use App\Mail\WelcomeEmail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'nama_lengkap' => 'required|string|max:255',
            'password' => 'required|min:6',
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png',
            'nama_toko' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'nama_sales' => 'nullable|string|max:100',
            'kantor_cabang' => 'required|in:Trenggalek,Kediri,Madiun,Solo,Jogja,Cirebon,Kudus,Bogor,Serang',
        ]);

        $fotoProfilePath = null;
        if ($request->hasFile('foto_profile')) {
            $file = $request->file("foto_profile");
            $fileName = time() . "_" . $file->getClientOriginalName();
            $fotoProfilePath = $file->storeAs('foto_profile', $fileName, 'public');
        }

        $user = User::create([
            'username' => $validated["username"],
            'nama_lengkap' => $validated["nama_lengkap"],
            'email' => $validated["email"],
            'password' => Hash::make($validated["password"]),
            'role' => 'member',
            'status_aktif' => 'tidak aktif',
            'foto_profile' => $fotoProfilePath,
            'kantor_cabang' => $validated["kantor_cabang"],
            'last_login_at' => now(),
            'login_streak' => 1,
            'total_points' => 1000,
            'points_today' => 1000,
        ]);

        Membership::create([
            'user_id' => $user->id,
            'nama_toko' => $validated["nama_toko"],
            'alamat' => $validated["alamat"],
            'no_hp' => $validated["no_hp"],
            'nama_sales' => $validated["nama_sales"],
        ]);

        // --- TAMBAHAN BARU: Buat OTP untuk pendaftar ---
        $kodeOtp = rand(100000, 999999);
        $user->otp = $kodeOtp;
        $user->save();
        // -----------------------------------------------

        // Masukkan $kodeOtp ke dalam WelcomeEmail agar tidak crash
        Mail::to($user->email)->send(new WelcomeEmail($user, $kodeOtp));

        return response()->json([
            'status' => 'success',
            'message' => 'Akun berhasil dibuat. Menunggu aktivasi admin.',
            'data' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            // Tetap biarkan 'username' karena Flutter mengirimkan key ini, 
            // tapi secara logika ini akan menampung Username atau Email
            'username' => 'required', 
            'password' => 'required',
        ]);

        // PERBAIKAN: Beri kelonggaran agar bisa login pakai Username ATAU Email
        $user = User::where('username', $request->username)
                    ->orWhere('email', $request->username)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username/Email atau password salah'
            ], 401);
        }
        
        // 1. Jika belum diverifikasi OTP email DAN belum diaktifkan manual oleh admin
        if (is_null($user->email_verified_at) && $user->status_aktif == 'tidak aktif') {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun belum aktif. Silakan verifikasi OTP.' 
            ], 403);
        }

        // 2. Jika akun sengaja dimatikan paksa oleh admin (suspend/banned)
        if ($user->status_aktif == 'tidak aktif') {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun Anda dinonaktifkan oleh sistem. Hubungi Admin.'
            ], 403);
        }

        // --- TAMBAHAN BARU: JALUR BYPASS SATPAM LARAVEL ---
        // Jika akun diaktifkan admin tapi emailnya masih kosong, otomatis isi tanggal verifikasinya
        if ($user->status_aktif == 'aktif' && is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->save(); // Simpan ke database agar akses data tidak ditendang oleh server
        }
        // --------------------------------------------------

        $now = Carbon::now();
        $lastLogin = $user->last_login_at;
        $message = 'Login berhasil';

        if (!$lastLogin) {
            $user->login_streak = 1;
            $user->points_today = 1000;
            $user->total_points = 1000;
            $message = 'Selamat! Anda mendapatkan 1000 poin tambahan hari ini.';
        } else {
            $lastLoginDate = Carbon::parse($lastLogin)->startOfDay();
            $today = $now->copy()->startOfDay();
            $diffInDays = $lastLoginDate->diffInDays($today);

            if ($diffInDays == 1) {
                $user->login_streak += 1;
            } elseif ($diffInDays > 1) {
                $user->login_streak = 1;
            }

            $pointsToday = $this->calculatePoints($user->login_streak);

            if (Carbon::parse($lastLogin)->toDateString() !== $now->toDateString()) {
                $user->total_points += $pointsToday;
                $user->points_today = $pointsToday;
                $message = "Selamat! Anda mendapatkan {$user->points_today} poin hari ini.";
            }

            if ($user->login_streak == 30 && Carbon::parse($lastLogin)->toDateString() !== $now->toDateString()) {
                $message = "Hebat! Login 30 hari berturut-turut! Anda dapat {$user->points_today} poin!";
            }
        }

        $user->last_login_at = $now;
        $user->save();

        // Generate Sanctum Token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'token' => $token,
            'data' => clone $user->makeHidden('password')
        ], 200);
    }
    
    // Fungsi untuk memverifikasi OTP Pendaftaran (Menerima Email atau Username)
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required', // Sengaja tidak pakai aturan |email agar bisa memproses username
            'otp' => 'required|digits:6',
        ]);

        // Cari user berdasarkan Email ATAU Username (Jalur Darurat dari Login)
        $user = User::where('email', $request->email)
                    ->orWhere('username', $request->email)
                    ->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Data pengguna tidak ditemukan.'], 404);
        }

        if ($user->otp !== $request->otp) {
            return response()->json(['status' => 'error', 'message' => 'Kode OTP salah atau tidak valid.'], 400);
        }

        // Jika cocok, verifikasi emailnya dan hapus OTP-nya
        $user->email_verified_at = now();
        $user->status_aktif = 'aktif';
        $user->otp = null;
        $user->save();

        // --- TAMBAHAN BARU: Buatkan Token agar user langsung ter-login ---
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Email berhasil diverifikasi! Anda otomatis masuk.',
            'token' => $token,
            'data' => clone $user->makeHidden('password')
        ], 200);
    }

    // 1. Fungsi untuk Meminta OTP Reset Password
    public function requestResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Email tidak terdaftar di sistem kami.'], 404);
        }

        // Generate OTP baru dan simpan ke database
        $kodeOtp = rand(100000, 999999);
        $user->otp = $kodeOtp;
        $user->save();

        // Kirim email (Sementara kita pinjam template WelcomeEmail agar cepat, 
        // Anda bisa membuat ResetPasswordEmail terpisah nanti)
        Mail::to($user->email)->send(new \App\Mail\WelcomeEmail($user, $kodeOtp));

        return response()->json([
            'status' => 'success',
            'message' => 'Kode OTP untuk reset password telah dikirim ke email Anda.'
        ], 200);
    }

    // 2. Fungsi untuk Menyimpan Password Baru dengan Validasi OTP
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Email tidak ditemukan'], 404);
        }

        // Cek kecocokan OTP
        if ($user->otp !== $request->otp) {
            return response()->json(['status' => 'error', 'message' => 'Kode OTP salah atau tidak valid.'], 400);
        }

        // Jika cocok, ubah password dan bersihkan kolom OTP
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->otp = null;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password berhasil diubah. Silakan login dengan password baru.'
        ], 200);
    }

    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ], 200);
    }

    private function calculatePoints($streak)
    {
        if ($streak == 1) return 1000;
        if ($streak >= 2 && $streak <= 30) return 1000 + ($streak - 1) * 1000;
        return 30000;
    }
    
    // Fungsi untuk menarik data profil & progres membership secara real-time
    public function profile(Request $request)
    {
        $user = $request->user()->load('membership');

        // 1. Definisikan tingkatan membership (Sama dengan Web)
        $tiers = [
            'bronze'    => ['nama' => 'Bronze',   'min' => 0,          'max' => 5000000],
            'silver'    => ['nama' => 'Silver',   'min' => 5000000,    'max' => 10000000],
            'gold'      => ['nama' => 'Gold',     'min' => 10000000,   'max' => 50000000],
            'platinum'  => ['nama' => 'Platinum', 'min' => 50000000,   'max' => 150000000],
            'diamond'   => ['nama' => 'Diamond',  'min' => 150000000,  'max' => 400000000],
        ];
        $tierOrder = ['bronze', 'silver', 'gold', 'platinum', 'diamond'];

        // 2. Hitung total belanja bulan ini yang sudah selesai (completed)
        $totalBelanjaBulanIni = $user->orders()
            ->where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        // 3. Ambil level saat ini
        $currentLevelKey = strtolower($user->membership->level_membership ?? 'bronze');
        $currentTier = $tiers[$currentLevelKey] ?? $tiers['bronze'];

        // 4. Cari tahu level berikutnya
        $nextLevelKey = null;
        $currentKeyIndex = array_search($currentLevelKey, $tierOrder);
        if ($currentKeyIndex !== false && $currentKeyIndex < (count($tierOrder) - 1)) {
            $nextLevelKey = $tierOrder[$currentKeyIndex + 1];
        }

        // 5. Kalkulasi Progres
        $progressData = [
            'total_belanja' => (float) $totalBelanjaBulanIni,
            'level_sekarang' => $currentTier['nama'],
            'is_max_level' => ($nextLevelKey === null),
            'percentage' => 100, 
            'level_berikutnya' => '',
            'target_berikutnya' => 0,
            'sisa_kebutuhan' => 0,
        ];

        if ($nextLevelKey) {
            $nextTier = $tiers[$nextLevelKey];
            $target = $nextTier['min'];
            $awal = $currentTier['min'];
            
            $range = $target - $awal;
            $dicapai = $totalBelanjaBulanIni - $awal;
            
            $percentage = ($range > 0) ? ($dicapai / $range) * 100 : 0;

            $progressData['level_berikutnya'] = $nextTier['nama'];
            $progressData['target_berikutnya'] = (float) $target;
            $progressData['percentage'] = max(0, min(100, $percentage));
            $progressData['sisa_kebutuhan'] = max(0, $target - $totalBelanjaBulanIni);
        }

        // Suntikkan data progres ke respons JSON
        $user->progress = $progressData;

        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
    }
    
    // --- FUNGSI BARU UNTUK UPDATE PROFIL & FOTO ---
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Validasi input
        $validated = $request->validate([
            'nama_lengkap' => 'nullable|string|max:255',
            'nama_toko'    => 'nullable|string|max:100',
            'no_hp'        => 'nullable|string|max:15',
            'alamat'       => 'nullable|string',
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 1. Update data dasar User
        if (isset($validated['nama_lengkap'])) {
            $user->nama_lengkap = $validated['nama_lengkap'];
        }

        // 2. Upload Foto Profil (Jika Ada)
        if ($request->hasFile('foto_profile')) {
            // Hapus foto lama jika ada
            if ($user->foto_profile) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto_profile);
            }
            // Simpan foto baru
            $file = $request->file("foto_profile");
            $fileName = time() . "_" . $file->getClientOriginalName();
            $path = $file->storeAs('foto_profile', $fileName, 'public');
            $user->foto_profile = $path;
        }
        $user->save();

        // 3. Update data Membership
        if ($user->membership) {
            if (isset($validated['nama_toko'])) $user->membership->nama_toko = $validated['nama_toko'];
            if (isset($validated['no_hp'])) $user->membership->no_hp = $validated['no_hp'];
            if (isset($validated['alamat'])) $user->membership->alamat = $validated['alamat'];
            $user->membership->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profil dan foto berhasil diperbarui!',
            'data' => $user->load('membership')
        ], 200);
    }
    
    // --- FUNGSI BARU UNTUK MEMPROSES KLAIM POIN HARIAN DARI FLUTTER ---
    public function claimDailyReward(Request $request)
    {
        $user = $request->user();
        
        // Tambahkan nominal poin yang didapat (misal: 100 poin)
        $user->points_today = ($user->points_today ?? 0) + 1000; 
        
        // Karena di database Anda ada total_points, kita tambahkan juga ke sana
        $user->total_points = ($user->total_points ?? 0) + 1000;
        
        // Simpan perubahan ke database
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Reward berhasil diklaim!',
            'new_points' => $user->points_today
        ], 200);
    }
    /**
     * Memproses Pengajuan Kemitraan dari Aplikasi Flutter
     */
    public function requestMembership(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        $user = $request->user();

        // Buat data membership baru dan langsung set statusnya jadi 'pending'
        \App\Models\Membership::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama_toko' => $request->nama_toko,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'level_membership' => 'bronze',
                'status_acc' => 'pending' // Masuk antrean Super Admin
            ]
        );

        // Ubah role menjadi member agar muncul di Dasbor Master Member
        $user->update(['role' => 'member']);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan mitra berhasil dikirim! Silakan tunggu ACC Admin.'
        ]);
    }
    public function reapplyMembership(Request $request)
    {
        $user = $request->user();
        if ($user->membership) {
            $user->membership->update(['status_acc' => 'pending']);
            return response()->json(['success' => true, 'message' => 'Pengajuan ulang berhasil dikirim!']);
        }
        return response()->json(['success' => false, 'message' => 'Gagal. Data membership tidak ditemukan.'], 400);
    }
}