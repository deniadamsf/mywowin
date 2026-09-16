@php
    // 1. Ekstraksi teks bersih paragraf pertama untuk meta description
    $data = $artikel->isi; 
    $teks_bersih = '';

    if (is_array($data)) {
        foreach ($data as $block) {
            if (($block['type'] ?? '') === 'text' || ($block['type'] ?? '') === 'teks') {
                $teks_bersih = $block['value'] ?? '';
                break;
            }
        }
        if (empty($teks_bersih) && isset($data[0]['value'])) {
            $teks_bersih = $data[0]['value'];
        }
    } elseif (is_string($data) && strpos($data, '[{"type"') !== false) {
        $decode = json_decode($data, true);
        if (is_array($decode)) {
            foreach ($decode as $block) {
                if (($block['type'] ?? '') === 'text' || ($block['type'] ?? '') === 'teks') {
                    $teks_bersih = $block['value'] ?? '';
                    break;
                }
            }
        }
    } else {
        $teks_bersih = is_string($data) ? $data : '';
    }

    $cleanMetaDescription = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($teks_bersih))), 155, '...');
    if (empty($cleanMetaDescription)) {
        $cleanMetaDescription = 'Ulasan lengkap dan panduan pemesanan ' . $artikel->judul . ' resmi dari PT Wowin Purnomo Putera.';
    }

    // 2. Ekstraksi seluruh gambar artikel untuk Rich Results ImageObject
    $daftarGambar = [];
    if (is_array($artikel->foto_artikel)) {
        foreach ($artikel->foto_artikel as $foto) {
            if ($foto) $daftarGambar[] = asset('storage/' . $foto);
        }
    } elseif (is_string($artikel->foto_artikel)) {
        $decodedFoto = json_decode($artikel->foto_artikel, true);
        if (is_array($decodedFoto)) {
            foreach ($decodedFoto as $foto) {
                if ($foto) $daftarGambar[] = asset('storage/' . $foto);
            }
        } elseif (!empty($artikel->foto_artikel)) {
            $daftarGambar[] = asset('storage/' . $artikel->foto_artikel);
        }
    }

    if (empty($daftarGambar)) {
        $daftarGambar[] = asset('images/logo-wowin.png');
    }

    $primaryImage = $daftarGambar[0];
    $urlArtikel = route('public.artikels.show', $artikel->slug ?? $artikel->id);
    $authorName = 'Penulis Wowin Food';

    // 3. Ekstraksi FAQPage Dinamis dari Konten Artikel untuk Google Rich Results
    $fullHtmlContent = '';
    if (is_array($data)) {
        foreach ($data as $block) {
            $fullHtmlContent .= ' ' . ($block['value'] ?? '');
        }
    } else {
        $fullHtmlContent = (string)$data;
    }

    $faqItems = [];
    if (preg_match_all('/<h3>(.*?)<\/h3>\s*<p>(.*?)<\/p>/is', $fullHtmlContent, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $q = trim(strip_tags($m[1]));
            $a = trim(strip_tags($m[2]));
            if (!empty($q) && !empty($a) && (str_contains($q, '?') || str_contains(strtolower($fullHtmlContent), 'faq'))) {
                $faqItems[] = [
                    "@type" => "Question",
                    "name" => $q,
                    "acceptedAnswer" => [
                        "@type" => "Answer",
                        "text" => $a
                    ]
                ];
            }
        }
    }

    // Jika tidak ditemukan FAQ spesifik di teks, gunakan FAQ standar resmi
    if (empty($faqItems)) {
        $faqItems = [
            [
                "@type" => "Question",
                "name" => "Bagaimana cara memesan produk resmi bumbu masak di My Wowin?",
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => "Pemesanan dapat dilakukan langsung secara online melalui situs mywowin.com atau via WhatsApp resmi PT Wowin Purnomo Putera dengan harga grosir pabrik langsung."
                ]
            ],
            [
                "@type" => "Question",
                "name" => "Apakah produk bumbu masak dan kecap Wowin Food berizin edar BPOM dan Halal?",
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => "Seluruh varian produk Wowin, Rajaku, dan Jangkar telah memiliki izin edar resmi BPOM RI dan sertifikasi Halal MUI yang menjamin kebersihan dan keaslian mutu."
                ]
            ]
        ];
    }

    // 4. Schema.org Graph Berstandar Rank Math SEO
    $schemaGraph = [
        "@context" => "https://schema.org",
        "@graph" => [
            [
                "@type" => "Organization",
                "@id" => url('/') . "/#organization",
                "name" => "PT Wowin Purnomo Putera",
                "url" => url('/'),
                "logo" => [
                    "@type" => "ImageObject",
                    "@id" => url('/') . "/#logo",
                    "url" => asset('images/lg-h.png'),
                    "caption" => "PT Wowin Purnomo Putera",
                    "inLanguage" => "id-ID"
                ],
                "contactPoint" => [
                    [
                        "@type" => "ContactPoint",
                        "contactType" => "customer service",
                        "telephone" => "+62-812-3456-7890",
                        "availableLanguage" => ["Indonesian"]
                    ]
                ]
            ],
            [
                "@type" => "WebSite",
                "@id" => url('/') . "/#website",
                "url" => url('/'),
                "name" => "My Wowin",
                "description" => "Toko Resmi Produsen Kecap Manis & Bumbu Masak PT Wowin Purnomo Putera",
                "publisher" => [
                    "@id" => url('/') . "/#organization"
                ],
                "inLanguage" => "id-ID"
            ],
            [
                "@type" => "BreadcrumbList",
                "@id" => $urlArtikel . "#breadcrumb",
                "itemListElement" => [
                    [
                        "@type" => "ListItem",
                        "position" => 1,
                        "name" => "Beranda",
                        "item" => url('/')
                    ],
                    [
                        "@type" => "ListItem",
                        "position" => 2,
                        "name" => "Artikel",
                        "item" => url('/artikels')
                    ],
                    [
                        "@type" => "ListItem",
                        "position" => 3,
                        "name" => $artikel->judul,
                        "item" => $urlArtikel
                    ]
                ]
            ],
            [
                "@type" => "Article",
                "@id" => $urlArtikel . "#article",
                "isPartOf" => [
                    "@type" => "WebPage",
                    "@id" => $urlArtikel
                ],
                "headline" => $artikel->judul,
                "description" => $cleanMetaDescription,
                "inLanguage" => "id-ID",
                "mainEntityOfPage" => [
                    "@type" => "WebPage",
                    "@id" => $urlArtikel
                ],
                "datePublished" => optional($artikel->created_at)->toIso8601String() ?: now()->toIso8601String(),
                "dateModified" => optional($artikel->updated_at)->toIso8601String() ?: now()->toIso8601String(),
                "author" => [
                    "@type" => "Person",
                    "name" => $authorName,
                    "url" => url('/')
                ],
                "publisher" => [
                    "@id" => url('/') . "/#organization"
                ],
                "image" => $daftarGambar
            ],
            [
                "@type" => "FAQPage",
                "@id" => $urlArtikel . "#faq",
                "mainEntity" => $faqItems
            ]
        ]
    ];
@endphp

@extends('public.layouts.app')

@section('title', $artikel->judul)
@section('meta_description', $cleanMetaDescription)
@section('meta_image', $primaryImage)
@section('canonical_url', $urlArtikel)
@section('og_type', 'article')

@section('structured_data')
    {{-- Schema.org JSON-LD Structured Data Berstandar Rank Math untuk Google Rich Results --}}
    <script type="application/ld+json">
    {!! json_encode($schemaGraph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endsection

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
html, body {
    overflow-x: hidden !important;
    max-width: 100vw !important;
    width: 100% !important;
}

.font-inter { font-family: 'Inter', sans-serif; }
.font-playfair { font-family: 'Playfair Display', serif; }

/* Grid & Content Constraints to prevent horizontal blowout */
main, .article-content, .prose-content {
    min-width: 0 !important;
    max-width: 100% !important;
    overflow-wrap: break-word !important;
    word-break: break-word !important;
}

/* Article Body Typography */
.article-content {
    font-family: 'Inter', sans-serif;
    color: #1f2937;
    line-height: 1.75;
    font-size: 1.0625rem;
    letter-spacing: -0.011em;
}

.article-content p {
    margin-bottom: 1.5rem;
    text-align: justify;
    hyphens: auto;
}

.article-content h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.75rem;
    font-weight: 700;
    color: #111827;
    margin: 2.5rem 0 1rem;
    line-height: 1.3;
}

.article-content h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    font-weight: 600;
    color: #1f2937;
    margin: 2rem 0 0.875rem;
    line-height: 1.4;
}

.article-content img {
    border-radius: 1rem;
    box-shadow: 0 8px 24px -4px rgba(0, 0, 0, 0.12);
    margin: 1.75rem 0;
    max-width: 100% !important;
    width: 100%;
    height: auto;
    object-fit: cover;
}

.article-content blockquote {
    border-left: 4px solid #16782d;
    padding: 1.25rem 1.5rem;
    margin: 1.75rem 0;
    font-style: italic;
    color: #4b5563;
    background: #f9fafb;
    border-radius: 0.5rem;
}

.article-content ul, .article-content ol {
    margin: 1.5rem 0;
    padding-left: 1.75rem;
}

.article-content li {
    margin-bottom: 0.75rem;
    line-height: 1.75;
}

/* Responsive Table Wrapper & Overflow */
.article-content .overflow-x-auto,
.article-content .table-wrapper {
    display: block !important;
    width: 100% !important;
    max-width: 100% !important;
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch !important;
    touch-action: pan-x pan-y;
    border-radius: 1rem;
    border: 1px solid #e5e7eb;
    margin: 1.5rem 0 2rem 0 !important;
    background: #ffffff;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
}

.article-content table {
    width: 100% !important;
    min-width: 580px !important;
    border-collapse: collapse !important;
    font-size: 0.875rem !important;
}

.article-content th {
    white-space: nowrap !important;
    background-color: #064e3b !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    padding: 0.75rem 1rem !important;
}

.article-content td {
    padding: 0.75rem 1rem !important;
    border-bottom: 1px solid #f3f4f6 !important;
}

/* Custom Scrollbar for Smooth UX */
.article-content .overflow-x-auto::-webkit-scrollbar,
.article-content .table-wrapper::-webkit-scrollbar {
    height: 6px;
}

.article-content .overflow-x-auto::-webkit-scrollbar-track,
.article-content .table-wrapper::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.article-content .overflow-x-auto::-webkit-scrollbar-thumb,
.article-content .table-wrapper::-webkit-scrollbar-thumb {
    background: #10b981;
    border-radius: 4px;
}

/* Mobile swipe hint */
.mobile-table-hint {
    display: none;
}

@media (max-width: 768px) {
    .mobile-table-hint {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        margin-bottom: 0.5rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }
}

/* Social Share Buttons */
.social-share-btn {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.social-share-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

/* Sidebar Cards */
.sidebar-card {
    background: white;
    border-radius: 1.25rem;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.sidebar-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    border-color: #d1d5db;
}

/* Author Card */
.author-card {
    background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
    border: 1px solid #e5e7eb;
    border-radius: 1.5rem;
    padding: 2rem;
    margin-top: 3rem;
}

/* Smooth Scroll */
html {
    scroll-behavior: smooth;
}

/* Mobile Typography Overrides */
@media (max-width: 640px) {
    .article-content {
        font-size: 0.975rem !important;
        line-height: 1.7 !important;
    }
    
    .article-content p {
        text-align: left !important;
        margin-bottom: 1.15rem !important;
        hyphens: none !important;
    }
    
    .article-content h2 {
        font-size: 1.35rem !important;
        line-height: 1.35 !important;
        margin: 1.75rem 0 0.75rem !important;
    }
    
    .article-content h3 {
        font-size: 1.15rem !important;
        line-height: 1.4 !important;
        margin: 1.35rem 0 0.5rem !important;
    }

    .article-content img {
        margin: 1.25rem 0 !important;
        border-radius: 0.75rem !important;
    }

    .article-content blockquote {
        padding: 1rem 1.25rem !important;
        margin: 1.25rem 0 !important;
        font-size: 0.925rem !important;
    }

    .article-content ul, .article-content ol {
        padding-left: 1.25rem !important;
        margin: 1.25rem 0 !important;
    }
}

/* CTA Button Animation */
.cta-btn {
    position: relative;
    overflow: hidden;
}

.cta-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s;
}

.cta-btn:hover::before {
    left: 100%;
}
</style>
@endsection

@section('content')
<div class="reading-progress" id="readingProgress"></div>

{{-- Hero Section - CONTAINER DIPERKECIL --}}
<section class="relative bg-white pt-4 sm:pt-6 pb-4">
    <div class="container mx-auto px-3 sm:px-6 max-w-6xl">
        
        <div class="relative bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900 rounded-2xl sm:rounded-[2rem] lg:rounded-[3rem] overflow-hidden shadow-2xl">
    
    {{-- Animated Background Elements --}}
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-20 left-20 w-72 h-72 bg-yellow-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
        <div class="absolute top-40 right-20 w-72 h-72 bg-green-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-20 left-40 w-72 h-72 bg-teal-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
    </div>

    {{-- Grid Pattern Overlay --}}
    <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>

    <div class="container mx-auto px-4 sm:px-8 lg:px-10 max-w-6xl relative z-10">
        <div class="py-8 sm:py-10 lg:py-14">
            
            {{-- Breadcrumb Navigation --}}
            <nav aria-label="Breadcrumb" class="mb-4 sm:mb-6 overflow-x-auto pb-1">
                <ol class="inline-flex items-center space-x-1.5 sm:space-x-2 bg-white/10 backdrop-blur-xl px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-full border border-white/20 shadow-lg text-xs sm:text-sm whitespace-nowrap">
                    <li class="flex items-center">
                        <a href="/" class="text-white/70 hover:text-yellow-300 transition-colors font-medium">
                            <i class="fas fa-home mr-1.5"></i>Home
                        </a>
                    </li>
                    <li class="text-white/40">
                        <i class="fas fa-chevron-right text-[10px] sm:text-xs"></i>
                    </li>
                    <li class="flex items-center">
                        <a href="/artikels" class="text-white/70 hover:text-yellow-300 transition-colors font-medium">Artikel</a>
                    </li>
                    <li class="text-white/40">
                        <i class="fas fa-chevron-right text-[10px] sm:text-xs"></i>
                    </li>
                    <li class="text-yellow-300 font-semibold">Detail</li>
                </ol>
            </nav>

            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                
                {{-- Content Side --}}
                <div class="space-y-4 sm:space-y-6">
                    
                    {{-- Category Badge --}}
                    <div class="inline-flex items-center gap-2 bg-gradient-to-r from-yellow-400 to-amber-300 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full shadow-lg">
                        <div class="w-2 h-2 bg-emerald-900 rounded-full animate-pulse"></div>
                        <span class="text-emerald-900 text-[11px] sm:text-xs font-bold uppercase tracking-wider">Featured Article</span>
                    </div>

                    {{-- Main Title --}}
                    <h1 class="font-playfair text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-white leading-snug sm:leading-tight tracking-tight break-words">
                        {{ $artikel->judul }}
                    </h1>

                    {{-- Description/Excerpt --}}
                    <p class="text-sm sm:text-base lg:text-lg text-white/80 leading-relaxed font-light max-w-xl">
                        Temukan insight mendalam dan informasi terkini seputar nutrisi, kesehatan, dan gaya hidup sehat.
                    </p>

                    {{-- Author & Meta Info --}}
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                        {{-- Author --}}
                        <div class="flex items-center gap-2 sm:gap-2.5 bg-white/10 backdrop-blur-xl px-3 sm:px-4 py-1.5 sm:py-2 rounded-full border border-white/20">
                            <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-full bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center text-emerald-900 font-black shadow-lg text-xs sm:text-sm shrink-0">
                                W
                            </div>
                            <div class="text-left">
                                <p class="text-white text-xs sm:text-sm font-semibold leading-tight">Penulis Wowin Food</p>
                                <p class="text-white/60 text-[10px] sm:text-xs">Tim Redaksi Wowin Food</p>
                            </div>
                        </div>

                        {{-- Date --}}
                        <div class="flex items-center gap-1.5 bg-white/10 backdrop-blur-xl px-3 sm:px-4 py-1.5 sm:py-2 rounded-full border border-white/20 text-xs sm:text-sm">
                            <i class="far fa-calendar text-yellow-300"></i>
                            <span class="text-white font-medium">{{ $artikel->created_at->format('d M Y') }}</span>
                        </div>

                        {{-- Read Time --}}
                        <div class="flex items-center gap-1.5 bg-white/10 backdrop-blur-xl px-3 sm:px-4 py-1.5 sm:py-2 rounded-full border border-white/20 text-xs sm:text-sm">
                            <i class="far fa-clock text-yellow-300"></i>
                            <span class="text-white font-medium">5 min read</span>
                        </div>
                    </div>

                    {{-- CTA Buttons --}}
                    <div class="flex flex-wrap gap-4 pt-1 sm:pt-2">
                        <button onclick="document.getElementById('content').scrollIntoView({behavior: 'smooth'})" 
                                class="group flex items-center gap-2 bg-yellow-400 hover:bg-yellow-300 text-emerald-900 px-5 sm:px-6 py-3 sm:py-3.5 rounded-full text-sm sm:text-base font-semibold shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                            <span>Mulai Membaca</span>
                            <i class="fas fa-arrow-down group-hover:translate-y-1 transition-transform"></i>
                        </button>
                    </div>
                </div>

                {{-- Image Side --}}
                <div class="relative lg:order-last">
                    @php 
                        $foto_raw = is_string($artikel->foto_artikel) ? json_decode($artikel->foto_artikel, true) : $artikel->foto_artikel;
                        $foto_puncak = is_array($foto_raw) ? ($foto_raw[0] ?? null) : $foto_raw;
                    @endphp

                    @if($foto_puncak)
                    <div class="relative group">
                        {{-- Main Image Container - UKURAN LEBIH KECIL LAGI --}}
                        <div class="relative rounded-2xl lg:rounded-2xl overflow-hidden shadow-2xl transform transition-all duration-500 group-hover:scale-[1.02]">
                            <img src="{{ asset('storage/' . $foto_puncak) }}" 
                                 alt="{{ $artikel->judul }}" 
                                 class="w-full h-[260px] sm:h-[320px] md:h-[360px] lg:h-[400px] xl:h-[420px] object-cover" {{-- Height lebih kecil --}}
                                 loading="eager">
                            
                            {{-- Gradient Overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-50"></div>
                        </div>

                        {{-- Decorative Elements --}}
                        <div class="absolute -top-3 -right-3 w-20 h-20 bg-yellow-400 rounded-full blur-2xl opacity-40 animate-pulse"></div>
                        <div class="absolute -bottom-3 -left-3 w-24 h-24 bg-teal-400 rounded-full blur-2xl opacity-30 animate-pulse animation-delay-2000"></div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- Bottom Wave Divider --}}
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
        <svg class="relative block w-full h-16 lg:h-20" viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"> {{-- Height dikurangi --}}
            <path d="M0,0 C150,80 350,80 600,50 C850,20 1050,20 1200,50 L1200,120 L0,120 Z" fill="white" opacity="0.3"/>
            <path d="M0,20 C200,80 400,80 600,60 C800,40 1000,40 1200,70 L1200,120 L0,120 Z" fill="white" opacity="0.5"/>
            <path d="M0,40 C250,100 450,100 600,80 C750,60 950,60 1200,90 L1200,120 L0,120 Z" fill="white"/>
        </svg>
    </div>
</div>
</section>

<style>
/* Custom Animations */
@keyframes blob {
    0%, 100% {
        transform: translate(0, 0) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}

/* Grid Pattern */
.bg-grid-pattern {
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 30px 30px;
}

/* Smooth Font Rendering */
* {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Hover Effects */
.group:hover img {
    transform: scale(1.05);
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

</style>

{{-- Main Content - CONTAINER RESPONSIF --}}
<article class="bg-white relative -mt-6 sm:-mt-10 rounded-t-[1.75rem] sm:rounded-t-[2.5rem] shadow-xl mx-auto w-full max-w-full sm:max-w-[96%] lg:max-w-6xl overflow-hidden" id="content">
    <div class="container mx-auto px-3.5 sm:px-6 max-w-6xl py-8 sm:py-10 lg:py-14">
        <div class="grid lg:grid-cols-12 gap-6 lg:gap-10">
            
            {{-- Sidebar Left: Social Share (Desktop Only) --}}
            <aside class="hidden lg:block lg:col-span-1">
                <div class="sticky top-24 flex flex-col items-center gap-4">
                    <span class="text-gray-400 text-xs font-semibold uppercase tracking-widest transform -rotate-90 origin-center whitespace-nowrap mb-8">
                        Share
                    </span>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($artikel->judul . ' - ' . url()->current()) }}" 
                       target="_blank" 
                       class="social-share-btn bg-green-500 text-white hover:bg-green-600"
                       aria-label="Share on WhatsApp">
                        <i class="fab fa-whatsapp text-lg"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                       target="_blank" 
                       class="social-share-btn bg-blue-600 text-white hover:bg-blue-700"
                       aria-label="Share on Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($artikel->judul) }}&url={{ urlencode(url()->current()) }}" 
                       target="_blank" 
                       class="social-share-btn bg-sky-500 text-white hover:bg-sky-600"
                       aria-label="Share on Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
                       target="_blank" 
                       class="social-share-btn bg-blue-700 text-white hover:bg-blue-800"
                       aria-label="Share on LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </aside>

            {{-- Main Content Area --}}
            <main class="lg:col-span-8 min-w-0 w-full max-w-full">
                <div class="article-content min-w-0 w-full max-w-full">
                    @php
                        $blocks = is_array($artikel->isi) ? $artikel->isi : json_decode($artikel->isi, true);
                    @endphp

                    @if(is_array($blocks) && count($blocks) > 0)
                        @foreach($blocks as $block)
                            @if($block['type'] === 'text')
                                <div class="prose-content min-w-0 w-full max-w-full">
                                    {!! $block['value'] !!}
                                </div>
                            @elseif($block['type'] === 'image')
                                <figure class="my-6 sm:my-8">
                                    <img src="{{ asset('storage/' . $block['value']) }}" 
                                         alt="Ilustrasi artikel"
                                         class="w-full h-auto rounded-xl sm:rounded-2xl"
                                         loading="lazy">
                                </figure>
                            @endif
                        @endforeach
                    @else
                        <div class="prose-content min-w-0 w-full max-w-full">
                            {!! nl2br(e($artikel->isi)) !!}
                        </div>
                    @endif
                </div>

                {{-- Tags Section --}}
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex flex-wrap gap-2">
                        <span class="text-sm font-semibold text-gray-500 mr-2">Tags:</span>
                        <a href="#" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-xs font-medium transition-colors">
                            Nutrisi
                        </a>
                        <a href="#" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-xs font-medium transition-colors">
                            Kesehatan
                        </a>
                        <a href="#" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-xs font-medium transition-colors">
                            Makanan
                        </a>
                    </div>
                </div>

                {{-- Author Bio --}}
                <div class="author-card mt-8 sm:mt-10 p-5 sm:p-8">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-5">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-[#16782d] to-[#22a844] flex items-center justify-center text-white text-xl sm:text-2xl font-black shadow-lg shrink-0">
                            W
                        </div>
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="font-playfair text-lg sm:text-xl font-bold text-gray-900 mb-1">
                                Penulis Wowin Food
                            </h3>
                            <p class="text-xs sm:text-sm text-emerald-700 font-semibold mb-2 sm:mb-3">Tim Redaksi & Edukasi Kuliner</p>
                            <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                                Tim redaksi resmi PT Wowin Purnomo Putera yang berdedikasi menyajikan informasi mendalam seputar industri kuliner, resep masakan autentik, efisiensi bahan baku dapur pedagang, dan inovasi bumbu masak berkualitas.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Mobile Social Share --}}
                <div class="lg:hidden mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm font-semibold text-gray-700 mb-4 text-center">Bagikan Artikel</p>
                    <div class="flex justify-center gap-3">
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($artikel->judul . ' - ' . url()->current()) }}" 
                           target="_blank" 
                           class="social-share-btn bg-green-500 text-white">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           target="_blank" 
                           class="social-share-btn bg-blue-600 text-white">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($artikel->judul) }}&url={{ urlencode(url()->current()) }}" 
                           target="_blank" 
                           class="social-share-btn bg-sky-500 text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
                           target="_blank" 
                           class="social-share-btn bg-blue-700 text-white">
                            <i class="fab fa-linkedin-in text-sm"></i>
                        </a>
                    </div>
                </div>
            </main>

            {{-- Sidebar Right --}}
            <aside class="lg:col-span-3">
                <div class="sticky top-24 space-y-6">
                    
                    {{-- Article Info Card --}}
                    <div class="sidebar-card">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-[#16782d]"></i>
                            Info Artikel
                        </h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-500">Status</span>
                                <span class="text-[#16782d] font-semibold flex items-center gap-1">
                                    <i class="fas fa-check-circle text-xs"></i>
                                    Verified
                                </span>
                            </div>
                            {{-- Baris Pengembang dengan Logo WOWIN Bulat --}}
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-500">Pengembang</span>
                                <div class="flex items-center gap-2">
                                   
                                    {{-- Logo Bulat --}}
                                    <div class="w-6 h-6 rounded-full overflow-hidden border border-gray-200 shadow-sm">
                                        <img src="{{ asset('images/lg-h.png') }}" alt="Logo Wowin" class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-500">Pembaruan</span>
                                <span class="text-gray-900 font-semibold">{{ $artikel->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- CTA Card --}}
                    <div class="sidebar-card bg-gradient-to-br from-[#16782d] to-[#0d5a20] text-white p-6 relative overflow-hidden group">
                        <div class="absolute -right-6 -top-6 opacity-10 group-hover:scale-110 transition-transform duration-500">
                            <i class="fas fa-leaf text-8xl"></i>
                        </div>
                        <div class="relative z-10">
                            <i class="fas fa-bell text-2xl mb-3 text-yellow-300"></i>
                            <h4 class="font-bold text-base mb-2 leading-snug">Tetap Update!</h4>
                            <p class="text-xs opacity-90 mb-4 leading-relaxed">
                                Dapatkan artikel terbaru langsung di email Anda.
                            </p>
                            <a href="/contacts" class="block">
                                <button class="cta-btn w-full py-2.5 bg-yellow-400 text-[#0d5a20] rounded-lg font-bold text-xs uppercase tracking-wide hover:bg-white transition-all duration-300 shadow-lg">
                                    Subscribe Sekarang
                                </button>
                            </a>
                        </div>
                    </div>

                    {{-- Popular Tags --}}
                    <div class="sidebar-card">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4 flex items-center gap-2">
                            <i class="fas fa-tags text-[#16782d]"></i>
                            Tag Populer
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            <a href="#" class="px-3 py-1.5 bg-gray-50 hover:bg-[#16782d] hover:text-white text-gray-700 rounded-lg text-xs font-medium transition-all duration-300">
                                #Nutrisi
                            </a>
                            <a href="#" class="px-3 py-1.5 bg-gray-50 hover:bg-[#16782d] hover:text-white text-gray-700 rounded-lg text-xs font-medium transition-all duration-300">
                                #Kesehatan
                            </a>
                            <a href="#" class="px-3 py-1.5 bg-gray-50 hover:bg-[#16782d] hover:text-white text-gray-700 rounded-lg text-xs font-medium transition-all duration-300">
                                #Makanan
                            </a>
                            <a href="#" class="px-3 py-1.5 bg-gray-50 hover:bg-[#16782d] hover:text-white text-gray-700 rounded-lg text-xs font-medium transition-all duration-300">
                                #Tips
                            </a>
                            <a href="#" class="px-3 py-1.5 bg-gray-50 hover:bg-[#16782d] hover:text-white text-gray-700 rounded-lg text-xs font-medium transition-all duration-300">
                                #Lifestyle
                            </a>
                        </div>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</article>

{{-- Reading Progress & Table Mobile Handling Script --}}
<script>
    // Reading progress bar
    const progressBar = document.getElementById('readingProgress');
    if (progressBar) {
        window.addEventListener('scroll', () => {
            const windowHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (window.scrollY / windowHeight) * 100;
            progressBar.style.width = Math.min(scrolled, 100) + '%';
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Auto-wrap & enhance tables for smooth mobile swiping
    document.addEventListener('DOMContentLoaded', function() {
        const articleTables = document.querySelectorAll('.article-content table');
        articleTables.forEach(function(table) {
            let parent = table.parentElement;
            let isWrapped = parent && (parent.classList.contains('overflow-x-auto') || parent.classList.contains('table-wrapper'));
            
            if (!isWrapped) {
                const wrapper = document.createElement('div');
                wrapper.className = 'overflow-x-auto table-wrapper my-6 rounded-xl sm:rounded-2xl border border-emerald-200 shadow-sm';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
                parent = wrapper;
            }

            if (!parent.classList.contains('overflow-x-auto')) {
                parent.classList.add('overflow-x-auto');
            }

            // Insert mobile swipe hint if not already present
            const prevElem = parent.previousElementSibling;
            if (!prevElem || !prevElem.classList.contains('mobile-table-hint')) {
                const hint = document.createElement('div');
                hint.className = 'mobile-table-hint';
                hint.innerHTML = '<i class="fas fa-arrows-left-right text-emerald-600"></i> Geser tabel ke samping ➔';
                parent.parentNode.insertBefore(hint, parent);
            }
        });
    });
</script>

@endsection