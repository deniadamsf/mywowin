<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label Thermal J&T - {{ $order->invoice_number }} ({{ $order->no_resi ?? 'Draft' }})</title>
    <!-- JsBarcode Library for crisp vector Code128 barcodes -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <style>
        @page {
            size: 100mm 150mm;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        html, body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
            font-size: 11px;
            background: #fff;
        }
        @media screen {
            body {
                background: #f1f5f9;
                padding: 16px 0;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
        }
        .no-print-bar {
            width: 100mm;
            max-width: 100%;
            background: #1e293b;
            color: #fff;
            padding: 10px 14px;
            margin-bottom: 12px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .btn-print {
            background: #16a34a;
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 12px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-print:hover {
            background: #15803d;
        }
        .btn-close {
            background: #475569;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
        }
        .btn-close:hover {
            background: #334155;
        }
        .label-container {
            width: 100mm;
            height: 147mm;
            max-height: 147mm;
            background: #fff;
            border: 2px solid #000;
            padding: 3.5mm 4mm;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
            margin-bottom: 3px;
        }
        .brand-jnt {
            font-size: 25px;
            font-weight: 900;
            color: #dc2626;
            letter-spacing: -1.5px;
            line-height: 1;
        }
        .brand-jnt span {
            font-size: 13px;
            font-weight: 800;
            color: #000;
            letter-spacing: 0;
            margin-left: 4px;
        }
        .header-badges {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .badge-ez {
            background: #000;
            color: #fff;
            font-size: 17px;
            font-weight: 900;
            padding: 2px 9px;
            border-radius: 4px;
            line-height: 1.1;
        }
        .badge-cod {
            background: #fff;
            color: #000;
            border: 2px solid #000;
            font-size: 11px;
            font-weight: 900;
            padding: 2px 5px;
            border-radius: 4px;
        }
        /* Barcode Area */
        .barcode-area {
            text-align: center;
            border-bottom: 2px solid #000;
            padding: 2px 0 5px 0;
        }
        .barcode-svg-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1px;
        }
        .barcode-svg-wrapper svg {
            max-width: 92mm;
            height: 46px;
        }
        .awb-text {
            font-size: 18px;
            font-weight: 900;
            letter-spacing: 2px;
            margin-top: 1px;
            font-family: 'Courier New', Courier, monospace;
        }
        .routing-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 4px;
            padding: 3px 8px;
            background: #f3f4f6;
            border: 1.5px solid #000;
            border-radius: 4px;
        }
        .sort-code {
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 1px;
        }
        .origin-dest {
            font-size: 11px;
            font-weight: bold;
            text-align: right;
        }
        /* Grid Order Info */
        .order-meta-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            border-bottom: 1.5px solid #000;
            padding: 4px 0;
            font-size: 9.5px;
            line-height: 1.35;
        }
        /* Addresses */
        .address-box {
            border-bottom: 1.5px solid #000;
            padding: 5px 0;
            line-height: 1.25;
        }
        .address-title {
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .address-name {
            font-size: 12.5px;
            font-weight: 900;
        }
        .address-phone {
            font-size: 11px;
            font-weight: bold;
        }
        .address-detail {
            font-size: 10px;
            margin-top: 2px;
            word-break: break-word;
        }
        /* Item details */
        .package-contents {
            flex-grow: 1;
            padding-top: 4px;
        }
        .contents-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        .contents-table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding: 2px 0;
            font-weight: 800;
        }
        .contents-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        /* Footer */
        .label-footer {
            margin-top: auto;
            border-top: 1.5px dashed #000;
            padding-top: 4px;
            text-align: center;
            font-size: 8px;
            font-weight: bold;
            color: #222;
        }
        @media print {
            html, body {
                width: 100mm !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                display: block !important;
            }
            .no-print-bar {
                display: none !important;
            }
            .label-container {
                border: 2px solid #000 !important;
                width: 100mm !important;
                height: 147mm !important;
                max-height: 147mm !important;
                padding: 3.5mm 4mm !important;
                margin: 0 !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                page-break-after: avoid !important;
                overflow: hidden !important;
            }
        }
    </style>
</head>
<body>

    <!-- TOOLBAR NON-PRINT -->
    <div class="no-print-bar">
        <div>
            <div style="font-weight: bold; font-size: 13px;">Format Thermal J&T (100x150 mm)</div>
            <div style="font-size: 10px; color: #94a3b8;">Pastikan printer memilih ukuran 100x150mm / 4x6 inch</div>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn-print" onclick="window.print()">
                🖨️ Cetak Ulang
            </button>
            <button class="btn-close" onclick="window.close()">
                ✕ Tutup
            </button>
        </div>
    </div>

    <!-- WADAH LABEL THERMAL 100x150 MM -->
    <div class="label-container">
        <!-- HEADER -->
        <div class="header">
            <div class="brand-jnt">
                J&T<span>EXPRESS</span>
            </div>
            <div class="header-badges">
                <span class="badge-ez">EZ</span>
                <span class="badge-cod">NON-COD</span>
            </div>
        </div>

        <!-- BARCODE & NOMOR RESI J&T -->
        <div class="barcode-area">
            <div class="barcode-svg-wrapper">
                <svg id="barcode-resi"></svg>
            </div>
            <div class="awb-text">{{ $order->no_resi ?? 'DRAFT-BELUM-ADA-RESI' }}</div>

            <!-- KODE SORTIR / ROUTING -->
            <div class="routing-box">
                <div class="sort-code">
                    {{ $order->jnt_des_code ?? 'SUB' }}
                </div>
                <div class="origin-dest">
                    <span>ASAL: <strong>{{ config('jnt.shipper.origin_code', 'SUB') }}</strong></span><br>
                    <span>LAYANAN: <strong>REGULER (EZ)</strong></span>
                </div>
            </div>
        </div>

        <!-- DETAIL ORDER & BERAT -->
        <div class="order-meta-grid">
            <div>
                <strong>No. Invoice:</strong> {{ $order->invoice_number }}<br>
                <strong>Tgl Order:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
            </div>
            <div style="text-align: right;">
                <strong>Berat Total:</strong> <span style="font-size: 12px; font-weight: 900;">{{ ceil($order->total_weight_kg ?? 1) }} Kg</span><br>
                <strong>Ongkir:</strong> Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}
            </div>
        </div>

        <!-- ALAMAT PENERIMA -->
        <div class="address-box">
            <div class="address-title">PENERIMA:</div>
            <div class="address-name">{{ $order->user->nama_lengkap }}</div>
            <div class="address-phone">Telp: {{ $order->user->membership->no_hp ?? $order->user->no_telp ?? '-' }}</div>
            <div class="address-detail">{{ $order->alamat }}</div>
        </div>

        <!-- ALAMAT PENGIRIM -->
        <div class="address-box">
            <div class="address-title">PENGIRIM:</div>
            <div class="address-name">{{ config('jnt.shipper.name', 'PT WOWIN PURNOMO PUTERA') }}</div>
            <div class="address-phone">Telp: {{ config('jnt.shipper.phone', '081216301220') }}</div>
            <div class="address-detail">{{ config('jnt.shipper.address', 'Jl. Raya No. KM 07, Duwet, Ngetal, Kec. Pogalan, Kab. Trenggalek, Jawa Timur 66371') }}</div>
        </div>

        <!-- ISI PAKET (RINGKASAN) -->
        <div class="package-contents">
            <div class="address-title">ISI PAKET:</div>
            <table class="contents-table">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th style="text-align: right; width: 45px;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td>{{ Str::limit($item->product_name, 38) }}</td>
                            <td style="text-align: right; font-weight: bold;">{{ $item->quantity }}x</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- FOOTER RESMI -->
        <div class="label-footer">
            PT WOWIN PURNOMO PUTERA &bull; Dokumen Resmi Ekosistem My Wowin &bull; Dicetak: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <!-- SKRIP GENERATOR BARCODE & AUTO-PRINT -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var resiNumber = "{{ $order->no_resi }}";
            if (!resiNumber) {
                resiNumber = "{{ $order->invoice_number }}";
            }

            try {
                JsBarcode("#barcode-resi", resiNumber, {
                    format: "CODE128",
                    width: 2.1,
                    height: 48,
                    displayValue: false,
                    margin: 0,
                    lineColor: "#000000"
                });
            } catch (e) {
                console.error("Gagal merender barcode:", e);
            }

            // Memicu jendela cetak printer thermal secara otomatis
            setTimeout(function() {
                window.print();
            }, 650);
        });
    </script>
</body>
</html>
