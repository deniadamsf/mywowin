@extends('admin.layouts.master')

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
                    <a href="{{ route('admin.user.index') }}" class="text-gray-700 hover:text-blue-600">Kelola Admin</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-500">Data Admin</span>
                </li>
            </ol>
        </nav>

        <!-- Title Section -->
        <h1 class="text-2xl font-bold text-gray-800">Master Data Admin</h1>
        <p class="text-gray-600 mb-4">Ini merupakan halaman untuk mengetahui master data dari user admin.</p>

        <!-- Tab Switcher -->
        <div class="flex gap-4 mt-4">
            <a href="{{ route('admin.user.index') }}" class="text-sm font-medium text-blue-600 border border-blue-300 px-3 py-1 rounded hover:bg-blue-50">
                Data Admin
            </a>
            <span class="text-gray-400">|</span>
            <a href="{{ route('admin.users.master-member') }}" class="text-sm font-medium text-green-600 border border-green-300 px-3 py-1 rounded hover:bg-green-50">
                Data Member
            </a>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-center mb-4">Master Data Admin</h2>
        
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.user.create') }}" class="bg-green-500 hover:bg-green-600 text-white text-sm font-semibold py-2 px-4 rounded-lg transition">
                + Tambah Admin
            </a>
        </div>

        <!-- Table Responsive Wrapper -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse border border-gray-300 text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr class="text-left">
                        <th class="border border-gray-300 px-4 py-2">Foto Profil</th>
                        <th class="border border-gray-300 px-4 py-2">Nama Lengkap</th>
                        <th class="border border-gray-300 px-4 py-2">Kantor Cabang</th>
                        <th class="border border-gray-300 px-4 py-2">Username</th>
                        <th class="border border-gray-300 px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <img src="{{ asset('storage/'.$user->foto_profile) }}" class="w-16 h-16 object-cover rounded-lg">
                            </td>
                            <td class="border border-gray-300 px-4 py-2">{{ $user->nama_lengkap }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $user->kantor_cabang }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $user->username }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <div class="flex justify-center space-x-2">
                                   <!-- Lihat Detail -->
<div x-data="{ open: false }" class="relative">
    <button x-on:click="open = true" class="px-4 py-2 text-white rounded-md text-xs bg-blue-600 hover:bg-blue-700">
        Detail
    </button>
    <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center">
        <div x-on:click="open = false" class="absolute inset-0 bg-gray-500 bg-opacity-75"></div>
        <div x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="relative bg-white rounded-lg shadow-2xl border border-gray-200 w-full max-w-md mx-4 z-50">
            
            <div class="max-h-[90vh] flex flex-col overflow-y-auto">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 border-b py-4">
                    <h2 class="text-2xl font-semibold text-gray-800">Detail User</h2>
                    <button x-on:click="open = false" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
    
                <!-- Content -->
                <div class="mt-4 px-6 space-y-4 text-left text-gray-700">
                    <!-- Input Readonly Style -->
                    <div class="flex justify-center">
                        <div class="space-y-2">
                            <div class="w-24 h-24 rounded-full overflow-hidden border border-gray-300 shadow-sm">
                                <img src="{{ asset('storage/' . $user->foto_profile) }}" alt="Foto Profil" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="font-bold text-black">Nama Lengkap :</label>
                        <input type="text" class="w-full bg-gray-100 border border-gray-300 rounded-md p-2 text-gray-700 cursor-not-allowed" readonly value="{{ $user->nama_lengkap }}">
                    </div>
                    <div class="space-y-2">
                        <label class="font-bold text-black">Email :</label>
                        <input type="text" class="w-full bg-gray-100 border border-gray-300 rounded-md p-2 text-gray-700 cursor-not-allowed" readonly value="{{ $user->email }}">
                    </div>
                    <div class="space-y-2">
                        <label class="font-bold text-black">Kantor Cabang :</label>
                        <input type="text" class="w-full bg-gray-100 border border-gray-300 rounded-md p-2 text-gray-700 cursor-not-allowed" readonly value="{{ $user->kantor_cabang }}">
                    </div>
                    <div class="space-y-2">
                        <label class="font-bold text-black">Role :</label>
                        <input type="text" class="w-full bg-gray-100 border border-gray-300 rounded-md p-2 text-gray-700 cursor-not-allowed" readonly value="{{ $user->role }}">
                    </div>
                    <div class="space-y-2">
                        <label class="font-bold text-black">Username :</label>
                        <input type="text" class="w-full bg-gray-100 border border-gray-300 rounded-md p-2 text-gray-700 cursor-not-allowed" readonly value="{{ $user->username }}">
                    </div>
                    <div class="space-y-2">
                        <label class="font-bold text-black">Status :</label>
                        <input type="text" class="w-full bg-gray-100 border border-gray-300 rounded-md p-2 text-gray-700 cursor-not-allowed" readonly value="{{ $user->status_aktif }}">
                    </div>
                    <div class="pb-6"></div>
                </div>
            </div>
        </div>
    </div>
</div>
                                
                                    <!-- Edit -->
                                    <div x-data="{ showEditModal: false }">
                                        <button type="button" @click="showEditModal = true"
                                            class="px-4 py-2 text-white rounded-md text-xs bg-blue-500 hover:bg-blue-600">
                                            Edit
                                        </button>
                                
                                        <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
                                                <h2 class="text-xl font-bold text-gray-800 mb-4">Edit Data Member</h2>
                                                <form method="POST" action="{{ route('admin.user.update', $user->id) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 text-left">Nama Lengkap</label>
                                                            <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}"
                                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 text-left">Email</label>
                                                            <input type="email" name="email" value="{{ $user->email }}"
                                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                        </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 text-left">Kantor Cabang</label>
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
                                                            <label class="block text-sm font-medium text-gray-700 text-left">Username</label>
                                                            <input type="text" name="username" value="{{ $user->username }}"
                                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 text-left">Foto Profil</label>
                                                            <input type="file" name="foto_profile"
                                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                                                            <p class="text-xs text-gray-500 mt-1 text-left">Kosongkan jika tidak ingin mengubah foto.</p>
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
                                
                                    <!-- Hapus -->
                                    <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus?');"
                                        action="{{ route('admin.user.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-4 py-2 text-white rounded-md text-xs bg-red-500 hover:bg-red-600">
                                            Hapus
                                        </button>
                                    </form>
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
            {{ $users->links('pagination::tailwind') }}
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
