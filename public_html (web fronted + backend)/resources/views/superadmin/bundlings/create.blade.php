@extends('superadmin.layouts.master')

@section('title', 'WOWINFood - Tambah Produk Bundling')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- CKEditor 5 untuk text editor -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<style>
    .ck-editor__editable_inline {
        min-height: 200px;
    }

    .ck.ck-editor {
        z-index: 30 !important;
        position: relative !important;
    }

    .ck.ck-toolbar {
        z-index: 30 !important;
    }

    .ck-editor__editable_inline:focus {
        z-index: 30 !important;
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
                    <a href="{{ route('superadmin.bundlings.index') }}" class="hover:text-purple-700 transition-colors duration-200">Kelola Bundling</a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Tambah Produk Bundling
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-purple-700 p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-plus-circle text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Tambah Produk Bundling</h1>
                <p class="text-gray-600">Tambahkan promo bundling baru untuk ditampilkan di website publik.</p>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex gap-3 mt-4">
            <a href="{{ route('superadmin.dashboard') }}" class="text-sm font-medium text-purple-700 border border-purple-700 px-4 py-2 rounded-lg hover:bg-purple-700/10 transition flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('superadmin.bundlings.index') }}" class="text-sm font-medium text-purple-700 border border-purple-700 px-4 py-2 rounded-lg hover:bg-purple-700/10 transition flex items-center">
                <i class="fas fa-box mr-2"></i> Data Bundling
            </a>
            <span class="text-sm font-medium text-white bg-purple-700 px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Bundling
            </span>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex items-center justify-center mb-6">
            <div class="bg-purple-700/10 p-4 rounded-full">
                <i class="fas fa-percentage text-2xl text-purple-700"></i>
            </div>
        </div>
        <h1 class="text-center font-semibold text-xl text-gray-800">INPUT PRODUK BUNDLINGS</h1>
        <p class="text-center text-gray-500 mb-8">Silahkan mengisi inputan dibawah ini untuk menambahkan promo bundlings</p>
        
        <form action="{{ route('superadmin.bundlings.store') }}" method="POST" enctype="multipart/form-data" class="mx-auto max-w-4xl">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <!-- Nama Promo Bundling -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="nama_bundling" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-tag text-purple-700 mr-2"></i>
                            Nama Promo Bundling
                        </label>
                        <input type="text" name="nama_bundling" id="nama_bundling" value="{{ old('nama_bundling') }}" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-700 focus:border-purple-700"/>
                        @error('nama_bundling')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Waktu Diskon Mulai -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="Waktu_diskon_mulai" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-calendar-plus text-purple-700 mr-2"></i>
                            Waktu Diskon Mulai
                        </label>
                        <input type="date" name="Waktu_diskon_mulai" id="Waktu_diskon_mulai" value="{{ old('waktu_diskon_mulai') }}" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-700 focus:border-purple-700"/>
                        @error('waktu_diskon_mulai')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Waktu Diskon Selesai -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="Waktu_diskon_selesai" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-calendar-minus text-purple-700 mr-2"></i>
                            Waktu Diskon Selesai
                        </label>
                        <input type="date" name="Waktu_diskon_selesai" id="Waktu_diskon_selesai" value="{{ old('waktu_diskon_selesai') }}" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-700 focus:border-purple-700"/>
                        @error('waktu_diskon_selesai')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Kolom Kanan -->
                <div class="space-y-4">
                    <!-- Harga Setelah Diskon -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="price" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-money-bill-wave text-purple-700 mr-2"></i>
                            Harga Setelah Diskon
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" required 
                                class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-700 focus:border-purple-700" step="0.01" min="0"/>
                        </div>
                        @error('price')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Harga Sebelum Diskon -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="price_before" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-money-bill text-purple-700 mr-2"></i>
                            Harga Sebelum Diskon
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                            <input type="number" name="price_before" id="price_before" value="{{ old('price_before') }}" required 
                                class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-700 focus:border-purple-700" step="0.01" min="0"/>
                        </div>
                        @error('price_before')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Link YouTube -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="youtube_link" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fab fa-youtube text-red-600 mr-2"></i>
                            Link Video YouTube
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">
                                <i class="fas fa-link"></i>
                            </span>
                            <input type="url" name="youtube_link" id="youtube_link" 
                                value="{{ old('youtube_link', $bundling->youtube_link ?? '') }}"
                                placeholder="https://www.youtube.com/watch?v=xxxx"
                                class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-700 focus:border-purple-700"/>
                        </div>
                        @error('youtube_link')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
            
            <!-- Upload Gambar Bundling -->
            <div class="mt-6 bg-gray-50 p-4 rounded-xl">
                <label for="barang_bundling" class="block text-gray-700 font-medium mb-2 flex items-center">
                    <i class="fas fa-image text-purple-700 mr-2"></i>
                    Upload Gambar Bundling
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-700 transition-colors cursor-pointer bg-white">
                    <input type="file" name="barang_bundling" id="barang_bundling" accept="image/*" required 
                        class="hidden" onchange="updateFileLabel(this)"/>
                    <label for="barang_bundling" class="cursor-pointer block">
                        <div class="mx-auto w-16 h-16 bg-purple-700/10 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-cloud-upload-alt text-2xl text-purple-700"></i>
                        </div>
                        <p class="text-gray-700 font-medium" id="fileLabel">Klik untuk upload gambar bundling</p>
                        <p class="text-gray-500 text-sm mt-1">Format: JPG, PNG (Maks. 2MB)</p>
                    </label>
                </div>
                @error('barang_bundling')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Syarat & Ketentuan -->
            <div class="mt-6 bg-gray-50 p-4 rounded-xl">
                <label for="snk" class="block text-gray-700 font-medium mb-2 flex items-center">
                    <i class="fas fa-file-contract text-purple-700 mr-2"></i>
                    Syarat & Ketentuan
                </label>
                <div id="snk-editor">
                    <textarea id="snk" name="snk" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('snk') }}</textarea>
                </div>
                @error('snk')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <!-- Produk dalam Promo -->
            <div class="mt-6 bg-gray-50 p-4 rounded-xl">
                <label for="products" class="block text-gray-700 font-medium mb-2 flex items-center">
                    <i class="fas fa-tags text-purple-700 mr-2"></i>
                    Pilih Produk untuk Promo Ini
                </label>
                <select name="products[]" id="products" class="w-full select2" multiple required>
                    @foreach ($products as $product)
                        <option value="{{ $product->id_product }}" {{ in_array($product->id_product, old('products', [])) ? 'selected' : '' }}>
                            {{ $product->nama_produk }}
                        </option>
                    @endforeach
                </select>
                 <small class="text-gray-500 block mt-2">
                    Tekan <strong>Ctrl</strong> (Windows) atau <strong>Cmd</strong> (Mac) untuk memilih lebih dari satu produk.
                </small>
                @error('products')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center mt-8">
                <button type="submit" class="bg-purple-700 hover:bg-[#135e24] text-white font-medium py-3 px-10 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-purple-700 focus:ring-offset-2 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Bundling
                </button>
            </div>
        </form>
    </div>    
</div>

<script>
    function updateFileLabel(input) {
        const fileLabel = document.getElementById('fileLabel');
        if (input.files.length > 0) {
            fileLabel.textContent = input.files[0].name;
        } else {
            fileLabel.textContent = 'Klik untuk upload gambar bundling';
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        ClassicEditor
            .create(document.querySelector('#snk'), {
                toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'undo', 'redo'],
            })
            .catch(error => {
                console.error('Error initializing CKEditor:', error);
            });
    });
</script>
@endsection