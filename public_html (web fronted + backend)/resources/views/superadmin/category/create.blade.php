@extends('superadmin.layouts.master')

@section('title', 'WOWINFood - Tambah Kategori Produk')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gradient-to-r from-white to-purple-50 p-6 rounded-2xl shadow-lg border border-gray-200">

        <!-- Breadcrumb -->
        <nav class="flex text-sm mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('superadmin.dashboard') }}" class="text-gray-700 hover:text-purple-700 flex items-center">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                </li>
                <li class="flex items-center text-gray-500">
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" /></svg>
                    <a href="{{ route('superadmin.categories.index') }}" class="hover:text-purple-700">Kelola Kategori</a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" /></svg>
                    Tambah Kategori Produk
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex items-center mb-6">
            <div class="bg-purple-700 p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-plus-circle text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Tambah Kategori Produk</h1>
                <p class="text-gray-600">Masukkan informasi kategori produk baru untuk ditampilkan di website.</p>
            </div>
        </div>

        <!-- Navigation Tab -->
        <div class="flex gap-3 mt-4">
            <a href="{{ route('superadmin.dashboard') }}" class="text-sm font-medium text-purple-700 border border-purple-700 px-4 py-2 rounded-lg hover:bg-purple-700/10 flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('superadmin.categories.index') }}" class="text-sm font-medium text-purple-700 border border-purple-700 px-4 py-2 rounded-lg hover:bg-purple-700/10 flex items-center">
                <i class="fas fa-box mr-2"></i> Data Kategori
            </a>
            <span class="text-sm font-medium text-white bg-purple-700 px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Kategori
            </span>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">

        <!-- Form Judul -->
        <div class="text-center mb-8">
            <div class="bg-purple-700/10 inline-block p-4 rounded-full mb-4">
                <i class="fas fa-list text-2xl text-purple-700"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Form Tambah Kategori</h2>
            <p class="text-gray-500">Lengkapi form berikut untuk menambahkan kategori produk baru.</p>
        </div>

        <!-- Form Input -->
        <form action="{{ route('superadmin.categories.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl mx-auto">
            @csrf
            <div class="space-y-5">
                <div>
                    <label for="name" class="block text-gray-700 font-medium mb-2">Nama Kategori Produk</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-700 focus:border-purple-700" />
                    @error('name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <br>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-700 transition-colors cursor-pointer bg-white">
                    <input type="file" name="foto_kategori" id="foto_kategori" accept="image/*" required 
                        class="hidden" onchange="updateFileLabel(this)"/>
                    <label for="foto_kategori" class="cursor-pointer block">
                        <div class="mx-auto w-16 h-16 bg-purple-700/10 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-cloud-upload-alt text-2xl text-purple-700"></i>
                        </div>
                        <p class="text-gray-700 font-medium" id="fileLabel">Klik untuk upload gambar Kategori</p>
                        <p class="text-gray-500 text-sm mt-1">Format: JPG, PNG (Maks. 2MB)</p>
                    </label>
                </div>

            <!-- Submit -->
            <div class="mt-8 flex justify-center">
                <button type="submit" class="bg-purple-700 hover:bg-[#135e24] text-white font-semibold px-8 py-3 rounded-lg shadow-sm transition duration-200">
                    <i class="fas fa-save mr-2"></i> Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
