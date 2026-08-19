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
    <div class="flex items-center space-x-2 bg-white px-4 py-2 rounded-lg shadow-md w-full max-w-7xl border-l-4 border-r-4 border-[#16782d]">
        <a href="/" class="text-gray-600 hover:text-[#16782d] font-medium transition-colors duration-300 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Beranda
        </a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-[#16782d] font-semibold flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Produk
        </span>
    </div>
</div>

<!-- Wrapper section agar sejajar -->
<div class="w-full max-w-[1280px] mx-auto px-4 py-4">


    <section>
        <h2 class="te xt-xl font-semibold text-left">Produk Terbaru</h2>
        <p class="text-left text-xs text-gray-500">Menampilkan Keseluruhan Produk</p>
        <br>
        <!-- Grid Produk: 2 Kolom di Mobile, 6 Kolom di Desktop -->
<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 md:gap-6" 
     x-data="{ 
        // Fungsi untuk mengirim data ke keranjang secara background (AJAX)
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
                    unit: 'pcs' // Secara default set ke pcs sesuai kebutuhan controller
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // SETELAH BERHASIL, LANGSUNG PINDAH KE HALAMAN CART
                    window.location.href = '{{ route('carts.index') }}';
                } else {
                    alert(data.message || 'Gagal menambahkan produk');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Silahkan login terlebih dahulu');
            });
        }
     }">
    
    @foreach ($products as $product)
    <div class="relative group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col h-full transform hover:-translate-y-1">
        
        <a href="{{ route('products.detail', ['id' => $product->id_product]) }}" class="block flex-grow">
            <div class="relative aspect-[4/5] flex items-center justify-center bg-white">
                @if ($product->images->isNotEmpty()) 
                    <img src="{{ asset('storage/' . $product->images->first()->image_url) }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 md:h-16 md:w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
            </div>

            <div class="p-2 md:p-4">
                <h3 class="text-xs md:text-sm font-semibold text-gray-800 line-clamp-2">
                    {{ $product->nama_produk }}
                </h3>
                <p class="text-gray-500 text-[10px] md:text-xs mb-1">{{ $product->isi_ml }} ml</p>
                <p class="text-[#16782d] font-bold text-xs md:text-sm mt-1">
                    Rp{{ number_format($product->harga, 0, ',', '.') }}
                </p>
            </div>
        </a>

        <div class="px-2 pb-2 md:px-4 md:pb-4 mt-auto">
            <button type="button" 
                @click="addToCart({{ $product->id_product }})"
                class="w-full bg-[#16782d] hover:bg-green-700 text-white text-xs font-medium py-2 rounded-lg transition-all active:scale-95 flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Beli
            </button>
        </div>
    </div>
    @endforeach
</div>
    </section>
</div>

<br>
@endsection