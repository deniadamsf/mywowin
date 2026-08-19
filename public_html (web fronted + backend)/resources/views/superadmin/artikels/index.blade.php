@extends('superadmin.layouts.master')

@section('title', 'WOWINFood - Kelola Artikel')

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
                    <a href="{{ route('superadmin.artikels.index') }}" class="hover:text-purple-700 transition-colors duration-200">Kelola Artikel</a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Master Artikel
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-purple-700 p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-newspaper text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Kelola Artikel</h1>
                <p class="text-gray-600">Data master untuk tampilan artikel yang digunakan untuk membuat, update, delete artikel kegiatan dan sebagainya.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-3">
                    <i class="fas fa-newspaper text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Artikel</p>
                    <p class="text-xl font-bold">{{ $artikels->total() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-3">
                    <i class="fas fa-calendar-check text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Hari Ini</p>
                    <p class="text-xl font-bold">{{ $artikels->where('created_at', '>=', \Carbon\Carbon::today())->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-3">
                    <i class="fas fa-calendar-week text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Minggu Ini</p>
                    <p class="text-xl font-bold">{{ $artikels->where('created_at', '>=', \Carbon\Carbon::now()->startOfWeek())->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex gap-3 mt-4">
            <a href="{{ route('superadmin.dashboard') }}" class="text-sm font-medium text-purple-700 border border-purple-700 px-4 py-2 rounded-lg hover:bg-purple-700/10 transition flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('superadmin.artikels.index') }}" class="text-sm font-medium text-white bg-purple-700 px-4 py-2 rounded-lg hover:bg-[#135e24] transition flex items-center">
                <i class="fas fa-newspaper mr-2"></i> Master Artikel
            </a>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-purple-700 mr-2"></i> Data Artikel
            </h2>
            
            <!-- Search Box -->
            <div class="relative">
                <form action="{{ route('superadmin.artikels.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Cari artikel..." value="{{ request('search') }}"
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-700 focus:border-purple-700 w-64">
                    <button type="submit" class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Filter & Export -->
        <div class="flex justify-between mb-4">
            <form method="GET" action="{{ route('superadmin.artikels.index') }}" class="flex space-x-2">
                <select name="date_filter" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-700 focus:border-purple-700">
                    <option value="">Semua Tanggal</option>
                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
            </form>
            
            <div class="flex space-x-2">
                <a href="{{ route('superadmin.artikels.create') }}" class="bg-purple-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-[#135e24] transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Artikel
                </a>
                {{-- <a href="#" class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800 transition flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </a> --}}
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b">
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-image mr-2 text-purple-700"></i>
                                Gambar
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2 text-purple-700"></i>
                                Author
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-heading mr-2 text-purple-700"></i>
                                Judul
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-file-alt mr-2 text-purple-700"></i>
                                Isi
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
                    @forelse ($artikels as $artikel)
                        <tr class="hover:bg-gray-50 border-b border-gray-100 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center h-full">
                                   @if (!empty($artikel->foto_artikel) && is_array($artikel->foto_artikel)) 
                                        {{-- Ambil gambar pertama dari array --}}
                                        <img src="{{ asset('storage/' . $artikel->foto_artikel[0]) }}" 
                                            class="w-16 h-16 object-cover rounded-xl shadow-sm border border-gray-100" 
                                            style="aspect-ratio: 1/1;"
                                            alt="{{ $artikel->judul }}">
                                        @if(count($artikel->foto_artikel) > 1)
                                            <span class="text-[10px] bg-purple-700 text-white px-1 rounded-full absolute -top-2 -right-2">
                                                +{{ count($artikel->foto_artikel) - 1 }}
                                            </span>
                                        @endif
                                    @else
                                        {{-- fallback jika bukan array atau kosong --}}
                                        <i class="fas fa-image text-gray-400"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $artikel->user->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $artikel->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $artikel->judul }}</div>
                            </td>
                           <td class="px-6 py-4">
                                <div class="line-clamp-2">
                                    @php
                                        $kontenArray = is_array($artikel->isi) ? $artikel->isi : json_decode($artikel->isi, true);
                                        $hanyaTeks = '';
                                        if(is_array($kontenArray)) {
                                            foreach($kontenArray as $blok) {
                                                if($blok['type'] == 'text') $hanyaTeks .= $blok['value'] . ' ';
                                            }
                                        }
                                    @endphp
                                    {!! Str::words(strip_tags($hanyaTeks), 15, '...') !!}
                                </div>
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
    <div class="fixed inset-0 z-50 flex items-center justify-end">
        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" x-on:click="open = false"></div>
        
        {{-- Panel Content --}}
        <div class="relative bg-white w-full max-w-lg h-full shadow-2xl overflow-y-auto animate__animated animate__fadeInRight animate__faster">
            
            {{-- Header Modal --}}
            <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-md px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <span class="bg-purple-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-file-alt text-purple-700"></i>
                    </span>
                    Pratinjau Artikel
                </h2>
                <button x-on:click="open = false" class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-full transition-all">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-8">
                {{-- 1. Galeri Gambar (Grid) --}}
                {{-- 1. Galeri Gambar (Grid) --}}
<div>
    <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Koleksi Media</h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
        @if(is_array($artikel->foto_artikel))
            @foreach($artikel->foto_artikel as $foto)
                <div class="group relative aspect-square overflow-hidden rounded-xl border border-gray-100 bg-gray-50">
                    <img src="{{ asset('storage/' . $foto) }}" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                </div>
            @endforeach
        @endif
    </div>
</div>

                {{-- 2. Informasi Utama --}}
                <div class="space-y-4">
                    <div class="border-l-4 border-purple-700 pl-4">
                        <h1 class="text-2xl font-extrabold text-gray-900 leading-tight">
                            {{ $artikel->judul }}
                        </h1>
                        <div class="flex items-center mt-2 text-sm text-gray-500">
                            <i class="fas fa-user-circle mr-2"></i>
                            <span class="font-medium text-gray-700">{{ $artikel->user->nama_lengkap }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $artikel->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- 3. Isi Artikel (Tipografi Profesional) --}}
                {{-- 3. Isi Artikel (Tampilan Dinamis) --}}
                <div class="space-y-6">
                    <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-2">Konten Narasi</h3>
                    
                    @php
                        $blocks = is_array($artikel->isi) ? $artikel->isi : json_decode($artikel->isi, true);
                    @endphp

                    @if(is_array($blocks))
                        @foreach($blocks as $block)
                            @if($block['type'] === 'text')
                                <div class="prose prose-purple max-w-none text-gray-700 leading-relaxed text-justify">
                                    {!! $block['value'] !!}
                                </div>
                            @elseif($block['type'] === 'image')
                                <div class="my-4 overflow-hidden rounded-2xl border border-gray-100 shadow-sm bg-gray-50">
                                    <img src="{{ asset('storage/' . $block['value']) }}" 
                                        class="w-full h-auto max-h-[400px] object-contain mx-auto block">
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p class="text-gray-500 italic">Konten tidak tersedia.</p>
                    @endif
                </div>

                {{-- 4. Footer Modal --}}
                <div class="pt-6 border-t border-gray-100 flex justify-end">
                    <button x-on:click="open = false" class="px-6 py-2.5 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-all flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Selesai Membaca
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
                                    </div>

                                   <div x-data="{ showEditModal: false }">
    <button type="button" @click="showEditModal = true"
        class="px-3 py-1.5 text-white text-xs bg-blue-500 hover:bg-blue-600 rounded-md transition flex items-center">
        <i class="fas fa-edit mr-1"></i> Edit
    </button>

    <div x-show="showEditModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        
        <div @click.away="showEditModal = false" 
             class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl flex flex-col max-h-[90vh] animate__animated animate__fadeInDown animate__faster">
            
            <div class="flex justify-between items-center p-6 border-b border-gray-100 bg-white rounded-t-2xl sticky top-0 z-10">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-edit text-blue-500 mr-2"></i> Edit Artikel
                </h2>
                <button type="button" @click="showEditModal = false" 
                        class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto p-6 custom-scrollbar">
                <form id="form-edit-{{ $artikel->id }}" method="POST" action="{{ route('superadmin.artikels.update', $artikel->id) }}" enctype="multipart/form-data" x-data="{ 
                    // State untuk mengelola penghapusan gambar lama
                    removedImages: [] 
                }">
                    @csrf
                    @method('PUT')
                    
                   <div class="space-y-6">
        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">Kelola Gambar Saat Ini (Klik X untuk Hapus)</label>
            <div class="grid grid-cols-4 sm:grid-cols-5 gap-3">
                @if(is_array($artikel->foto_artikel))
                    @foreach($artikel->foto_artikel as $index => $foto)
                        {{-- Bungkus dengan div x-show agar bisa hilang saat diklik hapus --}}
                        <div class="relative aspect-square group" x-show="!removedImages.includes('{{ $foto }}')">
                            <img src="{{ asset('storage/' . $foto) }}" 
                                 class="w-full h-full object-cover rounded-xl border-2 border-white shadow-sm transition group-hover:opacity-75">
                            
                            {{-- Tombol Hapus Gambar Individual --}}
                            <button type="button" 
                                @click="removedImages.push('{{ $foto }}')"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] hover:bg-red-600 shadow-md">
                                <i class="fas fa-times"></i>
                            </button>

                            {{-- Input hidden untuk memberi tahu server gambar mana yang DIHAPUS --}}
                            <input type="hidden" name="removed_images[]" :value="removedImages.includes('{{ $foto }}') ? '{{ $foto }}' : ''">
                        </div>
                    @endforeach
                @endif
            </div>
            
            <div class="mt-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tambah Foto Baru ke Galeri</label>
                <input type="file" name="foto_artikel[]" multiple
                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Artikel</label>
            <input type="text" name="judul" value="{{ $artikel->judul }}" required
                class="w-full border border-gray-200 rounded-xl px-4 py-2 focus:ring-2 focus:ring-purple-500 outline-none transition text-sm">
        </div>

        <div class="space-y-4">
            <label class="block text-sm font-semibold text-gray-700">Editor Konten Berurutan</label>
            <div class="space-y-4 bg-gray-50 p-4 rounded-2xl border border-gray-200">
    @php
        // Pastikan isi didecode menjadi array
        $blocks = is_array($artikel->isi) ? $artikel->isi : json_decode($artikel->isi, true);
    @endphp

    @if(is_array($blocks))
        @foreach($blocks as $index => $block)
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm relative group" x-data="{ isDeleted: false }" x-show="!isDeleted">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold px-2 py-1 rounded-md {{ ($block['type'] == 'text' || $block['type'] == 'teks') ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }} uppercase">
                        {{ ($block['type'] == 'text' || $block['type'] == 'teks') ? 'Blok Teks' : 'Gambar Sisipan' }}
                    </span>
                    
                    {{-- Tombol Hapus Blok --}}
                    <button type="button" @click="isDeleted = true" class="text-red-400 hover:text-red-600 transition">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                </div>

                {{-- KONTEN TEKS --}}
                @if($block['type'] == 'text' || $block['type'] == 'teks')
                    <textarea name="konten_isi[{{ $index }}]" rows="4" :disabled="isDeleted"
                        class="w-full text-sm border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 transition-all">{{ $block['value'] }}</textarea>
                    <input type="hidden" name="tipe_blok[{{ $index }}]" value="teks" :disabled="isDeleted">

                {{-- KONTEN GAMBAR --}}
                @else
                    <div class="flex items-center gap-4 bg-blue-50/50 p-2 rounded-lg">
                        <img src="{{ asset('storage/' . $block['value']) }}" class="w-20 h-20 object-cover rounded-lg border border-white">
                        <div class="flex-1">
                            <p class="text-[11px] text-gray-500 truncate">File: {{ Str::afterLast($block['value'], '/') }}</p>
                            
                            {{-- Input untuk ganti gambar --}}
                            <input type="file" name="replace_image_block[{{ $index }}]" class="mt-2 text-[10px]">
                            
                            {{-- Hidden input untuk menyimpan path lama jika tidak diganti --}}
                            <input type="hidden" name="konten_isi[{{ $index }}]" value="{{ $block['value'] }}" :disabled="isDeleted">
                            <input type="hidden" name="tipe_blok[{{ $index }}]" value="gambar" :disabled="isDeleted">
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>
        </div>
    </div>
</form>
            </div>

            <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex justify-end space-x-3 sticky bottom-0 z-10">
                <button type="button" @click="showEditModal = false"
                    class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-100 transition shadow-sm">
                    Batal
                </button>
                <button type="submit" form="form-edit-{{ $artikel->id }}"
                    class="px-8 py-2.5 text-sm font-bold text-white bg-purple-700 rounded-xl hover:bg-purple-800 transition shadow-lg shadow-purple-200 flex items-center">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('superadmin.artikels.destroy', $artikel->id) }}" method="POST" 
                                        onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
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
                                    <i class="fas fa-newspaper text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-red-500 font-medium">Belum ada artikel.</p>
                                    <p class="text-gray-500 text-sm mt-1">Tidak ada data artikel untuk ditampilkan saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $artikels->links('vendor.pagination.custom') }}
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