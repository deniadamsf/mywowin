<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Penyesuaian harga produk My Wowin berdasarkan kolom "Harga Web" pada spreadsheet HARGA WEB.xlsx.
     * Hanya produk yang cocok antara database dan spreadsheet yang diperbarui.
     */
    public function up(): void
    {
        $priceUpdates = [
            // [id_product => harga_karton_baru] (Harga Web Satuan x isi_karton)
            35 => 78000.00,  // Cuka Makan Rajaku 150ml (Excel: CUKA MAKAN RAJAKU = Rp 6.500 x 12)
            54 => 286000.00, // Kecap Manis Rajaku Hijau 500ml (Excel: RAJAKU HIJAU 690GRAM = Rp 14.300 x 20)
            37 => 209970.00, // Saos Raja Tomat Botol 500ml (Excel: RAJA TOMAT 500ML = Rp 6.999 x 30)
            40 => 71988.00,  // Saos Cabe Tani Ball 600gr (Excel: CABE TANI 600GR = Rp 5.999 x 12)
            42 => 209970.00, // Saos Cabe Tani 500ml (Excel: CABE TANI 500ML = Rp 6.999 x 30)
            36 => 71988.00,  // Saos Raja Tomat Ball 660ml (Excel: RAJA TOMAT 600GR = Rp 5.999 x 12)
            47 => 199980.00, // KM Jangkar Merah 500ml (Excel: JANGKAR MERAH 690GR = Rp 9.999 x 20)
            16 => 397998.00, // Manis Wowin Jirigen 6200ml (Excel: WOWIN PREMIUM JIRIGEN = Rp 198.999 x 2)
            11 => 306000.00, // Kecap Manis Wowin Pouch 600ml (Excel: WOWIN POUCH 600GR = Rp 25.500 x 12)
            12 => 499980.00, // Wowin Botol Plastik 500ml (Excel: WOWIN 690GR = Rp 24.999 x 20)
            52 => 359976.00, // Kecap Manis Wowin 380 Gram (Excel: WOWIN 380GR = Rp 14.999 x 24)
            25 => 210000.00, // Rajaku Premium Gold 550ml (Excel: RAJAKU PREMIUM GOLD 700GR = Rp 17.500 x 12)
            21 => 282000.00, // Jirigen Etiket Hijau 6200ml (Excel: RAJAKU HIJAU JIRIGEN = Rp 141.000 x 2)
            28 => 290000.00, // Rajaku Premium Gold 6200ml (Excel: RAJAKU PREMIUM GOLD JIRIGEN = Rp 145.000 x 2)
            45 => 249000.00, // KM Jangkar Jirigen 6200ml (Excel: JANGKAR MERAH JIRIGEN = Rp 124.500 x 2)
            51 => 426624.00, // Kecap Manis Wowin 192 Gram (Excel: WOWIN 192GR = Rp 8.888 x 48)
        ];

        foreach ($priceUpdates as $id => $harga) {
            DB::table('products')
                ->where('id_product', $id)
                ->update([
                    'harga' => $harga,
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     * Mengembalikan harga produk ke nilai awal (harga per karton/dus sebelum penyesuaian).
     */
    public function down(): void
    {
        $previousPrices = [
            35 => 36000.00,
            54 => 174000.00,
            37 => 63000.00,
            42 => 75000.00,
            36 => 24600.00,
            47 => 112000.00,
            16 => 268600.00,
            11 => 211800.00,
            12 => 258000.00,
            52 => 216000.00,
            25 => 130800.00,
            28 => 182500.00,
            45 => 117800.00,
            51 => 235200.00,
            40 => 28200.00,
        ];

        foreach ($previousPrices as $id => $harga) {
            DB::table('products')
                ->where('id_product', $id)
                ->update([
                    'harga' => $harga,
                    'updated_at' => now(),
                ]);
        }
    }
};
