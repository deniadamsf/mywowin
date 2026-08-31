@extends('admin.layouts.master')

@section('title', 'WOWINFood - Pengaturan Cabang')

@section('head')
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-gradient-to-r from-white to-green-50 p-6 rounded-2xl shadow-lg border border-gray-200">
        <nav class="flex text-sm mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-[#16782d] flex items-center transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Identitas PT Cabang
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-[#16782d] p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-id-card text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Pengaturan Identitas PT</h1>
                <p class="text-gray-600">Lengkapi data PT untuk keperluan cetak nota dan legalitas di cabang <strong>{{ auth()->user()->kantor_cabang }}</strong>.</p>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200">
        <form action="{{ route('admin.branch_settings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-building text-[#16782d] mr-2"></i> Nama PT di Nota
                        </label>
                        <input type="text" name="nama_pt" value="{{ $setting->nama_pt ?? '' }}" 
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-[#16782d] focus:border-[#16782d] transition shadow-sm"
                            placeholder="Contoh: PT. SHOWA L S" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-phone text-[#16782d] mr-2"></i> Nomor Telepon PT
                        </label>
                        <input type="text" name="no_telp" value="{{ $setting->no_telp ?? '' }}" 
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-[#16782d] focus:border-[#16782d] transition shadow-sm"
                            placeholder="Contoh: 0812xxxx" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-map-marker-alt text-[#16782d] mr-2"></i> Alamat Lengkap PT
                        </label>
                        <textarea name="alamat" rows="3" 
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-[#16782d] focus:border-[#16782d] transition shadow-sm"
                            placeholder="Alamat lengkap yang akan muncul di header nota..." required>{{ $setting->alamat ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <i class="fab fa-google text-red-500 mr-2"></i> Link Ulasan Google Maps Cabang
                        </label>
                        <input type="url" name="google_maps_review_url" value="{{ $setting->google_maps_review_url ?? '' }}" 
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-[#16782d] focus:border-[#16782d] transition shadow-sm"
                            placeholder="Contoh: https://g.page/r/XXXXX/review">
                        <p class="text-xs text-gray-500 mt-1">Tautan ini akan terbuka otomatis saat pembeli memberi rating bintang 4-5 di aplikasi/web.</p>
                    </div>
                </div>

                <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl p-6 bg-gray-50">
                    <label class="block text-sm font-bold text-gray-700 mb-4 text-center">Logo PT (Format: PNG/JPG)</label>
                    
                    <div class="relative group">
                        @if($setting && $setting->logo)
                            <img src="{{ asset('storage/' . $setting->logo) }}" id="preview-logo" class="w-40 h-40 object-contain mb-4 rounded-lg shadow-md bg-white p-2">
                        @else
                            <div id="placeholder-logo" class="w-40 h-40 flex items-center justify-center bg-white border border-gray-300 rounded-lg mb-4 shadow-sm">
                                <i class="fas fa-image text-gray-300 text-5xl"></i>
                            </div>
                        @endif
                    </div>

                    <input type="file" name="logo" id="input-logo" class="hidden" accept="image/*">
                    <button type="button" onclick="document.getElementById('input-logo').click()" 
                        class="bg-white border-2 border-[#16782d] text-[#16782d] px-6 py-2 rounded-xl text-sm font-bold hover:bg-[#16782d] hover:text-white transition-all duration-300">
                        <i class="fas fa-upload mr-2"></i> Pilih Gambar Logo
                    </button>
                    <p class="text-[10px] text-gray-400 mt-2 italic">*Kosongkan jika tidak ingin mengubah logo</p>
                </div>
            </div>

            <hr class="my-8 border-gray-100">

            <div class="flex justify-end">
                <button type="submit" class="bg-[#16782d] text-white px-10 py-3 rounded-xl font-bold shadow-lg hover:bg-[#135e24] transform hover:scale-105 transition-all duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i> Simpan Pengaturan Identitas
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Script sederhana untuk preview logo setelah pilih file
    document.getElementById('input-logo').onchange = function (evt) {
        const [file] = this.files
        if (file) {
            let preview = document.getElementById('preview-logo');
            if(!preview){
                // Jika sebelumnya tidak ada logo, ganti placeholder
                document.getElementById('placeholder-logo').innerHTML = `<img src="${URL.createObjectURL(file)}" class="w-full h-full object-contain p-2">`;
            } else {
                preview.src = URL.createObjectURL(file)
            }
        }
    }
</script>

@if(session('success'))
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    toastr.success("{{ session('success') }}", "Berhasil!", {
        progressBar: true,
        positionClass: "toast-top-right",
    });
</script>
@endif

@endsection