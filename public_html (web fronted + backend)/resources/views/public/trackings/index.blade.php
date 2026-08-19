@extends('public.layouts.app')

@section('head')
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<meta name="description" content="WOWINFood - Temukan berbagai produk berkualitas dengan harga terbaik">
    <style>
        /* Impor Font Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        /* Terapkan Font Poppins */
        .container {
            font-family: 'Poppins', sans-serif;
        }
    </style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-10 relative">
    <!-- Background Shape -->
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute w-96 h-96 bg-green-100 rounded-full blur-3xl opacity-40 top-0 left-[-100px]"></div>
        <div class="absolute w-72 h-72 bg-green-200 rounded-full blur-2xl opacity-30 bottom-10 right-[-80px]"></div>
        <div class="absolute w-60 h-60 bg-green-300 rounded-full blur-xl opacity-20 top-1/2 left-[60%] translate-x-[-50%] -translate-y-1/2"></div>
    </div>

    <!-- Header -->
    <div class="mb-12 text-center relative">
        <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 w-72 h-72 bg-green-200 opacity-30 blur-3xl rounded-full -z-10"></div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-green-700 tracking-tight">
            <span class="bg-gradient-to-r from-green-700 to-green-500 text-transparent bg-clip-text">Pesanan Saya</span>
        </h1>
        <p class="mt-3 text-gray-500 text-sm md:text-base max-w-xl mx-auto">
            Lacak dan kelola <span class="text-green-600 font-medium">semua pesanan</span> Anda dengan lebih mudah dan cepat
        </p>
        <div class="mt-6 flex justify-center">
            <div class="h-1 w-24 bg-gradient-to-r from-green-600 via-green-400 to-green-600 rounded-full animate-pulse"></div>
        </div>
    </div>

    <!-- Filter & Action -->
<div class="mb-8">
    {{-- 1. TAMPILAN DESKTOP --}}
    <div class="hidden md:flex flex-row justify-between items-center gap-4">
        <div class="flex flex-wrap gap-2">
            @php $status = request('status'); @endphp

            <a href="{{ route('public.trackings.index') }}"
               class="px-4 py-2 rounded-full text-xsx font-medium transition shadow
                      {{ !$status ? 'bg-green-700 text-white hover:bg-green-800' : 'bg-white border border-gray-300 text-gray-600 hover:bg-gray-100' }}">
                Semua
            </a>

            @foreach ([
                'pending' => 'Belum Bayar',
                'shipped' => 'Dikirim',
                'completed' => 'Selesai',
                'canceled' => 'Dibatalkan'
            ] as $key => $label)
                <a href="{{ route('public.trackings.index', ['status' => $key]) }}"
                   class="px-4 py-2 rounded-full text-sm font-medium transition shadow
                          {{ $status === $key ? 'bg-green-700 text-white hover:bg-green-800' : 'bg-white border border-gray-300 text-gray-600 hover:bg-gray-100' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="flex-shrink-0">
            <button onclick="showLoadingAndRedirect('{{ route('products') }}')" 
                class="px-5 py-2.5 bg-green-700 text-white rounded-full text-sm font-medium flex items-center gap-2 hover:bg-green-800 shadow transition">
                <i class="fas fa-plus text-sm"></i> Tambah Pesanan
            </button>
        </div>
    </div>

    {{-- 2. TAMPILAN MOBILE --}}
    <div class="flex md:hidden flex-row justify-between items-center gap-3" x-data="{ open: false }">
        @php 
            $labels = [
                'pending' => 'Belum Bayar',
                'shipped' => 'Dikirim',
                'completed' => 'Selesai',
                'canceled' => 'Dibatalkan'
            ];
            $currentLabel = $status && isset($labels[$status]) ? $labels[$status] : 'Semua Pesanan';
        @endphp

        <div class="relative flex-1">
            <button @click="open = !open" 
                class="w-full flex items-center justify-between px-4 py-2.5 bg-white border border-gray-200 rounded-2xl shadow-sm text-sm font-medium text-gray-700">
                <span class="truncate">{{ $currentLabel }}</span>
                <svg class="w-4 h-4 ml-2 transition-transform duration-200 text-gray-400" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="open" 
                 x-cloak
                 @click.away="open = false"
                 x-transition
                 class="absolute left-0 right-0 mt-2 z-50 bg-white border border-gray-100 rounded-2xl shadow-xl overflow-hidden">
                <a href="{{ route('public.trackings.index') }}" 
                   class="block px-5 py-3 text-sm {{ !$status ? 'bg-green-50 text-green-700 font-bold' : 'text-gray-600' }}"> Semua </a>
                @foreach ($labels as $key => $label)
                    <a href="{{ route('public.trackings.index', ['status' => $key]) }}" 
                       class="block px-5 py-3 text-sm {{ $status === $key ? 'bg-green-50 text-green-700 font-bold' : 'text-gray-600' }} border-t border-gray-50">
                       {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="flex-shrink-0">
            <button onclick="showLoadingAndRedirect('{{ route('products') }}')" 
                class="bg-[#16782d] text-white rounded-2xl shadow-lg w-[46px] h-[46px] flex items-center justify-center active:scale-90 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Modal Loading -->
<div id="loadingModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg p-6 flex flex-col items-center">
        <svg class="animate-spin h-8 w-8 text-green-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
        </svg>
        <p class="text-gray-700 font-medium">Sedang memuat, harap tunggu...</p>
    </div>
</div>

<script>
    function showLoadingAndRedirect(url) {
        // Tampilkan modal loading
        document.getElementById('loadingModal').classList.remove('hidden');

        // Redirect setelah 1.5 detik
        setTimeout(() => {
            window.location.href = url;
        }, 1500);
    }
</script>



    </div>

    <!-- Search -->
    <div class="mb-6 md:mb-10">
    <div class="relative group">
        <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base md:text-lg transition-colors group-focus-within:text-green-600"></i>
        
        <input type="text" 
            placeholder="Cari Toko, Produk, atau Pesanan..." 
            class="w-full pl-11 pr-4 py-2 md:py-3 rounded-full border border-gray-200 text-xs md:text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition-all">
    </div>
</div>

   <div class="space-y-6">
        @foreach ($orders as $order)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
           <div class="flex flex-row items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50/30 gap-2">
                <div class="flex flex-col">
                    <span class="text-[9px] uppercase font-bold text-gray-400 leading-none mb-1">Nomor Pesanan</span>
                    <span class="text-[11px] md:text-sm font-bold text-gray-800 tracking-tight">#{{ $order->invoice_number }}</span>
                </div>
               <div class="flex-shrink-0">
        <div class="text-[10px] md:text-sm text-green-700 font-bold bg-green-50 px-2.5 py-1 rounded-lg border border-green-100 shadow-sm flex items-center gap-1">
                        @php
                            $statusMap = [
                                'pending'   => 'Menunggu Pembayaran',
                                'paid'      => 'Sudah Dibayar',
                                'shipped'   => 'Disimpan/Dikirim',
                                'completed' => 'Pesanan Selesai',
                                'canceled'  => 'Dibatalkan',
                            ];
                        @endphp
                        {{ $statusMap[$order->status] ?? $order->status }}
                    </div>
                </div>
            </div>

{{-- Loop Produk --}}
            @foreach ($order->orderItems as $item)
            <div class="flex items-center gap-4 px-4 py-3 bg-white border-b border-gray-50 last:border-0">
                <div class="relative">
                    @php
                        $isBundling = $item->bundling_id !== null;
                        $imagePath = $isBundling ? ($item->bundling?->barang_bundling ?? null) : ($item->product?->images?->first()?->image_url ?? null);
                    @endphp
                    <img src="{{ asset('storage/' . $imagePath) }}" class="w-14 h-14 rounded-xl object-cover shadow-sm border" alt="Produk">
                </div>

                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">{{ $item->product_name }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        {{-- Badge Satuan --}}
                        <span class="text-[10px] px-2 py-0.5 rounded-full {{ str_contains($item->product_name, '(Karton)') ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600' }} font-bold uppercase">
                            {{ $item->quantity }} {{ str_contains($item->product_name, '(Karton)') ? 'Karton' : 'Pcs' }}
                        </span>
                    </div>
                </div>

                <div class="text-right flex flex-col justify-center">
                    @php
                        $hargaAsli = $item->price * $item->quantity;
                        $hargaBayar = $hargaAsli - ($item->discount_amount ?? 0);
                    @endphp

                    @if($item->discount_amount > 0)
                        <p class="text-[11px] text-gray-400 line-through">Rp{{ number_format($hargaAsli, 0, ',', '.') }}</p>
                    @endif
                    
                    <p class="text-sm font-bold text-green-700">
                        Rp{{ number_format($hargaBayar, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            @endforeach

            {{-- Baris Potongan Retur --}}
            @if($order->total_potongan_retur > 0)
                <div class="px-4 py-2 bg-orange-50 border-t border-orange-100 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-orange-700 text-[10px] font-bold uppercase tracking-wider">
                        <i class="ri-arrow-go-back-line"></i> Potongan Nota (Retur/Botol)
                    </div>
                    <span class="text-orange-700 font-bold text-xs">- Rp{{ number_format($order->total_potongan_retur, 0, ',', '.') }}</span>
                </div>
            @endif

            {{-- Footer Pesanan: Total & Tombol Aksi --}}
{{-- Footer Pesanan: Total & Tombol Aksi --}}
<div class="flex flex-col md:flex-row justify-between items-center px-4 py-4 border-t border-gray-100 bg-gray-50/50 gap-4">
    
    <div class="hidden md:block text-[10px] text-gray-400 uppercase font-bold tracking-widest">
        @php
            // Mengambil setting berdasarkan admin yang menangani (claim) member ini
            $adminSetting = \App\Models\BranchSetting::where('user_id', $order->user->admin_id)->first();
            
            // Jika tidak ada, gunakan setting cabang sebagai cadangan
            if(!$adminSetting) {
                $adminSetting = \App\Models\BranchSetting::where('enum_value', $order->user->kantor_cabang)->first();
            }
        @endphp
        {{ $adminSetting->nama_pt ?? 'PT SANKE BERSINAR TERANG' }}
    </div>

   <div class="flex flex-row items-center justify-between md:justify-end gap-3 md:gap-6 w-full md:w-auto">
    
    <div class="text-left md:text-right flex-shrink-0">
        <span class="block text-[9px] md:text-[10px] text-gray-400 uppercase font-bold tracking-tight">Total Wajib Bayar</span>
        <span class="text-base md:text-lg font-black text-gray-900 leading-none">
            Rp {{ number_format($order->total - $order->total_potongan_retur, 0, ',', '.') }}
        </span>
    </div>

    <div class="flex items-center gap-2 flex-shrink-0">
        {{-- Tombol Hubungi WhatsApp --}}
        @php
            // Logic admin dinamis
            $adminSetting = \App\Models\BranchSetting::where('user_id', $order->user->admin_id)->first() 
                            ?? \App\Models\BranchSetting::where('enum_value', $order->user->kantor_cabang)->first();
            
            $noTujuan = $adminSetting->no_telp ?? '6281216301220';
            $cleanNo = preg_replace('/[^0-9]/', '', $noTujuan);
            if (str_starts_with($cleanNo, '0')) { $cleanNo = '62' . substr($cleanNo, 1); }
            
            $teksKonfirmasi = "Halo admin " . ($adminSetting->nama_pt ?? '') . ", saya ingin konfirmasi pesanan #" . $order->invoice_number . " atas nama " . $order->user->nama_lengkap;
        @endphp

        <a href="https://wa.me/{{ $cleanNo }}?text={{ urlencode($teksKonfirmasi) }}" 
           target="_blank" 
           class="flex items-center justify-center min-w-[40px] h-10 w-10 md:w-auto md:px-4 md:py-2 bg-emerald-500 text-white rounded-full md:rounded-lg hover:bg-emerald-600 shadow-sm transition-all">
            <svg class="w-6 h-6 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.445 0 .01 5.437.01 12.045c0 2.112.552 4.173 1.598 6.011L0 24l6.105-1.602a11.821 11.821 0 005.937 1.587h.005c6.604 0 12.039-5.436 12.04-12.045a11.782 11.782 0 00-3.41-8.515z"></path>
            </svg>
            <span class="hidden md:inline ml-2 text-xs font-bold uppercase tracking-wider">Hubungi</span>
        </a>

        {{-- Tombol Detail --}}
        @if($order->payment_status === 'paid' || $order->status === 'completed')
            <a href="{{ route('public.trackings.nota', ['id' => $order->id]) }}" 
               class="whitespace-nowrap px-5 py-2.5 text-[11px] md:text-xs rounded-full md:rounded-lg font-bold bg-green-700 text-white hover:bg-green-800 transition shadow-md uppercase tracking-wider">
                Detail
            </a>
        @endif
    </div>
</div>
</div>
        </div> {{-- Penutup Wrapper Pesanan Putih --}}
        @endforeach
                        </div>

                        <!-- Modal Nota -->
                        <div id="notaModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
                            <div class="bg-white rounded-lg p-6 w-96 max-h-[90vh] overflow-y-auto">
                                <div id="notaContent">Loading...</div>
                                <button onclick="closeModal()" class="mt-4 px-4 py-2 bg-green-600 text-white rounded w-full">Tutup</button>
                            </div>
                        </div>
                    </div>
                    @endsection

@section('scripts')
<script>
// Define functions in the global scope
window.showNota = function(id) {
    console.log("Fetching nota for ID:", id);
    fetch('/nota/' + id)
        .then(res => res.text())
        .then(html => {
            document.getElementById('notaContent').innerHTML = html;
            document.getElementById('notaModal').classList.remove('hidden');
        })
        .catch(err => {
            console.error("Error loading nota:", err);
            document.getElementById('notaContent').innerHTML = '<p class="text-red-500">Gagal memuat nota.</p>';
            document.getElementById('notaModal').classList.remove('hidden');
        });
};

window.closeModal = function() {
    document.getElementById('notaModal').classList.add('hidden');
};

window.toggleModal = function() {
    document.getElementById('notaModal').classList.toggle('hidden');
};

// Add event listeners once the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM fully loaded");
    
    // Optional: Add event listeners to buttons if you prefer not using onclick attributes
    document.querySelectorAll('.detail-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            window.showNota(orderId);
        });
    });
});
</script>
@endsection
