@extends('admin.layouts.master')

@section('title', 'WOWINFood - Kelola Komentar')

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
                    <a href="{{ route('admin.komentars.index') }}" class="hover:text-[#16782d] transition-colors duration-200">Kelola Komentar</a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Master Komentar
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-[#16782d] p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-comments text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Kelola Komentar</h1>
                <p class="text-gray-600">Data komentar pengunjung untuk kebutuhan tindak lanjut atau dokumentasi kegiatan.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-3">
                    <i class="fas fa-user-check text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Komentar</p>
                    <p class="text-xl font-bold">{{ $komentars->total() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-3">
                    <i class="fas fa-calendar-check text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Hari Ini</p>
                    <p class="text-xl font-bold">{{ $komentars->where('created_at', '>=', \Carbon\Carbon::today())->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-3">
                    <i class="fas fa-calendar-week text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Minggu Ini</p>
                    <p class="text-xl font-bold">{{ $komentars->where('created_at', '>=', \Carbon\Carbon::now()->startOfWeek())->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex gap-3 mt-4">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-[#16782d] border border-[#16782d] px-4 py-2 rounded-lg hover:bg-[#16782d]/10 transition flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.komentars.index') }}" class="text-sm font-medium text-white bg-[#16782d] px-4 py-2 rounded-lg hover:bg-[#135e24] transition flex items-center">
                <i class="fas fa-comments mr-2"></i> Master Komentar
            </a>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-[#16782d] mr-2"></i> Data Komentar
            </h2>
            
            <!-- Search Box -->
            <div class="relative">
                <form action="{{ route('admin.komentars.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Cari komentar..." value="{{ request('search') }}"
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#16782d] focus:border-[#16782d] w-64">
                    <button type="submit" class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            
        </div>

        <!-- Filter & Export -->
        <div class="flex justify-between mb-4">
            <form action="{{ route('admin.komentars.index') }}" method="GET">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <select name="date_filter" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#16782d] focus:border-[#16782d]">
                    <option value="">Semua Tanggal</option>
                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
            </form>
            
            <div class="flex space-x-2">
                <!--<a href="#" class="bg-[#16782d] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#135e24] transition flex items-center">-->
                <!--    <i class="fas fa-file-excel mr-2"></i> Export Excel-->
                <!--</a>-->
                <!--<a href="#" class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800 transition flex items-center">-->
                <!--    <i class="fas fa-file-pdf mr-2"></i> Export PDF-->
                <!--</a>-->
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b">
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2 text-[#16782d]"></i>
                                Nama Lengkap
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-envelope mr-2 text-[#16782d]"></i>
                                Email
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-phone mr-2 text-[#16782d]"></i>
                                Nomor Handphone
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-comment-dots mr-2 text-[#16782d]"></i>
                                Pesan
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
                    @forelse ($komentars as $komentar)
                        <tr class="hover:bg-gray-50 border-b border-gray-100 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $komentar->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $komentar->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="mailto:{{ $komentar->email }}" class="text-blue-600 hover:underline">
                                    {{ $komentar->email }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <a href="tel:{{ $komentar->no_hp }}" class="text-blue-600 hover:underline">
                                    {{ $komentar->no_hp }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="line-clamp-2">{!! Str::words(strip_tags($komentar->pesan), 15, '...') !!}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Tombol Detail dengan x-data scope individual untuk setiap baris -->
                                    <div x-data="{ open: false }" class="relative">
                                        <button x-on:click="open = true" class="px-3 py-1.5 text-white text-xs bg-[#16782d] rounded-md hover:bg-[#135e24] transition flex items-center">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </button>
                                        <!-- Modal dan overlay dalam komponen yang sama -->
                                        <template x-if="open">
                                            <div class="fixed inset-0 z-50" x-cloak>                                        
                                                <!-- Overlay yang terpisah dan ditutup bersama dengan modal -->
                                                <div class="absolute inset-0 bg-black bg-opacity-40" x-on:click="open = false"></div>
                                                <!-- Modal content -->
                                                <div class="absolute right-0 top-0 bg-white w-full max-w-md h-full shadow-2xl p-6 overflow-y-auto animate__animated animate__fadeInRight animate__faster">
                                                    <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                                                        <h2 class="text-xl font-semibold text-[#16782d] flex items-center">
                                                            <i class="fas fa-comments mr-2"></i> Detail Komentar
                                                        </h2>
                                                        <button x-on:click="open = false" class="text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition-colors duration-200">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <div class="space-y-4">
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Nama Lengkap</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-user text-gray-400 mr-2"></i>
                                                                <span>{{ $komentar->nama_lengkap }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Email</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                                                <span>{{ $komentar->email }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Nomor Handphone</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-phone text-gray-400 mr-2"></i>
                                                                <span>{{ $komentar->no_hp }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Pesan</label>
                                                            <div class="border bg-white rounded p-3 max-h-60 overflow-y-auto">
                                                                {{ strip_tags($komentar->pesan) }}
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Waktu Dikirim</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                                                <span>{{ $komentar->created_at->format('d M Y, H:i:s') }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="flex justify-end space-x-2 mt-6">
                                                            <button x-on:click="open = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                                                                <i class="fas fa-times mr-1"></i> Tutup
                                                            </button>
                                                            <a href="mailto:{{ $komentar->email }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                                                                <i class="fas fa-reply mr-1"></i> Balas
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.komentars.destroy', $komentar->id) }}" method="POST" 
                                        onsubmit="return confirm('Yakin ingin menghapus komentar ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 text-xs text-white bg-red-500 hover:bg-red-600 rounded-md transition flex items-center">
                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-comment-slash text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-red-500 font-medium">Belum ada komentar.</p>
                                    <p class="text-gray-500 text-sm mt-1">Tidak ada data komentar untuk ditampilkan saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $komentars->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast-success" class="fixed top-20 right-4 flex items-center w-full max-w-xs p-4 mb-4 text-white bg-[#16782d] rounded-lg shadow animate__animated animate__fadeInRight hidden">
    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg">
        <i class="fas fa-check text-white"></i>
    </div>
    <div class="ml-3 text-sm font-normal">Data berhasil diperbarui.</div>
    <button type="button" id="close-toast" class="ml-auto -mx-1.5 -my-1.5 text-white hover:text-gray-200 p-1.5">
        <i class="fas fa-times"></i>
    </button>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if (session('success'))
            const toast = document.getElementById('toast-success');
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('animate__fadeOutRight');
                setTimeout(() => {
                    toast.classList.add('hidden');
                    toast.classList.remove('animate__fadeOutRight');
                }, 500);
            }, 3000);
        @endif

        document.getElementById('close-toast').addEventListener('click', () => {
            const toast = document.getElementById('toast-success');
            toast.classList.add('animate__fadeOutRight');
            setTimeout(() => {
                toast.classList.add('hidden');
                toast.classList.remove('animate__fadeOutRight');
            }, 500);
        });
    });
</script>
@endsection