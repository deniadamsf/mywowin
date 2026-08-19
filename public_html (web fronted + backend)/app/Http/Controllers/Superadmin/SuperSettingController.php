<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class SuperSettingController extends Controller
{
    public function index()
    {
        return view('superadmin.settings.index'); // pastikan view-nya sesuai
    }

     public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'username' => 'required|string|unique:users,username,' . $user->id,
        'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'password' => 'nullable|string|min:8',
    ]);

    // 1. Update info dasar
    $user->nama_lengkap = $request->nama_lengkap;
    $user->email = $request->email;
    $user->username = $request->username;

    // 2. Logika Upload Foto
    if ($request->hasFile('foto_profile')) {
        if ($user->foto_profile && Storage::disk('public')->exists($user->foto_profile)) {
            Storage::disk('public')->delete($user->foto_profile);
        }
        $path = $request->file('foto_profile')->store('profile', 'public');
        $user->foto_profile = $path; 
    }

    // 3. Update Password jika diisi
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    // 4. BAGIAN PENTING: Paksa redirect kembali ke halaman sebelumnya
    // Ini akan memicu browser untuk refresh dan menghentikan spinner/muter
    return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
}
}
