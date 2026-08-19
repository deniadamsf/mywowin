@extends('superadmin.layouts.master')

@section('title', 'WOWINFood')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <!-- Breadcrumb -->
        <nav class="flex text-sm mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L2 8v10h5v-6h6v6h5V8l-8-6z" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7"></path>
                    </svg>
                    <a href="{{ route('admin.rewards.index') }}" class="text-gray-700 hover:text-blue-600">Master Reward</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-500">Tambah Reward</span>
                </li>
            </ol>
        </nav>

        <!-- Title Section -->
        <h1 class="text-2xl font-bold text-gray-800">Tambah Reward</h1>
        <p class="text-gray-600 mb-4">Halaman ini merupakan halaman untuk menambahkan reward baru.</p>

        <!-- Tab Switcher -->
        <div class="flex gap-4 mt-4">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-blue-600 border border-blue-300 px-3 py-1 rounded hover:bg-blue-50">
                Dashboard
            </a>
            <span class="text-gray-400">|</span>
            <a href="{{ route('admin.rewards.index') }}" class="text-sm font-medium text-purple-600 border border-purple-300 px-3 py-1 rounded hover:bg-purple-50">
                Master Reward
            </a>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-center mb-4">Tambah Reward Baru</h2>

        <form action="{{ route('admin.rewards.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="nama_reward" class="block font-semibold text-gray-700 mb-2">Nama Reward:</label>
                <input type="text" name="nama_reward" id="nama_reward" value="{{ old('nama_reward') }}" 
                    class="w-full border border-gray-300 p-2 rounded" required>
                @error('nama_reward')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="relative z-30 mb-4">
                <label for="deskripsi" class="block font-semibold text-gray-700 mb-2">Deskripsi:</label>
                <textarea name="deskripsi" id="deskripsi" rows="10"
                    class="w-full border border-gray-300 p-2 rounded">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="points_required" class="block font-semibold text-gray-700 mb-2">Poin yang Dibutuhkan:</label>
                <input type="number" name="points_required" id="points_required" value="{{ old('points_required') }}" 
                    class="w-full border border-gray-300 p-2 rounded" required>
                @error('points_required')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="foto_rewards" class="block font-semibold text-gray-700 mb-2">Gambar Reward:</label>
                <input type="file" name="foto_rewards" id="foto_rewards" accept="image/*"
                    class="w-full border border-gray-300 p-2 rounded" required>
                @error('foto_rewards')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">
                    Simpan Reward
                </button>
            </div>
        </form>

        <!-- CKEditor Scripts -->
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('#deskripsi'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo']
                })
                .catch(error => {
                    console.error(error);
                });
        </script>

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

    </div>
</div>
@endsection
