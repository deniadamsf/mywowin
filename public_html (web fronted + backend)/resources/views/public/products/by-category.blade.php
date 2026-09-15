@extends('public.layouts.app')

@section('title', 'WOWINFood')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<meta name="description" content="WOWINFood - Temukan berbagai produk berkualitas dengan harga terbaik">
<style>
    /* Impor Font Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    /* Terapkan Font Poppins */
    .container {
        font-family: 'Poppins', sans-serif;
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
            {{ $kategori->name }}
        </span>
    </div>
</div>

<!-- Wrapper section agar sejajar -->
<div class="w-full max-w-[1280px] mx-auto px-4 py-4">
{{-- <div class="container mx-auto py-6 px-4"> --}}
    {{-- <h1 class="text-2xl font-bold mb-4 text-center">Kategori: {{ $kategori->name }}</h1> --}}

    @if($produk->count())
    <section>
        <h2 class="text-xl font-semibold text-left">Produk Kategori</h2>
        <p class="text-left text-xs text-gray-500 mb-4">Menampilkan Keseluruhan Produk</p>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 md:gap-6">
            @foreach ($produk as $product)
            <div 
                class="relative group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col h-full transform hover:-translate-y-1 cursor-pointer"
                onclick="window.location.href='{{ route('products.detail', ['id' => $product->id_product]) }}'">

                @if($product->is_featured)
                <div class="absolute top-2 left-2 md:top-4 md:left-4 z-10">
                    <span class="bg-[#16782d] text-white text-[10px] md:text-xs font-bold px-2 py-0.5 md:px-3 md:py-1 rounded-full">Unggulan</span>
                </div>
                @endif

                <div class="relative overflow-hidden aspect-[4/5] flex items-center justify-center bg-white">
                    @if ($product->images->isNotEmpty()) 
                        <img src="{{ asset('storage/' . $product->images->first()->image_url) }}" 
                            alt="{{ $product->name }}" 
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 md:h-16 md:w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="p-2 md:p-4 flex-grow flex flex-col">
                    <h3 class="text-xs md:text-sm font-semibold text-gray-800 mb-1 group-hover:text-[#16782d] transition-colors line-clamp-2">
                        {{ $product->nama_produk }}
                    </h3>
                    <p class="text-gray-500 text-[10px] md:text-xs mb-1 md:mb-2">{{ $product->isi_ml ?? '—' }} ml</p>

                    @if($product->in_stock)
                    <div class="flex items-center text-[10px] md:text-xs text-gray-600 mb-1 md:mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 md:h-4 md:w-4 mr-1 text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Stok Tersedia
                    </div>
                    @endif

                    <div class="mt-auto">
                        <div class="flex items-center justify-between mb-2 md:mb-3">
                            <p class="text-[#16782d] font-bold text-xs md:text-sm">
                                Rp{{ number_format($product->harga_pcs, 0, ',', '.') }} <span class="text-[10px] text-gray-500 font-normal">/ pcs</span>
                            </p>
                            @if($product->old_price)
                            <p class="text-gray-400 text-[10px] md:text-xs line-through">
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

                        <div class="md:hidden">
                            <form action="{{ route('carts.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" 
                                    class="flex items-center justify-center w-full bg-[#16782d] hover:bg-green-700 text-white text-xs font-medium py-1.5 px-4 rounded-lg transition-all">
                                    <span>Beli</span>
                                </button>
                            </form>
                        </div>

                        <div class="hidden md:flex space-x-3">
                            <form action="{{ route('carts.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" 
                                    class="flex items-center justify-center w-full bg-[#16782d] hover:bg-green-700 text-white text-xs font-medium py-2 px-12 rounded-lg transition-all">
                                    <span>Beli</span>
                                </button>
                            </form>

                            <form action="{{ route('carts.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="bg-gray-100 hover:bg-gray-200 p-1.5 rounded-lg transition-all" title="Tambah ke Keranjang">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l1.68 7.39M7 13h10l4-8H5.34M16 21a1 1 0 100-2 1 1 0 000 2zm-8 0a1 1 0 100-2 1 1 0 000 2z"/>
                                    </svg>                        
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @else
        <p class="text-center text-gray-600 mt-8">Tidak ada produk dalam kategori ini.</p>
    @endif
</div>
@endsection
