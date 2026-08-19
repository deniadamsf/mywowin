@extends('admin.layouts.master')

@section('title', 'WOWINFood - Rekap Retur & Stok')

@section('head')
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gradient-to-r from-white to-orange-50 p-6 rounded-2xl shadow-lg border border-gray-200">
        <nav class="flex text-sm mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-[#16782d] flex items-center transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Rekap Retur Cabang
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-orange-600 p-3 rounded-full mr-4 shadow-md text-white">
                <i class="fas fa-history text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Rekap Retur & Stok Kemasan</h1>
                <p class="text-gray-600">Laporan akumulasi botol/jerigen kosong dan barang rusak di Cabang: <span class="font-bold text-orange-600">{{ Auth::user()->kantor_cabang }}</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-blue-100 flex items-center hover:shadow-md transition">
                <div class="bg-blue-100 p-4 rounded-lg mr-4 text-blue-600">
                    <i class="fas fa-bottle-water text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Botol 625ml Terkumpul</p>
                    <p class="text-2xl font-black text-blue-700">{{ number_format($totalBotol) }} <span class="text-sm font-normal">Biji</span></p>
                </div>
            </div>
            
            <div class="bg-white p-5 rounded-xl shadow-sm border border-green-100 flex items-center hover:shadow-md transition">
                <div class="bg-green-100 p-4 rounded-lg mr-4 text-green-600">
                    <i class="fas fa-box text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Jerigen 5L Terkumpul</p>
                    <p class="text-2xl font-black text-green-700">{{ number_format($totalJerigen) }} <span class="text-sm font-normal">Biji</span></p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border border-red-100 flex items-center hover:shadow-md transition">
                <div class="bg-red-100 p-4 rounded-lg mr-4 text-red-600">
                    <i class="fas fa-tags text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Potongan Nota</p>
                    <p class="text-2xl font-black text-red-700">Rp {{ number_format($totalRupiah, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6 pb-12">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        
        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-list text-orange-600 mr-2"></i> Rincian Per Transaksi
            </h2>
            
            <form action="{{ route('admin.orders.rekap_retur') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <div class="flex items-center bg-gray-100 rounded-lg px-3 py-1 border border-gray-200">
                    <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="bg-transparent border-none text-sm focus:ring-0">
                    <span class="mx-2 text-gray-400">-</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="bg-transparent border-none text-sm focus:ring-0">
                </div>
                <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-orange-700 transition">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                @if(request()->has('start_date'))
                    <a href="{{ route('admin.orders.rekap_retur') }}" class="text-sm text-gray-500 hover:text-red-600 underline">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b">
                        <th class="px-6 py-4 text-left">Tanggal</th>
                        <th class="px-6 py-4 text-left">Member & Invoice</th>
                        <th class="px-6 py-4 text-left">Detail Retur</th>
                        <th class="px-6 py-4 text-center">Bukti Rusak</th>
                        <th class="px-6 py-4 text-right">Potongan Rupiah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($returs as $item)
                        <tr class="hover:bg-orange-50/30 transition-colors">
                            <td class="px-6 py-4 text-gray-600">
                                {{ $item->created_at->format('d M Y') }}
                                <div class="text-[10px] text-gray-400">{{ $item->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $item->user->nama_lengkap }}</div>
                                <div class="text-xs font-mono text-blue-600">{{ $item->invoice_number }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    @if($item->retur_botol > 0) 
                                        <span class="inline-block bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-[10px] font-bold mr-1">{{ $item->retur_botol }} Botol</span>
                                    @endif
                                    @if($item->retur_jerigen > 0) 
                                        <span class="inline-block bg-green-50 text-green-700 px-2 py-0.5 rounded text-[10px] font-bold mr-1">{{ $item->retur_jerigen }} Jerigen</span>
                                    @endif
                                    @if($item->qty_rusak > 0) 
                                        <div class="text-[11px] text-red-600 font-medium">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> {{ $item->qty_rusak }}x {{ $item->product_rusak->nama_produk ?? 'Barang Rusak' }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    @if($item->foto_kerusakan)
                                        <a href="{{ asset('storage/' . $item->foto_kerusakan) }}" target="_blank" class="relative group">
                                            <img src="{{ asset('storage/' . $item->foto_kerusakan) }}" class="w-12 h-12 object-cover rounded-lg border-2 border-white shadow-sm group-hover:scale-110 transition">
                                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 rounded-lg flex items-center justify-center text-white text-[8px]">LIHAT</div>
                                        </a>
                                    @else
                                        <span class="text-gray-300 italic text-[10px]">Tidak Ada</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-red-600 font-black text-base">- Rp {{ number_format($item->total_potongan_retur, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-folder-open text-4xl text-gray-200 mb-3"></i>
                                    <p class="text-gray-400 italic">Belum ada data retur yang tercatat.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $returs->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection