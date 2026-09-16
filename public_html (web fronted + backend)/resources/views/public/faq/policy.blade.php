@extends('public.layouts.app')

@section('title', 'Kebijakan Privasi | PT WOWIN PURNOMO PUTERA')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<script src="https://unpkg.com/alpinejs" defer></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8fafc;
        scroll-behavior: smooth;
    }
    section[id] {
        scroll-margin-top: 120px;
    }
    .content-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #e2e8f0;
        border-radius: 10px;
    }
</style>
@endsection

@section('content')
<div class="bg-[#16782d] pt-20 pb-40 px-4 relative overflow-hidden">
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-green-500 rounded-full opacity-20 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-green-700 rounded-full opacity-30 blur-3xl"></div>
    
    <div class="max-w-5xl mx-auto text-center relative z-10">
        <span class="inline-block px-3.5 py-1.5 bg-white/15 text-green-100 text-xs font-bold rounded-full uppercase tracking-wider mb-4 backdrop-blur-sm border border-white/20">
            Kepatuhan & Perlindungan Data Pengguna
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-tight animate__animated animate__fadeInDown">
            Kebijakan Privasi
        </h1>
        <p class="text-green-100 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed opacity-90">
            Transparansi resmi pengelolaan, perlindungan data pribadi, dan keamanan akun di ekosistem Aplikasi & Web My Wowin (PT Wowin Purnomo Putera).
        </p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 -mt-24 mb-24 relative z-20">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Sidebar Daftar Isi -->
        <div class="hidden lg:block w-1/4">
            <div class="sticky top-24 bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-5">Daftar Isi</h3>
                <nav class="space-y-1.5 text-xs font-semibold">
                    <a href="#pendahuluan" class="group flex items-center py-2 text-gray-600 hover:text-[#16782d] transition-all">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-[#16782d] mr-2.5"></span>
                        <span>1. Pendahuluan</span>
                    </a>
                    <a href="#data-dikumpulkan" class="group flex items-center py-2 text-gray-600 hover:text-[#16782d] transition-all">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-[#16782d] mr-2.5"></span>
                        <span>2. Data yang Dikumpulkan</span>
                    </a>
                    <a href="#penggunaan-data" class="group flex items-center py-2 text-gray-600 hover:text-[#16782d] transition-all">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-[#16782d] mr-2.5"></span>
                        <span>3. Penggunaan Data</span>
                    </a>
                    <a href="#keamanan" class="group flex items-center py-2 text-gray-600 hover:text-[#16782d] transition-all">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-[#16782d] mr-2.5"></span>
                        <span>4. Keamanan & Enkripsi Data</span>
                    </a>
                    <a href="#hapus-akun" class="group flex items-center py-2 text-red-600 hover:text-red-700 transition-all font-bold">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-400 mr-2.5"></span>
                        <span>5. Penghapusan Akun & Data</span>
                    </a>
                    <a href="#kontak" class="group flex items-center py-2 text-gray-600 hover:text-[#16782d] transition-all">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-[#16782d] mr-2.5"></span>
                        <span>6. Kontak & Bantuan</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="lg:w-3/4 w-full">
            <div class="content-card shadow-2xl rounded-[2rem] overflow-hidden border border-white/50">
                <div class="p-8 md:p-14">
                    
                    <!-- 1. Pendahuluan -->
                    <section id="pendahuluan" class="mb-14">
                        <div class="flex items-center space-x-3 mb-6">
                            <span class="px-4 py-1.5 bg-green-50 text-[#16782d] text-xs font-black rounded-full uppercase tracking-wider border border-green-100">
                                Diperbarui Agustus 2026
                            </span>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 tracking-tight">1. Pendahuluan</h2>
                        <p class="text-gray-600 leading-relaxed text-sm md:text-base font-light">
                            Selamat datang di <strong>My Wowin</strong>, platform digital resmi dari <strong>PT WOWIN PURNOMO PUTERA</strong> ("Kami", "Wowin Food"). Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, memproses, menyimpan, dan melindungi data pribadi Anda saat menggunakan aplikasi mobile Android <em>My Wowin</em> maupun portal web resmi kami (<a href="https://mywowin.com" class="text-[#16782d] font-semibold underline">https://mywowin.com</a>).
                        </p>
                    </section>

                    <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent mb-14"></div>

                    <!-- 2. Data yang Dikumpulkan -->
                    <section id="data-dikumpulkan" class="mb-14">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6 tracking-tight flex items-center">
                            <span class="w-9 h-9 bg-[#16782d] text-white rounded-xl flex items-center justify-center mr-3.5 shadow-md shadow-green-100 text-sm font-bold">02</span>
                            Data yang Kami Kumpulkan
                        </h2>
                        <p class="text-gray-600 text-sm leading-relaxed mb-6 font-light">
                            Untuk menyediakan layanan pemesanan produk grosir, manajemen membership kemitraan, dan pengiriman barang, kami mengumpulkan kategori data berikut:
                        </p>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="p-6 bg-slate-50 rounded-2xl border border-gray-100">
                                <div class="w-10 h-10 bg-green-100 text-[#16782d] rounded-xl flex items-center justify-center mb-4 font-bold text-lg">
                                    👤
                                </div>
                                <h4 class="text-base font-bold text-gray-900 mb-2">Informasi Akun & Identitas</h4>
                                <p class="text-gray-600 text-xs leading-relaxed">
                                    Nama lengkap, nama toko/badan usaha, alamat email, nomor handphone/WhatsApp aktif, serta kredensial kata sandi yang terenkripsi.
                                </p>
                            </div>

                            <div class="p-6 bg-slate-50 rounded-2xl border border-gray-100">
                                <div class="w-10 h-10 bg-green-100 text-[#16782d] rounded-xl flex items-center justify-center mb-4 font-bold text-lg">
                                    🚚
                                </div>
                                <h4 class="text-base font-bold text-gray-900 mb-2">Informasi Pengiriman & Toko</h4>
                                <p class="text-gray-600 text-xs leading-relaxed">
                                    Alamat lengkap toko/gudang pengantaran, pilihan kantor cabang distribusi terdekat (misal: Trenggalek, Kediri, dll.), dan catatan kurir.
                                </p>
                            </div>

                            <div class="p-6 bg-slate-50 rounded-2xl border border-gray-100">
                                <div class="w-10 h-10 bg-green-100 text-[#16782d] rounded-xl flex items-center justify-center mb-4 font-bold text-lg">
                                    💳
                                </div>
                                <h4 class="text-base font-bold text-gray-900 mb-2">Data Transaksi & Poin Loyalty</h4>
                                <p class="text-gray-600 text-xs leading-relaxed">
                                    Riwayat nota pemesanan, produk yang dibeli, metode pembayaran yang dipilih (Transfer Bank / COD), bukti transfer, akumulasi poin reward, dan status tier mitra.
                                </p>
                            </div>

                            <div class="p-6 bg-slate-50 rounded-2xl border border-gray-100">
                                <div class="w-10 h-10 bg-green-100 text-[#16782d] rounded-xl flex items-center justify-center mb-4 font-bold text-lg">
                                    🔔
                                </div>
                                <h4 class="text-base font-bold text-gray-900 mb-2">Perangkat & Notifikasi (FCM)</h4>
                                <p class="text-gray-600 text-xs leading-relaxed">
                                    Token Firebase Cloud Messaging (FCM) untuk mengirimkan update status pengiriman barang, notifikasi promo bundling, dan balasan Live Chat CS.
                                </p>
                            </div>
                        </div>
                    </section>

                    <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent mb-14"></div>

                    <!-- 3. Penggunaan Informasi -->
                    <section id="penggunaan-data" class="mb-14">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6 tracking-tight flex items-center">
                            <span class="w-9 h-9 bg-[#16782d] text-white rounded-xl flex items-center justify-center mr-3.5 shadow-md shadow-green-100 text-sm font-bold">03</span>
                            Tujuan Penggunaan Informasi
                        </h2>
                        <div class="space-y-3.5">
                            @php
                                $purposes = [
                                    'Memproses, memverifikasi, dan mengirimkan pesanan produk Wowin Food ke lokasi toko/gudang Anda.',
                                    'Menghitung skema diskon grosir, level kemitraan (Bronze, Silver, Gold, Platinum, Diamond), dan program poin reward harian.',
                                    'Menyediakan layanan bantuan pelanggan (Customer Care) dan live chat tanggap kendala.',
                                    'Mengirimkan pemberitahuan resmi mengenai invoice, resi pengiriman, serta pembaruan sistem.',
                                    'Mencegah aktivitas kecurangan (*fraud prevention*) dan melindungi keamanan transaksi mitra.'
                                ];
                            @endphp
                            @foreach($purposes as $item)
                            <div class="flex items-start p-3.5 bg-green-50/50 rounded-xl border border-green-100/60">
                                <div class="mt-0.5 flex-shrink-0 w-4 h-4 bg-[#16782d] text-white rounded-full flex items-center justify-center text-[10px] font-bold">
                                    ✓
                                </div>
                                <p class="ml-3 text-gray-700 text-xs md:text-sm leading-relaxed">{{ $item }}</p>
                            </div>
                            @endforeach
                        </div>
                    </section>

                    <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent mb-14"></div>

                    <!-- 4. Keamanan Informasi -->
                    <section id="keamanan" class="mb-14">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6 tracking-tight flex items-center">
                            <span class="w-9 h-9 bg-[#16782d] text-white rounded-xl flex items-center justify-center mr-3.5 shadow-md shadow-green-100 text-sm font-bold">04</span>
                            Keamanan & Perlindungan Data (HTTPS/TLS)
                        </h2>
                        <div class="bg-white border border-gray-200 p-6 md:p-8 rounded-2xl shadow-sm">
                            <p class="text-gray-600 text-xs md:text-sm leading-relaxed mb-4">
                                Seluruh komunikasi antara aplikasi mobile, browser web, dan server pusat <strong>PT WOWIN PURNOMO PUTERA</strong> dilindungi dengan enkripsi standar industri <strong>HTTPS / TLS (Transport Layer Security) 256-bit</strong>. Kata sandi pengguna disimpan menggunakan algoritma hashing satu arah yang aman.
                            </p>
                            <p class="text-gray-600 text-xs md:text-sm leading-relaxed font-semibold text-[#16782d]">
                                🔒 Kami tidak pernah dan tidak akan menjual, menyewakan, atau menyebarkan data pribadi Anda kepada pihak ketiga untuk kepentingan periklanan pihak luar.
                            </p>
                        </div>
                    </section>

                    <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent mb-14"></div>

                    <!-- 5. Hak Pengguna & Penghapusan Akun -->
                    <section id="hapus-akun" class="mb-14">
                        <div class="bg-red-50 border border-red-200 p-6 md:p-8 rounded-2xl">
                            <h2 class="text-xl md:text-2xl font-bold text-red-900 mb-3 flex items-center">
                                <span class="w-8 h-8 bg-red-600 text-white rounded-xl flex items-center justify-center mr-3 text-sm font-bold">05</span>
                                Hak Pengguna & Penghapusan Akun (Account Deletion)
                            </h2>
                            <p class="text-gray-700 text-xs md:text-sm leading-relaxed mb-4">
                                Sesuai regulasi perlindungan data dan kebijakan Google Play, Anda memiliki hak penuh untuk meminta penghapusan akun serta data pribadi Anda kapan saja melalui aplikasi atau melalui portal formulir web resmi kami.
                            </p>
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 pt-2">
                                <a href="{{ route('account.delete.request') }}" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center space-x-2">
                                    <span>Formulir Pengajuan Hapus Akun & Data</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                                <span class="text-xs text-gray-500">
                                    Proses verifikasi & eksekusi diproses oleh Admin dalam 1–3 hari kerja.
                                </span>
                            </div>
                        </div>
                    </section>

                    <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent mb-14"></div>

                    <!-- 6. Hubungi Kami -->
                    <section id="kontak" class="bg-gray-900 rounded-3xl p-8 md:p-10 text-center text-white overflow-hidden relative">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-[#16782d] rounded-full blur-3xl opacity-20 -mr-10 -mt-10"></div>
                        <h2 class="text-2xl md:text-3xl font-bold mb-3">Kontak Petugas Perlindungan Data</h2>
                        <p class="text-gray-400 mb-6 max-w-lg mx-auto text-xs md:text-sm leading-relaxed">
                            PT WOWIN PURNOMO PUTERA<br/>
                            Trenggalek, Jawa Timur, Indonesia
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                            <a href="mailto:cs@mywowin.com" class="w-full sm:w-auto px-6 py-3 bg-[#16782d] hover:bg-green-600 rounded-xl font-bold text-xs transition-all flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span>cs@mywowin.com</span>
                            </a>
                            <a href="https://wa.me/62812106600" target="_blank" class="w-full sm:w-auto px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl font-bold text-xs transition-all flex items-center justify-center space-x-2">
                                <span>WhatsApp Resmi: 0812-10-6600</span>
                            </a>
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection