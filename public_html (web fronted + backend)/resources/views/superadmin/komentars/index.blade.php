@extends('superadmin.layouts.master')

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

<div class="container mx-auto px-3 sm:px-4 lg:px-6">
    <div class="bg-gradient-to-r from-white to-purple-50 p-4 sm:p-5 rounded-xl shadow-md border border-gray-200">
        <!-- Breadcrumb -->
        <nav class="flex text-sm mb-3" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('superadmin.dashboard') }}" class="text-gray-700 hover:text-purple-700 flex items-center transition-colors duration-200">
                        <i class="fas fa-home mr-1.5"></i>
                        Dashboard
                    </a>
                </li>
                <li class="flex items-center text-gray-500">
                    <svg class="w-3 h-3 mx-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('superadmin.komentars.index') }}" class="hover:text-purple-700 transition-colors duration-200">Kelola Komentar</a>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center mb-4 gap-3">
            <div class="bg-purple-700 p-2 rounded-full shadow-md w-10 h-10 flex items-center justify-center">
                <i class="fas fa-comments text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Kelola Komentar</h1>
                <p class="text-sm text-gray-600">Data komentar pengunjung untuk tindak lanjut atau dokumentasi</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100 flex items-center">
                <div class="bg-blue-100 p-2 rounded-full mr-3">
                    <i class="fas fa-user-check text-blue-600 text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Total Komentar</p>
                    <p class="text-lg font-bold">{{ $komentars->total() }}</p>
                </div>
            </div>
            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100 flex items-center">
                <div class="bg-purple-100 p-2 rounded-full mr-3">
                    <i class="fas fa-calendar-check text-purple-600 text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Hari Ini</p>
                    <p class="text-lg font-bold">{{ $komentars->where('created_at', '>=', \Carbon\Carbon::today())->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-100 flex items-center">
                <div class="bg-purple-100 p-2 rounded-full mr-3">
                    <i class="fas fa-calendar-week text-purple-600 text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Minggu Ini</p>
                    <p class="text-lg font-bold">{{ $komentars->where('created_at', '>=', \Carbon\Carbon::now()->startOfWeek())->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex flex-wrap gap-2 mt-3">
            <a href="{{ route('superadmin.dashboard') }}" class="text-xs font-medium text-purple-700 border border-purple-700 px-3 py-1.5 rounded-md hover:bg-purple-50 transition flex items-center">
                <i class="fas fa-tachometer-alt mr-1.5"></i> Dashboard
            </a>
            <a href="{{ route('superadmin.komentars.index') }}" class="text-xs font-medium text-white bg-purple-700 px-3 py-1.5 rounded-md hover:bg-purple-800 transition flex items-center">
                <i class="fas fa-comments mr-1.5"></i> Master Komentar
            </a>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="container mx-auto px-3 sm:px-4 lg:px-6 mt-4">
    <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-4 gap-3">
            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-purple-700 mr-2"></i> Data Komentar
            </h2>
            
            {{-- <!-- Search Box -->
            <div class="relative w-full sm:w-auto">
                <form action="{{ route('superadmin.komentars.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Cari komentar..." 
                        class="pl-9 pr-3 py-1.5 border border-gray-300 rounded-md focus:ring-purple-700 focus:border-purple-700 w-full sm:w-56 text-sm">
                    <button type="submit" class="absolute left-2.5 top-2 text-gray-400">
                        <i class="fas fa-search text-sm"></i>
                    </button>
                </form>
            </div> --}}
        </div>

        <!-- Filter & Export -->
        <!-- Search & Filter -->
<div class="flex flex-col sm:flex-row justify-between mb-4 gap-3">
    <form action="{{ route('superadmin.komentars.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full">
        <div class="relative w-full sm:w-auto">
            <input type="text" name="search" placeholder="Cari komentar..." 
                value="{{ request('search') }}"
                class="pl-9 pr-3 py-1.5 border border-gray-300 rounded-md focus:ring-purple-700 focus:border-purple-700 w-full sm:w-56 text-sm">
            <button type="submit" class="absolute left-2.5 top-2 text-gray-400">
                <i class="fas fa-search text-sm"></i>
            </button>
        </div>

        <select name="filter" class="border border-gray-300 rounded-md px-3 py-1.5 text-xs focus:ring-purple-700 focus:border-purple-700">
            <option value="">Semua Tanggal</option>
            <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
            <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
            <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
        </select>

        <button type="submit" class="bg-purple-700 text-white text-xs px-4 py-1.5 rounded-md hover:bg-purple-800 transition">
            Filter
        </button>
    </form>
</div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-gray-100">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b">
                        <th class="px-3 py-2 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-1 text-purple-700"></i>
                                Nama
                            </div>
                        </th>
                        <th class="px-3 py-2 text-left hidden md:table-cell">
                            <div class="flex items-center">
                                <i class="fas fa-envelope mr-1 text-purple-700"></i>
                                Email
                            </div>
                        </th>
                        <th class="px-3 py-2 text-left hidden sm:table-cell">
                            <div class="flex items-center">
                                <i class="fas fa-phone mr-1 text-purple-700"></i>
                                No. HP
                            </div>
                        </th>
                        <th class="px-3 py-2 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-comment-dots mr-1 text-purple-700"></i>
                                Pesan
                            </div>
                        </th>
                        <th class="px-3 py-2 text-center">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-cogs mr-1 text-purple-700"></i>
                                Aksi
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($komentars as $komentar)
                        <tr class="hover:bg-gray-50 border-b border-gray-100 transition-colors duration-200">
                            <td class="px-3 py-2">
                                <div class="font-medium text-gray-900">{{ Str::limit($komentar->nama_lengkap, 15) }}</div>
                                <div class="text-xs text-gray-500">{{ $komentar->created_at->format('d/m/y H:i') }}</div>
                            </td>
                            <td class="px-3 py-2 hidden md:table-cell">
                                <a href="mailto:{{ $komentar->email }}" class="text-blue-600 hover:underline">
                                    {{ Str::limit($komentar->email, 20) }}
                                </a>
                            </td>
                            <td class="px-3 py-2 hidden sm:table-cell">
                                <a href="tel:{{ $komentar->no_hp }}" class="text-blue-600 hover:underline">
                                    {{ $komentar->no_hp }}
                                </a>
                            </td>
                            <td class="px-3 py-2">
                                <div class="line-clamp-1">{!! Str::words(strip_tags($komentar->pesan), 8, '...') !!}</div>
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex justify-center items-center gap-1">
                                    <!-- Tombol Detail dengan x-data scope individual untuk setiap baris -->
                                    <div x-data="{ open: false }" class="relative">
                                        <button x-on:click="open = true" class="px-2 py-1 text-white text-xs bg-purple-700 rounded hover:bg-purple-800 transition flex items-center">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </button>
                                        <!-- Modal dan overlay dalam komponen yang sama -->
                                        <template x-if="open">
                                            <div class="fixed inset-0 z-50" x-cloak>                                        
                                                <!-- Overlay yang terpisah dan ditutup bersama dengan modal -->
                                                <div class="absolute inset-0 bg-black bg-opacity-40" x-on:click="open = false"></div>
                                                <!-- Modal content -->
                                                <div class="absolute right-0 top-0 bg-white w-full max-w-sm h-full shadow-2xl p-4 overflow-y-auto animate__animated animate__fadeInRight animate__faster">
                                                    <div class="flex justify-between items-center pb-3 border-b border-gray-200 mb-3">
                                                        <h2 class="text-lg font-semibold text-purple-700 flex items-center">
                                                            <i class="fas fa-comments mr-2"></i> Detail Komentar
                                                        </h2>
                                                        <button x-on:click="open = false" class="text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full p-1.5 transition-colors duration-200">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <div class="space-y-3">
                                                        <div class="bg-gray-50 p-3 rounded-lg">
                                                            <label class="block font-medium text-xs text-gray-700 mb-1">Nama Lengkap</label>
                                                            <div class="flex items-center border bg-white rounded p-2 text-sm">
                                                                <i class="fas fa-user text-gray-400 mr-2"></i>
                                                                <span>{{ $komentar->nama_lengkap }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 p-3 rounded-lg">
                                                            <label class="block font-medium text-xs text-gray-700 mb-1">Email</label>
                                                            <div class="flex items-center border bg-white rounded p-2 text-sm">
                                                                <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                                                <span>{{ $komentar->email }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 p-3 rounded-lg">
                                                            <label class="block font-medium text-xs text-gray-700 mb-1">Nomor Handphone</label>
                                                            <div class="flex items-center border bg-white rounded p-2 text-sm">
                                                                <i class="fas fa-phone text-gray-400 mr-2"></i>
                                                                <span>{{ $komentar->no_hp }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 p-3 rounded-lg">
                                                            <label class="block font-medium text-xs text-gray-700 mb-1">Pesan</label>
                                                            <div class="border bg-white rounded p-2 max-h-40 overflow-y-auto text-sm">
                                                                {{ strip_tags($komentar->pesan) }}
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 p-3 rounded-lg">
                                                            <label class="block font-medium text-xs text-gray-700 mb-1">Waktu Dikirim</label>
                                                            <div class="flex items-center border bg-white rounded p-2 text-sm">
                                                                <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                                                <span>{{ $komentar->created_at->format('d M Y, H:i:s') }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="flex justify-end space-x-2 mt-4">
                                                            <button x-on:click="open = false" class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition text-xs">
                                                                <i class="fas fa-times mr-1"></i> Tutup
                                                            </button>
                                                            <a href="mailto:{{ $komentar->email }}" class="px-3 py-1.5 bg-blue-500 text-white rounded hover:bg-blue-600 transition text-xs">
                                                                <i class="fas fa-reply mr-1"></i> Balas
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                  <!-- Form dengan tombol hapus - PERBAIKAN: Tambahkan ID unik -->
<form id="deleteForm-{{ $komentar->id }}" action="{{ route('superadmin.komentars.destroy', $komentar->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="button" onclick="showDeleteModal({{ $komentar->id }})"
        class="px-2 py-1 text-xs text-white bg-red-700 rounded hover:bg-red-600 transition flex items-center">
        <i class="fas fa-trash-alt mr-1"></i> Hapus
    </button>
</form>

<!-- Modal Konfirmasi Hapus - PERBAIKAN: Tambahkan ID unik -->
<div id="deleteModal-{{ $komentar->id }}" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="modal-backdrop modal-enter fixed inset-0 bg-purple-900 bg-opacity-50" onclick="hideDeleteModal({{ $komentar->id }})"></div>
    
    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="modal-content-enter bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 border border-purple-100">
            <!-- Header dengan gradient purple -->
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 rounded-t-xl">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-white">Konfirmasi Penghapusan</h3>
                        <p class="text-purple-100 text-sm">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-6">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-comment-slash text-3xl text-red-500"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Apakah Anda yakin?</h4>
                    <p class="text-gray-600 mb-2">Anda akan menghapus komentar dari:</p>
                    <p class="text-purple-600 font-semibold mb-2">{{ $komentar->nama_lengkap }}</p>
                    <p class="text-gray-500 text-sm mb-4">{{ Str::limit($komentar->email, 30) }}</p>
                    <div class="bg-gray-50 p-3 rounded-lg mb-4">
                        <p class="text-gray-600 text-sm italic">"{!! Str::limit(strip_tags($komentar->pesan), 80, '...') !!}"</p>
                    </div>
                    <p class="text-gray-500 text-sm">Data ini akan dihapus secara permanen dan tidak dapat dibatalkan.</p>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                <button onclick="hideDeleteModal({{ $komentar->id }})" 
                    class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-150 font-medium">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button onclick="confirmDelete({{ $komentar->id }})" 
                    class="px-4 py-2 text-white bg-gradient-to-r from-red-500 to-red-600 rounded-lg hover:from-red-600 hover:to-red-700 transition duration-150 font-medium shadow-md">
                    <i class="fas fa-trash mr-1"></i> Ya, Hapus!
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript yang diperbaiki -->
<script>
// PERBAIKAN: Fungsi dengan parameter komentarId
function showDeleteModal(komentarId) {
    document.getElementById('deleteModal-' + komentarId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideDeleteModal(komentarId) {
    document.getElementById('deleteModal-' + komentarId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function confirmDelete(komentarId) {
    hideDeleteModal(komentarId);
    
    // PERBAIKAN: Submit form yang tepat berdasarkan komentarId
    document.getElementById('deleteForm-' + komentarId).submit();
}

// Tutup modal dengan ESC key - PERBAIKAN: Tutup semua modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        // Tutup semua modal delete yang mungkin terbuka
        const deleteModals = document.querySelectorAll('[id^="deleteModal-"]');
        deleteModals.forEach(modal => {
            modal.classList.add('hidden');
        });
        document.body.style.overflow = 'auto';
    }
});
</script>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-6 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-comment-slash text-3xl text-gray-300 mb-2"></i>
                                    <p class="text-red-500 font-medium">Belum ada komentar.</p>
                                    <p class="text-gray-500 text-xs mt-1">Tidak ada data komentar untuk ditampilkan saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $komentars->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast-success" class="fixed top-16 right-4 flex items-center w-full max-w-xs p-3 mb-4 text-white bg-purple-700 rounded-lg shadow animate__animated animate__fadeInRight hidden z-50">
    <div class="inline-flex items-center justify-center flex-shrink-0 w-6 h-6 bg-white/20 rounded-lg">
        <i class="fas fa-check text-white text-xs"></i>
    </div>
    <div class="ml-2 text-xs font-normal">Data berhasil diperbarui.</div>
    <button type="button" id="close-toast" class="ml-auto -mx-1.5 -my-1.5 text-white hover:text-gray-200 p-1">
        <i class="fas fa-times text-xs"></i>
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