@extends('public.layouts.app')

@section('title', 'WOWINFood')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
@endsection

@section('content')
<br>
@php
    $bundlings = \App\Models\Bundling::all(); // Ambil semua data dari tabel bundlings
@endphp

<section class="carousel-section relative w-full max-w-6xl mx-auto mt-6 group">
    <!-- Carousel Wrapper -->
    <div class="overflow-hidden relative rounded-lg shadow-lg border border-gray-200">
        <div x-data="{ 
            activeSlide: 0, 
            totalSlides: {{ $bundlings->count() }}, 
            autoPlay() { 
                setInterval(() => { this.next(); }, 5000); // Ganti slide setiap 5 detik
            }, 
            next() { 
                this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
            }, 
            prev() { 
                this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
            } 
        }" x-init="autoPlay()">

            <!-- Wrapper Slide -->
            <div class="flex transition-transform duration-700 ease-in-out"
                 :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">
                @foreach ($bundlings as $bundling)
                <div class="w-full shrink-0 flex justify-center items-center">
                    <img src="{{ asset('storage/' . $bundling->barang_bundling) }}" 
                         alt="Barang Bundling" 
                         class="w-full h-[380px] sm:h-[300px] md:h-[320px] lg:h-[380px] object-cover rounded-lg">
                </div>
                @endforeach
            </div>

            <!-- Tombol Navigasi -->
            <button @click="prev" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-gray-800 bg-opacity-70 text-white p-3 rounded-full shadow-lg hover:bg-opacity-100 transition">
                &#10094;
            </button>
            <button @click="next" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-gray-800 bg-opacity-70 text-white p-3 rounded-full shadow-lg hover:bg-opacity-100 transition">
                &#10095;
            </button>

            <!-- Indicators -->
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2">
                @foreach ($bundlings as $index => $bundling)
                    <button @click="activeSlide = {{ $index }}" 
                            :class="activeSlide === {{ $index }} ? 'bg-[#16782d] w-4 h-4' : 'bg-gray-400 w-3 h-3'"
                            class="rounded-full transition-all"></button>
                @endforeach
            </div>
        </div>
    </div>

   <!-- Horizontal Line & Lihat Semua -->
<div class="flex items-center mt-6">
    <hr class="flex-grow border-gray-300">
    <a href="{{ route('products') }}" class="ml-4 text-[#16782d] font-semibold text-xs hover:underline">Lihat Semua &rarr;</a>
</div>

</section>


@endsection
