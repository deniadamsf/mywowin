<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Ilustration;
use App\Models\User;
use App\Models\Membership;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail; // Kita buat mail ini nanti
use Illuminate\Support\Carbon;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register'); // Bikin view register nanti
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
           'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'nama_lengkap' => 'required|string|max:255',
            'password' => 'required|min:6',
            // 'role' => 'required|in:admin,member',
        //    'status_aktif' => 'required|in:aktif,tidak aktif',

            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png',
            'nama_toko'     => 'required|string|max:100',
            'alamat'        => 'required|string',
            'no_hp'         => 'required|string|max:15',
            'nama_sales' => 'required|string|max:100',
            'kantor_cabang' => 'required|in:Trenggalek,Kediri,Madiun,Solo,Jogja,Cirebon,Kudus,Bogor,Serang',
        ]);

        $fotoProfilePath = null;
        if ($request->hasFile('foto_profile')) {
            $file = $request->file("foto_profile");
            $fileName = time() . "_" . $file->getClientOriginalName();
            $fotoProfilePath = $file->storeAs('foto_profile', $fileName, 'public');
        }

        // Enkripsi password
        $validated["password"] = bcrypt($validated["password"]);

        // dd($validated);

        // Simpan data ke tabel users
        $user = User::create([
            'username' => $validated["username"],
            'nama_lengkap' => $validated["nama_lengkap"],
            'email' => $validated["email"],
            'password' => $validated["password"],
            'role' => 'member',
            'status_aktif' => 'tidak aktif',
            'foto_profile' => $fotoProfilePath,
            'kantor_cabang' => $validated["kantor_cabang"],
            'last_login_at' => now(),  // Set last login time saat registrasi
            'login_streak' => 1,       // Set streak login pertama
            'total_points' => 1000,     // Set poin hari pertama
            'points_today' => 1000,     // Set poin hari pertama
            // kok kembai ke situ
        ]);


        
            Membership::create([
                'user_id' => $user->id,
                'nama_toko' => $validated["nama_toko"],
                'alamat' => $validated["alamat"],
                'no_hp' => $validated["no_hp"],
                'nama_sales' => $validated["nama_sales"],
            ]);

             // Kirim email notifikasi
        Mail::to($user->email)->send(new WelcomeEmail($user));

        // Auth::login($user);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Cek email untuk informasi lebih lanjut.');; // Arahkan ke halaman utama
    } // mana emailnya

     // Menampilkan form login
     // Menampilkan form login
     public function showLoginForm()
     {
         $ilustration = Ilustration::latest()->first(); // Ambil ilustrasi terbaru
         return view('auth.login', compact('ilustration'));
     }
     public function login(Request $request)
     {
         $credentials = $request->validate([
             'username' => 'required',
             'password' => 'required|min:6',
         ]);
     
         if (Auth::attempt($credentials)) {
             $request->session()->regenerate();
     
             $user = User::find(Auth::id()); // ✅ selalu return model Eloquent
              // ✅ Cek apakah user sudah aktif
            if ($user->status_aktif == 'tidak aktif') {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'username' => 'Akun Anda belum diaktifkan oleh admin.'
                ]);
            }
             $role = $user->role;
             $now = Carbon::now();
     
             $lastLogin = $user->last_login_at;
     
             // Jika ini login pertama, set poin hari pertama
             if (!$lastLogin) {
                 $user->login_streak = 1;
                 $user->points_today = 1000;  // Poin untuk login pertama
                 $user->total_points = 1000;  // Poin total pada hari pertama
                 session()->flash('success', "🎉 Selamat! Anda mendapatkan 1000 poin tambahan hari ini.");
             } else {
                 $lastLoginDate = Carbon::parse($lastLogin)->startOfDay();
                 $today = $now->copy()->startOfDay();
                 $diffInDays = $lastLoginDate->diffInDays($today);
     
                 if ($diffInDays == 1) {
                     $user->login_streak += 1;
                 } elseif ($diffInDays > 1) {
                     $user->login_streak = 1;
                 }
     
                 // Hitung poin berdasarkan streak login
                 $pointsToday = $this->calculatePoints($user->login_streak);
     
                 if ($lastLogin && Carbon::parse($lastLogin)->toDateString() !== $now->toDateString()) {
                     $user->total_points += $pointsToday;
                     $user->points_today = $pointsToday;
                     session()->flash('success', "🎉 Selamat! Anda mendapatkan {$user->points_today} poin hari ini.");
                 } elseif ($lastLogin) {
                     session()->flash('welcome_back', true);
                 }
             }
     
             // Simpan waktu login terakhir
             $user->last_login_at = $now;
             $user->save(); // ✅ Ini akan berhasil karena $user masih instance User
             
     
             // Cek streak 30 hari berturut-turut
             if ($user->login_streak == 30) {
                 session()->flash('success', "🔥 Hebat! Anda telah login selama 30 hari berturut-turut dan mendapatkan {$user->points_today} poin!");
             }
     
             // Tentukan rute berdasarkan role
             if ($role === 'super_admin') {
                 return redirect()->route('superadmin.dashboard')->with('success', 'Login super admin berhasil!');
             } elseif ($role === 'admin') {
                 return redirect('/admin/dashboard')->with('success', 'Login admin berhasil!');
             } else {
                 return redirect('/')->with('success', 'Login berhasil!');
             }
         }
     
         return back()->withErrors(['username' => 'Username atau password salah'])->onlyInput('username');
     }  
     
     
     
 
     // Fungsi untuk menghitung poin berdasarkan streak
     private function calculatePoints($streak)
     {
         // Poin untuk login pertama
         if ($streak == 1) {
             return 1000; // Poin untuk hari pertama
         }
 
         // Poin untuk login kedua hingga ke-30
         if ($streak >= 2 && $streak <= 30) {
             return 1000 + ($streak - 1) * 1000; // Setiap hari bertambah 100 poin
         }
 
         // Poin maksimal setelah hari ke-30
         return 30000; // Poin maksimal pada hari ke-30 dan seterusnya
     }

     
     
    
     
 
     // Proses logout
     public function logout(Request $request)
     {
         Auth::logout();
         $request->session()->invalidate();
         $request->session()->regenerateToken();
         return redirect('/login')->with('success', 'Logout berhasil!');
     }
 
}
