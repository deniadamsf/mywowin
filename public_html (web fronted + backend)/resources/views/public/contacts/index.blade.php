@extends('public.layouts.app')

@section('title', 'Hubungi Kami - WOWINFood')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<meta name="description" content="WOWINFood - Hubungi tim kami untuk pertanyaan, umpan balik, atau dukungan">
<!-- Font combination for professional look -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Lora:wght@400;500;600;700&display=swap">
<!-- Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
    :root {
        --primary-green: #16782d;
    }
    body { 
        font-family: 'Montserrat', sans-serif; 
    }
    h1, h2, h3 {
        font-family: 'Lora', serif;
    }
    .bg-primary-green {
        background-color: var(--primary-green);
    }
    .text-primary-green {
        color: var(--primary-green);
    }
    .border-primary-green {
        border-color: var(--primary-green);
    }
    .hover\:bg-primary-green:hover {
        background-color: var(--primary-green);
    }
    .hover\:text-primary-green:hover {
        color: var(--primary-green);
    }
    .hover\:border-primary-green:hover {
        border-color: var(--primary-green);
    }
    .shadow-custom {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('content')
<div class="bg-green-100">
    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden">
        <!-- Background image -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/ct.jpg') }}" alt="Latar Belakang Kontak" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-white to-transparent opacity-150"></div>
        </div>

        <!-- Green accent elements -->
        <div class="absolute bottom-0 left-0 w-full h-2 bg-white"></div>
        {{-- <div class="absolute top-0 left-0 w-1/4 h-1 bg-primary-green"></div> --}}
        <div class="absolute top-0 right-0 w-1/4 h-1 bg-primary-green"></div>

        <!-- Content -->
        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="md:max-w-2xl">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-green-80 tracking-tight leading-tight">
                    Hubungi <span class="text-primary-green">WOWINFood</span>
                </h1>
                <div class="w-24 h-1 bg-primary-green mt-6 mb-6"></div>
                <p class="mt-4 text-lg text-black leading-relaxed">
                    Tim kami berdedikasi untuk memberikan layanan yang luar biasa.
                    Kami menantikan kabar dari Anda dan siap membantu dengan pertanyaan Anda.
                </p>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="max-w-6xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left column - Contact Information -->
            <div class="lg:col-span-4">
                <div class="bg-white p-8 rounded-lg shadow-custom border-l-4 border-primary-green">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-8">Mari Terhubung</h2>
                    
                    <div class="space-y-8 mb-12">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1 w-10 h-10 rounded-full bg-green-50 flex items-center justify-center">
                                <i class="bi bi-geo-alt text-primary-green"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900 uppercase tracking-wider">Alamat Kantor</h3>
                                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                                    Jl. Raya KM 07<br>
                                     Duwet, Ngetal, Pogalan<br>
                                     Trenggalek,Indonesia
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1 w-10 h-10 rounded-full bg-green-50 flex items-center justify-center">
                                <i class="bi bi-telephone text-primary-green"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900 uppercase tracking-wider">Telepon</h3>
                                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                                    Layanan: 0355-792861<br>
                                    WhatsApp: +62 812 - 106 - 600
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1 w-10 h-10 rounded-full bg-green-50 flex items-center justify-center">
                                <i class="bi bi-envelope text-primary-green"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900 uppercase tracking-wider">Email</h3>
                                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                                    wowinfood@gmail.com<br>
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1 w-10 h-10 rounded-full bg-green-50 flex items-center justify-center">
                                <i class="bi bi-clock text-primary-green"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-900 uppercase tracking-wider">Jam Kerja</h3>
                                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                                    Senin - Jumat: 08:00 - 17:00<br>
                                    Sabtu: 08:00 - 16:00
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 uppercase tracking-wider mb-4">Terhubung Dengan Kami</h3>
                        <div class="flex space-x-4">
                            <a href="https://www.instagram.com/wowinfood?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:border-primary-green hover:text-primary-green transition-all">
                                <span class="sr-only">Instagram</span>
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:border-primary-green hover:text-primary-green transition-all">
                                <span class="sr-only">Facebook</span>
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:border-primary-green hover:text-primary-green transition-all">
                                <span class="sr-only">Twitter</span>
                                <i class="bi bi-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:border-primary-green hover:text-primary-green transition-all">
                                <span class="sr-only">LinkedIn</span>
                                <i class="bi bi-linkedin"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right column - Contact Form -->
            <div class="lg:col-span-8">
                <div class="bg-white shadow-custom rounded-lg overflow-hidden">
                    <div class="bg-green-50 px-8 py-4 border-b border-gray-100">
                        <h2 class="text-2xl font-semibold text-gray-800">Kirim Pesan</h2>
                    </div>
                    
                    <div class="p-8">
                        <form action="{{ route('contacts.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="relative">
                                    <label for="nama_lengkap" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" 
                                           class="block w-full px-4 py-3 border-gray-200 rounded-md focus:ring-primary-green focus:border-primary-green" 
                                           placeholder="Nama lengkap Anda">
                                </div>
                                
                                <div class="relative">
                                    <label for="email" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat Email</label>
                                    <input type="email" name="email" id="email" 
                                           class="block w-full px-4 py-3 border-gray-200 rounded-md focus:ring-primary-green focus:border-primary-green" 
                                           placeholder="email.anda@contoh.com">
                                </div>
                            </div>
                            
                            <div class="relative">
                                <label for="no_hp" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Nomor Telepon</label>
                                <input type="tel" name="no_hp" id="no_hp" 
                                       class="block w-full px-4 py-3 border-gray-200 rounded-md focus:ring-primary-green focus:border-primary-green" 
                                       placeholder="+62 8xx xxxx xxxx">
                            </div>
                            
                            <div class="relative">
                                <label for="pesan" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Pesan</label>
                                <textarea id="pesan" name="pesan" rows="5" 
                                          class="block w-full px-4 py-3 border-gray-200 rounded-md focus:ring-primary-green focus:border-primary-green" 
                                          placeholder="Silakan jelaskan pertanyaan Anda secara detail..."></textarea>
                            </div>
                            
                            <div class="flex items-center">
                                <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-primary-green focus:ring-primary-green border-gray-300 rounded">
                                <label for="terms" class="ml-2 block text-xs text-gray-600">
                                    Saya menyetujui <a href="#" class="text-primary-green hover:underline">kebijakan privasi</a> WOWINFood
                                </label>
                            </div>
                            
                            <div>
                                <button type="submit" class="px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-green hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-green transition-all">
                                    Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Map Section -->
    <div class="bg-white border-t border-gray-100">
        <div class="max-w-6xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <h2 class="text-3xl font-semibold text-gray-800 mb-2">Lokasi Kami</h2>
                <div class="w-16 h-1 bg-primary-green mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">Kunjungi kantor kami yang berlokasi di jantung Jakarta Selatan. Kami akan senang menyambut Anda.</p>
            </div>
            
            <div class="relative rounded-lg overflow-hidden shadow-custom">
                <!-- Decorative elements -->
                <div class="absolute top-0 left-0 w-full h-1 bg-primary-green"></div>
                <div class="absolute bottom-0 left-0 w-full h-1 bg-primary-green"></div>
                
                <iframe 
                    src="https://maps.google.com/maps?q=Jl.%20Raya%20No.Km%2007,%20Duwet,%20Ngetal,%20Pogalan,%20Trenggalek%20Regency,%20East%20Java%2066371&t=&z=15&ie=UTF8&iwloc=&output=embed"
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-gray-50">
        <div class="max-w-4xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <h2 class="text-3xl font-semibold text-gray-800 mb-2">Pertanyaan yang Sering Diajukan</h2>
                <div class="w-16 h-1 bg-primary-green mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">Temukan jawaban cepat untuk pertanyaan umum tentang layanan kami.</p>
            </div>
            
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-primary-green hover:shadow-md transition-all">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">
                        Metode pembayaran apa yang Anda terima?
                    </h3>
                    <p class="text-gray-600">
                        Kami menerima berbagai metode pembayaran termasuk transfer bank, kartu kredit, dan pembayaran melalui aplikasi dompet digital seperti OVO dan Gopay.
                    </p>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-primary-green hover:shadow-md transition-all">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">
                        Bagaimana cara melacak pesanan saya?
                    </h3>
                    <p class="text-gray-600">
                        Anda dapat melacak status pesanan melalui akun WOWINFood Anda dengan memasukkan nomor pesanan atau melalui tautan pelacakan yang dikirim ke email Anda setelah pembelian.
                    </p>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-primary-green hover:shadow-md transition-all">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">
                        Apa kebijakan pengembalian Anda?
                    </h3>
                    <p class="text-gray-600">
                        Produk dapat dikembalikan dalam waktu 7 hari setelah penerimaan, tunduk pada ketentuan kebijakan pengembalian kami. Harap pastikan produk dalam kondisi aslinya dengan semua kemasan utuh.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Artistic divider -->
    <div class="py-12 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center">
                <div class="flex-grow h-px bg-gray-200"></div>
                <div class="mx-4">
                    <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-green" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zm7-10a1 1 0 01.707.293l.707.707.707-.707A1 1 0 0116 2h3a1 1 0 110 2h-3a1 1 0 01-.707-.293L15 3.414l-.707.707A1 1 0 0113 4h-1a1 1 0 110-2h1zm-7 4a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1V7a1 1 0 011-1zm7 0a1 1 0 01.707.293l.707.707.707-.707A1 1 0 0116 6h3a1 1 0 110 2h-3a1 1 0 01-.707-.293L15 7.414l-.707.707A1 1 0 0113 8h-1a1 1 0 110-2h1zm-7 4a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-grow h-px bg-gray-200"></div>
            </div>
        </div>
    </div>
    
    <!-- Newsletter Section -->
    <div class="bg-green-50">
        <div class="max-w-4xl mx-auto py-16 px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">Tetap Terhubung</h2>
            <p class="text-gray-600 mb-8">Berlangganan newsletter kami untuk mendapatkan informasi terbaru tentang produk, promosi, dan wawasan kuliner.</p>
            
            <form class="max-w-md mx-auto flex">
                <input type="email" placeholder="Alamat email Anda" class="flex-1 px-4 py-3 rounded-l-md border-r-0 border-gray-300 focus:ring-primary-green focus:border-primary-green">
                <button type="submit" class="px-6 py-3 bg-primary-green text-white rounded-r-md hover:bg-green-700 transition-all">Berlangganan</button>
            </form>
        </div>
    </div>

    <!-- Call to action -->
    <div class="bg-white relative overflow-hidden">
        <!-- Decorative circles -->
        <div class="absolute top-10 left-10 w-32 h-32 rounded-full bg-green-100 opacity-20"></div>
        <div class="absolute -bottom-10 -right-10 w-64 h-64 rounded-full bg-green-200 opacity-20"></div>
        <div class="absolute top-1/2 left-1/4 w-16 h-16 rounded-full bg-green-300 opacity-10"></div>
        <div class="absolute bottom-1/4 right-1/3 w-24 h-24 rounded-full bg-green-100 opacity-15"></div>
        
        <div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-primary-green rounded-lg shadow-xl overflow-hidden relative">
                <!-- Inner glamour circles -->
                <div class="absolute -top-8 -left-8 w-24 h-24 rounded-full bg-white opacity-10"></div>
                <div class="absolute -bottom-10 -right-10 w-32 h-32 rounded-full bg-white opacity-10"></div>
                
                <!-- Gold/glamour accent elements -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-200 via-yellow-400 to-yellow-200 opacity-70"></div>
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-200 via-yellow-400 to-yellow-200 opacity-70"></div>
                
                <div class="px-6 py-12 md:py-16 md:px-12 text-center backdrop-blur-sm">
                    <!-- Glamour circle behind the heading -->
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-48 rounded-full bg-white opacity-5"></div>
                    
                    <h2 class="text-3xl font-bold text-white mb-4 relative">
                        <!-- Subtle gold accent -->
                        <span class="inline-block relative">
                            Siap <span class="text-yellow-300">Menjelajahi</span> Produk Kami?
                            <span class="absolute -bottom-1 left-0 w-full h-px bg-gradient-to-r from-transparent via-yellow-300 to-transparent"></span>
                        </span>
                    </h2>
                    
                    <p class="text-green-100 mb-8 max-w-2xl mx-auto relative z-10">
                        Temukan rangkaian produk makanan berkualitas tinggi kami yang dirancang untuk meningkatkan pengalaman kuliner Anda.
                    </p>
                    
                    <a href="{{ route('products') }}" class="inline-block bg-white text-primary-green font-semibold px-8 py-3 rounded-md hover:bg-yellow-50 transition-all shadow-md relative overflow-hidden group">
                        <!-- Button glamour effect -->
                        <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-yellow-200 via-transparent to-yellow-200 opacity-0 group-hover:opacity-30 transition-opacity"></span>
                        <span class="relative">Jelajahi Produk</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection