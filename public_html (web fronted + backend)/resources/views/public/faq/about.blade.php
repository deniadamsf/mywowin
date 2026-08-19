
@extends('public.layouts.app') 
 
@section('title', 'MyWowin | About Us') 
 
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
        <span class="text-[#16782d] font-semibold flex items-center"> 
            Tentang Kami
        </span> 
    </div> 
</div> 

<!-- Hero Section with Animation -->
<div class="bg-[#0A5C36] text-white py-16 relative overflow-hidden">
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center animate__animated animate__fadeIn">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Tentang MyWowin</h1>
            <div class="w-24 h-1 bg-yellow-400 mx-auto mb-6"></div>
            <p class="text-lg max-w-2xl mx-auto">Memimpin inovasi untuk masa depan pangan yang berkelanjutan dan bermanfaat</p>
        </div>
    </div>
    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-full h-full">
        <div class="absolute top-10 left-10 w-16 h-16 rounded-full bg-[#FFD700] opacity-20 animate__animated animate__pulse animate__infinite"></div>
        <div class="absolute bottom-10 right-10 w-24 h-24 rounded-full bg-[#D32F2F] opacity-10 animate__animated animate__pulse animate__infinite animate__delay-1s"></div>
    </div>
</div>


<br>
<br>
<div class="flex justify-center">
    <div class="grid md:grid-cols-2 gap-6 w-full max-w-5xl px-4">
      @foreach (['visi' => 'Visi', 'misi' => 'Misi'] as $img => $title)
        <div class="relative group h-[400px] rounded-2xl overflow-hidden shadow-xl">
          
          <!-- Background Miring Dekoratif -->
          <div class="absolute inset-0 z-[-1] transform rotate-3 scale-110 bg-[#00ede6] transition-transform duration-500 group-hover:rotate-6 group-hover:scale-125 origin-bottom-left rounded-2xl"></div>
  
          <!-- Gambar Utama -->
          <img src="{{ asset('images/' . $img . '.jpg') }}" alt="{{ $title }} MyWowin"
            class="absolute inset-0 w-full h-full object-cover z-10 transition-transform duration-700 group-hover:scale-105" />
  
          <!-- Konten Glassmorphism -->
          <div class="absolute bottom-0 left-0 right-0 p-4 backdrop-blur-md bg-white/10 text-white text-sm z-20">
            <h3 class="text-lg md:text-xl font-bold mb-1 text-[#FFFF00]">{{ $title }}</h3>
  
            @if ($title === 'Visi')
              <p class="mb-2">
                Menjadi inovasi dalam ekosistem pangan yang berkelanjutan dan memberikan dampak positif bagi Indonesia.
              </p>
              <div x-data="{ expanded: false }">
                <div x-show="expanded" class="space-y-2 animate__animated animate__fadeIn text-xs">
                  <p>MyWowin berkomitmen menciptakan masa depan pangan lebih baik melalui pendekatan holistik...</p>
                  <p>Kami membayangkan dunia dengan akses pangan berkualitas tinggi...</p>
                  <p>Menerapkan prinsip ekonomi sirkular dan praktik regeneratif...</p>
                </div>
                <button 
                  @click="expanded = !expanded" 
                  class="mt-2 text-[#FFFF00] font-semibold hover:underline flex items-center"
                >
                  <span x-text="expanded ? 'Lihat Lebih Sedikit' : 'Lihat Lebih Banyak'" class="text-sm text-[#FFFF00]"></span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transition-transform duration-300" :class="{ 'rotate-180': expanded }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
              </div>
            @else
              <p class="mb-2">
                Mengembangkan solusi pangan yang inovatif dan berkelanjutan melalui integrasi teknologi, kemitraan strategis...
              </p>
              <div x-data="{ expanded: false }">
                <div x-show="expanded" class="space-y-2 animate__animated animate__fadeIn text-xs">
                  <ul class="list-inside list-decimal space-y-1">
                    <li>Membangun platform teknologi pangan terintegrasi.</li>
                    <li>Pengembangan produk pangan bernutrisi tinggi.</li>
                    <li>Memberdayakan petani lokal melalui pelatihan.</li>
                    <li>Praktik ramah lingkungan dan berkelanjutan.</li>
                    <li>Edukasi masyarakat terkait pangan bertanggung jawab.</li>
                  </ul>
                  <p>Komitmen kami untuk terus berinovasi dalam setiap langkah.</p>
                </div>
                <button 
                  @click="expanded = !expanded" 
                  class="mt-2 text-[#FFFF00] font-semibold hover:underline flex items-center"
                >
                  <span x-text="expanded ? 'Lihat Lebih Sedikit' : 'Lihat Lebih Banyak'" class="text-sm text-[#FFFF00]"></span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transition-transform duration-300" :class="{ 'rotate-180': expanded }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
  
  

<br>
<br>
<!-- Nilai-nilai Perusahaan -->
<div class="py-16 bg-[#0A5C36] text-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-2">Nilai-nilai Kami</h2>
            <div class="w-20 h-1 bg-[#FFD700] mx-auto mb-4"></div>
            <p class="max-w-2xl mx-auto opacity-80">Prinsip yang memandu setiap langkah dan keputusan MyWowin</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Nilai 1 -->
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 hover:bg-white/20 transition-all duration-300">
                <div class="h-14 w-14 rounded-full bg-[#FFD700] flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#0A5C36]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Integritas</h3>
                <p>Kami menjalankan bisnis dengan standar etika tertinggi, menjunjung transparansi dan kejujuran dalam setiap aspek operasional.</p>
            </div>
            
            <!-- Nilai 2 -->
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 hover:bg-white/20 transition-all duration-300">
                <div class="h-14 w-14 rounded-full bg-[#FFD700] flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#0A5C36]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Inovasi</h3>
                <p>Terus berusaha mencari solusi kreatif dan teknologi terdepan untuk menghadapi tantangan dalam industri pangan.</p>
            </div>
            
            <!-- Nilai 3 -->
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 hover:bg-white/20 transition-all duration-300">
                <div class="h-14 w-14 rounded-full bg-[#D32F2F] flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Keberlanjutan</h3>
                <p>Berkomitmen untuk meminimalkan dampak lingkungan dan memaksimalkan dampak sosial positif dalam semua kegiatan kami.</p>
            </div>
        </div>
    </div>
</div>
 @endsection
        