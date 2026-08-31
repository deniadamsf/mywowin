<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountDeletionController extends Controller
{
    /**
     * Tampilkan halaman formulir dan kebijakan penghapusan akun
     */
    public function show()
    {
        return view('public.delete_account');
    }

    /**
     * Proses pengajuan permohonan penghapusan akun
     */
    public function submit(Request $request)
    {
        $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'email_or_phone' => 'required|string|max:255',
            'alasan'         => 'required|string|max:1000',
            'konfirmasi'     => 'accepted',
        ], [
            'nama_lengkap.required'   => 'Nama lengkap wajib diisi.',
            'email_or_phone.required' => 'Email atau Nomor WhatsApp terdaftar wajib diisi.',
            'alasan.required'         => 'Silakan berikan alasan penghapusan akun.',
            'konfirmasi.accepted'     => 'Anda harus mencentang konfirmasi pemahaman konsekuensi penghapusan akun.',
        ]);

        $identifier = trim($request->input('email_or_phone'));
        $nama = trim($request->input('nama_lengkap'));
        $alasan = trim($request->input('alasan'));

        // Cari user yang bersangkutan jika ada
        $user = User::where('email', $identifier)
            ->orWhere('no_hp', $identifier)
            ->orWhere('username', $identifier)
            ->first();

        // Catat ke system log untuk rekam jejak audit kepatuhan
        Log::info('ACCOUNT_DELETION_REQUEST', [
            'user_id'        => $user ? $user->id : null,
            'identifier'     => $identifier,
            'nama_lengkap'   => $nama,
            'alasan'         => $alasan,
            'ip_address'     => $request->ip(),
            'user_agent'     => $request->userAgent(),
            'submitted_at'   => now()->toDateTimeString(),
        ]);

        return redirect()->route('account.delete.request')->with('success_deletion', [
            'nama'       => $nama,
            'identifier' => $identifier,
        ]);
    }
}
