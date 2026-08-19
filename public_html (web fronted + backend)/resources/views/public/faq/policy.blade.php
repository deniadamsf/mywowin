@extends('public.layouts.app')

@section('title', 'Kebijakan Privasi | WOWINFood')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<script src="https://unpkg.com/alpinejs" defer></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    /* Menggunakan font Inter untuk kesan profesional modern */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8fafc;
        scroll-behavior: smooth;
    }

    /* Memperbaiki masalah navigasi agar tidak tertutup header saat diklik */
    section[id] {
        scroll-margin-top: 120px;
    }

    .content-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
    }

    /* Custom scrollbar untuk sidebar agar lebih cantik */
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
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-tight animate__animated animate__fadeInDown">
            Kebijakan Privasi
        </h1>
        <p class="text-green-100 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed opacity-90">
            Transparansi dalam cara kami menjaga, mengelola, dan melindungi data pribadi Anda di ekosistem WOWINFood.
        </p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 -mt-24 mb-24 relative z-20">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <div class="hidden lg:block w-1/4">
            <div class="sticky top-24 bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Daftar Isi</h3>
                <nav class="space-y-2">
                    <a href="#pendahuluan" class="group flex items-center py-2 text-gray-600 hover:text-[#16782d] transition-all duration-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-[#16782d] mr-3 transition-all"></span>
                        <span class="font-semibold text-sm">1. Pendahuluan</span>
                    </a>
                    <a href="#data-dikumpulkan" class="group flex items-center py-2 text-gray-600 hover:text-[#16782d] transition-all duration-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-[#16782d] mr-3 transition-all"></span>
                        <span class="font-semibold text-sm">2. Data yang Dikumpulkan</span>
                    </a>
                    <a href="#penggunaan-data" class="group flex items-center py-2 text-gray-600 hover:text-[#16782d] transition-all duration-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-[#16782d] mr-3 transition-all"></span>
                        <span class="font-semibold text-sm">3. Penggunaan Data</span>
                    </a>
                    <a href="#keamanan" class="group flex items-center py-2 text-gray-600 hover:text-[#16782d] transition-all duration-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-[#16782d] mr-3 transition-all"></span>
                        <span class="font-semibold text-sm">4. Keamanan Informasi</span>
                    </a>
                    <a href="#kontak" class="group flex items-center py-2 text-gray-600 hover:text-[#16782d] transition-all duration-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-[#16782d] mr-3 transition-all"></span>
                        <span class="font-semibold text-sm">5. Hubungi Kami</span>
                    </a>
                </nav>
            </div>
        </div>

        <div class="lg:w-3/4 w-full">
            <div class="content-card shadow-2xl rounded-[2rem] overflow-hidden border border-white/50">
                <div class="p-8 md:p-14">
                    
                    <section id="pendahuluan" class="mb-16">
                        <div class="flex items-center space-x-3 mb-6">
                            <span class="px-4 py-1.5 bg-green-50 text-[#16782d] text-[10px] font-black rounded-full uppercase tracking-wider border border-green-100">
                                Diperbarui Mei 2024
                            </span>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 tracking-tight">1. Pendahuluan</h2>
                        <p class="text-gray-600 leading-loose text-lg font-light">
                            Selamat datang di <strong>WOWINFood</strong>. Kami sangat menghargai kepercayaan Anda dan berkomitmen untuk menjaga kerahasiaan setiap informasi pribadi yang Anda bagikan. Kebijakan ini merupakan bentuk transparansi kami dalam mengelola data Anda sesuai dengan regulasi perlindungan data yang berlaku di Indonesia.
                        </p>
                    </section>

                    <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent mb-16"></div>

                    <section id="data-dikumpulkan" class="mb-16">
                        <h2 class="text-3xl font-bold text-gray-900 mb-8 tracking-tight flex items-center">
                            <span class="w-10 h-10 bg-[#16782d] text-white rounded-xl flex items-center justify-center mr-4 shadow-lg shadow-green-100 text-sm font-bold">02</span>
                            Data yang Kami Kumpulkan
                        </h2>
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="p-8 bg-slate-50 rounded-3xl border border-gray-100 group hover:bg-white hover:shadow-xl hover:shadow-gray-100 transition-all duration-300">
                                <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-[#16782d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <h4 class="text-xl font-bold text-gray-800 mb-3">Informasi Profil</h4>
                                <p class="text-gray-500 text-sm leading-relaxed">Nama lengkap, tanggal lahir, dan preferensi akun MyWowin untuk pengalaman yang personal.</p>
                            </div>
                            <div class="p-8 bg-slate-50 rounded-3xl border border-gray-100 group hover:bg-white hover:shadow-xl hover:shadow-gray-100 transition-all duration-300">
                                <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-[#16782d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <h4 class="text-xl font-bold text-gray-800 mb-3">Informasi Kontak</h4>
                                <p class="text-gray-500 text-sm leading-relaxed">Alamat pengiriman, nomor WhatsApp aktif, dan alamat email untuk koordinasi pesanan.</p>
                            </div>
                        </div>
                    </section>

                    <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent mb-16"></div>

                    <section id="penggunaan-data" class="mb-16">
                        <h2 class="text-3xl font-bold text-gray-900 mb-8 tracking-tight flex items-center">
                            <span class="w-10 h-10 bg-[#16782d] text-white rounded-xl flex items-center justify-center mr-4 shadow-lg shadow-green-100 text-sm font-bold">03</span>
                            Penggunaan Informasi
                        </h2>
                        <div class="space-y-4">
                            @php
                                $purposes = [
                                    'Memproses dan mengirimkan pesanan makanan ke lokasi Anda dengan presisi.',
                                    'Mengelola poin loyalitas dan reward eksklusif member MyWowin.',
                                    'Meningkatkan kualitas layanan berdasarkan umpan balik (feedback) Anda.',
                                    'Memberikan notifikasi penting terkait promo, diskon, atau pembaruan sistem.'
                                ];
                            @endphp
                            @foreach($purposes as $item)
                            <div class="flex items-start p-4 hover:bg-green-50/50 rounded-2xl transition-colors">
                                <div class="mt-1 flex-shrink-0 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <p class="ml-4 text-gray-600 font-medium">{{ $item }}</p>
                            </div>
                            @endforeach
                        </div>
                    </section>

                    <section id="keamanan" class="relative group mb-16">
                        <div class="absolute -inset-1 bg-gradient-to-r from-green-600 to-[#16782d] rounded-[2rem] blur opacity-10 group-hover:opacity-20 transition duration-1000"></div>
                        <div class="relative bg-white border border-green-100 p-10 rounded-[2rem] shadow-sm">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-7 h-7 text-[#16782d] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                4. Keamanan Informasi
                            </h2>
                            <p class="text-gray-600 leading-loose">
                                Kami menggunakan teknologi enkripsi <strong>Secure Socket Layer (SSL)</strong> untuk memastikan data yang Anda kirimkan tidak dapat disadap oleh pihak lain. Selain itu, kami bekerja sama dengan payment gateway terverifikasi PCI-DSS untuk menjamin data pembayaran Anda diproses dengan standar keamanan internasional tanpa pernah disimpan di server internal kami.
                            </p>
                        </div>
                    </section>

                    <section id="kontak" class="bg-gray-900 rounded-[2.5rem] p-10 md:p-14 text-center text-white overflow-hidden relative">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-[#16782d] rounded-full blur-3xl opacity-20 -mr-10 -mt-10"></div>
                        <h2 class="text-3xl font-bold mb-6">Pertanyaan Lebih Lanjut?</h2>
                        <p class="text-gray-400 mb-10 max-w-lg mx-auto leading-relaxed text-sm md:text-base">
                            Jika Anda memerlukan klarifikasi mengenai penggunaan data pribadi Anda, tim Data Protection kami siap berdiskusi.
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                            <a href="mailto:privacy@wowinfood.com" class="w-full sm:w-auto px-10 py-4 bg-[#16782d] hover:bg-green-600 rounded-full font-bold transition-all shadow-xl shadow-green-900/20 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                Email Support
                            </a>
                            <a href="https://wa.me/+6281216301220" class="w-full sm:w-auto px-10 py-4 bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md rounded-full font-bold transition-all flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2 text-green-400" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                WhatsApp Chat
                            </a>
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 pb-20">
    <div class="bg-white rounded-3xl p-8 border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center">
                <img src="{{ asset('images/wwn-cr.png') }}" class="w-6 h-6" alt="Icon">
            </div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Pusat Bantuan</p>
                <p class="text-gray-800 font-bold">Butuh informasi cara belanja?</p>
            </div>
        </div>
        <a href="{{ route('faq') }}" class="px-8 py-3 bg-gray-50 hover:bg-[#16782d] hover:text-white text-gray-700 rounded-2xl font-bold transition-all duration-300">
            Kunjungi FAQ
        </a>
    </div>
</div>

@endsection