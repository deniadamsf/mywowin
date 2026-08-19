
@extends('admin.layouts.master')

@section('title', 'WOWINFood - Master Data Member')

@section('head')
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="//unpkg.com/alpinejs" defer></script>
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
                    <a href="{{ route('admin.user.index') }}" class="hover:text-[#16782d] transition-colors duration-200">Kelola Admin</a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Master Data Member
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-[#16782d] p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Master Data Member</h1>
                <p class="text-gray-600">Ini merupakan halaman untuk mengetahui master data dari user member.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-3">
                    <i class="fas fa-user-check text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Member</p>
                    <p class="text-xl font-bold">{{ $totalClaimedByMe }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-3">
                    <i class="fas fa-store text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Toko Aktif</p>
                    <p class="text-xl font-bold">{{ $totalTokoAktif }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-3">
                    <i class="fas fa-map-marker-alt text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Cabang</p>
                    <p class="text-xl font-bold">{{ Auth::user()->kantor_cabang }}</p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex gap-3 mt-4">
            <a href="{{ route('admin.dashboard') }}"
                class="text-sm font-medium text-[#16782d] border border-[#16782d] px-4 py-2 rounded-lg hover:bg-[#16782d]/10 transition flex items-center">
                <i class="fas fa-user-shield mr-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.users.master-member') }}"
                class="text-sm font-medium text-white bg-[#16782d] px-4 py-2 rounded-lg hover:bg-[#135e24] transition flex items-center">
                <i class="fas fa-users mr-2"></i> Data Member
            </a>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-[#16782d] mr-2"></i> Master Data Member
            </h2>
            
            <!-- Search Box -->
            <div class="relative">
                <form action="{{ route('admin.users.master-member') }}" method="GET">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari member..." 
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#16782d] focus:border-[#16782d] w-64">
                    <button type="submit" class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Filter & Export -->
        <div class="flex justify-between mb-4">
            <div class="flex space-x-2">
                {{-- <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#16782d] focus:border-[#16782d]">
                    <option value="">Semua Cabang</option>
                    <option value="Trenggalek">Trenggalek</option>
                    <option value="Kediri">Kediri</option>
                    <option value="Madiun">Madiun</option>
                    <option value="Solo">Solo</option>
                    <option value="Jogja">Jogja</option>
                    <option value="Cirebon">Cirebon</option>
                    <option value="Kudus">Kudus</option>
                    <option value="Bogor">Bogor</option>
                    <option value="Serang">Serang</option>
                </select> --}}
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.user.create') }}" 
                    class="bg-[#16782d] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#135e24] transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Member
                </a>
                {{-- <a href="#" class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800 transition flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </a> --}}
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b">
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-image mr-2 text-[#16782d]"></i>
                                Foto
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2 text-[#16782d]"></i>
                                Nama Lengkap
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-building mr-2 text-[#16782d]"></i>
                                 Cabang
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-user-tag mr-2 text-[#16782d]"></i>
                                Username
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-store mr-2 text-[#16782d]"></i>
                                Nama Toko
                            </div>
                        </th>
                        <th class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-cogs mr-2 text-[#16782d]"></i>
                                Aksi
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 border-b border-gray-100 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <img src="{{ asset('storage/' . $user->foto_profile) }}"
                                    class="w-16 h-16 object-cover rounded-lg shadow-sm border border-gray-200">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $user->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                                    {{ $user->kantor_cabang }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $user->username }}</td>
                            <td class="px-6 py-4">{{ $user->membership->nama_toko ?? '-' }}</td>
                            <td class="px-6 py-4">
                               <div class="flex justify-center items-center gap-2">
    {{-- LOGIKA CLAIM / UNCLAIM --}}
    @if(!$user->admin_id)
        {{-- Jika belum ada yang mengonfirmasi --}}
        <form action="{{ route('admin.users.claim', $user->id) }}" method="POST" onsubmit="return confirm('Konfirmasi member ini sebagai bagian dari cabang Anda?')">
            @csrf
            <button type="submit" class="px-3 py-1.5 text-white text-xs bg-orange-500 rounded-md hover:bg-orange-600 transition flex items-center shadow-sm animate-pulse">
                <i class="fas fa-check-circle mr-1"></i> Claim
            </button>
        </form>
    @elseif($user->admin_id == Auth::id())
        {{-- Jika sudah dikonfirmasi oleh Admin yang sedang login --}}
        <form action="{{ route('admin.users.unclaim', $user->id) }}" method="POST" onsubmit="return confirm('Lepas konfirmasi member ini? Admin lain akan bisa mengonfirmasi member ini kembali.')">
            @csrf
            <button type="submit" class="px-3 py-1.5 text-white text-xs bg-red-500 rounded-md hover:bg-red-600 transition flex items-center shadow-sm">
                <i class="fas fa-undo mr-1"></i> Lepas
            </button>
        </form>
    

    {{-- Tombol Detail (Ikon Mata) --}}
                    <div x-data="{ open: false }">
                        <button @click="open = true" title="Detail" class="w-8 h-8 flex items-center justify-center text-white bg-[#16782d] rounded-lg hover:bg-[#135e24] transition-all shadow-sm">
                            <i class="fas fa-eye text-xs"></i>
                        </button>
                        
                        {{-- Modal Detail diletakkan di sini --}}
                        <template x-if="open">
        <div 
            class="fixed inset-0 z-50 flex items-center justify-center p-4" 
            x-cloak 
            x-on:keydown.escape.window="open = false"
        >
            <div 
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-black bg-opacity-50" 
                x-on:click="open = false"
            ></div>

           <div 
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="relative w-full max-w-4xl bg-white rounded-lg shadow-4xl flex flex-col"
            >
                <div class="flex items-center justify-between p-4 border-b">
                    <h2 class="text-xl font-semibold text-[#16782d] flex items-center">
                        <i class="fas fa-user-circle mr-2"></i> Detail Member
                    </h2>
                    <button x-on:click="open = false" class="p-2 rounded-full hover:bg-gray-100">
                        <i class="fas fa-times text-gray-500"></i>
                    </button>
                </div>

                <div class="p-6 max-h-[70vh] overflow-y-auto">
                    <div class="flex justify-center mb-6">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200 shadow-sm">
                            <img src="{{ asset('storage/' . $user->foto_profile) }}" alt="Foto Profil" class="w-full h-full object-cover">
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        {{-- Nama Lengkap --}}
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block font-medium text-sm text-gray-700 mb-1">Nama Lengkap</label>
                            <div class="flex items-center border bg-white rounded p-2">
                                <i class="fas fa-user text-gray-400 mr-2"></i>
                                <span>{{ $user->nama_lengkap }}</span>
                            </div>
                        </div>
                        
                        {{-- Email --}}
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block font-medium text-sm text-gray-700 mb-1">Email</label>
                            <div class="flex items-center border bg-white rounded p-2">
                                <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                <span>{{ $user->email }}</span>
                            </div>
                        </div>

                        {{-- Kantor Cabang --}}
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block font-medium text-sm text-gray-700 mb-1">Kantor Cabang</label>
                            <div class="flex items-center border bg-white rounded p-2">
                                <i class="fas fa-building text-gray-400 mr-2"></i>
                                <span>{{ $user->kantor_cabang }}</span>
                            </div>
                        </div>

                        {{-- Role --}}
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block font-medium text-sm text-gray-700 mb-1">Role</label>
                            <div class="flex items-center border bg-white rounded p-2">
                                <i class="fas fa-user-tag text-gray-400 mr-2"></i>
                                <span>{{ $user->role }}</span>
                            </div>
                        </div>

                        {{-- Username --}}
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block font-medium text-sm text-gray-700 mb-1">Username</label>
                            <div class="flex items-center border bg-white rounded p-2">
                                <i class="fas fa-user-check text-gray-400 mr-2"></i>
                                <span>{{ $user->username }}</span>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block font-medium text-sm text-gray-700 mb-1">Status</label>
                            <div class="flex items-center border bg-white rounded p-2">
                                <i class="fas fa-toggle-on text-gray-400 mr-2"></i>
                                <span>{{ $user->status_aktif }}</span>
                            </div>
                        </div>
                        
                        {{-- Nama Toko --}}
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block font-medium text-sm text-gray-700 mb-1">Nama Toko</label>
                            <div class="flex items-center border bg-white rounded p-2">
                                <i class="fas fa-store text-gray-400 mr-2"></i>
                                <span>{{ $user->membership->nama_toko ?? '-' }}</span>
                            </div>
                        </div>
                        
                        {{-- Nama Sales --}}
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block font-medium text-sm text-gray-700 mb-1">Nama Sales</label>
                            <div class="flex items-center border bg-white rounded p-2">
                                <i class="fas fa-id-card text-gray-400 mr-2"></i>
                                <span>{{ $user->membership->nama_sales ?? '-' }}</span>
                            </div>
                        </div>
                        
                        {{-- No HP --}}
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block font-medium text-sm text-gray-700 mb-1">No HP</label>
                            <div class="flex items-center border bg-white rounded p-2">
                                <i class="fas fa-phone text-gray-400 mr-2"></i>
                                <span>{{ $user->membership->no_hp ?? '-' }}</span>
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block font-medium text-sm text-gray-700 mb-1">Alamat</label>
                            <div class="border bg-white rounded p-3 max-h-40 overflow-y-auto">
                                {{ $user->membership->alamat ?? '-' }}
                            </div>
                        </div>
                    </div>

                    {{-- Progres Membership untuk Admin Panel --}}
<div class="mt-6 border-t pt-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" />
        </svg>
        Progres Membership
    </h3>
    
    @if($user->progress->isMaxLevel)
        <div class="text-center bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md" role="alert">
            <p class="font-bold">🎉 Selamat!</p>
            <p>Member ini telah mencapai level membership tertinggi.</p>
        </div>
    @else
        <div class="space-y-2">
            <div class="flex justify-between items-end text-sm">
                <div class="font-medium text-gray-700">
                    Belanja Bulan Ini: 
                    <span class="font-bold text-[#16782d]">Rp {{ number_format($user->progress->totalBelanja, 0, ',', '.') }}</span>
                </div>
                <div class="text-gray-500">
                    Target {{ $user->progress->levelBerikutnya }}: 
                    <span class="font-semibold">Rp {{ number_format($user->progress->targetBerikutnya, 0, ',', '.') }}</span>
                </div>
            </div>
            
            {{-- Progress Bar --}}
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-[#16782d] h-4 rounded-full text-center text-white text-xs leading-4" style="width: {{ $user->progress->percentage }}%;">
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
               <div class="mt-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
        <i class="fas fa-history mr-2 text-gray-500"></i>
        Riwayat Pembelian
    </h3>
    <div class="bg-gray-50 p-4 rounded-lg border">
        <table class="w-full text-sm text-left">
            <thead class="text-gray-600">
                <tr>
                    <th class="py-2 px-3 font-medium">Invoice</th>
                    <th class="py-2 px-3 font-medium">Tanggal</th>
                    {{-- 1. Tambah header baru --}}
                    <th class="py-2 px-3 font-medium">Barang Dibeli</th> 
                    <th class="py-2 px-3 font-medium text-right">Total</th>
                    <th class="py-2 px-3 font-medium text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($user->orders as $order)
                    <tr class="border-t border-gray-200 align-top">
                        <td class="py-3 px-3">{{ $order->invoice_number }}</td>
                        <td class="py-3 px-3">{{ $order->created_at->format('d M Y') }}</td>
                        
                        {{-- 2. Tambah kolom baru untuk daftar barang --}}
                        <td class="py-3 px-3">
                            <ul class="space-y-1">
                                @foreach ($order->orderItems as $item)
                                    <li class="text-xs text-gray-700">
                                        - {{ $item->quantity }}x {{ $item->product->nama_produk ?? 'Produk Dihapus' }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>

                        <td class="py-3 px-3 text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
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
                        {{-- 3. Update colspan di @empty --}}
                        <td colspan="5" class="text-center py-4 text-gray-500">
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
                    <button x-on:click="open = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition flex items-center">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

                                        <!-- Edit -->
                                        <div x-data="{ showEditModal: false }">
                        <button @click="showEditModal = true" title="Edit" class="w-8 h-8 flex items-center justify-center text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-all shadow-sm">
                            <i class="fas fa-pen text-xs"></i>
                        </button>

                                            <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
                                                    <h2 class="text-xl font-bold text-gray-800 mb-4">Edit Data Member</h2>
                                                    <form method="POST"
                                                        action="{{ route('admin.user.update', $user->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block text-sm  text-gray-900 text-left font-semibold">Nama Lengkap :</label>
                                                                <input type="text" name="nama_lengkap"
                                                                    value="{{ $user->nama_lengkap }}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                            </div>
                                                            <div>
                                                                <label
                                                                    class="block text-sm  text-gray-900 text-left font-semibold">Email :</label>
                                                                <input type="email" name="email"
                                                                    value="{{ old('email', $user->email) }}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                            </div>
                                                            <div>
                                                                <label
                                                                    class="block text-sm  text-gray-900 text-left font-semibold">Kantor Cabang :</label>
                                                                    <select name="kantor_cabang" id="kantor_cabang" onchange="toggleMembershipForm()" style="width: 100%; padding: 8px; border: 1px solid #ccc;">
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
                                                                
                                                            </div>
                                                          <div>
                                                            <label class="block text-sm text-gray-900 text-left font-semibold">Status Aktif :</label>
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
                                                                <label
                                                                    class="block text-sm  text-gray-900 text-left font-semibold">Username :</label>
                                                                <input type="text" name="username"
                                                                    value="{{ $user->username }}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                            </div>
                                                            <div>
                                                                <label
                                                                    class="block text-sm  text-gray-900 text-left font-semibold">Password :</label>
                                                                    <input type="password" name="password"
                                                                    placeholder="Kosongkan jika tidak ingin mengubah password"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm  text-gray-900 text-left font-semibold">Nama Toko :</label>
                                                                <input type="text" name="nama_toko" value="{{ $user->membership->nama_toko ?? '' }}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm  text-gray-900 text-left font-semibold">Nama Sales :</label>
                                                                <input type="text" name="nama_sales" value="{{ $user->membership->nama_sales ?? '' }}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm  text-gray-900 text-left font-semibold">No. Hp :</label>
                                                                <input type="text" name="no_hp" value="{{ $user->membership->no_hp ?? '' }}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm  text-gray-900 text-left font-semibold">Alamat :</label>
                                                                <input type="text" name="alamat" value="{{ $user->membership->alamat ?? '' }}"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm  text-gray-900 text-left font-semibold">Foto Profil :</label>
                                                                <input type="file" name="foto_profile"
                                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                                <p class="text-xs text-gray-500 mt-1 text-left">Kosongkan jika tidak
                                                                    ingin mengubah foto.</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex justify-end mt-4 space-x-2">
                                                            <button type="button" @click="showEditModal = false"
                                                                class="px-4 py-2 text-white rounded-md text-xs bg-gray-400 hover:bg-gray-500">
                                                                Batal
                                                            </button>
                                                            <button type="submit"
                                                                class="px-4 py-2 text-white rounded-md text-xs bg-blue-600 hover:bg-blue-700">
                                                                Simpan Perubahan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Tombol Hapus (Ikon Tempat Sampah) --}}
                                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus member ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus" class="w-8 h-8 flex items-center justify-center text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all shadow-sm">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                    @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-red-500 py-4">Data admin belum ada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $users->links('vendor.pagination.custom') }}
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
@endsection
