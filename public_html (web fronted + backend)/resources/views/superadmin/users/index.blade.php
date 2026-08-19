@extends('superadmin.layouts.master')

@section('title', 'WOWINFood')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-lg shadow-lg border border-purple-100">
        <!-- Breadcrumb -->
        <nav class="flex text-sm mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('superadmin.dashboard') }}" class="text-gray-600 hover:text-purple-600 flex items-center transition duration-200">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L2 8v10h5v-6h6v6h5V8l-8-6z" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7"></path>
                    </svg>
                    <a href="{{ route('superadmin.users.index') }}" class="text-gray-600 hover:text-purple-600 transition duration-200">Kelola User</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-purple-600 font-medium">Data Admin</span>
                </li>
            </ol>
        </nav>

        <!-- Title Section -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <svg class="w-7 h-7 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Master Data Admin
            </h1>
            <p class="text-gray-600 mt-1 pl-10">Ini merupakan halaman untuk mengetahui master data dari user Super Admin.</p>
        </div>

        <!-- Tab Switcher -->
        <div class="flex gap-3 mt-8 mb-4 border-b border-gray-200 pb-2">
            <a href="{{ route('superadmin.users.index') }}" class="text-sm font-medium text-white bg-purple-600 px-5 py-2.5 rounded-lg shadow-sm hover:bg-purple-700 transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Data Admin
            </a>
            <a href="{{ route('superadmin.users.master-member') }}" class="text-sm font-medium text-purple-600 border border-purple-300 px-5 py-2.5 rounded-lg hover:bg-purple-50 transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Data Member
            </a>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-lg shadow-lg border border-purple-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <svg class="w-6 h-6 mr-2.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Master Data Admin
            </h2>
            
            <a href="{{ route('superadmin.users.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold py-2.5 px-5 rounded-lg shadow-md transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Admin
            </a>
        </div>
        
        <!-- Search Bar -->
        <div class="mb-5">
            <form action="{{ route('superadmin.users.index') }}" method="GET" class="flex items-center">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        id="search" 
                        placeholder="Cari nama admin..." 
                        value="{{ request('search') }}" 
                        class="w-full py-3 pl-10 pr-4 text-sm text-gray-700 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-200 focus:border-purple-500"
                    >
                    @if(request('search'))
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <a href="{{ route('superadmin.users.index') }}" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
                <button type="submit" class="ml-3 px-5 py-3 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-md transition duration-150 flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cari
                </button>
            </form>
        </div>

        <!-- Table Responsive Wrapper -->
        <div class="overflow-x-auto bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
            <table class="w-full border-collapse table-auto text-sm">
                <thead class="bg-purple-700 text-white">
                    <tr>
                        <th class="px-6 py-4 border-b border-purple-100 text-center font-semibold uppercase tracking-wider">Foto Profil</th>
                        <th class="px-6 py-4 border-b border-purple-100 text-left font-semibold uppercase tracking-wider">Nama Lengkap</th>
                        <th class="px-6 py-4 border-b border-purple-100 text-left font-semibold uppercase tracking-wider">Kantor Cabang</th>
                        <th class="px-6 py-4 border-b border-purple-100 text-left font-semibold uppercase tracking-wider">Username</th>
                        <th class="px-6 py-4 border-b border-purple-100 text-center font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 divide-y divide-purple-50">
                    @forelse ($users as $user)
                        <tr class="hover:bg-purple-50 transition duration-150">
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center">
                                    <img src="{{ asset('storage/'.$user->foto_profile) }}" class="w-12 h-12 object-cover rounded-full border-2 border-purple-200 shadow-sm">
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium">{{ $user->nama_lengkap }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1.5 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                    {{ $user->kantor_cabang }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $user->username }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center space-x-2">
                                   <!-- Lihat Detail -->
                                    <!-- Lihat Detail -->
                                    <div x-data="{ open: false }" class="relative">
                                        <!-- Detail Button -->
                                        <button x-on:click="open = true" class="px-3 py-1.5 text-white rounded-md text-xs bg-gradient-to-r from-purple-600 to-purple-700 hover:bg-purple-800 transition duration-150 shadow-md flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Detail
                                        </button>
                                        
                                        <!-- Modal Overlay -->
                                        <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <!-- Background Overlay -->
                                            <div class="flex items-center justify-center min-h-screen p-4">
                                                <div x-on:click="open = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                                
                                                <!-- Modal Content -->
                                                <div x-show="open" 
                                                    x-transition:enter="ease-out duration-300"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="ease-in duration-200"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95"
                                                    class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-4xl w-full mx-auto z-50 relative">
                                                    
                                                    <!-- Header -->
                                                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100">
                                                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                                                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                            </svg>
                                                            Detail Pengguna
                                                        </h2>
                                                        <button x-on:click="open = false" class="text-gray-500 hover:text-gray-700 focus:outline-none p-1 rounded-full hover:bg-gray-100 transition duration-150">
                                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Content -->
                                                    <div class="px-8 py-6 bg-white">
                                                        <div class="flex flex-col md:flex-row gap-8">
                                                            <!-- User Photo Column -->
                                                            <div class="md:w-1/3 flex flex-col items-center">
                                                                <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-purple-200 shadow-lg bg-purple-50 flex items-center justify-center mb-4">
                                                                    <img src="{{ asset('storage/' . $user->foto_profile) }}" alt="Foto Profil" class="w-full h-full object-cover">
                                                                </div>
                                                                
                                                                <!-- Status Badge -->
                                                                <div class="mt-4 text-center">
                                                                    <p class="text-sm text-gray-500 mb-2">Status</p>
                                                                    <span class="px-5 py-2 inline-block text-sm font-medium rounded-lg {{ $user->status_aktif == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                        {{ $user->status_aktif }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- User Information Column -->
                                                            <div class="md:w-2/3">
                                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                                    <div class="space-y-2">
                                                                        <label class="font-medium text-gray-700 text-sm block">Nama Lengkap</label>
                                                                        <div class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-700">
                                                                            {{ $user->nama_lengkap }}
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="space-y-2">
                                                                        <label class="font-medium text-gray-700 text-sm block">Email</label>
                                                                        <div class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-700">
                                                                            {{ $user->email }}
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="space-y-2">
                                                                        <label class="font-medium text-gray-700 text-sm block">Kantor Cabang</label>
                                                                        <div class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-700">
                                                                            {{ $user->kantor_cabang }}
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="space-y-2">
                                                                        <label class="font-medium text-gray-700 text-sm block">Role</label>
                                                                        <div class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-700">
                                                                            {{ $user->role }}
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="space-y-2">
                                                                        <label class="font-medium text-gray-700 text-sm block">Username</label>
                                                                        <div class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-700">
                                                                            {{ $user->username }}
                                                                        </div>
                                                                    </div>
                                                                     <div class="space-y-2">
                                                                        <label class="font-medium text-gray-700 text-sm block">Status</label>
                                                                        <div class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-700">
                                                                            {{ $user->status_aktif }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Footer -->
                                                    <div class="px-8 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                                                        <button x-on:click="open = false" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition duration-150 shadow-md">
                                                            Tutup
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
            
                                    <!-- Edit -->
                                    <div x-data="{ showEditModal: false }">
                                        <button type="button" @click="showEditModal = true"
                                            class="px-3 py-1.5 text-white rounded-md text-xs bg-amber-500 hover:bg-amber-600 transition duration-150 shadow-sm flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </button>
                                
                                        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-6">
                                                <div class="flex justify-between items-center pb-3 border-b border-gray-200 mb-5">
                                                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Edit Data Admin
                                                    </h2>
                                                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <form method="POST" action="{{ route('superadmin.users.update', $user->id) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="grid grid-cols-2 gap-5 mb-5">
                                                        <div>
                                                            <label class="block text-sm font-bold text-gray-700 text-left mb-2">Nama Lengkap</label>
                                                            <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}"
                                                                class="w-full border border-purple-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition duration-150">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-bold text-gray-700 text-left mb-2">Email</label>
                                                            <input type="email" name="email" value="{{ $user->email }}"
                                                                class="w-full border border-purple-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition duration-150">
                                                        </div>
                                                       <div class="space-y-1">
                                                                <label class="block text-sm font-bold text-gray-700">Kantor Cabang</label>
                                                                <select name="kantor_cabang" id="kantor_cabang" onchange="toggleMembershipForm()" 
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition duration-150">
                                                                    <option value="Trenggalek" {{ $user->kantor_cabang == 'Trenggalek' ? 'selected' : '' }}>Trenggalek</option>
                                                                    <option value="Kediri" {{ $user->kantor_cabang == 'Kediri' ? 'selected' : '' }}>Kediri</option>
                                                                    <option value="Madiun" {{ $user->kantor_cabang == 'Madiun' ? 'selected' : '' }}>Madiun</option>
                                                                    <option value="Solo" {{ $user->kantor_cabang == 'Solo' ? 'selected' : '' }}>Solo</option>
                                                                    <option value="Jogja" {{ $user->kantor_cabang == 'Jogja' ? 'selected' : '' }}>Jogja</option>
                                                                    <option value="Cirebon" {{ $user->kantor_cabang == 'Cirebon' ? 'selected' : '' }}>Cirebon</option>
                                                                    <option value="Kudus" {{ $user->kantor_cabang == 'Kudus' ? 'selected' : '' }}>Kudus</option>
                                                                    <option value="Bogor" {{ $user->kantor_cabang == 'Bogor' ? 'selected' : '' }}>Bogor</option>
                                                                    <option value="Serang" {{ $user->kantor_cabang == 'Serang' ? 'selected' : '' }}>Serang</option>
                                                                </select>
                                                        </div>
                                                         <div>
                                                            <label class="block text-sm text-gray-700 text-left font-bold">Status Aktif :</label>
                                                            <select name="status_aktif" id="status_aktif"
                                                                onchange="toggleMembershipForm()"
                                                                style="width: 100%; padding: 8px; border: 1px solid #ccc;">
                                                                
                                                                <option value="aktif"
                                                                    {{ (old('status_aktif') ?? $user->status_aktif) == 'aktif' ? 'selected' : '' }}>
                                                                    Aktif
                                                                </option>
                                                                <option value="tidak aktif"
                                                                    {{ (old('status_aktif') ?? $user->status_aktif) == 'tidak aktif' ? 'selected' : '' }}>
                                                                    Tidak Aktif
                                                                </option>
                                                            </select>
                                                        </div>
                                                       
                                                        <div>
                                                            <label class="block text-sm font-bold text-gray-700 text-left mb-2">Username</label>
                                                            <input type="text" name="username" value="{{ $user->username }}"
                                                                class="w-full border border-purple-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition duration-150">
                                                        </div>
                                                          <div class="space-y-1 text-left">
                                                                <label class="block text-sm font-bold text-gray-700">Password :</label>
                                                                <input type="password" name="password"
                                                                    placeholder="Password Baru"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition duration-150">
                                                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                                                            </div>
                                                        <div class="col-span-2">
                                                            <label class="block text-sm font-bold text-gray-700 text-left mb-2">Foto Profil</label>
                                                            <div class="flex items-center">
                                                                <span class="mr-4">
                                                                    <img src="{{ asset('storage/' . $user->foto_profile) }}" alt="Current profile" class="w-16 h-16 rounded-full object-cover border-2 border-purple-200 shadow-sm">
                                                                </span>
                                                                <input type="file" name="foto_profile"
                                                                    class="w-full border border-purple-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition duration-150">
                                                            </div>
                                                            <p class="text-xs text-gray-500 mt-2 text-left">Kosongkan jika tidak ingin mengubah foto.</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-end mt-6 space-x-3 border-t border-gray-100 pt-5">
                                                        <button type="button" @click="showEditModal = false"
                                                            class="px-5 py-2.5 text-gray-700 bg-gray-100 rounded-lg text-sm font-medium hover:bg-gray-200 transition duration-200 flex items-center">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                            Batal
                                                        </button>
                                                        <button type="submit"
                                                            class="px-5 py-2.5 text-white bg-purple-600 rounded-lg text-sm font-medium hover:bg-purple-700 transition duration-200 flex items-center">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                
                                                            <!-- Modal Konfirmasi Hapus dengan Tema Purple -->
                                <style>
                                .modal-backdrop {
                                    backdrop-filter: blur(4px);
                                }
                                .modal-enter {
                                    animation: modalEnter 0.3s ease-out;
                                }
                                .modal-content-enter {
                                    animation: modalContentEnter 0.3s ease-out;
                                }
                                @keyframes modalEnter {
                                    from { opacity: 0; }
                                    to { opacity: 1; }
                                }
                                @keyframes modalContentEnter {
                                    from {
                                        opacity: 0;
                                        transform: scale(0.9) translateY(-20px);
                                    }
                                    to {
                                        opacity: 1;
                                        transform: scale(1) translateY(0);
                                    }
                                }
                                </style>

                         <!-- Ganti bagian form dan modal delete dengan ini -->

<!-- Form dengan tombol hapus - PERBAIKAN: Tambahkan ID unik -->
<form id="deleteForm-{{ $user->id }}" action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="button" onclick="showDeleteModal({{ $user->id }})"
        class="px-3 py-1.5 text-white rounded-md text-xs bg-red-500 hover:bg-red-600 transition duration-150 shadow-sm flex items-center">
        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
        Hapus
    </button>
</form>

<!-- Modal Konfirmasi Hapus - PERBAIKAN: Tambahkan ID unik -->
<div id="deleteModal-{{ $user->id }}" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="modal-backdrop modal-enter fixed inset-0 bg-purple-900 bg-opacity-50" onclick="hideDeleteModal({{ $user->id }})"></div>
    
    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="modal-content-enter bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 border border-purple-100">
            <!-- Header dengan gradient purple -->
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 rounded-t-xl">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-white">Konfirmasi Penghapusan</h3>
                        <p class="text-purple-100 text-sm">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-6">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Apakah Anda yakin?</h4>
                    <p class="text-gray-600 mb-2">Anda akan menghapus admin:</p>
                    <p class="text-purple-600 font-semibold mb-4">{{ $user->nama_lengkap }}</p>
                    <p class="text-gray-500 text-sm">Data ini akan dihapus secara permanen dan tidak dapat dibatalkan.</p>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                <button onclick="hideDeleteModal({{ $user->id }})" 
                    class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-150 font-medium">
                    Batal
                </button>
                <button onclick="confirmDelete({{ $user->id }})" 
                    class="px-4 py-2 text-white bg-gradient-to-r from-red-500 to-red-600 rounded-lg hover:from-red-600 hover:to-red-700 transition duration-150 font-medium shadow-md">
                    Ya, Hapus!
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript yang diperbaiki -->
<script>
// PERBAIKAN: Fungsi dengan parameter userId
function showDeleteModal(userId) {
    document.getElementById('deleteModal-' + userId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideDeleteModal(userId) {
    document.getElementById('deleteModal-' + userId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function confirmDelete(userId) {
    hideDeleteModal(userId);
    
    // PERBAIKAN: Submit form yang tepat berdasarkan userId
    document.getElementById('deleteForm-' + userId).submit();
}

// Tutup modal dengan ESC key - PERBAIKAN: Tutup semua modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        // Tutup semua modal delete yang mungkin terbuka
        const deleteModals = document.querySelectorAll('[id^="deleteModal-"]');
        deleteModals.forEach(modal => {
            modal.classList.add('hidden');
        });
        document.body.style.overflow = 'auto';
    }
});
</script>

                                </div>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-purple-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                @if(request('search'))
                                    <p class="text-gray-600 font-medium">Tidak ada data admin dengan nama "{{ request('search') }}".</p>
                                    <p class="text-gray-500 text-sm mt-1">Coba cari dengan kata kunci lain.</p>
                                    <a href="{{ route('superadmin.users.index') }}" class="mt-4 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-md transition duration-150 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                        Tampilkan Semua
                                    </a>
                                @else
                                    <p class="text-gray-600 font-medium">Data admin belum ada.</p>
                                    <p class="text-gray-500 text-sm mt-1">Silakan tambahkan data admin baru.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Search Results Info -->
        @if(request('search') && $users->count() > 0)
            <div class="mt-4 text-sm text-gray-600">
                Menampilkan {{ $users->count() }} hasil pencarian untuk "{{ request('search') }}"
                <a href="{{ route('superadmin.users.index') }}" class="text-purple-600 hover:text-purple-800 font-medium ml-2">
                    Tampilkan semua
                </a>
            </div>
        @endif

        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->appends(['search' => request('search')])->links('vendor.pagination.custom') }}
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
    document.addEventListener("DOMContentLoaded", function() {
        @if (session('success'))
            document.getElementById('toast-success').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('toast-success').classList.add('hidden');
            }, 3000);
        @endif
        @if (session('error'))
            // Handle error toast if needed
        @endif

        document.getElementById('close-toast').addEventListener('click', function() {
            document.getElementById('toast-success').classList.add('hidden');
        });
    });
</script>
{{-- <script>
function confirmDelete(userId) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data admin ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm-' + userId).submit();
        }
    });
}
</script> --}}
<br>
@endsection
