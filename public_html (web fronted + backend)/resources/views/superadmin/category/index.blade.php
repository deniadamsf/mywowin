@extends('superadmin.layouts.master')

@section('title', 'WOWINFood - Kelola kategori')

@section('head')
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gradient-to-r from-white to-purple-50 p-6 rounded-2xl shadow-lg border border-gray-200">
        <!-- Breadcrumb -->
        <nav class="flex text-sm mb-4" aria-label="Breadcrumb">
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
                    <a href="{{ route('superadmin.categories.index') }}" class="hover:text-purple-700 transition-colors duration-200">Kelola Kategori</a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    <span>Data Kategori</span>
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-purple-700 p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-image text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Kelola Data Kategori</h1>
                <p class="text-gray-600">Halaman ini merupakan halaman untuk menambah kategori </p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-3">
                    <i class="fas fa-images text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Kategori</p>
                    <p class="text-xl font-bold">{{ $categories->total() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-3">
                    <i class="fas fa-calendar-check text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Terakhir Diperbarui</p>
                    <p class="text-xl font-bold">{{ $categories->first() ? $categories->first()->updated_at->format('d M Y') : '-' }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-3">
                    <i class="fas fa-user-check text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="text-xl font-bold">{{ $categories->count() > 0 ? 'Aktif' : 'Kosong' }}</p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex gap-3 mt-4">
            <a href="{{ route('superadmin.dashboard') }}" class="text-sm font-medium text-purple-700 border border-purple-700 px-4 py-2 rounded-lg hover:bg-purple-700/10 transition flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('superadmin.categories.index') }}" class="text-sm font-medium text-white bg-purple-700 px-4 py-2 rounded-lg hover:bg-[#135e24] transition flex items-center">
                <i class="fas fa-images mr-2"></i> Tampilan Kategori
            </a>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-purple-700 mr-2"></i> Master Kategori
            </h2>
            
            <a href="{{ route('superadmin.categories.create') }}" class="bg-purple-700 hover:bg-[#135e24] text-white font-semibold py-2 px-4 rounded-lg transition flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Kategori
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
            <table class="w-full text-sm">
                <thead>
                        <th class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-tag mr-2 text-purple-700"></i>
                                Gambar
                            </div>
                        </th>
                        <th class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-tag mr-2 text-purple-700"></i>
                                Nama Kategori
                            </div>
                        </th>
                        <th class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-cogs mr-2 text-purple-700"></i>
                                Aksi
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="hover:bg-gray-50 border-b border-gray-100 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center">
                                    @if (!empty($category->foto_kategori)) 
                                        <img src="{{ asset('storage/' . $category->foto_kategori) }}" 
                                             class="w-16 h-16 object-cover rounded-lg" 
                                             alt="{{ $category->name }}">
                                    @else
                                        <div class="w-16 h-16 flex items-center justify-center bg-gray-100 rounded-lg">
                                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="font-medium text-gray-900">{{ $category->name}}</div>
                                <div class="text-xs text-gray-500">{{ $category->updated_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Tombol Detail dengan x-data scope individual untuk setiap baris -->
                                    <div x-data="{ open: false }" class="relative">
                                        <button x-on:click="open = true" class="px-3 py-1.5 text-white text-xs bg-purple-700 rounded-md hover:bg-[#135e24] transition flex items-center">
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
                                                        <h2 class="text-xl font-semibold text-purple-700 flex items-center">
                                                            <i class="fas fa-image mr-2"></i> Detail Kategori
                                                        </h2>
                                                        <button x-on:click="open = false" class="text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition-colors duration-200">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <div class="space-y-4">
                                                        <div class="flex justify-center mb-4">
                                                            <div class="w-24 h-24 rounded-full overflow-hidden border border-gray-300 flex items-center justify-center bg-gray-100">
                                                                @if (!empty($category->foto_kategori)) 
                                                                    <img src="{{ asset('storage/' . $category->foto_kategori) }}" 
                                                                         class="w-full h-full object-cover" 
                                                                         alt="{{ $category->name }}">
                                                                @else
                                                                    <i class="fas fa-image text-gray-400 text-3xl"></i>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Nama Kategori</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-tag text-gray-400 mr-2"></i>
                                                                <span>{{ $category->name }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Dibuat Pada</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                                                <span>{{ $category->created_at->format('d M Y, H:i:s') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Terakhir Diperbarui</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-clock text-gray-400 mr-2"></i>
                                                                <span>{{ $category->updated_at->format('d M Y, H:i:s') }}</span>
                                                            </div>
                                                        </div>
                                                        
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

                                    <!-- Edit Button -->
                                    <div x-data="{ showEditModal: false }" class="relative">
                                        <button @click="showEditModal = true" 
                                            class="px-3 py-1.5 text-white text-xs bg-blue-500 rounded-md hover:bg-blue-600 transition flex items-center">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                    
                                        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                                            <div class="absolute inset-0 bg-black bg-opacity-40" @click="showEditModal = false"></div>
                                            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 z-10 animate__animated animate__fadeInDown animate__faster">
                                                <div class="flex justify-between items-center pb-3 border-b border-gray-200 mb-4">
                                                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                                                        <i class="fas fa-edit text-blue-500 mr-2"></i> Edit kategori
                                                    </h2>
                                                    <button @click="showEditModal = false" class="text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition-colors duration-200">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <form method="POST" action="{{ route('superadmin.categories.update', $category->id) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                                                            <div class="flex items-center border border-gray-300 bg-white rounded-md overflow-hidden focus-within:ring-2 focus-within:ring-blue-400">
                                                                <div class="px-3 py-2 bg-gray-100 border-r border-gray-300">
                                                                    <i class="fas fa-tag text-gray-500"></i>
                                                                </div>
                                                                <input type="text" name="name" value="{{ $category->name }}"
                                                                    class="w-full px-3 py-2 focus:outline-none" placeholder="Masukkan nama event">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Kategori</label>
                                                            <div class="flex items-center mb-2">
                                                                @if (!empty($category->foto_kategori))
                                                                    <div class="w-16 h-16 rounded-lg overflow-hidden border border-gray-300 mr-3">
                                                                        <img src="{{ asset('storage/' . $category->foto_kategori) }}" class="w-full h-full object-cover" alt="{{ $category->foto_kategori }}">
                                                                    </div>
                                                                @endif
                                                                <div class="relative flex-1">
                                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                        <i class="fas fa-image text-gray-400"></i>
                                                                    </div>
                                                                    <input type="file" name="foto_kategori" class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 focus:ring-purple-700 focus:border-purple-700 text-sm">
                                                                </div>
                                                            </div>
                                                            <p class="text-xs text-gray-500">Kosongkan jika tidak ingin mengubah foto.</p>
                                                        </div>
                                                    <div class="flex justify-end mt-6 space-x-2">
                                                        <button type="button" @click="showEditModal = false"
                                                            class="px-4 py-2 text-white rounded-md text-sm bg-gray-400 hover:bg-gray-500 transition flex items-center">
                                                            <i class="fas fa-times mr-1"></i> Batal
                                                        </button>
                                                        <button type="submit"
                                                            class="px-4 py-2 text-white rounded-md text-sm bg-blue-600 hover:bg-blue-700 transition flex items-center">
                                                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                                        </button>
                                                    </div>
                                                    
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <!-- Tombol Hapus -->
                                    <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus gambar ini?');"
                                        action="{{ route('superadmin.categories.destroy', $category->id) }}" method="POST">
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
                            <td colspan="3" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-image text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-red-500 font-medium">Belum ada gambar.</p>
                                    <p class="text-gray-500 text-sm mt-1">Anda belum menambahkan gambar untuk tampilan login.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $categories->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast-success" class="fixed top-20 right-4 flex items-center w-full max-w-xs p-4 mb-4 text-white bg-purple-700 rounded-lg shadow animate__animated animate__fadeInRight hidden">
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