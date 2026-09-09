<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class JntService
{
    /**
     * Hitung tanda tangan keamanan (data_sign) sesuai spesifikasi J&T Express:
     * base64(md5(data_param + key))
     */
    public static function generateSignature(string $dataParam, string $key): string
    {
        return base64_encode(md5($dataParam . $key));
    }

    /**
     * Cek apakah alamat tujuan berada di Jawa Timur & Madura (Paket VIP Jawara Rp 4.500/kg)
     */
    public static function isJatimDanMadura(?string $alamat): bool
    {
        if (empty($alamat) || trim($alamat) === '-') {
            return true; // Default basis operasional Wowin (Trenggalek, Jatim)
        }

        $alamatLower = strtolower($alamat);

        $kataKunciJatimMadura = [
            // Wilayah & Provinsi
            'jawa timur', 'jawatimur', 'jatim', 'madura',
            // Pulau Madura (4 Kabupaten)
            'bangkalan', 'sampang', 'pamekasan', 'sumenep',
            // Kota & Kabupaten di Jawa Timur
            'surabaya', 'sby', 'sidoarjo', 'sda', 'gresik', 'mojokerto', 'jombang',
            'lamongan', 'tuban', 'bojonegoro', 'madiun', 'magetan',
            'ngawi', 'ponorogo', 'pacitan', 'kediri', 'nganjuk',
            'blitar', 'tulungagung', 'trenggalek', 'malang', 'mlg', 'batu',
            'pasuruan', 'probolinggo', 'lumajang', 'jember', 'bondowoso',
            'situbondo', 'banyuwangi', 'bwi'
        ];

        foreach ($kataKunciJatimMadura as $kw) {
            if (str_contains($alamatLower, $kw)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Cek apakah alamat tujuan berada di Pulau Jawa
     */
    public static function isPulauJawa(?string $alamat): bool
    {
        if (empty($alamat) || trim($alamat) === '-') {
            return true;
        }

        if (self::isJatimDanMadura($alamat)) {
            return true;
        }

        $alamatLower = strtolower($alamat);

        $kataKunciJawaLainnya = [
            // Provinsi
            'jawa tengah', 'jawatengah', 'jateng', 'jawa barat', 'jawabarat', 'jabar',
            'dki jakarta', 'jakarta', 'jaksel', 'jakbar', 'jaktim', 'jakpus', 'jakut',
            'banten', 'yogyakarta', 'jogja', 'diy',
            // Jawa Tengah & DIY
            'semarang', 'smg', 'solo', 'surakarta', 'slo', 'kudus', 'pati', 'jepara',
            'demak', 'salatiga', 'magelang', 'klaten', 'boyolali', 'sukoharjo',
            'karanganyar', 'wonogiri', 'sragen', 'purwodadi', 'grobogan', 'rembang',
            'blora', 'kendal', 'batang', 'pekalongan', 'pemalang', 'tegal', 'brebes',
            'cilacap', 'banyumas', 'purwokerto', 'purbalingga', 'banjarnegara',
            'kebumen', 'purworejo', 'wonosobo', 'temanggung',
            'sleman', 'bantul', 'gunungkidul', 'kulon progo',
            // Jawa Barat, Banten & DKI
            'bandung', 'bdg', 'cimahi', 'bogor', 'bgr', 'depok', 'bekasi', 'bks', 'cirebon', 'crb', 'sukabumi',
            'tasikmalaya', 'garut', 'subang', 'purwakarta', 'karawang', 'ciamis',
            'kuningan', 'majalengka', 'sumedang', 'indramayu', 'cianjur', 'pangandaran',
            'tangerang', 'tangsel', 'serang', 'cilegon', 'lebak', 'pandeglang',
            'cikarang', 'tambun', 'cibinong', 'kartasura', 'ungaran'
        ];

        foreach ($kataKunciJawaLainnya as $kw) {
            if (str_contains($alamatLower, $kw)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Tentukan kode zona pengiriman: 'jatim_madura', 'jawa_non_jatim', atau 'luar_jawa'
     */
    public static function determineShippingZone(?string $alamat): string
    {
        if (self::isJatimDanMadura($alamat)) {
            return 'jatim_madura';
        } elseif (self::isPulauJawa($alamat)) {
            return 'jawa_non_jatim';
        }
        return 'luar_jawa';
    }

    /**
     * Hitung berat kotor satu item produk (dalam Gram)
     * Menggunakan acuan resmi HARGA WEB.xlsx atau kalkulasi otomatis berdasarkan isi_ml
     */
    public static function getProductGrossWeightGram($product): float
    {
        if (!$product) {
            return 500.0;
        }

        $beratG = (float) ($product->berat ?? 0);
        if ($beratG > 0) {
            return $beratG;
        }

        // Fallback formula jika data berat di database belum terisi:
        $ml = (float) ($product->isi_ml ?? 0);
        if ($ml >= 5000) {
            return (float) round($ml * 1.15);
        } elseif ($ml > 0) {
            return (float) max(200.0, round($ml * 1.2));
        }

        return 500.0;
    }

    /**
     * Hitung berat kotor satu paket bundling (dalam Gram)
     */
    public static function getBundlingGrossWeightGram($bundling): float
    {
        if (!$bundling) {
            return 1000.0;
        }

        $beratG = (float) ($bundling->berat ?? 0);
        if ($beratG > 0) {
            return $beratG;
        }

        // Jika bundling memiliki relasi produk penyusun, akumulasikan
        if ($bundling->relationLoaded('products') && $bundling->products && $bundling->products->isNotEmpty()) {
            $sum = 0.0;
            foreach ($bundling->products as $p) {
                $sum += self::getProductGrossWeightGram($p);
            }
            if ($sum > 0) {
                return $sum;
            }
        }

        return 1000.0; // Fallback rata-rata bundling 1 Kg
    }

    /**
     * Hitung total estimasi berat pesanan (Order) dalam kilogram (Kg)
     */
    public static function calculateTotalWeight(Order $order): float
    {
        $totalGram = 0.0;

        foreach ($order->orderItems as $item) {
            $qty = (int) ($item->quantity ?: 1);

            if ($item->product) {
                $isKarton = str_contains(strtolower($item->product_name ?? ''), 'karton')
                    || (isset($item->unit) && strtolower($item->unit) === 'karton');
                $beratSatuanG = self::getProductGrossWeightGram($item->product);

                if ($isKarton) {
                    $isiKarton = (int) ($item->product->isi_karton ?: 12);
                    $totalGram += ($beratSatuanG * $isiKarton) * $qty;
                } else {
                    $totalGram += $beratSatuanG * $qty;
                }
            } elseif ($item->bundling) {
                $beratBundlingG = self::getBundlingGrossWeightGram($item->bundling);
                $totalGram += $beratBundlingG * $qty;
            } else {
                $totalGram += 500.0 * $qty;
            }
        }

        $totalKg = $totalGram / 1000.0;
        // Minimal hitungan pengiriman J&T Express adalah 1.0 Kg
        return max(1.0, round($totalKg, 2));
    }

    /**
     * Hitung total estimasi berat keranjang belanja (Cart) dalam kilogram (Kg)
     */
    public static function calculateCartWeight($cartItems): float
    {
        $totalGram = 0.0;

        foreach ($cartItems as $cart) {
            $qty = (int) ($cart->quantity ?: 1);

            if ($cart->product) {
                $isKarton = strtolower($cart->unit ?? '') === 'karton'
                    || str_contains(strtolower($cart->product->nama_produk ?? ''), 'karton');
                $beratSatuanG = self::getProductGrossWeightGram($cart->product);

                if ($isKarton) {
                    $isiKarton = (int) ($cart->product->isi_karton ?: 12);
                    $totalGram += ($beratSatuanG * $isiKarton) * $qty;
                } else {
                    $totalGram += $beratSatuanG * $qty;
                }
            } elseif ($cart->bundling) {
                $beratBundlingG = self::getBundlingGrossWeightGram($cart->bundling);
                $totalGram += $beratBundlingG * $qty;
            } else {
                $totalGram += 500.0 * $qty;
            }
        }

        $totalKg = $totalGram / 1000.0;
        return max(1.0, round($totalKg, 2));
    }

    /**
     * Hitung ongkir berdasarkan alamat dan berat (Paket VIP J&T Jawara):
     * - Jatim + Madura: Rp 4.500 / Kg
     * - Pulau Jawa lainnya: Rp 9.500 / Kg
     * - Luar Jawa: Rp 25.000 / Kg
     * Serta evaluasi voucher diskon ongkir (Rp 4.500 untuk min belanja Rp 10.000).
     */
    public static function calculateShippingCost(float $weightKg, ?string $alamat, float $subtotal = 0.0): array
    {
        $weightRounded = (int) ceil($weightKg);
        $zone = self::determineShippingZone($alamat);

        $voucher = class_exists(\App\Models\ShippingVoucher::class) ? \App\Models\ShippingVoucher::getActiveVoucher() : null;
        $rateJatimMadura = $voucher ? (float)$voucher->base_rate_per_kg : (float)config('jnt.vip_jawara_jatim_madura', 4500);
        $rateJawaNonJatim = ($voucher && isset($voucher->rate_jawa_non_jatim)) ? (float)$voucher->rate_jawa_non_jatim : (float)config('jnt.vip_jawara_jawa_non_jatim', 9500);
        $rateLuarJawa = (float)config('jnt.regular_rate_luar_jawa', 25000);

        if ($zone === 'jatim_madura') {
            $applicableRate = $rateJatimMadura;
            $grossCost = $weightRounded * $applicableRate;
            $deskripsi = "Tarif VIP J&T Jawara Rp " . number_format($applicableRate, 0, ',', '.') . "/Kg (Jatim & Madura)";
        } elseif ($zone === 'jawa_non_jatim') {
            $applicableRate = $rateJawaNonJatim;
            $grossCost = $weightRounded * $applicableRate;
            $deskripsi = "Tarif VIP J&T Jawara Rp " . number_format($applicableRate, 0, ',', '.') . "/Kg (Pulau Jawa)";
        } else {
            $applicableRate = $rateLuarJawa;
            $grossCost = $weightRounded * $applicableRate;
            $deskripsi = "Tarif Reguler J&T Luar Pulau Jawa";
        }

        // Evaluasi Diskon Voucher Ongkir
        $discountOngkir = 0.0;
        $voucherApplied = false;
        $voucherCode = null;
        $shortfall = 0.0;

        if ($voucher) {
            if ($subtotal >= $voucher->min_purchase) {
                // Potongan ongkir maksimal sebesar nominal voucher (misal Rp 4.500 untuk gratis 1 Kg pertama)
                $discountOngkir = min((float)$grossCost, (float)$voucher->discount_amount);
                $voucherApplied = $discountOngkir > 0;
                $voucherCode = $voucher->code;
            } else {
                $shortfall = max(0.0, $voucher->min_purchase - $subtotal);
            }
        }

        $netCost = max(0.0, $grossCost - $discountOngkir);

        return [
            'zone' => $zone,
            'is_jawa' => $zone !== 'luar_jawa',
            'is_jatim_madura' => $zone === 'jatim_madura',
            'weight_kg' => $weightKg,
            'weight_rounded' => $weightRounded,
            'rate_per_kg' => $applicableRate,
            'shipping_cost' => $grossCost,
            'shipping_discount' => $discountOngkir,
            'net_shipping_cost' => $netCost,
            'voucher_applied' => $voucherApplied,
            'voucher_code' => $voucherCode,
            'voucher_shortfall' => $shortfall,
            'voucher_min_purchase' => $voucher ? $voucher->min_purchase : 10000.0,
            'voucher_discount_amount' => $voucher ? $voucher->discount_amount : 4500.0,
            'description' => $deskripsi,
        ];
    }

    /**
     * Terbitkan Resi Otomatis (Create Order) ke API J&T Express
     */
    public static function createOrder(Order $order): array
    {
        try {
            $key = config('jnt.key');
            $username = config('jnt.username');
            $apiKey = config('jnt.api_key');
            $endpoint = config('jnt.order_endpoint');

            if (empty($key) || empty($username) || empty($apiKey) || empty($endpoint)) {
                return [
                    'success' => false,
                    'message' => 'Konfigurasi kredensial J&T Express belum lengkap.'
                ];
            }

            // Hitung berat
            $weightKg = self::calculateTotalWeight($order);
            $weightCeil = (int) ceil($weightKg);

            // Bersihkan data penerima
            $user = $order->user;
            $receiverName = $user->nama_lengkap ?? 'Mitra Pelanggan Wowin';
            $rawPhone = $user->membership->no_hp ?? $user->no_telp ?? '081216301220';
            $receiverPhone = preg_replace('/[^0-9]/', '', $rawPhone);
            if (str_starts_with($receiverPhone, '0')) {
                $receiverPhone = '62' . substr($receiverPhone, 1);
            }
            if (empty($receiverPhone)) {
                $receiverPhone = '6281216301220';
            }

            $receiverAddress = !empty($order->alamat) ? $order->alamat : 'Alamat mitra belum dilengkapi';

            // Susun nama barang
            $itemNames = $order->orderItems->pluck('product_name')->filter()->take(3)->implode(', ');
            if (empty($itemNames)) {
                $itemNames = 'Produk Wowin Food';
            }

            $orderId = 'WOWIN-' . $order->id . '-' . strtoupper(substr(md5($order->invoice_number), 0, 6));

            $orderData = [
                'detail' => [
                    [
                        'username' => $username,
                        'api_key' => $apiKey,
                        'orderid' => $orderId,
                        'shipper_name' => config('jnt.shipper.name', 'PT WOWIN PURNOMO PUTERA'),
                        'shipper_contact' => config('jnt.shipper.contact', 'William Purnomo'),
                        'shipper_phone' => config('jnt.shipper.phone', '081216301220'),
                        'shipper_addr' => config('jnt.shipper.address', 'Jl. Raya No. KM 07, Duwet, Ngetal, Kec. Pogalan, Trenggalek'),
                        'origin_code' => config('jnt.shipper.origin_code', 'SUB'),
                        'receiver_name' => $receiverName,
                        'receiver_phone' => $receiverPhone,
                        'receiver_addr' => $receiverAddress,
                        'receiver_zip' => config('jnt.shipper.zip', '66371'),
                        'destination_code' => 'SUB',
                        'receiver_area' => 'SUB001',
                        'qty' => (string) ($order->orderItems->sum('quantity') ?: 1),
                        'weight' => (string) $weightCeil,
                        'goodsdesc' => 'Produk Wowin Food (Grosir)',
                        'servicetype' => '6', // EZ
                        'insurance' => '0',
                        'orderdate' => now()->format('Y-m-d H:i:s'),
                        'item_name' => Str::limit($itemNames, 90),
                        'cod' => '0',
                        'sendstarttime' => now()->format('Y-m-d H:i:s'),
                        'sendendtime' => now()->addHours(6)->format('Y-m-d H:i:s'),
                        'expresstype' => 'EZ',
                        'goodsvalue' => (string) (int) $order->total,
                    ]
                ]
            ];

            $dataParam = json_encode($orderData);
            $dataSign = self::generateSignature($dataParam, $key);

            // Kirim request ke J&T Gateway
            $response = Http::asForm()->timeout(20)->post($endpoint, [
                'data_param' => $dataParam,
                'data_sign' => $dataSign,
            ]);

            Log::info('J&T Create Order Response for Order #' . $order->id, [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['success']) && $result['success'] === true && !empty($result['detail'])) {
                    $detail = $result['detail'][0];
                    if (($detail['status'] ?? '') === 'Sukses') {
                        $awbNo = $detail['awb_no'] ?? null;
                        $desCode = $detail['desCode'] ?? null;

                        // Simpan ke database order
                        $order->update([
                            'no_resi' => $awbNo,
                            'jnt_order_id' => $orderId,
                            'jnt_des_code' => $desCode,
                            'shipping_courier' => 'J&T Express (EZ)',
                            'shipping_status' => 'Menunggu Pickup',
                            'total_weight_kg' => $weightKg,
                            'status' => 'shipped', // Otomatis tandai dikirim
                        ]);

                        return [
                            'success' => true,
                            'awb_no' => $awbNo,
                            'des_code' => $desCode,
                            'order_id' => $orderId,
                            'message' => "Nomor Resi J&T ($awbNo) berhasil diterbitkan!"
                        ];
                    } else {
                        $reason = $detail['reason'] ?? 'Gagal memproses order J&T.';
                        return ['success' => false, 'message' => "J&T menolak order: $reason"];
                    }
                }
            }

            return [
                'success' => false,
                'message' => 'Gagal terhubung ke API J&T Express (' . $response->status() . ').'
            ];
        } catch (\Exception $e) {
            Log::error('JntService createOrder exception: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menghubungi J&T: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Batalkan Resi J&T (Order Cancellation)
     */
    public static function cancelOrder(Order $order, string $reason = 'Dibatalkan oleh Admin Wowin'): array
    {
        try {
            if (empty($order->jnt_order_id)) {
                return ['success' => false, 'message' => 'Order ini belum memiliki ID Order J&T.'];
            }

            $key = config('jnt.key');
            $username = config('jnt.username');
            $apiKey = config('jnt.api_key');
            $endpoint = config('jnt.cancel_endpoint');

            $cancelData = [
                'detail' => [
                    [
                        'username' => $username,
                        'api_key' => $apiKey,
                        'orderid' => $order->jnt_order_id,
                        'reason' => $reason,
                    ]
                ]
            ];

            $dataParam = json_encode($cancelData);
            $dataSign = self::generateSignature($dataParam, $key);

            $response = Http::asForm()->timeout(15)->post($endpoint, [
                'data_param' => $dataParam,
                'data_sign' => $dataSign,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['success']) && $result['success'] === true) {
                    $order->update([
                        'shipping_status' => 'Resi Dibatalkan',
                    ]);

                    return [
                        'success' => true,
                        'message' => 'Resi J&T (' . $order->no_resi . ') berhasil dibatalkan.'
                    ];
                }
            }

            return ['success' => false, 'message' => 'Gagal membatalkan resi di sistem J&T.'];
        } catch (\Exception $e) {
            Log::error('JntService cancelOrder exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Kesalahan saat membatalkan resi: ' . $e->getMessage()];
        }
    }

    /**
     * Dapatkan link resmi pelacakan resi J&T Express
     */
    public static function getTrackingUrl(?string $noResi): string
    {
        if (empty($noResi)) {
            return 'https://www.jet.co.id/track';
        }
        return 'https://www.jet.co.id/track?awb=' . urlencode($noResi);
    }
}
