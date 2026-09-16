<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-4V2W4R81Q7"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-4V2W4R81Q7');
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- JUDUL DINAMIS --}}
    <title>@yield('title', 'MyWowin - Sahabat Hidangan Anda')</title>

    {{-- META DESCRIPTION DINAMIS --}}
    <meta name="description" content="@yield('meta_description', 'Platform resmi member dan agen Wowin Food.')">

    {{-- CANONICAL URL --}}
    <link rel="canonical" href="@yield('canonical_url', url()->current())" />

    {{-- OPEN GRAPH SEO --}}
    <meta property="og:site_name" content="My Wowin" />
    <meta property="og:type" content="@yield('og_type', 'website')" />
    <meta property="og:url" content="@yield('canonical_url', url()->current())" />
    <meta property="og:title" content="@yield('title', 'MyWowin')" />
    <meta property="og:description" content="@yield('meta_description', 'Platform resmi member Wowin.')" />
    <meta property="og:image" content="@yield('meta_image', asset('images/logo-wowin.png'))" /> 

    {{-- TWITTER CARD SEO --}}
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield('title', 'MyWowin')" />
    <meta name="twitter:description" content="@yield('meta_description', 'Platform resmi member Wowin.')" />
    <meta name="twitter:image" content="@yield('meta_image', asset('images/logo-wowin.png'))" />

    {{-- STRUCTURED DATA (SCHEMA.ORG JSON-LD FOR GOOGLE RICH RESULTS) --}}
    @yield('structured_data')

    @yield('head')

    <!-- Import Fonts Google -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Icons: FontAwesome & RemixIcon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Konfigurasi Tailwind Terpadu -->
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
                        sans: ['Montserrat', 'Roboto', 'sans-serif'],
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
<body class="bg-[#fff] font-sans">
    @include('public.layouts.navbar')


    @if(session('success') || session('welcome_back'))
    <div x-data="{ open: true }" x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm relative overflow-hidden">
            <!-- Blob decoration -->
            <div class="absolute -right-16 -top-16 w-32 h-32 bg-purple-400 opacity-20 rounded-full blur-xl"></div>
            <div class="absolute -left-16 -bottom-16 w-32 h-32 bg-indigo-400 opacity-20 rounded-full blur-xl"></div>
            
            <!-- Tombol close -->
            <button @click="open = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <!-- Isi modal -->
            <div class="text-center relative z-10">
                @if(session('welcome_back'))
                    <h2 class="text-xl font-bold mb-1 bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-blue-500">Hello Again! ✨</h2>
                @else
                    <h2 class="text-xl font-bold mb-1 bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-blue-500">Selamat Datang! ✨</h2>
                @endif
                
                <p class="text-gray-700 font-medium mb-2">{{ Auth::user()->nama_lengkap }} 😊</p>
    
               <!-- Gambar Utama (no background) dengan efek "mengambang" -->
                <div class="relative flex justify-center items-center overflow-hidden max-h-40">
                    <img src="{{ asset('images/MKL4.webp') }}" alt="Welcome" class="h-20 object-scale-down z-10">
                    
                    <!-- Badge overlay -->
                    <div class="absolute top-1 right-1 bg-purple-600 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center border-2 border-white shadow-lg z-20">
                        {{ Auth::user()->login_streak }}
                    </div>
                </div>
                <br>
    
                <!-- Points notification -->
                <div class="bg-gradient-to-r from-purple-100 to-indigo-100 rounded-lg p-3 mb-4">
                    <div class="text-sm text-purple-800 font-medium">
                        Hari ini Anda mendapatkan
                    </div>
                    <div class="text-2xl font-extrabold text-purple-700">
                        +{{ Auth::user()->points_today }} Poin!
                    </div>
                    <div class="text-xs text-purple-600 font-medium">
                        Terus tingkatkan login streak Anda! 🌟
                    </div>
                </div>
    
                <!-- Reward info -->
                <div class="bg-gradient-to-r from-yellow-100 to-amber-100 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-center mb-2">
                        <span class="text-xl">🎁</span>
                        <span class="font-bold text-amber-800 mx-1">Hadiah Spesial</span>
                        <span class="text-xl">🎁</span>
                    </div>
                    <p class="text-sm text-amber-800">
                        Login selama 30 hari berturut-turut untuk mendapatkan bundling rewards keren dari Wowin!
                    </p>
                    <p class="text-xs text-amber-700 mt-1 font-semibold">
                        Tinggal {{ 30 - Auth::user()->login_streak }} hari lagi. Semangat!
                    </p>
                </div>
    
                <button @click="open = false" class="w-full py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg text-sm hover:opacity-90 transition-opacity">
                    Lanjutkan 👍
                </button>
            </div>
        </div>
    </div>
    @endif
    
    @if(session('trigger'))
    <div 
        x-data="{ open: true }" 
        x-init="setTimeout(() => open = false, 5000)" 
        x-show="open" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 backdrop-blur-sm"
    >
        <div 
            class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md relative overflow-hidden transition-all duration-300 transform"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-10 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            style="animation: bounce 0.6s;"
        >
            <style>
                @keyframes bounce {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-20px); }
                }
                @keyframes pulse {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.1); }
                    100% { transform: scale(1); }
                }
                @keyframes slideIn {
                    0% { transform: translateX(-100%); }
                    100% { transform: translateX(0); }
                }
                .product-image {
                    animation: pulse 2s infinite;
                }
                .progress-animation {
                    animation: slideIn 0.3s ease-out;
                }
            </style>
            
            <!-- Progress bar for auto-close -->
            <div class="absolute top-0 left-0 right-0 h-1">
                <div 
                    style="background-color: #0e6a24;" 
                    class="h-full progress-animation" 
                    x-data="{ width: '100%' }"
                    x-init="
                        setTimeout(() => { 
                            const interval = setInterval(() => {
                                width = parseInt(width) - 0.5 + '%';
                                if (parseInt(width) <= 0) clearInterval(interval);
                            }, 25);
                        }, 100)
                    "
                    :style="`width: ${width}`"
                ></div>
            </div>
    
            <!-- Product image -->
            <div class="flex justify-center mb-4">
                <div class="product-image relative h-40 w-40 mx-auto">
                    <!-- Placeholder image. Replace src with an actual product image -->
                    <img src="{{ asset('images/wwn-cr.webp') }}" alt="Product image" class="object-contain h-full w-full rounded-lg shadow-md"/>
                    
                    <!-- Success check icon on top of image -->
                    <div class="absolute -right-3 -bottom-3 bg-white rounded-full p-2 shadow-lg" style="animation: pulse 1.5s infinite">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" style="color: #0e6a24;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>
    
            <!-- Close button -->
            <button @click="open = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
    
            <!-- Content -->
            <div class="text-center">
                <h2 class="text-2xl font-bold mb-2" style="color: #0e6a24; animation: pulse 1.5s 1;">Produk Ditambahkan!</h2>
                <p class="text-gray-700 mb-6">Produk berhasil ditambahkan ke keranjang Anda.</p>
    
                <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('carts.index') }}" 
                       style="background-color: #0e6a24; animation: pulse 2s infinite;" 
                       class="px-5 py-2 text-white font-medium rounded-lg transition-all duration-300 shadow-md hover:shadow-lg focus:outline-none transform hover:scale-105">
                        <div class="flex items-center justify-center text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Beli Sekarang
                        </div>
                    </a>
                    <button @click="window.location.href='{{ route('products') }}'; open = false"
                    class="px-5 py-2 bg-gray-100 text-gray-800 font-medium rounded-lg hover:bg-gray-200 transition-all duration-300 shadow-sm hover:shadow focus:outline-none transform hover:scale-105 text-sm">
                Lanjutkan Belanja
            </button>
            
                </div>
            </div>
    
            <!-- Decorative line at bottom with animation -->
            <div class="absolute bottom-0 left-0 right-0 h-2" style="background-color: #0e6a24; animation: slideIn 0.8s ease-out;"></div>
        </div>
    </div>
    @endif


    <div class="container mx-auto mt-5">
        @yield('content')
    </div>
    <br>
    @include('public.layouts.footer') <!-- Tambahkan Footer di Sini -->
<style>
@keyframes wiggle {
  0%, 100% { transform: rotate(-3deg); }
  50% { transform: rotate(3deg); }
}

.animate-wiggle {
  animation: wiggle 2s ease-in-out infinite;
}
</style>
<!-- Floating WhatsApp Icon -->
<div id="whatsapp-icon"
     class="fixed bottom-4 right-1 z-50 cursor-grab transition-all duration-300 hover:scale-105"
     title="Hubungi via WhatsApp">
    <a href="https://api.whatsapp.com/send?phone=62812106600&text=Hai%2C%20Min%20Wow%21%20Saya%20butuh%20bantuan%20terkait%20pesanan%20saya." target="_blank">
        <img src="{{ asset('images/shbt2.webp') }}" 
             alt="Chat WhatsApp"
             class="w-16 h-16 sm:w-[200px] sm:h-[160px] object-contain drop-shadow-xl pointer-events-none animate-wiggle" />
    </a>
</div>


@yield('scripts')
<!-- Script drag WhatsApp icon -->
<script>
    const waIcon = document.getElementById('whatsapp-icon');
    let isDragging = false;
    let offsetX = 0;
    let offsetY = 0;

    waIcon.addEventListener('mousedown', function(e) {
        // Cegah klik pada <a> langsung nyambung ke WhatsApp
        if (e.target.tagName.toLowerCase() === 'a' || e.target.tagName.toLowerCase() === 'img') {
            e.preventDefault();
        }

        isDragging = true;
        offsetX = e.clientX - waIcon.getBoundingClientRect().left;
        offsetY = e.clientY - waIcon.getBoundingClientRect().top;
        waIcon.style.cursor = 'grabbing';
        document.body.style.userSelect = 'none';
    });

    document.addEventListener('mousemove', function(e) {
        if (!isDragging) return;

        waIcon.style.left = (e.clientX - offsetX) + 'px';
        waIcon.style.top = (e.clientY - offsetY) + 'px';
        waIcon.style.bottom = 'auto';
        waIcon.style.right = 'auto';
    });

    document.addEventListener('mouseup', function() {
        if (isDragging) {
            isDragging = false;
            waIcon.style.cursor = 'grab';
            document.body.style.userSelect = 'auto';
        }
    });
</script>


</body>
</html>
