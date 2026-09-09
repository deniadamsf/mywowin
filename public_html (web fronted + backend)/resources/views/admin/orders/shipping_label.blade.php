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
            display: flex;
            align-items: center;
            color: #e11a22;
        }
        .jnt-logo-svg {
            height: 25px;
            width: auto;
            max-width: 140px;
            display: block;
        }
        .brand-mywowin {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .mywowin-logo-header {
            height: 22px;
            width: auto;
            max-width: 75px;
            display: block;
            object-fit: contain;
        }
        .brand-watermark-section {
            margin-top: auto;
            margin-bottom: 3px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .watermark-logo {
            height: 22px;
            width: auto;
            max-width: 75px;
            display: block;
            margin-bottom: 2px;
            opacity: 0.95;
        }
        .watermark-tagline {
            font-size: 7px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #334155;
            text-transform: uppercase;
        }
        @media print {
            .brand-jnt {
                color: #000000 !important;
            }
            .mywowin-logo-header, .watermark-logo {
                filter: grayscale(100%) contrast(200%) !important;
            }
            .watermark-tagline {
                color: #000000 !important;
            }
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
                <svg class="jnt-logo-svg" viewBox="0 0 1000 209" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="J&T Express">
                    <g fill="currentColor">
                        <path d="M 149.56 0.00 L 209.73 0.00 C 193.99 69.29 178.13 138.55 162.29 207.82 C 108.51 207.77 54.73 207.92 0.95 207.88 C 8.66 194.14 15.51 179.94 22.94 166.06 C 52.56 166.20 82.18 166.09 111.80 166.11 C 124.40 110.75 136.86 55.34 149.56 0.00 Z" />
                        <path d="M 240.39 0.00 L 374.44 0.00 C 363.88 19.06 353.46 38.20 342.84 57.24 C 342.86 52.17 343.87 47.09 343.17 42.05 C 342.14 36.26 337.08 32.21 331.84 30.21 C 322.38 26.60 311.03 26.66 302.34 32.29 C 290.42 39.55 285.14 55.99 291.19 68.69 C 296.80 79.83 305.16 89.29 313.76 98.21 C 324.15 108.70 335.24 118.51 346.99 127.45 C 348.80 125.24 350.17 122.72 351.06 120.01 C 353.97 111.53 355.81 102.72 357.51 93.93 C 371.36 97.87 385.20 101.86 399.05 105.82 C 396.44 126.63 388.22 146.50 376.69 163.93 C 388.65 178.54 400.66 193.12 412.61 207.74 C 394.44 207.78 376.28 207.72 358.12 207.77 C 354.19 203.68 350.42 199.44 346.49 195.36 C 328.21 203.86 308.06 208.02 287.97 208.67 C 264.68 209.01 240.42 203.70 221.50 189.57 C 210.82 179.09 204.51 163.65 206.42 148.61 C 207.89 136.55 213.80 125.50 221.11 115.98 C 230.62 103.76 242.46 93.47 255.33 84.92 C 241.32 69.27 231.80 48.20 234.38 26.88 C 236.21 17.88 238.36 8.95 240.39 0.00 M 260.27 138.34 C 256.97 143.57 256.06 150.42 258.87 156.06 C 263.71 165.94 274.19 172.55 285.12 173.14 C 298.11 173.95 310.79 169.27 321.93 162.95 C 309.10 147.77 296.44 132.45 283.49 117.39 C 275.08 123.51 265.73 129.17 260.27 138.34 Z" />
                        <path d="M 405.16 0.00 L 648.77 0.00 C 644.07 4.53 639.51 9.21 634.85 13.77 C 614.62 13.75 594.40 13.73 574.18 13.78 C 571.64 16.32 569.07 18.82 566.54 21.37 C 586.78 21.39 607.02 21.35 627.26 21.39 C 623.30 24.96 619.95 29.48 615.63 32.47 C 595.33 32.68 575.02 32.54 554.72 32.53 C 552.08 35.03 549.49 37.59 546.94 40.17 C 567.40 40.21 587.86 40.17 608.32 40.19 C 602.78 45.76 597.20 51.27 591.62 56.80 C 569.28 56.82 546.94 56.85 524.59 56.78 C 511.92 107.10 499.50 157.48 486.88 207.82 C 467.29 207.75 447.70 207.94 428.12 207.72 C 439.95 157.26 453.19 107.14 465.63 56.82 C 435.12 56.80 404.60 56.84 374.09 56.80 C 384.48 37.88 394.68 18.86 405.16 0.00 Z" />
                        <path d="M 889.20 138.19 C 894.09 130.49 903.09 126.34 911.92 125.24 C 920.43 124.30 929.42 124.97 937.16 128.92 C 935.34 133.78 933.48 138.62 931.71 143.49 C 925.02 140.45 917.18 138.62 910.00 140.97 C 905.84 142.23 902.43 147.12 904.69 151.35 C 907.34 155.76 912.39 157.77 916.61 160.35 C 923.04 163.80 929.42 168.80 931.44 176.14 C 933.54 184.91 930.77 194.91 923.86 200.86 C 917.98 206.05 910.14 208.24 902.49 209.00 L 894.71 209.00 C 887.98 208.32 881.11 207.03 875.34 203.30 C 877.29 198.50 879.25 193.71 881.20 188.91 C 888.39 192.79 896.82 195.08 904.99 193.58 C 909.18 192.79 913.43 189.59 913.50 184.98 C 913.82 180.23 909.59 177.13 905.94 175.02 C 899.72 171.54 893.01 168.17 888.84 162.15 C 883.84 155.13 884.78 145.25 889.20 138.19 Z" />
                        <path d="M 951.14 138.15 C 956.05 130.45 965.05 126.33 973.88 125.23 C 982.38 124.30 991.34 125.00 999.08 128.91 C 997.26 133.77 995.39 138.61 993.63 143.49 C 987.51 140.68 980.58 139.05 973.88 140.38 C 970.43 141.10 966.87 143.34 966.07 146.98 C 965.35 149.70 966.80 152.44 968.86 154.16 C 974.01 158.58 980.65 160.78 985.82 165.18 C 989.73 168.40 992.89 172.80 993.73 177.88 C 994.96 185.95 992.44 194.76 986.33 200.34 C 980.44 205.89 972.33 208.20 964.46 209.00 L 956.66 209.00 C 949.91 208.32 943.03 207.03 937.24 203.30 C 939.20 198.50 941.16 193.71 943.12 188.91 C 950.33 192.81 958.79 195.09 966.98 193.57 C 971.12 192.77 975.31 189.59 975.41 185.04 C 975.76 180.32 971.60 177.18 967.96 175.08 C 961.74 171.58 955.01 168.23 950.81 162.21 C 945.74 155.18 946.68 145.24 951.14 138.15 Z" />
                        <path d="M 559.38 126.19 C 575.56 126.19 591.73 126.15 607.91 126.21 C 606.90 131.18 605.95 136.15 604.97 141.12 C 594.69 141.13 584.42 141.12 574.15 141.12 C 573.07 146.94 571.74 152.71 570.88 158.56 C 580.54 158.42 590.21 158.54 599.88 158.50 C 598.97 163.39 598.02 168.28 597.09 173.17 C 587.39 173.21 577.68 173.17 567.97 173.19 C 566.73 179.77 565.38 186.32 564.27 192.92 C 575.13 192.82 585.99 192.90 596.85 192.88 C 595.92 197.86 594.99 202.84 594.05 207.81 C 577.32 207.82 560.59 207.82 543.86 207.81 C 549.00 180.60 554.22 153.40 559.38 126.19 Z" />
                        <path d="M 615.59 126.18 C 621.77 126.19 627.94 126.18 634.12 126.19 C 637.18 135.76 640.35 145.32 642.76 155.09 C 643.88 154.37 644.39 153.12 645.01 152.00 C 649.63 143.12 655.21 134.79 660.35 126.21 C 667.35 126.16 674.35 126.19 681.36 126.19 C 671.42 139.56 661.46 152.91 651.50 166.25 C 656.68 180.12 661.95 193.95 667.15 207.81 C 660.91 207.79 654.67 207.87 648.43 207.76 C 645.10 197.65 641.51 187.61 638.53 177.39 C 632.60 187.55 626.57 197.67 620.50 207.75 C 613.44 207.89 606.37 207.78 599.31 207.81 C 609.47 193.84 619.86 180.03 629.92 165.98 C 625.04 152.76 620.37 139.45 615.59 126.18 Z" />
                        <path d="M 690.87 127.72 C 700.80 125.72 711.11 124.89 721.18 126.25 C 729.39 127.39 737.98 131.71 741.26 139.76 C 745.54 150.93 741.13 164.50 731.54 171.51 C 722.15 178.46 709.76 179.65 698.45 178.45 C 696.58 188.24 694.74 198.03 692.87 207.82 C 687.16 207.84 681.45 207.76 675.75 207.86 C 680.63 181.12 685.85 154.43 690.87 127.72 M 705.76 140.07 C 704.23 148.12 702.66 156.17 701.15 164.23 C 707.71 165.38 715.24 165.05 720.44 160.38 C 725.25 156.28 727.13 148.10 722.85 143.00 C 718.41 138.55 711.45 139.44 705.76 140.07 Z" />
                        <path d="M 759.59 127.72 C 768.23 125.94 777.10 125.32 785.90 125.72 C 793.55 126.21 801.75 128.05 807.30 133.71 C 812.70 139.26 813.20 148.09 810.46 155.03 C 807.56 162.30 800.62 167.13 793.35 169.36 C 797.29 171.55 799.67 175.63 800.49 179.97 C 802.33 189.18 801.95 198.72 804.37 207.83 C 798.36 207.79 792.35 207.84 786.35 207.80 C 784.82 200.58 784.54 193.19 783.66 185.88 C 783.21 182.37 781.97 178.39 778.56 176.70 C 775.15 175.15 771.29 175.75 767.67 175.62 C 765.62 186.35 763.61 197.08 761.59 207.81 C 755.79 207.84 749.99 207.78 744.20 207.85 C 749.22 181.12 754.45 154.43 759.59 127.72 M 774.48 140.11 C 773.20 147.60 771.61 155.03 770.22 162.50 C 776.55 162.52 783.68 163.15 788.92 158.83 C 793.70 155.28 795.85 147.22 791.32 142.65 C 786.77 138.67 780.04 139.07 774.48 140.11 Z" />
                        <path d="M 830.47 126.19 C 846.63 126.18 862.80 126.19 878.97 126.19 C 878.01 131.17 877.04 136.15 876.06 141.12 C 865.78 141.13 855.50 141.12 845.22 141.13 C 844.11 146.92 842.96 152.70 841.86 158.49 C 851.55 158.51 861.25 158.50 870.96 158.50 C 870.05 163.40 869.11 168.29 868.17 173.18 C 858.46 173.19 848.76 173.19 839.06 173.18 C 837.79 179.74 836.53 186.31 835.30 192.88 C 846.17 192.87 857.05 192.87 867.93 192.88 C 866.99 197.86 866.08 202.84 865.12 207.82 C 848.39 207.81 831.66 207.82 814.94 207.82 C 820.09 180.60 825.30 153.40 830.47 126.19 Z" />
                    </g>
                </svg>
            </div>
            <div class="brand-mywowin">
                <img src="{{ asset('images/mywowin_logo_horizontal.png') }}" alt="My Wowin" class="mywowin-logo-header">
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

        <!-- BRAND WATERMARK SECTION -->
        <div class="brand-watermark-section">
            <img src="{{ asset('images/mywowin_logo_horizontal.png') }}" alt="My Wowin" class="watermark-logo">
            <div class="watermark-tagline">DISTRIBUSI RESMI PT WOWIN PURNOMO PUTERA</div>
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
