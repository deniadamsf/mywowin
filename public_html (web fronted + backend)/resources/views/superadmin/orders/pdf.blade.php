<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Order - PT. WOWIN PURNOMO PUTERA</title>
    <style>
        @page { size: landscape; margin: 15px; }

        :root {
            --primary-color: #16782d;
            --primary-dark: #0d5b1f;
            --secondary-color: #1e5f9c;
            --text-dark: #1f2937;
            --text-medium: #4b5563;
            --text-light: #6b7280;
            --gray-border: #e5e7eb;
            --gray-bg: #f9fafb;
        }

        body {
            font-family: 'Arial', 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: var(--text-dark);
            margin: 0; padding: 20px; line-height: 1.4;
        }

        .container {
            width: 100%; margin: 0 auto;
            padding: 15px 20px;
            background-color: white;
        }

        .kop-surat {
            display: flex; align-items: center;
            border-bottom: 3px solid var(--primary-color);
            margin-bottom: 20px; padding-bottom: 12px;
        }

        .kop-surat img { height: 60px; margin-right: 20px; }
        .kop-teks h1 { font-size: 20px; margin: 0 0 4px; color: var(--primary-color); }
        .kop-teks p { margin: 0; font-size: 11px; color: var(--text-medium); }

        .title {
            text-align: center;
            font-size: 16px; font-weight: 700;
            margin: 16px 0; text-transform: uppercase;
        }
        .title:after {
            content: ""; display: block;
            width: 70px; height: 3px;
            background-color: var(--primary-color); margin: 6px auto 0;
        }

        .report-meta {
            display: flex; justify-content: space-between;
            font-size: 10px; margin-bottom: 15px;
            color: var(--text-medium);
        }

        .info-section {
    display: flex;
    width: 100%;
    background-color: var(--gray-bg);
    border-radius: 5px;
    margin-bottom: 16px;
    border-left: 4px solid var(--primary-color);
    padding: 12px 0;
    justify-content: space-between; /* Tambahkan ini */
}

.info-group {
    flex: 1;
    padding: 0 15px;
    position: relative;
    border-right: 1px solid var(--gray-border); /* Tambahkan pembatas antar kolom */
}

.info-group:last-child {
    border-right: none; /* Hapus border di kolom terakhir */
}

/* Sisanya tetap sama dengan CSS sebelumnya */

        .info-group h3 {
            margin: 0 0 8px;
            font-size: 12px;
            color: var(--primary-color);
            font-weight: bold;
        }

        .info-item {
            display: flex;
            width: 100%;
            margin-bottom: 4px;
            align-items: center;
        }

        .info-label {
            width: 45%;
            color: var(--text-medium);
            font-size: 11px;
        }

        .info-value {
            width: 55%;
            color: var(--text-dark);
            font-size: 11px;
            font-weight: 500;
        }

        table {
            width: 100%; border-collapse: collapse;
            font-size: 10px; margin-bottom: 16px;
        }

        thead { background-color: var(--primary-color); color: white; }
        th {
            font-weight: 600;
            text-align: left;
            padding: 8px 10px;
            border: 1px solid var(--primary-dark);
        }

        td {
            border: 1px solid var(--gray-border);
            padding: 7px 10px;
            color: var(--text-medium);
        }

        tr:nth-child(even) { background-color: var(--gray-bg); }

        .status-pill {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 10px;
            font-size: 9px; font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending { background-color: #FEF3C7; color: #92400E; }
        .status-processing { background-color: #DBEAFE; color: #1E40AF; }
        .status-completed { background-color: #D1FAE5; color: #065F46; }
        .status-cancelled { background-color: #FEE2E2; color: #991B1B; }

        .product-details { margin-top: 4px; font-size: 9px; padding-left: 15px; }
        .product-item { display: flex; justify-content: space-between; margin-bottom: 3px; }
        .product-name { flex: 1; }
        .product-qty { width: 30px; text-align: center; }
        .product-price { width: 100px; text-align: right; }

        .summary-section {
            margin-top: 20px; display: flex; justify-content: flex-end;
        }

        .summary-table {
            width: 280px; font-size: 11px;
        }

        .summary-table td { padding: 5px 10px; border: none; }
        .summary-table .total-row { font-weight: bold; border-top: 1px solid var(--text-medium); }

        .signature-section {
            margin-top: 40px; display: flex; justify-content: flex-end;
        }

        .signature-box {
            text-align: center; width: 180px;
        }

        .signature-line {
            height: 50px; border-bottom: 1px solid var(--text-light); margin-bottom: 6px;
        }

        .footer {
            margin-top: 30px; padding-top: 15px;
            border-top: 1px solid var(--gray-border);
            font-size: 10px; display: flex; justify-content: space-between;
            color: var(--text-light);
        }

        .page-number {
            text-align: center; font-size: 10px;
            margin-top: 10px; color: var(--text-light);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="kop-surat">
        <img src="{{ public_path('images/wwn-cr.png') }}" alt="Logo">
        <div class="kop-teks">
            <h1>PT. WOWIN PURNOMO PUTERA</h1>
            <p>Jl. Raya No.Km 07, Duwet, Ngetal, Kec. Pogalan, Trenggalek</p>
            <p>Jawa Timur 66371 | Telp: 0812-1630-1220 | Email: info@perusahaan.com</p>
            <p>website: www.wowinpurnomoputera.com</p>
        </div>
    </div>

    <div class="title">LAPORAN DATA ORDER</div>

    <div class="report-meta">
        <div>Nomor Dokumen: RPT/ORD/{{ date('Ymd') }}/{{ str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}</div>
        <div>Halaman: 1</div>
    </div>

    <!-- Info Section yang diperbarui sebaris -->
    <div class="info-section">
        <div class="info-group">
            <h3>Informasi Laporan</h3>
            <div class="info-item">
                <div class="info-label">Tanggal Cetak:</div>
                <div class="info-value">{{ now()->format('d F Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Total Order:</div>
                <div class="info-value">{{ count($orders) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Dibuat Oleh:</div>
                <div class="info-value">{{ auth()->user()->nama_lengkap ?? 'Superadmin' }}</div>
            </div>
        </div>
        
        <div class="info-group">
            <h3>Periode</h3>
            <div class="info-item">
                <div class="info-label">Tanggal Mulai:</div>
                <div class="info-value">{{ now()->startOfMonth()->format('d F Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Tanggal Akhir:</div>
                <div class="info-value">{{ now()->format('d F Y') }}</div>
            </div>
        </div>
        
        <div class="info-group">
            <h3>Ringkasan Keuangan</h3>
            <div class="info-item">
                <div class="info-label">Total Nilai Order:</div>
                <div class="info-value">Rp {{ number_format($orders->sum('total'), 0, ',', '.') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Total Pembayaran:</div>
                <div class="info-value">Rp {{ number_format($orders->sum('paid_amount'), 0, ',', '.') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Sisa Pembayaran:</div>
                <div class="info-value">Rp {{ number_format($orders->sum('total') - $orders->sum('paid_amount'), 0, ',', '.') }}</div>
            </div>
        </div>
        
        @if(!empty($filterInfo))
        <div class="info-group">
            <h3>Filter yang digunakan</h3>
            @foreach($filterInfo as $info)
                <div class="info-item">
                    <div class="info-value" style="width: 100%;">{{ $info }}</div>
                </div>
            @endforeach
        </div>
        @endif
    </div>

    <table>
        <thead>
        <tr>
            <th>No.</th>
            <th>Nama Customer</th>
            <th>Invoice</th>
            <th>Metode Pembayaran</th>
            <th>Status</th>
            <th>Jumlah Terbayar</th>
            <th>Total Order</th>
            <th>Tanggal Order</th>
            <th>Detail Produk</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td style="text-align: center">{{ $loop->iteration }}</td>
                <td>{{ $order->user->nama_lengkap }}</td>
                <td>{{ $order->invoice_number }}</td>
                <td>{{ $order->payment_method }}</td>
                <td>
                    <span class="status-pill status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                </td>
                <td style="text-align: right">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</td>
                <td style="text-align: right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                <td>{{ $order->created_at->format('d-m-Y') }}</td>
                <td>
                    <div class="product-details">
                        @foreach($order->orderItems as $item)
                            <div class="product-item">
                                <div class="product-name">{{ $item->product->nama_produk }}</div>
                                <div class="product-qty">{{ $item->quantity }} x</div>
                                <div class="product-price">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="summary-section">
        <table class="summary-table">
            <tr><td>Total Order:</td><td style="text-align: right">{{ count($orders) }}</td></tr>
            <tr><td>Order Completed:</td><td style="text-align: right">{{ $orders->where('status', 'completed')->count() }}</td></tr>
            <tr><td>Order Pending:</td><td style="text-align: right">{{ $orders->where('status', 'pending')->count() }}</td></tr>
            <tr><td>Order Cancelled:</td><td style="text-align: right">{{ $orders->where('status', 'cancelled')->count() }}</td></tr>
            <tr><td>Total Terbayar:</td><td style="text-align: right">Rp {{ number_format($orders->sum('paid_amount'), 0, ',', '.') }}</td></tr>
            <tr class="total-row"><td>Total Nilai Order:</td><td style="text-align: right">Rp {{ number_format($orders->sum('total'), 0, ',', '.') }}</td></tr>
        </table>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p>Trenggalek, {{ now()->format('d F Y') }}</p>
            <div class="signature-line"></div>
            <p>{{ auth()->user()->nama_lengkap ?? 'Superadmin' }}</p>
            <p>Superadmin</p>
        </div>
    </div>

    <div class="footer">
        <div>PT. WOWIN PURNOMO PUTERA &copy; {{ date('Y') }}</div>
        <div>Dicetak pada {{ now()->format('d F Y, H:i') }}</div>
    </div>

    <div class="page-number">Halaman 1 dari 1</div>
</div>
</body>
</html>