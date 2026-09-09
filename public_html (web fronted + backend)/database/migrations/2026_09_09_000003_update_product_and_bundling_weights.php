<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Bundling;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ubah tipe kolom berat pada tabel products ke DECIMAL(10,2) agar dapat menampung angka bobot riil dalam gram
        try {
            DB::statement("ALTER TABLE products MODIFY COLUMN berat DECIMAL(10,2) NULL");
        } catch (\Throwable $e) {
            // Abaikan jika sudah diubah
        }

        // 2. Tambahkan kolom berat pada tabel bundlings jika belum ada
        if (!Schema::hasColumn('bundlings', 'berat')) {
            Schema::table('bundlings', function (Blueprint $table) {
                $table->decimal('berat', 10, 2)->nullable()->after('price');
            });
        }

        // 3. Kamus Pemetaan Berat Riil (Gram) dari HARGA WEB.xlsx
        $weightMapById = [
            11 => 800.0,   // Kecap Manis Wowin Pouch 600ml (Excel: WOWIN POUCH 600GR = 800gr)
            12 => 600.0,   // Wowin Botol Plastik 500ml (500ml x 1.2)
            13 => 725.0,   // Kecap Manis Wowin 625ml (Excel: WOWIN 690GR = 725gr)
            14 => 725.0,   // Kecap Manis Wowin 625ml (Excel: WOWIN 690GR = 725gr)
            16 => 7000.0,  // Manis Wowin Jirigen 6200ml (Excel: WOWIN PREMIUM JIRIGEN = 7000gr)
            17 => 30000.0, // Manis Wowin Jirigen 26000ml (26L jumbo jerigen = 30000gr)
            18 => 720.0,   // Manis Etiket Kuning 625ml
            19 => 7000.0,  // Jirigen Etiket Kuning 6200ml
            20 => 720.0,   // Manis Etiket Hijau 625ml (Excel: RAJAKU HIJAU 690GRAM = 720gr)
            21 => 7000.0,  // Jirigen Etiket Hijau 6200ml (Excel: RAJAKU HIJAU JIRIGEN = 7000gr)
            22 => 700.0,   // Rajaku Etiket Merah 625ml (Excel: JANGKAR MERAH 690GR = 700gr)
            23 => 700.0,   // Manis Etiket Merah 625ml (Excel: JANGKAR MERAH 690GR = 700gr)
            24 => 6200.0,  // Jirigen Etiket Merah 6200ml (Excel: JANGKAR MERAH JIRIGEN = 6200gr)
            25 => 715.0,   // Rajaku Premium Gold 550ml
            26 => 650.0,   // Rajaku Premium Gold 500ml
            27 => 1000.0,  // Rajaku Premium Gold 625ml (Excel: RAJAKU PREMIUM GOLD 700GR = 1000gr)
            28 => 7000.0,  // Rajaku Premium Gold 6200ml (Excel: RAJAKU PREMIUM GOLD JIRIGEN = 7000gr)
            35 => 500.0,   // Cuka Makan Rajaku 150ml (Excel: CUKA MAKAN RAJAKU = 500gr)
            36 => 720.0,   // Saos Raja Tomat Ball 660ml (Excel: RAJA TOMAT 600GR = 720gr)
            37 => 650.0,   // Saos Raja Tomat Botol 500ml (Excel: RAJA TOMAT 500ML = 650gr)
            38 => 720.0,   // Raja Tomat Botol 625ml (Excel: RAJA TOMAT 600GR = 720gr)
            39 => 6000.0,  // Raja Tomat Jirigen 5200ml
            40 => 720.0,   // Saos Cabe Tani Ball 600ml (Excel: CABE TANI 600GR = 720gr)
            41 => 720.0,   // Saos Cabe Tani Dos 600ml (Excel: CABE TANI 600GR = 720gr)
            42 => 650.0,   // Saos Cabe Tani 500ml (Excel: CABE TANI 500ML = 650gr)
            43 => 720.0,   // Saos Cabe Tani 625ml (Excel: CABE TANI 600GR = 720gr)
            44 => 6000.0,  // Saos Cabe Tani Jirigen 5200ml
            45 => 6200.0,  // KM Jangkar Jirigen 6200ml (Excel: JANGKAR MERAH JIRIGEN = 6200gr)
            46 => 30000.0, // KM Jangkar Jirigen 26000ml
            47 => 600.0,   // KM Jangkar Merah 500ml
            48 => 600.0,   // KM Jangkar Biru 500ml
            50 => 600.0,   // Paket Murmer 500ml
            51 => 210.0,   // Kecap Manis Wowin 192 Gram (Excel: WOWIN 192GR = 210gr)
            52 => 500.0,   // Kecap Manis Wowin 380 Gram (Excel: WOWIN 380GR = 500gr)
            53 => 720.0,   // Saos Raja Tomat Dus 660ml (Excel: RAJA TOMAT 600GR = 720gr)
            54 => 600.0,   // Kecap Manis Rajaku Hijau 500ml (Excel: RAJAKU HIJAU 500ML = 600gr)
        ];

        // Update seluruh produk yang ada
        $products = Product::all();
        foreach ($products as $p) {
            if (isset($weightMapById[$p->id_product])) {
                $p->berat = $weightMapById[$p->id_product];
            } else {
                // Fallback kalkulasi otomatis berdasarkan isi_ml:
                // Jika jerigen besar (>= 5000 ml): berat jenis ~1.15
                // Jika botol/pouch: berat jenis ~1.2, minimum 200 gram
                $ml = (float) ($p->isi_ml ?: 0);
                if ($ml >= 5000) {
                    $p->berat = round($ml * 1.15);
                } elseif ($ml > 0) {
                    $p->berat = max(200.0, round($ml * 1.2));
                } else {
                    $p->berat = 500.0;
                }
            }
            $p->save();
        }

        // 4. Update Bundling dengan estimasi bobot paket (Gram)
        $bundlingWeights = [
            15 => 1000.0, // PAKET BUNDLING MURAH LEBAY (1.00 Kg)
            16 => 1000.0, // Paket Bundling Murah Lebay 2 (1.00 Kg)
            17 => 800.0,  // Paket Bundling Murah Lebay 3 (0.80 Kg)
        ];

        foreach ($bundlingWeights as $id => $weight) {
            Bundling::where('id_bundling', $id)->update(['berat' => $weight]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('bundlings', 'berat')) {
            Schema::table('bundlings', function (Blueprint $table) {
                $table->dropColumn('berat');
            });
        }
    }
};
