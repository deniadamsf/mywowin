@extends('superadmin.layouts.master')

@section('title', 'WOWINFood - Tambah Artikel')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body { font-family: 'Poppins', sans-serif; }
    .ck-editor__editable_inline { min-height: 250px; border-radius: 0 0 1rem 1rem !important; }
    .block-item { transition: all 0.3s ease; }
    .block-item:hover { border-color: #a855f7; }
    .img-preview { max-height: 300px; width: 100%; object-fit: cover; border-radius: 0.75rem; }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="bg-purple-700 p-3 rounded-xl shadow-lg shadow-purple-200">
                    <i class="fas fa-pen-nib text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Tambah Artikel Baru</h1>
                    <p class="text-sm text-gray-500">Susun teks dan gambar secara bergantian dengan mudah.</p>
                </div>
            </div>
            <a href="{{ route('superadmin.artikels.index') }}" class="flex items-center text-sm font-semibold text-purple-700 hover:text-purple-800 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke List
            </a>
        </div>

        <form action="{{ route('superadmin.artikels.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            <i class="fas fa-heading mr-2 text-purple-600"></i> Judul Utama
                        </label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition" 
                            placeholder="Ketik judul artikel...">
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-link mr-2 text-purple-600"></i> Custom Slug URL (SEO)
                        </label>
                        <input type="text" name="slug" value="{{ old('slug') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition text-sm" 
                            placeholder="contoh: supplier-kecap-manis-jerigen-murah">
                        <p class="text-[11px] text-gray-400 mt-2 italic">*Opsional. Kosongkan jika ingin otomatis sesuai judul artikel.</p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            <i class="fas fa-image mr-2 text-purple-600"></i> Gambar Sampul (Utama)
                        </label>
                        <input type="file" name="foto_utama" accept="image/*" required class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                        <p class="text-[10px] text-gray-400 mt-2 italic">*Ini akan muncul sebagai header artikel.</p>
                    </div>

                    {{-- Widget Analisis Skor SEO Rank Math --}}
                    @include('components.rank-math-analyzer')
                </div>

                <div class="lg:col-span-8 space-y-6">
                    
                    <div id="content-blocks" class="space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden block-item animate__animated animate__fadeIn">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <label class="text-sm font-bold text-gray-700 flex items-center">
                                    <span class="bg-purple-600 text-white w-5 h-5 rounded-full flex items-center justify-center mr-2 text-[10px]">1</span>
                                    Paragraf Pembuka (Teks 1)
                                </label>
                            </div>
                            <div class="p-0">
                                <textarea name="konten_isi[]" class="editor-instance"></textarea>
                                <input type="hidden" name="tipe_blok[]" value="teks">
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 p-6 border-2 border-dashed border-purple-100 rounded-2xl bg-purple-50/30">
                        <span class="text-xs font-bold text-purple-400 uppercase tracking-wider">Tambah Elemen:</span>
                        <button type="button" onclick="addBlock('teks')" class="flex items-center px-5 py-2.5 bg-white border border-purple-200 text-purple-700 rounded-xl hover:shadow-md transition font-semibold text-sm">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Blok Teks
                        </button>
                        <button type="button" onclick="addBlock('gambar')" class="flex items-center px-5 py-2.5 bg-white border border-blue-200 text-blue-700 rounded-xl hover:shadow-md transition font-semibold text-sm">
                            <i class="fas fa-image mr-2"></i> Sisipkan Gambar
                        </button>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4">
                        <button type="button" id="btn-reset" class="px-6 py-3 text-sm font-semibold text-gray-500 hover:bg-gray-100 rounded-xl transition">
                            Reset Draft
                        </button>
                        <button type="submit" class="px-10 py-3 bg-purple-700 hover:bg-purple-800 text-white font-bold rounded-xl shadow-lg shadow-purple-200 transition-all transform hover:-translate-y-1">
                            <i class="fas fa-paper-plane mr-2"></i> Terbitkan Artikel
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let blockCount = 1;
    let activeEditors = [];

    // Sambungkan callback Rank Math State
    if (window.RankMathState) {
        window.RankMathState.getContentCallback = function() {
            return activeEditors.map(ed => {
                try { return ed.getData(); } catch(e) { return ''; }
            }).join('\n');
        };
        window.RankMathState.getMediaCountCallback = function() {
            let count = 0;
            const mainPhoto = document.querySelector('input[name="foto_utama"]');
            if (mainPhoto && mainPhoto.files && mainPhoto.files.length > 0) count++;
            document.querySelectorAll('input[name="konten_gambar[]"]').forEach(inp => {
                if (inp.files && inp.files.length > 0) count++;
            });
            return count;
        };
    }

    // Fungsi Inisialisasi CKEditor
    function initEditor(element) {
        ClassicEditor.create(element, {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo'],
            placeholder: 'Tuliskan narasi Anda di sini...'
        }).then(editor => {
            activeEditors.push(editor);
            editor.model.document.on('change:data', () => {
                if (typeof rmRunAudit === 'function') rmRunAudit();
            });
            if (typeof rmRunAudit === 'function') rmRunAudit();
        }).catch(error => { console.error(error); });
    }

    // Inisialisasi blok teks pertama
    document.querySelectorAll('.editor-instance').forEach(el => initEditor(el));

    // Listener foto utama
    const mainPhotoInput = document.querySelector('input[name="foto_utama"]');
    if (mainPhotoInput) {
        mainPhotoInput.addEventListener('change', function() {
            if (typeof rmRunAudit === 'function') rmRunAudit();
        });
    }

    // Fungsi Menambah Blok Baru (Teks atau Gambar)
    function addBlock(type) {
        blockCount++;
        const container = document.getElementById('content-blocks');
        let blockHtml = '';

        if (type === 'teks') {
            blockHtml = `
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden block-item animate__animated animate__fadeInUp">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <label class="text-sm font-bold text-gray-700 flex items-center">
                        <span class="bg-purple-600 text-white w-5 h-5 rounded-full flex items-center justify-center mr-2 text-[10px]">${blockCount}</span>
                        Teks Selanjutnya
                    </label>
                    <button type="button" onclick="this.closest('.block-item').remove(); if (typeof rmRunAudit === 'function') rmRunAudit();" class="text-red-400 hover:text-red-600 transition">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                <div class="p-0">
                    <textarea name="konten_isi[]" class="editor-instance"></textarea>
                    <input type="hidden" name="tipe_blok[]" value="teks">
                </div>
            </div>`;
        } else {
            blockHtml = `
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden block-item animate__animated animate__fadeInUp">
                <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex items-center justify-between">
                    <label class="text-sm font-bold text-blue-700 flex items-center">
                        <span class="bg-blue-600 text-white w-5 h-5 rounded-full flex items-center justify-center mr-2 text-[10px]">${blockCount}</span>
                        Gambar Sisipan
                    </label>
                    <button type="button" onclick="this.closest('.block-item').remove(); if (typeof rmRunAudit === 'function') rmRunAudit();" class="text-red-400 hover:text-red-600 transition">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                <div class="p-6">
                    <input type="file" name="konten_gambar[]" class="hidden" id="file-${blockCount}" accept="image/*" onchange="previewImage(this)">
                    <label for="file-${blockCount}" class="cursor-pointer block">
                        <div class="preview-area border-2 border-dashed border-blue-200 rounded-2xl p-8 text-center hover:bg-blue-50 transition">
                            <i class="fas fa-cloud-upload-alt text-3xl text-blue-300 mb-2"></i>
                            <p class="text-sm text-blue-500 font-medium">Klik untuk upload gambar</p>
                        </div>
                        <img class="img-preview hidden shadow-inner">
                    </label>
                    <input type="hidden" name="tipe_blok[]" value="gambar">
                    <input type="hidden" name="konten_isi[]" value="[IMAGE_PLACEHOLDER]">
                </div>
            </div>`;
        }

        container.insertAdjacentHTML('beforeend', blockHtml);
        
        if (type === 'teks') {
            const allEditors = container.querySelectorAll('.editor-instance');
            initEditor(allEditors[allEditors.length - 1]);
        }
    }

    // Fungsi Preview Gambar
    function previewImage(input) {
        const block = input.closest('.block-item');
        const preview = block.querySelector('.img-preview');
        const area = block.querySelector('.preview-area');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                area.classList.add('hidden');
                if (typeof rmRunAudit === 'function') rmRunAudit();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Reset Form
    document.getElementById('btn-reset').addEventListener('click', function() {
        if (confirm('Hapus semua draf?')) {
            window.location.reload();
        }
    });
</script>
@endsection