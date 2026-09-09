@extends('public.layouts.app')

@section('head')
    <link rel="icon" href="{{ asset('images/lg-h.png') }}" type="image/png">
    <meta name="description" content="WOWINFood">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
     <!-- Add html2canvas before jsPDF -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
@endsection

@section('content')
<div class="w-full max-w-lg mx-auto my-4 md:my-10 px-3 md:px-0" id="receipt-container">
    <div class="border rounded-md overflow-hidden shadow-lg bg-white" id="receipt">
        <!-- Header Merah -->
        <div class="bg-red-600 text-white text-center py-3">
            <h2 class="text-lg md:text-xl font-semibold">Bukti Pembayaran</h2>
        </div>

        <!-- Logo dan Invoice -->
        <div class="p-4 md:p-6 text-sm">
    <div class="text-center mb-4">
    @if($branchSetting && $branchSetting->logo)
        {{-- Mengambil logo dari storage/logos/ --}}
        <img src="{{ asset('storage/' . $branchSetting->logo) }}" alt="Logo Cabang" class="w-16 md:w-20 mx-auto">
    @else
        {{-- Logo Default jika belum ada --}}
        <img src="{{ asset('images/wwn-cr.png') }}" alt="Logo Default" class="w-16 md:w-20 mx-auto">
    @endif


 <div class="mb-2 text-center">
    {{-- Tampilkan Nama PT Cabang --}}
    <h3 class="text-green-700 font-bold text-lg">
        {{ $branchSetting->nama_pt ?? 'PT. WOWIN PURNOMO PUTERA' }}
    </h3>
    {{-- Tampilkan Alamat Cabang --}}
    @if($branchSetting)
        <p class="text-gray-500 text-[10px] md:text-xs">
            {{ $branchSetting->alamat }} <br>
            Telp: {{ $branchSetting->no_telp }}
        </p>
    @endif
</div>

        <h3 class="text-green-700 font-semibold border-t pt-2">Nota Pesanan #{{ $order->invoice_number }}</h3>
        <p class="text-gray-500 text-xs">Tanggal: {{ $order->created_at->format('d F Y H:i:s') }}</p>
    </div>

            <!-- Informasi Pelanggan -->
            <div class="mb-4 text-xs md:text-sm text-gray-700 leading-relaxed">
                <p><span class="font-medium">Nama Member:</span> {{ $order->user->nama_lengkap }}</p>
                <p><span class="font-medium">Nama Toko:</span> {{ $order->user->membership->nama_toko ?? '-' }}</p>
                <p><span class="font-medium">No. HP:</span> {{ $order->user->membership->no_hp ?? '-' }}</p>
                <p><span class="font-medium">Alamat:</span> {{ $order->alamat }}</p>
                @if(!empty($order->no_resi))
                <div class="mt-2.5 p-2.5 bg-red-50 border border-red-200 rounded-lg flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <span class="font-bold text-red-700 text-xs flex items-center gap-1.5"><i class="fas fa-truck"></i> Kurir: J&T Express (EZ)</span>
                        <div class="text-xs text-gray-800 mt-0.5">No. Resi: <strong class="select-all text-red-800">{{ $order->no_resi }}</strong> {{ !empty($order->jnt_des_code) ? '('.$order->jnt_des_code.')' : '' }}</div>
                    </div>
                    <a href="{{ \App\Services\JntService::getTrackingUrl($order->no_resi) }}" target="_blank" class="px-2.5 py-1 bg-red-600 text-white text-xs font-bold rounded-md hover:bg-red-700 transition flex items-center gap-1">
                        Lacak Paket &rarr;
                    </a>
                </div>
                @endif
            </div>

            <!-- Tabel Produk -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-t border-gray-200 mb-4 text-xs md:text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="py-2 px-2 md:px-3">Produk</th>
                            <th class="py-2 px-1 md:px-3">Jumlah</th>
                            <th class="py-2 px-1 md:px-3">Harga</th>
                            <th class="py-2 px-1 md:px-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $item)
                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                <td class="py-2 px-2 md:px-3">{{ $item->product_name }}</td>
                                <td class="py-2 px-1 md:px-3 text-center">{{ $item->quantity }}</td>
                                <td class="py-2 px-1 md:px-3">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="py-2 px-1 md:px-3">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Ringkasan Pembayaran -->
<div class="text-xs md:text-sm mt-4 border-t border-gray-200 pt-4">
    <div class="space-y-3">
        <div class="flex justify-between">
            <span class="text-gray-600">Subtotal</span>
            <span class="text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>

        @if($discountData['discountAmount'] > 0)
        <div class="flex justify-between">
            <span class="text-gray-600">Diskon ({{ $discountData['discountPercent'] }}%)</span>
            <span class="text-red-600 font-medium">- Rp {{ number_format($discountData['discountAmount'], 0, ',', '.') }}</span>
        </div>
        @endif

        @if(($order->potongan_poin ?? 0) > 0)
        <div class="flex justify-between text-amber-800 text-xs py-1 border-t border-dashed border-gray-200">
            <span>Potongan Poin ({{ number_format($order->points_used ?? 0) }} Poin)</span>
            <span class="font-medium">- Rp {{ number_format($order->potongan_poin, 0, ',', '.') }}</span>
        </div>
        @endif

        {{-- SECTION POTONGAN RETUR --}}
    {{-- Hanya muncul jika status bukan pending ATAU jika total potongan sudah diisi oleh admin --}}
    @if($order->total_potongan_retur > 0)
        <div class="py-2 border-t border-dashed border-gray-200 mt-2">
            <div class="flex justify-between text-orange-700 font-bold text-xs uppercase tracking-wider">
                <span>Potongan Retur</span>
                <span>- Rp {{ number_format($order->total_potongan_retur, 0, ',', '.') }}</span>
            </div>
            
            {{-- Rincian Item yang Diretur --}}
            <div class="bg-orange-50 p-2 rounded mt-1 text-[10px] md:text-xs text-orange-800 italic leading-relaxed">
                @if($order->retur_botol > 0)
                    <div class="flex justify-between">
                        <span>{{ $order->retur_botol }}x Botol Kosong (@1.200)</span>
                        <span>Rp {{ number_format($order->retur_botol * 1200, 0, ',', '.') }}</span>
                    </div>
                @endif

                @if($order->retur_jerigen > 0)
                    <div class="flex justify-between">
                        <span>{{ $order->retur_jerigen }}x Jerigen Kosong (@2.500)</span>
                        <span>Rp {{ number_format($order->retur_jerigen * 2500, 0, ',', '.') }}</span>
                    </div>
                @endif

                @if($order->qty_rusak > 0)
                    @php
                        // Ambil harga satuan produk rusak (Harga / Isi Karton)
                        $harga_satuan_rusak = ($order->product_rusak->harga ?? 0) / ($order->product_rusak->isi_karton ?? 1);
                    @endphp
                    <div class="flex justify-between">
                        <span>{{ $order->qty_rusak }}x {{ $order->product_rusak->nama_produk ?? 'Produk Rusak' }}</span>
                        <span>Rp {{ number_format($order->qty_rusak * $harga_satuan_rusak, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

        <div class="flex justify-between">
            <span class="text-gray-600">Biaya Pengiriman</span>
            <span class="text-gray-800">Rp 0</span>
        </div>

        <div class="flex justify-between font-bold pt-3 border-t border-gray-200 text-base md:text-lg">
    <span class="text-gray-800">Total</span>
    <span class="text-green-700 font-extrabold">
        {{-- KURANGI TOTAL DENGAN POTONGAN RETUR --}}
        Rp {{ number_format($order->total - $order->total_potongan_retur, 0, ',', '.') }}
    </span>
</div>
    </div>
</div>

            <!-- Pembayaran -->
            <p class="uppercase text-center font-medium text-gray-600 mt-4 text-xs md:text-sm">Pembayaran {{ strtoupper($order->payment_method ?? 'COD') }}</p>
            <p class="text-center text-gray-500 text-xs mt-2">Tgl: {{ $order->created_at->format('d-m-Y H:i:s') }}</p>

            <!-- Footer -->
            <div class="text-center text-xs text-gray-400 mt-6">
                <p>E-receipt tersedia selama 90 hari sejak tanggal cetak.</p>
            </div>
        </div>
    </div>

<div class="flex flex-col sm:flex-row justify-center gap-3 mt-6">
    <a href="{{ route('order.download-receipt', ['orderId' => $order->id]) }}" class="flex items-center justify-center gap-2 bg-gray-800 text-white px-6 py-3 rounded-lg hover:bg-black transition font-medium">
        <i class="ri-download-line text-lg"></i> Unduh PDF
    </a>
    
    {{-- LOGIKA NOMOR WA DINAMIS BERDASARKAN BRANCH SETTING PT --}}
@php
    // 1. Ambil nomor dari branch setting PT
    $noTujuan = $branchSetting->no_telp ?? '6281216301220'; 
    $cleanNo = preg_replace('/[^0-9]/', '', $noTujuan);
    if (str_starts_with($cleanNo, '0')) {
        $cleanNo = '62' . substr($cleanNo, 1);
    }
    
    $namaPT = $branchSetting->nama_pt ?? 'Admin WOWINFood';

    // 2. Susun Daftar Barang dengan Satuan dan Diskon per Item
    $daftarBarang = "";
    foreach($order->orderItems as $item) {
        $satuan = str_contains($item->product_name, '(Karton)') ? 'Karton' : 'Pcs';
        $daftarBarang .= "- " . $item->product_name . " (" . $item->quantity . " " . $satuan . ")";
        
        // Cek jika ada diskon pada item ini (misal promo produk)
        if (isset($item->discount_amount) && $item->discount_amount > 0) {
            $daftarBarang .= " [Potongan: Rp " . number_format($item->discount_amount, 0, ',', '.') . "]";
        }
        $daftarBarang .= "\n";
    }

    // 3. Susun Detail Potongan (Diskon Membership & Retur)
    $potonganLain = "";
    
    // Diskon Membership (Global)
    if(isset($discountData['discountAmount']) && $discountData['discountAmount'] > 0) {
        $potonganLain .= "Diskon Membership (" . $discountData['discountPercent'] . "%): -Rp " . number_format($discountData['discountAmount'], 0, ',', '.') . "\n";
    }

    // Potongan Retur
    if($order->total_potongan_retur > 0) {
        $potonganLain .= "Potongan Retur: -Rp " . number_format($order->total_potongan_retur, 0, ',', '.') . "\n";
    }

    // 4. Susun Pesan Lengkap
    $teksWA = "Halo Admin " . $namaPT . ",\n\n";
    $teksWA .= "Saya ingin konfirmasi pembayaran untuk pesanan berikut:\n\n";
    $teksWA .= "Atas Nama: " . $order->user->nama_lengkap . "\n";
    $teksWA .= "Nama Toko: " . ($order->user->membership->nama_toko ?? '-') . "\n";
    $teksWA .= "Nota: #" . $order->invoice_number . "\n";
    $teksWA .= "Alamat: " . str_replace(["\r", "\n"], " ", $order->alamat) . "\n\n";
    $teksWA .= "Detail Barang:\n" . $daftarBarang . "\n";
    
    if ($potonganLain != "") {
        $teksWA .= "Rincian Potongan:\n" . $potonganLain . "\n";
    }

    $teksWA .= "TOTAL WAJIB BAYAR: Rp " . number_format($order->total - $order->total_potongan_retur, 0, ',', '.') . "\n\n";
    $teksWA .= "Mohon segera diproses. Terima kasih.";
@endphp

<a href="https://wa.me/{{ $cleanNo }}?text={{ urlencode($teksWA) }}" 
   target="_blank"
   class="flex items-center justify-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700 transition font-bold shadow-md">
    Konfirmasi WhatsApp
</a>
</div>
@endsection

{{-- @section('scripts')
<script>
    function sendOrderConfirmation() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'pt', 'a4');

        // Convert the HTML content of the receipt into a PDF
        doc.html(document.querySelector("#receipt"), {
            callback: function (doc) {
                // Save the PDF locally (optional, can be removed if not needed)
                doc.save("nota-pesanan.pdf");

                // Send PDF to WhatsApp via API
                const orderNumber = "{{ $order->invoice_number }}"; // Get the order number dynamically
                const phoneNumber = "{{ $order->user->membership->no_hp }}"; // Get phone number dynamically
                const baseUrl = "https://wa.me/";

                const message = `Konfirmasi Pesanan #${orderNumber} - Kami kirimkan nota pesanan Anda.`;
                
                // Create the URL for WhatsApp API
                const url = `${baseUrl}${phoneNumber}?text=${encodeURIComponent(message)}`;

                // Open WhatsApp link
                window.open(url, "_blank");
            },
            x: 20,
            y: 20,
            width: 550,
            windowWidth: 800
        });
    }
</script> --}}
{{-- @endsection --}}