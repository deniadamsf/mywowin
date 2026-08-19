<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | My Wowin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: '#0e6a24',
                            secondary: '#fff301',
                            accent: '#47d107',
                            dark: '#0a4d1a',
                            light: '#e6f7ea'
                        }
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                        display: ['"Playfair Display"', 'serif']
                    },
                    boxShadow: {
                        'navbar': '0 4px 12px rgba(0, 0, 0, 0.08)',
                        'dropdown': '0 10px 25px rgba(0, 0, 0, 0.1)'
                    }
                }
            }
        }
    </script>    
</head>
<body class="font-sans bg-white text-gray-800">
    <!-- Announcement Bar -->
    {{-- <div class="bg-brand-secondary text-brand-dark text-xs md:text-sm py-2 px-4 text-center font-medium">
        Dapatkan diskon 25% untuk pembelian pertama dengan kode: <span class="font-semibold">WOWIN25</span>
    </div> --}}

    <!-- Navbar -->
    <nav id="main-nav" class="sticky top-0 w-full bg-white shadow-navbar z-50 transition-all duration-300">
        <div class="container mx-auto">
            <!-- Upper Nav - Logo & Main Actions -->
            <div class="flex items-center justify-between px-4 md:px-6 py-4">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 group">
                    <div class="bg-brand-primary p-2 rounded-lg transition-all duration-300 group-hover:bg-brand-dark">
                        <img src="{{ asset('images/wwn-cr.webp') }}" alt="Wowin Food Logo" class="h-8 w-auto">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-display text-xl text-brand-primary font-bold tracking-wide leading-tight group-hover:text-brand-dark transition-colors duration-300 text-center">MyWowin</span>
                        <span class="text-xs text-gray-500 -mt-1">Sahabat Hidangan Anda</span>
                    </div>
                </a>
                
               <!-- Center - Search on Desktop -->
<div class="hidden lg:block flex-grow max-w-xl mx-4">
    @auth
        <!-- Jika user sudah login, tampilkan form pencarian -->
        <form class="relative w-full" action="{{ route('products') }}" method="GET">
            <input 
                type="text" 
                name="search" 
                placeholder="Cari produk sehat favorit Anda..." 
                value="{{ request('search') }}"
                class="w-full pl-12 pr-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:border-[#16782d] focus:ring-2 focus:ring-[#16782d]/20 focus:outline-none transition-all duration-200">
            
            <input type="hidden" name="search_by" value="nama_produk">
            
            <button type="submit" class="absolute left-0 top-0 h-full px-3 flex items-center justify-center text-gray-400 hover:text-[#16782d] transition-colors">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    @else
        <!-- Jika belum login, redirect ke login saat klik -->
        <div class="relative w-full">
            <input 
                type="text" 
                placeholder="Cari produk sehat favorit Anda..." 
                class="w-full pl-12 pr-4 py-2.5 text-sm rounded-xl border border-gray-200 cursor-pointer focus:outline-none"
                onclick="window.location.href='{{ route('login') }}'"
                readonly
            >
            <button type="button" class="absolute left-0 top-0 h-full px-3 flex items-center justify-center text-gray-400 hover:text-[#16782d] transition-colors"
                onclick="window.location.href='{{ route('login') }}'">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>
    @endauth
</div>


                
                <!-- Right Actions - Desktop -->
                <div class="hidden lg:flex items-center gap-6">
                    <!-- Cart -->
                    @php
    $cartCount = Auth::check() ? \App\Models\Cart::where('user_id', Auth::id())->sum('quantity') : 0;
        @endphp

        <a href="{{ route('carts.index') }}" class="relative flex items-center gap-2 text-gray-600 hover:text-brand-primary transition-all duration-200">
            <div class="relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2m0 0L6 15h12l1.6-8H5.4ZM16 21a1 1 0 100-2 1 1 0 000 2Zm-8 0a1 1 0 100-2 1 1 0 000 2Z"/>
                </svg>
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                    {{ $cartCount }}
                </span>
            </div>
            <span class="text-sm font-medium">Keranjang</span>
        </a>
                    
                    <!-- Auth Actions -->
                    <div class="flex items-center gap-3">
                        @if(Auth::check())
                            {{-- User sudah login --}}
                            <span class="text-sm font-medium text-gray-700">
                                Halo, {{ Auth::user()->nama_lengkap }}
                            </span>
                            <a href="{{ route('logout') }}" 
                               class="text-sm font-medium bg-red-500 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                Logout
                            </a>
                        @else
                            {{-- Belum login --}}
                            <a href="{{ route('auth.register') }}" 
                               class="text-sm font-medium text-brand-primary hover:text-brand-dark transition-colors duration-200">
                                Daftar
                            </a>
                            <a href="{{ route('login') }}" 
                               class="text-sm font-medium bg-brand-primary hover:bg-brand-dark text-white px-6 py-2.5 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                Masuk
                            </a>
                        @endif
                    </div>
                    
                </div>
                
               <!-- Right Actions - Mobile -->
                <div class="flex items-center gap-4 lg:hidden">
                    <!-- Cart Icon Mobile -->
                    <a href="{{ route('carts.index') }}" class="relative text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2m0 0L6 15h12l1.6-8H5.4ZM16 21a1 1 0 100-2 1 1 0 000 2Zm-8 0a1 1 0 100-2 1 1 0 000 2Z"/>
                        </svg>
                        @if ($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    
                    <!-- Menu Toggle -->
                    <button id="menu-toggle" class="text-gray-700 focus:outline-none">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Lower Nav - Navigation Links (Desktop Only) -->
            <div class="hidden lg:block border-t border-gray-100">
                <div class="container mx-auto px-6">
                    <ul class="flex items-center justify-center space-x-10 font-medium text-gray-600">
                        <li>
                            <a href="/" class="nav-link flex items-center gap-2 py-3 border-b-2 border-transparent hover:text-brand-primary hover:border-brand-primary transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="{{ Auth::check() ? url('products') : url('/login') }}" 
                            class="nav-link flex items-center gap-2 py-3 border-b-2 border-transparent hover:text-brand-primary hover:border-brand-primary transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Produk
                            </a>
                        </li>
                        <li>
                            <a href="{{ Auth::check() ? url('/members') : url('/login') }}" 
                               class="nav-link flex items-center gap-2 py-3 border-b-2 border-transparent hover:text-brand-primary hover:border-brand-primary transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Membership
                            </a>
                        </li>
                        
                        <a href="{{ Auth::check() ? url('/trackings') : url('/login') }}" 
                            class="nav-link flex items-center gap-2 py-3 border-b-2 border-transparent hover:text-brand-primary hover:border-brand-primary transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                Pesanan
                            </a>
                        </li>
                        <li>
                            <a href="{{ Auth::check() ? url('/promo') : url('/login') }}" class="nav-link flex items-center gap-2 py-3 border-b-2 border-transparent hover:text-brand-primary hover:border-brand-primary transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Promo
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contacts') }}" class="nav-link flex items-center gap-2 py-3 border-b-2 border-transparent hover:text-brand-primary hover:border-brand-primary transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Kontak
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('artikels') }}" class="nav-link flex items-center gap-2 py-3 border-b-2 border-transparent hover:text-brand-primary hover:border-brand-primary transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14v16z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 10h10M7 14h5" />
                                </svg>
                                Artikel
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rewards.index') }}" class="nav-link flex items-center gap-2 py-3 border-b-2 border-transparent hover:text-brand-primary hover:border-brand-primary transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20 12v7a2 2 0 01-2 2H6a2 2 0 01-2-2v-7m16 0H4m16 0V8a2 2 0 00-2-2h-3M4 12V8a2 2 0 012-2h3m0 0a2 2 0 114 0m-4 0a2 2 0 104 0M12 12v9" />
                                </svg>
                                Rewards
                            </a>
                        </li>                        
                        
                    </ul>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white shadow-dropdown">
            <!-- Mobile Search -->
<div class="p-4 border-b border-gray-100 lg:hidden">
    @auth
        <form class="relative w-full" action="{{ route('products') }}" method="GET">
            <input 
                type="text" 
                name="search"
                placeholder="Cari produk sehat favorit Anda..." 
                value="{{ request('search') }}"
                class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-accent/30 transition-all duration-200">
            
            <input type="hidden" name="search_by" value="nama_produk">
            
            <button type="submit" class="absolute left-0 top-0 h-full px-3 flex items-center justify-center text-gray-400">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    @else
        <div class="relative w-full">
            <input 
                type="text" 
                placeholder="Cari produk sehat favorit Anda..." 
                class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 rounded-lg focus:bg-white cursor-pointer focus:outline-none"
                onclick="window.location.href='{{ route('login') }}'"
                readonly
            >
            <button type="button" class="absolute left-0 top-0 h-full px-3 flex items-center justify-center text-gray-400"
                onclick="window.location.href='{{ route('login') }}'">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>
    @endauth
</div>

            
            <!-- Mobile Nav Links -->
            <nav class="px-4 py-2">
                <ul class="divide-y divide-gray-100">
                    <li>
                        <a href="/" class="flex items-center justify-between py-3 hover:text-brand-primary transition-colors">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <span class="font-medium">Beranda</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ Auth::check() ? url('products') : url('/login') }}" class="flex items-center justify-between py-3 hover:text-brand-primary transition-colors">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <span class="font-medium">Produk</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ Auth::check() ? url('/members') : url('/login') }}"  class="flex items-center justify-between py-3 hover:text-brand-primary transition-colors">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium">Membership</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ Auth::check() ? url('/trackings') : url('/login') }}" class="flex items-center justify-between py-3 hover:text-brand-primary transition-colors">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <span class="font-medium">Pesanan</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ Auth::check() ? url('/promo') : url('/login') }}" class="flex items-center justify-between py-3 hover:text-brand-primary transition-colors">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <span class="font-medium">Promo</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contacts') }}" class="flex items-center justify-between py-3 hover:text-brand-primary transition-colors">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">Kontak</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <!-- Mobile Auth & Cart -->
<div class="p-4 bg-gray-50 border-t border-gray-200 lg:hidden">
    @if(Auth::check())
        {{-- Jika user sudah login --}}
        <div class="mb-3 text-sm text-gray-700">
            Halo, <strong>{{ Auth::user()->nama_lengkap }}</strong>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <!-- Keranjang -->
            @php
                $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
            @endphp
            <a href="{{ route('carts.index') }}" class="relative flex items-center justify-center bg-white border border-gray-200 text-gray-800 py-3 rounded-lg text-sm font-medium hover:bg-gray-100 transition">
                <div class="relative flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2m0 0L6 15h12l1.6-8H5.4ZM16 21a1 1 0 100-2 1 1 0 000 2Zm-8 0a1 1 0 100-2 1 1 0 000 2Z"/>
                    </svg>
                    <span>Keranjang</span>
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </div>
            </a>

            <!-- Logout -->
            <a href="{{ route('logout') }}"
               class="text-center bg-red-500 hover:bg-red-700 text-white py-3 rounded-lg text-sm font-medium transition-all duration-200 shadow">
                Logout
            </a>
        </div>
    @else
        {{-- Jika belum login --}}
        <div class="grid grid-cols-2 gap-3">
           
            <a href="{{ route('login') }}"
               class="text-center bg-brand-primary text-white py-3 rounded-lg text-sm font-medium hover:bg-brand-dark transition-colors duration-200">
                Masuk
            </a>
             <a href="{{ route('auth.register') }}"
               class="text-center bg-white border border-gray-200 text-gray-800 py-3 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors duration-200">
                Daftar
            </a>
        </div>
    @endif
</div>

        </div>
    </nav>

    <!-- Scripts -->
    <script>
        // Mobile Menu Toggle
        document.getElementById("menu-toggle").addEventListener("click", function() {
            document.getElementById("mobile-menu").classList.toggle("hidden");
        });

        // Active Menu Item Highlight
        const navLinks = document.querySelectorAll('.nav-link');
        const currentPath = window.location.pathname;
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === currentPath || (href !== '/' && currentPath.startsWith(href))) {
                link.classList.add('text-brand-primary');
                link.classList.add('border-brand-primary');
            }
        });
        
        // Scroll Effect for Navbar
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('main-nav');
            if (window.scrollY > 30) {
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('shadow-lg');
            }
        });
    </script>
</body>
</html>