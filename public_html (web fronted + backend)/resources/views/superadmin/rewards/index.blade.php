@extends('superadmin.layouts.master')

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
    <div class="bg-gradient-to-r from-white to-purple-50 p-4 sm:p-6 rounded-2xl shadow-lg border border-gray-200">
        <!-- Breadcrumb -->
        <nav class="flex text-sm mb-4 overflow-x-auto" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('superadmin.dashboard') }}" class="text-gray-700 hover:text-purple-700 flex items-center transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="flex items-center text-gray-500">
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('superadmin.rewards.index') }}" class="hover:text-purple-700 transition-colors duration-200">Kelola Rewards</a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Master Rewards
                </li>
            </ol>
        </nav>

        <div class="flex flex-col sm:flex-row items-start sm:items-center mb-6">
            <div class="bg-purple-700 p-3 rounded-full mr-4 shadow-md mb-3 sm:mb-0">
                <i class="fas fa-gift text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-1">Kelola Rewards</h1>
                <p class="text-gray-600 text-sm sm:text-base">Halaman ini merupakan Data Master untuk Mengelola rewards member yang digunakan untuk membuat, update, delete rewards.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
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
        <div class="flex flex-wrap gap-2 sm:gap-3 mt-4">
            <a href="{{ route('superadmin.dashboard') }}" class="text-sm font-medium text-purple-700 border border-purple-700 px-3 sm:px-4 py-2 rounded-lg hover:bg-purple-700/10 transition flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('superadmin.rewards.index') }}" class="text-sm font-medium text-white bg-purple-700 px-3 sm:px-4 py-2 rounded-lg hover:bg-purple-800 transition flex items-center">
                <i class="fas fa-gift mr-2"></i> Master Rewards
            </a>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-purple-700 mr-2"></i> Data Rewards
            </h2>
            
            {{-- <!-- Search Box -->
            <div class="relative w-full sm:w-auto">
                <form action="{{ route('superadmin.rewards.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Cari rewards..." 
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-700 focus:border-purple-700 w-full sm:w-64">
                    <button type="submit" class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div> --}}
        </div>

       <!-- Filter & Export -->
<form method="GET" action="{{ route('superadmin.rewards.index') }}" class="flex flex-col sm:flex-row justify-between mb-4 gap-3">
    <div class="flex flex-wrap gap-2">
        <!-- Filter Poin -->
        <select name="poin" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-700 focus:border-purple-700">
            <option value="">Semua Poin</option>
            <option value="low" {{ request('poin') == 'low' ? 'selected' : '' }}>Poin Rendah (&lt;500)</option>
            <option value="medium" {{ request('poin') == 'medium' ? 'selected' : '' }}>Poin Menengah (500-1000)</option>
            <option value="high" {{ request('poin') == 'high' ? 'selected' : '' }}>Poin Tinggi (&gt;1000)</option>
        </select>

        <!-- Filter Tanggal -->
        <select name="filter" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-700 focus:border-purple-700">
            <option value="">Semua Tanggal</option>
            <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
            <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
            <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
        </select>

        <!-- Search -->
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/email" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-700 focus:border-purple-700">
        
        <!-- Optional: Tombol submit -->
        <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-800 transition">
            Cari
        </button>
    </div>

    <div>
        <a href="{{ route('superadmin.rewards.create') }}" class="bg-purple-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-800 transition flex items-center justify-center sm:justify-start">
            <i class="fas fa-plus mr-2"></i> Tambah Reward
        </a>
    </div>
</form>

        <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b">
                        <th class="px-3 sm:px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-image mr-2 text-purple-700"></i>
                                Foto
                            </div>
                        </th>
                        <th class="px-3 sm:px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-gift mr-2 text-purple-700"></i>
                                Nama Reward
                            </div>
                        </th>
                        <th class="px-3 sm:px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-coins mr-2 text-purple-700"></i>
                                Poin 
                            </div>
                        </th>
                        <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-purple-700"></i>
                                Tanggal
                            </div>
                        </th>
                        <th class="px-3 sm:px-6 py-3 text-center">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-cogs mr-2 text-purple-700"></i>
                                Aksi
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rewards as $reward)
                        <tr class="hover:bg-gray-50 border-b border-gray-100 transition-colors duration-200">
                            <td class="px-3 sm:px-6 py-4">
                                <div class="flex justify-center items-center">
                                    @if (!empty($reward->foto_rewards)) 
                                        <img src="{{ asset('storage/' . $reward->foto_rewards) }}" 
                                             class="w-12 h-12 sm:w-16 sm:h-16 object-cover rounded-lg border border-gray-200 shadow-sm" 
                                             alt="{{ $reward->nama_reward }}">
                                    @else
                                        <div class="w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center bg-gray-100 rounded-lg border border-gray-200">
                                            <i class="fas fa-gift text-gray-400 text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-3 sm:px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $reward->nama_reward }}</div>
                                <div class="text-xs text-gray-500 max-w-xs truncate">
                                    {{ Str::limit(strip_tags($reward->deskripsi), 50) }}
                                </div>
                                <div class="md:hidden text-xs text-gray-500 mt-1">
                                    <i class="fas fa-calendar-alt mr-1"></i> {{ $reward->created_at->format('d M Y') }}
                                </div>
                            </td>
                            <td class="px-3 sm:px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-coins mr-1"></i>
                                    {{ number_format($reward->points_required) }} poin
                                </span>
                            </td>
                            <td class="hidden md:table-cell px-3 sm:px-6 py-4">
                                <span>{{ $reward->created_at->format('d M Y, H:i') }}</span>
                            </td>
                            <td class="px-3 sm:px-6 py-4">
                                <div class="flex flex-col sm:flex-row justify-center items-center gap-2">
                                    <!-- Detail Button -->
                                    <div x-data="{ open: false }" class="relative">
                                        <button x-on:click="open = true" class="px-3 py-1.5 text-white text-xs bg-purple-700 rounded-md hover:bg-purple-800 transition flex items-center">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </button>
                                        
                                        <!-- Modal Content -->
                                        <template x-if="open">
                                            <div class="fixed inset-0 z-50" x-cloak>                                        
                                                <!-- Overlay -->
                                                <div class="absolute inset-0 bg-black bg-opacity-40" x-on:click="open = false"></div>
                                                
                                                <!-- Slide-in Panel -->
                                                <div class="absolute right-0 top-0 bg-white w-full max-w-md h-full shadow-2xl p-4 sm:p-6 overflow-y-auto animate__animated animate__fadeInRight animate__faster">
                                                    <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                                                        <h2 class="text-lg sm:text-xl font-semibold text-purple-700 flex items-center">
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
                                                            <div class="w-32 h-32 sm:w-48 sm:h-48 rounded-lg overflow-hidden border border-gray-200 shadow-sm flex items-center justify-center">
                                                                @if (!empty($reward->foto_reward)) 
                                                                    <img src="{{ asset('storage/' . $reward->foto_reward) }}" 
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
                                    <div x-data="{ showEditModal: false }" class="w-full sm:w-auto">
                                        <button type="button" @click="showEditModal = true"
                                            class="w-full sm:w-auto px-4 py-2 text-white rounded-md text-xs bg-blue-500 hover:bg-blue-600">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                
                                        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
                                            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-4 sm:p-6 max-h-[90vh] overflow-y-auto">
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

                                                <form method="POST" action="{{ route('superadmin.rewards.update', $reward->id) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 text-left">Nama Reward:</label>
                                                            <input type="text" name="nama_reward" value="{{ $reward->nama_reward }}"
                                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-purple-200">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 text-left">Poin Dibutuhkan:</label>
                                                            <input type="number" name="points_required" value="{{ $reward->points_required }}"
                                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-purple-200">
                                                        </div>
                                                        <div class="sm:col-span-2">
                                                            <label class="block text-sm font-medium text-gray-700 text-left">Deskripsi:</label>
                                                            <textarea name="deskripsi" rows="4"
                                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-purple-200">{{ $reward->deskripsi }}</textarea>
                                                        </div>
                                                        <div class="sm:col-span-2">
                                                            <label class="block text-sm font-medium text-gray-700 text-left">Foto Reward</label>
                                                        
                                                            <div class="flex flex-col sm:flex-row items-center mb-2">
                                                                <div class="w-24 h-24 rounded-lg overflow-hidden border border-gray-300 mr-0 sm:mr-3 mb-3 sm:mb-0">
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
                                                                <div class="flex-1 w-full">
                                                                    <input type="file" name="foto_reward"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-purple-200">
                                                                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col sm:flex-row justify-end mt-4 space-y-2 sm:space-y-0 sm:space-x-2">
                                                        <button type="button" @click="showEditModal = false"
                                                            class="px-4 py-2 text-white rounded-md text-xs bg-gray-400 hover:bg-gray-500 w-full sm:w-auto">
                                                            <i class="fas fa-times mr-1"></i> Batal
                                                        </button>
                                                        <button type="submit"
                                                            class="px-4 py-2 text-white rounded-md text-xs bg-blue-600 hover:bg-blue-700 w-full sm:w-auto">
                                                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                            
                                    <!-- Hapus -->
                                    <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus reward ini?');"
                                        action="{{ route('superadmin.rewards.destroy', $reward->id) }}" method="POST"
                                        class="w-full sm:w-auto">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full sm:w-auto px-4 py-2 text-white rounded-md text-xs bg-red-500 hover:bg-red-600">
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
                                    <a href="{{ route('superadmin.rewards.create') }}" class="mt-3 text-purple-700 hover:underline">
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
<div id="toast-success" class="fixed top-20 right-4 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm hidden z-50" role="alert">
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