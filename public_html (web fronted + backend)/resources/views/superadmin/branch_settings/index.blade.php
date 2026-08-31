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
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gradient-to-r from-white to-purple-50 p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex items-center mb-6">
            <div class="bg-purple-700 p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-desktop text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Monitoring Konfigurasi PT</h1>
                <p class="text-gray-600">Pantau seluruh Admin Cabang yang telah mengatur identitas PT mereka.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-3">
                    <i class="fas fa-user-tie text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Admin Sudah Setting</p>
                    {{-- Ganti count($cabangList) jadi $settings->count() --}}
                    <p class="text-xl font-bold text-purple-700">{{ $settings->count() }} Admin</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-3">
                    <i class="fas fa-check-double text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status Nota</p>
                    <p class="text-xl font-bold text-green-600">Multi-PT Berjalan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase font-bold">
                        <th class="px-6 py-4 text-left">Wilayah</th>
                        <th class="px-6 py-4 text-left">Admin Pengelola</th>
                        <th class="px-6 py-4 text-left">Nama PT & Info</th>
                        <th class="px-6 py-4 text-center">Google Maps Review</th>
                        <th class="px-6 py-4 text-center">Logo</th>
                        <th class="px-6 py-4 text-right">Update Terakhir</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    {{-- Loop berdasarkan $settings yang ditarik dari database --}}
                    @forelse ($settings as $setting)
                        <tr class="hover:bg-gray-50 border-b border-gray-100 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full font-bold text-xs uppercase">
                                    {{ $setting->enum_value }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($setting->user)
                                    <div class="font-bold text-gray-800">{{ $setting->user->nama_lengkap }}</div>
                                    <div class="text-[10px] text-gray-400">ID: #{{ $setting->user_id }}</div>
                                @else
                                    <div class="font-bold text-red-500 italic">Akun Admin Tidak Ditemukan</div>
                                    <div class="text-[10px] text-red-400 font-semibold">Data ini harus dihapus/reset</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold">{{ $setting->nama_pt }}</div>
                                <div class="text-xs text-gray-500 italic">{{ $setting->no_telp }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($setting->google_maps_review_url)
                                    <a href="{{ $setting->google_maps_review_url }}" target="_blank" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200">
                                        <i class="fab fa-google mr-1 text-red-500"></i> Buka Link
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs italic">Belum diisi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    @if ($setting->logo)
                                        <img src="{{ asset('storage/' . $setting->logo) }}" 
                                             class="w-10 h-10 object-contain p-1 border rounded bg-white shadow-sm">
                                    @else
                                        <span class="text-red-400 text-[10px] italic">Belum Upload</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right text-xs text-gray-400">
                                {{ $setting->updated_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                <i class="fas fa-info-circle mb-2 text-2xl"></i>
                                <p>Belum ada Admin yang mengisi Identitas PT Cabang.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection