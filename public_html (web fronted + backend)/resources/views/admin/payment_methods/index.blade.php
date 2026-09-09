@php
    $isSuperAdmin = request()->is('superadmin*') || (auth()->check() && in_array(auth()->user()->role ?? '', ['super_admin', 'superadmin']));
    $prefix = $isSuperAdmin ? 'superadmin.' : 'admin.';
@endphp

@extends($isSuperAdmin ? 'superadmin.layouts.master' : 'admin.layouts.master')

@section('title', 'WOWINFood - Kelola Metode Pembayaran')

@section('head')
<link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-white to-green-50 p-6 rounded-2xl shadow-lg border border-gray-200 mb-6">
        <nav class="flex text-sm mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route($prefix . 'dashboard') }}" class="text-gray-700 hover:text-[#16782d] flex items-center transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                </li>
                <li class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" />
                    </svg>
                    Metode Pembayaran
                </li>
            </ol>
        </nav>

        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center">
                <div class="bg-[#16782d] p-3 rounded-2xl mr-4 shadow-md text-white">
                    <i class="fas fa-wallet text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-1">Pengaturan Metode Pembayaran Modular</h1>
                    <p class="text-gray-600 text-sm">Aktifkan atau nonaktifkan metode pembayaran, nomor rekening bank, dan nomor WhatsApp admin untuk aplikasi mobile dan web.</p>
                </div>
            </div>
            <div class="bg-emerald-50 border border-emerald-200 px-4 py-2 rounded-xl flex items-center gap-2 text-emerald-800 text-xs font-semibold">
                <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                <span>Tersambung Otomatis ke Flutter & Web Checkout</span>
            </div>
        </div>
    </div>

    <!-- Alert Sukses / Error -->
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-300 text-emerald-800 px-5 py-4 rounded-xl shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i class="fas fa-times"></i></button>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-300 text-red-800 px-5 py-4 rounded-xl shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>
    </div>
    @endif

    @php
        $transferMethod = $paymentMethods->firstWhere('code', 'transfer');
        $waMethod = $paymentMethods->firstWhere('code', 'wa');
        $codMethod = $paymentMethods->firstWhere('code', 'cod');
        $bankAccounts = $transferMethod ? ($transferMethod->config['bank_accounts'] ?? []) : [];
        $waNumber = $waMethod ? ($waMethod->config['phone_number'] ?? '6281216301220') : '6281216301220';
        $toggleRoute = route($prefix . 'payment_methods.toggle');
        $updateWaRoute = route($prefix . 'payment_methods.update_wa');
        $addBankRoute = route($prefix . 'payment_methods.bank_accounts.add');
        $updateVoucherRoute = route($prefix . 'payment_methods.shipping_voucher.update');
        $toggleVoucherRoute = route($prefix . 'payment_methods.shipping_voucher.toggle');
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- KARTU 1: TRANSFER BANK -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden flex flex-col">
            <div class="p-6 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-700 shadow-sm">
                        <i class="fas fa-building-columns text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Transfer Bank</h2>
                        <p class="text-xs text-gray-500">Instruksi rekening BCA, BRI, dll</p>
                    </div>
                </div>
                
                <!-- Toggle Form -->
                <form action="{{ $toggleRoute }}" method="POST" class="toggle-payment-form">
                    @csrf
                    <input type="hidden" name="code" value="transfer">
                    <button type="submit" class="toggle-btn relative inline-flex items-center h-7 rounded-full w-14 transition-all focus:outline-none {{ ($transferMethod && $transferMethod->is_active) ? 'bg-[#16782d]' : 'bg-gray-300' }}">
                        <span class="toggle-thumb inline-block w-5 h-5 transform bg-white rounded-full transition-transform {{ ($transferMethod && $transferMethod->is_active) ? 'translate-x-8' : 'translate-x-1' }}"></span>
                    </button>
                    <span class="toggle-label block text-right text-[10px] font-bold mt-1 {{ ($transferMethod && $transferMethod->is_active) ? 'text-green-700' : 'text-gray-400' }}">
                        {{ ($transferMethod && $transferMethod->is_active) ? 'AKTIF' : 'NONAKTIF' }}
                    </span>
                </form>
            </div>

            <div class="p-6 flex-1 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                        <i class="fas fa-credit-card text-gray-400"></i> Daftar Rekening Penerima
                    </h3>
                    <button onclick="document.getElementById('modalAddBank').classList.remove('hidden')" class="px-3 py-1.5 bg-[#16782d] hover:bg-green-800 text-white rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                        <i class="fas fa-plus"></i> Tambah Bank
                    </button>
                </div>

                @if(count($bankAccounts) > 0)
                <div class="space-y-3 flex-1">
                    @foreach($bankAccounts as $acc)
                    @php
                        $isAccActive = $acc['is_active'] ?? true;
                        $toggleBankRoute = route($prefix . 'payment_methods.bank_accounts.toggle', $acc['id'] ?? '');
                        $deleteBankRoute = route($prefix . 'payment_methods.bank_accounts.delete', $acc['id'] ?? '');
                    @endphp
                    <div class="p-4 rounded-xl border {{ $isAccActive ? 'border-blue-200 bg-blue-50/40' : 'border-gray-200 bg-gray-50 opacity-60' }} flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-extrabold text-sm text-gray-800">{{ $acc['bank_name'] ?? 'Bank' }}</span>
                                @if($isAccActive)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700">Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-200 text-gray-600">Off</span>
                                @endif
                            </div>
                            <p class="text-xs font-mono font-bold text-blue-900 tracking-wider">{{ $acc['account_number'] ?? '-' }}</p>
                            <p class="text-[11px] text-gray-500">a.n. {{ $acc['account_holder'] ?? '-' }}</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <form action="{{ $toggleBankRoute }}" method="POST">
                                @csrf
                                <button type="submit" title="{{ $isAccActive ? 'Nonaktifkan Rekening' : 'Aktifkan Rekening' }}" class="p-2 rounded-lg border {{ $isAccActive ? 'border-amber-300 text-amber-700 bg-amber-50 hover:bg-amber-100' : 'border-green-300 text-green-700 bg-green-50 hover:bg-green-100' }} text-xs font-bold transition">
                                    <i class="fas {{ $isAccActive ? 'fa-pause' : 'fa-play' }}"></i>
                                </button>
                            </form>

                            <form action="{{ $deleteBankRoute }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus rekening {{ $acc['bank_name'] }} ini?')">
                                @csrf
                                <button type="submit" title="Hapus Rekening" class="p-2 rounded-lg border border-red-200 text-red-600 bg-red-50 hover:bg-red-100 text-xs transition">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-6 text-center border-2 border-dashed border-gray-200 rounded-xl flex-1 flex flex-col items-center justify-center">
                    <i class="fas fa-university text-3xl text-gray-300 mb-2"></i>
                    <p class="text-xs text-gray-500">Belum ada rekening bank yang terdaftar.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- KARTU 2: PESAN VIA WHATSAPP -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden flex flex-col">
            <div class="p-6 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center text-green-700 shadow-sm">
                        <i class="fab fa-whatsapp text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Pesan via WhatsApp</h2>
                        <p class="text-xs text-gray-500">Pemesanan langsung terhubung ke admin</p>
                    </div>
                </div>

                <!-- Toggle Form -->
                <form action="{{ $toggleRoute }}" method="POST" class="toggle-payment-form">
                    @csrf
                    <input type="hidden" name="code" value="wa">
                    <button type="submit" class="toggle-btn relative inline-flex items-center h-7 rounded-full w-14 transition-all focus:outline-none {{ ($waMethod && $waMethod->is_active) ? 'bg-[#16782d]' : 'bg-gray-300' }}">
                        <span class="toggle-thumb inline-block w-5 h-5 transform bg-white rounded-full transition-transform {{ ($waMethod && $waMethod->is_active) ? 'translate-x-8' : 'translate-x-1' }}"></span>
                    </button>
                    <span class="toggle-label block text-right text-[10px] font-bold mt-1 {{ ($waMethod && $waMethod->is_active) ? 'text-green-700' : 'text-gray-400' }}">
                        {{ ($waMethod && $waMethod->is_active) ? 'AKTIF' : 'NONAKTIF' }}
                    </span>
                </form>
            </div>

            <div class="p-6 flex-1 flex flex-col justify-between">
                <form action="{{ $updateWaRoute }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nomor WhatsApp Admin Wowin</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-sm font-bold">
                                <i class="fab fa-whatsapp text-green-600 mr-1.5"></i> +
                            </span>
                            <input type="text" name="phone_number" value="{{ $waNumber }}" 
                                class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d] transition"
                                placeholder="6281216301220" required>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-1">Gunakan kode negara (contoh: <b>6281216301220</b>). Nomor ini akan menerima pesan rincian pesanan dari pembeli.</p>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="px-4 py-2.5 bg-[#16782d] hover:bg-green-800 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan Nomor WA
                        </button>
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-bold transition flex items-center gap-2">
                            <i class="fas fa-external-link-alt"></i> Tes Chat WA
                        </a>
                    </div>
                </form>

                <!-- KARTU 3: CASH ON DELIVERY (COD) -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between p-4 rounded-xl border border-gray-200 bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center text-orange-700">
                                <i class="fas fa-hand-holding-dollar"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-800">Cash on Delivery (COD)</h3>
                                <p class="text-[11px] text-gray-500">Bayar tunai ke kurir saat barang tiba</p>
                            </div>
                        </div>

                        <form action="{{ $toggleRoute }}" method="POST" class="toggle-payment-form">
                            @csrf
                            <input type="hidden" name="code" value="cod">
                            <button type="submit" class="toggle-btn relative inline-flex items-center h-6 rounded-full w-12 transition-all focus:outline-none {{ ($codMethod && $codMethod->is_active) ? 'bg-[#16782d]' : 'bg-gray-300' }}">
                                <span class="toggle-thumb inline-block w-4 h-4 transform bg-white rounded-full transition-transform {{ ($codMethod && $codMethod->is_active) ? 'translate-x-7' : 'translate-x-1' }}"></span>
                            </button>
                            <span class="toggle-label block text-right text-[10px] font-bold mt-0.5 {{ ($codMethod && $codMethod->is_active) ? 'text-green-700' : 'text-gray-400' }}">
                                {{ ($codMethod && $codMethod->is_active) ? 'AKTIF' : 'NONAKTIF' }}
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- KARTU 4: VOUCHER DISKON ONGKIR J&T EXPRESS -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden flex flex-col lg:col-span-2">
            <div class="p-6 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-amber-200 flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center text-white shadow-sm">
                        <i class="fas fa-ticket-alt text-lg"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-bold text-gray-800">Voucher Diskon Ongkir (J&T Express)</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">PROMO OTOMATIS</span>
                        </div>
                        <p class="text-xs text-gray-600">Atur tarif ongkir dasar, minimal belanja, dan diskon gratis ongkir otomatis untuk pembeli.</p>
                    </div>
                </div>
                
                <!-- Toggle Form Voucher -->
                <form action="{{ $toggleVoucherRoute }}" method="POST" class="toggle-payment-form">
                    @csrf
                    <button type="submit" class="toggle-btn relative inline-flex items-center h-7 rounded-full w-14 transition-all focus:outline-none {{ ($shippingVoucher && $shippingVoucher->is_active) ? 'bg-[#16782d]' : 'bg-gray-300' }}">
                        <span class="toggle-thumb inline-block w-5 h-5 transform bg-white rounded-full transition-transform {{ ($shippingVoucher && $shippingVoucher->is_active) ? 'translate-x-8' : 'translate-x-1' }}"></span>
                    </button>
                    <span class="toggle-label block text-right text-[10px] font-bold mt-1 {{ ($shippingVoucher && $shippingVoucher->is_active) ? 'text-green-700' : 'text-gray-400' }}">
                        {{ ($shippingVoucher && $shippingVoucher->is_active) ? 'AKTIF' : 'NONAKTIF' }}
                    </span>
                </form>
            </div>

            <div class="p-6 flex-1 bg-white">
                <form action="{{ $updateVoucherRoute }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <!-- 1. Tarif J&T Jawara Jatim + Madura -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <label class="block text-xs font-bold text-gray-700 mb-1 flex items-center gap-1.5">
                                <i class="fas fa-truck text-[#16782d]"></i> Tarif Jatim & Madura
                            </label>
                            <div class="relative mt-2">
                                <span class="absolute left-3.5 top-2.5 text-xs font-bold text-gray-500">Rp</span>
                                <input type="number" step="100" name="base_rate_per_kg" value="{{ old('base_rate_per_kg', $shippingVoucher->base_rate_per_kg ?? 4500) }}" class="w-full pl-9 pr-12 py-2 text-sm font-bold border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]" required>
                                <span class="absolute right-3.5 top-2.5 text-xs text-gray-400 font-semibold">/ Kg</span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-2">Paket VIP J&T Jawara khusus Jawa Timur & Madura.</p>
                        </div>

                        <!-- 2. Tarif J&T Jawara Sisa Pulau Jawa -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <label class="block text-xs font-bold text-gray-700 mb-1 flex items-center gap-1.5">
                                <i class="fas fa-map-marked-alt text-blue-600"></i> Tarif Pulau Jawa Lainnya
                            </label>
                            <div class="relative mt-2">
                                <span class="absolute left-3.5 top-2.5 text-xs font-bold text-gray-500">Rp</span>
                                <input type="number" step="100" name="rate_jawa_non_jatim" value="{{ old('rate_jawa_non_jatim', $shippingVoucher->rate_jawa_non_jatim ?? 9500) }}" class="w-full pl-9 pr-12 py-2 text-sm font-bold border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]" required>
                                <span class="absolute right-3.5 top-2.5 text-xs text-gray-400 font-semibold">/ Kg</span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-2">Jateng, DIY, Jabar, DKI Jakarta, Banten.</p>
                        </div>

                        <!-- 3. Minimal Belanja -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <label class="block text-xs font-bold text-gray-700 mb-1 flex items-center gap-1.5">
                                <i class="fas fa-shopping-basket text-amber-600"></i> Syarat Min. Belanja
                            </label>
                            <div class="relative mt-2">
                                <span class="absolute left-3.5 top-2.5 text-xs font-bold text-gray-500">Rp</span>
                                <input type="number" step="500" name="min_purchase" value="{{ old('min_purchase', $shippingVoucher->min_purchase ?? 10000) }}" class="w-full pl-9 pr-4 py-2 text-sm font-bold border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]" required>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-2">Belanja produk minimum agar voucher aktif otomatis.</p>
                        </div>

                        <!-- 4. Potongan Ongkir -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <label class="block text-xs font-bold text-gray-700 mb-1 flex items-center gap-1.5">
                                <i class="fas fa-tags text-green-600"></i> Potongan Diskon Ongkir
                            </label>
                            <div class="relative mt-2">
                                <span class="absolute left-3.5 top-2.5 text-xs font-bold text-gray-500">Rp</span>
                                <input type="number" step="100" name="discount_amount" value="{{ old('discount_amount', $shippingVoucher->discount_amount ?? 4500) }}" class="w-full pl-9 pr-4 py-2 text-sm font-bold border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]" required>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-2">Maksimal potongan ongkir (Rp 4.500 = Gratis Ongkir 1 Kg Jatim).</p>
                        </div>
                    </div>

                    <!-- Simulasi & Penjelasan -->
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-5 flex items-start gap-3">
                        <div class="text-emerald-600 text-lg mt-0.5"><i class="fas fa-lightbulb"></i></div>
                        <div class="text-xs text-emerald-800 leading-relaxed">
                            <strong>Simulasi Otomatis untuk Pembeli (Paket VIP J&T Jawara):</strong>
                            <ul class="list-disc ml-4 mt-1 space-y-0.5">
                                <li><strong>Jatim + Madura ($\le 1\text{ Kg}$):</strong> Ongkir Rp 4.500 - Diskon Rp 4.500 = <strong>Rp 0 (GRATIS ONGKIR 100%)</strong> jika belanja $\ge$ Rp 10.000.</li>
                                <li><strong>Pulau Jawa Lainnya ($\le 1\text{ Kg}$):</strong> Ongkir Rp 9.500 - Diskon Rp 4.500 = <strong>Rp 5.000</strong> jika belanja $\ge$ Rp 10.000 (Subsidi 1 Kg pertama).</li>
                                <li><strong>Paket Kartonan Jatim (misal 11 Kg):</strong> Ongkir Rp 49.500 - Diskon Rp 4.500 = <strong>Rp 45.000</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-[#16782d] text-white rounded-xl text-xs font-bold hover:bg-green-800 transition shadow-sm flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan Pengaturan Voucher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH REKENING BANK -->
<div id="modalAddBank" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 animate__animated animate__fadeInUp">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
            <h3 class="font-bold text-base text-gray-800 flex items-center gap-2">
                <i class="fas fa-plus-circle text-[#16782d]"></i> Tambah Rekening Bank
            </h3>
            <button onclick="document.getElementById('modalAddBank').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <form action="{{ $addBankRoute }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Bank</label>
                <input type="text" name="bank_name" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]" placeholder="Contoh: Bank BCA, Bank BRI, Bank Mandiri" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Nomor Rekening</label>
                <input type="text" name="account_number" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-sm font-mono focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]" placeholder="Contoh: 0891234567 atau 0123-01-000456-53-0" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Atas Nama Pemilik Rekening</label>
                <input type="text" name="account_holder" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#16782d] focus:border-[#16782d]" placeholder="Contoh: PT WOWIN PURNOMO PUTERA" required>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3">
                <button type="button" onclick="document.getElementById('modalAddBank').classList.add('hidden')" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-[#16782d] text-white rounded-xl text-xs font-bold hover:bg-green-800 transition shadow-sm">
                    Simpan Rekening
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-payment-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const button = this.querySelector('.toggle-btn');
            const thumb = this.querySelector('.toggle-thumb');
            const label = this.querySelector('.toggle-label');
            const isSmall = button.classList.contains('w-12');
            
            button.style.opacity = '0.6';
            button.style.pointerEvents = 'none';
            
            try {
                const formData = new FormData(this);
                const res = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    if (data.is_active) {
                        button.classList.remove('bg-gray-300');
                        button.classList.add('bg-[#16782d]');
                        thumb.classList.remove('translate-x-1');
                        thumb.classList.add(isSmall ? 'translate-x-7' : 'translate-x-8');
                        label.classList.remove('text-gray-400');
                        label.classList.add('text-green-700');
                        label.textContent = 'AKTIF';
                    } else {
                        button.classList.remove('bg-[#16782d]');
                        button.classList.add('bg-gray-300');
                        thumb.classList.remove('translate-x-8', 'translate-x-7');
                        thumb.classList.add('translate-x-1');
                        label.classList.remove('text-green-700');
                        label.classList.add('text-gray-400');
                        label.textContent = 'NONAKTIF';
                    }
                    if (typeof toastr !== 'undefined') {
                        toastr.options = { closeButton: true, progressBar: true, positionClass: 'toast-top-right', timeOut: 3000 };
                        toastr.success(data.message);
                    }
                } else {
                    window.location.reload();
                }
            } catch (err) {
                this.submit();
            } finally {
                button.style.opacity = '1';
                button.style.pointerEvents = 'auto';
            }
        });
    });
});
</script>
@endsection
