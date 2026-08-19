@extends('superadmin.layouts.master')

@section('title', 'WOWINFood - Rekap Retur')

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
                    <a href="{{ route('superadmin.dashboard') }}" class="text-gray-700 hover:text-orange-700 flex items-center transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                </li>
                <li class="flex items-center text-gray-500">
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Rekap Retur
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-orange-600 p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-undo-alt text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Rekapitulasi Retur & Potongan Nota</h1>
                <p class="text-gray-600">Pantau seluruh potongan nota akibat retur botol, jerigen, dan produk rusak dari semua cabang.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-orange-100 flex items-center">
                <div class="bg-red-100 p-3 rounded-full mr-3">
                    <i class="fas fa-money-bill-wave text-red-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold">Total Potongan</p>
                    <p class="text-lg font-bold text-red-600">Rp {{ number_format($totalPotongan, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-orange-100 flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-3">
                    <i class="fas fa-wine-bottle text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold">Retur Botol</p>
                    <p class="text-lg font-bold text-blue-600">{{ $totalBotol }} Pcs</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-orange-100 flex items-center">
                <div class="bg-orange-100 p-3 rounded-full mr-3">
                    <i class="fas fa-box text-orange-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold">Retur Jerigen</p>
                    <p class="text-lg font-bold text-orange-600">{{ $totalJerigen }} Pcs</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-orange-100 flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-3">
                    <i class="fas fa-exclamation-triangle text-purple-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold">Total Transaksi</p>
                    <p class="text-lg font-bold text-purple-600">{{ $rekapData->total() }} Nota</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-4 items-end">
    <form action="{{ route('superadmin.rekap.retur') }}" method="GET" class="flex flex-wrap gap-3 items-end w-full">
        <div class="w-full md:w-64">
            <label class="block text-xs font-bold text-gray-600 mb-1 uppercase">Pilih PT / Cabang</label>
            <select name="admin_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500">
                <option value="">Semua PT / Cabang</option>
                @foreach($ptList as $pt)
                    {{-- Kita pakai user_id dari branch_setting karena itu mereferensikan admin_id di tabel users --}}
                    <option value="{{ $pt->user_id }}" {{ request('admin_id') == $pt->user_id ? 'selected' : '' }}>
                        {{ $pt->nama_pt }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-orange-700 transition">
            <i class="fas fa-filter mr-1"></i> Terapkan Filter
        </button>
        <a href="{{ route('superadmin.rekap.retur') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-bold hover:bg-gray-300 transition text-center">
            Reset
        </a>
    </form>
</div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6 mb-10">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b uppercase text-xs">
                        <th class="px-6 py-4 text-left font-bold">Nota & Tanggal</th>
                        <th class="px-6 py-4 text-left font-bold">Admin (PT Cabang)</th>
                        <th class="px-6 py-4 text-left font-bold">Pelanggan</th>
                        <th class="px-6 py-4 text-left font-bold">Rincian Barang Retur</th>
                        <th class="px-6 py-4 text-right font-bold text-red-600">Nilai Potongan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($rekapData as $order)
                        <tr class="hover:bg-orange-50/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">#{{ $order->invoice_number }}</div>
                                <div class="text-[11px] text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-[10px] font-bold">
                                    {{ $order->user->admin->nama_lengkap ?? 'PUSAT' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700">
                                {{ $order->user->nama_lengkap }}
                            </td>
                            <td class="px-6 py-4">
                                <ul class="text-[11px] space-y-1">
                                    @if($order->retur_botol > 0)
                                        <li class="flex items-center text-blue-700">
                                            <i class="fas fa-wine-bottle mr-2 w-4"></i> {{ $order->retur_botol }} Botol Kosong
                                        </li>
                                    @endif
                                    @if($order->retur_jerigen > 0)
                                        <li class="flex items-center text-orange-700">
                                            <i class="fas fa-box mr-2 w-4"></i> {{ $order->retur_jerigen }} Jerigen Kosong
                                        </li>
                                    @endif
                                    @if($order->qty_rusak > 0)
                                        <li class="flex items-center text-red-700">
                                            <i class="fas fa-exclamation-circle mr-2 w-4"></i> {{ $order->qty_rusak }} Pcs (Rusak)
                                        </li>
                                    @endif
                                </ul>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="font-black text-red-600">
                                    - Rp {{ number_format($order->total_potongan_retur, 0, ',', '.') }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
                                <i class="fas fa-folder-open text-4xl mb-3 block"></i>
                                Tidak ditemukan data potongan retur dalam periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $rekapData->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection