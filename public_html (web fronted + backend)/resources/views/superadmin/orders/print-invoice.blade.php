<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->invoice_number }}</title>
    <style>
        :root {
            --primary-color: #16782d; /* Base color as requested */
            --secondary-color: #0d5720; /* Darker shade for contrast */
            --accent-color: #f0fdf4;
            --text-color: #333333;
            --light-gray: #f9fafb;
            --border-color: #e5e7eb;
        }
        
        @page {
            margin: 0.5cm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.6;
            color: var(--text-color);
            background: #fff;
            margin: 0;
            padding: 1.5cm;
            font-size: 14px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            border-radius: 8px;
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1.2rem;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .logo {
            max-height: 90px;
            margin-bottom: 10px;
        }
        
        .logo-container {
            margin-bottom: 15px;
        }
        
        .company-info {
            text-align: right;
        }
        
        .company-name {
            font-family: 'Times New Roman', Times, serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
            letter-spacing: 0.8px;
        }
        
        .company-tagline {
            font-family: 'Times New Roman', Times, serif;
            font-style: italic;
            color: var(--secondary-color);
            margin-top: 8px;
            font-size: 14px;
        }
        
        .company-details {
            margin-top: 12px;
            font-size: 13px;
            line-height: 1.6;
        }
        
        .company-details p {
            margin: 3px 0;
        }
        
        .invoice-title {
            font-family: 'Times New Roman', Times, serif;
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--secondary-color);
            text-align: center;
            letter-spacing: 0.5px;
        }
        
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2.5rem;
            padding: 1.2rem 0;
        }
        
        .invoice-details-left, .invoice-details-right {
            flex: 1;
        }
        
        .invoice-details-right {
            text-align: right;
        }
        
        .invoice-details h3 {
            font-family: 'Times New Roman', Times, serif;
            font-size: 17px;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .customer-details {
            margin-bottom: 2rem;
            padding: 1.2rem;
            background-color: var(--accent-color);
            border-radius: 6px;
            border-left: 4px solid var(--primary-color);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2.5rem;
            border-radius: 6px;
            overflow: hidden;
        }
        
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        th {
            background-color: var(--light-gray);
            font-weight: 600;
            color: var(--primary-color);
            font-size: 14px;
            text-transform: uppercase;
        }
        
        tbody tr:hover {
            background-color: rgba(240, 253, 244, 0.5);
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-section {
            margin-top: 1.5rem;
            text-align: right;
        }
        
        .total-section table {
            max-width: 350px;
            margin-left: auto;
            background-color: var(--light-gray);
            border-radius: 6px;
        }
        
        .total-section td {
            padding: 0.8rem 1rem;
        }
        
        .total-row {
            font-weight: 700;
            font-size: 16px;
            color: var(--primary-color);
            border-top: 2px solid var(--primary-color);
        }
        
        .thanks-message {
            margin-top: 2.5rem;
            margin-bottom: 2rem;
            text-align: center;
            font-style: italic;
            color: var(--secondary-color);
            font-family: 'Times New Roman', Times, serif;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            margin-top: 10px;
        }
        
        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-canceled {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        
        .status-shipped {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-paid {
            background-color: #e9d5ff;
            color: #6b21a8;
        }
        
        .footer {
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            font-size: 12px;
            text-align: center;
            color: #666;
        }
        
        .print-buttons {
            text-align: center;
            margin-top: 25px;
            margin-bottom: 20px;
        }
        
        .btn {
            padding: 12px 24px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Times New Roman', Times, serif;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .btn-print {
            background-color: var(--primary-color);
        }
        
        .btn-print:hover {
            background-color: var(--secondary-color);
        }
        
        .btn-close {
            background-color: #6c757d;
        }
        
        .btn-close:hover {
            background-color: #495057;
        }
        
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .invoice-container {
                width: 100%;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
       <!-- Header dengan Logo dan Nama Toko -->
        <div class="invoice-header">
            <div class="logo-container">
                @if(file_exists(public_path('images/wwn-cr.png')))
                    <img src="{{ asset('images/wwn-cr.png') }}" alt="Logo Toko" class="logo">
                @endif
            </div>
            <div class="company-info">
                <h1 class="company-name">PT. WOWIN PURNOMO PUTERA</h1>
                <div class="company-tagline">Sahabat Hidangan Anda</div>
                <div class="company-details">
                    <p>Jl. Raya No.Km 07, Duwet, Ngetal, Kec. Pogalan, Kabupaten Trenggalek, Jawa Timur 66371</p>
                    <p>Telepon: 0812 - 1630 - 1220 | Email: wowinfood@gmail.com</p>
                </div>
            </div>
        </div>

        <!-- Konten Invoice -->
        <div class="invoice-title">
            INVOICE #{{ $order->invoice_number }}
            <div>
                <span class="status-badge 
                    @if($order->status == 'completed') status-completed @endif
                    @if($order->status == 'pending') status-pending @endif
                    @if($order->status == 'canceled') status-canceled @endif
                    @if($order->status == 'shipped') status-shipped @endif
                    @if($order->status == 'paid') status-paid @endif
                ">
                    @if($order->status == 'completed') SELESAI @endif
                    @if($order->status == 'pending') MENUNGGU @endif
                    @if($order->status == 'canceled') DIBATALKAN @endif
                    @if($order->status == 'shipped') DIKIRIM @endif
                    @if($order->status == 'paid') DIBAYAR @endif
                </span>
            </div>
        </div>

        <!-- Detail Invoice dan Customer -->
        <div class="invoice-details">
            <div class="invoice-details-left">
                <h3>Tanggal Invoice</h3>
                <p>{{ $order->created_at->format('d F Y') }}</p>
                <h3>Metode Pembayaran</h3>
                <p>{{ $order->payment_method }}</p>
            </div>
            <div class="invoice-details-right">
                <h3>Ditagihkan kepada</h3>
                <p>{{ $order->user->nama_lengkap }}</p>
                <p>{{ $order->alamat }}</p>
            </div>
        </div>

        <!-- Tabel Produk -->
        <table>
            <thead>
                <tr class="tr">
                    <th width="5%">#</th>
                    <th width="45%">Produk</th>
                    <th width="15%" class="text-center">Jumlah</th>
                    <th width="15%" class="text-right">Harga</th>
                    <th width="20%" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($order->orderItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data produk</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Total dan Footer -->
        <div class="total-section">
            <table style="max-width: 350px; margin-left: auto;">
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
                @if(isset($order->shipping_cost) && $order->shipping_cost > 0)
                <tr>
                    <td>Biaya Pengiriman:</td>
                    <td class="text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if(isset($order->discount) && $order->discount > 0)
                <tr>
                    <td>Diskon:</td>
                    <td class="text-right">- Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>Total:</td>
                    <td class="text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Sudah Dibayar:</td>
                    <td class="text-right">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</td>
                </tr>
                @if($order->total - $order->paid_amount > 0)
                <tr>
                    <td>Sisa Pembayaran:</td>
                    <td class="text-right">Rp {{ number_format($order->total - $order->paid_amount, 0, ',', '.') }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="thanks-message">
            Terima kasih atas kepercayaan Anda kepada PT. Wowin Purnomo Putera
        </div>

        <!-- Tombol Cetak dan Tutup -->
        <div class="print-buttons no-print">
            <button onclick="window.print()" class="btn btn-print">
                <i class="fas fa-print"></i> Cetak Invoice
            </button>
            <button onclick="window.close()" class="btn btn-close">
                <i class="fas fa-times"></i> Tutup
            </button>
        </div>

        <div class="footer">
            <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
            <p>{{ config('app.name', 'PT. Wowin Purnomo Putera') }} &copy; {{ date('Y') }} | Hak Cipta Dilindungi</p>
        </div>
    </div>

    <!-- Script untuk otomatis cetak saat halaman dimuat -->
    <script>
        window.onload = function() {
            // Jika parameter autoprint ada di URL
            if (window.location.search.includes('autoprint=true')) {
                window.print();
            }
        };
    </script>
</body>
</html>