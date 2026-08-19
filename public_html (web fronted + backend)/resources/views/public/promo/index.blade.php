@extends('public.layouts.app')

@section('title', 'WOWINFood')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
@endsection

@section('content')
<!-- Breadcrumb Navigation yang Lebih Elegan -->
<div class="container mx-auto  px-4 py-4">
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
            Promo
        </span>
    </div>
</div>

{{-- tampilan bundling rewards --}}

<!-- Promo Section -->
<section class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach ($bundlings as $bundling)
            <div class="bg-white shadow-md rounded-lg overflow-hidden cursor-pointer" 
                 onclick="window.location.href='{{ route('promo.detail', ['id' => $bundling->id_bundling]) }}'">
                <img src="{{ asset('storage/' . $bundling->barang_bundling) }}" alt="{{ $bundling->nama_bundling }}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gray-800">{{ $bundling->nama_bundling }}</h2>
                </div>
            </div>
        @endforeach
    </div>
</section>



@endsection
