@extends('public.layouts.app')

@section('head')
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
<meta name="description" content="WOWINFood - Temukan berbagai produk berkualitas dengan harga terbaik">
    <style>
        /* Impor Font Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        /* Terapkan Font Poppins */
        .container {
            font-family: 'Poppins', sans-serif;
        }
    </style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-10 relative">
    <!-- Background Shape -->
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute w-96 h-96 bg-green-100 rounded-full blur-3xl opacity-40 top-0 left-[-100px]"></div>
        <div class="absolute w-72 h-72 bg-green-200 rounded-full blur-2xl opacity-30 bottom-10 right-[-80px]"></div>
        <div class="absolute w-60 h-60 bg-green-300 rounded-full blur-xl opacity-20 top-1/2 left-[60%] translate-x-[-50%] -translate-y-1/2"></div>
    </div>

    <!-- Header -->
    <div class="mb-12 text-center relative">
        <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 w-72 h-72 bg-green-200 opacity-30 blur-3xl rounded-full -z-10"></div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-green-700 tracking-tight">
            <span class="bg-gradient-to-r from-green-700 to-green-500 text-transparent bg-clip-text">Pesanan Saya</span>
        </h1>
        <p class="mt-3 text-gray-500 text-sm md:text-base max-w-xl mx-auto">
            Lacak dan kelola <span class="text-green-600 font-medium">semua pesanan</span> Anda dengan lebih mudah dan cepat
        </p>
        <div class="mt-6 flex justify-center">
            <div class="h-1 w-24 bg-gradient-to-r from-green-600 via-green-400 to-green-600 rounded-full animate-pulse"></div>
        </div>
    </div>

    {{-- Flash Notification Alerts --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-sm font-semibold shadow-xs">
            <i class="ri-checkbox-circle-fill text-emerald-600 text-xl shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 text-red-800 text-sm font-semibold shadow-xs">
            <i class="ri-error-warning-fill text-red-600 text-xl shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if(session('warning'))
        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-center gap-3 text-amber-800 text-sm font-semibold shadow-xs">
            <i class="ri-alert-fill text-amber-600 text-xl shrink-0"></i>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    {{-- MODAL SUKSES CHECKOUT (Selaras dengan Flow Aplikasi Flutter & Web) --}}
    @if(isset($newOrder) && $newOrder && empty($newOrder->bukti_transfer) && $newOrder->status === 'pending' && $newOrder->payment_status === 'pending')
    <div id="checkoutSuccessModal" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 relative my-8 border border-gray-100 max-h-[90vh] overflow-y-auto">
            <!-- Close Button -->
            <button onclick="closeCheckoutSuccessModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-700 flex items-center justify-center transition cursor-pointer" title="Tutup">
                <i class="ri-close-line text-lg"></i>
            </button>

            <div class="text-center">
                <!-- Icon Sukses -->
                <div class="w-14 h-14 mx-auto bg-green-100 border-2 border-green-300 rounded-full flex items-center justify-center mb-2.5">
                    <i class="ri-checkbox-circle-fill text-[#16782d] text-2xl"></i>
                </div>

                <h3 class="text-xl font-black text-gray-900">Pesanan Berhasil Dibuat! 🎉</h3>
                <p class="text-xs font-semibold text-gray-500 mt-1">Nomor Pesanan: <span class="font-mono text-gray-800 font-bold">#{{ $newOrder->invoice_number }}</span></p>

                <!-- Status Badge -->
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 border border-amber-200 rounded-full text-[11px] font-bold text-amber-800 mt-2.5">
                    <i class="ri-hourglass-top-line text-amber-600"></i> STATUS: MENUNGGU PEMBAYARAN
                </div>

                <!-- Total Tagihan -->
                <div class="mt-3.5 p-3.5 bg-gray-50 border border-gray-200 rounded-2xl flex items-center justify-between">
                    <span class="text-xs text-gray-600 font-medium">Total Tagihan Transfer</span>
                    <span class="text-lg font-black text-amber-600">Rp {{ number_format($newOrder->total - ($newOrder->total_potongan_retur ?? 0), 0, ',', '.') }}</span>
                </div>

                @if($newOrder->payment_method === 'transfer')
                    <!-- Rekening Tujuan Transfer Card -->
                    <div class="mt-3.5 p-3.5 bg-blue-50/90 border border-blue-200 rounded-2xl text-left">
                        <div class="flex items-center gap-2 text-blue-900 font-bold text-xs mb-2">
                            <i class="ri-bank-card-line text-blue-700 text-base"></i>
                            <span>Rekening Resmi Wowin (BCA / BRI):</span>
                        </div>

                        <div class="space-y-2">
                            @foreach($bankAccounts as $bank)
                                <div class="flex items-center justify-between p-2.5 bg-white rounded-xl border border-blue-100 shadow-2xs">
                                    <div>
                                        <span class="text-[11px] font-bold text-blue-800 block">{{ $bank['bank_name'] ?? 'Bank BCA' }}</span>
                                        <span class="text-xs font-black text-gray-900 tracking-wider font-mono select-all">{{ $bank['account_number'] ?? '-' }}</span>
                                        <span class="text-[10px] text-gray-500 block">{{ $bank['account_holder'] ?? 'PT WOWIN PURNOMO PUTERA' }}</span>
                                    </div>
                                    <button type="button" onclick="copyToClipboard('{{ $bank['account_number'] ?? '' }}', 'Nomor rekening')" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-bold transition flex items-center gap-1 cursor-pointer">
                                        <i class="ri-file-copy-line text-xs"></i> Salin
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <p class="text-[10px] text-blue-700 font-medium mt-2.5 leading-relaxed">
                            ⏳ <b>Batas Waktu: 24 Jam.</b> Silakan transfer nominal tepat di atas, kemudian unggah foto bukti transfer di bawah ini.
                        </p>
                    </div>

                    <!-- Tempat Upload Bukti Transfer Langsung -->
                    <form action="{{ route('public.trackings.upload-proof', ['id' => $newOrder->id]) }}" method="POST" enctype="multipart/form-data" class="mt-4 text-left space-y-3" onsubmit="handleDirectUploadSubmit(event)">
                        @csrf
                        <label class="block text-xs font-bold text-gray-700">Unggah Bukti Transfer Sekarang <span class="text-red-500">*</span></label>
                        <div onclick="document.getElementById('directProofFileInput').click()" class="border-2 border-dashed border-gray-300 hover:border-green-600 rounded-2xl p-4 text-center cursor-pointer transition bg-gray-50/60 hover:bg-green-50/40 group">
                            <input type="file" id="directProofFileInput" name="bukti_transfer" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewDirectProofImage(event)" required>
                            
                            <div id="directUploadPrompt">
                                <i class="ri-image-add-line text-3xl text-gray-400 group-hover:text-green-600 transition mb-1 block"></i>
                                <span class="text-xs font-semibold text-gray-700 block">Klik di sini untuk memilih foto bukti transfer</span>
                                <span class="text-[10px] text-gray-400 block mt-0.5">Format: JPG, JPEG, PNG, WEBP (Maks. 5MB)</span>
                            </div>

                            <div id="directProofPreviewContainer" class="hidden">
                                <img id="directProofPreviewImage" src="" alt="Preview Bukti" class="max-h-40 mx-auto rounded-xl object-contain shadow-sm border border-gray-200 mb-2">
                                <span class="text-[11px] text-green-700 font-bold block"><i class="ri-checkbox-circle-fill"></i> Foto dipilih</span>
                                <span class="text-[10px] text-gray-400 hover:text-red-500 underline mt-1 inline-block">Klik untuk mengganti foto</span>
                            </div>
                        </div>

                        <button type="submit" id="directSubmitProofBtn" class="w-full py-3 bg-[#16782d] hover:bg-green-800 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center justify-center gap-2 cursor-pointer">
                            <i class="ri-send-plane-fill text-sm"></i> 
                            <span id="directSubmitProofBtnText">Kirim Bukti Pembayaran</span>
                        </button>
                    </form>

                    <!-- Bantuan WhatsApp & Tutup -->
                    <div class="mt-3 pt-3 border-t border-gray-100 space-y-2">
                        @php
                            $waNum = $officialWaNumber ?? '62812106600';
                            $cleanWa = preg_replace('/[^0-9]/', '', $waNum);
                            if (str_starts_with($cleanWa, '0')) { $cleanWa = '62' . substr($cleanWa, 1); }
                            $waTextTransfer = "Halo Admin Wowin Food, saya ingin konfirmasi pesanan saya:\n\n"
                                            . "• No. Invoice: #" . $newOrder->invoice_number . "\n"
                                            . "• Nama: " . ($newOrder->user->nama_lengkap ?? 'Pelanggan') . "\n"
                                            . "• Total: Rp " . number_format($newOrder->total - ($newOrder->total_potongan_retur ?? 0), 0, ',', '.') . "\n"
                                            . "• Metode: TRANSFER BANK\n\n"
                                            . "Mohon bantuan untuk diproses. Terima kasih!";
                        @endphp
                        <a href="https://wa.me/{{ $cleanWa }}?text={{ urlencode($waTextTransfer) }}" target="_blank"
                           class="w-full py-2 bg-white hover:bg-emerald-50 text-emerald-700 border border-emerald-300 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                            <i class="ri-whatsapp-line text-sm text-emerald-600"></i> Butuh Bantuan? Hubungi Admin WhatsApp
                        </a>

                        <button type="button" onclick="closeCheckoutSuccessModal()" class="w-full py-2 text-gray-500 hover:text-gray-700 text-xs font-semibold">
                            Upload Nanti (Lihat Daftar Pesanan)
                        </button>
                    </div>
                @else
                    <!-- Metode WA atau lainnya -->
                    <div class="mt-4 p-4 bg-emerald-50/80 border border-emerald-200 rounded-2xl text-left">
                        <p class="text-xs text-gray-700 leading-relaxed">
                            Pesanan Anda tersimpan di sistem kami. Silakan klik tombol di bawah untuk konfirmasi langsung ke Admin WhatsApp Wowin.
                        </p>
                    </div>

                    <div class="mt-5 space-y-2.5">
                        @php
                            $waNum = $officialWaNumber ?? '62812106600';
                            $cleanWa = preg_replace('/[^0-9]/', '', $waNum);
                            if (str_starts_with($cleanWa, '0')) { $cleanWa = '62' . substr($cleanWa, 1); }
                            $waTextOrder = "Halo Admin Wowin Food, saya baru saja membuat pesanan:\n\n"
                                         . "• No. Invoice: #" . $newOrder->invoice_number . "\n"
                                         . "• Nama: " . ($newOrder->user->nama_lengkap ?? 'Pelanggan') . "\n"
                                         . "• Total: Rp " . number_format($newOrder->total - ($newOrder->total_potongan_retur ?? 0), 0, ',', '.') . "\n"
                                         . "• Metode: PESAN VIA WHATSAPP\n\n"
                                         . "Mohon segera diproses. Terima kasih!";
                        @endphp
                        <a href="https://wa.me/{{ $cleanWa }}?text={{ urlencode($waTextOrder) }}" target="_blank"
                           class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                            <i class="ri-whatsapp-line text-lg"></i> Kirim Pesanan ke WhatsApp
                        </a>

                        <button type="button" onclick="closeCheckoutSuccessModal()" class="w-full py-2 text-gray-500 hover:text-gray-700 text-xs font-semibold">
                            Tutup & Lihat Riwayat Pesanan
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Filter & Action -->
<div class="mb-8">
    {{-- 1. TAMPILAN DESKTOP --}}
    <div class="hidden md:flex flex-row justify-between items-center gap-4">
        <div class="flex flex-wrap gap-2">
            @php $status = request('status'); @endphp

            <a href="{{ route('public.trackings.index') }}"
               class="px-4 py-2 rounded-full text-xsx font-medium transition shadow
                      {{ !$status ? 'bg-green-700 text-white hover:bg-green-800' : 'bg-white border border-gray-300 text-gray-600 hover:bg-gray-100' }}">
                Semua
            </a>

            @foreach ([
                'pending' => 'Belum Bayar',
                'shipped' => 'Dikirim',
                'completed' => 'Selesai',
                'canceled' => 'Dibatalkan'
            ] as $key => $label)
                <a href="{{ route('public.trackings.index', ['status' => $key]) }}"
                   class="px-4 py-2 rounded-full text-sm font-medium transition shadow
                          {{ $status === $key ? 'bg-green-700 text-white hover:bg-green-800' : 'bg-white border border-gray-300 text-gray-600 hover:bg-gray-100' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="flex-shrink-0">
            <button onclick="showLoadingAndRedirect('{{ route('products') }}')" 
                class="px-5 py-2.5 bg-green-700 text-white rounded-full text-sm font-medium flex items-center gap-2 hover:bg-green-800 shadow transition">
                <i class="fas fa-plus text-sm"></i> Tambah Pesanan
            </button>
        </div>
    </div>

    {{-- 2. TAMPILAN MOBILE --}}
    <div class="flex md:hidden flex-row justify-between items-center gap-3" x-data="{ open: false }">
        @php 
            $labels = [
                'pending' => 'Belum Bayar',
                'shipped' => 'Dikirim',
                'completed' => 'Selesai',
                'canceled' => 'Dibatalkan'
            ];
            $currentLabel = $status && isset($labels[$status]) ? $labels[$status] : 'Semua Pesanan';
        @endphp

        <div class="relative flex-1">
            <button @click="open = !open" 
                class="w-full flex items-center justify-between px-4 py-2.5 bg-white border border-gray-200 rounded-2xl shadow-sm text-sm font-medium text-gray-700">
                <span class="truncate">{{ $currentLabel }}</span>
                <svg class="w-4 h-4 ml-2 transition-transform duration-200 text-gray-400" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="open" 
                 x-cloak
                 @click.away="open = false"
                 x-transition
                 class="absolute left-0 right-0 mt-2 z-50 bg-white border border-gray-100 rounded-2xl shadow-xl overflow-hidden">
                <a href="{{ route('public.trackings.index') }}" 
                   class="block px-5 py-3 text-sm {{ !$status ? 'bg-green-50 text-green-700 font-bold' : 'text-gray-600' }}"> Semua </a>
                @foreach ($labels as $key => $label)
                    <a href="{{ route('public.trackings.index', ['status' => $key]) }}" 
                       class="block px-5 py-3 text-sm {{ $status === $key ? 'bg-green-50 text-green-700 font-bold' : 'text-gray-600' }} border-t border-gray-50">
                       {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="flex-shrink-0">
            <button onclick="showLoadingAndRedirect('{{ route('products') }}')" 
                class="bg-[#16782d] text-white rounded-2xl shadow-lg w-[46px] h-[46px] flex items-center justify-center active:scale-90 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Modal Loading -->
<div id="loadingModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg p-6 flex flex-col items-center">
        <svg class="animate-spin h-8 w-8 text-green-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
        </svg>
        <p class="text-gray-700 font-medium">Sedang memuat, harap tunggu...</p>
    </div>
</div>

<script>
    function showLoadingAndRedirect(url) {
        // Tampilkan modal loading
        document.getElementById('loadingModal').classList.remove('hidden');

        // Redirect setelah 1.5 detik
        setTimeout(() => {
            window.location.href = url;
        }, 1500);
    }
</script>



    </div>

    <!-- Search -->
    <div class="mb-6 md:mb-10">
    <div class="relative group">
        <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base md:text-lg transition-colors group-focus-within:text-green-600"></i>
        
        <input type="text" 
            placeholder="Cari Toko, Produk, atau Pesanan..." 
            class="w-full pl-11 pr-4 py-2 md:py-3 rounded-full border border-gray-200 text-xs md:text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition-all">
    </div>
</div>

   <div class="space-y-6">
        @foreach ($orders as $order)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
           <div class="flex flex-row items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50/30 gap-2">
                <div class="flex flex-col">
                    <span class="text-[9px] uppercase font-bold text-gray-400 leading-none mb-1">Nomor Pesanan</span>
                    <span class="text-[11px] md:text-sm font-bold text-gray-800 tracking-tight">#{{ $order->invoice_number }}</span>
                </div>
               <div class="flex-shrink-0">
                    @php
                        $payStatus = $order->payment_status ?? 'pending';
                        $hasProof = !empty($order->bukti_transfer);
                        $isTransfer = ($order->payment_method === 'transfer');
                    @endphp

                    @if($order->status === 'canceled')
                        <div class="text-[10px] md:text-xs text-red-700 font-bold bg-red-50 px-2.5 py-1 rounded-lg border border-red-200 flex items-center gap-1">
                            <i class="ri-close-circle-line"></i> Dibatalkan
                        </div>
                    @elseif($payStatus === 'paid' || $order->status === 'paid' || $order->status === 'completed')
                        <div class="text-[10px] md:text-xs text-green-700 font-bold bg-green-50 px-2.5 py-1 rounded-lg border border-green-200 shadow-2xs flex items-center gap-1">
                            <i class="ri-checkbox-circle-fill"></i> Sudah Dibayar
                        </div>
                    @elseif($payStatus === 'waiting_confirmation' || ($hasProof && $payStatus !== 'rejected'))
                        <div class="text-[10px] md:text-xs text-blue-700 font-bold bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200 shadow-2xs flex items-center gap-1">
                            <i class="ri-time-line"></i> Menunggu Verifikasi Admin
                        </div>
                    @elseif($payStatus === 'rejected')
                        <div class="text-[10px] md:text-xs text-red-700 font-bold bg-red-50 px-2.5 py-1 rounded-lg border border-red-200 shadow-2xs flex items-center gap-1">
                            <i class="ri-error-warning-fill"></i> Bukti Ditolak
                        </div>
                    @else
                        <div class="text-[10px] md:text-xs text-amber-700 font-bold bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200 shadow-2xs flex items-center gap-1">
                            <i class="ri-hourglass-fill"></i> Menunggu Pembayaran
                        </div>
                    @endif
                </div>
            </div>

{{-- Loop Produk --}}
            @foreach ($order->orderItems as $item)
            <div class="flex items-center gap-4 px-4 py-3 bg-white border-b border-gray-50 last:border-0">
                <div class="relative">
                    @php
                        $isBundling = $item->bundling_id !== null;
                        $imagePath = $isBundling ? ($item->bundling?->barang_bundling ?? null) : ($item->product?->images?->first()?->image_url ?? null);
                    @endphp
                    <img src="{{ asset('storage/' . $imagePath) }}" class="w-14 h-14 rounded-xl object-cover shadow-sm border" alt="Produk">
                </div>

                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">{{ $item->product_name }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        {{-- Badge Satuan --}}
                        <span class="text-[10px] px-2 py-0.5 rounded-full {{ str_contains($item->product_name, '(Karton)') ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600' }} font-bold uppercase">
                            {{ $item->quantity }} {{ str_contains($item->product_name, '(Karton)') ? 'Karton' : 'Pcs' }}
                        </span>
                    </div>
                </div>

                <div class="text-right flex flex-col justify-center">
                    @php
                        $hargaAsli = $item->price * $item->quantity;
                        $hargaBayar = $hargaAsli - ($item->discount_amount ?? 0);
                    @endphp

                    @if($item->discount_amount > 0)
                        <p class="text-[11px] text-gray-400 line-through">Rp{{ number_format($hargaAsli, 0, ',', '.') }}</p>
                    @endif
                    
                    <p class="text-sm font-bold text-green-700">
                        Rp{{ number_format($hargaBayar, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            @endforeach

            {{-- Baris Potongan Poin --}}
            @if(($order->potongan_poin ?? 0) > 0)
                <div class="px-4 py-2 bg-amber-50 border-t border-amber-100 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-amber-800 text-[10px] font-bold uppercase tracking-wider">
                        <i class="ri-coins-line"></i> Potongan Poin ({{ number_format($order->points_used ?? 0) }} Poin)
                    </div>
                    <span class="text-amber-800 font-bold text-xs">- Rp{{ number_format($order->potongan_poin, 0, ',', '.') }}</span>
                </div>
            @endif

            {{-- Baris Potongan Retur --}}
            @if($order->total_potongan_retur > 0)
                <div class="px-4 py-2 bg-orange-50 border-t border-orange-100 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-orange-700 text-[10px] font-bold uppercase tracking-wider">
                        <i class="ri-arrow-go-back-line"></i> Potongan Nota (Retur/Botol)
                    </div>
                    <span class="text-orange-700 font-bold text-xs">- Rp{{ number_format($order->total_potongan_retur, 0, ',', '.') }}</span>
                </div>
            @endif

            {{-- Baris Info Pengiriman J&T Express Jika Resi Sudah Ada --}}
            @if(!empty($order->no_resi))
                <div class="px-4 py-2.5 bg-red-50/60 border-t border-red-100 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="bg-red-600 text-white font-black text-[9px] px-2 py-0.5 rounded">J&T EXPRESS</span>
                        <span class="text-xs font-bold text-gray-800">No. Resi: <span class="text-red-700 select-all">{{ $order->no_resi }}</span></span>
                        @if(!empty($order->jnt_des_code))
                            <span class="text-[10px] text-gray-500 font-mono bg-white px-1.5 py-0.5 rounded border">({{ $order->jnt_des_code }})</span>
                        @endif
                    </div>
                    <button type="button" onclick="openLiveTrackingModal({{ $order->id }}, '{{ $order->no_resi }}', '{{ $order->invoice_number }}')" class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-md shadow-xs transition cursor-pointer">
                        <i class="ri-radar-line animate-pulse"></i> Lacak Pengiriman
                    </button>
                </div>
            @endif

            {{-- Section Panduan Pembayaran & Status Bukti Transfer --}}
            @if($order->status !== 'canceled')
                @if($payStatus === 'rejected')
                    {{-- Kotak Peringatan Bukti Transfer Ditolak Admin --}}
                    <div class="mx-4 my-3 p-3.5 bg-red-50/90 border border-red-200 rounded-2xl">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 shrink-0 mt-0.5">
                                <i class="ri-error-warning-fill text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <span class="text-xs font-bold text-red-900 block">Bukti Transfer Pembayaran Ditolak</span>
                                @if(!empty($order->rejection_reason))
                                    <div class="text-xs text-red-800 mt-1.5 p-2 bg-white/90 rounded-xl border border-red-200 shadow-2xs leading-relaxed">
                                        <b>Alasan Admin:</b> {{ $order->rejection_reason }}
                                    </div>
                                @endif
                                <p class="text-[11px] text-red-700 mt-2">Silakan periksa kembali transaksi rekening Anda dan unggah ulang foto bukti transfer yang valid.</p>
                                <button type="button" 
                                        onclick="openUploadModal({{ $order->id }}, '{{ $order->invoice_number }}', {{ $order->total - ($order->total_potongan_retur ?? 0) }})"
                                        class="mt-2.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm cursor-pointer">
                                    <i class="ri-upload-2-line"></i> Unggah Ulang Bukti Transfer
                                </button>
                            </div>
                        </div>
                    </div>
                @elseif($payStatus === 'waiting_confirmation' || ($hasProof && $payStatus !== 'paid'))
                    {{-- Kotak Bukti Sudah Diunggah & Menunggu Verifikasi --}}
                    <div class="mx-4 my-3 p-3.5 bg-blue-50/80 border border-blue-200 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                <i class="ri-time-line text-lg animate-pulse"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-blue-900 block">Bukti Transfer Sedang Diverifikasi</span>
                                <span class="text-[11px] text-blue-700">Admin kami sedang memeriksa bukti pembayaran Anda. Mohon ditunggu.</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0 self-end sm:self-auto">
                            <button type="button" 
                                    onclick="openProofPreviewModal('{{ asset('storage/' . $order->bukti_transfer) }}', '{{ $order->invoice_number }}')"
                                    class="px-3.5 py-1.5 bg-white hover:bg-blue-100 text-blue-700 border border-blue-300 rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-2xs cursor-pointer">
                                <i class="ri-image-line"></i> Lihat Bukti
                            </button>
                            <button type="button" 
                                    onclick="openUploadModal({{ $order->id }}, '{{ $order->invoice_number }}', {{ $order->total - ($order->total_potongan_retur ?? 0) }})"
                                    title="Ganti Bukti Transfer"
                                    class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition cursor-pointer">
                                <i class="ri-refresh-line"></i> Ganti
                            </button>
                        </div>
                    </div>
                @elseif($isTransfer && empty($order->bukti_transfer) && ($payStatus === 'pending' || $order->status === 'pending'))
                    {{-- Kotak Rekening Panduan Transfer & Tombol Unggah Bukti --}}
                    <div class="mx-4 my-3 p-3.5 bg-amber-50/80 border border-amber-200 rounded-2xl">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2 text-amber-900 font-bold text-xs mb-1.5">
                                    <i class="ri-bank-card-line text-amber-700 text-base"></i>
                                    <span>Rekening Transfer Resmi Wowin (BCA & BRI):</span>
                                </div>
                                <div class="flex flex-wrap gap-2 text-[11px] text-gray-800">
                                    @foreach($bankAccounts as $b)
                                        <div class="inline-flex items-center gap-1.5 bg-white px-2.5 py-1 rounded-lg border border-amber-200 shadow-2xs">
                                            <span class="font-bold text-amber-900">{{ $b['bank_name'] ?? 'Bank' }}:</span>
                                            <span class="font-mono font-black text-gray-900">{{ $b['account_number'] ?? '-' }}</span>
                                            <button type="button" onclick="copyToClipboard('{{ $b['account_number'] ?? '' }}', 'No. Rekening')" class="text-amber-700 hover:text-amber-900 p-0.5" title="Salin">
                                                <i class="ri-file-copy-line text-xs"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-[10px] text-amber-800 mt-1.5 font-medium">Batas waktu transfer 24 jam. Segera unggah bukti setelah membayar.</p>
                            </div>
                            <div class="shrink-0 self-stretch sm:self-auto flex items-center">
                                <button type="button" 
                                        onclick="openUploadModal({{ $order->id }}, '{{ $order->invoice_number }}', {{ $order->total - ($order->total_potongan_retur ?? 0) }})"
                                        class="w-full sm:w-auto px-4 py-2.5 bg-[#16782d] hover:bg-green-800 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center justify-center gap-2 cursor-pointer">
                                    <i class="ri-upload-cloud-2-line text-base"></i> Unggah Bukti Transfer
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            {{-- Footer Pesanan: Total & Tombol Aksi --}}
<div class="flex flex-col md:flex-row justify-between items-center px-4 py-4 border-t border-gray-100 bg-gray-50/50 gap-4">
    
    <div class="hidden md:block text-[10px] text-gray-400 uppercase font-bold tracking-widest">
        @php
            // Mengambil setting berdasarkan admin yang menangani (claim) member ini
            $adminSetting = \App\Models\BranchSetting::where('user_id', $order->user->admin_id)->first();
            if(!$adminSetting) {
                $adminSetting = \App\Models\BranchSetting::where('enum_value', $order->user->kantor_cabang)->first();
            }
        @endphp
        {{ $adminSetting->nama_pt ?? 'PT SANKE BERSINAR TERANG' }}
    </div>

   <div class="flex flex-row items-center justify-between md:justify-end gap-2 md:gap-3 w-full md:w-auto">
    
    <div class="text-left md:text-right flex-shrink-0 mr-1 md:mr-2">
        <span class="block text-[9px] md:text-[10px] text-gray-400 uppercase font-bold tracking-tight">Total Wajib Bayar</span>
        <span class="text-base md:text-lg font-black text-gray-900 leading-none">
            Rp {{ number_format($order->total - $order->total_potongan_retur, 0, ',', '.') }}
        </span>
    </div>

    <div class="flex items-center gap-2 flex-shrink-0">
        {{-- Tombol Hubungi WhatsApp Resmi (Berakhiran 6600) --}}
        @php
            $waNum = $officialWaNumber ?? '62812106600';
            $cleanNo = preg_replace('/[^0-9]/', '', $waNum);
            if (str_starts_with($cleanNo, '0')) { $cleanNo = '62' . substr($cleanNo, 1); }
            
            $payMethodLabel = ($order->payment_method === 'transfer') ? 'Transfer Bank' : (($order->payment_method === 'wa') ? 'Pesan via WhatsApp' : strtoupper($order->payment_method ?? 'Transfer'));
            $teksKonfirmasi = "Halo Admin Wowin Food, saya ingin konfirmasi pesanan saya:\n\n"
                            . "• No. Invoice: #" . $order->invoice_number . "\n"
                            . "• Nama: " . ($order->user->nama_lengkap ?? 'Pelanggan') . "\n"
                            . "• Total: Rp " . number_format($order->total - ($order->total_potongan_retur ?? 0), 0, ',', '.') . "\n"
                            . "• Metode: " . $payMethodLabel . "\n\n"
                            . "Mohon bantuan untuk diproses. Terima kasih!";
        @endphp

        <a href="https://wa.me/{{ $cleanNo }}?text={{ urlencode($teksKonfirmasi) }}" 
           target="_blank" 
           class="flex items-center justify-center h-9 px-3.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl shadow-2xs transition text-xs font-bold gap-1.5"
           title="Hubungi WhatsApp CS Wowin ({{ $cleanNo }})">
            <i class="ri-whatsapp-line text-base"></i>
            <span class="hidden sm:inline">Hubungi WA</span>
        </a>

        {{-- Tombol Unggah Bukti di Footer jika Transfer & Belum Terverifikasi --}}
        @if($isTransfer && ($payStatus === 'pending' || $payStatus === 'rejected'))
            <button type="button" 
                    onclick="openUploadModal({{ $order->id }}, '{{ $order->invoice_number }}', {{ $order->total - ($order->total_potongan_retur ?? 0) }})"
                    class="h-9 px-3.5 bg-[#16782d] hover:bg-green-800 text-white rounded-xl text-xs font-bold shadow-2xs transition flex items-center gap-1.5 cursor-pointer">
                <i class="ri-upload-2-line text-sm"></i>
                <span>{{ $payStatus === 'rejected' ? 'Unggah Ulang' : 'Unggah Bukti' }}</span>
            </button>
        @elseif($hasProof)
            <button type="button" 
                    onclick="openProofPreviewModal('{{ asset('storage/' . $order->bukti_transfer) }}', '{{ $order->invoice_number }}')"
                    class="h-9 px-3 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-xl text-xs font-bold transition flex items-center gap-1 cursor-pointer">
                <i class="ri-image-line text-sm"></i>
                <span class="hidden sm:inline">Lihat Bukti</span>
            </button>
        @endif

        {{-- Tombol Detail / Nota --}}
        <a href="{{ route('public.trackings.nota', ['id' => $order->id]) }}" 
           class="h-9 px-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-200 rounded-xl text-xs font-bold shadow-2xs transition flex items-center gap-1">
            <i class="ri-file-text-line"></i>
            <span>Nota</span>
        </a>
    </div>
</div>
</div>
        </div> {{-- Penutup Wrapper Pesanan Putih --}}
        @endforeach
                        </div>

                        <!-- Modal Live Tracking J&T -->
                        <div id="liveTrackingModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
                            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] flex flex-col overflow-hidden border border-gray-100">
                                <!-- Modal Header -->
                                <div class="p-4 bg-gradient-to-r from-red-600 to-red-700 text-white flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center font-black text-xs">
                                            J&T
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-sm tracking-wide">
                                                Lacak Pengiriman J&T Express
                                            </h4>
                                            <p id="trackingModalSubtitle" class="text-[11px] text-red-100 font-mono">Invoice: -</p>
                                        </div>
                                    </div>
                                    <button onclick="closeLiveTrackingModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer">
                                        <i class="ri-close-line text-lg"></i>
                                    </button>
                                </div>

                                <!-- Resi & Quick Action Bar -->
                                <div class="p-3 bg-red-50/70 border-b border-red-100 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-500">No. Resi:</span>
                                        <span id="trackingModalAwb" class="font-bold font-mono text-red-700 select-all">-</span>
                                        <button onclick="copyAwbToClipboard()" title="Salin Resi" class="p-1 text-gray-500 hover:text-red-700 hover:bg-red-100 rounded transition cursor-pointer">
                                            <i class="ri-file-copy-line"></i>
                                        </button>
                                    </div>
                                    <div id="trackingModalBadge" class="px-2 py-0.5 rounded text-[11px] font-bold bg-white text-red-700 border border-red-200 shadow-xs">
                                        Memuat...
                                    </div>
                                </div>

                                <!-- Modal Body (Timeline Stepper) -->
                                <div id="trackingTimelineContent" class="p-5 overflow-y-auto flex-1 space-y-4 text-xs">
                                    <div class="flex flex-col items-center justify-center py-10 text-gray-400 gap-3">
                                        <div class="w-8 h-8 border-3 border-red-600 border-t-transparent rounded-full animate-spin"></div>
                                        <span>Mengambil data pelacakan live dari J&T Express...</span>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="p-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-2">
                                    <a id="trackingOfficialUrl" href="https://www.jet.co.id/track" target="_blank" class="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-800 font-semibold underline">
                                        <i class="ri-external-link-line"></i> Buka Web J&T
                                    </a>
                                    <div class="flex items-center gap-2">
                                        <button id="trackingRefreshBtn" onclick="refreshCurrentTracking()" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition flex items-center gap-1 cursor-pointer">
                                            <i class="ri-refresh-line"></i> Segarkan
                                        </button>
                                        <button onclick="closeLiveTrackingModal()" class="px-4 py-1.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-xs font-semibold transition cursor-pointer">
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Nota -->
                        <div id="notaModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
                            <div class="bg-white rounded-lg p-6 w-96 max-h-[90vh] overflow-y-auto">
                                <div id="notaContent">Loading...</div>
                                <button onclick="closeModal()" class="mt-4 px-4 py-2 bg-green-600 text-white rounded w-full">Tutup</button>
                            </div>
                        </div>

                        <!-- Modal Upload Bukti Transfer -->
                        <div id="uploadProofModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 overflow-y-auto">
                            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden border border-gray-100 my-8 animate-in fade-in zoom-in duration-200">
                                <div class="p-4 bg-gradient-to-r from-green-700 to-emerald-600 text-white flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white">
                                            <i class="ri-upload-cloud-2-line text-lg"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-sm">Unggah Bukti Transfer</h4>
                                            <p id="uploadModalInvoice" class="text-[11px] text-green-100 font-mono">Invoice: -</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="closeUploadModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer">
                                        <i class="ri-close-line text-lg"></i>
                                    </button>
                                </div>

                                <form id="uploadProofForm" action="" method="POST" enctype="multipart/form-data" class="p-5 space-y-4" onsubmit="handleUploadSubmit(event)">
                                    @csrf

                                    <!-- Tagihan Info -->
                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-2xl flex items-center justify-between text-xs">
                                        <span class="text-gray-600 font-medium">Nominal Wajib Bayar:</span>
                                        <span id="uploadModalTotal" class="font-black text-green-700 text-sm">Rp 0</span>
                                    </div>

                                    <!-- Rekening Panduan Singkat -->
                                    <div class="p-3 bg-blue-50/80 border border-blue-200 rounded-2xl text-[11px] text-blue-900 space-y-1.5">
                                        <div class="font-bold text-blue-800 flex items-center gap-1.5">
                                            <i class="ri-bank-card-line text-blue-600"></i> Rekening Tujuan Transfer Resmi:
                                        </div>
                                        <div class="space-y-1">
                                            @foreach($bankAccounts as $b)
                                                <div class="flex items-center justify-between bg-white px-2 py-1 rounded border border-blue-100">
                                                    <div>
                                                        <b>{{ $b['bank_name'] ?? 'Bank' }}:</b> <span class="font-mono">{{ $b['account_number'] ?? '-' }}</span>
                                                        <span class="text-[9px] text-gray-400 block">{{ $b['account_holder'] ?? '' }}</span>
                                                    </div>
                                                    <button type="button" onclick="copyToClipboard('{{ $b['account_number'] ?? '' }}', 'No. Rekening')" class="text-blue-600 hover:text-blue-800 text-[10px] font-bold p-1">
                                                        Salin
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- File Input / Preview Area -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Pilih Foto Bukti Transfer <span class="text-red-500">*</span></label>
                                        <div id="dropZone" onclick="document.getElementById('proofFileInput').click()" class="border-2 border-dashed border-gray-300 hover:border-green-600 rounded-2xl p-5 text-center cursor-pointer transition bg-gray-50/50 hover:bg-green-50/40 group">
                                            <input type="file" id="proofFileInput" name="bukti_transfer" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewProofImage(event)" required>
                                            
                                            <div id="uploadPrompt">
                                                <i class="ri-image-add-line text-3xl text-gray-400 group-hover:text-green-600 transition mb-1 block"></i>
                                                <span class="text-xs font-semibold text-gray-700 block">Klik di sini untuk memilih foto</span>
                                                <span class="text-[10px] text-gray-400 block mt-0.5">Format: JPG, JPEG, PNG, WEBP (Maks. 5MB)</span>
                                            </div>

                                            <div id="proofPreviewContainer" class="hidden">
                                                <img id="proofPreviewImage" src="" alt="Preview Bukti" class="max-h-48 mx-auto rounded-xl object-contain shadow-sm border border-gray-200 mb-2">
                                                <span class="text-[11px] text-green-700 font-bold block"><i class="ri-checkbox-circle-fill"></i> Foto siap diunggah</span>
                                                <span class="text-[10px] text-gray-400 hover:text-red-500 underline mt-1 inline-block">Klik untuk mengganti foto</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol Submit -->
                                    <div class="pt-2 space-y-2">
                                        <button type="submit" id="submitProofBtn" class="w-full py-3 bg-[#16782d] hover:bg-green-800 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                                            <i class="ri-send-plane-fill"></i>
                                            <span id="submitProofBtnText">Kirim Bukti Pembayaran</span>
                                        </button>
                                        <button type="button" onclick="closeUploadModal()" class="w-full py-2 text-gray-500 hover:text-gray-700 text-xs font-semibold text-center">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal Preview Bukti Transfer -->
                        <div id="proofPreviewModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
                            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-gray-200 flex flex-col max-h-[90vh]">
                                <div class="p-3.5 bg-gray-900 text-white flex items-center justify-between">
                                    <div>
                                        <span class="text-xs font-bold">Bukti Transfer Pembayaran</span>
                                        <span id="previewModalInvoice" class="text-[10px] text-gray-400 block font-mono">Invoice: -</span>
                                    </div>
                                    <button type="button" onclick="closeProofPreviewModal()" class="w-7 h-7 rounded-full bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition cursor-pointer">
                                        <i class="ri-close-line text-base"></i>
                                    </button>
                                </div>
                                <div class="p-4 bg-gray-100 flex-1 overflow-auto flex items-center justify-center">
                                    <img id="fullProofImage" src="" alt="Bukti Transfer" class="max-h-[65vh] w-auto rounded-lg shadow object-contain border border-gray-300">
                                </div>
                                <div class="p-3 bg-white border-t border-gray-200 flex items-center justify-between">
                                    <a id="downloadProofLink" href="#" target="_blank" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 inline-flex items-center gap-1">
                                        <i class="ri-external-link-line"></i> Buka Gambar Penuh
                                    </a>
                                    <button type="button" onclick="closeProofPreviewModal()" class="px-4 py-1.5 bg-gray-800 hover:bg-gray-900 text-white text-xs font-semibold rounded-lg cursor-pointer">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endsection

@section('scripts')
<script>
let currentTrackingOrderId = null;
let currentTrackingAwb = null;
let currentTrackingInvoice = null;

window.openLiveTrackingModal = function(orderId, awb, invoice) {
    currentTrackingOrderId = orderId;
    currentTrackingAwb = awb;
    currentTrackingInvoice = invoice;

    const subtitle = document.getElementById('trackingModalSubtitle');
    const awbSpan = document.getElementById('trackingModalAwb');
    const officialUrl = document.getElementById('trackingOfficialUrl');
    const modal = document.getElementById('liveTrackingModal');

    if (subtitle) subtitle.innerText = 'Invoice: #' + invoice;
    if (awbSpan) awbSpan.innerText = awb || '-';
    if (officialUrl) officialUrl.href = 'https://www.jet.co.id/track?awb=' + encodeURIComponent(awb || '');
    if (modal) modal.classList.remove('hidden');

    fetchTrackingData(orderId);
};

window.closeLiveTrackingModal = function() {
    const modal = document.getElementById('liveTrackingModal');
    if (modal) modal.classList.add('hidden');
};

window.refreshCurrentTracking = function() {
    if (currentTrackingOrderId) {
        fetchTrackingData(currentTrackingOrderId);
    }
};

window.copyAwbToClipboard = function() {
    if (currentTrackingAwb) {
        navigator.clipboard.writeText(currentTrackingAwb).then(() => {
            alert('Nomor resi ' + currentTrackingAwb + ' berhasil disalin!');
        });
    }
};

function fetchTrackingData(orderId) {
    const content = document.getElementById('trackingTimelineContent');
    const badge = document.getElementById('trackingModalBadge');
    
    if (content) {
        content.innerHTML = `
            <div class="flex flex-col items-center justify-center py-10 text-gray-400 gap-3">
                <div class="w-8 h-8 border-3 border-red-600 border-t-transparent rounded-full animate-spin"></div>
                <span>Mengambil data pelacakan live dari J&T Express...</span>
            </div>
        `;
    }
    if (badge) badge.innerText = 'Memuat...';

    fetch('/trackings/' + orderId + '/live')
        .then(res => res.json())
        .then(res => {
            if (!res.success && res.status !== 'success') {
                if (content) {
                    content.innerHTML = `
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-yellow-800 text-center">
                            <i class="ri-error-warning-line text-2xl text-yellow-600 mb-1"></i>
                            <p class="font-bold">Informasi Pelacakan Belum Tersedia</p>
                            <p class="text-[11px] mt-1 text-gray-600">${res.message || 'Nomor resi belum terdaftar atau menunggu scan oleh kurir J&T.'}</p>
                        </div>
                    `;
                }
                if (badge) badge.innerText = 'Belum Aktif';
                return;
            }

            const data = res.data;
            if (badge) {
                badge.innerText = data.status || 'Diproses';
                if (data.source === 'jnt_live') {
                    badge.className = 'px-2 py-0.5 rounded text-[11px] font-bold bg-green-100 text-green-800 border border-green-200 shadow-xs';
                } else {
                    badge.className = 'px-2 py-0.5 rounded text-[11px] font-bold bg-red-100 text-red-800 border border-red-200 shadow-xs';
                }
            }

            if (!data.checkpoints || data.checkpoints.length === 0) {
                if (content) {
                    content.innerHTML = `
                        <div class="p-4 bg-gray-50 border rounded-xl text-center text-gray-500">
                            <p>Belum ada riwayat perjalanan paket.</p>
                        </div>
                    `;
                }
                return;
            }

            let html = `<div class="relative pl-6 border-l-2 border-red-200 space-y-6 my-2">`;

            data.checkpoints.forEach((cp) => {
                const isCurrent = cp.is_current;
                const isDone = cp.is_completed;
                
                let dotClass = 'bg-gray-300 ring-4 ring-gray-100';
                if (isCurrent) {
                    dotClass = 'bg-red-600 ring-4 ring-red-100 animate-pulse';
                } else if (isDone) {
                    dotClass = 'bg-green-600 ring-4 ring-green-100';
                }

                html += `
                    <div class="relative group">
                        <div class="absolute -left-[31px] top-0.5 w-3.5 h-3.5 rounded-full ${dotClass}"></div>
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-bold text-xs ${isCurrent ? 'text-red-700' : 'text-gray-800'}">
                                    ${cp.title}
                                </span>
                                <span class="text-[10px] text-gray-400 font-mono">${cp.time}</span>
                            </div>
                            <p class="text-gray-600 text-[11px] mt-0.5 leading-relaxed">${cp.description}</p>
                            ${cp.location && cp.location !== '-' ? `
                                <div class="mt-1 flex items-center gap-1 text-[10px] text-gray-400">
                                    <i class="ri-map-pin-line"></i> ${cp.location}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
            });

            html += `</div>`;

            if (data.source === 'internal_pre_pickup') {
                html += `
                    <div class="p-2.5 bg-blue-50/80 border border-blue-100 rounded-lg flex items-start gap-2 text-[11px] text-blue-700 mt-3">
                        <i class="ri-information-line text-sm shrink-0 mt-0.5"></i>
                        <span>Resi J&T telah dibuat. Status tracking fisik kurir akan terbarui otomatis setelah paket diserahterimakan dan discan oleh kurir J&T Express.</span>
                    </div>
                `;
            }

            if (content) content.innerHTML = html;
        })
        .catch(err => {
            console.error('Error fetching live tracking:', err);
            if (content) {
                content.innerHTML = `
                    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-center">
                        <p class="font-bold">Gagal Mengambil Data</p>
                        <p class="text-[11px] mt-1">Terjadi kesalahan koneksi ke server tracking.</p>
                    </div>
                `;
            }
            if (badge) badge.innerText = 'Error';
        });
}

// Define functions in the global scope
window.showNota = function(id) {
    console.log("Fetching nota for ID:", id);
    fetch('/nota/' + id)
        .then(res => res.text())
        .then(html => {
            document.getElementById('notaContent').innerHTML = html;
            document.getElementById('notaModal').classList.remove('hidden');
        })
        .catch(err => {
            console.error("Error loading nota:", err);
            document.getElementById('notaContent').innerHTML = '<p class="text-red-500">Gagal memuat nota.</p>';
            document.getElementById('notaModal').classList.remove('hidden');
        });
};

window.closeModal = function() {
    document.getElementById('notaModal').classList.add('hidden');
};

window.toggleModal = function() {
    document.getElementById('notaModal').classList.toggle('hidden');
};

// --- JAVASCRIPT LOGIC UNTUK UPLOAD & MODAL BUKTI TRANSFER ---
window.openUploadModal = function(orderId, invoice, total) {
    const modal = document.getElementById('uploadProofModal');
    const form = document.getElementById('uploadProofForm');
    const invoiceEl = document.getElementById('uploadModalInvoice');
    const totalEl = document.getElementById('uploadModalTotal');
    const fileInput = document.getElementById('proofFileInput');
    const previewContainer = document.getElementById('proofPreviewContainer');
    const uploadPrompt = document.getElementById('uploadPrompt');

    if (modal && form) {
        form.action = '/trackings/' + orderId + '/upload-proof';
        if (invoiceEl) invoiceEl.innerText = 'Invoice: #' + invoice;
        if (totalEl) totalEl.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        if (fileInput) fileInput.value = '';
        if (previewContainer) previewContainer.classList.add('hidden');
        if (uploadPrompt) uploadPrompt.classList.remove('hidden');

        modal.classList.remove('hidden');
    }
};

window.closeUploadModal = function() {
    const modal = document.getElementById('uploadProofModal');
    if (modal) modal.classList.add('hidden');
};

window.previewProofImage = function(event) {
    const file = event.target.files[0];
    if (!file) return;

    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran berkas melebihi batas 5MB. Silakan pilih foto dengan ukuran lebih kecil.');
        event.target.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const previewImg = document.getElementById('proofPreviewImage');
        const previewContainer = document.getElementById('proofPreviewContainer');
        const uploadPrompt = document.getElementById('uploadPrompt');

        if (previewImg && previewContainer && uploadPrompt) {
            previewImg.src = e.target.result;
            previewContainer.classList.remove('hidden');
            uploadPrompt.classList.add('hidden');
        }
    };
    reader.readAsDataURL(file);
};

window.handleUploadSubmit = function(event) {
    const btn = document.getElementById('submitProofBtn');
    const btnText = document.getElementById('submitProofBtnText');
    if (btn) {
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        if (btnText) btnText.innerText = 'Sedang Mengunggah...';
    }
};

window.previewDirectProofImage = function(event) {
    const file = event.target.files[0];
    if (!file) return;

    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran berkas melebihi batas 5MB. Silakan pilih foto dengan ukuran lebih kecil.');
        event.target.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const previewImg = document.getElementById('directProofPreviewImage');
        const previewContainer = document.getElementById('directProofPreviewContainer');
        const uploadPrompt = document.getElementById('directUploadPrompt');

        if (previewImg && previewContainer && uploadPrompt) {
            previewImg.src = e.target.result;
            previewContainer.classList.remove('hidden');
            uploadPrompt.classList.add('hidden');
        }
    };
    reader.readAsDataURL(file);
};

window.handleDirectUploadSubmit = function(event) {
    const btn = document.getElementById('directSubmitProofBtn');
    const btnText = document.getElementById('directSubmitProofBtnText');
    if (btn) {
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        if (btnText) btnText.innerText = 'Sedang Mengunggah...';
    }
};

window.openProofPreviewModal = function(imageUrl, invoice) {
    const modal = document.getElementById('proofPreviewModal');
    const img = document.getElementById('fullProofImage');
    const invoiceEl = document.getElementById('previewModalInvoice');
    const linkEl = document.getElementById('downloadProofLink');

    if (modal && img) {
        img.src = imageUrl;
        if (invoiceEl) invoiceEl.innerText = 'Invoice: #' + invoice;
        if (linkEl) linkEl.href = imageUrl;
        modal.classList.remove('hidden');
    }
};

window.closeProofPreviewModal = function() {
    const modal = document.getElementById('proofPreviewModal');
    if (modal) modal.classList.add('hidden');
};

window.closeCheckoutSuccessModal = function() {
    const modal = document.getElementById('checkoutSuccessModal');
    if (modal) modal.classList.add('hidden');
};

window.copyToClipboard = function(text, label) {
    if (!text) return;
    navigator.clipboard.writeText(text).then(() => {
        alert((label || 'Teks') + ' berhasil disalin: ' + text);
    }).catch(err => {
        console.error('Gagal menyalin:', err);
    });
};

// Add event listeners once the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.detail-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            window.showNota(orderId);
        });
    });
});
</script>
@endsection
