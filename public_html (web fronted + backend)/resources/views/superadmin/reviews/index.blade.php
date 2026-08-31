@extends('superadmin.layouts.master')

@section('title', 'WOWINFood - Pusat Moderasi Ulasan')

@section('head')
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gradient-to-r from-white to-purple-50 p-6 rounded-2xl shadow-lg border border-gray-200 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-purple-700 p-3 rounded-full mr-4 shadow-md text-white">
                    <i class="fas fa-star-half-alt text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-1">Pusat Moderasi Ulasan & Rating</h1>
                    <p class="text-gray-600">Pantau, moderasi, dan balas ulasan pelanggan dari seluruh kantor cabang.</p>
                </div>
            </div>
            <div>
                <a href="{{ route('superadmin.branch-settings.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-purple-600 text-purple-700 rounded-xl text-sm font-semibold hover:bg-purple-50 transition shadow-sm">
                    <i class="fab fa-google text-red-500 mr-2"></i> Konfigurasi Link Google Maps PT
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Cabang & Rating -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center gap-3">
        <span class="text-sm font-semibold text-gray-700">Filter Cabang:</span>
        <a href="{{ route('superadmin.reviews.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ !request('kantor_cabang') ? 'bg-purple-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Semua Cabang</a>
        @foreach(['Trenggalek', 'Kediri', 'Madiun', 'Solo', 'Jogja', 'Cirebon', 'Kudus', 'Bogor', 'Serang'] as $cb)
            <a href="{{ route('superadmin.reviews.index', ['kantor_cabang' => $cb, 'rating' => request('rating')]) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('kantor_cabang') == $cb ? 'bg-purple-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                {{ $cb }}
            </a>
        @endforeach
    </div>

    <!-- Review List -->
    <div class="space-y-4">
        @forelse($reviews as $rev)
            <div class="bg-white p-6 rounded-2xl shadow-sm border {{ $rev->is_hidden ? 'border-red-200 bg-red-50/30' : 'border-gray-200' }} hover:shadow-md transition">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 pb-4 border-b border-gray-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-800 text-base">{{ $rev->is_anonymous ? (substr($rev->user->name ?? 'User', 0, 1) . '***') : ($rev->user->name ?? 'Pengguna Wowin') }}</span>
                            <span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full font-bold uppercase">{{ $rev->kantor_cabang ?? 'Pusat' }}</span>
                            @if($rev->is_anonymous)
                                <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-medium">Anonim</span>
                            @endif
                            @if($rev->is_hidden)
                                <span class="text-[10px] bg-red-100 text-red-700 px-2 py-0.5 rounded font-bold">Disembunyikan</span>
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

                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1 text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $rev->rating ? 'fas' : 'far' }} fa-star text-base"></i>
                            @endfor
                        </div>

                        <!-- Tombol Sembunyikan / Tampilkan -->
                        <form action="{{ route('superadmin.reviews.toggle-visibility', $rev->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-xs px-2.5 py-1 rounded-lg font-medium {{ $rev->is_hidden ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}" title="{{ $rev->is_hidden ? 'Tampilkan ke publik' : 'Sembunyikan ulasan ini' }}">
                                {{ $rev->is_hidden ? 'Tampilkan' : 'Sembunyikan' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tags & Komentar -->
                <div class="mt-4">
                    @if(!empty($rev->tags) && is_array($rev->tags))
                        <div class="flex flex-wrap gap-1 mb-2">
                            @foreach($rev->tags as $t)
                                <span class="text-[11px] bg-purple-50 text-purple-700 px-2.5 py-0.5 rounded-full font-medium">✓ {{ $t }}</span>
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

                <!-- Balasan Superadmin / Admin -->
                @if($rev->balasan_admin)
                    <div class="mt-4 p-3 bg-purple-50/50 rounded-xl border-l-4 border-purple-700 text-xs">
                        <span class="font-bold text-gray-800">Balasan Resmi:</span>
                        <p class="text-gray-600 mt-1">{{ $rev->balasan_admin }}</p>
                        <span class="text-[10px] text-gray-400 block mt-1">{{ $rev->balasan_admin_at ? $rev->balasan_admin_at->format('d M Y H:i') : '' }}</span>
                    </div>
                @else
                    <form action="{{ route('superadmin.reviews.reply', $rev->id) }}" method="POST" class="mt-4 pt-3 border-t border-gray-100 flex gap-2">
                        @csrf
                        <input type="text" name="balasan_admin" placeholder="Tulis tanggapan resmi Superadmin..." required class="flex-1 text-xs border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-700 focus:border-purple-700">
                        <button type="submit" class="bg-purple-700 hover:bg-purple-800 text-white text-xs px-4 py-2 rounded-lg font-semibold transition">Kirim Respon</button>
                    </form>
                @endif
            </div>
        @empty
            <div class="bg-white p-12 text-center rounded-2xl border border-dashed border-gray-200">
                <i class="fas fa-star text-4xl text-gray-300 mb-3"></i>
                <h3 class="font-bold text-gray-700">Belum Ada Ulasan</h3>
                <p class="text-xs text-gray-400 mt-1">Ulasan dari seluruh pengguna aplikasi dan web akan dikompilasi di sini.</p>
            </div>
        @endforelse

        <div class="pt-4">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection
