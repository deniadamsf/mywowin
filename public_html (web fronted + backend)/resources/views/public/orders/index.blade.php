@extends('public.layouts.app')

@section('title', 'Ringkasan Pesanan | WOWINFood')
@section('description', 'WOWINFood - Ringkasan Pesanan')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">


@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-red-100 rounded-full shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18m-9 5h9" />
                    </svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight">
                    Ringkasan Pesanan
                </h1>
            </div>
            <span class="hidden md:inline-block text-sm text-gray-500 font-medium">Wowin Food-Sahabat Hidangan Anda</span>
        </div>
        <p class="mt-1 text-sm text-gray-600">Silakan periksa detail pesanan Anda sebelum melanjutkan ke pembayaran.</p>
    </div>
    <svg viewBox="0 0 120 6" preserveAspectRatio="none" class="w-full h-3 mb-6">
        <defs>
          <pattern id="airmail-stripes" patternUnits="userSpaceOnUse" width="20" height="6">
            <rect x="0" y="2" width="8" height="2" fill="#EF4444" /> <!-- merah -->
            <rect x="10" y="2" width="8" height="2" fill="#06B6D4" /> <!-- biru -->
          </pattern>
        </defs>
        <rect width="100%" height="6" fill="url(#airmail-stripes)" />
      </svg>
      
    
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Left Column: Recipient and Order Details -->
        <div class="w-full md:w-3/5">
            <!-- Recipient Details -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Penerima</h2>
                <div class="border-b border-gray-200 pb-4">
                    <p class="font-medium text-gray-800">{{ $user->nama_lengkap }} - {{ $membership?->no_hp ?? '081216301220' }}</p>
                    <p class="text-gray-700 mt-1">{{ $membership?->nama_toko ?? 'Toko Mitra Wowin' }} | {{ $membership?->nama_sales ?? 'Pusat Wowin' }}</p>
                    <p class="text-gray-600 text-sm mt-1">{{ $membership?->alamat ?? 'Jl. Raya Trenggalek - Tulungagung No. KM 07, Pogalan, Trenggalek, Jawa Timur 66371' }}</p>
                </div>
            </div>
            <br>
            <!-- Shipping Details -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-base font-semibold text-gray-900">Pengiriman</h2>
                <div class="flex justify-between items-center mb-4">
                    <p class="text-gray-500 text-xs">No. Pesanan: <span class="text-gray-700">{{ $orderNumber }}</span></p>
                </div>
                
            </div>

            <div class="mb-6">
                <div class="flex items-start gap-2 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-gray-700 text-sm">Kamis, 17 April 2025</span>
                </div>
                <div class="flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-gray-700 text-sm">Maks. 1 jam setelah pembayaran selama jam operasional (07:00 - 21:00)</span>
                </div>
            </div>
            <br>
            <!-- Product Section -->
<div class="mb-8">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/store.png') }}" alt="Store Icon" class="w-5 h-5">
            <h2 class="text-base font-semibold text-gray-900">Stok dari Toko</h2>
        </div>
        <p class="text-gray-500 text-xs"><span class="text-gray-700">PT. Wowin Purnomo Putera</span></p>
    </div>

    @foreach ($cartItems as $cart)
   @php
    $isBundling = $cart->bundling !== null;

   // --- LOGIKA HARGA BARU ---
   if ($isBundling) {
        $price = $cart->bundling->price ?? 0;
        $itemName = $cart->bundling->nama_bundling;
    } elseif ($cart->product) {
        $price = $cart->price ?? 0; // <-- DIPERBAIKI: Mengambil dari cart->price
        $itemName = $cart->product->nama_produk;
    } else {
        $price = 0;
        $itemName = "Produk tidak ditemukan";
    }

    $total = $price * $cart->quantity;
@endphp


    <div class="flex items-center py-4 border-t border-b border-gray-200">
        {{-- Gambar bundling atau produk --}}
        @if($isBundling && !empty($cart->bundling->barang_bundling))
            <div class="w-16 h-16 mr-4 rounded overflow-hidden">
                <img src="{{ asset('storage/' . $cart->bundling->barang_bundling) }}"
                     alt="{{ $cart->bundling->nama_bundling }}"
                     class="w-full h-full object-cover">
            </div>
        @elseif(!$isBundling && $cart->product && $cart->product->images->isNotEmpty())
            <div class="w-16 h-16 mr-4 rounded overflow-hidden">
                <img src="{{ asset('storage/' . $cart->product->images->first()->image_url) }}"
                     alt="{{ $cart->product->nama_produk }}"
                     class="w-full h-full object-cover">
            </div>
        @else
            <div class="w-16 h-16 mr-4 bg-gray-100 flex items-center justify-center rounded">
                <i class="bi bi-image text-gray-400 text-2xl"></i>
            </div>
        @endif

        {{-- Info produk/bundling --}}
        <div class="flex-1">
            @if($isBundling)
                <h3 class="font-bold text-sm text-green-800">{{ $itemName }}</h3>
                <div class="text-xs text-gray-600 bg-gray-100 px-2 py-1 mt-1 rounded-md inline-block">
                Item bundling
                </div>

                {{-- Produk di bundling --}}
        <ul class="mt-2 ml-4 list-disc text-xs text-gray-600">
                @foreach ($cart->bundling->products as $product)
                <li>{{ $product->nama_produk }}</li>
                @endforeach
        </ul>

         @else
            <h3 class="font-bold text-sm text-gray-800">{{ $itemName }}</h3>
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md inline-block mt-1">
            Unit: <strong>{{ Str::ucfirst($cart->unit ?? 'Karton') }}</strong>
            </span>
        @endif

            {{-- Harga dan jumlah --}}
            <div class="flex justify-between items-center mt-2">
                <div>
                    <span class="text-gray-700">Rp {{ number_format($price, 0, ',', '.') }}</span>
                    <span class="text-gray-500 text-sm ml-2">Jumlah beli: {{ $cart->quantity }}</span>
                </div>
                <p class="text-xs font-semibold text-[#16782d]">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
@endforeach



           <!-- Container tambahan di bawah Left Column -->
            <div class="mt-6 flex items-start gap-3 bg-gray-50 border border-gray-200 p-4 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8z" />
                </svg>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Dengan memesan, Anda menyetujui <span class="underline decoration-dotted hover:text-red-600 transition">Ketentuan Pengguna dan Penjualan</span> Wowin Food. Transaksi ini akan dilakukan di platform kami menggunakan metode pembayaran yang tersedia.
                </p>
            </div>

            </div>
        </div>
        
        <!-- Right Column: Summary and A-Poin -->
        <div class="w-full md:w-1/3">
            <div class="border border-gray-200 rounded-lg p-5">
                <!-- Order Summary -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>
                    <form id="orderForm" action="{{ route('public.orders.store') }}" method="POST">
                        @csrf

                    @php
                        $userPoints = Auth::user()->total_points ?? 0;
                        $shipping = $shippingCost ?? 0;
                        $shippingDisc = $shippingDiscount ?? 0;
                        $netShipping = $netShippingCost ?? ($shipping - $shippingDisc);
                        $initialTotal = ($subtotal - $discountData['discountAmount']) + $netShipping;
                        $maxPointDiscount = min($userPoints, $initialTotal);
                    @endphp

                    @if($shippingDisc > 0)
                    <!-- Voucher Diskon Ongkir Aktif Banner -->
                    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center text-green-700">
                                <i class="fas fa-ticket-alt text-xs"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-green-900 block">{{ $shippingCalculation['voucher_name'] ?? 'Voucher Diskon Ongkir' }}</span>
                                <span class="text-xs text-green-700">Diskon Rp {{ number_format($shippingDisc, 0, ',', '.') }} otomatis diterapkan pada ongkir J&T Express</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($userPoints > 0)
                    <!-- Poin Loyalitas Section -->
                    <div class="mb-4 bg-amber-50 border border-amber-200 rounded-lg p-3.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                                    <i class="fas fa-coins text-sm"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-amber-900 block">Poin Loyalitas</span>
                                    <span class="text-xs text-amber-700">Tersedia: <b>{{ number_format($userPoints) }} Poin</b> (Hemat Rp {{ number_format($maxPointDiscount, 0, ',', '.') }})</span>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="use_points" value="1" id="usePointsCheckbox" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#16782d]"></div>
                            </label>
                        </div>
                    </div>
                    @endif

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Diskon ({{ $discountData['discountPercent'] }}%)</span>
                            <span class="text-red-600 font-medium">- Rp {{ number_format($discountData['discountAmount'], 0, ',', '.') }}</span>
                        </div>
                        <div id="pointDiscountRow" class="hidden flex justify-between text-green-700 font-medium">
                            <span>Potongan Poin</span>
                            <span>- Rp <span id="pointDiscountDisplay">{{ number_format($maxPointDiscount, 0, ',', '.') }}</span></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <div>
                                <span class="text-gray-600 block">Ongkos Kirim (J&T Express EZ)</span>
                                <span class="text-xs text-gray-400 font-normal">{{ $totalWeightKg ?? 1.0 }} Kg &bull; {{ $shippingCalculation['description'] ?? 'Tarif Flat VIP J&T' }}</span>
                            </div>
                            <span class="text-gray-800 font-medium {{ $shippingDisc > 0 ? 'line-through text-gray-400' : '' }}">Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                        </div>
                        @if($shippingDisc > 0)
                        <div class="flex justify-between items-center text-sm text-green-700 font-medium">
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-ticket-alt text-xs"></i>
                                <span>Diskon Ongkir ({{ $shippingCalculation['voucher_code'] ?? 'Voucher' }})</span>
                            </div>
                            <span>- Rp {{ number_format($shippingDisc, 0, ',', '.') }}</span>
                        </div>
                        @endif
                       <div class="flex justify-between font-semibold pt-3 border-t border-gray-200">
                            <span class="text-gray-800">Total Belanja</span>
                            
                            <span id="grandTotalDisplay" class="text-gray-900 font-bold text-lg">
                                Rp {{ number_format($initialTotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Metode Pembayaran Section -->
                <div class="relative bg-[#16782d] rounded-lg p-4 mb-6">
                    <h2 class="text-lg font-semibold text-white mb-3">Pilih Metode Pembayaran</h2>

                    <div class="space-y-3">
                        @if(isset($activePaymentMethods) && $activePaymentMethods->isNotEmpty())
                            @foreach($activePaymentMethods as $pm)
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="metode_pembayaran" value="{{ $pm->code }}" class="form-radio text-blue-600" {{ $loop->first ? 'checked' : '' }}>
                                    <span class="text-white">{{ $pm->name }}</span>
                                </label>
                            @endforeach
                        @else
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="metode_pembayaran" value="transfer" class="form-radio text-blue-600" checked>
                                <span class="text-white">Transfer Bank (BCA / BRI)</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" name="metode_pembayaran" value="wa" class="form-radio text-blue-600">
                                <span class="text-white">Pesan via WhatsApp (WA)</span>
                            </label>
                        @endif
                    </div>

                    <!-- Error Message Alert -->
                    <div id="errorAlert" class="hidden mt-4 bg-red-600 text-white rounded-lg px-4 py-3 shadow-md">
                        <span class="text-xs">⚠️ Pilih metode pembayaran</span>
                    </div>
                </div>

                <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const form = document.getElementById("orderForm");
                    const errorAlert = document.getElementById("errorAlert");
                    const radios = document.querySelectorAll('input[name="metode_pembayaran"]');
                    const usePointsCheckbox = document.getElementById("usePointsCheckbox");
                    const pointDiscountRow = document.getElementById("pointDiscountRow");
                    const grandTotalDisplay = document.getElementById("grandTotalDisplay");

                    const initialTotal = {{ $initialTotal }};
                    const pointDiscount = {{ $maxPointDiscount }};

                    if (usePointsCheckbox) {
                        usePointsCheckbox.addEventListener("change", function() {
                            if (this.checked) {
                                pointDiscountRow.classList.remove("hidden");
                                const newTotal = Math.max(0, initialTotal - pointDiscount);
                                grandTotalDisplay.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(newTotal);
                            } else {
                                pointDiscountRow.classList.add("hidden");
                                grandTotalDisplay.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(initialTotal);
                            }
                        });
                    }

                    // Saat submit
                    form.addEventListener("submit", function(e) {
                        const metode = document.querySelector('input[name="metode_pembayaran"]:checked');
                        if (!metode) {
                            e.preventDefault();
                            errorAlert.classList.remove("hidden");
                        } else {
                            errorAlert.classList.add("hidden");
                        }
                    });

                    // Hilangkan alert begitu user pilih radio
                    radios.forEach(radio => {
                        radio.addEventListener("change", function() {
                            errorAlert.classList.add("hidden");
                        });
                    });
                });
                </script>
                  <!-- Catatan Section -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">Catatan</h2>
                    <textarea name="catatan" rows="3" class="w-full p-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#16782d]" placeholder="Tulis catatan tambahan untuk pesanan Anda (optional)"></textarea>
                </div>
                
                <!-- Payment Button -->
                <div>
                    <button class="w-full bg-red-600 text-white font-medium py-3.5 rounded-lg hover:bg-red-700 transition duration-300">
                        Selesaikan Pesanan
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection