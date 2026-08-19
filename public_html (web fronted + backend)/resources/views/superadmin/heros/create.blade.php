@extends('superadmin.layouts.master')

@section('title', 'WOWINFood - Tambah Gambar Hero')

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
                    <a href="{{ route('superadmin.dashboard') }}" class="text-gray-700 hover:text-purple-700 flex items-center transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                </li>
                <li class="flex items-center text-gray-500">
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('superadmin.heros.index') }}" class="hover:text-purple-700 transition-colors duration-200">Tampilan Hero</a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Tambah Gambar Hero
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex items-center mb-6">
            <div class="bg-purple-700 p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-image text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Tambah Gambar Hero</h1>
                <p class="text-gray-600">Upload gambar hero yang akan tampil di halaman landing page.</p>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex gap-3 mt-4">
            <a href="{{ route('superadmin.dashboard') }}" class="text-sm font-medium text-purple-700 border border-purple-700 px-4 py-2 rounded-lg hover:bg-purple-700/10 transition flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('superadmin.heros.index') }}" class="text-sm font-medium text-purple-700 border border-purple-700 px-4 py-2 rounded-lg hover:bg-purple-700/10 transition flex items-center">
                <i class="fas fa-images mr-2"></i> Tampilan Hero
            </a>
            <span class="text-sm font-medium text-white bg-purple-700 px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Gambar Hero
            </span>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex items-center justify-center mb-6">
            <div class="bg-purple-700/10 p-4 rounded-full">
                <i class="fas fa-upload text-2xl text-purple-700"></i>
            </div>
        </div>
        <h1 class="text-center font-semibold text-xl text-gray-800">Form Tambah Gambar Hero</h1>
        <p class="text-center text-gray-500 mb-8">Silakan unggah gambar hero yang akan tampil di halaman landing page.</p>

        <!-- Form -->
        <form action="{{ route('superadmin.heros.store') }}" method="POST" enctype="multipart/form-data" class="max-w-xl mx-auto">
            @csrf

            <!-- Nama Event -->
            <div class="mb-5">
                <label for="nama_event" class="block font-medium text-gray-700 mb-2">Nama Event</label>
                <input type="text" id="nama_event" name="nama_event" value="{{ old('nama_event') }}"
                    class="w-full border border-gray-300 p-3 rounded-lg focus:ring focus:ring-purple-100" required>
                @error('nama_event')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gambar -->
            <div class="mb-5">
                <label for="gambar_hero" class="block font-medium text-gray-700 mb-2">Gambar Hero</label>
                <input type="file" id="gambar_hero" name="gambar_hero" accept="image/*"
                    class="w-full border border-gray-300 p-3 rounded-lg" required>
                @error('gambar_hero')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="text-center">
                <button type="submit"
                    class="bg-purple-700 text-white px-6 py-2 rounded-lg hover:bg-[#125c22] transition">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
