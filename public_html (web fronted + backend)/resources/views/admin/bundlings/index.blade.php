@extends('admin.layouts.master')

@section('title', 'WOWINFood - Kelola Bundling')

@section('head')
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
@endsection

@section('content')
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
                    <a href="{{ route('admin.bundlings.index') }}" class="hover:text-[#16782d] transition-colors duration-200">Kelola Bundling</a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Master Data Bundling
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-[#16782d] p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-box-open text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Master Data Bundling</h1>
                <p class="text-gray-600">Halaman untuk mengelola data bundling produk yang tersedia.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-3">
                    <i class="fas fa-box text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Bundling</p>
                    <p class="text-xl font-bold">{{ $bundlings->total() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-3">
                    <i class="fas fa-calendar-check text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Aktif Hari Ini</p>
                    <p class="text-xl font-bold">{{ $bundlings->where('waktu_diskon_mulai', '<=', date('Y-m-d'))->where('waktu_diskon_selesai', '>=', date('Y-m-d'))->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-3">
                    <i class="fas fa-tag text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Akan Datang</p>
                    <p class="text-xl font-bold">{{ $bundlings->where('waktu_diskon_mulai', '>', date('Y-m-d'))->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex gap-3 mt-4">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-[#16782d] border border-[#16782d] px-4 py-2 rounded-lg hover:bg-[#16782d]/10 transition flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.bundlings.index') }}" class="text-sm font-medium text-white bg-[#16782d] px-4 py-2 rounded-lg hover:bg-[#135e24] transition flex items-center">
                <i class="fas fa-box-open mr-2"></i> Master Bundling
            </a>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-[#16782d] mr-2"></i> Data Bundling
            </h2>
            
            <!-- Search Box -->
            <div class="relative">
                <form action="{{ route('admin.bundlings.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Cari bundling..." 
                        value="{{ request('search') }}" 
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#16782d] focus:border-[#16782d] w-64">
                    <button type="submit" class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Filter & Add Button -->
        <div class="flex justify-between mb-4">
            <div class="flex space-x-2">
                <form action="{{ route('admin.bundlings.index') }}" method="GET">
                    {{-- Pertahankan pencarian jika ada --}}
                    <input type="hidden" name="search" value="{{ request('search') }}">
            
                    <select name="status" onchange="this.form.submit()"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#16782d] focus:border-[#16782d]">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Berakhir</option>
                    </select>
                </form>
            </div>
            
            <div class="flex space-x-2">
                <a href="{{ route('admin.bundlings.create') }}" class="bg-[#16782d] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#135e24] transition flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Bundling
                </a>
                <a href="#" class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800 transition flex items-center">
                    <i class="fas fa-file-export mr-2"></i> Export Data
                </a>
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b">
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-image mr-2 text-[#16782d]"></i>
                                Gambar Bundling
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-box-open mr-2 text-[#16782d]"></i>
                                Nama Bundling
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-[#16782d]"></i>
                                Waktu Mulai
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-times mr-2 text-[#16782d]"></i>
                                Waktu Selesai
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
                    @forelse ($bundlings as $bundling)
                        <tr class="hover:bg-gray-50 border-b border-gray-100 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center">
                                    @if (!empty($bundling->barang_bundling)) 
                                        <img src="{{ asset('storage/' . $bundling->barang_bundling) }}" 
                                             class="w-16 h-16 object-cover rounded-lg" 
                                             alt="{{ $bundling->nama_bundling }}">
                                    @else
                                        <div class="w-16 h-16 flex items-center justify-center bg-gray-100 rounded-lg">
                                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $bundling->nama_bundling }}</div>
                                <div class="text-xs text-gray-500">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Rp {{ number_format($bundling->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <i class="far fa-calendar-alt text-gray-400 mr-2"></i>
                                    {{ $bundling->waktu_diskon_mulai }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <i class="far fa-calendar-check text-gray-400 mr-2"></i>
                                    {{ $bundling->waktu_diskon_selesai }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Tombol Detail -->
                                    <div x-data="{ open: false }" class="relative">
                                        <button x-on:click="open = true" class="px-3 py-1.5 text-white text-xs bg-[#16782d] rounded-md hover:bg-[#135e24] transition flex items-center">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </button>
                                        
                                        <!-- Modal Detail -->
                                        <template x-if="open">
                                            <div class="fixed inset-0 z-50">
                                                <!-- Overlay -->
                                                <div class="absolute inset-0 bg-black bg-opacity-40" x-on:click="open = false"></div>
                                                <!-- Modal content -->
                                                <div class="absolute right-0 top-0 bg-white w-full max-w-md h-full shadow-2xl p-6 overflow-y-auto animate__animated animate__fadeInRight animate__faster">
                                                    <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                                                        <h2 class="text-xl font-semibold text-[#16782d] flex items-center">
                                                            <i class="fas fa-box-open mr-2"></i> Detail Bundling
                                                        </h2>
                                                        <button x-on:click="open = false" class="text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition-colors duration-200">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Detail Content -->
                                                    <div class="space-y-4">
                                                        <div class="flex justify-center mb-4">
                                                            <div class="w-24 h-24 rounded-full overflow-hidden border border-gray-300 flex items-center justify-center bg-gray-100">
                                                                @if (!empty($bundling->barang_bundling)) 
                                                                    <img src="{{ asset('storage/' . $bundling->barang_bundling) }}" 
                                                                         class="w-full h-full object-cover" 
                                                                         alt="{{ $bundling->nama_bundling }}">
                                                                @else
                                                                    <i class="fas fa-image text-gray-400 text-3xl"></i>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Nama Barang Bundling</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-box-open text-gray-400 mr-2"></i>
                                                                <span>{{ $bundling->nama_bundling }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Waktu Diskon Mulai</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                                                <span>{{ $bundling->waktu_diskon_mulai }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Waktu Diskon Selesai</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-calendar-times text-gray-400 mr-2"></i>
                                                                <span>{{ $bundling->waktu_diskon_selesai }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Harga Bundling</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-tag text-gray-400 mr-2"></i>
                                                                <span>Rp {{ number_format($bundling->price, 0, ',', '.') }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Harga Awal</label>
                                                            <div class="flex items-center border bg-white rounded p-2">
                                                                <i class="fas fa-money-bill text-gray-400 mr-2"></i>
                                                                <span>Rp {{ number_format($bundling->price_before, 0, ',', '.') }}</span>
                                                            </div>
                                                        </div>

                                                       
                                                        
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Syarat dan Ketentuan</label>
                                                            <div class="border bg-white rounded p-3 max-h-60 overflow-y-auto">
                                                                <div class="border bg-white rounded p-3 max-h-60 overflow-y-auto">
                                                                    <div class="[&>ul]:list-disc [&>ul]:pl-5 [&>li]:mb-1">
                                                                        {!! $bundling->snk !!}
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="bg-gray-50 p-4 rounded-lg">
                                                            <label class="block font-medium text-sm text-gray-700 mb-1">Produk dalam Promo</label>
                                                            <ul class="list-disc pl-5 text-sm text-gray-800 space-y-1">
                                                                @forelse ($bundling->products as $product)
                                                                    <li>{{ $product->nama_produk }}</li>
                                                                @empty
                                                                    <li class="text-gray-500 italic">Belum ada produk yang ditambahkan.</li>
                                                                @endforelse
                                                            </ul>
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
                                    
                                    <!-- Tombol Edit -->
                                    <div x-data="{ showEditModal: false }" class="relative">
                                        <button @click="showEditModal = true" class="px-3 py-1.5 text-xs text-white bg-blue-500 hover:bg-blue-600 rounded-md transition flex items-center">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        
                                        <!-- Modal Edit -->
                                       <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 animate__animated animate__fadeInDown animate__faster overflow-y-auto max-h-screen">
                                                <div class="flex justify-between items-center pb-3 border-b border-gray-200 mb-4">
                                                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                                        <i class="fas fa-edit text-blue-500 mr-2"></i> Edit Data Bundling
                                                    </h2>
                                                    <button @click="showEditModal = false" class="text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition-colors duration-200">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                
                                                <form method="POST" action="{{ route('admin.bundlings.update', $bundling->id_bundling) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang Bundling</label>
                                                            <div class="relative">
                                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                    <i class="fas fa-box-open text-gray-400"></i>
                                                                </div>
                                                                <input type="text" name="nama_bundling" value="{{ $bundling->nama_bundling }}" class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 focus:ring-[#16782d] focus:border-[#16782d]">
                                                            </div>
                                                        </div>
                                                        
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                                                            <div class="relative">
                                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                                                </div>
                                                                <input type="date" name="waktu_diskon_mulai" value="{{ $bundling->waktu_diskon_mulai }}" class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 focus:ring-[#16782d] focus:border-[#16782d]">
                                                            </div>
                                                        </div>
                                                        
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Diskon Selesai</label>
                                                            <div class="relative">
                                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                    <i class="fas fa-calendar-times text-gray-400"></i>
                                                                </div>
                                                                <input type="date" name="waktu_diskon_selesai" value="{{ $bundling->waktu_diskon_selesai }}" class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 focus:ring-[#16782d] focus:border-[#16782d]">
                                                            </div>
                                                        </div>
                                                        
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Bundling</label>
                                                            <div class="relative">
                                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                    <i class="fas fa-tag text-gray-400"></i>
                                                                </div>
                                                                <input type="number" name="price" value="{{ $bundling->price }}" class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 focus:ring-[#16782d] focus:border-[#16782d]">
                                                            </div>
                                                        </div>
                                                        
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Awal</label>
                                                            <div class="relative">
                                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                    <i class="fas fa-money-bill text-gray-400"></i>
                                                                </div>
                                                                <input type="number" name="price_before" value="{{ $bundling->price_before }}" class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 focus:ring-[#16782d] focus:border-[#16782d]">
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Bundling</label>
                                                            <div class="flex items-center mb-2">
                                                                @if (!empty($bundling->barang_bundling))
                                                                    <div class="w-16 h-16 rounded-lg overflow-hidden border border-gray-300 mr-3">
                                                                        <img src="{{ asset('storage/' . $bundling->barang_bundling) }}" class="w-full h-full object-cover" alt="{{ $bundling->nama_bundling }}">
                                                                    </div>
                                                                @endif
                                                                <div class="relative flex-1">
                                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                        <i class="fas fa-image text-gray-400"></i>
                                                                    </div>
                                                                    <input type="file" name="barang_bundling" class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 focus:ring-[#16782d] focus:border-[#16782d] text-sm">
                                                                </div>
                                                            </div>
                                                            <p class="text-xs text-gray-500">Kosongkan jika tidak ingin mengubah foto.</p>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Syarat & Ketentuan</label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                <i class="fas fa-list-ul text-gray-400"></i>
                                                            </div>
                                                            <textarea name="snk" id="snk_{{ $bundling->id_bundling }}" class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 focus:ring-[#16782d] focus:border-[#16782d]">{{ $bundling->snk }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-span-2">
                                                        <label for="products_{{ $bundling->id_bundling }}" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                                            <i class="fas fa-tags text-[#16782d] mr-2"></i>
                                                            Pilih Produk untuk Promo Ini
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                <i class="fas fa-box text-gray-400"></i>
                                                            </div>
                                                            <select name="products[]" id="products_{{ $bundling->id_bundling }}" class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 focus:ring-[#16782d] focus:border-[#16782d] select2" multiple required>
                                                                @foreach ($products as $product)
                                                                    <option value="{{ $product->id_product }}"
                                                                        {{ in_array($product->id_product, old('products', $bundling->products->pluck('id_product')->toArray())) ? 'selected' : '' }}>
                                                                        {{ $product->nama_produk }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <small class="text-gray-500 block mt-2">
                                                            Tekan <strong>Ctrl</strong> (Windows) atau <strong>Cmd</strong> (Mac) untuk memilih lebih dari satu produk.
                                                        </small>
                                                        @error('products')
                                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="flex justify-end mt-6 space-x-2">
                                                        <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition flex items-center">
                                                            <i class="fas fa-times mr-1"></i> Batal
                                                        </button>
                                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center">
                                                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tombol Hapus -->
                                    <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus bundling ini?');" action="{{ route('admin.bundlings.destroy', $bundling->id_bundling) }}" method="POST">
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
                                    <i class="fas fa-box-open text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-red-500 font-medium">Data bundling belum ada.</p>
                                    <p class="text-gray-500 text-sm mt-1">Belum ada data bundling untuk ditampilkan saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $bundlings->links('pagination::tailwind') }}
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
    // Store the CKEditor instances for future reference
    const editorInstances = {};

    // Add custom styles to the document to ensure proper bullet list display
    const styleElement = document.createElement('style');
    styleElement.textContent = `
        /* Custom styles for CKEditor content */
        .ck-content ul {
            list-style-type: disc !important;
            padding-left: 40px !important;
            margin: 1em 0 !important;
        }
        .ck-content ul li {
            display: list-item !important;
            margin-bottom: 8px !important;
            font-size: 14px !important;
        }
        /* Make sure the bullet points are visible */
        .ck-content ul li::marker {
            font-size: 1.2em !important;
        }
        /* Style for editor container */
        .ck.ck-editor__editable_inline {
            min-height: 150px;
            padding: 10px 15px;
        }
        /* Make the toolbar buttons more visible */
        .ck.ck-toolbar .ck-button {
            padding: 8px;
        }
    `;
    document.head.appendChild(styleElement);

    // Function to initialize CKEditor with proper list support
    function initEditor(editorId) {
        const element = document.getElementById(editorId);
        
        // Check if element exists and hasn't been initialized yet
        if (element && !editorInstances[editorId]) {
            ClassicEditor
                .create(element, {
                    // Simple toolbar configuration focused on lists
                    toolbar: {
                        items: [
                            'bold', 'italic',
                            '|',
                            'bulletedList', 'numberedList',
                            '|',
                            'undo', 'redo'
                        ],
                        shouldNotGroupWhenFull: true
                    },
                    // Explicitly configure the bullet list icon
                    bulletedList: {
                        properties: {
                            styles: false,  // Disable styles to use default
                            startIndex: false,
                            reversed: false
                        },
                        icon: 'bulletedList'  // Ensure proper icon is used
                    },
                    // Ensure proper plugins are loaded
                    plugins: ['Essentials', 'Bold', 'Italic', 'List', 'Paragraph']
                })
                .then(editor => {
                    // Store the editor instance
                    editorInstances[editorId] = editor;
                    console.log(`Editor ${editorId} initialized successfully`);
                    
                    // Focus the editor to ensure it's ready for input
                    editor.editing.view.focus();
                })
                .catch(error => {
                    console.error(`Failed to initialize editor ${editorId}:`, error);
                });
        }
    }

    // Function to detect when a modal is opened and initialize editors
    function setupModalListeners() {
        // For Alpine.js modals
        document.addEventListener('click', function(event) {
            // Find any button that opens a modal
            const modalButton = event.target.closest('button[x-on\\:click*="showEditModal = true"], button[\\@click*="showEditModal = true"]');
            
            if (modalButton) {
                // Get the bundling ID from the button or a nearby element
                const modalContainer = modalButton.closest('[x-data*="showEditModal"]');
                if (modalContainer) {
                    // Give Alpine.js time to render the modal
                    setTimeout(() => {
                        // Find all SNK editors in the newly opened modal
                        document.querySelectorAll('[id^="snk_"]').forEach(element => {
                            if (element.offsetParent !== null) { // Check if element is visible
                                initEditor(element.id);
                            }
                        });
                    }, 150); // Slightly longer timeout to ensure modal is fully rendered
                }
            }
        });
    }

    // Initialize any visible editors when the page loads
    document.querySelectorAll('[id^="snk_"]').forEach(element => {
        if (element.offsetParent !== null) { // Check if element is visible
            initEditor(element.id);
        }
    });

    // Set up modal listeners
    setupModalListeners();

    // Toast notification handling
    if (document.getElementById('toast-success')) {
        @if (session('success'))
            document.getElementById('toast-success').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('toast-success').classList.add('hidden');
            }, 3000);
        @endif

        document.getElementById('close-toast')?.addEventListener('click', function() {
            document.getElementById('toast-success').classList.add('hidden');
        });
    }
});
</script>
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
