@extends('superadmin.layouts.master')

@section('title', 'WOWINFood')

@section('head')
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection

@section('content')
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
                        <span class="text-purple-600 font-medium">Data Member</span>
                    </li>
                </ol>
            </nav>
    
            <!-- Title Section -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <svg class="w-7 h-7 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Master Data Member
                </h1>
                <p class="text-gray-600 mt-1 pl-10">Ini merupakan halaman untuk mengetahui master data dari user Super Admin.</p>
            </div>
    
            <!-- Tab Switcher -->
            <div class="flex gap-3 mt-8 mb-4 border-b border-gray-200 pb-2">
                <a href="{{ route('superadmin.users.index') }}" class="text-sm font-medium text-purple-600 border border-purple-300 px-5 py-2.5 rounded-lg shadow-sm hover:bg-purple-50 transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Data Admin
                </a>
                <a href="{{ route('superadmin.users.master-member') }}" class="text-sm font-medium text-white bg-purple-600 px-5 py-2.5 rounded-lg shadow-sm hover:bg-purple-700 transition duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Data Member
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
         <div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-purple-800">Master Data Member</h2>

    <div class="flex items-center space-x-3">
        <!-- Filter Cabang -->
        <form method="GET" action="{{ route('superadmin.users.master-member') }}" class="flex items-center space-x-3">
            <select name="kantor_cabang" onchange="this.form.submit()"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#16782d] focus:border-[#16782d]">
                <option value="">Semua Cabang</option>
                @foreach($cabangs as $cabang)
                    <option value="{{ $cabang->kantor_cabang }}" {{ request('kantor_cabang') == $cabang->kantor_cabang ? 'selected' : '' }}>
                        {{ $cabang->kantor_cabang }}
                    </option>
                @endforeach
            </select>

            <!-- Search -->
            {{-- <input type="text" name="search" placeholder="Cari nama member..."
                value="{{ request('search') }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#16782d] focus:border-[#16782d]" />

            <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-lg text-sm">
                Cari
            </button> --}}
        </form>

        <!-- Tombol Tambah Member -->
        <a href="{{ route('superadmin.users.create') }}"
            class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-sm transition duration-150 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Member
        </a>
    </div>
</div>



            <!-- Search Bar -->
        <div class="mb-5 ">
            <form action="{{ route('superadmin.users.master-member') }}" method="GET" class="flex items-center">
                <div class="relative flex-1 ">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        id="search" 
                        placeholder="Cari nama member..." 
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
            <div class="overflow-x-auto shadow-sm rounded-lg">
                <table class="w-full table-auto text-sm">
                    <thead class="bg-purple-800 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold border-b">Foto Profil</th>
                            <th class="px-4 py-3 text-left font-semibold border-b">Nama Lengkap</th>
                            <th class="px-4 py-3 text-left font-semibold border-b">Cabang & Username</th>
                            <th class="px-4 py-3 text-left font-semibold border-b">Toko & Level</th>
                            <th class="px-4 py-3 text-center font-semibold border-b">Status</th>
                            <th class="px-4 py-3 text-center font-semibold border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr class="hover:bg-purple-50 transition duration-150">
                                <td class="px-4 py-3 text-center">
                                    <img src="{{ asset('storage/' . $user->foto_profile) }}" class="w-12 h-12 object-cover rounded-full mx-auto border-2 border-purple-100">
                                </td>
                                <td class="px-4 py-3 font-medium">{{ $user->nama_lengkap }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-800">{{ $user->kantor_cabang }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->username }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-medium">{{ $user->membership->nama_toko ?? '-' }}</p>
                                    <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        {{ strtoupper($user->membership->level_membership ?? 'BRONZE') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($user->status_aktif == 'aktif')
                                        <span class="px-2 py-1 text-[10px] font-semibold text-green-800 bg-green-100 rounded-full block mb-1">Akun Aktif</span>
                                    @else
                                        <span class="px-2 py-1 text-[10px] font-semibold text-red-800 bg-red-100 rounded-full block mb-1">Akun Nonaktif</span>
                                    @endif

                                    @if($user->membership && $user->membership->status_acc == 'approved')
                                        <span class="px-2 py-1 text-[10px] font-semibold text-blue-800 bg-blue-100 rounded-full block border border-blue-200">ACC Diskon</span>
                                    @elseif($user->membership && $user->membership->status_acc == 'rejected')
                                        <span class="px-2 py-1 text-[10px] font-semibold text-gray-800 bg-gray-200 rounded-full block border border-gray-300">Ditolak</span>
                                    @else
                                        <span class="px-2 py-1 text-[10px] font-semibold text-yellow-800 bg-yellow-100 rounded-full block border border-yellow-200 animate-pulse">Pending ACC</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex flex-wrap justify-center gap-2">
                                        <!-- === SAKLAR STATUS AKTIF MEMBER === -->
                                        @if($user->membership)
                                            @if($user->membership->status_acc == 'pending')
                                                <form action="{{ route('superadmin.users.acc', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="status_acc" value="approved">
                                                    <button type="submit" onclick="return confirm('ACC member ini agar mendapat diskon mitra?')" class="px-3 py-1.5 text-white rounded-md text-xs bg-green-500 hover:bg-green-600 transition flex items-center shadow-sm">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        ACC
                                                    </button>
                                                </form>
                                                <form action="{{ route('superadmin.users.acc', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="status_acc" value="rejected">
                                                    <button type="submit" onclick="return confirm('Tolak member ini menjadi pelanggan biasa?')" class="px-3 py-1.5 text-white rounded-md text-xs bg-orange-500 hover:bg-orange-600 transition flex items-center shadow-sm">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        Tolak
                                                    </button>
                                                </form>
                                            @elseif($user->membership->status_acc == 'approved')
                                                <form action="{{ route('superadmin.users.acc', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="status_acc" value="rejected">
                                                    <button type="submit" onclick="return confirm('Yakin ingin MENONAKTIFKAN status member orang ini? (Dia masih bisa login, tapi diskon mitra akan dicabut)')" class="px-3 py-1.5 text-white rounded-md text-xs bg-red-500 hover:bg-red-600 transition flex items-center shadow-sm">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                        Nonaktifkan
                                                    </button>
                                                </form>
                                            @elseif($user->membership->status_acc == 'rejected')
                                                <form action="{{ route('superadmin.users.acc', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="status_acc" value="approved">
                                                    <button type="submit" onclick="return confirm('Yakin ingin MENGAKTIFKAN kembali status member orang ini?')" class="px-3 py-1.5 text-white rounded-md text-xs bg-green-500 hover:bg-green-600 transition flex items-center shadow-sm">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        Aktifkan
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                        <!-- ====================================== -->
                                        
                                       <div x-data="{ open: false }" class="relative" @keydown.escape.window="open = false">
    
    <button @click="open = true" class="px-3 py-1.5 text-white rounded-md text-xs bg-purple-600 hover:bg-purple-700 transition duration-150 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        Detail
    </button>

    <template x-if="open">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            
            <div 
                x-show="open" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0" 
                x-transition:enter-end="opacity-100" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100" 
                x-transition:leave-end="opacity-0"
                @click="open = false" 
                class="absolute inset-0 bg-gray-500 bg-opacity-75"
            ></div>

            <div 
                x-show="open" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0 transform scale-95" 
                x-transition:enter-end="opacity-100 transform scale-100" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100 transform scale-100" 
                x-transition:leave-end="opacity-0 transform scale-95"
                class="relative w-full max-w-2xl bg-white rounded-lg shadow-2xl flex flex-col"
            >
                <div class="flex items-center justify-between px-6 py-4 bg-purple-50 border-b border-purple-200 rounded-t-lg">
                    <h2 class="text-xl font-semibold text-purple-800">Detail User</h2>
                    <button @click="open = false" class="p-2 rounded-full hover:bg-purple-100">
                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 max-h-[75vh] overflow-y-auto">
                    <div class="flex justify-center mb-6">
                        <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-purple-100 shadow-sm bg-white">
                            <img src="{{ asset('storage/' . $user->foto_profile) }}" alt="Foto Profil" class="w-full h-full object-cover">
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        {{-- Data Diri --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-purple-700 text-left">Nama Lengkap</label>
                                <p class="mt-1 p-2 bg-gray-50 border rounded-md text-left">{{ $user->nama_lengkap }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-purple-700 text-left">Email</label>
                                <p class="mt-1 p-2 bg-gray-50 border rounded-md text-left">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-purple-700 text-left">Kantor Cabang</label>
                                <p class="mt-1 p-2 bg-gray-50 border rounded-md text-left">{{ $user->kantor_cabang }}</p>
                            </div>
                             <div>
                                <label class="block text-sm font-bold text-purple-700 text-left">Role</label>
                                <p class="mt-1 p-2 bg-gray-50 border rounded-md text-left">{{ $user->role }}</p>
                            </div>
                             <div>
                                <label class="block text-sm font-bold text-purple-700 text-left">Nama Toko</label>
                                <p class="mt-1 p-2 bg-gray-50 border rounded-md text-left">{{ $user->membership->nama_toko ?? '-' }}</p>
                            </div>
                             <div>
                                <label class="block text-sm font-bold text-purple-700 text-left">Nama Sales</label>
                                <p class="mt-1 p-2 bg-gray-50 border rounded-md text-left">{{ $user->membership->nama_sales ?? '-' }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-purple-700 text-left">Alamat</label>
                            <p class="mt-1 p-2 bg-gray-50 border rounded-md min-h-[60px] text-left">{{ $user->membership->alamat ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- (Letakkan ini di atas div "Riwayat Pembelian") --}}
                            <div class="mt-6 border-t pt-6">
                                <div class="flex justify-between items-center mb-4">
        
                                    <h3 class="text-lg font-semibold text-purple-800 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" />
                                        </svg>
                                        Progres Membership
                                    </h3>
                                    
                                    <a href="{{ route('superadmin.users.upgrade.form', $user->id) }}" class="px-3 py-1.5 text-xs font-medium bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-150 flex items-center shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        Upgrade
                                    </a>
                                </div>
                                
                                @if($user->progress->isMaxLevel)
                                    <div class="text-center bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md" role="alert">
                                        <p class="font-bold">🎉 Selamat!</p>
                                        <p>Anda telah mencapai level membership tertinggi: Diamond.</p>
                                    </div>
                                @else
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-end text-sm">
                                            <div class="font-medium text-gray-700">
                                                Belanja Bulan Ini: 
                                                <span class="font-bold text-purple-700">Rp {{ number_format($user->progress->totalBelanja, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="text-gray-500">
                                                Target {{ $user->progress->levelBerikutnya }}: 
                                                <span class="font-semibold">Rp {{ number_format($user->progress->targetBerikutnya, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        
                                        {{-- Progress Bar --}}
                                        <div class="w-full bg-gray-200 rounded-full h-4">
                                            <div class="bg-purple-600 h-4 rounded-full text-center text-white text-xs leading-4" style="width: {{ $user->progress->percentage }}%;">
                                                {{ round($user->progress->percentage) }}%
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-between text-xs font-medium text-gray-500">
                                            <span>Level: {{ $user->progress->levelSekarang }}</span>
                                            <span>Menuju: {{ $user->progress->levelBerikutnya }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                    
                    {{-- Riwayat Pembelian --}}
                    <div class="mt-6 border-t pt-6">
                        <h3 class="text-lg font-semibold text-purple-800 mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            Riwayat Pembelian
                        </h3>
                        <div class="bg-purple-50 p-3 rounded-lg border border-purple-200 max-h-80 overflow-y-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-purple-700">
                                    <tr>
                                        <th class="py-2 px-3 font-semibold">Invoice</th>
                                        <th class="py-2 px-3 font-semibold">Barang Dibeli</th>
                                        <th class="py-2 px-3 font-semibold text-right">Total</th>
                                        <th class="py-2 px-3 font-semibold text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($user->orders as $order)
                                        <tr class="border-t border-purple-200 align-top bg-white">
                                            <td class="py-3 px-3">
                                                <p class="font-medium text-gray-800">{{ $order->invoice_number }}</p>
                                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y') }}</p>
                                            </td>
                                            
                                            <td class="py-3 px-3">
                                                <ul class="space-y-1">
                                                    @foreach ($order->orderItems as $item)
                                                        <li class="text-xs text-gray-700">
                                                            - {{ $item->quantity }}x {{ $item->product->nama_produk ?? 'Produk Dihapus' }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>

                                            <td class="py-3 px-3 text-right font-medium text-gray-800">
                                                Rp {{ number_format($order->total, 0, ',', '.') }}
                                            </td>

                                            <td class="py-3 px-3 text-center">
                                                @if ($order->status == 'completed')
                                                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Selesai</span>
                                                @elseif ($order->status == 'pending')
                                                    <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                                                @elseif ($order->status == 'shipped')
                                                    <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Dikirim</span>
                                                @elseif ($order->status == 'canceled')
                                                    <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Dibatalkan</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">{{ ucfirst($order->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-gray-500 bg-white">
                                                Belum ada riwayat pembelian.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end p-4 border-t bg-gray-50 rounded-b-lg">
                    <button @click="open = false" class="px-5 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition flex items-center">Tutup</button>
                </div>
            </div>
        </div>
    </template>
</div>
                                        <!-- Edit -->
                                        <div x-data="{ showEditModal: {{ old('edit_user_id') == $user->id && $errors->any() ? 'true' : 'false' }} }">
                                            <button type="button" @click="showEditModal = true"
                                                class="px-3 py-1.5 text-white rounded-md text-xs bg-indigo-500 hover:bg-indigo-600 transition duration-150 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>

                                            <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 mx-4" 
                                                     @click.away="showEditModal = false">
                                                    <div class="flex justify-between items-center border-b border-gray-200 pb-3 mb-4">
                                                        <h2 class="text-xl font-bold text-purple-800">Edit Data Member</h2>
                                                        <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">
                                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <form method="POST" action="{{ route('superadmin.users.update', $user->id) }}" enctype="multipart/form-data" class="space-y-4">
                                                        @csrf
                                                        @method('PUT')
                                                        
                                                        <input type="hidden" name="edit_user_id" value="{{ $user->id }}">
                                                        
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                            <div class="space-y-1 text-left">
                                                                <label class="block text-sm font-bold text-gray-700">Nama Lengkap :</label>
                                                                <input type="text" name="nama_lengkap"
                                                                    value="{{ $user->nama_lengkap }}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition duration-150">
                                                            </div>
                                                            <div class="space-y-1 text-left">
                                                                <label class="block text-sm font-bold text-gray-700">Email :</label>
                                                                <input type="email" name="email"
                                                                    value="{{ $user->email }}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition duration-150">
                                                            </div>
                                                            <div class="space-y-1 text-left">
                                                                <label class="block text-sm font-bold text-gray-700">Kantor Cabang :</label>
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
                                                            <div class="space-y-1 text-left">
                                                                <label class="block text-sm font-bold text-gray-700">Username :</label>
                                                                <input type="text" name="username"
                                                                    value="{{ $user->username }}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition duration-150">
                                                            </div>
                                                            <div class="space-y-1 text-left">
                                                                <label class="block text-sm font-bold text-gray-700">Password :</label>
                                                                <input type="password" name="password"
                                                                    placeholder="Password Baru"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition duration-150">
                                                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                                                            </div>
                                                            <div class="space-y-1 text-left">
                                                                <label class="block text-sm font-bold text-gray-700">Nama Usaha :</label>
                                                                <input type="text" name="nama_toko" value="{{ $user->membership->nama_toko ?? '' }}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition duration-150">
                                                            </div>
                                                            <div class="space-y-1 text-left">
                                                                <label class="block text-sm font-bold text-gray-700">Nama Sales :</label>
                                                                <input type="text" name="nama_sales" value="{{ $user->membership->nama_sales ?? '' }}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition duration-150">
                                                            </div>
                                                            <div class="space-y-1 text-left">
                                                                <label class="block text-sm font-bold text-gray-700">No. Hp :</label>
                                                                <input type="text" name="no_hp" value="{{ $user->membership->no_hp ?? '' }}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition duration-150">
                                                            </div>
                                                            <div class="space-y-1 text-left">
                                                                <label class="block text-sm font-bold text-gray-700">Alamat :</label>
                                                                <textarea name="alamat" rows="2"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition duration-150">{{ $user->membership->alamat ?? '' }}</textarea>
                                                            </div>
                                                            <div class="space-y-1 text-left">
                                                                <label class="block text-sm font-bold text-gray-700">Foto Profil :</label>
                                                                <div class="flex items-center space-x-3">
                                                                    <img src="{{ asset('storage/' . $user->foto_profile) }}" class="h-12 w-12 rounded-full object-cover border border-gray-200">
                                                                    <input type="file" name="foto_profile"
                                                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-purple-200 focus:border-purple-500 transition duration-150">
                                                                </div>
                                                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex justify-end mt-6 space-x-2">
                                                            <button type="button" @click="showEditModal = false"
                                                                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md text-sm font-medium hover:bg-gray-200 transition duration-150">
                                                                Batal
                                                            </button>
                                                            <button type="submit"
                                                                class="px-4 py-2 text-white bg-purple-600 rounded-md text-sm font-medium hover:bg-purple-700 transition duration-150">
                                                                Simpan Perubahan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                       <!-- Form dengan tombol hapus (gunakan ID unik berdasarkan user ID) -->
<form id="deleteForm-{{ $user->id }}" action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="button" onclick="showDeleteModal({{ $user->id }})"
        class="px-3 py-1.5 text-white rounded-md text-xs bg-red-500 hover:bg-red-600 transition duration-150 shadow-sm flex items-center">
        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        Hapus
    </button>
</form>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal-{{ $user->id }}" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-purple-900 bg-opacity-50" onclick="hideDeleteModal({{ $user->id }})"></div>

    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 border border-purple-100">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 rounded-t-xl">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-white">Konfirmasi Penghapusan</h3>
                        <p class="text-purple-100 text-sm">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 py-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Apakah Anda yakin?</h4>
                <p class="text-gray-600 mb-2">Anda akan menghapus admin:</p>
                <p class="text-purple-600 font-semibold mb-4">{{ $user->nama_lengkap }}</p>
                <p class="text-gray-500 text-sm">Data ini akan dihapus secara permanen dan tidak dapat dibatalkan.</p>
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
<script>
// Tampilkan modal hapus
function showDeleteModal(userId) {
    document.getElementById('deleteModal-' + userId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Sembunyikan modal
function hideDeleteModal(userId) {
    document.getElementById('deleteModal-' + userId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Submit form hapus
function confirmDelete(userId) {
    hideDeleteModal(userId);
    document.getElementById('deleteForm-' + userId).submit();
}

// Tutup semua modal saat ESC ditekan
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('[id^="deleteModal-"]').forEach(modal => {
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
                <a href="{{ route('superadmin.users.master-member') }}" class="text-purple-600 hover:text-purple-800 font-medium ml-2">
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
<br>
@endsection
