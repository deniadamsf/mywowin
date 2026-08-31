@extends('public.layouts.app')

@section('title', 'WOWINFood')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="//unpkg.com/alpinejs" defer></script>
<meta name="description" content="WOWINFood - {{ $product->nama_produk }}. Temukan berbagai produk berkualitas dengan harga terbaik">

{{-- Google SEO Schema.org JSON-LD Structured Data untuk Rating & Produk --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ $product->nama_produk }}",
  "image": "{{ $product->images->first() ? (str_starts_with($product->images->first()->image_url, 'http') ? $product->images->first()->image_url : asset('storage/' . $product->images->first()->image_url)) : asset('images/lg-h.png') }}",
  "description": "{{ $product->rekom_guna ?? $product->nama_produk }}",
  "sku": "WOWIN-PROD-{{ $product->id_product }}",
  "brand": {
    "@type": "Brand",
    "name": "Wowin Food"
  },
  "offers": {
    "@type": "Offer",
    "priceCurrency": "IDR",
    "price": "{{ (float)($product->harga ?? 0) }}",
    "availability": "https://schema.org/InStock",
    "url": "{{ url()->current() }}"
  }
  @if(($totalReviews ?? 0) > 0)
  ,
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{{ number_format($avgRating ?? 5.0, 1) }}",
    "reviewCount": "{{ $totalReviews ?? 1 }}",
    "bestRating": "5",
    "worstRating": "1"
  },
  "review": [
    @foreach(($reviews ?? []) as $rev)
    {
      "@type": "Review",
      "author": {
        "@type": "Person",
        "name": "{{ $rev->is_anonymous ? ($rev->user ? substr($rev->user->name, 0, 1) . '***' : 'Pelanggan Wowin') : ($rev->user ? $rev->user->name : 'Pelanggan Wowin') }}"
      },
      "datePublished": "{{ $rev->created_at ? $rev->created_at->format('Y-m-d') : date('Y-m-d') }}",
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": "{{ $rev->rating }}",
        "bestRating": "5",
        "worstRating": "1"
      },
      "reviewBody": "{{ addslashes($rev->komentar ?? 'Produk sangat memuaskan dan berkualitas.') }}"
    }@if(!$loop->last),@endif
    @endforeach
  ]
  @endif
}
</script>

<style>
    /* Impor Font Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

    
    /* Terapkan Font Poppins */
    .container {
        font-family: 'Roboto', sans-serif;
    }
</style>
@endsection

@section('content')
<!-- Breadcrumb Navigation yang Lebih Elegan -->
<div class="container mx-auto px-4 py-4">
    <div class="flex items-center space-x-2 bg-white px-4 py-2 rounded-lg shadow-md w-full max-w-7xl border-l-4 border-[#16782d]">
        {{-- <a href="/" class="text-gray-600 hover:text-[#16782d] font-medium transition-colors duration-300 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Beranda
        </a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg> --}}
        <a href="{{ route('products') }}" class="text-[#16782d] font-semibold flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Produk
        </a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-[#16782d] font-semibold inline-flex items-center">
            {{ $product->nama_produk }}
        </span>
    </div>
</div>
@php
    $harga_karton = $product->harga;
    $isi_karton = $product->isi_karton ?? 1; // Default ke 1 untuk menghindari error
    $harga_pcs = ($isi_karton > 0) ? ($harga_karton / $isi_karton) : $harga_karton; 
@endphp
<div class="bg-white shadow-lg rounded-lg p-6 max-w-[1250px] mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 items-start" 
     x-data="{ 
        unit: 'karton', 
        harga_karton: {{ $harga_karton }}, 
        harga_pcs: {{ $harga_pcs }},

        // FUNGSI BARU UNTUK TAMBAH KE KERANJANG
        addToCart() {
            const quantityInput = document.getElementById('quantityInput');
            const quantity = parseInt(quantityInput.value) || 1;
            const productId = {{ $product->id_product }};
            
            // Ambil elemen modal
            const cartModal = document.getElementById('cartModal');
            const progress = document.getElementById('progress');

            // 1. Kirim data ke server (termasuk 'unit')
            fetch('{{ route('carts.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity,
                    unit: this.unit
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 2. JIKA SUKSES, tampilkan modal & mainkan suara
                    const audio = new Audio('data:audio/mp3;base64,SUQzBAAAAAABEVRYWFgAAAAtAAADY29tbWVudABCaWdTb3VuZEJhbmsuY29tIC8gTGFyZ2Vzb3VuZEJhbmsuY29tAAAA//uQZAAAAAAAAAAAAAAAAAAAAAAAWGluZwAAAA8AAAACAAACcQCAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA//////////////////////////////////////////////////////////////////8AAAAeTEFNRTMuMTAwA8MAAAAAAAAAABQgJAUHQQAB9AAAbXFzzAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA//sQZAAP8AAAaQAAAAgAAA0gAAABAAABpAAAACAAADSAAAAETEFNRTMuMTAwVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVU=');
                    audio.play();
                    
                    cartModal.style.display = 'flex';
                    progress.style.width = '0%';
                    
                    void progress.offsetWidth; // Trigger reflow
                    progress.style.animation = 'none';
                    setTimeout(() => {
                        progress.style.animation = 'progressAnimation 3s linear forwards';
                    }, 10);
                    
                    // 3. Redirect setelah 3 detik
                    setTimeout(function () {
                        window.location.href = '{{ route('carts.index') }}';
                    }, 3000);

                } else {
                    // 4. JIKA GAGAL, tampilkan alert
                    alert(data.message || 'Gagal menambahkan produk ke keranjang.');
                }
            })
            .catch(error => {
                // 5. JIKA ERROR JARINGAN
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        }
     }">

    <div x-data="{ activeImage: '{{ $product->images->first()->image_url ?? '' }}' }">
        <!-- Gambar Utama -->
        @if ($product->images->isNotEmpty()) 
            <img :src="'{{ asset('storage/') }}' + '/' + activeImage" 
                 alt="{{ $product->nama_produk }}" 
                 class="w-full w-[400px] h-[400px] object-contain transition-transform duration-500 group-hover:scale-105">
    
            <!-- Thumbnail Images -->
            <div class="flex flex-wrap justify-left gap-2 mt-4">
                @foreach ($product->images as $image)
                    <div 
                        @click="activeImage = '{{ $image->image_url }}'"
                        :class="{ 'shadow-xl shadow-gray-500/60': activeImage === '{{ $image->image_url }}' }"
                        class="relative w-16 h-16 border border-gray-300 rounded-lg overflow-hidden cursor-pointer transition-transform hover:scale-105">
                        
                        <img src="{{ asset('storage/' . $image->image_url) }}" 
                             alt="{{ $product->nama_produk }}" 
                             class="w-full h-full object-cover">
    
                        <!-- Overlay saat thumbnail aktif -->
                        <div x-show="activeImage === '{{ $image->image_url }}'" 
                             class="absolute inset-0 bg-black bg-opacity-40"></div>
                    </div>
                @endforeach
            </div>
    
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif
    </div>
    @php
        $harga_karton = $product->harga;
        $isi_karton = $product->isi_karton ?? 1; // Default ke 1 untuk menghindari error
        $harga_pcs = ($isi_karton > 0) ? ($harga_karton / $isi_karton) : $harga_karton; 
    @endphp
    <!-- Detail Produk -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $product->nama_produk }}</h1>
        <div class="flex items-center gap-2 mt-2">
            <div class="flex items-center text-amber-400">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= round($avgRating ?? 5) ? 'fill-amber-400 text-amber-400' : 'text-gray-200' }}" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
            </div>
            <span class="text-sm font-bold text-gray-800">{{ number_format($avgRating ?? 5.0, 1) }}</span>
            <span class="text-xs text-gray-400">•</span>
            <a href="#reviews-section" class="text-xs text-gray-500 hover:text-[#16782d] underline font-medium">({{ $totalReviews ?? 0 }} Ulasan)</a>
            <span class="text-xs text-gray-400">•</span>
            <span class="text-xs text-[#16782d] bg-green-50 px-2 py-0.5 rounded font-semibold">Terverifikasi</span>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4 my-3 shadow-sm space-y-3">
            <!-- Informasi Brand -->
            <div class="flex items-center space-x-2 text-gray-500 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                </svg>                  
                <span class="text-gray-500"><b>Brand :</b> PT. Wowin Purnomo Putera </span>
                <span class="text-blue-500 font-semibold">{{ $product->brand }}</span>
            </div>
        
            <!-- Garis Putus-putus -->
            <div class="border-t border-dashed border-gray-300"></div>
        
            <!-- Informasi Stok -->
            <div class="flex items-start space-x-2">
                <img width="24" height="48" src="https://img.icons8.com/color/48/shop.png" alt="shop"/>
                <div>
                    <p class=" text-gray-700 text-sm font-semibold">Stok dari Toko</p>
                    <p class="text-gray-500 text-sm">Pengiriman dilakukan pada hari yang sama.</p>
                </div>
            </div>
       {{-- ... (setelah div info brand & stok) ... --}}
    </div>
    <div class="my-3">

    <div class="flex border border-gray-300 rounded-lg p-1 max-w-min mb-3 shadow-sm">
        <button 
            @click="unit = 'karton'"
            :class="{ 'bg-[#16782d] text-white shadow-md': unit === 'karton', 'text-gray-600 hover:bg-gray-100': unit !== 'karton' }"
            class="px-4 py-1.5 rounded-md font-semibold text-sm transition-all duration-200 focus:outline-none">
            Karton
        </button>
        <button 
            @click="unit = 'pcs'"
            :class="{ 'bg-[#16782d] text-white shadow-md': unit === 'pcs', 'text-gray-600 hover:bg-gray-100': unit !== 'pcs' }"
            class="px-4 py-1.5 rounded-md font-semibold text-sm transition-all duration-200 focus:outline-none">
            Pcs
        </button>
    </div>

    <div>
        <div x-show="unit === 'karton'" class="flex items-end space-x-2" x-transition>
            <p class="text-red-600 text-2xl font-bold">
                Rp <span x-text="Math.round(harga_karton).toLocaleString('id-ID')"></span>
            </p>
            <span class="text-sm text-gray-700 font-normal pb-1">/ karton</span>
        </div>

        <div x-show="unit === 'pcs'" class="flex items-end space-x-2" x-transition>
            <p class="text-red-600 text-2xl font-bold">
                Rp <span x-text="Math.round(harga_pcs).toLocaleString('id-ID')"></span>
            </p>
            <span class="text-sm text-gray-700 font-normal pb-1">/ pcs</span>
        </div>
    </div>
</div>
<br>
<br>
    {{-- ... (kode deskripsi produk) ... --}}
        <h2 class="text-lg mb-2">
            <span class="font-bold">Deskripsi :</span><br>
            <div class="rekom-guna" id="rekomGuna">{!! $product->rekom_guna !!}</div>
            <button id="readMoreBtn" class="text-blue-600 underline mt-2 hidden">Baca Selengkapnya</button>
        </h2>
        
        <style>
            .rekom-guna ul {
                list-style-type: disc;
                margin-left: 1.25rem;
            }
        </style>
        
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const fullContent = document.getElementById("rekomGuna");
                const readMoreBtn = document.getElementById("readMoreBtn");
        
                // Ambil hanya teks tanpa tag HTML
                const textContent = fullContent.innerText || fullContent.textContent;
                const words = textContent.trim().split(/\s+/);
        
                // Kalau lebih dari 150 kata, potong dan simpan sisanya
                if (words.length > 150) {
                    const shortText = words.slice(0, 150).join(" ") + "...";
                    const originalHTML = fullContent.innerHTML;
        
                    fullContent.dataset.full = originalHTML;
                    fullContent.dataset.short = shortText;
        
                    // Render short version dulu
                    fullContent.innerText = shortText;
                    readMoreBtn.classList.remove("hidden");
        
                    // Toggle handler
                    readMoreBtn.addEventListener("click", function () {
                        if (readMoreBtn.innerText === "Baca Selengkapnya") {
                            fullContent.innerHTML = fullContent.dataset.full;
                            readMoreBtn.innerText = "Tampilkan Lebih Sedikit";
                        } else {
                            fullContent.innerText = fullContent.dataset.short;
                            readMoreBtn.innerText = "Baca Selengkapnya";
                        }
                    });
                }
            });
        </script>
        
        
          
    </div>
    

 <!-- Tombol Keranjang dan Pengiriman -->
<div class="p-4 border border-gray-200 rounded-lg max-w-[400px] mx-auto">

    <!-- Tombol Pembelian -->
    <div class="flex items-center justify-between mb-4" x-data="{ qty: 1 }">
    <div class="flex items-center space-x-4">
        <p class="text-gray-700 text-xs font-semibold uppercase">Jumlah Pembelian :</p>
        <div class="flex items-center border border-gray-300 rounded-full shadow-md bg-gray-50 overflow-hidden">
            
            <button type="button" 
                    @click="qty > 1 ? qty-- : 1" 
                    class="bg-white text-gray-800 w-8 h-8 flex items-center justify-center hover:bg-gray-100 transition active:scale-90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
            </button>

            <input id="quantityInput" 
                   type="number" 
                   x-model="qty" 
                   readonly 
                   class="w-12 text-center py-1 bg-transparent text-gray-800 font-bold focus:outline-none text-sm pointer-events-none" />

            <button type="button" 
                    @click="qty++" 
                    class="bg-white text-gray-800 w-8 h-8 flex items-center justify-center hover:bg-gray-100 transition active:scale-90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
            </button>
        </div>
    </div>
</div>

    <!-- Tombol Tambah ke Keranjang -->
    <button @click="addToCart()" class="bg-red-600 font-semibold text-white px-4 py-2 rounded-md text-base w-full max-w-[400px] shadow-md hover:bg-red-700 transition">
        + Keranjang
    </button>


 <hr class="border-t border-gray-300 my-4">

 <!-- Detail Produk -->
 <div class="mb-4 space-y-2 text-sm text-gray-700">
    <h2 class="text-sm font-bold text-black">Detail Produk :</h2>
    <!-- Isi -->
    <div class="flex items-start gap-2">
        <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 10H7" />
            <path d="M7 6h14" />
            <path d="M7 14h14" />
            <path d="M7 18h14" />
            <path d="M3 6h.01" />
            <path d="M3 10h.01" />
            <path d="M3 14h.01" />
            <path d="M3 18h.01" />
        </svg>
        <p><span class="font-semibold">Isi:</span> {{ $product->isi_ml }} ml</p>
    </div>

    <!-- Isi per Karton -->
    <div class="flex items-start gap-2">
        <svg class="w-5 h-5 text-yellow-500 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 7l8.5 5L20 7" />
            <path d="M3 17l8.5 5L20 17" />
            <path d="M3 7v10l8.5 5V12L3 7z" />
            <path d="M20 7v10l-8.5 5V12L20 7z" />
        </svg>
        <p><span class="font-semibold">Isi per Karton:</span> {{ $product->isi_karton }}</p>
    </div>

    <!-- BPOM -->
    <div class="flex items-start gap-2">
        <svg class="w-5 h-5 text-indigo-500 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2l9 4.5v11L12 22l-9-4.5v-11L12 2z" />
            <path d="M12 7v5l3 3" />
        </svg>
        <p><span class="font-semibold">No. BPOM:</span> {{ $product->no_bpom }}</p>
    </div>

    <!-- Kategori -->
<div class="flex items-start gap-2">
    <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
         viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 6L9 17l-5-5" />
    </svg>
    <p><span class="font-semibold">Kategori:</span> {{ $product->category->name ?? '-' }}</p>
</div>

</div>
   
    <!-- AJAX untuk Tambah ke Keranjang -->
  {{-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        const productId = {{ $product->id_product }};
                .catch(error => console.error("Error:", error));
        });
    // });
</script> --}}
        
    <hr class="border-t border-gray-300 my-4">
<!-- Info Pengiriman -->
<div class="p-4 bg-white rounded-lg shadow-sm">
    <h3 class="font-bold text-gray-800 mb-2">Pengiriman</h3>
    <p class="text-gray-600">Dikirim oleh <strong>SAPA Instant Delivery</strong></p>
    <p class="text-gray-600">Biaya Pengiriman <strong>Gratis</strong></p>
    <br>
   <!-- Info Marketplace -->
<div class="mt-3">
    <h4 class="font-semibold text-gray-800 mb-2">Tersedia di marketplace lainnya:</h4>
    <ul class="flex flex-wrap gap-3 items-center">
        <li class="bg-orange-100 p-2 rounded-full">
            <a href="https://shopee.co.id/wowinfood" target="_blank">
                <img src="{{ asset('images/shopee.png') }}" alt="Shopee" class="w-5 h-5 rounded-sm">
            </a>
        </li>
        <li class="bg-green-100 p-2 rounded-full">
            <a href="https://bnc.lt/a/key_live_abhHgIh1DQiuPxdBNg9EXepdDugwwkHr?channel=salinlink&feature=share&campaign=Shop-0-5098852-300425&sdk=web2.63.0&source=web-sdk&data=eyIkb2dfaW1hZ2VfdXJsIjoiaHR0cHM6Ly9pbWFnZXMudG9rb3BlZGlhLm5ldC9pbWcvY2FjaGUvMjE1LXNxdWFyZS9zaG9wcy0xLzIwMjAvMy8yMS81MDk4ODUyLzUwOTg4NTJfMDUzMzU1ZTEtYjc0NC00ZTA3LWFmNzQtOTA5ZjIyMzRlMjdjLmpwZyIsIiRkZXNrdG9wX3VybCI6Imh0dHBzOi8vd3d3LnRva29wZWRpYS5jb20vd293aW5mb29kIiwiJGFuZHJvaWRfZGVlcGxpbmtfcGF0aCI6InNob3AvNTA5ODg1MiIsIiRpb3NfZGVlcGxpbmtfcGF0aCI6InNob3AvNTA5ODg1MiIsIiRhbmRyb2lkX3VybCI6Imh0dHBzOi8vd3d3LnRva29wZWRpYS5jb20vd293aW5mb29kIiwiJGlvc191cmwiOiJodHRwczovL3d3dy50b2tvcGVkaWEuY29tL3dvd2luZm9vZCIsIiRjYW5vbmljYWxfdXJsIjoiaHR0cHM6Ly93d3cudG9rb3BlZGlhLmNvbS93b3dpbmZvb2QiLCIkb2dfdGl0bGUiOiJUb2tvIHdvd2luZm9vZCBPbmxpbmUgLSBQcm9kdWsgTGVuZ2thcCAmIEhhcmdhIFRlcmJhaWsgfCBUb2tvcGVkaWEiLCIkb2dfZGVzY3JpcHRpb24iOiJCZWxpIHByb2R1ayB3b3dpbmZvb2Qgb25saW5lLCBwcm9kdWsgdGVybGVuZ2thcCBkYW4gaGFyZ2EgdGVyYmFpay4gRGFwYXRrYW4gYmVyYmFnYWkgcHJvbW8gbWVuYXJpay4gQmVsYW5qYSBhbWFuIGRhbiBueWFtYW4gaGFueWEgZGkgVG9rb3BlZGlhLiIsIiRvZ192aWRlbyI6bnVsbCwiJG9nX3R5cGUiOiJ3ZWJzaXRlIn0%3D" target="_blank">
                <img src="{{ asset('images/tokped.png') }}" alt="Tokopedia" class="w-5 h-5 rounded-sm">
            </a>
        </li>
        <li class="bg-blue-100 p-2 rounded-full">
            <a href="https://www.tiktok.com/@wowinfood?is_from_webapp=1&sender_device=pc" target="_blank">
                <img src="{{ asset('images/ttshop.png') }}" alt="TiktokShop" class="w-5 h-5 rounded-sm">
            </a>
        </li>
    </ul>
</div>

</div>   
</div>
</div>

<!-- ========================================================================= -->
<!-- SEKSI ULASAN & RATING PEMBELI (GOOGLE-READY & INTERAKTIF) -->
<!-- ========================================================================= -->
<div id="reviews-section" class="bg-white shadow-lg rounded-2xl p-6 md:p-8 max-w-[1250px] mx-auto mt-8 border border-gray-100">
    <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-gray-100 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <span class="text-amber-500">★</span> Ulasan & Penilaian Pelanggan
            </h2>
            <p class="text-sm text-gray-500 mt-1">Ulasan asli dari pembeli terverifikasi produk Wowin</p>
        </div>
        <div class="flex items-center gap-2 bg-green-50 px-4 py-2 rounded-xl border border-green-200">
            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-xs font-semibold text-green-800">100% Ulasan Terverifikasi Pembeli</span>
        </div>
    </div>

    <!-- Rating Summary Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 my-8 items-center bg-gray-50/70 p-6 rounded-2xl border border-gray-100">
        <!-- Skor Utama -->
        <div class="md:col-span-4 text-center md:border-r md:border-gray-200 md:pr-6">
            <div class="text-5xl font-black text-gray-900 tracking-tight">
                {{ number_format($avgRating ?? 5.0, 1) }}
                <span class="text-xl text-gray-400 font-normal">/ 5.0</span>
            </div>
            <div class="flex justify-center items-center gap-1 my-2">
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="w-6 h-6 {{ $i <= round($avgRating ?? 5) ? 'text-amber-400 fill-amber-400' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
            </div>
            <p class="text-xs font-semibold text-gray-500">Berdasarkan {{ $totalReviews ?? 0 }} ulasan pelanggan</p>
        </div>

        <!-- Distribusi Bintang -->
        <div class="md:col-span-8 space-y-2">
            @php
                $dist = $ratingDistribution ?? [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                $totalCount = max($totalReviews ?? 1, 1);
            @endphp
            @foreach([5, 4, 3, 2, 1] as $star)
                @php
                    $count = $dist[$star] ?? 0;
                    $percent = ($totalReviews ?? 0) > 0 ? round(($count / $totalReviews) * 100) : ($star == 5 ? 100 : 0);
                @endphp
                <div class="flex items-center gap-3 text-xs">
                    <span class="w-12 font-medium text-gray-600 flex items-center gap-1">{{ $star }} <span class="text-amber-500">★</span></span>
                    <div class="flex-1 bg-gray-200 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-amber-400 h-2.5 rounded-full" style="width: {{ $percent }}%"></div>
                    </div>
                    <span class="w-12 text-right text-gray-500 font-medium">{{ $count }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Daftar Ulasan Pembeli -->
    <div class="space-y-6 mt-6">
        @forelse(($reviews ?? []) as $rev)
            <div class="p-5 rounded-2xl bg-white border border-gray-100 hover:border-gray-200 transition shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#16782d]/10 text-[#16782d] font-bold flex items-center justify-center text-sm uppercase">
                            {{ $rev->is_anonymous ? 'U' : ($rev->user ? substr($rev->user->name, 0, 1) : 'U') }}
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                {{ $rev->is_anonymous ? (substr($rev->user->name ?? 'User', 0, 1) . '***') : ($rev->user->name ?? 'Pengguna Wowin') }}
                                <span class="bg-green-100 text-green-800 text-[10px] px-2 py-0.5 rounded-full font-semibold">Pembeli Terverifikasi</span>
                            </div>
                            <div class="text-xs text-gray-400 flex items-center gap-2 mt-0.5">
                                <span>Cabang {{ $rev->kantor_cabang ?? 'Pusat' }}</span>
                                <span>•</span>
                                <span>{{ $rev->created_at ? $rev->created_at->format('d M Y, H:i') : '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bintang Ulasan -->
                    <div class="flex items-center gap-0.5">
                        @for($s = 1; $s <= 5; $s++)
                            <svg class="w-4 h-4 {{ $s <= $rev->rating ? 'text-amber-400 fill-amber-400' : 'text-gray-200' }}" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                </div>

                <!-- Tag Kualitas Cepat -->
                @if(!empty($rev->tags) && is_array($rev->tags))
                    <div class="flex flex-wrap gap-1.5 my-3">
                        @foreach($rev->tags as $tag)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                ✓ {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <!-- Teks Ulasan -->
                @if($rev->komentar)
                    <p class="text-sm text-gray-700 mt-2 leading-relaxed">{{ $rev->komentar }}</p>
                @endif

                <!-- Foto Ulasan -->
                @if(!empty($rev->foto) && is_array($rev->foto))
                    <div class="flex flex-wrap gap-2 mt-3">
                        @foreach($rev->foto as $img)
                            <a href="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}" target="_blank" class="block group overflow-hidden rounded-lg border border-gray-200">
                                <img src="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}" 
                                     class="w-16 h-16 object-cover group-hover:scale-105 transition duration-200" 
                                     alt="Foto Ulasan Pembeli">
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- Balasan Resmi Admin / Cabang -->
                @if($rev->balasan_admin)
                    <div class="mt-4 p-3.5 bg-gray-50 rounded-xl border-l-4 border-[#16782d] text-xs">
                        <div class="font-bold text-gray-800 flex items-center gap-1.5 mb-1">
                            <span class="text-[#16782d]">💬 Respon Resmi Wowin ({{ $rev->kantor_cabang ?? 'Pusat' }}):</span>
                        </div>
                        <p class="text-gray-600 leading-relaxed">{{ $rev->balasan_admin }}</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-12 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                <div class="w-14 h-14 mx-auto mb-3 text-gray-300">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-700">Belum Ada Ulasan untuk Produk Ini</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Jadilah yang pertama memberikan penilaian setelah berbelanja melalui aplikasi My Wowin.</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if(isset($reviews) && method_exists($reviews, 'links'))
            <div class="pt-4">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>

<br>
{{-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        const addToCartBtn = document.getElementById('addToCart');
        const quantityInput = document.getElementById('quantityInput');

        addToCartBtn.addEventListener('click', function () {
            const quantity = quantityInput.value;

            // Simulasi penambahan ke keranjang (bisa diganti dengan fetch/ajax nanti)
            alert(`Produk berhasil ditambahkan ke keranjang sebanyak ${quantity} item.`);

            // Kalau mau kirim ke route Laravel pakai fetch/ajax:
            /*
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: {{ $product->id }},
                    quantity: quantity
                })
            }).then(response => response.json())
              .then(data => {
                  alert('Produk berhasil ditambahkan ke keranjang!');
              }).catch(error => {
                  alert('Terjadi kesalahan saat menambahkan ke keranjang.');
              });
            */
        });
    });
</script> --}}
<!-- Modal HTML - Futuristic Agricultural Cart Notification -->
<div id="cartModal" class="cart-modal">
    <div class="modal-content">
        <div class="success-icon">
            <svg viewBox="0 0 24 24" class="checkmark">
                <circle class="checkmark-circle" cx="12" cy="12" r="11" />
                <path class="checkmark-check" d="M7 13l3 3 7-7" />
            </svg>
        </div>
        <h2 class="modal-title">Berhasil!</h2>
        <p class="modal-message">Produk ditambahkan ke keranjang</p>
        <div class="leaf-decoration left"></div>
        <div class="leaf-decoration right"></div>
        <div class="progress-bar">
            <div id="progress" class="progress"></div>
        </div>
    </div>
</div>

<style>
    .cart-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(3px);
    }

    .modal-content {
        background: #ffffff;
        color: #2e5e36;
        padding: 40px;
        border-radius: 20px;
        text-align: center;
        max-width: 350px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15), 
                    0 0 0 1px rgba(46, 94, 54, 0.08),
                    0 0 20px rgba(78, 174, 91, 0.1);
        border: 1px solid rgba(78, 174, 91, 0.2);
        position: relative;
        transform: translateY(20px);
        opacity: 0;
        animation: slideIn 0.5s forwards;
        overflow: hidden;
    }

    @keyframes slideIn {
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .success-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 25px;
        position: relative;
    }

    .checkmark {
        width: 100%;
        height: 100%;
    }

    .checkmark-circle {
        stroke-width: 2;
        stroke: #45a049;
        fill: none;
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        animation: stroke 1s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }

    .checkmark-check {
        stroke-width: 2;
        stroke: #45a049;
        fill: none;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.8s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }

    @keyframes stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }

    .modal-title {
        font-size: 26px;
        margin: 0 0 10px;
        background: linear-gradient(90deg, #2e5e36, #45a049);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-weight: 600;
    }

    .modal-message {
        font-size: 16px;
        margin-bottom: 25px;
        color: #4a6b50;
    }

    .leaf-decoration {
        position: absolute;
        width: 60px;
        height: 60px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%234CAF50' opacity='0.1'%3E%3Cpath d='M17,8C8,10,5.9,16.17,3.82,21.34L5.71,22l1-2.3C9,14,14,8.47,22,3c0,0-1,1-1,3C21,9,21,12,17,8z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-size: contain;
        opacity: 0.15;
        z-index: -1;
    }

    .leaf-decoration.left {
        top: 10px;
        left: 10px;
        transform: rotate(-30deg);
    }

    .leaf-decoration.right {
        bottom: 10px;
        right: 10px;
        transform: rotate(120deg);
    }

    .progress-bar {
        width: 100%;
        height: 4px;
        background-color: rgba(46, 94, 54, 0.1);
        border-radius: 2px;
        overflow: hidden;
    }

    .progress {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #2e5e36, #7cb342);
        animation: progressAnimation 3s linear forwards;
    }

    @keyframes progressAnimation {
        to {
            width: 100%;
        }
    }

    /* Shine effect around the modal */
    .modal-content::before {
        content: '';
        position: absolute;
        top: -10px;
        left: -10px;
        right: -10px;
        bottom: -10px;
        background: linear-gradient(45deg, transparent, rgba(78, 174, 91, 0.1), transparent);
        z-index: -1;
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 480px) {
        .modal-content {
            max-width: 90%;
            padding: 30px;
        }
        
        .success-icon {
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
        }
        
        .modal-title {
            font-size: 22px;
        }
    }
</style>

{{-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        const addToCartBtn = document.getElementById('addToCart');
        const quantityInput = document.getElementById('quantityInput');
        const cartModal = document.getElementById('cartModal');
        const progress = document.getElementById('progress');

        addToCartBtn.addEventListener('click', function () {
            const quantity = quantityInput.value;
            
            // Add subtle "nature" sound effect (optional)
            const audio = new Audio('data:audio/mp3;base64,SUQzBAAAAAABEVRYWFgAAAAtAAADY29tbWVudABCaWdTb3VuZEJhbmsuY29tIC8gTGFyZ2Vzb3VuZEJhbmsuY29tAAAA//uQZAAAAAAAAAAAAAAAAAAAAAAAWGluZwAAAA8AAAACAAACcQCAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA//////////////////////////////////////////////////////////////////8AAAAeTEFNRTMuMTAwA8MAAAAAAAAAABQgJAUHQQAB9AAAbXFzzAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA//sQZAAP8AAAaQAAAAgAAA0gAAABAAABpAAAACAAADSAAAAETEFNRTMuMTAwVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVU=');
            audio.play();
            
            // Tampilkan modal dengan animasi
            cartModal.style.display = 'flex';
            progress.style.width = '0%';
            
            // Reset progress bar animation
            void progress.offsetWidth; // Trigger reflow untuk reset animasi
            progress.style.animation = 'none';
            setTimeout(() => {
                progress.style.animation = 'progressAnimation 3s linear forwards';
            }, 10);
            
            // Tunggu 3 detik lalu redirect ke halaman keranjang
            setTimeout(function () {
                window.location.href = "{{ route('carts.index') }}";
            }, 3000);
        });
    });
</script> --}}

@endsection
