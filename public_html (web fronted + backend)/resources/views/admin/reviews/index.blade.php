@extends('admin.layouts.master')

@section('title', 'WOWINFood - Ulasan Pelanggan Cabang')

@section('head')
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gradient-to-r from-white to-green-50 p-6 rounded-2xl shadow-lg border border-gray-200 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-[#16782d] p-3 rounded-full mr-4 shadow-md text-white">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-1">Ulasan Pelanggan (Cabang {{ $kantorCabang }})</h1>
                    <p class="text-gray-600">Pantau feedback dan balas ulasan pelanggan pesanan di cabang Anda.</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.branch_settings.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-[#16782d] text-[#16782d] rounded-xl text-sm font-semibold hover:bg-green-50 transition shadow-sm">
                    <i class="fab fa-google text-red-500 mr-2"></i> Pengaturan Link Google Maps
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Rating -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center gap-3">
        <span class="text-sm font-semibold text-gray-700">Filter Bintang:</span>
        <a href="{{ route('admin.reviews.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ !request('rating') ? 'bg-[#16782d] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Semua</a>
        @foreach([5, 4, 3, 2, 1] as $star)
            <a href="{{ route('admin.reviews.index', ['rating' => $star]) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('rating') == $star ? 'bg-[#16782d] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                {{ $star }} ★
            </a>
        @endforeach
    </div>

    <!-- Review List -->
    <div class="space-y-4">
        @forelse($reviews as $rev)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 pb-4 border-b border-gray-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-800 text-base">{{ $rev->is_anonymous ? (substr($rev->user->name ?? 'User', 0, 1) . '***') : ($rev->user->name ?? 'Pengguna Wowin') }}</span>
                            @if($rev->is_anonymous)
                                <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-medium">Anonim</span>
                            @endif
                            <span class="text-xs text-gray-400">• {{ $rev->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            <span>Produk: <strong>{{ $rev->product->nama_produk ?? 'Produk Wowin' }}</strong></span>
                            @if($rev->order)
                                <span class="ml-2">| No. Invoice: <strong>{{ $rev->order->invoice_number }}</strong></span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-1 text-amber-400">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $rev->rating ? 'fas' : 'far' }} fa-star text-base"></i>
                        @endfor
                    </div>
                </div>

                <!-- Tags & Komentar -->
                <div class="mt-4">
                    @if(!empty($rev->tags) && is_array($rev->tags))
                        <div class="flex flex-wrap gap-1 mb-2">
                            @foreach($rev->tags as $t)
                                <span class="text-[11px] bg-green-50 text-green-700 px-2.5 py-0.5 rounded-full font-medium">✓ {{ $t }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if($rev->komentar)
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $rev->komentar }}</p>
                    @endif

                    <!-- Foto Ulasan -->
                    @if(!empty($rev->foto) && is_array($rev->foto))
                        <div class="flex flex-wrap gap-2 mt-3">
                            @foreach($rev->foto as $img)
                                <a href="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}" target="_blank" class="block border rounded-lg overflow-hidden">
                                    <img src="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}" class="w-16 h-16 object-cover" alt="Foto">
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Balasan Admin -->
                @if($rev->balasan_admin)
                    <div class="mt-4 p-3 bg-gray-50 rounded-xl border-l-4 border-[#16782d] text-xs">
                        <span class="font-bold text-gray-800">Balasan Anda:</span>
                        <p class="text-gray-600 mt-1">{{ $rev->balasan_admin }}</p>
                        <span class="text-[10px] text-gray-400 block mt-1">{{ $rev->balasan_admin_at ? $rev->balasan_admin_at->format('d M Y H:i') : '' }}</span>
                    </div>
                @else
                    <form action="{{ route('admin.reviews.reply', $rev->id) }}" method="POST" class="mt-4 pt-3 border-t border-gray-100 flex gap-2">
                        @csrf
                        <input type="text" name="balasan_admin" placeholder="Tulis balasan untuk pelanggan ini..." required class="flex-1 text-xs border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#16782d] focus:border-[#16782d]">
                        <button type="submit" class="bg-[#16782d] hover:bg-[#136325] text-white text-xs px-4 py-2 rounded-lg font-semibold transition">Balas</button>
                    </form>
                @endif
            </div>
        @empty
            <div class="bg-white p-12 text-center rounded-2xl border border-dashed border-gray-200">
                <i class="fas fa-star text-4xl text-gray-300 mb-3"></i>
                <h3 class="font-bold text-gray-700">Belum Ada Ulasan</h3>
                <p class="text-xs text-gray-400 mt-1">Ulasan dari pelanggan cabang ini akan muncul di sini setelah pesanan diselesaikan.</p>
            </div>
        @endforelse

        <div class="pt-4">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection
