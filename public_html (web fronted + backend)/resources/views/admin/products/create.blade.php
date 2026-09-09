@extends('admin.layouts.master')

@section('title', 'WOWINFood - Tambah Produk')

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

    /* Image preview styles */
    .image-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }

    .image-preview-item {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #e5e7eb;
    }

    .image-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #ef4444;
        font-size: 12px;
        transition: all 0.2s;
    }

    .remove-image:hover {
        background: rgba(255, 255, 255, 1);
        transform: scale(1.1);
    }
</style>
@endsection

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
                    Tambah Produk
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-[#16782d] p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-plus-circle text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Tambah Produk</h1>
                <p class="text-gray-600">Tambahkan produk baru untuk ditampilkan di website publik.</p>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex gap-3 mt-4">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-[#16782d] border border-[#16782d] px-4 py-2 rounded-lg hover:bg-[#16782d]/10 transition flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-[#16782d] border border-[#16782d] px-4 py-2 rounded-lg hover:bg-[#16782d]/10 transition flex items-center">
                <i class="fas fa-box mr-2"></i> Data Produk
            </a>
            <span class="text-sm font-medium text-white bg-[#16782d] px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Produk
            </span>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex items-center justify-center mb-6">
            <div class="bg-[#16782d]/10 p-4 rounded-full">
                <i class="fas fa-box-open text-2xl text-[#16782d]"></i>
            </div>
        </div>
        <h1 class="text-center font-semibold text-xl text-gray-800">INPUT DATA PRODUK</h1>
        <p class="text-center text-gray-500 mb-8">Silahkan mengisi inputan dibawah ini untuk menambahkan produk</p>
        
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="mx-auto max-w-4xl" id="productForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <!-- Nama Produk -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="nama_produk" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-tag text-[#16782d] mr-2"></i>
                            Nama Produk
                        </label>
                        <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk') }}" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]"/>
                        @error('nama_produk')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- No BPOM -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="no_bpom" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-certificate text-[#16782d] mr-2"></i>
                            Nomor BPOM
                        </label>
                        <input type="text" name="no_bpom" id="no_bpom" value="{{ old('no_bpom') }}" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]"/>
                        @error('no_bpom')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- No Halal -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="no_halal" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-check-circle text-[#16782d] mr-2"></i>
                            Nomor Halal
                        </label>
                        <input type="text" name="no_halal" id="no_halal" value="{{ old('no_halal') }}" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]"/>
                        @error('no_halal')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                      <!-- Kategori Produk -->
                    <div class="bg-gray-50 p-4 rounded-xl mt-4">
                        <label class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-tags text-[#16782d] mr-2"></i>
                            Nama Kategori
                        </label>
                        <div class="kategori-wrapper relative">
                            <input type="text" 
                                class="kategori-input w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-[#16782d] focus:border-[#16782d]" 
                                 value="{{ $product->category->name ?? '' }}" 
                                autocomplete="off">

                            <input type="hidden" name="category_id" class="category-id" value="{{ old('category_id', $product->category_id) }}">

                            <ul class="kategori-list absolute z-50 bg-white border border-gray-300 mt-1 w-full rounded hidden shadow-md max-h-40 overflow-auto">
                            </ul>
                        </div>
                        @error('category_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <script>
                    const searchUrl = "{{ route('admin.categories.search') }}";
                    </script>
                </div>
                
                <!-- Kolom Kanan -->
                <div class="space-y-4">
                    <!-- Harga -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="harga" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-money-bill-wave text-[#16782d] mr-2"></i>
                            Harga
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                            <input type="number" name="harga" id="harga" value="{{ old('harga') }}" required 
                                class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]" step="0.01" min="0"/>
                        </div>
                        @error('harga')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Isi Permili -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="isi_ml" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-flask text-[#16782d] mr-2"></i>
                            Isi Permili
                        </label>
                        <div class="relative">
                            <input type="number" name="isi_ml" id="isi_ml" value="{{ old('isi_ml') }}" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]"/>
                            <span class="absolute right-3 top-2.5 text-gray-500">ml</span>
                        </div>
                        @error('isi_ml')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Berat Kotor Fisik (Gram) -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="berat" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-weight-hanging text-[#16782d] mr-2"></i>
                            Berat Kotor Fisik
                        </label>
                        <div class="relative">
                            <input type="number" step="any" name="berat" id="berat" value="{{ old('berat') }}" placeholder="Contoh: 500" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]"/>
                            <span class="absolute right-3 top-2.5 text-gray-500">gr</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Berat kotor fisik termasuk botol/kemasan untuk acuan ongkir J&T.</p>
                        @error('berat')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Isi Karton -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="isi_karton" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-box text-[#16782d] mr-2"></i>
                            Isi tiap karton
                        </label>
                        <input type="number" name="isi_karton" id="isi_karton" value="{{ old('isi_karton') }}" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]"/>
                        @error('isi_karton')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Rekom Guna -->
            <div class="mt-6 bg-gray-50 p-4 rounded-xl">
                <label for="rekom_guna" class="block text-gray-700 font-medium mb-2 flex items-center">
                    <i class="fas fa-info-circle text-[#16782d] mr-2"></i>
                    Rekomendasi Penggunaan Produk
                </label>
                <textarea id="rekom_guna" name="rekom_guna" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('rekom_guna') }}</textarea>
                @error('rekom_guna')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Upload Gambar -->
            <div class="mt-6 bg-gray-50 p-4 rounded-xl">
                <label class="block text-gray-700 font-medium mb-2 flex items-center">
                    <i class="fas fa-image text-[#16782d] mr-2"></i>
                    Upload Gambar Produk
                </label>
                
                <!-- Hidden input that will store all selected files -->
                <input type="file" name="images[]" id="imagesInput" multiple accept="image/*" class="hidden" required/>
                
                <!-- Individual image upload button -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-[#16782d] transition-colors cursor-pointer bg-white mb-3">
                    <input type="file" id="singleImageInput" accept="image/*" class="hidden" onchange="handleSingleImageSelect(this)"/>
                    <label for="singleImageInput" class="cursor-pointer block">
                        <div class="mx-auto w-16 h-16 bg-[#16782d]/10 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-image text-2xl text-[#16782d]"></i>
                        </div>
                        <p class="text-gray-700 font-medium">Klik untuk pilih gambar</p>
                        <p class="text-gray-500 text-sm mt-1">Format: JPG, PNG, WebP</p>
                    </label>
                </div>
                
                <!-- Tambah Gambar Button -->
                <button type="button" id="addMoreImages" class="bg-[#16782d]/10 hover:bg-[#16782d]/20 text-[#16782d] font-medium py-2 px-4 rounded-lg transition-colors focus:outline-none flex items-center justify-center w-full mb-3">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Tambah Gambar Lagi
                </button>
                
                <!-- Image preview container -->
                <div id="imagePreviewContainer" class="image-preview-container"></div>
                
                <!-- Selected files count -->
                <p id="selectedFileCount" class="text-gray-700 text-center mt-3 hidden">
                    <span id="fileCount">0</span> gambar dipilih
                </p>
                
                @error('images')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
        
            <!-- Submit Button -->
            <div class="flex justify-center mt-8">
                <button type="submit" class="bg-[#16782d] hover:bg-[#135e24] text-white font-medium py-3 px-10 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:ring-offset-2 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>    
</div>

<script>
    // Initialize arrays to keep track of selected files
    let selectedFiles = [];
    let imageCounter = 0;
    
    // Handle single image selection
    function handleSingleImageSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            addImageToPreview(file);
            updateFileCount();
            
            // Reset the input so the same file can be selected again
            input.value = '';
        }
    }
    
    // Add image to preview container
    function addImageToPreview(file) {
        const previewContainer = document.getElementById('imagePreviewContainer');
        const imageId = 'img-' + imageCounter++;
        
        // Create preview item
        const previewItem = document.createElement('div');
        previewItem.className = 'image-preview-item animate__animated animate__fadeIn';
        previewItem.id = imageId;
        
        // Create image element
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        
        // Create remove button
        const removeBtn = document.createElement('div');
        removeBtn.className = 'remove-image';
        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
        removeBtn.onclick = function() {
            removeImage(imageId, file);
        };
        
        // Append elements
        previewItem.appendChild(img);
        previewItem.appendChild(removeBtn);
        previewContainer.appendChild(previewItem);
        
        // Add file to selected files array
        selectedFiles.push({
            id: imageId,
            file: file
        });
        
        // Update the hidden input with all files
        updateHiddenInput();
        
        // Show selected files count
        document.getElementById('selectedFileCount').classList.remove('hidden');
    }
    
    // Remove image from preview
    function removeImage(imageId, file) {
        // Remove preview item with animation
        const previewItem = document.getElementById(imageId);
        previewItem.classList.remove('animate__fadeIn');
        previewItem.classList.add('animate__fadeOut');
        
        setTimeout(() => {
            previewItem.remove();
            
            // Remove file from selected files array
            selectedFiles = selectedFiles.filter(item => item.id !== imageId);
            
            // Update hidden input and file count
            updateHiddenInput();
            updateFileCount();
            
            // Hide count if no files selected
            if (selectedFiles.length === 0) {
                document.getElementById('selectedFileCount').classList.add('hidden');
            }
        }, 300);
    }
    
    // Update hidden input with all selected files
    function updateHiddenInput() {
        const dataTransfer = new DataTransfer();
        
        selectedFiles.forEach(item => {
            dataTransfer.items.add(item.file);
        });
        
        document.getElementById('imagesInput').files = dataTransfer.files;
    }
    
    // Update file count display
    function updateFileCount() {
        document.getElementById('fileCount').textContent = selectedFiles.length;
    }
    
    // Add more images button click handler
    document.getElementById('addMoreImages').addEventListener('click', function() {
        document.getElementById('singleImageInput').click();
    });
    
    // Form submission handler
    document.getElementById('productForm').addEventListener('submit', function(e) {
        if (selectedFiles.length === 0) {
            e.preventDefault();
            alert('Silahkan pilih minimal satu gambar produk.');
        }
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        ClassicEditor
            .create(document.querySelector('#rekom_guna'), {
                toolbar: ['bold', 'bulletedList', 'undo', 'redo'],
            })
            .catch(error => {
                console.error('Error initializing CKEditor:', error);
            });
    });
</script>
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