@extends('public.layouts.app')

@section('title', 'WOWINFood | Syarat & Ketentuan')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<script src="https://unpkg.com/alpinejs" defer></script>
<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Roboto', sans-serif;
    }
    .prose h3 {
        color: #16782d;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
    .prose p {
        margin-bottom: 1rem;
        line-height: 1.6;
        color: #4b5563;
    }
</style>
@endsection

@section('content')
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
        <span class="text-[#16782d] font-semibold">Syarat & Ketentuan</span>
    </div>
</div>

<div class="max-w-4xl mx-auto mt-6 px-4">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="bg-[#16782d] p-8 text-white text-center">
            <h1 class="text-3xl font-bold">Syarat & Ketentuan</h1>
            <p class="mt-2 opacity-90">Terakhir diperbarui: {{ date('d F Y') }}</p>
        </div>

        <div class="p-8 prose max-w-none">
            <p>Selamat datang di <strong>WOWINFood</strong>. Dengan mengakses dan menggunakan aplikasi atau situs kami, Anda dianggap telah membaca, memahami, dan menyetujui seluruh Syarat & Ketentuan di bawah ini.</p>

            <hr class="my-6 border-gray-100">

            <div class="space-y-6 text-sm md:text-base">
                <section>
                    <h3>1. Ketentuan Umum</h3>
                    <p>Layanan WOWINFood disediakan untuk memfasilitasi pengguna dalam melakukan pemesanan makanan dan produk terkait. Pengguna wajib berusia minimal 18 tahun atau di bawah pengawasan orang tua saat menggunakan layanan ini.</p>
                </section>

                <section>
                    <h3>2. Akun & Membership</h3>
                    <ul class="list-disc ml-5 space-y-2 text-gray-600">
                        <li>Untuk menjadi member <strong>MyWowin</strong>, pengguna harus memiliki riwayat transaksi (sales) terlebih dahulu.</li>
                        <li>Pengguna bertanggung jawab menjaga kerahasiaan informasi akun dan kata sandi.</li>
                        <li>WOWINFood berhak membekukan akun jika ditemukan indikasi kecurangan atau pelanggaran hukum.</li>
                    </ul>
                </section>

                <section>
                    <h3>3. Pemesanan & Pembayaran</h3>
                    <p>Semua pesanan yang telah dikonfirmasi bersifat final. Pembayaran dapat dilakukan melalui metode yang tersedia (Transfer Bank, E-Wallet, dll). Pesanan hanya akan diproses setelah verifikasi pembayaran berhasil.</p>
                </section>

                <section>
                    <h3>4. Pengiriman & Pengambilan (Pickup)</h3>
                    <p>Estimasi waktu pengiriman bergantung pada lokasi dan jasa ekspedisi. Untuk layanan <strong>Pickup Langsung</strong>, pengguna wajib menunjukkan bukti pemesanan yang sah di lokasi toko yang telah dipilih.</p>
                </section>

                <section>
                    <h3>5. Kebijakan Pembatalan & Refund</h3>
                    <p>Refund atau pengembalian dana hanya dapat diproses apabila terjadi kesalahan dari pihak kami (produk tidak tersedia atau rusak). Pengguna wajib melampirkan video unboxing sebagai syarat utama klaim.</p>
                </section>

                <section>
                    <h3>6. Perubahan Ketentuan</h3>
                    <p>WOWINFood berhak untuk mengubah, menambah, atau menghapus bagian dari Syarat & Ketentuan ini kapan saja tanpa pemberitahuan sebelumnya. Pastikan Anda memeriksa halaman ini secara berkala.</p>
                </section>
            </div>
        </div>

        <div class="bg-gray-50 p-6 text-center border-t border-gray-100">
            <p class="text-gray-500 text-sm">Punya pertanyaan mengenai syarat ini? <a href="{{ route('contacts') }}" class="text-[#16782d] font-bold underline">Hubungi Tim Bantuan Kami</a></p>
        </div>
    </div>
</div>

<br><br>

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