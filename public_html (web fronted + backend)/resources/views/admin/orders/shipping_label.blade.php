<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label Pengiriman J&T Express - {{ $order->invoice_number }}</title>
    <style>
        @page {
            size: 100mm 150mm;
            margin: 0;
        }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 8mm;
            background: #fff;
            color: #111;
            font-size: 11px;
            box-sizing: border-box;
        }
        .label-container {
            border: 2px solid #000;
            padding: 6px;
            height: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 6px;
        }
        .jnt-logo {
            font-size: 24px;
            font-weight: 900;
            color: #d32f2f;
            letter-spacing: -1px;
        }
        .jnt-logo span {
            font-size: 13px;
            font-weight: bold;
            color: #333;
            letter-spacing: 0;
            margin-left: 4px;
        }
        .service-badge {
            background: #000;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            padding: 4px 12px;
            border-radius: 4px;
            text-align: center;
        }
        .barcode-section {
            text-align: center;
            border-bottom: 2px solid #000;
            padding: 8px 0;
        }
        .awb-code {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-top: 4px;
        }
        .des-code {
            font-size: 22px;
            font-weight: 900;
            background: #f0f0f0;
            padding: 4px;
            display: inline-block;
            border: 1px dashed #000;
            margin-top: 4px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-bottom: 1.5px solid #000;
            padding: 6px 0;
            font-size: 10px;
        }
        .info-grid div {
            padding: 2px 4px;
        }
        .address-box {
            border-bottom: 1.5px solid #000;
            padding: 6px 0;
        }
        .box-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 2px;
        }
        .person-name {
            font-size: 13px;
            font-weight: bold;
        }
        .person-phone {
            font-size: 11px;
            font-weight: bold;
            color: #222;
        }
        .person-addr {
            font-size: 11px;
            line-height: 1.3;
            margin-top: 2px;
        }
        .items-section {
            padding-top: 6px;
            flex-grow: 1;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
        }
        .items-table th {
            text-align: left;
            border-bottom: 1px solid #999;
            padding: 2px 0;
        }
        .items-table td {
            padding: 2px 0;
        }
        .footer-note {
            margin-top: auto;
            border-top: 1px dashed #666;
            padding-top: 4px;
            text-align: center;
            font-size: 8.5px;
            color: #555;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="background: #f8fafc; padding: 12px; margin-bottom: 16px; border: 1px solid #e2e8f0; border-radius: 8px; text-align: center;">
        <button onclick="window.print()" style="background: #16782d; color: white; border: none; padding: 10px 24px; font-size: 14px; font-weight: bold; border-radius: 6px; cursor: pointer;">
            🖨️ CETAK LABEL THERMAL (100x150mm)
        </button>
        <span style="margin-left: 12px; font-size: 12px; color: #64748b;">Gunakan opsi cetak 100% / Scale Fit Paper.</span>
    </div>

    <div class="label-container">
        <!-- HEADER LOGO & LAYANAN -->
        <div class="header">
            <div class="jnt-logo">
                J&T<span>EXPRESS</span>
            </div>
            <div class="service-badge">
                EZ
            </div>
        </div>

        <!-- BARCODE & NOMOR RESI J&T -->
        <div class="barcode-section">
            <div style="font-size: 10px; color: #444;">NOMOR RESI (AWB)</div>
            <div class="awb-code">{{ $order->no_resi ?? 'BELUM ADA RESI' }}</div>
            @if(!empty($order->jnt_des_code))
                <div class="des-code">{{ $order->jnt_des_code }}</div>
            @endif
        </div>

        <!-- INFO PESANAN & BERAT -->
        <div class="info-grid">
            <div>
                <strong>No. Invoice:</strong> {{ $order->invoice_number }}<br>
                <strong>Tgl Order:</strong> {{ $order->created_at->format('d/m/Y') }}
            </div>
            <div style="text-align: right;">
                <strong>Berat:</strong> {{ ceil($order->total_weight_kg ?? 1) }} Kg<br>
                <strong>COD:</strong> <span style="color: #b91c1c; font-weight: bold;">NON-COD</span>
            </div>
        </div>

        <!-- ALAMAT PENERIMA -->
        <div class="address-box">
            <div class="box-title">PENERIMA:</div>
            <div class="person-name">{{ $order->user->nama_lengkap }}</div>
            <div class="person-phone">{{ $order->user->membership->no_hp ?? $order->user->no_telp ?? '-' }}</div>
            <div class="person-addr">{{ $order->alamat }}</div>
        </div>

        <!-- ALAMAT PENGIRIM -->
        <div class="address-box">
            <div class="box-title">PENGIRIM:</div>
            <div class="person-name">{{ $branchSetting->nama_pt ?? config('jnt.shipper.name') }}</div>
            <div class="person-phone">{{ $branchSetting->no_telp ?? config('jnt.shipper.phone') }}</div>
            <div class="person-addr">{{ $branchSetting->alamat ?? config('jnt.shipper.address') }}</div>
        </div>

        <!-- ISI PAKET -->
        <div class="items-section">
            <div class="box-title">ISI PAKET:</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th style="text-align: right;">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td>{{ Str::limit($item->product_name, 35) }}</td>
                            <td style="text-align: right; font-weight: bold;">{{ $item->quantity }}x</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- FOOTER -->
        <div class="footer-note">
            PT WOWIN PURNOMO PUTERA &bull; Dokumen Pengiriman Resmi Ekosistem My Wowin
        </div>
    </div>

</body>
</html>
