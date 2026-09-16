@extends('public.layouts.app')

@section('title', 'WOWINFood')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<meta name="description" content="WOWINFood - Temukan berbagai produk berkualitas dengan harga terbaik">
@endsection

@section('content')
@php
    // Mengambil semua bundling, urutkan dari yang terbaru diinput
    $bundlings = \App\Models\Bundling::latest()->get();

    // Mengambil 6 produk terbaru
    $products = \App\Models\Product::latest()->take(6)->get(); 
@endphp


<section class="bg-gradient-to-r from-green-50 to-green-100 py-6 sm:py-8 lg:py-12 px-3 sm:px-4 rounded-xl sm:rounded-2xl lg:rounded-3xl w-full max-w-7xl mx-auto"
    x-data="heroSlider()"
    x-init="start()">

    <!-- Wrapper flex yang berubah sesuai device -->
    <div class="max-w-6xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-4 sm:gap-6 lg:gap-8">

        <!-- Gambar: urutan pertama di mobile, urutan kedua di desktop -->
        <div class="w-full lg:w-1/2 order-1 lg:order-2 mb-4 lg:mb-0">
            <img src="{{ asset('storage/' . $hero->gambar_hero) }}" alt="Hero Image" 
                class="w-full h-48 sm:h-56 lg:h-auto object-cover rounded-lg shadow-sm">
        </div>

        <!-- Konten Slider -->
        <div class="w-full lg:w-1/2 order-2 lg:order-1 relative self-start" style="min-height: 200px; lg:min-height: 250px;">

            <template x-for="(slideContent, index) in slides" :key="index">
                <div
                    x-show="animatingSlide === index"
                    x-transition.opacity.duration.500ms
                    class="absolute top-0 left-0 w-full"
                >
                    <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold leading-tight mb-3 sm:mb-4"
                        x-html="slideContent.title"></h1>
                    <p class="text-gray-600 mb-4 sm:mb-6 lg:mb-8 text-sm sm:text-base lg:text-lg leading-relaxed" 
                        x-html="slideContent.text"></p>
                    <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 lg:gap-4 mb-4 sm:mb-6" 
                        x-html="slideContent.buttons"></div>

                    <!-- Navigasi Line -->
                    <div class="flex gap-1.5 sm:gap-2 mt-2">
                        <template x-for="(slide, i) in slides" :key="i">
                            <div @click="goToSlide(i)"
                                class="h-0.5 sm:h-1 cursor-pointer transition-all"
                                :class="currentSlide === i ? 'bg-[#16782d] w-6 sm:w-8' : 'bg-gray-300 w-4 sm:w-6'">
                            </div>
                        </template>
                    </div>
                </div>
            </template>

        </div>
    </div>
</section>

<script src="//unpkg.com/alpinejs" defer></script>
<script>
function heroSlider() {
    return {
        slides: [
            {
                title: 'Temukan Kualitas <span class="text-[#16782d]">Terbaik</span> untuk Kebutuhan Anda',
                text: 'Produk berkualitas dengan harga terjangkau. Nikmati pengalaman belanja online yang aman dan nyaman di WOWINFood.',
                buttons: `
                    @auth
                        <a href="{{ route('products') }}" class="bg-[#16782d] hover:bg-green-700 text-white font-medium py-2 sm:py-2.5 lg:py-3 px-4 sm:px-5 lg:px-6 rounded-lg transition-all shadow-md hover:shadow-lg text-sm sm:text-base w-full sm:w-auto text-center">
                            Belanja Sekarang
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-[#16782d] hover:bg-green-700 text-white font-medium py-2 sm:py-2.5 lg:py-3 px-4 sm:px-5 lg:px-6 rounded-lg transition-all shadow-md hover:shadow-lg text-sm sm:text-base w-full sm:w-auto text-center">
                            Belanja Sekarang
                        </a>
                    @endauth
                    <a href="#featured-products" class="border-2 border-[#16782d] text-[#16782d] hover:bg-green-50 font-medium py-2 sm:py-2.5 lg:py-3 px-4 sm:px-5 lg:px-6 rounded-lg transition-all text-sm sm:text-base w-full sm:w-auto text-center">
                        Produk Rekomendasi
                    </a>
                `
            },
            {
                title: 'Buat Usaha Anda Makin Dikenal dengan <span class="text-[#16782d]">Banner Gratis</span> dari MyWowin!',
                text: 'Daftarkan sekarang juga dan nikmati penawaran spesial hanya untuk member.',
                buttons: `
                    @auth
                        @php
                            $user = auth()->user();
                            $membership = \DB::table('memberships')->where('user_id', $user->id)->first();
                            $waNumber = '62812106600'; // Nomor resmi CS Wowin berakhiran 6600
                            $message = "Hai admin aku mau dong dibuatin banner untuk usaha ku :\n\n" .
                                    "Nama: " . $user->nama_lengkap . "\n" .
                                    "Nama Toko: " . ($membership->nama_toko ?? '-') . "\n" .
                                    "Alamat: " . ($membership->alamat ?? '-') . "\n" .
                                    "No Telp: " . ($membership->no_hp ?? '-');
                            $waLink = "https://wa.me/{$waNumber}?text=" . urlencode($message);
                        @endphp

                        <a href="{{ $waLink }}" target="_blank"
                        class="bg-[#16782d] hover:bg-green-700 text-white font-medium py-2 sm:py-2.5 lg:py-3 px-4 sm:px-5 lg:px-6 rounded-lg transition-all shadow-md hover:shadow-lg text-sm sm:text-base w-full sm:w-auto text-center">
                            Klik di sini
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                        class="bg-[#16782d] hover:bg-green-700 text-white font-medium py-2 sm:py-2.5 lg:py-3 px-4 sm:px-5 lg:px-6 rounded-lg transition-all shadow-md hover:shadow-lg text-sm sm:text-base w-full sm:w-auto text-center">
                            Klik di sini
                        </a>
                    @endauth
                `
            }
        ],
        currentSlide: 0,
        animatingSlide: 0,
        start() {
            setInterval(() => {
                this.goToSlide((this.currentSlide + 1) % this.slides.length);
            }, 5000);
        },
        goToSlide(index) {
            this.animatingSlide = this.currentSlide;
            setTimeout(() => {
                this.currentSlide = index;
                this.animatingSlide = index;
            }, 500);
        }
    }
}
</script>





@if($bundlings->isNotEmpty())
@php
    // Jika lebih dari 1 bundling, kita duplikasi item pertama di ujung agar pada desktop (2 kolom) tidak pernah ada slot kosong!
    $sliderItems = $bundlings->count() > 1 ? $bundlings->concat([$bundlings->first()]) : $bundlings;
    $totalSliderCards = $sliderItems->count();
    $totalSlides = $bundlings->count();
@endphp
<section class="mt-6 sm:mt-8 relative w-full max-w-7xl mx-auto px-3 sm:px-4"
    x-data="{
        activeSlide: 0,
        totalSlides: {{ $totalSlides }},
        interval: null,
        touchStartX: 0,
        touchEndX: 0,
        next() {
            this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
        },
        prev() {
            this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
        },
        goTo(index) {
            this.activeSlide = index;
        },
        startAutoSlide() {
            if (this.totalSlides > 1) {
                this.interval = setInterval(() => this.next(), 5000);
            }
        },
        stopAutoSlide() {
            if (this.interval) {
                clearInterval(this.interval);
            }
        },
        handleTouchStart(e) {
            this.touchStartX = e.changedTouches[0].screenX;
        },
        handleTouchEnd(e) {
            this.touchEndX = e.changedTouches[0].screenX;
            if (this.touchStartX - this.touchEndX > 45) {
                this.next();
            } else if (this.touchEndX - this.touchStartX > 45) {
                this.prev();
            }
        }
    }"
    x-init="startAutoSlide()"
    @mouseenter="stopAutoSlide()"
    @mouseleave="startAutoSlide()"
    @touchstart.passive="handleTouchStart($event)"
    @touchend.passive="handleTouchEnd($event)"
>
    <!-- Carousel Box -->
    <div class="relative overflow-hidden group">
        
        <!-- Track -->
        <div class="flex transition-transform duration-500 ease-in-out -mx-2 sm:-mx-2.5"
             :style="'transform: translateX(-' + (activeSlide * (100 / {{ $totalSliderCards }})) + '%)'">
            @foreach ($sliderItems as $bundling)
                @php
                    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/))([^\&\?\/]+)/', $bundling->youtube_link ?? '', $matches);
                    $videoId = $matches[1] ?? null;
                @endphp
                <div class="w-full md:w-1/2 shrink-0 px-2 sm:px-2.5">
                    <a href="{{ route('promo.detail', $bundling->id_bundling) }}" 
                       class="block relative group/card overflow-hidden rounded-xl sm:rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 bg-white">
                        @if($videoId)
                            <div class="w-full h-56 sm:h-64 relative bg-black">
                                <iframe 
                                    src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1&mute=1&loop=1&playlist={{ $videoId }}" 
                                    class="w-full h-full object-cover"
                                    frameborder="0"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @else
                            <img src="{{ asset('storage/' . $bundling->barang_bundling) }}"
                                 alt="{{ $bundling->nama_bundling }}"
                                 class="w-full h-56 sm:h-64 object-cover transition-transform duration-500 group-hover/card:scale-105"
                                 loading="lazy">
                        @endif

                        <!-- Bottom Gradient & CTA Button -->
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent pointer-events-none">
                            <div class="flex justify-between items-end px-3.5 sm:px-5 py-3 sm:py-3.5">
                                @if(!empty($bundling->snk))
                                    <p class="text-white text-[10px] sm:text-xs bg-black/40 backdrop-blur-sm px-2.5 py-1 rounded-full">
                                        *S&amp;K Berlaku
                                    </p>
                                @else
                                    <div></div>
                                @endif

                                <div class="bg-red-600 group-hover/card:bg-red-700 text-white font-bold px-4 sm:px-5 py-1.5 sm:py-2 rounded-xl shadow-lg text-xs sm:text-sm tracking-wide transition-all transform group-hover/card:scale-105 pointer-events-auto">
                                    KLIK DI SINI
                                </div>
                            </div>
                        </div>

                        @if(!empty($bundling->snk) && stripos($bundling->snk, 'ongkir') !== false)
                        <div class="absolute top-2.5 right-2.5 sm:top-3 sm:right-3">
                            <div class="bg-red-600 text-white text-[10px] sm:text-xs px-2.5 py-1 rounded-lg font-bold text-center leading-tight shadow-md">
                                BEBAS ONGKIR<br>SEPUASNYA
                            </div>
                        </div>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>

        @if($bundlings->count() > 1)
        <!-- Navigasi Panah Kiri -->
        <button @click="prev()"
                class="absolute left-1 sm:left-2 top-1/2 -translate-y-1/2 z-10 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/90 hover:bg-white text-gray-800 shadow-lg flex items-center justify-center backdrop-blur-sm transition-all duration-200 opacity-90 sm:opacity-0 sm:group-hover:opacity-100 hover:scale-110"
                aria-label="Previous Slide">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <!-- Navigasi Panah Kanan -->
        <button @click="next()"
                class="absolute right-1 sm:right-2 top-1/2 -translate-y-1/2 z-10 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/90 hover:bg-white text-gray-800 shadow-lg flex items-center justify-center backdrop-blur-sm transition-all duration-200 opacity-90 sm:opacity-0 sm:group-hover:opacity-100 hover:scale-110"
                aria-label="Next Slide">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
            </svg>
        </button>
        @endif
    </div>

    <!-- Bullet Indicator & Link Lihat Semua -->
    <div class="flex justify-between items-center mt-3 sm:mt-4 px-1">
        <div class="flex items-center gap-1.5 sm:gap-2">
            @foreach ($bundlings as $i => $bundling)
                <button @click="goTo({{ $i }})"
                        :class="activeSlide === {{ $i }} ? 'bg-[#16782d] w-6 sm:w-7' : 'bg-gray-300 hover:bg-gray-400 w-2.5 sm:w-3'"
                        class="h-2.5 sm:h-3 rounded-full transition-all duration-300"
                        aria-label="Slide {{ $i + 1 }}">
                </button>
            @endforeach
        </div>
        
        <a href="{{ route('promo') }}"
           class="inline-flex items-center gap-1.5 text-xs sm:text-sm text-[#16782d] font-semibold hover:text-[#ffcb05] transition-colors group">
            <span>Lihat Semua Promo</span>
            <svg class="h-4 w-4 group-hover:translate-x-1 transition-transform" xmlns="http://www.w3.org/2000/svg"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>
</section>
@endif
<section x-data="{ 
    scrollLeft() { 
        this.$refs.track.scrollLeft -= 300 
    },
    scrollRight() { 
        this.$refs.track.scrollLeft += 300 
    }
}" class="relative py-6 bg-white">
    <div class="flex items-center">
        <!-- Tombol Kiri -->
        <button @click="scrollLeft()"
                class="flex-shrink-0 bg-white shadow-md rounded-full w-10 h-10 flex items-center justify-center mr-4 hover:shadow-lg transition-shadow duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <!-- Track Scrollable -->
        <div class="flex-1 overflow-hidden">
            <div class="overflow-x-auto scrollbar-hide scroll-smooth" x-ref="track" style="scrollbar-width: none; -ms-overflow-style: none;">
                <div class="flex space-x-4 w-max py-1">
                    @foreach ($kategoriList as $kategori)
                        @auth
                        <a href="{{ route('category.show', $kategori->id) }}"
                           class="flex items-center bg-white text-gray-600 rounded-full shadow-md px-4 py-2 whitespace-nowrap transition-all duration-300 hover:shadow-lg hover:bg-gray-50">
                            <img src="{{ asset('storage/' . $kategori->foto_kategori) }}" 
                                 class="w-5 h-5 mr-2"
                                 alt="{{ $kategori->name }}"> 
                            <span class="text-sm font-medium">{{ $kategori->name }}</span>
                        </a>
                        @endauth

                        @guest
                        <a href="{{ route('login') }}"
                           onclick="event.preventDefault(); window.location.href='{{ route('login') }}';"
                           class="flex items-center bg-white text-gray-600 rounded-full shadow-md px-4 py-2 whitespace-nowrap transition-all duration-300 hover:shadow-lg hover:bg-gray-50"
                           title="Silakan login terlebih dahulu">
                            <img src="{{ asset('storage/' . $kategori->foto_kategori) }}" 
                                 class="w-5 h-5 mr-2"
                                 alt="{{ $kategori->name }}"> 
                            <span class="text-sm font-medium">{{ $kategori->name }}</span>
                        </a>
                        @endguest
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tombol Kanan -->
        <button @click="scrollRight()"
                class="flex-shrink-0 bg-white shadow-md rounded-full w-10 h-10 flex items-center justify-center ml-4 hover:shadow-lg transition-shadow duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</section>



<style>
/* Tambahkan CSS ini untuk menghilangkan scrollbar di semua browser */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>


<!-- Enhanced Featured Products Section -->
<section id="featured-products" class="pt-8 pb-8 px-8 bg-white"
    x-data="{ 
    showModal: false,
    addToCart(productId) {
        fetch('{{ route('carts.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1,
                unit: 'pcs'
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.showModal = true;
                
                // Redirect setelah 2.5 detik (memberi waktu animasi bar selesai)
                setTimeout(() => {
                    window.location.href = '{{ route('carts.index') }}';
                }, 2500);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            window.location.href = '{{ route('login') }}';
        });
    }
}">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 space-y-4 md:space-y-0">
            <div>
                <h2 class="text-sm md:text-lg font-semibold text-gray-800">Produk Rekomendasi</h2>
                <p class="text-xs md:text-base text-gray-500">Pilihan terbaik untuk kebutuhan Anda, dipilih khusus dengan kualitas premium.</p>
            </div>
        </div>
        

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach ($products as $product)
            <div class="group bg-white rounded-2xl shadow hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col h-full transform hover:-translate-y-1.5">

                @if($product->is_featured)
                <div class="absolute top-3 left-3 z-10">
                    <span class="bg-gradient-to-r from-[#16782d] to-[#45a35a] text-white text-xs font-semibold px-2 py-1 rounded-md shadow">Unggulan</span>
                </div>
                @endif

                <div class="relative overflow-hidden h-48">
                    @if ($product->images->isNotEmpty())
                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <img src="{{ asset('storage/' . $product->images->first()->image_url) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="p-4 flex-grow flex flex-col">
                    <h3 class="text-base font-semibold text-gray-900 mb-1 group-hover:text-[#16782d] transition-colors">
                        {{ $product->nama_produk }}
                    </h3>
                    <p class="text-xs text-gray-500 mb-2">
                        @if($product->isi_ml)
                            {{ $product->isi_ml }} ml
                        @elseif($product->berat)
                            {{ (float)$product->berat }} gr
                        @endif
                    </p>

                    <div class="mb-3 flex-grow space-y-1.5 text-xs text-gray-600">
                        <div class="flex items-center">
                            <span class="w-4 h-4 flex items-center justify-center rounded-full bg-[#edf7ef] mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            Premium
                        </div>
                        @if($product->in_stock)
                        <div class="flex items-center">
                            <span class="w-4 h-4 flex items-center justify-center rounded-full bg-[#edf7ef] mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            Stok Tersedia
                        </div>
                        @endif
                    </div>

                    <div class="mt-auto">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-baseline gap-1">
                                <span class="text-[#16782d] font-bold text-base">
                                    Rp{{ number_format($product->harga_pcs, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-gray-500 font-normal">/ pcs</span>
                            </div>
                            @if($product->old_price)
                            <p class="text-gray-400 text-xs line-through">
                                Rp{{ number_format($product->old_price, 0, ',', '.') }}
                            </p>
                            @endif
                        </div>

                        <div class="relative">
                            <div class="flex items-center my-2">
                                <img width="20" height="20" src="https://img.icons8.com/color/48/shop.png" alt="shop" class="mr-2">
                                <span class="text-xs font-bold text-gray-600">Tersedia dari toko</span>
                            </div>
                        
                            <hr class="w-full border-t border-gray-200 my-2 mx-0">
                        </div>
                        

                        <div class="flex space-x-1">
                            {{-- Tombol Detail --}}
                                <a href="{{ Auth::check() ? route('products.detail', ['id' => $product->id_product]) : route('auth.register') }}"
                                class="flex-grow text-center bg-gradient-to-r from-[#16782d] to-[#45a35a] hover:from-[#106123] hover:to-[#3b8c4d] text-white font-medium py-2 px-2 rounded-xl transition-all duration-300 text-xs shadow-sm">
                                    Detail
                                </a>

                            <form action="{{ route('carts.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                                <input type="hidden" name="quantity" value="1">

                               {{-- Tombol Tambah ke Keranjang (Langsung Redirect) --}}
                                <button type="button" 
                                    @click.stop="addToCart({{ $product->id_product }})"
                                    class="bg-gray-50 hover:bg-gray-100 p-2 rounded-xl transition-all duration-300 border border-gray-200 hover:border-[#16782d]/30" 
                                    title="Tambah ke Keranjang">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 hover:text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M3 3h2l.4 2m0 0L6 15h12l1.6-8H5.4M16 21a1 1 0 100-2 1 1 0 000 2zM8 21a1 1 0 100-2 1 1 0 000 2z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- <div class="flex justify-center mt-12">
            <a href="{{ Auth::check() ? route('products') : route('auth.register') }}"
               class="group inline-flex items-center px-6 py-3 bg-white border border-[#16782d] text-[#16782d] hover:bg-gradient-to-r hover:from-[#16782d] hover:to-[#45a35a] hover:text-white font-medium rounded-xl transition-all duration-300 shadow-sm relative overflow-hidden">
                <span class="relative z-10">Lihat Semua Produk</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transition-transform duration-300 group-hover:translate-x-1 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
                <span class="absolute inset-0 w-0 bg-gradient-to-r from-[#16782d] to-[#45a35a] transition-all duration-300 ease-out group-hover:w-full"></span>
            </a>
        </div> --}}
    </div>
<div x-show="showModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     class="fixed inset-0 z-[999] flex items-center justify-center px-4 bg-black/60 backdrop-blur-sm"
     style="display: none;">
    
    <div class="bg-white rounded-2xl p-8 shadow-2xl max-w-sm w-full text-center relative overflow-hidden">
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        
        <h3 class="text-xl font-bold text-gray-800">Produk Ditambahkan!</h3>
        <p class="text-gray-500 text-sm mt-2">Menyiapkan keranjang belanja Anda...</p>

        <div class="w-full bg-gray-100 h-2 mt-6 rounded-full overflow-hidden">
            <div x-show="showModal" 
                 class="h-full bg-gradient-to-r from-green-400 to-[#16782d]"
                 style="animation: progressBarAnim 2s linear forwards;">
            </div>
        </div>
    </div>
</div>

<style>
    /* Definisi Animasi Progress Bar */
    @keyframes progressBarAnim {
        0% { width: 0%; }
        100% { width: 100%; }
    }

    /* Tambahan: Efek denyut pada icon agar lebih hidup */
    .animate-bounce {
        animation: bounce 1s infinite;
    }
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
</style>
</section>

{{-- <!-- Hero Section Wrapped in White Rounded Container -->
<section class="max-w-8xl mx-auto pt-10 px-4">
    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-3xl shadow-xl p-6">
        <div class="flex flex-col lg:flex-row gap-6">
           <!-- Left Panel - Agricultural Production -->
<div class="lg:w-1/2 h-[400px] relative rounded-3xl overflow-hidden shadow-lg">
    <!-- Full background image -->
    <img src="{{ asset('images/fr.jpg') }}" alt="Fresh vegetables" class="w-full h-full object-cover" />

    <!-- Overlay dark gradient (optional for readability) -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/20 to-transparent z-0"></div>

    <!-- Title -->
    <div class="absolute top-6 left-6 z-10">
        <h2 class="text-xl lg:text-2xl font-bold text-white leading-snug drop-shadow-md">
            New Opportunities<br/>For Agricultural<br/>Production
        </h2>
    </div>

    <!-- Tags -->
    <div class="absolute top-6 right-6 flex flex-col gap-2 z-10">
        <div class="bg-white/90 backdrop-blur-sm rounded-full px-4 py-2 flex items-center shadow-sm">
            <div class="bg-orange-200 rounded-full w-6 h-6 flex items-center justify-center mr-2">
                <span class="text-orange-600">🍽️</span>
            </div>
            <span class="text-gray-500 text-xs mr-1">dlv/</span>
            <span class="font-medium text-gray-800 text-xs">Fresh food</span>
        </div>
        <div class="bg-white/90 backdrop-blur-sm rounded-full px-4 py-2 flex items-center shadow-sm">
            <div class="bg-green-200 rounded-full w-6 h-6 flex items-center justify-center mr-2">
                <span class="text-green-600">🌱</span>
            </div>
            <span class="text-gray-500 text-xs mr-1">2023/</span>
            <span class="font-medium text-gray-800 text-xs">New harvest</span>
        </div>
    </div>

    <!-- Info Box (Glassmorphism) -->
<div class="absolute bottom-6 left-6 bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-4 shadow-xl w-72 z-10">
    <div class="flex items-baseline gap-2">
        <h3 class="text-3xl font-bold text-[#FFFF00]">250</h3>
        <span class="text-lg text-gray-100">liter</span>
    </div>
    <div class="w-full h-2 bg-white/20 rounded-full mt-2 overflow-hidden">
        <div class="w-4/5 h-full bg-gradient-to-r from-yellow-500 to-green-500 rounded-full"></div>
    </div>
    <p class="text-xs text-white mt-2 drop-shadow-sm">
        Produksi kecap setiap hari<br/>dengan kualitas terjaga
    </p>

    <!-- Action Button -->
    <div class="absolute right-2 top-1/2 -translate-y-1/2 flex flex-col gap-2">
        <button class="w-8 h-8 bg-green-700 hover:bg-green-800 transition rounded-full text-white flex items-center justify-center shadow">-</button>
    </div>
</div>
</div>
            <!-- Right Panel - Vertical Farming -->
            <div class="lg:w-1/2 h-[300px] bg-yellow-200 rounded-3xl p-8 relative shadow-md">
                <h2 class="text-2xl lg:text-3xl font-bold text-center text-grey-700 mb-2">
                    Innovations In<br/>Vertical Farming!
                </h2>
           
                
               <!-- Container Putih -->
                <div class="bg-white rounded-2xl shadow-xl p-6 mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Left card -->
                        <div class="rounded-2xl p-4 relative overflow-hidden shadow-md bg-cover bg-center" style="background-image: url('{{ asset('images/fr.jpg') }}')">

                            <h3 class="text-white font-bold mb-1 text-lg">Less Water<br/>And Pesticides</h3>
                            
                            <button class="bg-white/80 text-xs px-3 py-1 rounded-full absolute bottom-4 left-4 flex items-center shadow hover:bg-white">
                                Explore <span class="ml-1 bg-gray-200 rounded-full w-2 h-2"></span>
                            </button>
                            
                            <img src="{{ asset('images/wp.png') }}" alt="Yellow apples" class="absolute right-4 bottom-4 w-24 h-24 object-cover drop-shadow-md" />
                            
                            <div class="mt-12">
                                <h3 class="text-white font-bold text-sm">More Yield<br/>All Year Around</h3>
                            </div>
                        </div>

                        <!-- Right card -->
                        <div class="rounded-2xl p-4 relative overflow-hidden shadow-md bg-cover bg-center" style="background-image: url('{{ asset('images/fr.jpg') }}')">

                            <h3 class="text-white font-bold mb-1 text-lg">Minimum<br/>Space Usage</h3>
                            <img src="{{ asset('images/wp.png') }}" alt="Asparagus" class="absolute right-4 top-24 w-24 h-24 object-cover drop-shadow-md" />
                            <div class="mt-20">
                                <h3 class="text-white font-bold text-sm">Maximum<br/>Harvest in 2023</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}

{{-- <!-- CTA Section with Parallax Elements -->
<section class="pt-4  relative overflow-hidden bg-gradient-to-r from-green-50 to-green-100">
    <!-- Animated Shapes -->
    <div class="absolute inset-0 w-full h-full overflow-hidden opacity-10 z-0 pointer-events-none">
        <div class="absolute top-1/3 left-1/5 w-40 h-40 bg-white rounded-full blur-2xl animate-float-slow"></div>
        <div class="absolute bottom-1/4 right-1/3 w-32 h-32 bg-white rounded-xl blur-xl rotate-12 animate-float-medium"></div>
        <svg class="absolute top-6 right-6 w-20 h-20 text-white opacity-60 blur-xl animate-float-fast" viewBox="0 0 100 100" fill="currentColor">
            <polygon points="50,0 100,100 0,100" />
        </svg>
        <div class="absolute bottom-0 left-1/2 w-48 h-48 bg-white rounded-full blur-3xl animate-float-slower"></div>
    </div>
<br>     --}}
<!-- Sela Section - Modern, Profesional, Tanpa Gambar -->
<section class="bg-gradient-to-r from-green-50 to-green-100 py-8 px-6 rounded-2xl shadow-md">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-8 items-center">
        
        <!-- Left Text Content -->
        <div data-aos="fade-up" data-aos-duration="800">
            <span class="inline-block bg-green-200 text-green-900 text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide">
                #BelanjaOnlineTerpercaya
            </span>

            <h2 class="text-2xl md:text-3xl font-extrabold text-[#16782d] mb-4 leading-snug">
                Belanja Hemat & Nyaman <br>
                hanya di <span class="underline decoration-green-300">WOWINFood</span>
            </h2>

            <p class="text-sm md:text-base text-gray-700 mb-6 leading-relaxed">
                Temukan berbagai produk pilihan terbaik untuk kebutuhan harian Anda. Transaksi mudah, cepat, dan aman dari genggaman Anda.
            </p>

            <!-- 3 Fitur Utama -->
            <div class="grid sm:grid-cols-3 gap-4 text-sm text-gray-700">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-green-100 text-green-700 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span>Pengiriman Cepat</span>
                </div>

                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-green-100 text-green-700 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span>Pembayaran Aman</span>
                </div>

                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-green-100 text-green-700 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 17v-6h13M6 20h.01M3 17h.01M6 17a1 1 0 011-1h13" />
                        </svg>
                    </div>
                    <span>Layanan Responsif</span>
                </div>
            </div>
        </div>

        <!-- Right Promo Box / Quote (Variatif Bagian Kanan) -->
        <div class="bg-white shadow-inner rounded-xl p-6 border border-green-100 text-center" data-aos="fade-left" data-aos-duration="800">
            <h3 class="text-green-700 font-bold text-lg mb-2">✨ Promo Spesial Untuk Member MyWowin</h3>
            <p class="text-sm text-gray-600 mb-4">Dapatkan diskon hingga <strong>30%</strong> untuk produk pilihan Anda. Promo berlaku sesuai dengan <em>Syarat & Ketentuan</em>!</p>
            <a href="{{ auth()->check() ? route('products') : route('login') }}"
                onclick="{{ auth()->check() ? '' : 'event.preventDefault(); window.location.href=\'' . route('login') . '\';' }}"
                class="inline-block mt-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-full transition">
                Lihat Produk
            </a>

        </div>
    </div>
</section>



@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush

<br>
{{-- section artikel --}}
{{-- section artikel --}}

@php
    // Ambil 4 artikel terbaru.
    $artikels = \App\Models\Artikel::latest()->take(4)->get();
@endphp

<section class="bg-gray-50 py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                Wawasan & Cerita Terbaru
            </h2>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-500">
                Jelajahi ide, berita, dan tren terkini yang kami sajikan khusus untuk Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            @forelse ($artikels as $artikel)
            <div class="group bg-white rounded-xl shadow-md overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:-translate-y-1.5">
                
                <div class="relative w-full h-44 overflow-hidden">
                    <a href="{{ route('public.artikels.show', $artikel->id) }}" class="block w-full h-full">
                        {{-- PERBAIKAN DI SINI: Menambahkan [0] untuk mengambil gambar pertama --}}
                        @if(!empty($artikel->foto_artikel) && is_array($artikel->foto_artikel))
                            <img src="{{ asset('storage/' . $artikel->foto_artikel[0]) }}" 
                                 alt="{{ $artikel->judul }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-3xl"></i>
                            </div>
                        @endif
                    </a>
                </div>

                <div class="p-5 flex flex-col flex-grow">
                    <div class="flex-grow">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                            <a href="{{ route('public.artikels.show', $artikel->id) }}" class="hover:text-[#16782d] transition-colors duration-300">
                                {{ $artikel->judul }}
                            </a>
                        </h3>
                        
                       <p class="text-sm text-gray-600 line-clamp-2">
                            @php
                                // Ambil data isi (ubah dari JSON ke Array jika perlu)
                                $kontenArray = is_array($artikel->isi) ? $artikel->isi : json_decode($artikel->isi, true);
                                $hanyaTeks = '';

                                if(is_array($kontenArray)) {
                                    foreach($kontenArray as $blok) {
                                        // Hanya ambil blok yang tipenya 'text'
                                        if(isset($blok['type']) && $blok['type'] === 'text') {
                                            $hanyaTeks .= $blok['value'] . ' ';
                                        }
                                    }
                                } else {
                                    // Fallback jika data masih dalam format string lama
                                    $hanyaTeks = $artikel->isi ?? 'Klik untuk membaca selengkapnya...';
                                }
                            @endphp
                            
                            {{-- Sekarang strip_tags aman karena $hanyaTeks sudah menjadi string --}}
                            {{ Str::limit(strip_tags($hanyaTeks), 80) }}
                        </p>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('public.artikels.show', $artikel->id) }}" 
                           class="inline-block w-full text-center bg-[#16782d] text-white text-sm font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition-colors duration-300">
                            Baca Selengkapnya
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-1 sm:col-span-2 lg:col-span-4 text-center py-12">
                <p class="text-gray-500">Belum ada artikel yang dipublikasikan.</p>
            </div>
            @endforelse

        </div>
    </div>
</section>

<section style="
    padding: 10px 0; 
    position: relative; 
    overflow: hidden; 
    background-color: #f9fafb;
    /* background-image: 
        radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 25%, rgba(147, 51, 234, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 25% 75%, rgba(239, 68, 68, 0.1) 0%, transparent 50%); */
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
">
    <!-- Background Elements -->
    <div style="position: absolute; inset: 0;">
        <div style="
            position: absolute; 
            top: 40px; 
            left: 40px; 
            width: 80px; 
            height: 80px; 
            background-color: rgba(52, 211, 153, 0.2); 
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        "></div>
        <div style="
            position: absolute; 
            top: 50%; 
            right: 80px; 
            width: 64px; 
            height: 64px; 
            background-color: rgba(59, 130, 246, 0.2); 
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
            animation-delay: -2s;
        "></div>
        <div style="
            position: absolute; 
            bottom: 80px; 
            left: 25%; 
            width: 48px; 
            height: 48px; 
            background-color: rgba(147, 51, 234, 0.2); 
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
            animation-delay: -4s;
        "></div>
        <div style="
            position: absolute; 
            top: 25%; 
            right: 33%; 
            width: 96px; 
            height: 96px; 
            background-color: rgba(99, 102, 241, 0.1); 
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
            animation-delay: -1s;
        "></div>
    </div>
    
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 24px; position: relative; z-index: 10;">
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 64px;">
            <h2 style="
                font-size: clamp(2rem, 5vw, 3.5rem); 
                font-weight: 700; 
                color: #1f2937; 
                margin-bottom: 24px; 
                line-height: 1.2;
            ">
                Kenapa Memilih 
                <span style="
                    background: linear-gradient(135deg, #10b981, #059669, #047857);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    position: relative;
                    overflow: hidden;
                ">Wowin Food?</span>
            </h2>
            <p style="
                font-size: 1.25rem; 
                color: #4b5563; 
                max-width: 768px; 
                margin: 0 auto; 
                line-height: 1.6;
            ">
                Kami berkomitmen untuk memberikan pengalaman terbaik melalui kualitas produk dan layanan yang tinggi. 
                <span style="color: #059669; font-weight: 600;">Bergabunglah dengan ribuan pelanggan puas kami!</span>
            </p>
            
           
        </div>
        
        <!-- Features Grid -->
        <div style="
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
            gap: 32px;
        ">
            <!-- Feature 1: Produk Berkualitas -->
            <div style="
                position: relative;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            " onmouseover="this.style.transform='translateY(-15px) scale(1.03)'; this.style.boxShadow='0 25px 50px rgba(0, 0, 0, 0.15)'; this.querySelector('.progress-bar').style.transform='scaleX(1)'; this.querySelector('.icon-container').style.transform='translateX(-50%) scale(1.1)';" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 25px rgba(0, 0, 0, 0.1)'; this.querySelector('.progress-bar').style.transform='scaleX(0)'; this.querySelector('.icon-container').style.transform='translateX(-50%) scale(1)';">
                <div style="
                    position: relative; 
                    padding: 32px; 
                    background-color: white; 
                    border-radius: 16px; 
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); 
                    border: 1px solid rgba(229, 231, 235, 1); 
                    height: 100%;
                ">
                    <div class="icon-container" style="
                        position: absolute; 
                        top: -24px; 
                        left: 50%; 
                        transform: translateX(-50%);
                        transition: transform 0.3s ease;
                    ">
                        <div style="
                            width: 64px; 
                            height: 64px; 
                            background: linear-gradient(135deg, #34d399, #059669); 
                            border-radius: 16px; 
                            display: flex; 
                            align-items: center; 
                            justify-content: center; 
                            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                            animation: pulse-glow 2s infinite;
                        ">
                            <svg style="
                                width: 32px; 
                                height: 32px; 
                                color: white;
                                animation: bounce-gentle 2s infinite;
                            " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div style="padding-top: 32px; text-align: center;">
                        <h3 style="
                            font-size: 1.25rem; 
                            font-weight: 700; 
                            color: #1f2937; 
                            margin-bottom: 12px;
                        ">
                            Produk Berkualitas
                        </h3>
                        <p style="color: #4b5563; line-height: 1.6;">
                            Setiap produk kami pilih dengan hati-hati untuk memastikan mutu yang sempurna dan kepuasan Anda.
                        </p>
                    </div>
                    <div class="progress-bar" style="
                        position: absolute; 
                        bottom: 0; 
                        left: 0; 
                        width: 100%; 
                        height: 4px; 
                        background: linear-gradient(90deg, #34d399, #059669); 
                        transform: scaleX(0); 
                        transition: transform 0.3s ease; 
                        border-radius: 0 0 16px 16px;
                    "></div>
                </div>
            </div>
            
            <!-- Feature 2: Pengiriman Cepat -->
            <div style="
                position: relative;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            " onmouseover="this.style.transform='translateY(-15px) scale(1.03)'; this.style.boxShadow='0 25px 50px rgba(0, 0, 0, 0.15)'; this.querySelector('.progress-bar').style.transform='scaleX(1)'; this.querySelector('.icon-container').style.transform='translateX(-50%) scale(1.1)';" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 25px rgba(0, 0, 0, 0.1)'; this.querySelector('.progress-bar').style.transform='scaleX(0)'; this.querySelector('.icon-container').style.transform='translateX(-50%) scale(1)';">
                <div style="
                    position: relative; 
                    padding: 32px; 
                    background-color: white; 
                    border-radius: 16px; 
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); 
                    border: 1px solid rgba(229, 231, 235, 1); 
                    height: 100%;
                ">
                    <div class="icon-container" style="
                        position: absolute; 
                        top: -24px; 
                        left: 50%; 
                        transform: translateX(-50%);
                        transition: transform 0.3s ease;
                    ">
                        <div style="
                            width: 64px; 
                            height: 64px; 
                            background: linear-gradient(135deg, #60a5fa, #2563eb); 
                            border-radius: 16px; 
                            display: flex; 
                            align-items: center; 
                            justify-content: center; 
                            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                        ">
                            <svg style="
                                width: 32px; 
                                height: 32px; 
                                color: white;
                                animation: bounce-gentle 2s infinite;
                            " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div style="padding-top: 32px; text-align: center;">
                        <h3 style="
                            font-size: 1.25rem; 
                            font-weight: 700; 
                            color: #1f2937; 
                            margin-bottom: 12px;
                        ">
                            Pengiriman Cepat
                        </h3>
                        <p style="color: #4b5563; line-height: 1.6;">
                            Kami menjaga kecepatan pengiriman agar pengalaman berbelanja Anda tetap menyenangkan dan memuaskan.
                        </p>
                    </div>
                    <div class="progress-bar" style="
                        position: absolute; 
                        bottom: 0; 
                        left: 0; 
                        width: 100%; 
                        height: 4px; 
                        background: linear-gradient(90deg, #60a5fa, #2563eb); 
                        transform: scaleX(0); 
                        transition: transform 0.3s ease; 
                        border-radius: 0 0 16px 16px;
                    "></div>
                </div>
            </div>
            
            <!-- Feature 3: Transaksi Aman -->
            <div style="
                position: relative;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            " onmouseover="this.style.transform='translateY(-15px) scale(1.03)'; this.style.boxShadow='0 25px 50px rgba(0, 0, 0, 0.15)'; this.querySelector('.progress-bar').style.transform='scaleX(1)'; this.querySelector('.icon-container').style.transform='translateX(-50%) scale(1.1)';" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 25px rgba(0, 0, 0, 0.1)'; this.querySelector('.progress-bar').style.transform='scaleX(0)'; this.querySelector('.icon-container').style.transform='translateX(-50%) scale(1)';">
                <div style="
                    position: relative; 
                    padding: 32px; 
                    background-color: white; 
                    border-radius: 16px; 
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); 
                    border: 1px solid rgba(229, 231, 235, 1); 
                    height: 100%;
                ">
                    <div class="icon-container" style="
                        position: absolute; 
                        top: -24px; 
                        left: 50%; 
                        transform: translateX(-50%);
                        transition: transform 0.3s ease;
                    ">
                        <div style="
                            width: 64px; 
                            height: 64px; 
                            background: linear-gradient(135deg, #a78bfa, #7c3aed); 
                            border-radius: 16px; 
                            display: flex; 
                            align-items: center; 
                            justify-content: center; 
                            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                        ">
                            <svg style="
                                width: 32px; 
                                height: 32px; 
                                color: white;
                                animation: bounce-gentle 2s infinite;
                            " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.0 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                    <div style="padding-top: 32px; text-align: center;">
                        <h3 style="
                            font-size: 1.25rem; 
                            font-weight: 700; 
                            color: #1f2937; 
                            margin-bottom: 12px;
                        ">
                            Transaksi Aman
                        </h3>
                        <p style="color: #4b5563; line-height: 1.6;">
                            Keamanan transaksi Anda adalah prioritas utama kami dengan sistem enkripsi terbaik dan terpercaya.
                        </p>
                    </div>
                    <div class="progress-bar" style="
                        position: absolute; 
                        bottom: 0; 
                        left: 0; 
                        width: 100%; 
                        height: 4px; 
                        background: linear-gradient(90deg, #a78bfa, #7c3aed); 
                        transform: scaleX(0); 
                        transition: transform 0.3s ease; 
                        border-radius: 0 0 16px 16px;
                    "></div>
                </div>
            </div>
            
            <!-- Feature 4: Layanan Pelanggan -->
            <div style="
                position: relative;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            " onmouseover="this.style.transform='translateY(-15px) scale(1.03)'; this.style.boxShadow='0 25px 50px rgba(0, 0, 0, 0.15)'; this.querySelector('.progress-bar').style.transform='scaleX(1)'; this.querySelector('.icon-container').style.transform='translateX(-50%) scale(1.1)';" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 25px rgba(0, 0, 0, 0.1)'; this.querySelector('.progress-bar').style.transform='scaleX(0)'; this.querySelector('.icon-container').style.transform='translateX(-50%) scale(1)';">
                <div style="
                    position: relative; 
                    padding: 32px; 
                    background-color: white; 
                    border-radius: 16px; 
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); 
                    border: 1px solid rgba(229, 231, 235, 1); 
                    height: 100%;
                ">
                    <div class="icon-container" style="
                        position: absolute; 
                        top: -24px; 
                        left: 50%; 
                        transform: translateX(-50%);
                        transition: transform 0.3s ease;
                    ">
                        <div style="
                            width: 64px; 
                            height: 64px; 
                            background: linear-gradient(135deg, #818cf8, #4f46e5); 
                            border-radius: 16px; 
                            display: flex; 
                            align-items: center; 
                            justify-content: center; 
                            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
                        ">
                            <svg style="
                                width: 32px; 
                                height: 32px; 
                                color: white;
                                animation: bounce-gentle 2s infinite;
                            " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div style="padding-top: 32px; text-align: center;">
                        <h3 style="
                            font-size: 1.25rem; 
                            font-weight: 700; 
                            color: #1f2937; 
                            margin-bottom: 12px;
                        ">
                            Layanan Pelanggan
                        </h3>
                        <p style="color: #4b5563; line-height: 1.6;">
                            Dapatkan dukungan 24/7 dengan tim profesional yang siap membantu Anda kapan saja dengan responsif.
                        </p>
                    </div>
                    <div class="progress-bar" style="
                        position: absolute; 
                        bottom: 0; 
                        left: 0; 
                        width: 100%; 
                        height: 4px; 
                        background: linear-gradient(90deg, #818cf8, #4f46e5); 
                        transform: scaleX(0); 
                        transition: transform 0.3s ease; 
                        border-radius: 0 0 16px 16px;
                    "></div>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 64px;">
    @auth
        <a href="{{ route('products') }}"
           style="
               display: inline-block;
               position: relative; 
               padding: 16px 32px; 
               background: linear-gradient(90deg, #10b981, #059669); 
               color: white; 
               font-weight: 600; 
               border-radius: 12px; 
               box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); 
               transform: scale(1); 
               transition: all 0.3s ease; 
               border: none; 
               cursor: pointer; 
               overflow: hidden;
               font-size: 16px;
               text-decoration: none;
           "
           onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 15px 35px rgba(0, 0, 0, 0.2)';"
           onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 10px 25px rgba(0, 0, 0, 0.15)';"
        >
            Mulai Berbelanja Sekarang
        </a>
    @else
        <a href="{{ route('login') }}"
           style="
               display: inline-block;
               position: relative; 
               padding: 16px 32px; 
               background: linear-gradient(90deg, #10b981, #059669); 
               color: white; 
               font-weight: 600; 
               border-radius: 12px; 
               box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); 
               transform: scale(1); 
               transition: all 0.3s ease; 
               border: none; 
               cursor: pointer; 
               overflow: hidden;
               font-size: 16px;
               text-decoration: none;
           "
           onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 15px 35px rgba(0, 0, 0, 0.2)';"
           onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 10px 25px rgba(0, 0, 0, 0.15)';"
        >
            Mulai Berbelanja Sekarang
        </a>
    @endauth
</div>

    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.3); }
            50% { box-shadow: 0 0 40px rgba(16, 185, 129, 0.6), 0 0 60px rgba(16, 185, 129, 0.3); }
        }
        
        @keyframes bounce-gentle {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
    </style>
</section>





@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@endsection
