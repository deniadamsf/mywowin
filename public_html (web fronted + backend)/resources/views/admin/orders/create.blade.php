@extends('admin.layouts.master')

@section('title', 'WOWINFood - Tambah Order')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- CKEditor 5 untuk text editor -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<style>
    .ck-editor__editable_inline {
        min-height: 200px;
    }

    .ck.ck-editor {
        z-index: 30 !important;
        position: relative !important;
    }

    .ck.ck-toolbar {
        z-index: 30 !important;
    }

    .ck-editor__editable_inline:focus {
        z-index: 30 !important;
        position: relative !important;
    }
    
    .ck-content ul,
    .ck-content ol {
        padding-left: 1.5rem;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
        list-style-type: disc;
    }

    .ck-content li {
        font-size: 1rem;
        line-height: 1.5rem;
        margin-bottom: 0.25rem;
    }
</style>
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
                    <a href="{{ route('admin.orders.index') }}" class="hover:text-[#16782d] transition-colors duration-200">Master Order</a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Tambah Order
                </li>
            </ol>
        </nav>

        <div class="flex items-center mb-6">
            <div class="bg-[#16782d] p-3 rounded-full mr-4 shadow-md">
                <i class="fas fa-shopping-cart text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Tambah Order</h1>
                <p class="text-gray-600">Tambahkan order baru ke dalam sistem.</p>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex gap-3 mt-4">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-[#16782d] border border-[#16782d] px-4 py-2 rounded-lg hover:bg-[#16782d]/10 transition flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-[#16782d] border border-[#16782d] px-4 py-2 rounded-lg hover:bg-[#16782d]/10 transition flex items-center">
                <i class="fas fa-list mr-2"></i> Master Order
            </a>
            <span class="text-sm font-medium text-white bg-[#16782d] px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Order
            </span>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
        <div class="flex items-center justify-center mb-6">
            <div class="bg-[#16782d]/10 p-4 rounded-full">
                <i class="fas fa-file-invoice text-2xl text-[#16782d]"></i>
            </div>
        </div>
        <h1 class="text-center font-semibold text-xl text-gray-800">INPUT DATA ORDER</h1>
        <p class="text-center text-gray-500 mb-8">Silahkan mengisi inputan dibawah ini untuk menambahkan order baru</p>
        
        <form action="{{ route('admin.orders.store') }}" method="POST" enctype="multipart/form-data" class="mx-auto max-w-4xl">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <!-- Jumlah Terbayarkan -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="paid_amount" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-money-bill-wave text-[#16782d] mr-2"></i>
                            Jumlah Terbayarkan
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                            <input type="number" name="paid_amount" id="paid_amount" value="{{ old('paid_amount') }}" required 
                                class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]"/>
                        </div>
                        @error('paid_amount')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Total -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="total" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-hand-holding-usd text-[#16782d] mr-2"></i>
                            Total Harga
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                            <input type="number" name="total" id="total" value="{{ old('total') }}" required 
                                class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]"/>
                        </div>
                        @error('total')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Bukti Transfer -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="bukti_transfer" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-image text-[#16782d] mr-2"></i>
                            Bukti Transfer
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-[#16782d] transition-colors cursor-pointer bg-white">
                            <input type="file" name="bukti_transfer" id="bukti_transfer" accept="image/*" required 
                                class="hidden" onchange="updateFileLabel(this)"/>
                            <label for="bukti_transfer" class="cursor-pointer block">
                                <div class="mx-auto w-12 h-12 bg-[#16782d]/10 rounded-full flex items-center justify-center mb-2">
                                    <i class="fas fa-upload text-xl text-[#16782d]"></i>
                                </div>
                                <p class="text-gray-700 font-medium" id="fileLabel">Klik untuk upload bukti transfer</p>
                            </label>
                        </div>
                        @error('bukti_transfer')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Kolom Kanan -->
                <div class="space-y-4">
                    <!-- Status -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="status" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-tag text-[#16782d] mr-2"></i>
                            Status Order
                        </label>
                        <select name="status" id="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]">
                            <option value="">-- Pilih Status --</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                            <option value="shipped" {{ old('status') == 'shipped' ? 'selected' : '' }}>Dalam Pengiriman</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('status')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Payment Method -->
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label for="payment_method" class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-credit-card text-[#16782d] mr-2"></i>
                            Metode Pembayaran
                        </label>
                        <select name="payment_method" id="payment_method" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]">
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="wa" {{ old('payment_method') == 'wa' ? 'selected' : '' }}>WhatsApp</option>
                            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Cash On Delivery</option>
                        </select>
                        @error('payment_method')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Alamat -->
            <div class="mt-6 bg-gray-50 p-4 rounded-xl">
                <label for="alamat" class="block text-gray-700 font-medium mb-2 flex items-center">
                    <i class="fas fa-map-marker-alt text-[#16782d] mr-2"></i>
                    Alamat Pengiriman
                </label>
                <textarea id="alamat" name="alamat" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Catatan -->
            <div class="mt-6 bg-gray-50 p-4 rounded-xl">
                <label for="catatan" class="block text-gray-700 font-medium mb-2 flex items-center">
                    <i class="fas fa-sticky-note text-[#16782d] mr-2"></i>
                    Catatan Order
                </label>
                <textarea id="catatan" name="catatan" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
        
            <!-- Submit Button -->
            <div class="flex justify-center mt-8">
                <button type="submit" class="bg-[#16782d] hover:bg-[#135e24] text-white font-medium py-3 px-10 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-[#16782d] focus:ring-offset-2 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Order
                </button>
            </div>
        </form>
    </div>    
</div>

<script>
    function updateFileLabel(input) {
        const fileLabel = document.getElementById('fileLabel');
        if (input.files.length > 0) {
            fileLabel.textContent = input.files[0].name;
        } else {
            fileLabel.textContent = 'Klik untuk upload bukti transfer';
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        ClassicEditor
            .create(document.querySelector('#alamat'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo']
            })
            .catch(error => {
                console.error('Error initializing CKEditor for address:', error);
            });
            
        ClassicEditor
            .create(document.querySelector('#catatan'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo']
            })
            .catch(error => {
                console.error('Error initializing CKEditor for notes:', error);
            });
    });
</script>
@endsection