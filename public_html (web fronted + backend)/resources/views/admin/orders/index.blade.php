@extends('admin.layouts.master')

@section('title', 'WOWINFood - Kelola Order')

@section('head')
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
@php use Illuminate\Support\Str; @endphp
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gradient-to-r from-white to-green-50 p-6 rounded-2xl shadow-lg border border-gray-200">
        <!-- Breadcrumb -->
        <nav class="flex text-sm mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-[#16782d] flex items-center transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="flex items-center text-gray-500">
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('admin.orders.index') }}" class="hover:text-[#16782d] transition-colors duration-200">Kelola Order</a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Master Order
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-[#16782d] p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-shopping-cart text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Kelola Order</h1>
                <p class="text-gray-600">Halaman ini merupakan Data Master untuk Mengelola Order yang digunakan untuk membuat, update, delete order.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-3">
                    <i class="fas fa-shopping-bag text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Order</p>
                    <p class="text-xl font-bold">{{ $orders->total() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-3">
                    <i class="fas fa-calendar-check text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Hari Ini</p>
                    <p class="text-xl font-bold">{{ $orders->where('created_at', '>=', \Carbon\Carbon::today())->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-3">
                    <i class="fas fa-calendar-week text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Minggu Ini</p>
                    <p class="text-xl font-bold">{{ $orders->where('created_at', '>=', \Carbon\Carbon::now()->startOfWeek())->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex gap-3 mt-4">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-[#16782d] border border-[#16782d] px-4 py-2 rounded-lg hover:bg-[#16782d]/10 transition flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-white bg-[#16782d] px-4 py-2 rounded-lg hover:bg-[#135e24] transition flex items-center">
                <i class="fas fa-shopping-cart mr-2"></i> Master Order
            </a>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-[#16782d] mr-2"></i> Data Order
            </h2>
            
            <!-- Search Box -->
            <div class="relative">
                <form action="{{ route('admin.orders.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Cari order..." 
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#16782d] focus:border-[#16782d] w-64">
                    <button type="submit" class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Filter & Export -->
        <div class="flex justify-between mb-4">
           <!-- Replace existing filter section with this improved version -->
<div class="flex flex-wrap justify-between mb-4">
    <div class="flex flex-wrap gap-2 mb-2 md:mb-0">
        <!-- Date Range Filter -->
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-2">
            <!-- Hidden input to preserve other possible filters -->
            @if(request()->has('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            @if(request()->has('payment_status'))
                <input type="hidden" name="payment_status" value="{{ request('payment_status') }}">
            @endif
            
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Periode:</span>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#16782d] focus:border-[#16782d]">
                <span class="text-sm">s/d</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#16782d] focus:border-[#16782d]">
                
                <button type="submit" class="bg-[#16782d] text-white px-3 py-2 rounded-lg text-sm hover:bg-[#135e24] transition">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                
                @if(request()->hasAny(['start_date', 'end_date', 'status', 'payment_status']))
                    <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-gray-600 transition">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
    
    <div class="flex flex-wrap gap-2">
        <!-- Order Status Filter -->
        <form action="{{ route('admin.orders.index') }}" method="GET" class="inline-block">
            <!-- Preserve date filters if they exist -->
            @if(request()->has('start_date'))
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            @endif
            @if(request()->has('end_date'))
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            @endif
            @if(request()->has('payment_status'))
                <input type="hidden" name="payment_status" value="{{ request('payment_status') }}">
            @endif
            
            <select name="status" onchange="this.form.submit()" 
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#16782d] focus:border-[#16782d]">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Dalam Pengiriman</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </form>
        
        <!-- Payment Status Filter -->
        <form action="{{ route('admin.orders.index') }}" method="GET" class="inline-block">
            <!-- Preserve date filters if they exist -->
            @if(request()->has('start_date'))
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            @endif
            @if(request()->has('end_date'))
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            @endif
            @if(request()->has('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            
            <select name="payment_status" onchange="this.form.submit()" 
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#16782d] focus:border-[#16782d]">
                <option value="">Semua Status Pembayaran</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Belum Dibayar</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
            </select>
        </form>

        <a href="{{ route('admin.orders.create') }}" class="bg-[#16782d] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#135e24] transition flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Order
        </a>
    
        <a href="{{ route('admin.orders.exportPdf') }}{{ request()->getQueryString() ? '?'.request()->getQueryString() : '' }}" target="_blank" class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800 transition flex items-center">
            <i class="fas fa-file-pdf mr-2"></i> Export PDF
        </a>  
    
        <a href="{{ route('admin.orders.exportExcel') }}{{ request()->getQueryString() ? '?'.request()->getQueryString() : '' }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition flex items-center">
            <i class="fas fa-file-excel mr-2"></i> Export Excel
        </a>
    </div>
</div>
            
        </div>
        

        <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b">
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-image mr-2 text-[#16782d]"></i>
                                Bukti Transfer
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2 text-[#16782d]"></i>
                                Nama Lengkap
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-file-invoice mr-2 text-[#16782d]"></i>
                                No. Invoice
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-shopping-basket mr-2 text-[#16782d]"></i>
                                Produk
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-[#16782d]"></i>
                                Tanggal
                            </div>
                        </th>
                        <th class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-cogs mr-2 text-[#16782d]"></i>
                                Aksi
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 border-b border-gray-100 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center">
                                    @if (!empty($order->bukti_transfer)) 
                                        <img src="{{ asset('storage/' . $order->bukti_transfer) }}" 
                                             class="w-16 h-16 object-cover rounded-lg border border-gray-200 shadow-sm" 
                                             alt="{{ $order->bukti_transfer}}">
                                    @else
                                        <div class="w-16 h-16 flex items-center justify-center bg-gray-100 rounded-lg border border-gray-200">
                                            <i class="fas fa-image text-gray-400 text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $order->user->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900 block">{{ $order->invoice_number }}</span>
                                @if(!empty($order->no_resi))
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-red-700 bg-red-50 border border-red-200 px-2 py-0.5 rounded-full mt-1 shadow-xs">
                                        <i class="fas fa-truck text-red-600"></i> J&T: {{ $order->no_resi }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs max-w-xs overflow-hidden">
                                    @forelse ($order->orderItems->take(2) as $item)
                                        <div class="mb-1">
                                            {{ $item->quantity }}x {{ Str::limit($item->product_name, 20) }}
                                        </div>
                                    @empty
                                        <span class="text-gray-500">Tidak ada produk</span>
                                    @endforelse
                                    
                                    @if ($order->orderItems->count() > 2)
                                        <div class="text-xs text-gray-500">+ {{ $order->orderItems->count() - 2 }} produk lainnya</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span>{{ \Carbon\Carbon::parse($order->paid_at)->format('d M Y, H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center gap-1.5">
                                    @if(!empty($order->no_resi))
                                        <!-- Tombol Cepat Cetak Label Thermal J&T -->
                                        <a href="{{ route('admin.orders.jnt-label', $order->id) }}" target="_blank" class="px-2.5 py-1.5 text-white text-xs bg-emerald-600 rounded-md hover:bg-emerald-700 transition flex items-center shadow-xs font-semibold" title="Cetak Label Thermal J&T">
                                            <i class="fas fa-barcode mr-1"></i> Label
                                        </a>
                                    @elseif($order->status !== 'canceled')
                                        <!-- Tombol Cepat Request Pick Up J&T -->
                                        <form action="{{ route('admin.orders.jnt-generate', $order->id) }}" method="POST" onsubmit="return confirm('Request pick up kurir & terbitkan resi resmi J&T Express untuk pesanan #{{ $order->invoice_number }} sekarang?')" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 text-white text-xs bg-red-600 hover:bg-red-700 rounded-md transition flex items-center shadow-xs font-bold whitespace-nowrap" title="Request Pick Up & Terbitkan Resi J&T">
                                                <i class="fas fa-truck-loading mr-1"></i> Pick Up J&T
                                            </button>
                                        </form>
                                    @endif
                                    <div x-data="{ open: false }" class="relative">
                                        <button x-on:click="open = true" class="px-3 py-1.5 text-white text-xs bg-[#16782d] rounded-md hover:bg-[#135e24] transition flex items-center">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </button>
    
                                    <template x-if="open">
                                                <div class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
                                                    <div class="flex items-center justify-center min-h-screen p-4">
                                                        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" x-on:click="open = false"></div>
                                                        
                                                        <div class="relative bg-white w-full max-w-5xl rounded-xl shadow-2xl p-6 max-h-[90vh] overflow-y-auto animate__animated animate__fadeIn animate__faster">
                                                            <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-6">
                                                                <h2 class="text-xl font-bold text-[#16782d] flex items-center">
                                                                    <i class="fas fa-clipboard-list mr-2"></i> Detail Pesanan #{{ $order->invoice_number }}
                                                                </h2>
                                                                <button x-on:click="open = false" class="text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition-colors duration-200">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                
                <!-- Status Banner -->
                <div class="mb-6 p-3 rounded-lg 
                    @if($order->status == 'completed') bg-green-50 border-l-4 border-green-500 @endif
                    @if($order->status == 'pending') bg-yellow-50 border-l-4 border-yellow-500 @endif
                    @if($order->status == 'canceled') bg-red-50 border-l-4 border-red-500 @endif
                    @if($order->status == 'shipped') bg-blue-50 border-l-4 border-blue-500 @endif
                    @if($order->status == 'paid') bg-purple-50 border-l-4 border-purple-500 @endif
                ">
                    <div class="flex items-center">
                        <span class="flex items-center justify-center w-10 h-10 rounded-full 
                            @if($order->status == 'completed') bg-green-100 text-green-600 @endif
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-600 @endif
                            @if($order->status == 'canceled') bg-red-100 text-red-600 @endif
                            @if($order->status == 'shipped') bg-blue-100 text-blue-600 @endif
                            @if($order->status == 'paid') bg-purple-100 text-purple-600 @endif
                        ">
                            @if($order->status == 'completed')
                                <i class="fas fa-check-circle"></i>
                            @elseif($order->status == 'pending')
                                <i class="fas fa-clock"></i>
                            @elseif($order->status == 'canceled')
                                <i class="fas fa-times-circle"></i>
                            @elseif($order->status == 'shipped')
                                <i class="fas fa-shipping-fast"></i>
                            @elseif($order->status == 'paid')
                                <i class="fas fa-money-bill-wave"></i>
                            @endif
                        </span>
                        <div class="ml-3">
                            <h3 class="font-semibold text-gray-800">
                                Status Pesanan: 
                                <span class="font-bold
                                    @if($order->status == 'completed') text-green-600 @endif
                                    @if($order->status == 'pending') text-yellow-600 @endif
                                    @if($order->status == 'canceled') text-red-600 @endif
                                    @if($order->status == 'shipped') text-blue-600 @endif
                                    @if($order->status == 'paid') text-purple-600 @endif
                                ">
                                    @if($order->status == 'completed') SELESAI @endif
                                    @if($order->status == 'pending') MENUNGGU @endif
                                    @if($order->status == 'canceled') DIBATALKAN @endif
                                    @if($order->status == 'shipped') DIKIRIM @endif
                                    @if($order->status == 'paid') DIBAYAR @endif
                                </span>
                            </h3>
                            <p class="text-sm text-gray-600">
                                @if($order->status == 'completed')
                                    Pesanan telah selesai dan sudah diterima pelanggan.
                                @elseif($order->status == 'pending')
                                    Pesanan sedang menunggu pembayaran dari pelanggan.
                                @elseif($order->status == 'canceled')
                                    Pesanan telah dibatalkan.
                                @elseif($order->status == 'shipped')
                                    Pesanan dalam proses pengiriman ke pelanggan.
                                @elseif($order->status == 'paid')
                                    Pembayaran diterima, pesanan siap diproses.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Grid Layout untuk Informasi -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Kolom 1: Informasi Pelanggan -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-user-circle mr-2 text-[#16782d]"></i> Informasi Pelanggan
                            </h3>
                        </div>
                        <div class="p-4 space-y-4">
    <div>
        <div class="flex items-center justify-between">
            <span class="font-bold text-gray-800">{{ $order->user->nama_lengkap }}</span>
            <div class="flex gap-2">
                @php
                    // 1. Bersihkan nomor telepon
                    $rawPhone = $order->user->membership->no_hp ?? $order->user->no_telp ?? '';
                    $noTelp = preg_replace('/[^0-9]/', '', $rawPhone);
                    if (str_starts_with($noTelp, '0')) {
                        $noTelp = '62' . substr($noTelp, 1);
                    }

                    // 2. Susun Daftar Barang
                    $daftarBarang = "";
                    foreach($order->orderItems as $item) {
                        $daftarBarang .= "- " . $item->product_name . " (" . $item->quantity . "x)\n";
                    }

                    // 3. Susun Detail Retur (Jika ada)
                    $detailRetur = "";
                    if($order->total_potongan_retur > 0) {
                        $detailRetur = "\nPotongan Retur: -Rp " . number_format($order->total_potongan_retur, 0, ',', '.');
                    }

                    // 4. Hitung Total Akhir
                    $totalAkhir = $order->total - $order->total_potongan_retur;

                    // 5. Susun Pesan Lengkap
                    $pesan = "Halo kak *" . $order->user->nama_lengkap . "*,\n\n" .
                             "Saya Admin dari *WOWINFood Cabang " . Auth::user()->kantor_cabang . "* ingin mengonfirmasi pesanan Anda dengan nomor Invoice: *" . $order->invoice_number . "*\n\n" .
                             "Barang yang dipesan:\n" . $daftarBarang .
                             $detailRetur . "\n" .
                             "*Total Akhir: Rp " . number_format($totalAkhir, 0, ',', '.') . "*\n\n" .
                             "Mohon segera lakukan pembayaran dan kirimkan bukti transfernya ya kak. Terima kasih!";
                @endphp

                @if(!empty($noTelp))
                <a href="https://wa.me/{{ $noTelp }}?text={{ urlencode($pesan) }}" 
                   target="_blank" 
                   class="bg-green-500 text-white p-2 rounded-full hover:bg-green-600 shadow-md transition-transform hover:scale-110"
                   title="Kirim Konfirmasi WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>
                @endif

                {{-- <a href="tel:{{ $rawPhone }}" 
                   class="bg-blue-500 text-white p-2 rounded-full hover:bg-blue-600 shadow-md transition-transform hover:scale-110"
                   title="Hubungi Via Telepon">
                    <i class="fas fa-phone-alt"></i>
                </a> --}}
            </div>
        </div>
    </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Nomor Telepon / WA</label>
                                <span class="text-sm font-medium">{{ $order->user->membership->no_hp ?? $order->user->no_telp ?? '-' }}</span>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Alamat Pengiriman</label>
                                <div class="flex">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-2 mt-1"></i>
                                    <div class="text-sm">
                                        {!! nl2br(e($order->alamat)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kolom 2: Detail Pembayaran -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-credit-card mr-2 text-[#16782d]"></i> Detail Pembayaran
                            </h3>
                        </div>
                        <div class="p-4 space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Nomor Invoice</label>
                                <div class="flex items-center">
                                    <i class="fas fa-file-invoice text-gray-400 mr-2"></i>
                                    <span class="font-medium">{{ $order->invoice_number }}</span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Metode Pembayaran</label>
                                <div class="flex items-center">
                                    <i class="fas fa-money-check-alt text-gray-400 mr-2"></i>
                                    <span>{{ $order->payment_method }}</span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Total Tagihan</label>
                                    <div class="flex items-center text-gray-900">
                                        <i class="fas fa-money-bill-wave text-gray-400 mr-2"></i>
                                        <span class="font-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Sudah Dibayar</label>
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-gray-400 mr-2"></i>
                                        <span class="font-semibold @if($order->paid_amount >= $order->total) text-green-600 @endif">
                                            Rp {{ number_format($order->paid_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kolom 3: Bukti & Catatan -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-image mr-2 text-[#16782d]"></i> Bukti & Catatan
                            </h3>
                        </div>
                        <div class="p-4 space-y-4">
                            <!-- Bukti Transfer -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-2">Bukti Transfer</label>
                                <div class="flex justify-center">
                                    <div class="w-full h-40 rounded-lg overflow-hidden border border-gray-200 shadow-sm flex items-center justify-center bg-gray-50">
                                        @if (!empty($order->bukti_transfer)) 
                                            <a href="{{ asset('storage/' . $order->bukti_transfer) }}" target="_blank" class="w-full h-full block">
                                                <img src="{{ asset('storage/' . $order->bukti_transfer) }}" 
                                                    class="w-full h-full object-contain hover:scale-105 transition-transform" 
                                                    alt="Bukti Transfer">
                                            </a>
                                        @else
                                            <div class="text-center text-gray-400">
                                                <i class="fas fa-image text-3xl mb-2"></i>
                                                <p class="text-xs">Tidak ada bukti transfer</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Catatan -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Catatan Pelanggan</label>
                                <div class="border border-gray-200 rounded p-3 bg-gray-50 min-h-12 text-sm">
                                    @if(!empty($order->catatan))
                                        {!! nl2br(e($order->catatan)) !!}
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada catatan</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Ekspedisi & Pengiriman J&T Express -->
                <div class="mt-6 bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-xl p-4 shadow-sm">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-red-100">
                        <div class="flex items-center gap-3">
                            <div class="bg-red-600 text-white font-black text-xs px-2.5 py-1.5 rounded-md tracking-wider">
                                J&T EXPRESS
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Pengiriman & Logistik Resmi</h4>
                                <p class="text-xs text-gray-600">Layanan Reguler (EZ) &bull; Tarif Flat VIP Rp 4.000/Kg se-Pulau Jawa</p>
                            </div>
                        </div>
                        <div>
                            @if(!empty($order->no_resi))
                                <span class="bg-green-100 text-green-800 border border-green-300 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1.5">
                                    <i class="fas fa-check-circle text-green-600"></i> Resi Aktif: {{ $order->no_resi }}
                                </span>
                            @else
                                <span class="bg-yellow-100 text-yellow-800 border border-yellow-300 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1.5">
                                    <i class="fas fa-clock text-yellow-600"></i> Resi Belum Diterbitkan
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-3 text-xs">
                        <div>
                            <span class="text-gray-500 block">No. Resi (AWB):</span>
                            <span class="font-bold text-gray-800 text-sm select-all">{{ $order->no_resi ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block">Kode Sortir (DesCode):</span>
                            <span class="font-bold text-gray-800">{{ $order->jnt_des_code ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block">Estimasi Berat Total:</span>
                            <span class="font-bold text-gray-800">{{ ceil($order->total_weight_kg ?? 1) }} Kg</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block">Status Pengiriman:</span>
                            <span class="font-semibold text-gray-800">{{ $order->shipping_status ?? ($order->no_resi ? 'Dalam Pengiriman' : 'Menunggu Diproses') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Produk yang Dipesan -->
                <div class="mt-6">
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-shopping-cart mr-2 text-[#16782d]"></i> Daftar Produk
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-4 py-3 text-left font-medium text-gray-600">Produk</th>
                                        <th class="px-4 py-3 text-center font-medium text-gray-600 w-20">Jumlah</th>
                                        <th class="px-4 py-3 text-right font-medium text-gray-600">Harga Satuan</th>
                                        <th class="px-4 py-3 text-right font-medium text-gray-600">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($order->orderItems as $item)
                                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-gray-800">{{ $item->product_name }}</div>
                                            <div class="text-xs text-gray-500">
                                                SKU: 
                                                @if($item->bundling_id)
                                                    Bundling #{{ $item->bundling_id }}
                                                @else
                                                    Produk #{{ $item->product_id }}
                                                @endif
                                            </div>

                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center h-6 w-10 rounded bg-gray-100 text-gray-800 font-medium">
                                                {{ $item->quantity }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-gray-500 italic">Tidak ada data produk</td>
                                    </tr>
                                    @endforelse
                                </tbody>
<tfoot>
    <tr class="bg-gray-50 font-medium text-gray-800 border-t">
        <td colspan="3" class="px-4 py-3 text-right">Total Pesanan:</td>
        <td class="px-4 py-3 text-right font-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
    </tr>

    {{-- Rincian Retur Bertumpuk --}}
    @if($order->total_potongan_retur > 0)
        <tr class="text-[11px] text-orange-700 bg-orange-50 italic border-t border-orange-100">
            <td colspan="4" class="px-4 py-3 text-right leading-relaxed">
                <div class="font-bold uppercase mb-1 border-b border-orange-200 pb-1 flex justify-between">
                    <span></span>
                    <span>[ Rincian Potongan ]</span>
                </div>
                
                {{-- List Perhitungan --}}
                <div class="space-y-1">
                    @if($order->retur_botol > 0) 
                        <div>{{ $order->retur_botol }} Botol x Rp 1.200 = <span class="font-bold">Rp {{ number_format($order->retur_botol * 1200, 0, ',', '.') }}</span></div>
                    @endif
                    
                    @if($order->retur_jerigen > 0) 
                        <div>{{ $order->retur_jerigen }} Jerigen x Rp 2.500 = <span class="font-bold">Rp {{ number_format($order->retur_jerigen * 2500, 0, ',', '.') }}</span></div>
                    @endif
                    
                    @if($order->qty_rusak > 0) 
                        @php
                            $harga_karton = $order->product_rusak->harga ?? 0;
                            $isi_karton = $order->product_rusak->isi_karton ?? 1;
                            $harga_satuan = $harga_karton / $isi_karton;
                        @endphp
                        <div>
                            {{ $order->qty_rusak }} pcs {{ $order->product_rusak->nama_produk ?? 'Produk' }} 
                            @ Rp {{ number_format($harga_satuan, 0, ',', '.') }} = 
                            <span class="font-bold">Rp {{ number_format($order->qty_rusak * $harga_satuan, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            </td>
        </tr>
        
        <tr class="text-red-600 bg-red-50 font-semibold border-t border-red-100">
            <td colspan="3" class="px-4 py-2 text-right">Total Potongan Retur:</td>
            <td class="px-4 py-2 text-right font-bold text-base">- Rp {{ number_format($order->total_potongan_retur, 0, ',', '.') }}</td>
        </tr>
    @endif

    <tr class="bg-gray-100 font-bold text-gray-900 border-t-2 border-gray-300">
        <td colspan="3" class="px-4 py-3 text-right">Total Akhir (Wajib Bayar):</td>
        <td class="px-4 py-3 text-right text-xl text-[#16782d]">
            Rp {{ number_format($order->total - $order->total_potongan_retur, 0, ',', '.') }}
        </td>
    </tr>
</tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-between mt-6">
                    <!-- Tombol Kiri (Update Status & Cetak) -->
                    <div class="flex flex-wrap gap-2">
                        <!-- Tombol Cetak Invoice -->
                        <a href="{{ route('admin.orders.print-invoice', $order->id) }}" target="_blank" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center text-xs font-semibold">
                            <i class="fas fa-print mr-1"></i> Cetak Nota
                        </a>

                        @if(!empty($order->no_resi))
                            <!-- Tombol Cetak Thermal Label J&T -->
                            <a href="{{ route('admin.orders.jnt-label', $order->id) }}" target="_blank" class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition flex items-center text-xs font-semibold shadow-sm">
                                <i class="fas fa-barcode mr-1.5"></i> Cetak Label J&T
                            </a>
                            <!-- Tombol Lacak J&T -->
                            <a href="{{ \App\Services\JntService::getTrackingUrl($order->no_resi) }}" target="_blank" class="px-3 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition flex items-center text-xs font-semibold">
                                <i class="fas fa-truck mr-1.5"></i> Lacak J&T
                            </a>
                            <!-- Tombol Batal Resi J&T -->
                            <form action="{{ route('admin.orders.jnt-cancel', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan resi J&T ini?')" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition flex items-center text-xs font-semibold">
                                    <i class="fas fa-ban mr-1"></i> Batal Resi J&T
                                </button>
                            </form>
                        @elseif($order->status !== 'canceled')
                            <!-- Tombol Generate Resi J&T Otomatis -->
                            <form action="{{ route('admin.orders.jnt-generate', $order->id) }}" method="POST" onsubmit="return confirm('Request pick up kurir & terbitkan nomor resi resmi J&T Express untuk order ini sekarang?')" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-md hover:from-red-700 hover:to-red-800 transition flex items-center text-xs font-bold shadow-md">
                                    <i class="fas fa-truck-loading mr-1.5"></i> 📦 Request Pick Up & Buat Resi J&T
                                </button>
                            </form>
                        @endif
                        
                        @if($order->status == 'pending')
                        <button class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition flex items-center">
                            <i class="fas fa-check-circle mr-1"></i> Konfirmasi Pembayaran
                        </button>
                        @endif
                        
                        @if($order->status == 'paid')
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center">
                            <i class="fas fa-shipping-fast mr-1"></i> Kirim Pesanan
                        </button>
                        @endif
                        
                        @if($order->status == 'pending')
                        <button class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition flex items-center">
                            <i class="fas fa-times-circle mr-1"></i> Batalkan Pesanan
                        </button>
                        @endif
                    </div>
                    
                    <!-- Tombol Tutup (Kanan) -->
                    <div>
                        <button x-on:click="open = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition flex items-center">
                            <i class="fas fa-times mr-1"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>                               
                                        <!-- Edit -->
                                        <div x-data="{ showEditModal: false }">
                                                <button type="button" @click="showEditModal = true"
                                                    class="px-3 py-1.5 text-white rounded-md text-xs bg-blue-500 hover:bg-blue-600 transition flex items-center">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </button>
                                    
                        <template x-if="showEditModal">
                                    <div class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
                                        <div class="flex items-center justify-center min-h-screen p-4">
                                            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showEditModal = false"></div>

                                            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col animate__animated animate__fadeInUp animate__faster">
                                                
                                                <div class="px-6 py-4 border-b flex justify-between items-center bg-white rounded-t-xl">
                                                    <h2 class="text-xl font-bold text-gray-800">Edit Data Order</h2>
                                                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 p-2">
                                                        <i class="fas fa-times text-xl"></i>
                                                    </button>
                                                </div>

                                                <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
                                                    @if ($errors->any())
                                                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 text-sm">
                                                            @foreach ($errors->all() as $error)
                                                                <p>{{ $error }}</p>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <form method="POST" action="{{ route('admin.orders.update', $order->id) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 text-left">Nama Lengkap :</label>
                                                                <input type="text" name="nama_lengkap" value="{{ $order->user->nama_lengkap}}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 text-left">Metode Pembayaran</label>
                                                                <select name="payment_method" id="payment_method" onchange="toggleMembershipForm()" style="width: 100%; padding: 8px; border: 1px solid #ccc;">
                                                                    <option value="wa" {{ old('payment_method') == 'wa' ? 'selected' : '' }}>WhatsApp</option>
                                                                    <option value="cod" {{ old('payment_method') == 'code' ? 'selected' : '' }}>Cash On Delivery (COD)</option>
                                                                    <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Rekening Bank</option>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 text-left">Status Pesanan</label>
                                                                <select name="status" id="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                                {{-- Pastikan atribut 'selected' memeriksa data dari database ($order->status) --}}
                                                                <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                <option value="paid" {{ old('status', $order->status) == 'paid' ? 'selected' : '' }}>Dibayar</option>
                                                                <option value="shipped" {{ old('status', $order->status) == 'shipped' ? 'selected' : '' }}>Dalam Pengiriman</option>
                                                                <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Pesanan Selesai</option>
                                                                <option value="canceled" {{ old('status', $order->status) == 'canceled' ? 'selected' : '' }}>Pesanan Batal</option>
                                                            </select>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 text-left">Status Pembayaran</label>
                                                                <select name="payment_status" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                                                    <option value="pending" {{ old('payment_status', $order->payment_status) == 'pending' ? 'selected' : '' }}>Belum Dibayar</option>
                                                                    <option value="paid" {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 text-left">Jumlah yang dibayarkan :</label>
                                                                <input type="text" name="paid_amount" value="{{ $order->paid_amount}}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 text-left">Total :</label>
                                                                <input type="text" name="total" value="{{ $order->total}}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                            </div>
                                                            
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 text-left">Terbayar Pada :</label>
                                                                <input type="datetime-local" name="paid_at"
                                                                value="{{ old('paid_at', \Carbon\Carbon::parse($order->paid_at)->format('Y-m-d\TH:i')) }}"
                                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200" />
                                                            
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 text-left">Alamat :</label>
                                                                <input type="text" name="alamat" value="{{ $order->alamat}}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 text-left">Catatan :</label>
                                                                <input type="text" name="catatan" value="{{ $order->catatan}}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                            </div>
                                                            <div class="col-span-2 mt-4 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                                                                <h3 class="text-sm font-bold text-orange-800 mb-3 flex items-center">
                                                                    <i class="fas fa-undo-alt mr-2"></i> Input Retur & Potongan Nota
                                                                </h3>
                                                                
                                                                <div class="grid grid-cols-2 gap-4">
                                                                    <div>
                                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Botol 625ml Kosong (Biji)</label>
                                                                        <input type="number" name="retur_botol" value="{{ $order->retur_botol ?? 0 }}" min="0"
                                                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-orange-500">
                                                                        <p class="text-[10px] text-gray-500 mt-1">*Otomatis potong Rp 1.200/biji</p>
                                                                    </div>

                                                                    <div>
                                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Jerigen 5L Kosong (Biji)</label>
                                                                        <input type="number" name="retur_jerigen" value="{{ $order->retur_jerigen ?? 0 }}" min="0"
                                                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-orange-500">
                                                                        <p class="text-[10px] text-gray-500 mt-1">*Otomatis potong Rp 2.500/biji</p>
                                                                    </div>

                                                                    <div class="col-span-2">
                                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Pilih Barang Rusak/Cacat</label>
                                                                        <select name="produk_rusak_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                                                            <option value="">-- Tidak ada barang rusak --</option>
                                                                            @foreach($order->orderItems as $item)
                                                                                <option value="{{ $item->product_id }}" {{ $order->produk_rusak_id == $item->product_id ? 'selected' : '' }}>
                                                                                    {{ $item->product_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div>
                                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Qty Rusak (Biji)</label>
                                                                        <input type="number" name="qty_rusak" value="{{ $order->qty_rusak ?? 0 }}" min="0"
                                                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                                                    </div>
                                                                    
                                                                    <div>
                                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Tipe Retur</label>
                                                                        <select name="tipe_retur" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                                                            <option value="">-- Pilih Tipe --</option>
                                                                            <option value="rusak_pengiriman" {{ $order->tipe_retur == 'rusak_pengiriman' ? 'selected' : '' }}>Rusak Pengiriman</option>
                                                                            <option value="cacat_produksi" {{ $order->tipe_retur == 'cacat_produksi' ? 'selected' : '' }}>Cacat Produksi</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-span-2">
                                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Bukti Kerusakan</label>
                                                                        @if($order->foto_kerusakan)
                                                                            <div class="mb-2">
                                                                                <img src="{{ asset('storage/' . $order->foto_kerusakan) }}" class="w-20 h-20 object-cover rounded border">
                                                                            </div>
                                                                        @endif
                                                                        <input type="file" name="foto_kerusakan" class="text-xs">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                            <label class="block text-sm font-medium text-gray-700 text-left">Gambar</label>
                                                            
                                                            <div class="w-24 h-24 mx-auto mb-2 rounded-full overflow-hidden border border-gray-300">
                                                                @if (!empty($order->bukti_transfer)) 
                                                                <img src="{{ asset('storage/' . $order->bukti_transfer) }}" 
                                                                class="w-full h-full object-cover" 
                                                                alt="{{ $order->bukti_transfer}}">
                                                                @else
                                                                    <span class="text-gray-500 flex items-center justify-center h-full">No Image</span>
                                                                @endif
                                                            </div>
                                                            
                                                                <input type="file" name="bukti_transfer"
                                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                            
                                                            <p class="text-xs text-gray-500 mt-1 text-left">Kosongkan jika tidak ingin mengubah foto.</p>
                                                            </div>
                                                            
                                                            

                                                        </div>
                                                        <div class="flex justify-end mt-4 space-x-2">
                                                            <button type="button" @click="showEditModal = false"
                                                                class="px-4 py-2 text-white rounded-md text-xs bg-gray-400 hover:bg-gray-500">
                                                                Batal
                                                            </button>
                                                            <button type="submit"
                                                                class="px-4 py-2 text-white rounded-md text-xs bg-blue-600 hover:bg-blue-700">
                                                                Simpan Perubahan
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                
                                    <!-- Hapus -->
                                    <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus?');" action="{{ route('admin.orders.destroy', $order->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 text-white rounded-md text-xs bg-red-500 hover:bg-red-600 transition flex items-center">
                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-red-500 py-4">Data Artikel belum ada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
<!-- Toast Notification -->
<div id="toast-success" class="fixed top-20 right-4 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm hidden" role="alert">
    <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
        </svg>
        <span class="sr-only">Check icon</span>
    </div>
    <div class="ms-3 text-sm font-normal">Data berhasil diperbaharui</div>
    <button type="button" id="close-toast" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
    </button>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if (session('success'))
            document.getElementById('toast-success').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('toast-success').classList.add('hidden');
            }, 3000);
        @endif
        @if (session('error'))
            // Handle error toast if needed
        @endif

        document.getElementById('close-toast').addEventListener('click', function() {
            document.getElementById('toast-success').classList.add('hidden');
        });
    });
</script>
@endsection
