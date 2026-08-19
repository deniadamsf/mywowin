@extends('admin.layouts.master')

@section('title', 'WOWINFood')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
  /* Custom shapes */
  .shape-blob1 {
    position: absolute;
    top: -80px;
    right: -50px;
    width: 300px;
    height: 300px;
    background-color: #16782d;
    opacity: 0.1;
    border-radius: 58% 42% 38% 62% / 42% 55% 45% 58%;
    z-index: 0;
  }
  
  .shape-blob2 {
    position: absolute;
    bottom: -100px;
    left: -80px;
    width: 250px;
    height: 250px;
    background-color: #16782d;
    opacity: 0.07;
    border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
    z-index: 0;
  }
  
  .shape-blob3 {
    position: absolute;
    top: 40%;
    right: 15%;
    width: 150px;
    height: 150px;
    background-color: #16782d;
    opacity: 0.05;
    border-radius: 63% 37% 54% 46% / 55% 48% 52% 45%;
    z-index: 0;
  }
</style>
@endsection

@section('content')
<div class="container mx-auto relative overflow-hidden">
  <!-- Decorative shapes -->
  <div class="shape-blob1"></div>
  <div class="shape-blob2"></div>
  <div class="shape-blob3"></div>

  <!-- Welcome card -->
<div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-[#16782d] mb-8 relative z-10">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang, Di WOWINFood</h1>
        <h2 class="text-xl text-gray-600">Sistem Index Admin Wowinfood</h2>
      </div>
      <div class="hidden md:block">
        <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-[#16782d] shadow">
          <img src="{{ asset('images/wwn-cr.png') }}" alt="WOWINFood Logo" class="w-full h-full object-cover">
        </div>
      </div>
    </div>
  </div>
  
  
  <!-- Stats cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Users stat -->
    <div class="bg-white p-5 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 relative z-10">
      <div class="flex items-start mb-4">
        <div class="w-10 h-10 rounded-lg bg-[#16782d]/10 flex items-center justify-center mr-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-500">Total Pengguna</h3>
          <p class="text-2xl font-bold text-gray-800">{{ $totalMembershipUsers }}</p>
        </div>
      </div>
      <div class="flex items-center text-[#16782d] text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
        <span>12% dari bulan lalu</span>
      </div>
    </div>
    
    <!-- Orders stat -->
    <div class="bg-white p-5 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 relative z-10">
      <div class="flex items-start mb-4">
        <div class="w-10 h-10 rounded-lg bg-[#16782d]/10 flex items-center justify-center mr-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-500">Total Pesanan</h3>
          <p class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</p>
        </div>
      </div>
      <div class="flex items-center text-[#16782d] text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
        <span>8% dari bulan lalu</span>
      </div>
    </div>
    
    <!-- Revenue stat -->
    <div class="bg-white p-5 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 relative z-10">
      <div class="flex items-start mb-4">
        <div class="w-10 h-10 rounded-lg bg-[#16782d]/10 flex items-center justify-center mr-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-500">Total Pendapatan</h3>
          <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
      </div>
      <div class="flex items-center text-[#16782d] text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
        <span>15% dari bulan lalu</span>
      </div>
    </div>
    
    <!-- Products stat -->
    <div class="bg-white p-5 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 relative z-10">
      <div class="flex items-start mb-4">
        <div class="w-10 h-10 rounded-lg bg-[#16782d]/10 flex items-center justify-center mr-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
          </svg>
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-500">Produk Aktif</h3>
          <p class="text-2xl font-bold text-gray-800">{{ $totalProducts }}</p>
        </div>
      </div>
      <div class="flex items-center text-[#16782d] text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
        <span>5% dari bulan lalu</span>
      </div>
    </div>
  </div>
  
  <!-- Quick actions -->
  <div class="bg-white p-6 rounded-xl shadow-md relative z-10 mb-8">
    <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
      <a href="{{ route('admin.products.create') }}" class="block">
        <div class="bg-gray-50 hover:bg-[#16782d] hover:text-white group p-4 rounded-lg flex flex-col items-center justify-center transition-colors duration-300 cursor-pointer">
          <div class="w-10 h-10 rounded-full bg-[#16782d]/10 group-hover:bg-white flex items-center justify-center mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#16782d] group-hover:text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
          </div>
          <span class="text-sm font-medium">Tambah Produk</span>
        </div>
      </a>
      
      
      <a href="{{ route('admin.orders.index') }}" class="block">
        <div class="bg-gray-50 hover:bg-[#16782d] hover:text-white group p-4 rounded-lg flex flex-col items-center justify-center transition-colors duration-300 cursor-pointer">
          <div class="w-10 h-10 rounded-full bg-[#16782d]/10 group-hover:bg-white flex items-center justify-center mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#16782d] group-hover:text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </div>
          <span class="text-sm font-medium">Kelola Pesanan</span>
        </div>
      </a>
      
      
      <!-- Manage Users -->
      <a href="{{ route('admin.users.master-member') }}" class="block">
      <div class="bg-gray-50 hover:bg-[#16782d] hover:text-white group p-4 rounded-lg flex flex-col items-center justify-center transition-colors duration-300 cursor-pointer">
        <div class="w-10 h-10 rounded-full bg-[#16782d]/10 group-hover:bg-white flex items-center justify-center mb-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#16782d] group-hover:text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        </div>
        <span class="text-sm font-medium">Kelola Pengguna</span>
      </div>
      </a> 
      
      <!-- Reports -->
      <a href="{{ route('admin.orders.index') }}" class="block">
      <div class="bg-gray-50 hover:bg-[#16782d] hover:text-white group p-4 rounded-lg flex flex-col items-center justify-center transition-colors duration-300 cursor-pointer">
        <div class="w-10 h-10 rounded-full bg-[#16782d]/10 group-hover:bg-white flex items-center justify-center mb-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#16782d] group-hover:text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <span class="text-sm font-medium">Laporan Order</span>
      </div>
    </a>
      
      <!-- Settings -->
      <a href="{{ route('admin.settings.index') }}" class="block">
      <div class="bg-gray-50 hover:bg-[#16782d] hover:text-white group p-4 rounded-lg flex flex-col items-center justify-center transition-colors duration-300 cursor-pointer">
        <div class="w-10 h-10 rounded-full bg-[#16782d]/10 group-hover:bg-white flex items-center justify-center mb-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#16782d] group-hover:text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </div>
        <span class="text-sm font-medium">Pengaturan</span>
      </div>
      </a>
      
      <!-- Help -->
      <div class="bg-gray-50 hover:bg-[#16782d] hover:text-white group p-4 rounded-lg flex flex-col items-center justify-center transition-colors duration-300 cursor-pointer">
        <div class="w-10 h-10 rounded-full bg-[#16782d]/10 group-hover:bg-white flex items-center justify-center mb-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#16782d] group-hover:text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <span class="text-sm font-medium">Bantuan</span>
      </div>
    </div>
  </div>
  
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
    <!-- Left Column: Recent Activity -->
    <div class="bg-white p-6 rounded-xl shadow-md relative z-10">
      @foreach ($activities as $activity)
        <div class="flex items-start">
          <div class="w-10 h-10 rounded-full bg-[#16782d]/10 flex items-center justify-center mr-4 mt-1">
            @if($activity['type'] === 'order')
              <!-- Icon Order -->
              <svg class="h-5 w-5 text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
            @elseif($activity['type'] === 'user')
              <!-- Icon User -->
              <svg class="h-5 w-5 text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            @elseif($activity['type'] === 'payment')
              <!-- Icon Payment -->
              <svg class="h-5 w-5 text-[#16782d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            @endif
          </div>
          <div>
            <p class="text-gray-800 font-medium">{{ $activity['title'] }}</p>
            <p class="text-gray-500 text-sm">{{ $activity['description'] }}</p>
            <p class="text-gray-400 text-xs mt-1">{{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}</p>
          </div>
        </div>
      @endforeach
    </div>
  
    <!-- Right Column: Visitor Stats Chart -->
    <div class="bg-white p-6 rounded-xl shadow-md relative z-10">
      <h3 class="text-lg font-semibold">Statistik Kunjungan per Negara</h3>
      <canvas id="visitorChart"></canvas>
    </div>
  </div>
  
  <script>
    // Prepare data for the chart
    const visitorStats = @json($visitorStats); // Data dari controller
    
    const labels = visitorStats.map(stat => stat.country); // Nama-nama negara
    const data = visitorStats.map(stat => stat.total); // Jumlah pengunjung
    
    const ctx = document.getElementById('visitorChart').getContext('2d');
    const visitorChart = new Chart(ctx, {
      type: 'bar', // Tipe chart (bar chart)
      data: {
        labels: labels,
        datasets: [{
          label: 'Jumlah Pengunjung',
          data: data,
          backgroundColor: 'rgba(22, 120, 45, 0.6)', // Warna bar
          borderColor: 'rgba(22, 120, 45, 1)', // Warna border bar
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          x: {
            title: {
              display: true,
              text: 'Negara'
            }
          },
          y: {
            title: {
              display: true,
              text: 'Jumlah Pengunjung'
            },
            beginAtZero: true
          }
        }
      }
    });
  </script>
   
@endsection