@extends('public.layouts.app')

@section('title', 'WOWINFood - Membership Profile')
@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<meta name="description" content="WOWINFood - Temukan berbagai produk berkualitas dengan harga terbaik">
<!-- Font Poppins -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<!-- Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection

@section('content')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 800, // Durasi animasi (ms)
    once: true,    // Animasi hanya jalan sekali saat di-scroll
  });
</script>
@php
    $user = Auth::user();
@endphp

<div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
       <!-- Header -->
<div class="bg-gradient-to-r from-green-700 to-green-900 py-6 md:py-8 px-4 md:px-8" data-aos="fade-down">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between max-w-7xl mx-auto">
        <div class="text-center md:text-left">
            <h1 class="text-2xl md:text-3xl font-bold text-white uppercase tracking-wide">Profil Membership</h1>
            <p class="text-green-100 mt-1 md:mt-2 text-sm md:text-lg">Selamat datang di WOWINFood Membership</p>
        </div>

        @if(isset($user) && $user->membership)
        @php
            $levels = [
                'bronze' => ['icon' => '🥉', 'color' => 'bg-white text-orange-900', 'label' => 'Bronze'],
                'silver' => ['icon' => '🥈', 'color' => 'bg-gray-300 text-gray-800', 'label' => 'Silver'],
                'gold' => ['icon' => '🥇', 'color' => 'bg-yellow-400 text-yellow-900', 'label' => 'Gold'],
                'platinum' => ['icon' => '💎', 'color' => 'bg-blue-300 text-blue-900', 'label' => 'Platinum'],
                'diamond' => ['icon' => '🔷', 'color' => 'bg-indigo-500 text-white', 'label' => 'Diamond'],
            ];
            $currentLevel = strtolower($user->membership->level_membership);
        @endphp

        <div class="mt-6 lg:mt-0 bg-white/10 backdrop-blur-md py-4 px-4 md:px-6 rounded-2xl border border-white/20 shadow-xl overflow-x-auto">
            <div class="flex flex-row items-center justify-between min-w-[320px] md:min-w-0 space-x-2 md:space-x-6">
                @foreach($levels as $key => $level)
                    @php
                        $isActive = $key === $currentLevel;
                    @endphp
                    <div class="flex flex-col items-center transition-all duration-300 flex-1">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center text-xl md:text-2xl font-bold transition-all duration-500 {{ $isActive ? $level['color'] . ' shadow-[0_0_15px_rgba(255,255,255,0.5)] scale-110' : 'bg-white/20 text-white/30 border border-white/10' }}">
                            {{ $level['icon'] }}
                        </div>
                        
                        <span class="mt-2 text-[10px] md:text-xs uppercase tracking-tighter md:tracking-normal {{ $isActive ? 'text-white font-bold' : 'text-white/40 font-medium' }}">
                            {{ $level['label'] }}
                        </span>

                        @if($isActive)
                            <div class="w-1 h-1 bg-white rounded-full mt-1 animate-pulse lg:hidden"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

        
        <!-- Main Content -->
        <div class="flex flex-col lg:flex-row">
            <!-- Left Side: Profile Information -->
            <div class="lg:w-2/5 xl:w-1/3 border-r border-gray-200" data-aos="fade-right" data-aos-delay="200">
                <!-- Profile Section -->
                <div class="flex flex-col items-center pt-10 pb-8 border-b border-gray-200 px-6">
                    @if(isset($user))
                        <!-- Tampilan Jika Sudah Login -->
                        <div class="relative">
                            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg">
                                <img src="{{ asset('storage/'.$user->foto_profile) }}" class="w-full h-full object-cover" alt="{{ $user->nama_lengkap }}">
                            </div>
                            @if($user->membership)
                            <div class="absolute -bottom-3 -right-3 bg-[#16782d] text-white text-sm font-medium px-4 py-2 rounded-full shadow-md">
                                {{ $user->membership->level_membership }}
                            </div>
                            @endif
                        </div>
                        <h2 class="mt-6 text-2xl font-semibold text-gray-800">{{ $user->nama_lengkap }}</h2>
                        <p class="text-gray-500 flex items-center gap-2 mt-1">
                            <i class="bi bi-envelope"></i> {{ $user->email }}
                        </p>
                        
                        <!-- Login Streak & Points Section -->
                        <div class="w-full mt-6 grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-xl p-4 text-center">
                                <div class="text-blue-700 text-lg font-semibold">{{ $user->login_streak ?? 0 }}/30</div>
                                <div class="text-blue-500 text-sm">Login Streak</div>
                                <div class="mt-2 h-2 bg-blue-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600" style="width: {{ ($user->login_streak / 30) * 100 }}%"></div>
                                </div>
                            </div>
                            <div class="bg-green-50 rounded-xl p-4 text-center">
                                <div class="text-green-700 text-lg font-semibold">{{ $user->total_points ?? $user->points_today ?? 0 }}</div>
                                <div class="text-green-500 text-sm">Total Points</div>
                                <div class="mt-2 flex justify-center">
                                    <i class="bi bi-star-fill text-yellow-500"></i>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Tampilan Jika Belum Login (Guest) -->
                        <div class="relative">
                            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-100 flex items-center justify-center">
                                <i class="bi bi-person text-gray-400" style="font-size: 4rem;"></i>
                            </div>
                        </div>
                        <h2 class="mt-6 text-2xl font-semibold text-gray-800">Pengunjung</h2>
                        <p class="text-gray-500 flex items-center gap-2 mt-1">
                            Anda Belum Login
                        </p>
                        <a href="{{ route('login') }}" class="mt-6 w-full text-center bg-[#16782d] hover:bg-green-800 text-white font-bold py-2.5 px-4 rounded-lg transition duration-300">
                            Masuk ke Akun
                        </a>
                    @endif
                </div>
                
                <!-- Membership Details -->
                <div class="px-8 py-6">
                    @if(isset($user) && $user->membership)
                        <!-- BANNER STATUS ACC -->
                        @if($user->membership->status_acc == 'pending')
                            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-start shadow-sm">
                                <i class="bi bi-hourglass-split text-yellow-600 text-xl mr-3 mt-0.5"></i>
                                <div>
                                    <h4 class="text-yellow-800 font-bold">Menunggu Persetujuan Admin</h4>
                                    <p class="text-yellow-700 text-sm mt-1 leading-snug">Akun mitra Anda sedang ditinjau. Anda akan mendapatkan harga khusus grosir setelah di-ACC.</p>
                                </div>
                            </div>
                        @elseif($user->membership->status_acc == 'rejected')
                            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start shadow-sm">
                                <i class="bi bi-x-circle text-red-600 text-xl mr-3 mt-0.5"></i>
                                <div class="flex-1">
                                    <h4 class="text-red-800 font-bold">Kemitraan Dinonaktifkan</h4>
                                    <p class="text-red-700 text-sm mt-1 leading-snug">Pengajuan Anda ditolak atau dinonaktifkan Admin. Anda tetap dapat berbelanja sebagai Pelanggan Reguler.</p>
                                    
                                    <!-- TOMBOL PENGAJUAN ULANG -->
                                    <form action="{{ route('public.memberships.reapply') }}" method="POST" class="mt-3">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Ajukan ulang status kemitraan Anda?')" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2 px-4 rounded-lg transition duration-200 shadow-sm">
                                            <i class="bi bi-arrow-clockwise mr-1"></i> Ajukan Ulang Kemitraan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <!-- SEMBUNYIKAN KARTU JIKA BELUM ACC -->
                        @if($user->membership->status_acc == 'approved')
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Membership</h3>
                        <div class="space-y-5">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama Toko</label>
                                <div class="mt-1 flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <i class="bi bi-shop mr-3 text-[#16782d]"></i>
                                    <span class="text-gray-800">{{ $user->membership->nama_toko }}</span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama Sales</label>
                                <div class="mt-1 flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <i class="bi bi-person-badge mr-3 text-[#16782d]"></i>
                                    <span class="text-gray-800">{{ $user->membership->nama_sales }}</span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nomor Telepon</label>
                                <div class="mt-1 flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <i class="bi bi-telephone mr-3 text-[#16782d]"></i>
                                    <span class="text-gray-800">{{ $user->membership->no_hp }}</span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Alamat</label>
                                <div class="mt-1 flex p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <i class="bi bi-geo-alt mr-3 mt-1 text-[#16782d]"></i>
                                    <span class="text-gray-800">{{ $user->membership->alamat }}</span>
                                </div>
                            </div>
                            
                            {{-- <div>
                                <label class="text-sm font-medium text-gray-500">Terakhir Upgrade</label>
                                <div class="mt-1 flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <i class="bi bi-calendar-check mr-3 text-[#16782d]"></i>
                                    <span class="text-gray-800">{{ $user->membership->last_upgrade }}</span>
                                </div>
                            </div> --}}
                        </div>
                        
                        <!-- Membership Card -->
                        <div class="mt-8">
                            <div class="relative bg-gradient-to-r from-green-700 to-green-900 p-6 rounded-xl shadow-lg overflow-hidden">
                                <!-- Decorative circles -->
                                <div class="absolute right-0 top-0 -mt-10 -mr-10 w-40 h-40 rounded-full bg-green-500 opacity-20"></div>
                                <div class="absolute left-0 bottom-0 -ml-10 -mb-10 w-40 h-40 rounded-full bg-green-500 opacity-20"></div>
                                
                                <!-- Card content -->
                                <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                                    <!-- Left side - Member info -->
                                    <div>
                                        <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Member ID</p>
                                        <p class="text-white font-bold text-xl mb-4">{{ $user->id ?? 'N/A' }}</p>
                                        <p class="text-white font-semibold">{{ $user->nama_lengkap }}</p>
                                        <p class="text-blue-200 text-sm">{{ $user->membership->nama_toko }}</p>
                                    </div>
                                    
                                    <!-- Right side - Level and date -->
                                    <div class="mt-4 sm:mt-0">
                                        <div class="text-right">
                                            <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Level</p>
                                            <p class="text-white font-bold text-xl">{{ $user->membership->level_membership }}</p>
                                        </div>
                                        <div class="mt-4 text-right">
                                            <p class="text-white text-xs">{{ $user->membership->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Brand badge -->
                                <div class="absolute bottom-4 right-6">
                                    <div class="text-white font-bold flex items-center">
                                        <span class="text-sm mr-1">Wowin Food</span>
                                        <i class="bi bi-patch-check-fill text-blue-200"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif <!-- Penutup IF Approved Informasi & Kartu -->
                    @else
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
                                <i class="bi bi-exclamation-circle text-blue-600 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Belum Memiliki Membership</h3>
                            <p class="mt-2 text-gray-500 max-w-md mx-auto">Anda belum terdaftar sebagai member. Silakan hubungi tim penjualan kami untuk mendaftar.</p>
                            <div class="mt-6">
                                <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#16782d] hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300">
                                    Daftar Membership
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

<!-- Right Side: Membership Benefits -->
<div class="lg:w-3/5 xl:w-2/3 bg-gradient-to-br from-gray-50 to-gray-100" data-aos="fade-left" data-aos-delay="400">
    <div class="p-6 lg:p-8">
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-6" data-aos="zoom-in">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-11 w-11 rounded-xl bg-gradient-to-br from-[#16782d] to-[#0e4d1d] flex items-center justify-center shadow-lg">
                    <i class="bi bi-gem text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl lg:text-2xl font-bold text-gray-800 leading-tight">Keuntungan Membership</h2>
                    <p class="text-xs text-gray-500 mt-0.5">MyWowin Partnership Program</p>
                </div>
            </div>
        </div>
        
        <!-- Membership Cards Grid -->
        <div class="flex flex-nowrap overflow-x-auto gap-4 pb-6 snap-x snap-mandatory lg:grid lg:grid-cols-2 xl:grid-cols-3 lg:overflow-x-visible lg:pb-0 no-scrollbar">

           @php
            $membershipLevels = [
                [
                    'name' => 'Bronze',
                    'subtitle' => 'Starter',
                    'icon' => 'bi-award',
                    'color' => 'text-amber-600',
                    'bg' => 'bg-amber-50',
                    'gradient' => 'from-amber-50 to-orange-50',
                    'border' => 'border-amber-200',
                    'min_purchase' => '0',
                    'portal_fee' => '0',
                    'benefits' => [
                        ['qty' => '10', 'disc' => '1.5'],
                        ['qty' => '20', 'disc' => '2'],
                        ['qty' => '30', 'disc' => '2.5'],
                        ['qty' => '50', 'disc' => '4.5'],
                    ]
                ],
                [
                    'name' => 'Silver',
                    'subtitle' => 'Growth',
                    'icon' => 'bi-shield-fill-check',
                    'color' => 'text-gray-600',
                    'bg' => 'bg-gray-100',
                    'gradient' => 'from-gray-50 to-slate-50',
                    'border' => 'border-gray-300',
                    'min_purchase' => '30',
                    'portal_fee' => '50.000',
                    'benefits' => [
                        ['qty' => '10', 'disc' => '1.85'],
                        ['qty' => '20', 'disc' => '2.6'],
                        ['qty' => '30', 'disc' => '3.1'],
                        ['qty' => '50', 'disc' => '5.1'],
                    ]
                ],
                [
                    'name' => 'Gold',
                    'subtitle' => 'Premium',
                    'icon' => 'bi-trophy-fill',
                    'color' => 'text-yellow-600',
                    'bg' => 'bg-yellow-50',
                    'gradient' => 'from-yellow-50 to-amber-50',
                    'border' => 'border-yellow-300',
                    'min_purchase' => '50',
                    'portal_fee' => '100.000',
                    'benefits' => [
                        ['qty' => '10', 'disc' => '1.95'],
                        ['qty' => '20', 'disc' => '3.2'],
                        ['qty' => '30', 'disc' => '3.7'],
                        ['qty' => '50', 'disc' => '5.7'],
                    ]
                ],
                [
                    'name' => 'Platinum',
                    'subtitle' => 'Elite',
                    'icon' => 'bi-gem',
                    'color' => 'text-cyan-600',
                    'bg' => 'bg-cyan-50',
                    'gradient' => 'from-cyan-50 to-blue-50',
                    'border' => 'border-cyan-300',
                    'min_purchase' => '75',
                    'portal_fee' => '200.000',
                    'benefits' => [
                        ['qty' => '10', 'disc' => '2.05'],
                        ['qty' => '20', 'disc' => '3.3'],
                        ['qty' => '30', 'disc' => '4.3'],
                        ['qty' => '50', 'disc' => '6.3'],
                    ]
                ],
                [
                    'name' => 'Diamond', // Di gambar tertulis DIAMON (Typo), disesuaikan menjadi Diamond
                    'subtitle' => 'Ultimate',
                    'icon' => 'bi-stars',
                    'color' => 'text-purple-600',
                    'bg' => 'bg-purple-50',
                    'gradient' => 'from-purple-50 to-pink-50',
                    'border' => 'border-purple-300',
                    'min_purchase' => '100',
                    'portal_fee' => '500.000',
                    'benefits' => [
                        ['qty' => '10', 'disc' => '2.55'],
                        ['qty' => '20', 'disc' => '4.3'],
                        ['qty' => '30', 'disc' => '5.8'],
                        ['qty' => '50', 'disc' => '8.3'],
                    ]
                ],
            ];
            @endphp

            @foreach($membershipLevels as $level)
           <div class="group relative bg-white rounded-2xl border-2 {{ $level['border'] }} 
                    shrink-0 w-[85%] lg:w-full snap-center 
                    hover:border-[#16782d] shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <!-- Gradient Background -->
                <div class="absolute inset-0 bg-gradient-to-br {{ $level['gradient'] }} opacity-30 group-hover:opacity-50 transition-opacity"></div>
                
                <!-- Content -->
                <div class="relative p-4 flex flex-col h-full">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 rounded-xl {{ $level['bg'] }} flex items-center justify-center group-hover:scale-110 transition-transform duration-300 border {{ $level['border'] }}">
                                <i class="bi {{ $level['icon'] }} {{ $level['color'] }} text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $level['subtitle'] }}</p>
                                <h3 class="text-base font-bold text-gray-800 leading-none">{{ $level['name'] }}</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Benefits List -->
                    <div class="space-y-2 flex-grow">
                        @foreach($level['benefits'] as $item)
                        <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-white/80 backdrop-blur-sm border border-gray-100 group-hover:border-green-200 transition-all">
                            <div class="flex items-center space-x-2">
                                <div class="h-1.5 w-1.5 rounded-full bg-[#16782d]"></div>
                                <span class="text-xs text-gray-600 font-medium">{{ $item['qty'] }} Dus</span>
                            </div>
                            <span class="text-xs font-bold text-[#16782d] bg-green-50 px-2 py-0.5 rounded-md">{{ $item['disc'] }}%</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-3 pt-3 border-t border-gray-100 space-y-2">
    <div class="flex items-center justify-between">
        <span class="text-[10px] text-gray-400 font-medium">Min. Belanja:</span>
        <span class="text-[10px] font-bold {{ $level['color'] }}">
            {{ $level['min_purchase'] > 0 ? $level['min_purchase'] . ' Juta' : 'Tanpa Minimal' }}
        </span>
    </div>

    <div class="flex items-center justify-center">
        @if($level['portal_fee'] > 0)
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700">
                <i class="bi bi-door-open-fill mr-1"></i> Biaya Portal: Rp {{ $level['portal_fee'] }}
            </span>
        @else
            <span class="text-[10px] text-green-500 font-medium">Gratis Biaya Portal</span>
        @endif
    </div>
</div>
                </div>

                <!-- Hover Effect Accent -->
                <div class="absolute top-0 right-0 h-20 w-20 bg-[#16782d] opacity-0 group-hover:opacity-5 rounded-bl-full transition-opacity duration-300"></div>
            </div>
            @endforeach

            <!-- Special Benefits Card -->
            <div class="relative overflow-hidden bg-gradient-to-br from-[#16782d] via-[#0e4d1d] to-[#16782d] rounded-2xl shadow-xl border-2 border-green-700/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <!-- Animated Background Effects -->
                <div class="absolute -right-8 -top-8 h-32 w-32 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute -left-8 -bottom-8 h-28 w-28 bg-yellow-400/10 rounded-full blur-2xl"></div>
                
                <!-- Content -->
                <div class="relative z-10 p-4 flex flex-col h-full">
                    <!-- Header -->
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="h-10 w-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                            <i class="bi bi-star-fill text-yellow-300 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-green-200 uppercase tracking-wider">Exclusive</p>
                            <h3 class="text-base font-bold text-white leading-none">Privilege+</h3>
                        </div>
                    </div>
                    
                    <!-- Benefits -->
                    <div class="space-y-3 flex-grow">
                        <div class="flex items-start space-x-2 p-2 rounded-lg bg-white/5 backdrop-blur-sm border border-white/10">
                            <div class="mt-0.5 h-6 w-6 rounded-lg bg-yellow-400/20 flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-truck-front-fill text-yellow-300 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">Gratis Ongkir</p>
                                <p class="text-[10px] text-green-100 leading-tight mt-0.5">Pengiriman terjadwal tanpa biaya</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-2 p-2 rounded-lg bg-white/5 backdrop-blur-sm border border-white/10">
                            <div class="mt-0.5 h-6 w-6 rounded-lg bg-yellow-400/20 flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-calendar-event-fill text-yellow-300 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">Event VIP</p>
                                <p class="text-[10px] text-green-100 leading-tight mt-0.5">Akses eksklusif acara WOWINFood</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-2 p-2 rounded-lg bg-white/5 backdrop-blur-sm border border-white/10">
                            <div class="mt-0.5 h-6 w-6 rounded-lg bg-yellow-400/20 flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-gift-fill text-yellow-300 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">Bonus Poin</p>
                                <p class="text-[10px] text-green-100 leading-tight mt-0.5">Tukar poin dengan hadiah menarik</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Note -->
                    <div class="mt-3 pt-3 border-t border-white/20">
                        <p class="text-[9px] text-green-200 text-center italic">
                            *Berlaku untuk semua level membership
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Info Banner -->
        <div class="mt-6 p-4 bg-white rounded-xl border border-gray-200 shadow-sm" data-aos="fade-up">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="bi bi-info-circle-fill text-blue-600 text-sm"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-600 leading-relaxed">
                        <span class="font-semibold text-gray-800">Informasi Penting:</span> Semakin tinggi level membership Anda, semakin besar diskon yang didapatkan. Pembelian dalam jumlah besar otomatis mendapat diskon maksimal sesuai tier.
                    </p>
                </div>
            </div>
        </div>

 <!-- Current Status & Progress -->
                    @if(isset($user) && $user->membership)
                        <!-- Current Status & Next Level -->
                        <div class="mt-12 bg-white p-6 rounded-xl shadow-md border border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-800 mb-6">Status & Progress</h3>
                            
                            <!-- Login Streak -->
                            <div class="mb-8">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-medium text-gray-700">Login Streak</h4>
                                    <span class="text-sm font-medium text-green-600">{{ $user->login_streak ?? 0 }}/30 hari</span>
                                </div>
                                <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-green-500 rounded-full" style="width: {{ ($user->login_streak / 30) * 100 }}%"></div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Login setiap hari untuk mendapatkan bonus poin dan hadiah spesial</p>
                            </div>
                            
                            <!-- Point Stats -->
                            <div class="mb-8">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-medium text-gray-700">Total Poin Aktif</h4>
                                    <span class="text-sm font-medium text-blue-600">{{ $user->total_points ?? $user->points_today ?? 0 }} poin</span>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                    <div class="flex items-center">
                                        <div class="p-3 bg-blue-100 rounded-full">
                                            <i class="bi bi-award text-blue-700"></i>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-gray-700">Kumpulkan poin lebih banyak dengan melakukan transaksi dan aktivitas di WOWINFood</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        <!-- SEMBUNYIKAN PROGRES BELANJA JIKA BELUM ACC -->
                        @if($user->membership->status_acc == 'approved')
                        <div class="mt-12 bg-white p-6 rounded-xl shadow-md border border-gray-200" data-aos="fade-up">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-purple-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" />
            </svg>
            Progres Membership
        </h3>
    </div>

    @php
        // 1. AMBIL TOTAL BELANJA REAL DARI DATABASE (Logika Superadmin)
        $totalBelanja = $user->orders()
            ->where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total'); 

        $currentLevel = $user->membership->level_membership;
        
        // 2. Definisi Tiers (Harus sama dengan di Controller Superadmin)
        $tiers = [
            'bronze'    => ['nama' => 'Bronze',   'min' => 0,          'max' => 5000000],
            'silver'    => ['nama' => 'Silver',   'min' => 5000000,    'max' => 10000000],
            'gold'      => ['nama' => 'Gold',     'min' => 10000000,   'max' => 50000000],
            'platinum'  => ['nama' => 'Platinum', 'min' => 50000000,   'max' => 150000000],
            'diamond'   => ['nama' => 'Diamond',  'min' => 150000000,  'max' => 400000000],
        ];
        $tierOrder = ['bronze', 'silver', 'gold', 'platinum', 'diamond'];
        $currentLevelKey = strtolower($currentLevel ?? 'bronze');

        $nextLevel = '';
        $targetBerikutnya = 0;
        $progressPercent = 0;
        $kekurangan = 0;

        // 3. Hitung Progres Level
        $currentKeyIndex = array_search($currentLevelKey, $tierOrder);
        if ($currentKeyIndex !== false && $currentKeyIndex < (count($tierOrder) - 1)) {
            $nextLevelKey = $tierOrder[$currentKeyIndex + 1];
            $nextTier = $tiers[$nextLevelKey];
            
            $nextLevel = $nextTier['nama'];
            $targetBerikutnya = $nextTier['min'];
            $awalRange = $tiers[$currentLevelKey]['min'];

            $range = $targetBerikutnya - $awalRange;
            $dicapai = $totalBelanja - $awalRange;
            
            $progressPercent = ($range > 0) ? ($dicapai / $range) * 100 : 0;
            $progressPercent = max(0, min(100, $progressPercent));
            $kekurangan = $targetBerikutnya - $totalBelanja;
        }
    @endphp

    @if($currentLevel == 'Diamond' || $nextLevel == '')
        <div class="text-center bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md">
            <p class="font-bold">🎉 Selamat!</p>
            <p>Anda telah mencapai level membership tertinggi: Diamond.</p>
        </div>
    @else
        <div class="space-y-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end text-sm gap-2">
                <div class="font-medium text-gray-700">
                    Belanja Bulan Ini: 
                    <span class="font-bold text-purple-700">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</span>
                </div>
                <div class="text-gray-500 text-xs md:text-sm italic">
                    Target {{ $nextLevel }}: 
                    <span class="font-semibold text-gray-700">Rp {{ number_format($targetBerikutnya, 0, ',', '.') }}</span>
                </div>
            </div>
            
            {{-- Progress Bar Beranimasi --}}
            <div class="w-full bg-gray-100 rounded-full h-5 p-1 shadow-inner relative overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-800 h-full rounded-full text-center text-white text-[10px] font-bold leading-3 flex items-center justify-center transition-all duration-[2000ms] ease-out shadow-md" 
                     style="width: {{ $progressPercent }}%;">
                    {{ round($progressPercent) }}%
                </div>
            </div>
            
            <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                <span>Level: <span class="text-purple-600">{{ $currentLevel }}</span></span>
                <span>Menuju: <span class="text-purple-600">{{ $nextLevel }}</span></span>
            </div>

            <div class="mt-4 flex items-center p-3 bg-purple-50 rounded-lg border border-purple-100 border-dashed">
                <i class="bi bi-info-circle-fill text-purple-600 mr-3"></i>
                <span class="text-sm text-gray-700">
                    Beli <strong>Rp {{ number_format(max(0, $kekurangan), 0, ',', '.') }}</strong> lagi untuk naik ke level <strong>{{ $nextLevel }}</strong>
                </span>
            </div>
        </div>
    @endif
</div>
                        @endif <!-- Penutup IF Approved Progres Membership -->
   
                        <!-- Call to Action -->
   
                        <!-- Call to Action -->
                        <div class="mt-8 bg-gradient-to-r from-green-700 to-green-900 p-6 rounded-xl shadow-md text-center">
                            <h3 class="text-xl font-bold text-white mb-2">Butuh Bantuan?</h3>
                            <p class="text-green-100 mb-4">Tim support kami siap membantu Anda 24/7</p>
                            <a href="https://api.whatsapp.com/send?phone=62812106600&text=Hai%2C%20Min%20Wow%21%20Saya%20butuh%20bantuan%20terkait%20pesanan%20saya" class="inline-block bg-white text-green-800 font-semibold py-3 px-6 rounded-lg hover:bg-green-50 transition duration-300">
                                Hubungi Kami
                            </a>
                        </div>
                    @else
                        <!-- Call to Action for Non-Members -->
                        <div class="mt-10 bg-gradient-to-br from-blue-50 to-green-50 p-8 rounded-xl shadow-md border border-green-200">
                            <h3 class="text-xl font-bold text-green-800 mb-4">Bergabunglah Sekarang!</h3>
                            <p class="text-gray-700 mb-6">Nikmati semua keuntungan membership eksklusif WOWINFood dan tingkatkan pengalaman belanja Anda.</p>
                            
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="#" class="bg-[#16782d] hover:bg-green-800 text-white font-bold py-3 px-6 rounded-lg text-center transition duration-300 ease-in-out flex-1">
                                    Daftar Membership
                                </a>
                                <a href="#" class="bg-white border border-green-600 hover:bg-green-50 text-green-700 font-bold py-3 px-6 rounded-lg text-center transition duration-300 ease-in-out flex-1">
                                    Pelajari Benefit
                                </a>
                            </div>
                            
                            <p class="text-center text-gray-600 mt-6 flex items-center justify-center gap-2">
                                <i class="bi bi-shield-check text-green-600"></i>
                                Hubungi sales kami untuk informasi lebih lanjut
                            </p>
                        </div>
                    @endif
                </div>
    </div>
</div>
                    </div>
                    
                   
                        </div>
                     
            </div>
        </div>
    </div>
</div>
@endsection