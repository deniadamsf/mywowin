<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Membership;
use App\Mail\ResetPasswordEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function showResetForm()
    {
        return view('auth.passwords.manual-reset');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Email tidak terdaftar di sistem kami.'], 404);
        }

        $kodeOtp = rand(100000, 999999);
        $user->otp = $kodeOtp;
        $user->save();

        try {
            Mail::to($user->email)->send(new ResetPasswordEmail($user, $kodeOtp));
        } catch (\Throwable $e) {
            Log::error('Web Reset Password OTP failed to send to ' . $user->email . ': ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal mengirim email OTP. Silakan coba lagi.'], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Kode OTP 6-digit telah dikirim ke email Anda.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'nullable|digits:6',
            'no_hp' => 'nullable',
            'password' => 'required|confirmed|min:6',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan']);
        }

        // Validasi kode OTP jika disertakan (Prioritas Utama)
        if ($request->filled('otp')) {
            if ($user->otp !== $request->otp) {
                return back()->withErrors(['otp' => 'Kode OTP verifikasi salah atau kedaluwarsa']);
            }
            $user->otp = null;
        } else {
            // Fallback validasi kepemilikan via nomor HP terdaftar
            $membership = Membership::where('user_id', $user->id)->first();
            if (!$membership || $membership->no_hp !== $request->no_hp) {
                return back()->withErrors(['no_hp' => 'Nomor HP atau Kode OTP tidak cocok dengan data terdaftar']);
            }
        }

        // Update password user
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login.');
    }
}
