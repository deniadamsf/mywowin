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
use App\Mail\ResetPasswordEmail;
use Illuminate\Support\Facades\Log;

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
            'alamat' => [
                'required',
                'string',
                'min:15',
                function ($attribute, $value, $fail) {
                    if (!str_contains($value, ',')) {
                        $fail('Alamat pengiriman wajib berjenjang lengkap (Provinsi, Kota/Kabupaten, Kecamatan, Desa, dan Kode Pos).');
                    }
                },
            ],
            'no_hp' => 'required|string|max:15',
            'nama_sales' => 'nullable|string|max:100',
            'kantor_cabang' => 'required|in:Trenggalek,Kediri,Madiun,Solo,Jogja,Cirebon,Kudus,Bogor,Serang',
        ], [
            'alamat.min' => 'Alamat pengiriman terlalu pendek. Harap gunakan pemilihan alamat berjenjang lengkap.',
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
            'last_login_at' => Carbon::now('Asia/Jakarta'),
            'login_streak' => 1,
            'total_points' => 100,
            'points_today' => 100,
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

        // Kirim WelcomeEmail berisi OTP
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user, $kodeOtp));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim WelcomeEmail ke ' . $user->email . ': ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Akun berhasil dibuat. Silakan verifikasi email Anda.',
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

        $now = Carbon::now('Asia/Jakarta');
        $lastLogin = $user->last_login_at;
        $message = 'Login berhasil';

        if (!$lastLogin) {
            $user->login_streak = 1;
            $user->points_today = 100;
            $user->total_points = 100;
            $message = 'Selamat! Anda mendapatkan 100 poin tambahan hari ini.';
        } else {
            $lastLoginDate = Carbon::parse($lastLogin)->setTimezone('Asia/Jakarta')->startOfDay();
            $today = $now->copy()->startOfDay();
            $diffInDays = $lastLoginDate->diffInDays($today);

            if ($diffInDays == 1) {
                $user->login_streak += 1;
            } elseif ($diffInDays > 1) {
                $user->login_streak = 1;
            }

            $pointsToday = $this->calculatePoints($user->login_streak);

            if (Carbon::parse($lastLogin)->setTimezone('Asia/Jakarta')->toDateString() !== $now->toDateString()) {
                $user->total_points += $pointsToday;
                $user->points_today = $pointsToday;
                $message = "Selamat! Anda mendapatkan {$user->points_today} poin hari ini.";
            }

            if ($user->login_streak == 30 && Carbon::parse($lastLogin)->setTimezone('Asia/Jakarta')->toDateString() !== $now->toDateString()) {
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

    // Fungsi untuk Mengirim Ulang Kode OTP Pendaftaran
    public function resendRegistrationOtp(Request $request)
    {
        $request->validate([
            'email' => 'required'
        ]);

        $user = User::where('email', $request->email)
                    ->orWhere('username', $request->email)
                    ->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data pengguna dengan email/username tersebut tidak ditemukan.'
            ], 404);
        }

        if (!is_null($user->email_verified_at) && $user->status_aktif === 'aktif') {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun Anda sudah diverifikasi dan aktif. Silakan langsung login.'
            ], 400);
        }

        // Generate OTP baru 6-digit
        $kodeOtp = rand(100000, 999999);
        $user->otp = $kodeOtp;
        $user->save();

        // Kirim WelcomeEmail berisi kode OTP baru
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user, $kodeOtp));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim ulang OTP pendaftaran ke ' . $user->email . ': ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim email OTP. Silakan periksa koneksi server atau coba lagi nanti.'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Kode OTP verifikasi baru telah dikirim ke email Anda.'
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

        // Kirim email khusus reset password
        try {
            Mail::to($user->email)->send(new ResetPasswordEmail($user, $kodeOtp));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim ResetPasswordEmail ke ' . $user->email . ': ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim email OTP. Silakan periksa konfigurasi mail server atau coba lagi nanti.'
            ], 500);
        }

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
        if ($streak == 1) return 100;
        if ($streak >= 2 && $streak <= 30) return 100 + ($streak - 1) * 100;
        return 3000;
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
        $currentLevelKey = strtolower($user->membership?->level_membership ?? 'bronze');
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

        // Cek status klaim harian untuk hari ini (WIB)
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $hasClaimedToday = false;

        if ($user->last_daily_claim_at && Carbon::parse($user->last_daily_claim_at)->setTimezone('Asia/Jakarta')->toDateString() === $today) {
            $hasClaimedToday = true;
        } else {
            $cacheKey = 'claimed_daily_reward_' . $user->id . '_' . $today;
            if (cache()->has($cacheKey)) {
                $hasClaimedToday = true;
            }
        }
        $user->has_claimed_daily_today = $hasClaimedToday;

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
    
    // --- FUNGSI BARU UNTUK MEMPROSES KLAIM POIN HARIAN DARI FLUTTER (ANTI-EXPLOIT) ---
    public function claimDailyReward(Request $request)
    {
        $user = $request->user();
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();

        // 1. Cek apakah user sudah mengklaim reward hari ini (cek database & cache)
        $alreadyClaimed = false;
        if ($user->last_daily_claim_at && Carbon::parse($user->last_daily_claim_at)->setTimezone('Asia/Jakarta')->toDateString() === $today) {
            $alreadyClaimed = true;
        }

        $cacheKey = 'claimed_daily_reward_' . $user->id . '_' . $today;
        if (cache()->has($cacheKey)) {
            $alreadyClaimed = true;
        }

        if ($alreadyClaimed) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah mengklaim reward harian hari ini. Silakan kembali besok setelah jam 12 malam!',
                'new_points' => $user->total_points ?? $user->points_today
            ], 400);
        }

        // 2. Tambahkan poin reward (100 poin)
        $rewardPoints = 100;
        $user->points_today = $rewardPoints;
        $user->total_points = ($user->total_points ?? 0) + $rewardPoints;
        $user->last_daily_claim_at = $now;
        $user->save();

        // 3. Tandai sudah klaim untuk hari ini (kedaluwarsa pukul 23:59:59 WIB)
        cache()->put($cacheKey, true, $now->copy()->endOfDay());

        return response()->json([
            'status' => 'success',
            'message' => 'Selamat! Reward harian 100 poin berhasil diklaim.',
            'new_points' => $user->total_points
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