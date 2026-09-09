@extends('admin.layouts.master')

@section('title', 'WOWINFood - Kelola Produk')

@section('head')
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .ck-editor__editable_inline {
            min-height: 200px;
        }

        .ck.ck-editor {
            z-index: 50 !important;
            position: relative !important;
        }

        .ck.ck-toolbar {
            z-index: 50 !important;
        }

        .ck-editor__editable_inline:focus {
            z-index: 50 !important;
            position: relative !important;
        }
        
        .ck-content ul,
        .ck-content ol {
            padding-left: 1.5rem;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
            list-style-type: disc;
        }

        .ck-content li {
            font-size: 1rem;
            line-height: 1.5rem;
            margin-bottom: 0.25rem;
        }
    </style>
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
                    <a href="{{ route('admin.products.index') }}" class="hover:text-[#16782d] transition-colors duration-200">Kelola Produk</a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Master Produk
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-[#16782d] p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-box text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Kelola Produk</h1>
                <p class="text-gray-600">Master data produk untuk kebutuhan manajemen dan dokumentasi produk.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-3">
                    <i class="fas fa-box-open text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Produk</p>
                    <p class="text-xl font-bold">{{ $products->total() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-3">
                    <i class="fas fa-plus-circle text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Ditambahkan Hari Ini</p>
                    <p class="text-xl font-bold">{{ $products->where('created_at', '>=', \Carbon\Carbon::today())->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-3">
                    <i class="fas fa-sync-alt text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Diperbarui Minggu Ini</p>
                    <p class="text-xl font-bold">{{ $products->where('updated_at', '>=', \Carbon\Carbon::now()->startOfWeek())->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex gap-3 mt-4">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-[#16782d] border border-[#16782d] px-4 py-2 rounded-lg hover:bg-[#16782d]/10 transition flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-white bg-[#16782d] px-4 py-2 rounded-lg hover:bg-[#135e24] transition flex items-center">
                <i class="fas fa-box mr-2"></i> Master Produk
            </a>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-[#16782d] mr-2"></i> Data Produk
            </h2>
            
            <div class="relative">
                <form action="{{ route('admin.products.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Cari produk..." 
                        value="{{ request('search') }}" {{-- Agar tetap muncul setelah search --}}
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#16782d] focus:border-[#16782d] w-64">
                    <button type="submit" class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            
        </div>

        <!-- Filter & Export -->
        <div class="flex justify-between mb-4">
            <div class="flex space-x-2">
                <form action="{{ route('admin.products.index') }}" method="GET">
                    {{-- Pertahankan pencarian jika sedang digunakan --}}
                    <input type="hidden" name="search" value="{{ request('search') }}">
            
                    <select name="sort" onchange="this.form.submit()" 
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#16782d] focus:border-[#16782d]">
                        <option value="">Semua Produk</option>
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </form>
            </div>
            
            <div class="flex space-x-2">
                <a href="{{ route('admin.products.create') }}" class="bg-[#16782d] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#135e24] transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Produk
                </a>
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
                                <i class="fas fa-image mr-2 text-[#16782d]"></i>
                                Foto Produk
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-tag mr-2 text-[#16782d]"></i>
                                Nama Produk
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-flask mr-2 text-[#16782d]"></i>
                                Isi Per (ml)
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-money-bill-wave mr-2 text-[#16782d]"></i>
                                Harga
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
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 border-b border-gray-100 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center">
                                    @if ($product->images->isNotEmpty()) 
                                        <img src="{{ asset('storage/'.$product->images->first()->image_url) }}" 
                                             class="w-16 h-16 object-cover rounded-lg shadow-sm" 
                                             alt="{{ $product->nama_produk }}">
                                    @else
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $product->nama_produk }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $product->id_product }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-md text-xs font-medium">
                                    {{ $product->isi_ml }} ml
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Tombol Detail -->
<div x-data="{ open: false }" class="relative">
    <button x-on:click="open = true" class="px-3 py-1.5 text-white text-xs bg-[#16782d] rounded-md hover:bg-[#135e24] transition flex items-center">
        <i class="fas fa-eye mr-1"></i> Detail
    </button>

    <!-- Modal -->
    <template x-if="open">
        <div class="fixed inset-0 z-50 flex items-center justify-center" x-cloak>
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-40" x-on:click="open = false"></div>

            <!-- Modal Content -->
            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-xl mx-4 p-6 z-50 animate__animated animate__fadeInDown animate__faster overflow-y-auto max-h-[90vh]">
                <!-- Header -->
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                    <h2 class="text-xl font-semibold text-[#16782d] flex items-center">
                        <i class="fas fa-box-open mr-2"></i> Detail Produk
                    </h2>
                    <button x-on:click="open = false" class="text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition-colors duration-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Gambar Produk -->
                <div class="flex flex-wrap justify-center mb-4">
                    @foreach ($product->images as $image)
                        <img src="{{ asset('storage/' . $image->image_url) }}" 
                            class="w-24 h-24 object-cover rounded-lg border-2 border-[#16782d]/30 shadow-md m-2" 
                            alt="{{ $product->nama_produk }}">
                    @endforeach
                    @if ($product->images->isEmpty())
                        <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-[#16782d]/30">
                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Detail Produk -->
                @php
                    $rekomText = $product->rekom_guna;
                    $rekomText = str_replace(['<li>', '</li>'], ['', "\n"], $rekomText);
                    $rekomText = strip_tags($rekomText);
                    $rekomText = preg_replace('/^/m', '• ', $rekomText);
                @endphp

                <div class="space-y-3">
                    @foreach ([
                        'Nama Produk' => ['fas fa-tag', $product->nama_produk],
                        'Isi' => ['fas fa-flask', $product->isi_ml],
                        'Berat' => ['fas fa-weight-hanging', ($product->berat ? number_format($product->berat, 0, ',', '.') . ' gr' : '-')],
                        'Harga' => ['fas fa-money-bill-wave', 'Rp ' . number_format($product->harga, 0, ',', '.')],
                        'Isi Karton' => ['fas fa-box', $product->isi_karton],
                        'Nomor BPOM' => ['fas fa-certificate', $product->no_bpom],
                        'Nomor Sertifikat Halal' => ['fas fa-check-circle', $product->no_halal],
                       'Kategori' => ['fas fa-tag', $product->category->name ?? '-'],
                    ] as $label => [$icon, $value])
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">{{ $label }}</label>
                            <div class="flex items-center border bg-white rounded p-2 shadow-sm">
                                <i class="{{ $icon }} text-gray-400 mr-2"></i>
                                <span>{{ $value }}</span>
                            </div>
                        </div>
                    @endforeach

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Rekomendasi Penggunaan</label>
                        <div class="border bg-white rounded p-3 shadow-sm max-h-40 overflow-y-auto whitespace-pre-line text-sm text-gray-700 leading-relaxed">
                            {{ $rekomText }}
                        </div>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="flex justify-end space-x-2 mt-6">
                    <button x-on:click="open = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition flex items-center">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                    <a href="{{ route('admin.products.edit', $product->id_product) }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition flex items-center">
                        <i class="fas fa-pencil-alt mr-1"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </template>
</div>

                     <!-- Edit Button dengan Modal yang Lengkap -->
<div x-data="{ showEditModal: false }" class="relative">
    <button @click="showEditModal = true; $nextTick(() => initProductEditor('{{ $product->id_product }}'))" 
        class="px-3 py-1.5 text-xs text-white bg-blue-500 hover:bg-blue-600 rounded-md transition flex items-center">
        <i class="fas fa-edit mr-1"></i> Edit
    </button>
    
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="absolute inset-0 bg-black bg-opacity-40" @click="showEditModal = false"></div>
        <div class="relative bg-white w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-lg shadow-lg animate__animated animate__fadeIn animate__faster">
            <div class="p-6">
                <!-- Header Modal -->
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 mb-4">
                    <h2 class="text-xl font-semibold text-[#16782d] flex items-center">
                        <i class="fas fa-pencil-alt mr-2"></i> Edit Produk
                    </h2>
                    <button @click="showEditModal = false" class="text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition-colors duration-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Form Edit -->
                <form method="POST" action="{{ route('admin.products.update', $product->id_product) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Produk -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                            <input type="text" name="nama_produk" value="{{ $product->nama_produk }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-[#16782d] focus:border-[#16782d]">
                        </div>
                    <div class="kategori-wrapper space-y-2 relative">
                            <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>

                            <input type="text" 
                                    class="kategori-input w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-[#16782d] focus:border-[#16782d]" 
                                    value="{{ $product->category->name ?? '' }}" 
                                    autocomplete="off">

                            <input type="hidden" name="category_id" class="category-id" value="{{ old('category_id', $product->category_id) }}">

                            <ul class="kategori-list absolute z-50 bg-white border border-gray-300 mt-1 w-full rounded hidden shadow-md max-h-40 overflow-auto">
                            </ul>
                    </div>
                        <script>
                        const searchUrl = "{{ route('admin.categories.search') }}";
                        </script>

                        <!-- Isi ML -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Isi </label>
                            <input type="text" name="isi_ml" value="{{ $product->isi_ml }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-[#16782d] focus:border-[#16782d]">
                        </div>

                        <!-- Berat (Gram) -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Berat Kotor (Gram)</label>
                            <input type="number" step="any" name="berat" value="{{ old('berat', $product->berat ? (int)$product->berat : '') }}"
                                placeholder="Contoh: 500"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-[#16782d] focus:border-[#16782d]">
                            <p class="text-xs text-gray-400">Berat kotor fisik termasuk kemasan (gram) untuk dasar hitung ongkir J&T.</p>
                        </div>


                        <!-- Harga -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Harga</label>
                            <input type="text" name="harga" value="{{ $product->harga }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-[#16782d] focus:border-[#16782d]">
                        </div>

                        <!-- Isi Karton -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Isi Karton</label>
                            <input type="text" name="isi_karton" value="{{ $product->isi_karton }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-[#16782d] focus:border-[#16782d]">
                        </div>

                        <!-- No BPOM -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Nomor BPOM</label>
                            <input type="text" name="no_bpom" value="{{ $product->no_bpom }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-[#16782d] focus:border-[#16782d]">
                        </div>

                        <!-- No Halal -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Nomor Sertifikat Halal</label>
                            <input type="text" name="no_halal" value="{{ $product->no_halal }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-[#16782d] focus:border-[#16782d]">
                        </div>

                        <!-- Rekomendasi Penggunaan -->
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Rekomendasi Penggunaan</label>
                            <textarea name="rekom_guna" id="rekom_guna_{{ $product->id_product }}" rows="4"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-[#16782d] focus:border-[#16782d] editor-field">{{ $product->rekom_guna }}</textarea>
                        </div>

                        <!-- Gambar Lama -->
<div class="space-y-2 md:col-span-2">
    <label class="block text-sm font-medium text-gray-700">Gambar Produk</label>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach ($product->images as $image)
            <div class="space-y-1">
                <img src="{{ asset('storage/' . $image->image_url) }}" class="w-full h-24 object-cover rounded border">
                
                <label class="block text-xs mt-1">
                    Ganti Gambar:
                    <input type="file" name="replace_image[{{ $image->id }}]">
                </label>
                
                <label class="text-xs text-red-600 mt-1 block">
                    <input type="checkbox" name="hapus[]" value="{{ $image->id }}"> Hapus
                </label>
            </div>
        @endforeach
    </div>
</div>

<!-- Gambar Baru -->
<div class="space-y-2 md:col-span-2 mt-4">
    <label class="block text-sm font-medium text-gray-700">Tambah Gambar Baru</label>
    <input type="file" name="product_images[]" multiple
        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-[#16782d] focus:border-[#16782d]">
</div>

                    </div>
  <input type="hidden" name="page" value="{{ request()->get('page', 1) }}">
                    <!-- Tombol Aksi -->
                    <div class="flex justify-end mt-6 space-x-2">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center text-sm">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-[#16782d] text-white rounded-lg hover:bg-[#135e24] transition flex items-center text-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
                                    <!-- Hapus Button -->
                                    <form action="{{ route('admin.products.destroy', $product->id_product) }}" method="POST" 
                                        onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
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
                                    <p class="text-red-500 font-medium">Belum ada produk.</p>
                                    <p class="text-gray-500 text-sm mt-1">Tidak ada data produk untuk ditampilkan saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
           {{ $products->links('vendor.pagination.custom') }}
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
    // Tambahkan ini ke bagian script Anda
document.addEventListener("DOMContentLoaded", function() {
    // Untuk modal edit yang sudah ada di DOM
    window.initProductEditor = function(productId) {
        const editorElement = document.getElementById('rekom_guna_' + productId);
        if (editorElement && !editorElement.classList.contains('ck-initialized')) {
            ClassicEditor
                .create(editorElement, {
                    toolbar: ['bold', 'bulletedList', 'undo', 'redo'],
                })
                .then(editor => {
                    // Tandai elemen telah diinisialisasi
                    editorElement.classList.add('ck-initialized');
                })
                .catch(error => {
                    console.error('Error initializing editor:', error);
                });
        }
    };
});
</script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    // Simpan referensi editor yang sudah diinisialisasi
    const editorInstances = {};
    
    // Fungsi untuk menginisialisasi editor dengan ID produk tertentu
    function initProductEditor(productId) {
        const editorId = 'rekom_guna_' + productId;
        const element = document.getElementById(editorId);
        
        // Hanya inisialisasi jika elemen ada dan belum diinisialisasi
        if (element && !editorInstances[editorId]) {
            ClassicEditor
                .create(element, {
                    toolbar: ['bold', 'bulletedList', 'undo', 'redo'],
                })
                .then(editor => {
                    // Simpan instance editor untuk referensi nanti
                    editorInstances[editorId] = editor;
                    console.log('Editor berhasil diinisialisasi: ' + editorId);
                })
                .catch(error => {
                    console.error('Error initializing editor:', error);
                });
        }
    }
    
    document.addEventListener("DOMContentLoaded", function() {
        // Toasts dan notifikasi lainnya
        @if (session('success'))
            document.getElementById('toast-success').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('toast-success').classList.add('hidden');
            }, 3000);
        @endif
        
        document.getElementById('close-toast').addEventListener('click', function() {
            document.getElementById('toast-success').classList.add('hidden');
        });
        
        // Tambahkan event listener untuk modal saat dokumen dimuat
        document.querySelectorAll('[x-data*="showEditModal"]').forEach(modal => {
            const productId = modal.querySelector('[id^="rekom_guna_"]')?.id.split('_').pop();
            if (productId) {
                const button = modal.querySelector('button');
                button.addEventListener('click', function() {
                    // Sedikit penundaan untuk memastikan DOM diperbarui
                    setTimeout(() => initProductEditor(productId), 100);
                });
            }
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrappers = document.querySelectorAll('.kategori-wrapper');

    wrappers.forEach(wrapper => {
        const input = wrapper.querySelector('.kategori-input');
        const list = wrapper.querySelector('.kategori-list');
        const hiddenInput = wrapper.querySelector('.category-id');

        input.addEventListener('input', function () {
            const query = this.value.trim();
            if (!query) {
                list.classList.add('hidden');
                return;
            }

            fetch(`/admin/categories/search?name=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    list.innerHTML = '';
                    if (data.length === 0) {
                        list.classList.add('hidden');
                        return;
                    }

                    data.forEach(cat => {
                        const li = document.createElement('li');
                        li.textContent = cat.name;
                        li.classList.add('cursor-pointer', 'px-2', 'py-1', 'hover:bg-gray-200');
                        li.addEventListener('click', function () {
                            input.value = cat.name;
                            hiddenInput.value = cat.id;
                            list.classList.add('hidden');
                        });
                        list.appendChild(li);
                    });

                    list.classList.remove('hidden');
                })
                .catch(() => {
                    list.classList.add('hidden');
                });
        });

        // Tutup dropdown kalau klik di luar
        document.addEventListener('click', function (e) {
            if (!wrapper.contains(e.target)) {
                list.classList.add('hidden');
            }
        });
    });
});
</script>
@endsection
