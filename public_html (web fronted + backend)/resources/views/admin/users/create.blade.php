@extends('admin.layouts.master')

@section('title', 'WOWINFood - Create Member')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
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
                    <a href="{{ route('admin.user.index') }}" class="hover:text-[#16782d] transition-colors duration-200">Kelola Membership</a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Create Membership
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-[#16782d] p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-user-plus text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Create Membership Account</h1>
                <p class="text-gray-600">Halaman untuk membuat akun Membership baru di sistem</p>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex gap-3 mt-4">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-[#16782d] border border-[#16782d] px-4 py-2 rounded-lg hover:bg-[#16782d]/10 transition flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.settings.index') }}" class="text-sm font-medium text-[#16782d] border border-[#16782d] px-4 py-2 rounded-lg hover:bg-[#16782d]/10 transition flex items-center">
                <i class="fas fa-users mr-2"></i> Profile
            </a>
            <a href="{{ route('admin.users.master-member') }}" class="text-sm font-medium text-[#16782d] border border-[#16782d] px-4 py-2 rounded-lg hover:bg-[#16782d]/10 transition flex items-center">
                <i class="fas fa-user-tag mr-2"></i> Data Member
            </a>
            <span class="text-sm font-medium text-white bg-[#16782d] px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Create Membership
            </span>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex items-center justify-center mb-6">
            <div class="bg-[#16782d]/10 p-4 rounded-full">
                <i class="fas fa-user-cog text-3xl text-[#16782d]"></i>
            </div>
        </div>
        <h1 class="text-center font-semibold text-xl text-gray-800">INPUT DATA MEMBERSHIP ADMIN SIDE</h1>
        <p class="text-center text-gray-500 mb-8">Silahkan mengisi data berikut untuk membuat akun membership baru</p>
        
        <form action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data" class="mx-auto max-w-3xl">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Username -->
                <div class="bg-gray-50 p-4 rounded-xl">
                    <label for="username" class="block text-gray-700 font-medium mb-2 flex items-center">
                        <i class="fas fa-user text-[#16782d] mr-2"></i>
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]" 
                        placeholder="Masukkan username">
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Lengkap -->
                <div class="bg-gray-50 p-4 rounded-xl">
                    <label for="nama_lengkap" class="block text-gray-700 font-medium mb-2 flex items-center">
                        <i class="fas fa-id-card text-[#16782d] mr-2"></i>
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]"
                        placeholder="Masukkan nama lengkap">
                    @error('nama_lengkap')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="bg-gray-50 p-4 rounded-xl">
                    <label for="email" class="block text-gray-700 font-medium mb-2 flex items-center">
                        <i class="fas fa-envelope text-[#16782d] mr-2"></i>
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]"
                        placeholder="email@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="bg-gray-50 p-4 rounded-xl">
                    <label for="password" class="block text-gray-700 font-medium mb-2 flex items-center">
                        <i class="fas fa-lock text-[#16782d] mr-2"></i>
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]"
                        placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kantor Cabang -->
                <div class="bg-gray-50 p-4 rounded-xl">
                    <label for="kantor_cabang" class="block text-gray-700 font-medium mb-2 flex items-center">
                        <i class="fas fa-building text-[#16782d] mr-2"></i>
                        Kantor Cabang <span class="text-red-500">*</span>
                    </label>
                    <select name="kantor_cabang" id="kantor_cabang" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d] bg-white">
                        <option value="Trenggalek" {{ old('kantor_cabang') == 'Trenggalek' ? 'selected' : '' }}>Trenggalek</option>
                        <option value="Kediri" {{ old('kantor_cabang') == 'Kediri' ? 'selected' : '' }}>Kediri</option>
                        <option value="Madiun" {{ old('kantor_cabang') == 'Madiun' ? 'selected' : '' }}>Madiun</option>
                        <option value="Solo" {{ old('kantor_cabang') == 'Solo' ? 'selected' : '' }}>Solo</option>
                        <option value="Jogja" {{ old('kantor_cabang') == 'Jogja' ? 'selected' : '' }}>Jogja</option>
                        <option value="Cirebon" {{ old('kantor_cabang') == 'Cirebon' ? 'selected' : '' }}>Cirebon</option>
                        <option value="Kudus" {{ old('kantor_cabang') == 'Kudus' ? 'selected' : '' }}>Kudus</option>
                        <option value="Bogor" {{ old('kantor_cabang') == 'Bogor' ? 'selected' : '' }}>Bogor</option>
                        <option value="Serang" {{ old('kantor_cabang') == 'Serang' ? 'selected' : '' }}>Serang</option>
                    </select>
                    @error('kantor_cabang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

               <!-- Role -->
                <div class="bg-gray-50 p-4 rounded-xl">
                    <label for="role" class="block text-gray-700 font-medium mb-2 flex items-center">
                        <i class="fas fa-user-shield text-[#16782d] mr-2"></i>
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" id="role" onchange="toggleMembershipForm()" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d] bg-white">
                        <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Member</option>
                    </select>
                </div>
                <!-- Foto Profile -->
                <div class="bg-gray-50 p-4 rounded-xl">
                    <label for="foto_profile" class="block text-gray-700 font-medium mb-2 flex items-center">
                        <i class="fas fa-image text-[#16782d] mr-2"></i>
                        Foto Profile
                    </label>
                    <div class="flex items-center">
                        <label class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                            <i class="fas fa-cloud-upload-alt mr-2 text-[#16782d]"></i>
                            <span id="file-name">Pilih File</span>
                            <input type="file" name="foto_profile" id="foto_profile" class="hidden" onchange="updateFileName()">
                        </label>
                    </div>
                    @error('foto_profile')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Aktif -->
                <div class="bg-gray-50 p-4 rounded-xl">
                    <label class="block text-gray-700 font-medium mb-2 flex items-center">
                        <i class="fas fa-toggle-on text-[#16782d] mr-2"></i>
                        Status Aktif <span class="text-red-500">*</span>
                    </label>
                    <div class="flex space-x-4">
                        <div class="flex items-center">
                            <input type="radio" name="status_aktif" id="status_aktif_1" value="1" {{ old('status_aktif', '1') == '1' ? 'checked' : '' }} 
                                class="h-4 w-4 text-[#16782d] border-gray-300 focus:ring-[#16782d]">
                            <label for="status_aktif_1" class="ml-2 block text-sm text-gray-700">Aktif</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" name="status_aktif" id="status_aktif_0" value="0" {{ old('status_aktif') == '0' ? 'checked' : '' }}
                                class="h-4 w-4 text-[#16782d] border-gray-300 focus:ring-[#16782d]">
                            <label for="status_aktif_0" class="ml-2 block text-sm text-gray-700">Nonaktif</label>
                        </div>
                    </div>
                    @error('status_aktif')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Membership Form (hanya jika role = member) -->
            <div id="membershipForm" class="{{ old('role') == 'member' ? 'block' : 'hidden' }} mt-8 border border-gray-200 rounded-lg p-6 bg-gray-50">
                <div class="flex items-center mb-4">
                    <i class="fas fa-store text-[#16782d] mr-2"></i>
                    <h3 class="text-lg font-medium text-gray-800">Data Membership</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Toko -->
                    <div>
                        <label for="nama_toko" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-store-alt text-[#16782d] mr-2"></i>
                            Nama Toko
                        </label>
                        <input type="text" name="nama_toko" id="nama_toko" value="{{ old('nama_toko') }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]"
                            placeholder="Nama toko">
                        @error('nama_toko')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-map-marker-alt text-[#16782d] mr-2"></i>
                            Alamat
                        </label>
                        <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]"
                            placeholder="Alamat lengkap">
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No HP -->
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-phone text-[#16782d] mr-2"></i>
                            No HP
                        </label>
                        <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]"
                            placeholder="08xxxxxxxxxx">
                        @error('no_hp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Sales -->
                    <div>
                        <label for="nama_sales" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-user-tie text-[#16782d] mr-2"></i>
                            Nama Sales
                        </label>
                        <input type="text" name="nama_sales" id="nama_sales" value="{{ old('nama_sales') }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]"
                            placeholder="Nama sales">
                        @error('nama_sales')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-8 flex justify-center">
                <button type="submit" class="bg-[#16782d] hover:bg-[#135e24] text-white font-medium py-3 px-10 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:ring-offset-2 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Buat Akun Membership
                </button>
            </div>
        </form>
    </div>
</div>

<script>
   function toggleMembershipForm() {
        const role = document.getElementById('role')?.value;
        const membershipForm = document.getElementById('membershipForm');
        
        if (role === 'member') {
            membershipForm.classList.remove('hidden');
            membershipForm.classList.add('block');
        } else {
            membershipForm.classList.remove('block');
            membershipForm.classList.add('hidden');
        }
    }

    // Jalankan saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', toggleMembershipForm);
    
    function updateFileName() {
        const fileInput = document.getElementById('foto_profile');
        const fileNameDisplay = document.getElementById('file-name');
        
        if (fileInput.files.length > 0) {
            fileNameDisplay.textContent = fileInput.files[0].name;
        } else {
            fileNameDisplay.textContent = 'Pilih File';
        }
    }
</script>
@endsection