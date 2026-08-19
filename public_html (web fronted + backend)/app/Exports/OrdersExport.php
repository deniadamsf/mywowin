<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;

class OrdersExport implements FromArray, WithHeadings, WithEvents
{
    // 1. TARO PROPERTI DI SINI
    protected $data = [];
    protected $filterDescription;
    protected $branchSetting;

    // 2. PERBARUI CONSTRUCTOR UNTUK MENERIMA DATA DARI CONTROLLER
    public function __construct($orders, $filterDescription, $branchSetting = null)
    {
        $this->filterDescription = $filterDescription;
        $this->branchSetting = $branchSetting;

        // Proses data orders yang dikirim dari controller ke dalam array excel
        foreach ($orders as $order) {
            $this->data[] = [
                'Nama Lengkap'      => $order->user->nama_lengkap ?? '-',
                'Alamat'            => $order->user->membership->alamat ?? '-',
                'Telepon'           => $order->user->membership->no_hp ?? '-',
                'Nomor Invoice'     => $order->invoice_number,
                'Metode Pembayaran' => $order->payment_method,
                'Status'            => $order->status,
                'Jumlah Terbayar'   => 'Rp' . number_format($order->paid_amount, 0, ',', '.'),
                'Total'             => 'Rp' . number_format($order->total, 0, ',', '.'),
                'Tanggal'           => $order->created_at->format('d-m-Y'),
                'Detail Produk'     => '',
            ];

            foreach ($order->orderItems as $item) {
                if ($item->bundling_id) {
                    $productName = $item->product_name; 
                } else {
                    $productName = optional($item->product)->nama_produk ?? $item->product_name ?? '-';
                }

                $this->data[] = [
                    'Nama Lengkap'      => '',
                    'Alamat'            => '',
                    'Telepon'           => '',
                    'Nomor Invoice'     => '',
                    'Metode Pembayaran' => '',
                    'Status'            => '',
                    'Jumlah Terbayar'   => '',
                    'Total'             => '',
                    'Tanggal'           => '',
                    'Detail Produk'     => "- {$productName} x{$item->quantity} @ Rp" . number_format($item->price, 0, ',', '.'),
                ];
            }
        }
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap', 'Alamat', 'Telepon', 'Nomor Invoice',
            'Metode Pembayaran', 'Status', 'Jumlah Terbayar',
            'Total', 'Tanggal', 'Detail Produk',
        ];
    }

   public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();
            
            // 1. Sisipkan 6 baris kosong di atas untuk area Kop
            $sheet->insertNewRowBefore(1, 6);

            // --- LOGO DINAMIS (POSISI KIRI RAPAT) ---
            $drawing = new Drawing();
            $drawing->setName('Logo');
            
            if ($this->branchSetting && $this->branchSetting->logo) {
                $logoPath = public_path('storage/' . $this->branchSetting->logo);
            } else {
                $logoPath = public_path('images/wwn-cr.png');
            }

            if (file_exists($logoPath)) {
                $drawing->setPath($logoPath);
                $drawing->setHeight(60); // Ukuran tinggi logo yang ideal
                
                // Koordinat A1 agar benar-benar di pojok kiri atas
                $drawing->setCoordinates('A1'); 
                
                // Memberikan sedikit jarak (offset) agar rapi dalam sel
                $drawing->setOffsetX(5); 
                $drawing->setOffsetY(5);
                
                $drawing->setWorksheet($sheet);
            }

            // --- KOP SURAT DINAMIS (MULAI DARI KOLOM B AGAR TIDAK TERTUTUP LOGO) ---
            $namaPT = $this->branchSetting->nama_pt ?? 'PT. WOWIN PURNOMO PUTERA';
            $alamatPT = $this->branchSetting->alamat ?? 'Jl. Raya No.Km 07, Duwet, Ngetal, Kec. Pogalan, Trenggalek';
            
            // Teks dimulai dari Kolom B agar berdampingan dengan logo di Kolom A
            $sheet->setCellValue('B2', strtoupper($namaPT));
            $sheet->setCellValue('B3', $alamatPT);
            $sheet->setCellValue('B4', 'LAPORAN DATA PESANAN - ' . ($this->filterDescription));

            // Merge sel dari B sampai H (Area Teks)
            $sheet->mergeCells('B2:H2');
            $sheet->mergeCells('B3:H3');
            $sheet->mergeCells('B4:H4');

            // Styling Teks Kop (Rata Kiri agar rapat dengan logo)
            $sheet->getStyle('B2:B4')->applyFromArray([
                'font' => [
                    'bold' => true, 
                    'size' => 12,
                    'name' => 'Arial'
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // Memberikan border bawah pada area kop (Opsional, agar terlihat seperti surat resmi)
            $sheet->getStyle('A6:J6')->applyFromArray([
                'borders' => [
                    'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
                ],
            ]);

            // --- STYLING TABLE DATA ---
            $sheet->getStyle('A7:J7')->getFont()->setBold(true);

            foreach (range('A', 'J') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $rowCount = count($this->data) + 7;
            $sheet->getStyle("A7:J{$rowCount}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]);
        },
    ];
}
}