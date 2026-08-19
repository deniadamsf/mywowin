<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran - {{ $order->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f5f5f5; }
        .receipt-container { max-width: 600px; margin: 20px auto; padding: 20px; background-color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-radius: 10px; }
        .header { background-color: #ff4757; color: white; text-align: center; padding: 20px; border-radius: 10px 10px 0 0; }
        .header h2 { margin: 0; font-size: 20px; }
        .logo-invoice { text-align: center; margin-top: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
        .logo-invoice img { width: 80px; margin-bottom: 10px; }
        .branch-info { font-size: 12px; color: #636e72; line-height: 1.6; margin-bottom: 15px; }
        .customer-info { margin-top: 20px; font-size: 13px; color: #2d3436; line-height: 1.5; }
        .product-table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 13px; }
        .product-table th { background-color: #f1f2f6; color: #2d3436; padding: 10px; border: 1px solid #ddd; text-align: left; }
        .product-table td { padding: 10px; border: 1px solid #ddd; }
        .payment-summary { margin-top: 20px; padding-top: 10px; border-top: 2px solid #f1f2f6; }
        .summary-row { display: flex; justify-content: space-between; margin: 5px 0; font-size: 14px; color: #2d3436; }
        .total-row { border-top: 1px solid #ddd; margin-top: 10px; padding-top: 10px; font-size: 16px; font-weight: bold; }
        .text-red { color: #ff4757; }
        .text-green { color: #16782d; }
        .footer { margin-top: 30px; font-size: 11px; text-align: center; color: #b2bec3; }
    </style>
</head>
<body>
<div class="receipt-container">
    <div class="header">
        <h2>Bukti Pembayaran</h2>
    </div>

    <div class="logo-invoice">
        @if($branchSetting && $branchSetting->logo)
            <img src="{{ public_path('storage/' . $branchSetting->logo) }}" alt="Logo Cabang">
        @else
            <img src="{{ public_path('images/wwn-cr.png') }}" alt="Logo Default">
        @endif

        <div class="branch-info">
            <strong>{{ $branchSetting->nama_pt ?? 'PT. WOWIN PURNOMO PUTERA' }}</strong><br>
            {{ $branchSetting->alamat ?? 'Alamat tidak tersedia' }}<br>
            Telp: {{ $branchSetting->no_telp ?? '-' }}
        </div>

        <h3 style="margin: 10px 0 5px 0;">Nota Pesanan #{{ $order->invoice_number }}</h3>
        <p style="font-size: 11px; color: #636e72; margin: 0;">Tanggal: {{ $order->created_at->format('d F Y H:i:s') }}</p>
    </div>

    <div class="customer-info">
        <p><strong>Nama Member:</strong> {{ $order->user->nama_lengkap }}</p>
        <p><strong>Nama Toko:</strong> {{ $order->user->membership->nama_toko ?? '-' }}</p>
        <p><strong>No. HP:</strong> {{ $order->user->membership->no_hp ?? '-' }}</p>
        <p><strong>Alamat Kirim:</strong> {{ $order->alamat }}</p>
    </div>

    <table class="product-table">
        <thead>
            <tr>
                <th>Produk</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Harga</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

   <style>
    /* Tambahkan atau update style berikut */
    .payment-summary { 
        margin-top: 20px; 
        border-top: 2px solid #f1f2f6; 
        padding-top: 10px;
    }
    .summary-table { 
        width: 100%; 
        border-collapse: collapse; 
    }
    .summary-table td { 
        padding: 4px 0; 
        font-size: 13px; 
        color: #2d3436; 
    }
    .text-right { text-align: right; }
    .text-red { color: #ff4757; font-weight: bold; }
    .text-green { color: #16782d; }
    
    /* Box Retur yang lebih elegan */
    .retur-box {
        background-color: #fff9f0;
        border-radius: 8px;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ffeaa7;
    }
    .retur-item-text {
        font-size: 11px;
        color: #d35400;
        font-style: italic;
    }
    .total-final {
        border-top: 2px solid #2d3436;
        margin-top: 10px;
        padding-top: 10px;
    }
    .total-final td {
        font-size: 16px;
        font-weight: bold;
        color: #000;
    }
</style>

<div class="payment-summary">
    <table class="summary-table">
        <tr>
            <td>Subtotal Pesanan</td>
            <td class="text-right">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
        </tr>

        @if ($discountData['discountAmount'] > 0)
        <tr>
            <td class="text-red">Diskon Membership ({{ $discountData['discountPercent'] }}%)</td>
            <td class="text-right text-red">- Rp{{ number_format($discountData['discountAmount'], 0, ',', '.') }}</td>
        </tr>
        @endif

        @if (($order->shipping_fee ?? 0) > 0)
        <tr>
            <td>Biaya Pengiriman</td>
            <td class="text-right">Rp{{ number_format($order->shipping_fee, 0, ',', '.') }}</td>
        </tr>
        @endif
    </table>

    @if($order->total_potongan_retur > 0)
    <div class="retur-box">
        <table class="summary-table">
            <tr>
                <td colspan="2" style="font-weight: bold; color: #d35400; font-size: 11px; text-transform: uppercase; padding-bottom: 5px;">
                    <i class="ri-refresh-line"></i> Penyesuaian Retur / Potongan Nota
                </td>
            </tr>
            @if($order->retur_botol > 0)
            <tr>
                <td class="retur-item-text">{{ $order->retur_botol }}x Botol Kosong (@1.200)</td>
                <td class="text-right retur-item-text">Rp{{ number_format($order->retur_botol * 1200, 0, ',', '.') }}</td>
            </tr>
            @endif

            @if($order->retur_jerigen > 0)
            <tr>
                <td class="retur-item-text">{{ $order->retur_jerigen }}x Jerigen Kosong (@2.500)</td>
                <td class="text-right retur-item-text">Rp{{ number_format($order->retur_jerigen * 2500, 0, ',', '.') }}</td>
            </tr>
            @endif

            @if($order->qty_rusak > 0)
            @php
                $harga_satuan_rusak = ($order->product_rusak->harga ?? 0) / ($order->product_rusak->isi_karton ?? 1);
            @endphp
            <tr>
                <td class="retur-item-text">{{ $order->qty_rusak }}x {{ $order->product_rusak->nama_produk ?? 'Produk Rusak' }} (@Rp{{ number_format($harga_satuan_rusak, 0, ',', '.') }})</td>
                <td class="text-right retur-item-text">Rp{{ number_format($order->qty_rusak * $harga_satuan_rusak, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td style="font-weight: bold; padding-top: 8px;">Total Potongan Retur</td>
                <td class="text-right" style="font-weight: bold; color: #ff4757; padding-top: 8px;">- Rp{{ number_format($order->total_potongan_retur, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    @endif

    <table class="summary-table total-final">
        <tr>
            <td style="text-transform: uppercase; letter-spacing: 1px;">Total Wajib Bayar</td>
            <td class="text-right text-green" style="font-size: 20px;">Rp{{ number_format($order->total - $order->total_potongan_retur, 0, ',', '.') }}</td>
        </tr>
    </table>
</div>
</body>
</html>