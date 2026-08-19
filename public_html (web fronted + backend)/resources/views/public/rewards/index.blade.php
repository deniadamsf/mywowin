@extends('public.layouts.app')

@section('title', 'WOWINFood - Rewards Program')

@section('head')
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
<!-- Hero Section - Simplified with better spacing -->
<div class="bg-gradient-to-r from-[#0b5c20] to-[#16782d] py-10">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="md:w-1/2">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">WOWINFood Rewards</h1>
                <p class="text-gray-100 text-base mb-5">Nikmati berbagai hadiah menarik dari usaha kuliner terbaik dengan menukarkan poin Anda.</p>
                <div class="flex flex-wrap gap-3">
                    <a href="#rewards-section" class="bg-white text-[#16782d] px-5 py-2.5 rounded-lg font-medium hover:bg-gray-100 transition-all shadow-md flex items-center">
                        <i class="fas fa-gift mr-2"></i> Lihat Rewards
                    </a>
                    <a href="#" id="caraKerjaButton" class="bg-transparent border border-white text-white px-5 py-2.5 rounded-lg font-medium hover:bg-white hover:text-[#16782d] transition-all flex items-center">
                        <i class="fas fa-info-circle mr-2"></i> Cara Kerja
                    </a>
                </div>
            </div>
            <div class="md:w-5/12">
                <div class="bg-white bg-opacity-10 p-6 rounded-xl backdrop-blur-sm border border-white border-opacity-20 shadow-lg">
                    <div class="text-center">
                        <div class="inline-block p-3 rounded-full bg-white bg-opacity-20 mb-3">
                            <i class="fas fa-coins text-3xl text-yellow-300"></i>
                        </div>
                        <h2 class="text-lg font-bold text-white mb-1">Poin Anda</h2>
                        <p class="text-4xl font-bold text-white mb-2">{{ number_format(Auth::user()->points_today ?? 0) }}</p>
                        <div class="h-2 bg-white bg-opacity-20 rounded-full mt-3 mb-2">
                            <div class="h-2 bg-yellow-400 rounded-full" style="width: {{ min(100, (Auth::user()->points_today ?? 0) / 10) }}%"></div>
                        </div>
                        <p class="text-xs text-gray-100">Kumpulkan lebih banyak poin untuk rewards eksklusif</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Breadcrumb Navigation - Cleaner and smaller -->
<div class="bg-gray-50 border-b border-gray-200">
    <div class="container mx-auto px-4 py-2">
        <div class="flex items-center space-x-2 text-xs">
            <a href="/" class="text-gray-600 hover:text-[#16782d] transition-colors flex items-center">
                <i class="fas fa-home mr-1"></i> Beranda
            </a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-[#16782d] font-medium flex items-center">
                <i class="fas fa-award mr-1"></i> Rewards Program
            </span>
        </div>
    </div>
</div>

<!-- Filter Section - More compact -->
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-5">
        <h2 class="text-xl font-bold text-gray-800 mb-3 md:mb-0">Explore Rewards</h2>
        <div class="flex flex-wrap gap-2">
            <button class="bg-[#16782d] text-white px-4 py-1.5 rounded-md hover:bg-[#0e5420] transition-all shadow-sm flex items-center text-sm">
                <i class="fas fa-th-large mr-1.5"></i> Semua
            </button>
            <button class="bg-white text-gray-700 border border-gray-300 px-4 py-1.5 rounded-md hover:bg-gray-50 transition-all shadow-sm flex items-center text-sm">
                <i class="fas fa-sort-amount-down mr-1.5"></i> Poin Terendah
            </button>
            <button class="bg-white text-gray-700 border border-gray-300 px-4 py-1.5 rounded-md hover:bg-gray-50 transition-all shadow-sm flex items-center text-sm">
                <i class="fas fa-sort-amount-up mr-1.5"></i> Poin Tertinggi
            </button>
            <button class="bg-white text-gray-700 border border-gray-300 px-4 py-1.5 rounded-md hover:bg-gray-50 transition-all shadow-sm flex items-center text-sm">
                <i class="fas fa-clock mr-1.5"></i> Terbaru
            </button>
        </div>
    </div>

    <form method="GET" action="{{ route('rewards.index') }}" class="relative mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari rewards..." class="w-full px-4 py-3 rounded-lg border border-gray-200 shadow-sm focus:outline-none focus:ring-1 focus:ring-[#16782d] focus:border-[#16782d] pl-10 text-sm">
        <i class="fas fa-search absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
    </form>
    
</div>

<section id="rewards-section" class="max-w-[1250px] mx-auto px-4 pb-6">
    <div class="space-y-3">
        @foreach ($rewards as $reward)
            <div class="flex flex-wrap items-center bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow transition-all px-3 py-2 gap-3">
                
                <!-- Gambar: tetap 64x64, tidak berubah di mobile -->
                <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100">
                    <img src="{{ asset('storage/' . $reward->foto_rewards) }}" alt="{{ $reward->nama_reward }}" class="w-full h-full object-cover">
                </div>

                <!-- Info Reward: flex-grow supaya isi lebar -->
                <div class="flex-1 min-w-[150px]">
                    <h3 class="text-sm font-semibold text-gray-800">{{ $reward->nama_reward }}</h3>
                    @php
                        $cleanDeskripsi = strip_tags($reward->deskripsi, '<li>');
                        $cleanDeskripsi = preg_replace('/<li>(.*?)<\/li>/', '• $1<br>', $cleanDeskripsi);
                    @endphp
                    <p class="text-xs text-gray-600 mt-0.5 line-clamp-2">
                        {!! $cleanDeskripsi !!}
                    </p>
                    <div class="text-xs text-gray-400 mt-1">
                        <i class="far fa-clock mr-1"></i> Berlaku 30 hari
                    </div>
                </div>

                <!-- Poin & Klaim: fixed width, rata kanan -->
                <div class="w-28 text-right flex-shrink-0">
                    <div class="text-[#16782d] font-bold mb-1.5 flex items-center justify-end text-sm">
                        <i class="fas fa-coins text-yellow-500 mr-1"></i>
                        {{ number_format($reward->points_required) }}
                    </div>
                    @if(Auth::check() && Auth::user()->points_today >= $reward->points_required)
                        <button onclick="window.location.href='{{ route('rewards.store', ['id' => $reward->id]) }}'" 
                                class="bg-[#16782d] text-white px-3 py-1 rounded-md text-xs font-medium hover:bg-[#0e5420] transition w-full">
                            Klaim
                        </button>
                    @else
                        <button class="bg-gray-100 text-gray-400 px-3 py-1 rounded-md text-xs font-medium cursor-not-allowed w-full">
                            <i class="fas fa-lock mr-1"></i> Tidak Cukup
                        </button>
                        <p class="text-xs text-gray-400 mt-1 text-center">
                            {{ Auth::user()->points_today ?? 0 }}/{{ $reward->points_required }}
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>


<!-- Pagination - More compact -->
<div class="container mx-auto px-4 py-6 mb-6">
    <div class="flex justify-center">
        {{ $rewards->links() }}
    </div>
</div>

<!-- Benefits Section - More visual appeal -->
<section class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Keuntungan Program Rewards</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-sm text-center transition-all hover:shadow border border-gray-50">
                <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-green-100 text-[#16782d] mb-4">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Exclusive Rewards</h3>
                <p class="text-sm text-gray-600">Nikmati berbagai hadiah eksklusif yang hanya tersedia untuk member WOWINFood.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm text-center transition-all hover:shadow border border-gray-50">
                <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-green-100 text-[#16782d] mb-4">
                    <i class="fas fa-percentage text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Diskon Spesial</h3>
                <p class="text-sm text-gray-600">Dapatkan diskon spesial untuk setiap pembelian produk WOWINFood favorit Anda.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm text-center transition-all hover:shadow border border-gray-50">
                <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-green-100 text-[#16782d] mb-4">
                    <i class="fas fa-gift text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Birthday Rewards</h3>
                <p class="text-sm text-gray-600">Dapatkan hadiah spesial di hari ulang tahun Anda sebagai member WOWINFood.</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section - More concise -->
<section class="container mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Pertanyaan Umum</h2>
    
    <div class="max-w-2xl mx-auto">
        <div class="mb-4 border-b border-gray-100 pb-4">
            <h3 class="text-base font-bold text-gray-800 mb-2">Bagaimana cara mengumpulkan poin?</h3>
            <p class="text-sm text-gray-600">Anda dapat mengumpulkan poin dengan melakukan pembelian produk WOWINFood, mengisi survei, atau berpartisipasi dalam program promosi kami.</p>
        </div>
        
        <div class="mb-4 border-b border-gray-100 pb-4">
            <h3 class="text-base font-bold text-gray-800 mb-2">Berapa lama masa berlaku poin?</h3>
            <p class="text-sm text-gray-600">Poin Anda berlaku selama 12 bulan sejak tanggal diperoleh. Pastikan untuk menggunakan poin Anda sebelum masa berlaku habis.</p>
        </div>
        
        <div class="mb-4 border-b border-gray-100 pb-4">
            <h3 class="text-base font-bold text-gray-800 mb-2">Bagaimana cara menukarkan poin?</h3>
            <p class="text-sm text-gray-600">Pilih rewards yang Anda inginkan, klik tombol "Klaim Reward", dan ikuti petunjuk selanjutnya untuk menyelesaikan proses penukaran.</p>
        </div>
        
        <div class="mb-4">
            <h3 class="text-base font-bold text-gray-800 mb-2">Apakah ada biaya tambahan untuk menukarkan poin?</h3>
            <p class="text-sm text-gray-600">Tidak ada biaya tambahan untuk menukarkan poin Anda dengan rewards yang tersedia.</p>
        </div>
    </div>
</section>

<!-- Empty State (if rewards is empty) - More elegant -->
@if(count($rewards) == 0)
<div class="container mx-auto px-4 py-12">
    <div class="bg-white p-8 rounded-lg shadow-sm text-center max-w-lg mx-auto border border-gray-100">
        <div class="flex justify-center mb-4">
            <div class="h-16 w-16 rounded-full bg-gray-50 flex items-center justify-center">
                <i class="fas fa-gift text-2xl text-gray-400"></i>
            </div>
        </div>
        <h2 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Rewards</h2>
        <p class="text-sm text-gray-600 mb-5">Saat ini belum ada rewards yang tersedia. Silakan cek kembali nanti untuk melihat penawaran terbaru dari kami.</p>
        <a href="/" class="inline-flex items-center px-5 py-2 bg-[#16782d] text-white rounded-md hover:bg-[#0e5420] transition-all shadow-sm text-sm">
            <i class="fas fa-home mr-2"></i> Kembali ke Beranda
        </a>
    </div>
</div>
@endif

<!-- Slim, Aesthetic, Professional & Attractive CTA -->
<div class="bg-[#16782d] py-4 relative overflow-hidden shadow-md border-b-4 border-emerald-800">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-64 h-16 bg-emerald-600 opacity-20 rounded-bl-full transform -translate-y-2 translate-x-16"></div>
    <div class="absolute bottom-0 left-0 w-32 h-8 bg-white opacity-10 rounded-tr-full"></div>
    
    <div class="container mx-auto px-4 flex items-center justify-between text-white">
        <!-- Left Content - Refined Typography -->
        <div class="text-left flex-grow">
            <div class="flex items-center">
                <span class="w-1 h-6 bg-yellow-400 mr-2 rounded-full"></span>
                <h2 class="text-lg font-bold tracking-tight">Kumpulkan Poin Sekarang</h2>
            </div>
            <p class="text-xs text-gray-100 max-w-sm mt-1 leading-relaxed pl-3 border-l border-emerald-500">
                Dapatkan reward menarik dari WOWINFood dengan mengumpulkan poin.
            </p>
            <div class="mt-3 pl-3">
                <a href="{{ route('products') }}"
                   class="inline-flex items-center px-4 py-1.5 bg-white text-[#16782d] rounded-full font-medium hover:bg-yellow-50 transition-all duration-300 shadow-md text-xs group">
                    <i class="fas fa-shopping-cart text-yellow-500 mr-1.5 group-hover:animate-pulse"></i> 
                    Belanja Sekarang
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1.5 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Right Logo with Sleek White Circular Container -->
        <div class="w-20 h-20 relative flex items-center justify-center ml-4 mr-2">
            <div class="absolute inset-0 bg-white rounded-full shadow-md"></div>
            <div class="absolute inset-0.5 bg-white rounded-full"></div> <!-- Double border effect -->
            <div class="relative w-14 h-14 flex items-center justify-center">
                <img src="{{ asset('images/lg-h.png') }}" alt="WOWINFood Logo" class="w-full object-contain">
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div id="caraKerjaModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden backdrop-blur-sm transition-all duration-300 overflow-hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 relative overflow-hidden transform transition-all duration-500 scale-95 opacity-0 max-h-[90vh]" id="modalContent">
        <!-- Drag indicator -->
        <div class="w-full flex justify-center py-2 cursor-grab" id="modalDragHandle">
            <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
        </div>
        
        <!-- Decorative header -->
        <div class="bg-gradient-to-r from-[#0b5c20] to-[#16782d] pt-4 pb-8 px-6 relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full transform -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-5 rounded-full transform translate-y-12 -translate-x-12"></div>
            
            <div class="flex items-center mb-2">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-info-circle text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-white">Cara Kerja</h3>
            </div>
            <p class="text-sm text-gray-100 relative z-10">Program MyWowin Rewards</p>
        </div>
        
        <!-- Close button -->
        <button id="closeModal" class="absolute top-4 right-4 text-white hover:text-gray-200 transition-colors">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Content section -->
        <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
            <div class="space-y-5">
                <!-- Step 1 -->
                <div class="flex">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-[#16782d] text-white flex items-center justify-center mr-3 font-medium">1</div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-1">Kumpulkan Poin</h4>
                        <p class="text-sm text-gray-600">Dapatkan poin setiap kali Anda melakukan login pada website MyWowin selama <b> 30 hari berturut-turut</b>. Setiap 1000 poin setara dengan Rp 1.000.</p>
                    </div>
                </div>
                
                <!-- Step 2 -->
                <div class="flex">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-[#16782d] text-white flex items-center justify-center mr-3 font-medium">2</div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-1">Pilih Reward</h4>
                        <p class="text-sm text-gray-600">Pilih hadiah yang Anda inginkan dari berbagai pilihan yang tersedia. Pastikan poin Anda mencukupi serta sesuai dengan s&k yang ada.</p>
                    </div>
                </div>
                
                <!-- Step 3 -->
                <div class="flex">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-[#16782d] text-white flex items-center justify-center mr-3 font-medium">3</div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-1">Klaim Reward</h4>
                        <p class="text-sm text-gray-600">Klik tombol "Klaim" pada reward yang Anda inginkan. Poin akan otomatis terpotong dari total poin Anda.</p>
                    </div>
                </div>
                
                <!-- Step 4 -->
                <div class="flex">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-[#16782d] text-white flex items-center justify-center mr-3 font-medium">4</div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-1">Nikmati Hadiah</h4>
                        <p class="text-sm text-gray-600">Hadiah reward selama 30 hari berturut-turut akan dikirim kan melalui cabang terdekat dari cabang PT. Wowin Purnomo Putera sesuai Kantor Cabang terdekat Anda.</p>
                    </div>
                </div>
            </div>
            
            <!-- Tips section -->
            <div class="mt-6 bg-gray-50 rounded-lg p-4 border border-gray-100">
                <div class="flex items-center mb-2">
                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                    <h5 class="font-medium text-gray-800">Tips</h5>
                </div>
                <ul class="text-sm text-gray-600 space-y-1.5">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-[#16782d] mt-1 mr-2 flex-shrink-0"></i>
                        <span>Poin akan pada setelan awal jika Anda tidak login secara berturut-turut selama 30 hari.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-[#16782d] mt-1 mr-2 flex-shrink-0"></i>
                        <span>Dapatkan poin bonus dengan mengikuti kegiatan promosi MyWowin.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-[#16782d] mt-1 mr-2 flex-shrink-0"></i>
                        <span>Reward yang telah diklaim berlaku selama 30 hari.</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-center items-center">
            <button id="closeModalBtn" class="px-6 py-2.5 bg-[#16782d] text-white text-base font-medium rounded-md hover:bg-[#0e5420] transition-colors shadow-sm w-full max-w-xs">
                Mengerti
            </button>
        </div>
    </div>
</div>

<!-- JavaScript for Modal Functionality with Sliding and Dragging -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const caraKerjaButtons = document.querySelectorAll('a[href="#"]:has(.fa-info-circle)');
    const modal = document.getElementById('caraKerjaModal');
    const modalContent = document.getElementById('modalContent');
    const closeModal = document.getElementById('closeModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const dragHandle = document.getElementById('modalDragHandle');
    
    // Variables for drag functionality
    let startY;
    let currentTranslate = 0;
    let isDragging = false;
    
    // Function to open modal with animation
    function openModal() {
        modal.classList.remove('hidden');
        // Trigger reflow for animation
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    // Function to close modal with animation
    function closeModalWithAnimation() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 500);
    }
    
    // Event listeners for opening modal
    caraKerjaButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            openModal();
        });
    });
    
    // Alternative selector for the Cara Kerja button
    const caraKerjaButton = document.querySelector('a.bg-transparent.border.border-white.text-white');
    if (caraKerjaButton) {
        caraKerjaButton.addEventListener('click', function(e) {
            e.preventDefault();
            openModal();
        });
    }
    
    // Event listeners for closing modal
    closeModal.addEventListener('click', closeModalWithAnimation);
    closeModalBtn.addEventListener('click', closeModalWithAnimation);
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModalWithAnimation();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModalWithAnimation();
        }
    });
    
    // Drag functionality
    function handleDragStart(e) {
        isDragging = true;
        startY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
        
        document.addEventListener('mousemove', handleDragMove);
        document.addEventListener('touchmove', handleDragMove);
        document.addEventListener('mouseup', handleDragEnd);
        document.addEventListener('touchend', handleDragEnd);
        
        // Change cursor style during drag
        dragHandle.classList.remove('cursor-grab');
        dragHandle.classList.add('cursor-grabbing');
    }
    
    function handleDragMove(e) {
        if (!isDragging) return;
        
        const currentY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
        const diff = currentY - startY;
        
        // Allow dragging in all directions but limit scaling effect
        currentTranslate = diff;
        const scale = Math.max(0.95, 1 - (diff / 1000));
        modalContent.style.transform = `scale(${scale})`;
        modalContent.style.opacity = Math.max(0.5, 1 - (diff / 500));
    }
    
    function handleDragEnd() {
        isDragging = false;
        
        // Remove event listeners
        document.removeEventListener('mousemove', handleDragMove);
        document.removeEventListener('touchmove', handleDragMove);
        document.removeEventListener('mouseup', handleDragEnd);
        document.removeEventListener('touchend', handleDragEnd);
        
        // Reset cursor style
        dragHandle.classList.add('cursor-grab');
        dragHandle.classList.remove('cursor-grabbing');
        
        // If dragged more than 150px down, close the modal
        if (currentTranslate > 150) {
            closeModalWithAnimation();
        } else {
            // Otherwise snap back
            modalContent.style.transform = 'scale(1)';
            currentTranslate = 0;
        }
    }
    
    // Add drag event listeners
    dragHandle.addEventListener('mousedown', handleDragStart);
    dragHandle.addEventListener('touchstart', handleDragStart);
    
    // Prevent scrolling when touching the handle on mobile
    dragHandle.addEventListener('touchmove', function(e) {
        if (isDragging) {
            e.preventDefault();
        }
    }, { passive: false });
});
</script>
@endsection