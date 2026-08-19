@extends('superadmin.layouts.master')

@section('title', 'My Wowin - superadmin Profile')

@section('head')
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .profile-card {
            transition: all 0.3s ease;
        }
        .profile-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(22, 120, 45, 0.1);
        }
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
        }
        .avatar-upload:hover {
            background-color: #25a245;
        }
        /* Overlay untuk loading */
.loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: white;
}

/* Animasi Spinner */
.spinner {
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top: 4px solid #ffffff;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin-bottom: 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
    </style>
@endsection

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

<div class="bg-gray-50 min-h-screen pb-12">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-700 to-purple-600 shadow-md">
        <div class="container mx-auto px-4 py-6">
            <!-- Breadcrumb -->
            <nav class="flex mb-4 text-sm" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('superadmin.dashboard') }}" class="text-purple-100 hover:text-white flex items-center transition-colors">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="flex items-center text-purple-50">
                        <svg class="w-4 h-4 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        superadmin Profile
                    </li>
                </ol>
            </nav>

            <div class="mb-6">
                <h2 class="text-3xl font-extrabold text-white">Pengaturan Profil</h2>
                <p class="text-purple-100 mt-1">Silakan sesuaikan informasi akun Anda sesuai kebutuhan.</p>
            </div>
            
            <div class="flex justify-between items-center bg-purple-500/20 p-4 rounded-lg shadow-lg">
                <p class="text-white text-sm">Kelola data dan keamanan akun Anda dengan lebih mudah.</p>
                <a href="{{ route('superadmin.dashboard') }}" class="text-sm font-medium text-white hover:text-purple-100 border border-purple-200 px-4 py-2 rounded-lg hover:bg-white/10 transition flex items-center">
                    <i class="fas fa-tachometer-alt mr-2"></i> Kembali ke Dashboard
                </a>
            </div>
            
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="stat-card bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class="fas fa-calendar-check text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Bergabung Sejak</p>
                    <p class="text-lg font-bold">{{ auth()->user()->created_at->format('d M Y') }}</p>
                </div>
            </div>
            <div class="stat-card bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-4">
                    <i class="fas fa-clock text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Terakhir Login</p>
                    <p class="text-lg font-bold">
                        {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d M Y, H:i') : 'No data' }}
                    </p>                    
                </div>  
            </div>
            <div class="stat-card bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-4">
                    <i class="fas fa-user-shield text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Role</p>
                    <p class="text-lg font-bold">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
            </div>
        </div>

 <!-- Main Content -->
        <div class="profile-card bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Profile Header -->
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <div class="relative">
                        <img src="{{ auth()->user()->foto_profile ? asset('storage/'.auth()->user()->foto_profile) : asset('images/default-avatar.png') }}" 
                            alt="Profile" 
                            id="profile-preview"
                            class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md">
                        
                        <label for="avatar-upload" class="absolute bottom-0 right-0 bg-purple-600 rounded-full p-2 border-2 border-white cursor-pointer shadow avatar-upload transition-colors duration-300">
                            <i class="fas fa-camera text-white text-sm"></i>
                        </label>
                    </div>
                    <div class="text-center sm:text-left">
                        <h2 class="text-xl font-bold text-gray-800">{{ auth()->user()->nama_lengkap }}</h2>
                        <div class="flex items-center justify-center sm:justify-start mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-check-circle mr-1"></i>{{ ucfirst(auth()->user()->role) }}
                            </span>
                        </div>
                        <p class="text-gray-500 text-sm mt-2">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <form action="{{ route('superadmin.settings.update', auth()->user()->id) }}" method="POST" enctype="multipart/form-data" id="profile-form">
                @csrf
                @method('PUT')
                
                <input type="file" id="avatar-upload" name="foto_profile" class="hidden" accept="image/*">
                
                <div class="p-6">
                    <!-- Personal Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-user-edit text-purple-600 mr-2"></i>
                            Informasi Pribadi
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" id="nama_lengkap" name="nama_lengkap" 
                                        value="{{ old('nama_lengkap', auth()->user()->nama_lengkap) }}" required
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                </div>
                                @error('nama_lengkap')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Alamat Email <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" id="email" name="email" 
                                        value="{{ old('email', auth()->user()->email) }}" required
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                </div>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                                    Username <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-at text-gray-400"></i>
                                    </div>
                                    <input type="text" id="username" name="username" 
                                        value="{{ old('username', auth()->user()->username) }}" required
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                </div>
                                @error('username')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-lock text-blue-600 mr-2"></i>
                            Ganti Password
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Password Baru
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" id="password" name="password"
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika Anda tidak ingin mengganti password</p>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            {{-- <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                    Confirm Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                                @error('password_confirmation')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div> --}}
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" id="form-submit" 
                            class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-300">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="loading-screen" class="loading-overlay">
    <div class="spinner"></div>
    <p class="font-medium">Sedang mengunggah, tunggu sesaat lagi...</p>
</div>
<div id="loading-screen" class="loading-overlay">
    <div class="spinner"></div>
    <p class="font-medium">Sedang memproses perubahan...</p>
</div>
</div>

<script>
    // 1. Inisialisasi Toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };

    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    @endif

    const fileInput = document.getElementById('avatar-upload');
    const profileForm = document.getElementById('profile-form');
    const loadingScreen = document.getElementById('loading-screen');

    // 2. Logika saat PILIH FOTO (Hanya Preview)
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            
            // Validasi ukuran (2MB)
            if (file.size > 2 * 1024 * 1024) {
                toastr.error('Ukuran file terlalu besar (Maks 2MB)');
                this.value = '';
                return;
            }

            // PREVIEW SAJA
            const reader = new FileReader();
            reader.onload = function(e) {
                // Update foto besar dan foto header sekaligus
                document.querySelectorAll('img[alt*="Profile"], #profile-preview').forEach(img => {
                    img.src = e.target.result;
                });
            }
            reader.readAsDataURL(file);

            toastr.info('Foto dipilih. Klik "Simpan Perubahan" untuk menerapkan.');
        }
    });

    // 3. Logika saat KLIK TOMBOL SIMPAN (Munculkan Spinner)
    profileForm.addEventListener('submit', function() {
        loadingScreen.style.display = 'flex';
    });
</script>
@endsection