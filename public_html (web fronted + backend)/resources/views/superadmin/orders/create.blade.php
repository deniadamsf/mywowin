@extends('superadmin.layouts.master')

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
                    <a href="{{ route('superadmin.dashboard') }}" class="text-gray-700 hover:text-blue-600 flex items-center">
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
                    <a href="{{ route('superadmin.orders.index') }}" class="text-gray-700 hover:text-blue-600">Master Order</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-500">Tambah Order</span>
                </li>
            </ol>
        </nav>

        <!-- Title Section -->
        <h1 class="text-2xl font-bold text-gray-800">Tambah order</h1>
        <p class="text-gray-600 mb-4">Halaman ini merupakan halaman untuk menambahkan order baru.</p>

        <!-- Tab Switcher -->
        <div class="flex gap-4 mt-4">
            <a href="{{ route('superadmin.dashboard') }}" class="text-sm font-medium text-blue-600 border border-blue-300 px-3 py-1 rounded hover:bg-blue-50">
                Dashboard
            </a>
            <span class="text-gray-400">|</span>
            <a href="{{ route('superadmin.orders.index') }}" class="text-sm font-medium text-green-600 border border-green-300 px-3 py-1 rounded hover:bg-green-50">
                Master order
            </a>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-center mb-4">Tambah Order Baru</h2>

        <!-- ✅ Form Tambah Ilustrasi -->
        <form action="{{ route('superadmin.orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf


            <div class="mb-4">
                <label for="paid_amount" class="block font-semibold text-gray-700 mb-2">Jumlah Terbayarkan:</label>
                <input type="number" name="paid_amount" id="paid_amount" value="{{ old('paid_amount') }}" 
                    class="w-full border border-gray-300 p-2 rounded" required>
                @error('paid_amount')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            
	        <div class="mb-4">
                <label for="total" class="block font-semibold text-gray-700 mb-2">Total:</label>
                <input type="number" name="total" id="total" value="{{ old('total') }}" 
                    class="w-full border border-gray-300 p-2 rounded" required>
                @error('total')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
             </div>
             <div class="mb-4">
                <label for="status" class="block font-semibold text-gray-700 mb-2">Status:</label>
                <select name="status" id="status" class="w-full border border-gray-300 p-2 rounded" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="shipped" {{ old('status') == 'failed' ? 'selected' : '' }}>Dalam Pengiriman</option>
                    <option value="completed" {{ old('status') == 'failed' ? 'selected' : '' }}>Selesai</option>
                    <option value="canceled" {{ old('status') == 'failed' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                @error('status')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

             <div class="mb-4">
                <label for="payment_method" class="block font-semibold text-gray-700 mb-2">Payment Method:</label>
                <select name="payment_method" id="payment_method" class="w-full border border-gray-300 p-2 rounded" required>
                    <option value="">-- Pilih Payment Method --</option>
                    <option value="wa" {{ old('payment_method') == 'wa' ? 'selected' : '' }}>WhatsApp</option>
                    <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Cash On Delivery</option>
                </select>
                @error('payment_method')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            

            <div class="relative z-30 mb-4">
                <label for="alamat" class="block font-semibold text-gray-700 mb-2">Alamat:</label>
                <textarea name="alamat" id="alamat" rows="10"
                    class="w-full border border-gray-300 p-2 rounded">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="relative z-30 mb-4">
                <label for="catatan" class="block font-semibold text-gray-700 mb-2">Catatan:</label>
                <textarea name="catatan" id="catatan" rows="10"
                    class="w-full border border-gray-300 p-2 rounded">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            
        
            <style>
                /* Biarkan toolbar tampil penuh */
                .ck-editor__editable_inline {
                    min-height: 200px;
                }
            
                .ck.ck-editor {
                    z-index: 50 !important; /* pastikan tinggi */
                    position: relative !important;
                }
            
                .ck.ck-toolbar {
                    z-index: 50 !important;
                }
            
                /* Tambahan: cegah parent container menghalangi interaksi */
                .ck-editor__editable_inline:focus {
                    z-index: 50 !important;
                    position: relative !important;
                }
            </style>
            <style>
                /* Styling default isi editor */
                .ck-content ul,
                .ck-content ol {
                    padding-left: 1.5rem; /* Jarak dari kiri */
                    margin-top: 0.5rem;
                    margin-bottom: 0.5rem;
                    list-style-type: disc; /* Tipe bullet */
                }
            
                .ck-content li {
                    font-size: 1rem; /* Ukuran font bullet */
                    line-height: 1.5rem;
                    margin-bottom: 0.25rem;
                }
            </style>
            
            
            <!-- Gambar event -->
            <div class="mb-4">
                <label for="bukti_transfer" class="block font-semibold text-gray-700 mb-2">Bukti Transfer:</label>
                <input type="file" name="bukti_transfer" id="bukti_transfer" accept="image/*"
                    class="w-full border border-gray-300 p-2 rounded" required>
                @error('bukti_transfer')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <div>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Simpan
                </button>
            </div>
        </form>
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('#alamat'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo']
                })
                .catch(error => {
                    console.error(error);
                });
        
            ClassicEditor
                .create(document.querySelector('#catatan'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo']
                })
                .catch(error => {
                    console.error(error);
                });
        </script>
        
        
        

    </div>
</div>
@endsection
