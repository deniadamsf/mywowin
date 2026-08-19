@extends('public.layouts.app')

@section('title', 'WOWINFood - Detail Bundling')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<meta name="description" content="WOWINFood">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    /* Impor Font Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    .container-poppins {
        font-family: 'Poppins', sans-serif;
    }

    /* Menghilangkan panah pada input number */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Custom scrollbar untuk thumbnail mobile agar tetap cantik */
    .custom-scrollbar::-webkit-scrollbar {
        height: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
</style>
@endsection

@section('content')
<div class="container-poppins container mx-auto px-4 py-4">
    <div class="flex items-center space-x-2 bg-white px-4 py-2 rounded-lg shadow-md w-full max-w-7xl border-l-4 border-[#16782d] mb-6 overflow-x-auto whitespace-nowrap">
        {{-- <a href="/" class="text-gray-600 hover:text-[#16782d] font-medium transition-colors duration-300 flex items-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Beranda
        </a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg> --}}
        <a href="{{ route('promo') }}" class="text-[#16782d] font-semibold flex items-center shrink-0">
            Promo
        </a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-[#16782d] font-semibold inline-flex items-center truncate">
            {{ $bundling->nama_bundling }}
        </span>
    </div>

    <div class="bg-white shadow-lg rounded-xl p-4 md:p-8 max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <div class="w-full" x-data="{ 
            activeMedia: '{{ $bundling->youtube_link ? str_replace('watch?v=', 'embed/', $bundling->youtube_link) : asset('storage/' . $bundling->barang_bundling) }}', 
            isVideo: {{ $bundling->youtube_link ? 'true' : 'false' }} 
        }">
            <div class="relative w-full aspect-square max-w-[400px] mx-auto bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center shadow-inner">
                <template x-if="isVideo">
                    <iframe :src="activeMedia + '?autoplay=1&mute=1&rel=0&showinfo=0'" 
                            class="absolute inset-0 w-full h-full"
                            frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                    </iframe>
                </template>
                <template x-if="!isVideo">
                    <img :src="activeMedia" 
                         alt="Barang Bundling"  
                         class="w-full h-full object-contain p-2 transition-transform duration-500 hover:scale-105">
                </template>
            </div>

            <div class="flex space-x-2 mt-4 overflow-x-auto pb-2 custom-scrollbar justify-center lg:justify-start">
                @if ($bundling->youtube_link)
                    <div class="cursor-pointer shrink-0" @click="activeMedia='{{ str_replace('watch?v=', 'embed/', $bundling->youtube_link) }}'; isVideo=true">
                        <img src="https://img.youtube.com/vi/{{ \Illuminate\Support\Str::afterLast($bundling->youtube_link, 'v=') }}/0.jpg" 
                             class="w-20 h-20 rounded-lg border-2 transition" :class="isVideo ? 'border-[#16782d]' : 'border-gray-200'">
                    </div>
                @endif

                @if ($bundling->barang_bundling)
                    <div class="cursor-pointer shrink-0" @click="activeMedia='{{ asset('storage/' . $bundling->barang_bundling) }}'; isVideo=false">
                        <img src="{{ asset('storage/' . $bundling->barang_bundling) }}" 
                             class="w-20 h-20 rounded-lg border-2 transition" :class="!isVideo ? 'border-[#16782d]' : 'border-gray-200'">
                    </div>
                @endif
            </div>
        </div>

        <div class="w-full">
            <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $bundling->nama_bundling }}</h1>
            
            <div class="bg-white rounded-xl border border-gray-200 p-4 my-4 shadow-sm space-y-3">
                <div class="flex items-center space-x-2 text-gray-500 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                    </svg>                  
                    <span>Brand : PT. Wowin Purnomo Putera</span>
                    <span class="text-blue-500 font-semibold">{{ $bundling->brand }}</span>
                </div>
                <div class="border-t border-dashed border-gray-300"></div>
                <div class="flex items-start space-x-2">
                    <img width="24" height="24" src="https://img.icons8.com/color/48/shop.png" alt="shop"/>
                    <div>
                        <p class="text-gray-700 font-medium text-sm">Stok dari Toko</p>
                        <p class="text-gray-500 text-xs">Pengiriman dilakukan pada hari yang sama.</p>
                    </div>
                </div>
            </div>
            
            <div class="py-2">
                <p class="text-sm text-gray-500">Harga Bundling:</p>
                <p class="text-red-600 text-3xl font-bold my-1">
                    Rp {{ number_format($bundling->price, 0, ',', '.') }}
                </p>
                @if ($bundling->price_before > 0 && $bundling->price_before > $bundling->price)
                    <div class="flex items-center space-x-2">
                        <small class="text-gray-400 line-through text-base">Rp {{ number_format($bundling->price_before, 0, ',', '.') }}</small>
                        @php $disc = (($bundling->price_before - $bundling->price) / $bundling->price_before) * 100; @endphp
                        <span class="text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded text-xs">Hemat {{ round($disc) }}%</span>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <h2 class="font-bold text-lg mb-2 border-b pb-1 text-gray-800">Deskripsi</h2>
                <ul class="list-disc list-inside text-gray-700 text-sm space-y-1">
                    <li>Produk Berlaku : 12 feb - 19 feb 2025</li>
                    <li>Wowin Bundling</li>
                    <li>Sahabat Hidangan Anda</li>
                </ul>
            </div>
        </div>

        <div class="p-5 border border-gray-200 rounded-2xl bg-white shadow-sm w-full lg:max-w-[400px] mx-auto" x-data="{ qty: 1 }">
            <form action="{{ route('carts.addBundling') }}" method="POST">
                @csrf
                <input type="hidden" name="bundling_id" value="{{ $bundling->id_bundling }}">
                
                <div class="flex items-center justify-between mb-6">
                    <p class="text-gray-700 text-sm font-semibold uppercase tracking-wider">Jumlah :</p>
                    <div class="flex items-center border border-gray-300 rounded-full overflow-hidden shadow-sm bg-gray-50">
                        <button type="button" @click="qty > 1 ? qty-- : 1" class="bg-white text-gray-800 w-9 h-9 flex items-center justify-center hover:bg-gray-100 transition active:bg-gray-200">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        </button>
                        <input type="number" name="quantity" x-model="qty" class="w-10 text-center bg-transparent border-none focus:ring-0 font-bold text-gray-800 pointer-events-none" readonly>
                        <button type="button" @click="qty++" class="bg-white text-gray-800 w-9 h-9 flex items-center justify-center hover:bg-gray-100 transition active:bg-gray-200">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl w-full shadow-lg shadow-red-100 transition-all active:scale-95 flex items-center justify-center space-x-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>+ Keranjang</span>
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="font-bold text-gray-900 text-xs tracking-wide uppercase mb-3">Pengiriman</h3>
                <div class="space-y-2">
                    <p class="text-gray-600 text-sm">Oleh <strong>PT. Wowin Purnomo Putera</strong></p>
                    <p class="text-green-600 font-bold text-sm italic">Gratis Biaya Pengiriman</p>
                </div>
            </div>
        </div>
    </div>

    <section class="mt-12">
        <div class="flex flex-col mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Produk dalam Promo Ini</h2>
            <p class="text-sm text-gray-500">Dapatkan semua produk di bawah ini dalam satu harga hemat</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse ($bundling->products as $product)
                <div class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all border border-gray-100 overflow-hidden cursor-pointer"
                    onclick="window.location.href='{{ route('products.detail', ['id' => $product->id_product]) }}'">
                    
                    <div class="aspect-square bg-white flex items-center justify-center overflow-hidden">
                        @if ($product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->first()->image_url) }}" 
                                 class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                        @else
                            <div class="text-gray-200 flex flex-col items-center">
                                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span class="text-[10px]">No Image</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-3">
                        <h3 class="text-xs font-semibold text-gray-800 line-clamp-2 uppercase leading-tight group-hover:text-blue-600 transition-colors">
                            {{ $product->nama_produk }}
                        </h3>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 italic col-span-full py-10 text-center">Tidak ada produk dalam bundling ini.</p>
            @endforelse
        </div>
    </section>
</div>
<script>
    document.getElementById('addBundlingToCart').addEventListener('click', function() {
    const bundlingId = this.getAttribute('data-bundling-id');

    fetch('/carts/addBundling', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ bundling_id: bundlingId })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert('Promo bundling berhasil ditambahkan ke keranjang!');
        } else {
            alert('Gagal menambahkan bundling ke keranjang.');
        }
    })
    .catch(err => {
        alert('Terjadi kesalahan.');
        console.error(err);
    });
});

</script>
@endsection
