@extends('public.layouts.app')

@section('title', 'WOWINFood | Artikel')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<script src="https://unpkg.com/alpinejs" defer></script>
<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Roboto', sans-serif;
    }
</style>


@endsection

@section('content')
<!-- Breadcrumb Navigation yang Lebih Elegan -->
<div class="container mx-auto px-4 py-4">
    <div class="flex items-center space-x-2 bg-white px-4 py-2 rounded-lg shadow-md w-full max-w-7xl border-l-4 border-[#16782d]">
        <a href="/" class="text-gray-600 hover:text-[#16782d] font-medium transition-colors duration-300 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Beranda
        </a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('faq') }}" class="text-[#16782d] font-semibold flex items-center">
            {{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg> --}}
            Pertanyaan Umum
        </a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-[#16782d] font-semibold inline-flex items-center">
            Pengiriman Gratis
        </span>
    </div>
</div>
<br>
<h1 class="text-2xl font-semibold text-black text-center">Pengiriman Gratis</h1>
<!-- Accordion FAQ MyWowin -->
<div class="max-w-3xl mx-auto mt-6">
    <div class="bg-white shadow-lg rounded-xl divide-y divide-gray-200">
        @php
            $faqs = [
               // Pertanyaan pengiriman gratis
    ['q' => '🚚 Apakah tersedia pengiriman gratis?', 'a' => 'Ya, kami menyediakan layanan pengiriman gratis untuk pembelian dengan jumlah tertentu atau selama periode promo khusus. Cek halaman promo atau saat checkout untuk informasi lebih lanjut.'],

['q' => '🚚 Kapan saya bisa mendapatkan pengiriman gratis?', 'a' => 'Pengiriman gratis dapat diperoleh saat melakukan pembelian dengan nilai tertentu, atau pada event promo khusus yang kami selenggarakan. Pastikan untuk memeriksa ketentuan promo di halaman checkout.'],

['q' => '🚚 Apakah pengiriman gratis berlaku untuk semua produk?', 'a' => 'Pengiriman gratis berlaku untuk sebagian besar produk, namun beberapa produk tertentu mungkin tidak termasuk dalam promo ini. Pastikan untuk melihat detail produk sebelum checkout.'],

['q' => '🚚 Bagaimana cara mengetahui apakah saya memenuhi syarat untuk pengiriman gratis?', 'a' => 'Saat checkout, kamu akan melihat informasi apakah pesananmu memenuhi syarat untuk pengiriman gratis. Kamu juga bisa memeriksa syarat dan ketentuan di halaman promo atau FAQ pengiriman kami.'],
            ];
        @endphp

        @foreach ($faqs as $index => $faq)
            <div x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }" class="px-6 py-4">
                <button @click="open = !open" class="w-full text-left flex justify-between items-center focus:outline-none">
                    <span :class="open ? 'text-red-600 font-semibold' : 'text-gray-800'" class="transition duration-200">
                        {{ $faq['q'] }}
                    </span>
                    <svg :class="open ? 'transform rotate-180 text-red-600' : 'text-gray-600'" class="w-4 h-4 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="text-gray-600 mt-2 text-sm">
                    {{ $faq['a'] }}
                </div>
            </div>
        @endforeach
    </div>
</div>
<br>
<br>
<!-- Enhanced Professional FAQ Topics Section with Slim Buttons -->
<div class=" py-12">
    <div class="container mx-auto px-4">
      <div class="text-center mb-10">
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Pilih Topik Sesuai Pertanyaan Anda</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Temukan jawaban atas pertanyaan Anda dengan memilih kategori yang sesuai dengan kebutuhan Anda.</p>
      </div>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 max-w-4xl mx-auto">
        <!-- Slim Buttons with Icon Left and Text Right -->
        <a href="{{ route('faq') }}" class="bg-white rounded-lg shadow hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">
          <div class="p-3 flex items-center">
            <div class="w-10 h-10 bg-green-200 rounded-full shadow flex items-center justify-center mr-4 group-hover: transition-colors duration-300">
              <img src="{{ asset('images/wwn-cr.png') }}" alt="Aplikasi" class="w-7 h-7">
            </div>
            <h3 class="text-gray-800 font-medium group-hover:text-[#16782d] transition-colors duration-300 text-sm">Aplikasi</h3>
          </div>
        </a>
        
        <a href="{{ route('howtobuy') }}" class="bg-white rounded-lg shadow hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">
          <div class="p-3 flex items-center">
            <div class="w-10 h-10 bg-green-200 rounded-full shadow flex items-center justify-center mr-4 group-hover: transition-colors duration-300">
              <img src="{{ asset('images/cart.png') }}" alt="Cara Belanja" class="w-7 h-7">
            </div>
            <h3 class="text-gray-800 font-medium group-hover:text-[#16782d] transition-colors duration-300 text-sm">Cara Belanja</h3>
          </div>
        </a>
        
        <a href="{{ route('shipping') }}" class="bg-white rounded-lg shadow hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">
          <div class="p-3 flex items-center">
            <div class="w-10 h-10 bg-green-200 rounded-full shadow flex items-center justify-center mr-4 group-hover: transition-colors duration-300">
              <img src="{{ asset('images/ship.png') }}" alt="Pengiriman" class="w-7 h-7">
            </div>
            <h3 class="text-gray-800 font-medium group-hover:text-[#16782d] transition-colors duration-300 text-sm">Pengiriman</h3>
          </div>
        </a>
        
        <a  href="{{ route('freong') }}" class="bg-white rounded-lg shadow hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">
          <div class="p-3 flex items-center">
            <div class="w-10 h-10 bg-green-200 rounded-full shadow flex items-center justify-center mr-4 group-hover: transition-colors duration-300">
              <img src="{{ asset('images/freong.png') }}" alt="Gratis Ongkir" class="w-7 h-7">
            </div>
            <h3 class="text-gray-800 font-medium group-hover:text-[#16782d] transition-colors duration-300 text-sm">Gratis Ongkir</h3>
          </div>
        </a>
        
        <a  href="{{ route('pickup') }}" class="bg-white rounded-lg shadow hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">
          <div class="p-3 flex items-center">
            <div class="w-10 h-10 bg-green-200 rounded-full shadow flex items-center justify-center mr-4 group-hover: transition-colors duration-300">
              <img src="{{ asset('images/toko.png') }}" alt="Pickup Langsung" class="w-7 h-7">
            </div>
            <h3 class="text-gray-800 font-medium group-hover:text-[#16782d] transition-colors duration-300 text-sm">Pickup Langsung</h3>
          </div>
        </a>
        
        <a href="{{ route('transaction') }}" class="bg-white rounded-lg shadow hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">
          <div class="p-3 flex items-center">
            <div class="w-10 h-10 bg-green-200 rounded-full shadow flex items-center justify-center mr-4 group-hover: transition-colors duration-300">
              <img src="{{ asset('images/dollar.png') }}" alt="Pembayaran" class="w-7 h-7">
            </div>
            <h3 class="text-gray-800 font-medium group-hover:text-[#16782d] transition-colors duration-300 text-sm">Pembayaran</h3>
          </div>
        </a>
        
        <a  href="{{ route('refund') }}" class="bg-white rounded-lg shadow hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">
          <div class="p-3 flex items-center">
            <div class="w-10 h-10 bg-green-200 rounded-full shadow flex items-center justify-center mr-4 group-hover: transition-colors duration-300">
              <img src="{{ asset('images/wallet.png') }}" alt="Refund" class="w-7 h-7">
            </div>
            <h3 class="text-gray-800 font-medium group-hover:text-[#16782d] transition-colors duration-300 text-sm">Refund</h3>
          </div>
        </a>
        
        <a  href="{{ route('faq-member') }}" class="bg-white rounded-lg shadow hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">
          <div class="p-3 flex items-center">
            <div class="w-10 h-10 bg-green-200 rounded-full shadow flex items-center justify-center mr-4 group-hover: transition-colors duration-300">
              <img src="{{ asset('images/membership.png') }}" alt="Membership" class="w-7 h-7">
            </div>
            <h3 class="text-gray-800 font-medium group-hover:text-[#16782d] transition-colors duration-300 text-sm">Membership</h3>
          </div>
        </a>
      </div>
    </div>
  </div>

@endsection