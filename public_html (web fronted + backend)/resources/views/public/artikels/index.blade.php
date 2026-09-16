@extends('public.layouts.app')

@section('title', 'WOWINFood | Artikel & Wawasan')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<style>
    /* Sophisticated Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.96); }
        to { opacity: 1; transform: scale(1); }
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-15px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }

    .animate-fade-up {
        animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    
    .animate-scale {
        animation: fadeInScale 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }

    .animate-slide {
        animation: slideIn 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }

    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    
    /* Premium Typography */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Elegant Card Effects */
    .article-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0, 0, 0, 0.06);
    }
    
    .article-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.08);
        border-color: rgba(22, 120, 45, 0.12);
    }
    
    .article-card:hover .article-image {
        transform: scale(1.06);
    }
    
    .article-image {
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Premium Image Overlay */
    .image-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.4) 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .article-card:hover .image-overlay {
        opacity: 1;
    }

    /* Loading State */
    .img-loading {
        background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 50%, #f8f9fa 100%);
        background-size: 1000px 100%;
        animation: shimmer 2s infinite;
    }

    /* Refined Scrollbar */
    ::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f8f9fa;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #16782d 0%, #105023 100%);
        border-radius: 5px;
        border: 2px solid #f8f9fa;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #105023 0%, #0d3d1c 100%);
    }

    /* Luxury Badge */
    .luxury-badge {
        background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
    }

    /* Premium Button */
    .premium-btn {
        background: linear-gradient(135deg, #16782d 0%, #105023 100%);
        box-shadow: 0 4px 14px 0 rgba(22, 120, 45, 0.25);
    }

    .premium-btn:hover {
        box-shadow: 0 6px 20px 0 rgba(22, 120, 45, 0.35);
        transform: translateY(-1px);
    }

    /* Featured Article Gradient */
    .featured-gradient {
        background: linear-gradient(135deg, #fafafa 0%, #ffffff 100%);
    }

    /* Text Balance */
    .text-balance {
        text-wrap: balance;
    }

    /* Refined Select */
    select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2316782d'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1.25em;
        padding-right: 2.5rem;
    }
</style>
@endsection

@section('content')
<div class="bg-white min-h-screen">

    {{-- HERO SECTION - Featured Article --}}
    @if($artikels->isNotEmpty())
        @php 
            $featured = $artikels->first();
            $featuredContent = is_array($featured->isi) ? $featured->isi : json_decode($featured->isi, true);
            $featuredText = '';
            if(is_array($featuredContent)) {
                foreach($featuredContent as $block) {
                    if(isset($block['type']) && $block['type'] === 'text') {
                        $featuredText .= $block['value'] . ' ';
                    }
                }
            } else {
                $featuredText = $featured->isi;
            }
            $featuredText = strip_tags($featuredText);
        @endphp
        
        <section class="relative featured-gradient border-b border-gray-100">
            <div class="container mx-auto px-6 lg:px-8 py-16 lg:py-24">
                <div class="grid lg:grid-cols-2 gap-12 xl:gap-16 items-center max-w-7xl mx-auto">
                    
                    {{-- Text Content --}}
                    <div class="animate-slide space-y-6">
                        <!-- Premium Badge -->
                        <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#16782d] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#16782d]"></span>
                            </span>
                            <span class="text-[#16782d] text-xs font-bold uppercase tracking-wider">Featured Article</span>
                        </div>
                        
                        <!-- Title -->
                        <h1 class="text-4xl lg:text-5xl xl:text-6xl font-bold text-gray-900 leading-tight text-balance tracking-tight">
                            {{ $featured->judul }}
                        </h1>
                        
                        <!-- Excerpt -->
                        <p class="text-lg text-gray-600 leading-relaxed line-clamp-3">
                            {{ Str::limit($featuredText, 200) }}
                        </p>
                        
                        <!-- Meta Info -->
                        <div class="flex items-center gap-5 pt-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-[#16782d] to-green-700 flex items-center justify-center text-white font-black text-base shadow-md">
                                    W
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">Penulis Wowin Food</p>
                                    <p class="text-xs text-gray-500 font-medium">{{ $featured->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="h-10 w-px bg-gray-200"></div>
                            <div class="flex items-center gap-1.5 text-gray-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-xs font-medium">{{ ceil(str_word_count($featuredText) / 200) }} min read</span>
                            </div>
                        </div>
                        
                        <!-- CTA Button -->
                        <div class="pt-2">
                            <a href="{{ route('public.artikels.show', $featured->slug ?? $featured->id) }}" 
                               class="premium-btn inline-flex items-center gap-2.5 px-7 py-3.5 text-white text-sm font-semibold rounded-xl transition-all duration-300 group">
                                Read Article
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Image Side --}}
                    <div class="animate-scale delay-100">
                        <div class="relative group aspect-[4/3] rounded-2xl overflow-hidden shadow-2xl">
                            @php $foto_hero = is_array($featured->foto_artikel) ? ($featured->foto_artikel[0] ?? '') : ''; @endphp
                            
                            <img src="{{ asset('storage/' . $foto_hero) }}" 
                                class="w-full h-full object-cover article-image" {{-- Kuncinya ada di w-full h-full object-cover --}}
                                alt="{{ $featured->judul }}"
                                loading="eager">
                            
                            <div class="image-overlay"></div>

                            @if(is_array($featured->foto_artikel) && count($featured->foto_artikel) > 1)
                                <div class="absolute top-5 right-5 luxury-badge px-3.5 py-2 rounded-xl shadow-lg">
                                    <span class="text-xs font-bold text-gray-800">
                                        +{{ count($featured->foto_artikel) - 1 }} Photos
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ARTICLES GRID SECTION --}}
    <section class="py-16 lg:py-24 bg-gradient-to-b from-white to-gray-50">
        <div class="container mx-auto px-6 lg:px-8 max-w-7xl">
            
            {{-- Section Header --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 mb-12">
                <div>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2 tracking-tight">
                        Latest Insights
                    </h2>
                    <p class="text-sm text-gray-600 font-medium">
                        Discover stories and expert perspectives
                    </p>
                </div>
                
                {{-- Sort Dropdown --}}
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Sort By</span>
                    <select id="sort" 
                            class="border border-gray-200 rounded-xl bg-white text-sm font-medium text-gray-700 focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d] px-4 py-2.5 cursor-pointer transition-all shadow-sm hover:shadow-md">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    </select>
                </div>
            </div>

            {{-- Articles Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($artikels as $index => $artikel)
                    @if($loop->first && $artikels->count() > 1 && !request('page')) @continue @endif

                    @php
                        $cardContent = is_array($artikel->isi) ? $artikel->isi : json_decode($artikel->isi, true);
                        $cardText = '';
                        if(is_array($cardContent)) {
                            foreach($cardContent as $block) {
                                if(isset($block['type']) && $block['type'] === 'text') {
                                    $cardText .= $block['value'] . ' ';
                                }
                            }
                        } else {
                            $cardText = $artikel->isi;
                        }
                        $cardText = strip_tags($cardText);
                    @endphp

                    <article class="article-card bg-white rounded-2xl overflow-hidden animate-fade-up delay-{{ ($loop->iteration % 3) * 100 }}">
                        
                        {{-- Image --}}
                        <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
                            @php $foto_card = is_array($artikel->foto_artikel) ? ($artikel->foto_artikel[0] ?? '') : ''; @endphp
                            
                            <a href="{{ route('public.artikels.show', ['id' => $artikel->id, 'slug' => \Illuminate\Support\Str::slug($artikel->judul)])}}">
                                <img src="{{ asset('storage/' . $foto_card) }}" 
                                        class="article-image w-full h-full object-cover" {{-- Object-cover akan memotong gambar secara otomatis agar pas --}}
                                        alt="{{ $artikel->judul }}"
                                        loading="lazy">
                            </a>
                            
                            <div class="image-overlay"></div>
                            
                            @if(is_array($artikel->foto_artikel) && count($artikel->foto_artikel) > 1)
                                <div class="absolute top-4 right-4 luxury-badge px-3 py-1.5 rounded-lg shadow-md">
                                    <span class="text-xs font-bold text-gray-700">
                                        +{{ count($artikel->foto_artikel) - 1 }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="p-6">
                            <!-- Meta -->
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">
                                    Article
                                </span>
                                <span class="text-gray-300">•</span>
                                <span class="text-xs text-gray-500 font-medium">
                                    {{ $artikel->created_at->format('M d, Y') }}
                                </span>
                            </div>
                            
                            <!-- Title -->
                            <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight line-clamp-2 hover:text-[#16782d] transition-colors duration-300">
                                <a href="{{ route('public.artikels.show', $artikel->slug ?? $artikel->id) }}">
                                    {{ $artikel->judul }}
                                </a>
                            </h3>
                            
                            <!-- Excerpt -->
                            <p class="text-sm text-gray-600 mb-5 leading-relaxed line-clamp-2">
                                {{ Str::limit($cardText, 110) }}
                            </p>

                            <!-- Footer -->
                            <div class="flex items-center justify-between pt-5 border-t border-gray-100">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#16782d] to-green-700 flex items-center justify-center text-white text-xs font-black shadow-sm">
                                        W
                                    </div>
                                    <span class="text-xs font-semibold text-gray-700 truncate max-w-[140px]">
                                        Penulis Wowin Food
                                    </span>
                                </div>
                                
                                <a href="{{ route('public.artikels.show', $artikel->slug ?? $artikel->id) }}" 
                                   class="flex items-center justify-center w-9 h-9 rounded-xl bg-gray-50 hover:bg-[#16782d] text-gray-600 hover:text-white transition-all duration-300 group">
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            
            {{-- Empty State --}}
            @if($artikels->isEmpty() || ($artikels->count() === 1 && !request('page')))
                <div class="text-center py-20">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-2xl bg-gray-100 mb-5">
                        <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Articles Available</h3>
                    <p class="text-sm text-gray-500">New content will be added soon</p>
                </div>
            @endif
            
            {{-- Pagination --}}
            @if($artikels->hasPages())
                <div class="mt-16 flex justify-center">
                    <div class="inline-flex rounded-xl shadow-sm border border-gray-200 overflow-hidden bg-white">
                        {{ $artikels->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- NEWSLETTER SECTION --}}
    <section class="py-16 lg:py-20 bg-white">
        <div class="container mx-auto px-6 lg:px-8 max-w-7xl">
            <div class="relative bg-gradient-to-br from-[#16782d] via-green-700 to-green-900 rounded-3xl shadow-2xl overflow-hidden">
                
                {{-- Decorative Elements --}}
                <div class="absolute inset-0 overflow-hidden opacity-10">
                    <div class="absolute -top-24 -right-24 w-80 h-80 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-white rounded-full blur-3xl"></div>
                </div>
                
                {{-- Content --}}
<div class="relative px-8 sm:px-12 py-10 lg:py-12 text-center"> {{-- Tinggi dikurangi dengan mengubah py-16/20 menjadi py-10/12 --}}
    <div class="max-w-2xl mx-auto space-y-5"> {{-- Spasi antar elemen dikurangi sedikit (space-y-5) --}}
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md shadow-lg"> {{-- Ukuran icon diperkecil sedikit --}}
            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        
        <div class="space-y-2">
            <h2 class="text-2xl lg:text-3xl font-bold text-white tracking-tight">
                Stay in the Loop
            </h2>
            
            <p class="text-green-50 text-sm lg:text-base leading-relaxed max-w-xl mx-auto opacity-90">
                Hubungi kami untuk mendapatkan wawasan eksklusif, pembaruan industri, dan konten pilihan langsung dari tim kami.
            </p>
        </div>
        
        <div class="pt-2">
            <a href="/contacts" 
               class="inline-flex items-center justify-center px-10 py-3.5 bg-white text-[#16782d] font-bold rounded-xl hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                <i class="fas fa-paper-plane mr-2 text-sm"></i>
                Hubungi Kami Sekarang
            </a>
        </div>
        
        <p class="text-green-100/70 text-[10px] uppercase tracking-widest pt-2 font-medium">
            Kami siap melayani pertanyaan Anda 24/7
        </p>
    </div>
</div>
            </div>
        </div>
    </section>
</div>

{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sort Handler
    const sortSelect = document.getElementById('sort');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', this.value);
            window.location.href = url.toString();
        });
    }

    // Intersection Observer for Lazy Loading & Animations
    if ('IntersectionObserver' in window) {
        const observerOptions = {
            rootMargin: '50px',
            threshold: 0.1
        };

        const imgObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    img.classList.remove('img-loading');
                    imgObserver.unobserve(img);
                }
            });
        }, observerOptions);

        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            img.classList.add('img-loading');
            imgObserver.observe(img);
        });
    }

    // Newsletter Form Handler
    const newsletterForm = document.querySelector('form');
    if (newsletterForm && newsletterForm.querySelector('input[type="email"]')) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput.value;
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.textContent;
            
            button.textContent = 'Subscribing...';
            button.disabled = true;
            button.style.opacity = '0.6';
            
            setTimeout(() => {
                alert('Thank you! A confirmation email has been sent to ' + email);
                emailInput.value = '';
                button.textContent = originalText;
                button.disabled = false;
                button.style.opacity = '1';
            }, 1200);
        });
    }

    // Smooth Scroll for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }
            }
        });
    });

    // Add loading state to article images
    document.querySelectorAll('.article-image').forEach(img => {
        if (!img.complete) {
            img.classList.add('img-loading');
            img.addEventListener('load', function() {
                this.classList.remove('img-loading');
            });
        }
    });
});
</script>
@endsection