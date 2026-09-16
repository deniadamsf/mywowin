@extends('public.layouts.app')

@section('title', 'Penghapusan Akun & Data Pengguna | My Wowin')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8fafc;
        scroll-behavior: smooth;
    }
    .content-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
    }
</style>
@endsection

@section('content')
<div class="bg-[#16782d] pt-16 pb-36 px-4 relative overflow-hidden">
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-green-500 rounded-full opacity-20 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-green-700 rounded-full opacity-30 blur-3xl"></div>
    
    <div class="max-w-4xl mx-auto text-center relative z-10">
        <span class="inline-block px-3.5 py-1.5 bg-white/15 text-green-100 text-xs font-bold rounded-full uppercase tracking-wider mb-4 backdrop-blur-sm border border-white/20">
            Kepatuhan & Privasi Pengguna
        </span>
        <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight animate__animated animate__fadeInDown">
            Penghapusan Akun & Data
        </h1>
        <p class="text-green-100 text-base md:text-lg max-w-2xl mx-auto leading-relaxed opacity-90">
            Formulir resmi permohonan penutupan akun dan penghapusan data pribadi pada platform My Wowin (PT Wowin Purnomo Putera).
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 -mt-20 mb-20 relative z-20">
    <div class="content-card shadow-2xl rounded-3xl overflow-hidden border border-gray-100 p-6 md:p-12">

        @if(session('success_deletion'))
            <div class="bg-green-50 border border-green-200 rounded-2xl p-6 md:p-8 mb-8 text-center animate__animated animate__fadeIn">
                <div class="w-16 h-16 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl shadow-lg shadow-green-500/30">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Permohonan Berhasil Dikirim</h2>
                <p class="text-gray-600 max-w-lg mx-auto mb-6 text-sm leading-relaxed">
                    Terima kasih <strong>{{ session('success_deletion')['nama'] }}</strong>. Permohonan penghapusan akun untuk <strong>{{ session('success_deletion')['identifier'] }}</strong> telah diterima oleh sistem. Tim Admin akan memverifikasi dan memproses permintaan Anda dalam waktu <strong>1–3 hari kerja</strong>.
                </p>
                <a href="{{ url('/') }}" class="inline-block px-6 py-2.5 bg-[#16782d] hover:bg-[#125e23] text-white text-sm font-semibold rounded-xl transition-all shadow-md">
                    Kembali ke Beranda
                </a>
            </div>
        @endif

        <!-- Rincian Kebijakan -->
        <div class="grid md:grid-cols-2 gap-6 mb-10">
            <div class="bg-red-50/70 border border-red-100 rounded-2xl p-5">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold text-sm">
                        🗑️
                    </div>
                    <h3 class="font-bold text-red-900 text-sm">Data yang Dihapus Permanen:</h3>
                </div>
                <ul class="text-xs text-red-700 space-y-1.5 list-disc list-inside leading-relaxed">
                    <li>Kredensial login (Email, Password, Username).</li>
                    <li>Profil pengguna, nama toko, dan foto profil.</li>
                    <li>Alamat pengiriman dan nomor kontak.</li>
                    <li>Akumulasi saldo loyalty point dan status membership.</li>
                </ul>
            </div>

            <div class="bg-amber-50/70 border border-amber-100 rounded-2xl p-5">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm">
                        📋
                    </div>
                    <h3 class="font-bold text-amber-900 text-sm">Ketentuan Retensi & Verifikasi:</h3>
                </div>
                <ul class="text-xs text-amber-800 space-y-1.5 list-disc list-inside leading-relaxed">
                    <li>Akun tidak boleh memiliki pesanan yang sedang berjalan (*shipping/pending*).</li>
                    <li>Catatan invoice riwayat transaksi masa lalu diarsipkan secara anonim untuk keperluan audit pembukuan & hukum perpajakan.</li>
                    <li>Estimasi waktu verifikasi & eksekusi admin: 1–3 hari kerja.</li>
                </ul>
            </div>
        </div>

        <!-- Formulir Pengajuan -->
        <div class="border-t border-gray-100 pt-8">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Formulir Pengajuan Hapus Akun</h2>
            <p class="text-gray-500 text-xs md:text-sm mb-6">
                Isi data akun Anda di bawah ini dengan benar agar Admin dapat mencocokkan dan memverifikasi kepemilikan akun.
            </p>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('account.delete.submit') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Nama Lengkap / Nama Toko Terdaftar <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                        placeholder="Contoh: Budi Santoso / Toko Berkah"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-transparent text-sm transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Email atau Nomor WhatsApp Terdaftar <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="email_or_phone" value="{{ old('email_or_phone') }}" required
                        placeholder="Contoh: budi@gmail.com atau 08123456789"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-transparent text-sm transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Alasan Permohonan Penghapusan Akun <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alasan" rows="3" required
                        placeholder="Tuliskan alasan singkat Anda ingin menghapus akun..."
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-transparent text-sm transition-all">{{ old('alasan') }}</textarea>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <label class="flex items-start space-x-3 cursor-pointer">
                        <input type="checkbox" name="konfirmasi" value="1" required class="mt-1 w-4 h-4 text-[#16782d] focus:ring-[#16782d] rounded border-gray-300">
                        <span class="text-xs text-gray-700 leading-relaxed">
                            Saya menyatakan dengan sadar bahwa saya adalah pemilik sah dari akun ini dan meminta penghapusan akun secara permanen beserta seluruh akumulasi poin loyalty yang tidak dapat dikembalikan.
                        </span>
                    </label>
                </div>

                <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg shadow-red-600/30 transition-all text-sm flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span>Kirim Permohonan Hapus Akun</span>
                    </button>

                    <a href="https://wa.me/62812106600?text={{ urlencode('Halo Admin Wowin Food, saya ingin bantuan permohonan penutupan akun saya.') }}"
                       target="_blank"
                       class="text-xs text-gray-500 hover:text-[#16782d] font-semibold flex items-center space-x-1.5 transition-colors">
                        <span>Butuh bantuan cepat? Hubungi CS WhatsApp</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
