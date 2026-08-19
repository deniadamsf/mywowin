@extends('admin.layouts.master')

@section('title', 'WOWINFood - Kelola Rewards')

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
                    <a href="{{ route('admin.rewards.index') }}" class="hover:text-[#16782d] transition-colors duration-200">Kelola Rewards</a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Master Rewards
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-[#16782d] p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-gift text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Kelola Rewards</h1>
                <p class="text-gray-600">Halaman ini merupakan Data Master untuk Mengelola rewards member yang digunakan untuk membuat, update, delete rewards.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-3">
                    <i class="fas fa-gift text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Rewards</p>
                    <p class="text-xl font-bold">{{ $rewards->total() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-3">
                    <i class="fas fa-calendar-check text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Ditambahkan Hari Ini</p>
                    <p class="text-xl font-bold">{{ $rewards->where('created_at', '>=', \Carbon\Carbon::today())->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-3">
                    <i class="fas fa-users text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Klaim</p>
                    <p class="text-xl font-bold">{{ $totalClaims ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex gap-3 mt-4">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-[#16782d] border border-[#16782d] px-4 py-2 rounded-lg hover:bg-[#16782d]/10 transition flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.rewards.index') }}" class="text-sm font-medium text-white bg-[#16782d] px-4 py-2 rounded-lg hover:bg-[#135e24] transition flex items-center">
                <i class="fas fa-gift mr-2"></i> Master Rewards
            </a>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-[#16782d] mr-2"></i> Data Rewards
            </h2>
            
            <div class="relative">
                <form action="{{ route('admin.rewards.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Cari rewards..." 
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#16782d] focus:border-[#16782d] w-64"
                        value="{{ request('search') }}">
                    <button type="submit" class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>            
        </div>

        <!-- Filter & Export -->
        <div class="flex justify-between mb-4">
            <div class="flex space-x-2">
                <form action="{{ route('admin.rewards.index') }}" method="GET" class="flex space-x-2">
                    <!-- Filter Poin -->
                    <select name="point_filter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#16782d] focus:border-[#16782d]">
                        <option value="">Semua Poin</option>
                        <option value="low" {{ request('point_filter') == 'low' ? 'selected' : '' }}>Poin Rendah (<500)</option>
                        <option value="medium" {{ request('point_filter') == 'medium' ? 'selected' : '' }}>Poin Menengah (500-1000)</option>
                        <option value="high" {{ request('point_filter') == 'high' ? 'selected' : '' }}>Poin Tinggi (>1000)</option>
                    </select>
            
                    <!-- Filter Tanggal -->
                    <select name="date_filter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#16782d] focus:border-[#16782d]">
                        <option value="">Semua Tanggal</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
            
                    <button type="submit" class="text-gray-400">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </form>
            </div>
            
            <div class="flex space-x-2">
                <a href="{{ route('admin.rewards.create') }}" class="bg-[#16782d] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#135e24] transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Reward
                </a>
            
                {{-- <a href="{{ route('admin.rewards.exportPdf') }}" target="_blank" class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800 transition flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </a>  
            
                <a href="{{ route('admin.rewards.exportExcel') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition flex items-center">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </a> --}}
            </div>
            
        </div>

        <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b">
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-image mr-2 text-[#16782d]"></i>
                                Foto
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-gift mr-2 text-[#16782d]"></i>
                                Nama Reward
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-coins mr-2 text-[#16782d]"></i>
                                Poin 
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
                    @forelse ($rewards as $reward)
                        <tr class="hover:bg-gray-50 border-b border-gray-100 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center">
                                    @if (!empty($reward->foto_rewards)) 
                                        <img src="{{ asset('storage/' . $reward->foto_rewards) }}" 
                                             class="w-16 h-16 object-cover rounded-lg border border-gray-200 shadow-sm" 
                                             alt="{{ $reward->nama_reward }}">
                                    @else
                                        <div class="w-16 h-16 flex items-center justify-center bg-gray-100 rounded-lg border border-gray-200">
                                            <i class="fas fa-gift text-gray-400 text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $reward->nama_reward }}</div>
                                <div class="text-xs text-gray-500 max-w-xs truncate">
                                    {{ Str::limit(strip_tags($reward->deskripsi), 50) }}
                                </div>
                                
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-coins mr-1"></i>
                                    {{ number_format($reward->points_required) }} 
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span>{{ $reward->created_at->format('d M Y, H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Detail Button -->
                                    <div x-data="{ open: false }" class="relative">
                                        <button x-on:click="open = true" class="px-3 py-1.5 text-white text-xs bg-[#16782d] rounded-md hover:bg-[#135e24] transition flex items-center">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </button>
                                        
                                        <!-- Modal Content -->
                                        <template x-if="open">
                                            <div class="fixed inset-0 z-50" x-cloak>                                        
                                                <!-- Overlay -->
                                                <div class="absolute inset-0 bg-black bg-opacity-40" x-on:click="open = false"></div>
                                                
                                                <!-- Slide-in Panel -->
                                                <div class="absolute right-0 top-0 bg-white w-full max-w-md h-full shadow-2xl p-6 overflow-y-auto animate__animated animate__fadeInRight animate__faster">
                                                    <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                                                        <h2 class="text-xl font-semibold text-[#16782d] flex items-center">
                                                            <i class="fas fa-info-circle mr-2"></i> Detail Reward
                                                        </h2>
                                                        <button x-on:click="open = false" class="text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition-colors duration-200">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Reward Details -->
                                                    <div class="space-y-4">
                                                        <!-- Image -->
                                                        <div class="flex justify-center mb-2">
                                                            <div class="w-48 h-48 rounded-lg overflow-hidden border border-gray-200 shadow-sm flex items-center justify-center">
                                                                @if (!empty($reward->foto_rewards)) 
                                                                    <img src="{{ asset('storage/' . $reward->foto_rewards) }}" 
                                                                         class="w-full h-full object-cover" 
                                                                         alt="{{ $reward->nama_reward }}">
                                                                @else
                                                                    <i class="fas fa-gift text-gray-400 text-5xl"></i>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Fields -->
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Nama Reward</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-gift text-gray-400 mr-2"></i>
                                                                <span>{{ $reward->nama_reward }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Poin Dibutuhkan</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-coins text-gray-400 mr-2"></i>
                                                                <span>{{ number_format($reward->points_required) }} poin</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Tanggal Dibuat</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                                                <span>{{ $reward->created_at->format('d M Y, H:i') }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Deskripsi</label>
                                                            <div class="border bg-white rounded p-3 max-h-48 overflow-y-auto hidden-tags">
                                                                {!! nl2br(e(\Illuminate\Support\Str::limit(str_replace(['&nbsp;', '<p>', '</p>'], [' ', '', ''], strip_tags($reward->deskripsi)), 120))) !!}
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Total Diklaim</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-users text-gray-400 mr-2"></i>
                                                                <span>{{ $reward->claims_count ?? 0 }} kali</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Action Buttons -->
                                                        <div class="flex justify-end space-x-2 mt-6">
                                                            <button x-on:click="open = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
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
                                            class="px-4 py-2 text-white rounded-md text-xs bg-blue-500 hover:bg-blue-600">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                
                                        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
                                                <h2 class="text-xl font-bold text-gray-800 mb-4">Edit Reward</h2>
                                                @if ($errors->any())
                                                <div class="text-red-500 mb-4">
                                                    <ul class="list-disc pl-5">
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif

                                                <form method="POST" action="{{ route('admin.rewards.update', $reward->id) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 text-left">Nama Reward:</label>
                                                            <input type="text" name="nama_reward" value="{{ $reward->nama_reward }}"
                                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 text-left">Poin Dibutuhkan:</label>
                                                            <input type="number" name="points_required" value="{{ $reward->points_required }}"
                                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                        </div>
                                                        <div class="sm:col-span-2">
                                                            <label class="block text-sm font-medium text-gray-700 text-left">Deskripsi:</label>
                                                            <textarea name="deskripsi" rows="4"
                                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">{{ $reward->deskripsi }}</textarea>
                                                        </div>
                                                        <div class="sm:col-span-2">
                                                            <label class="block text-sm font-medium text-gray-700 text-left">Foto Reward</label>
                                                        
                                                            <div class="flex items-center mb-2">
                                                                <div class="w-24 h-24 rounded-lg overflow-hidden border border-gray-300 mr-3">
                                                                    @if (!empty($reward->foto_reward)) 
                                                                    <img src="{{ asset('storage/' . $reward->foto_reward) }}" 
                                                                    class="w-full h-full object-cover" 
                                                                    alt="{{ $reward->nama_reward }}">
                                                                    @else
                                                                        <div class="flex items-center justify-center h-full bg-gray-100">
                                                                            <i class="fas fa-gift text-gray-400 text-xl"></i>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="flex-1">
                                                                    <input type="file" name="foto_reward"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-end mt-4 space-x-2">
                                                        <button type="button" @click="showEditModal = false"
                                                            class="px-4 py-2 text-white rounded-md text-xs bg-gray-400 hover:bg-gray-500">
                                                            <i class="fas fa-times mr-1"></i> Batal
                                                        </button>
                                                        <button type="submit"
                                                            class="px-4 py-2 text-white rounded-md text-xs bg-blue-600 hover:bg-blue-700">
                                                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </form>
                                            
                                            </div>
                                            
                                        </div>
                                    </div>
                            
                                    <!-- Hapus -->
                                    <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus reward ini?');"
                                        action="{{ route('admin.rewards.destroy', $reward->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-4 py-2 text-white rounded-md text-xs bg-red-500 hover:bg-red-600">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-red-500 py-8">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-gift text-gray-300 text-5xl mb-3"></i>
                                    <span>Belum ada data rewards.</span>
                                    <a href="{{ route('admin.rewards.create') }}" class="mt-3 text-[#16782d] hover:underline">
                                        + Tambah Reward Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $rewards->links('pagination::tailwind') }}
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
