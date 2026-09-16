@extends('admin.layouts.master')

@section('title', 'WOWINFood - Tambah Artikel')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    <a href="{{ route('admin.artikels.index') }}" class="hover:text-[#16782d] transition-colors duration-200">Master Artikel</a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Tambah Artikel
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-[#16782d] p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-newspaper text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Tambah Artikel</h1>
                <p class="text-gray-600">Tambahkan artikel baru untuk ditampilkan di halaman publikasi.</p>
            </div>
        </div>

        <div class="flex gap-3 mt-4">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-[#16782d] border border-[#16782d] px-4 py-2 rounded-lg hover:bg-[#16782d]/10 transition flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.artikels.index') }}" class="text-sm font-medium text-[#16782d] border border-[#16782d] px-4 py-2 rounded-lg hover:bg-[#16782d]/10 transition flex items-center">
                <i class="fas fa-book mr-2"></i> Master Artikel
            </a>
            <span class="text-sm font-medium text-white bg-[#16782d] px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Artikel
            </span>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex items-center justify-center mb-6">
            <div class="bg-[#16782d]/10 p-4 rounded-full">
                <i class="fas fa-pen-nib text-2xl text-[#16782d]"></i>
            </div>
        </div>
        <h1 class="text-center font-semibold text-xl text-gray-800">INPUT DATA ARTIKEL</h1>
        <p class="text-center text-gray-500 mb-8">Silahkan mengisi inputan dibawah ini untuk menambahkan artikel baru</p>

        <form action="{{ route('admin.artikels.store') }}" method="POST" enctype="multipart/form-data" class="mx-auto max-w-6xl">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {{-- Kolom Input Konten (8 Kolom) --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <label for="judul" class="block text-gray-700 font-medium mb-2 flex items-center">
                                <i class="fas fa-heading text-[#16782d] mr-2"></i>
                                Judul Artikel
                            </label>
                            <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]" />
                            @error('judul')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl">
                            <label for="foto_artikel" class="block text-gray-700 font-medium mb-2 flex items-center">
                                <i class="fas fa-image text-[#16782d] mr-2"></i>
                                Gambar Artikel
                            </label>
                            <input type="file" name="foto_artikel" id="foto_artikel" accept="image/*" required 
                                class="w-full border border-gray-300 rounded-lg p-2" />
                            @error('foto_artikel')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl md:col-span-2">
                            <label for="slug" class="block text-gray-700 font-medium mb-2 flex items-center">
                                <i class="fas fa-link text-[#16782d] mr-2"></i>
                                Custom Slug URL (SEO)
                            </label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" 
                                placeholder="contoh: supplier-kecap-manis-jerigen-murah (kosongkan untuk otomatis dari judul)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]" />
                            <p class="text-xs text-gray-400 mt-1 italic">*Opsional. Jika dikosongkan, URL otomatis digenerate dari judul artikel.</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="isi" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-align-left text-[#16782d] mr-2"></i>
                            Konten Artikel
                        </label>
                        <textarea name="isi" id="isi" rows="10" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('isi') }}</textarea>
                        @error('isi')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#16782d] hover:bg-[#145f24] text-white py-3 rounded-xl shadow-lg transition-all font-semibold">
                            <i class="fas fa-save mr-2"></i> Simpan Artikel
                        </button>
                    </div>
                </div>

                {{-- Kolom Widget Rank Math SEO (5 Kolom) --}}
                <div class="lg:col-span-5 space-y-6">
                    @include('components.rank-math-analyzer')
                </div>
            </div>
        </form>

        <script>
            let adminEditor;
            ClassicEditor
                .create(document.querySelector('#isi'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo']
                })
                .then(editor => {
                    adminEditor = editor;
                    editor.model.document.on('change:data', () => {
                        if (typeof rmRunAudit === 'function') rmRunAudit();
                    });
                    if (typeof rmRunAudit === 'function') rmRunAudit();
                })
                .catch(error => {
                    console.error(error);
                });

            if (window.RankMathState) {
                window.RankMathState.getContentCallback = function() {
                    return adminEditor ? adminEditor.getData() : '';
                };
                window.RankMathState.getMediaCountCallback = function() {
                    const photo = document.getElementById('foto_artikel');
                    return (photo && photo.files && photo.files.length > 0) ? 1 : 0;
                };
            }

            const photoEl = document.getElementById('foto_artikel');
            if (photoEl) {
                photoEl.addEventListener('change', function() {
                    if (typeof rmRunAudit === 'function') rmRunAudit();
                });
            }
        </script>
    </div>
</div>
@endsection
