<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Membership;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function showResetForm()
    {
        return view('auth.passwords.manual-reset');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'no_hp' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan']);
        }

        // Cari membership berdasarkan user_id
        $membership = Membership::where('user_id', $user->id)->first();
        if (!$membership || $membership->no_hp !== $request->no_hp) {
            return back()->withErrors(['no_hp' => 'No HP tidak cocok dengan data kami']);
        }

        // Update password user
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login.');
    }
}
