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

            // Cegah duplikasi resi jika pesanan sudah memiliki resi aktif
            if (!empty($order->no_resi) && $order->shipping_status !== 'Resi Dibatalkan') {
                return [
                    'success' => false,
                    'message' => 'Pesanan ini sudah memiliki nomor resi J&T (' . $order->no_resi . '). Batalkan resi terlebih dahulu jika ingin membuat resi baru.'
                ];
            }

            // Hitung berat
            $weightKg = self::calculateTotalWeight($order);
            $weightCeil = (int) ceil($weightKg);

            // Bersihkan data penerima
            $user = $order->user;
            $receiverName = $user->nama_lengkap ?? 'Mitra Pelanggan Wowin';
            $rawPhone = $user->membership->no_hp ?? $user->no_telp ?? '0812106600';
            $receiverPhone = preg_replace('/[^0-9]/', '', $rawPhone);
            if (str_starts_with($receiverPhone, '0')) {
                $receiverPhone = '62' . substr($receiverPhone, 1);
            }
            if (empty($receiverPhone)) {
                $receiverPhone = '62812106600';
            }

            $receiverAddress = !empty($order->alamat) ? $order->alamat : 'Alamat mitra belum dilengkapi';

            // Resolusi kode tujuan (destination_code) & area (receiver_area) resmi J&T
            // Mendukung deteksi berbasis teks alamat dan fallback kantor cabang mitra
            $userBranch = $user->kantor_cabang ?? null;
            $routing = self::resolveDestinationAndArea($receiverAddress, $userBranch);
            $destinationCode = $routing['destination_code'];
            $receiverArea = $routing['receiver_area'];

            // Ekstraksi kodepos 5 digit secara dinamis dari teks alamat penerima
            $receiverZip = '66371';
            if (preg_match('/\b(\d{5})\b/', $receiverAddress, $matches)) {
                $receiverZip = $matches[1];
            }

            // Susun nama barang
            $itemNames = $order->orderItems->pluck('product_name')->filter()->take(3)->implode(', ');
            if (empty($itemNames)) {
                $itemNames = 'Produk Wowin Food';
            }

            // Order ID unik (disertai timestamp unik agar re-generate pasca batal resi tidak ditolak J&T)
            $orderId = 'WOWIN-' . $order->id . '-' . strtoupper(substr(md5($order->invoice_number . '-' . now()->timestamp), 0, 6));

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
                        'origin_code' => config('jnt.shipper.origin_code', 'TGK'),
                        'receiver_name' => $receiverName,
                        'receiver_phone' => $receiverPhone,
                        'receiver_addr' => $receiverAddress,
                        'receiver_zip' => $receiverZip,
                        'destination_code' => $destinationCode,
                        'receiver_area' => $receiverArea,
                        'qty' => (string) ($order->orderItems->sum('quantity') ?: 1),
                        'weight' => (string) $weightCeil,
                        'goodsdesc' => 'Produk Wowin Food (Grosir)',
                        'servicetype' => '1', // 1: EZ (Standar E-Commerce J&T)
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
                    $oldAwb = $order->no_resi;
                    $order->update([
                        'no_resi' => null,
                        'jnt_des_code' => null,
                        'jnt_order_id' => null,
                        'shipping_status' => 'Resi Dibatalkan',
                        'status' => 'paid',
                    ]);

                    return [
                        'success' => true,
                        'message' => 'Resi J&T (' . $oldAwb . ') berhasil dibatalkan.'
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

    /**
     * Lacak Perjalanan Paket secara Real-Time melalui API J&T Express
     * Jika paket belum discan oleh kurir J&T (API return 404),
     * sediakan timeline milestone gudang/internal secara mulus.
     */
    public static function trackOrder(string $noResi, ?Order $order = null): array
    {
        $noResi = trim($noResi);
        if (empty($noResi)) {
            return [
                'success' => false,
                'message' => 'Nomor resi tidak valid atau kosong.',
                'status' => 'Belum Ada Resi',
                'checkpoints' => [],
            ];
        }

        if (!$order) {
            try {
                $order = Order::where('no_resi', $noResi)->first();
            } catch (\Throwable $e) {
                // Ignore DB connection issues
            }
        }

        $trackingUrl = self::getTrackingUrl($noResi);
        $username = config('jnt.username', 'SUB-PT-WOWINFOOD');
        $password = config('jnt.track_password', '8yz0HYv4puRV');
        $endpoint = config('jnt.track_endpoint', 'https://secure-jk.jet.co.id/jandt-order-web/track/trackAction!tracking.action');

        $jntCheckpoints = [];
        $apiSuccess = false;
        $jntStatus = null;

        try {
            $response = Http::withBasicAuth($username, $password)
                ->asForm()
                ->timeout(12)
                ->post($endpoint, [
                    'billCodes' => $noResi,
                    'lang' => 'id',
                ]);

            if ($response->successful()) {
                $data = $response->json();

                $details = [];
                if (is_array($data)) {
                    if (isset($data[0]['details']) && is_array($data[0]['details'])) {
                        $details = $data[0]['details'];
                        $jntStatus = $data[0]['status'] ?? null;
                    } elseif (isset($data['details']) && is_array($data['details'])) {
                        $details = $data['details'];
                        $jntStatus = $data['status'] ?? null;
                    } elseif (isset($data['history']) && is_array($data['history'])) {
                        $details = $data['history'];
                    }
                }

                if (!empty($details)) {
                    $apiSuccess = true;
                    foreach ($details as $item) {
                        $time = $item['date_time'] ?? $item['scanTime'] ?? $item['scantime'] ?? $item['time'] ?? '';
                        $type = $item['status'] ?? $item['scanType'] ?? $item['scantype'] ?? 'Update Pengiriman';
                        $desc = $item['note'] ?? $item['desc'] ?? $item['reason'] ?? $item['context'] ?? '';
                        $location = $item['city_name'] ?? $item['scanNetworkName'] ?? $item['city'] ?? $item['location'] ?? '';

                        $jntCheckpoints[] = [
                            'time' => $time,
                            'title' => $type,
                            'description' => $desc,
                            'location' => $location,
                            'is_completed' => true,
                            'is_current' => false,
                        ];
                    }

                    if (!empty($jntCheckpoints)) {
                        $jntCheckpoints[0]['is_current'] = true;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning("JntService trackOrder API call failed for $noResi: " . $e->getMessage());
        }

        // Jika J&T API berhasil mengembalikan checkpoint live
        if ($apiSuccess && !empty($jntCheckpoints)) {
            $currentStatus = $jntStatus ?? $jntCheckpoints[0]['title'];
            return [
                'success' => true,
                'source' => 'jnt_live',
                'no_resi' => $noResi,
                'courier' => 'J&T Express (EZ)',
                'des_code' => $order?->jnt_des_code,
                'status' => $currentStatus,
                'is_delivered' => stripos($currentStatus, 'delivered') !== false || stripos($currentStatus, 'terkirim') !== false,
                'tracking_url' => $trackingUrl,
                'checkpoints' => $jntCheckpoints,
                'order' => $order ? [
                    'id' => $order->id,
                    'invoice_number' => $order->invoice_number,
                    'recipient_name' => $order->user?->name ?? 'Pelanggan',
                    'alamat' => $order->alamat,
                ] : null,
            ];
        }

        // Fallback: Timeline terstruktur berdasarkan tahapan pemrosesan sistem & gudang
        $checkpoints = [];

        // 1. Pesanan Dibuat
        if ($order && $order->created_at) {
            $checkpoints[] = [
                'time' => $order->created_at->translatedFormat('d M Y, H:i'),
                'title' => 'Pesanan Dibuat',
                'description' => 'Pesanan berhasil dibuat oleh pelanggan (' . $order->invoice_number . ').',
                'location' => 'Sistem Wowin',
                'is_completed' => true,
                'is_current' => false,
            ];
        }

        // 2. Pembayaran
        $isPaid = $order && ($order->payment_status === 'paid' || $order->status === 'paid' || $order->status === 'completed' || $order->status === 'shipped');
        if ($isPaid) {
            $paidTime = $order->paid_at ? $order->paid_at->translatedFormat('d M Y, H:i') : ($order->created_at ? $order->created_at->translatedFormat('d M Y, H:i') : '');
            $checkpoints[] = [
                'time' => $paidTime,
                'title' => 'Pembayaran Dikonfirmasi',
                'description' => 'Pembayaran telah berhasil diverifikasi oleh sistem Wowin.',
                'location' => 'Sistem Wowin',
                'is_completed' => true,
                'is_current' => false,
            ];
        }

        // 3. Resi J&T Diterbitkan
        $checkpoints[] = [
            'time' => $order && $order->updated_at ? $order->updated_at->translatedFormat('d M Y, H:i') : now()->translatedFormat('d M Y, H:i'),
            'title' => 'Resi J&T Diterbitkan',
            'description' => "Nomor resi resmi J&T Express ($noResi) telah berhasil diterbitkan.",
            'location' => 'Gudang Wowin (Trenggalek)',
            'is_completed' => true,
            'is_current' => false,
        ];

        // 4. Menunggu Pick Up / Serah Terima Kurir
        $isDelivered = $order && ($order->status === 'completed' || $order->shipping_status === 'Terkirim');
        $checkpoints[] = [
            'time' => $isDelivered ? '-' : 'Dalam Proses',
            'title' => 'Menunggu Pick Up Kurir J&T',
            'description' => 'Paket sedang disiapkan di gudang Wowin dan menunggu penjemputan atau pemindaian barcode oleh kurir J&T Express.',
            'location' => 'Gudang Wowin (Trenggalek)',
            'is_completed' => $isDelivered,
            'is_current' => !$isDelivered,
        ];

        // 5. Dalam Perjalanan Hub J&T
        $checkpoints[] = [
            'time' => '-',
            'title' => 'Dalam Pengiriman J&T',
            'description' => 'Paket akan diproses melalui jaringan drop point dan transit hub J&T Express menuju kota tujuan.',
            'location' => !empty($order?->jnt_des_code) ? 'Tujuan: ' . $order->jnt_des_code : 'Drop Point / Hub J&T',
            'is_completed' => $isDelivered,
            'is_current' => false,
        ];

        // 6. Diterima Pembeli
        $checkpoints[] = [
            'time' => '-',
            'title' => 'Paket Diterima',
            'description' => 'Paket tiba di alamat tujuan dan diterima oleh pembeli.',
            'location' => $order?->alamat ? Str::limit($order->alamat, 40) : 'Alamat Tujuan',
            'is_completed' => $isDelivered,
            'is_current' => $isDelivered,
        ];

        // Balikkan urutan checkpoint agar checkpoint terbaru berada di atas
        $reversed = array_reverse($checkpoints);

        return [
            'success' => true,
            'source' => 'internal_pre_pickup',
            'no_resi' => $noResi,
            'courier' => 'J&T Express (EZ)',
            'des_code' => $order?->jnt_des_code,
            'status' => $order?->shipping_status ?? 'Menunggu Pick Up Kurir',
            'is_delivered' => $isDelivered,
            'tracking_url' => $trackingUrl,
            'checkpoints' => $reversed,
            'order' => $order ? [
                'id' => $order->id,
                'invoice_number' => $order->invoice_number,
                'recipient_name' => $order->user?->name ?? 'Pelanggan',
                'alamat' => $order->alamat,
            ] : null,
        ];
    }

    /**
     * Resolusi cerdas destination_code dan receiver_area J&T Express
     * berdasarkan nama kota/kabupaten, kecamatan, dan fallback kantor cabang mitra.
     * Menggunakan kode resmi gateway dan drop point yang 100% tervalidasi oleh API J&T.
     */
    public static function resolveDestinationAndArea(string $address, ?string $userBranch = null): array
    {
        $addr = strtolower($address);

        // 1. Kasus khusus / disambiguasi nama kecamatan/wilayah yang sama di berbagai kota
        if (str_contains($addr, 'gondang')) {
            if (str_contains($addr, 'bojonegoro') || str_contains($addr, 'bje')) {
                return ['destination_code' => 'BJE', 'receiver_area' => 'BJE001'];
            }
            if (str_contains($addr, 'mojokerto') || str_contains($addr, 'mjk')) {
                return ['destination_code' => 'MJK', 'receiver_area' => 'MJK001'];
            }
            if (str_contains($addr, 'tulungagung') || str_contains($addr, 'tla')) {
                return ['destination_code' => 'TLA', 'receiver_area' => 'TLA001'];
            }
            if (str_contains($addr, 'sragen')) {
                return ['destination_code' => 'SOC', 'receiver_area' => 'SOC001'];
            }
            if (str_contains($addr, 'nganjuk')) {
                return ['destination_code' => 'NGK', 'receiver_area' => 'NGK001'];
            }
        }

        if (str_contains($addr, 'woyla')) {
            return ['destination_code' => 'MEH', 'receiver_area' => 'MEH010'];
        }

        if (str_contains($addr, 'buah batu') || str_contains($addr, 'buahbatu')) {
            return ['destination_code' => 'BDO', 'receiver_area' => 'BDO001'];
        }

        // 2. Prioritas Tertinggi: Kabupaten Trenggalek & Seluruh Kecamatannya (Basis Pusat Distribusi Wowin)
        $trenggalekKeywords = [
            'trenggalek', 'durenan', 'pogalan', 'watulimo', 'karangan',
            'kampak', 'panggul', 'munjungan', 'dongko', 'pule',
            'bendungan', 'tugu', 'suruh', 'prigi', 'kendalrejo',
            'ngadirenggo', 'gandusari trenggalek'
        ];
        foreach ($trenggalekKeywords as $kw) {
            if (str_contains($addr, $kw)) {
                return ['destination_code' => 'TGK', 'receiver_area' => 'TGK001'];
            }
        }
        // Deteksi kodepos wilayah Trenggalek (663xx)
        if (preg_match('/\b(663\d{2})\b/', $addr)) {
            return ['destination_code' => 'TGK', 'receiver_area' => 'TGK001'];
        }

        // 3. Kabupaten Tulungagung (Karesidenan Kediri / Tetangga Trenggalek)
        $tulungagungKeywords = [
            'tulungagung', 'boyolangu', 'kedungwaru', 'ngunut', 'sobontoro',
            'bago', 'kutoanyar', 'rejotangan', 'kalidawir', 'pucanglaban',
            'campurdarat', 'sumbergempol', 'tanggunggunung', 'sendang',
            'pagerwojo', 'karangrejo', 'ngantru', 'pakel', 'bandung tulungagung',
            'jepun', 'kepatihan', 'tamanan', 'plosokandang', 'beji'
        ];
        foreach ($tulungagungKeywords as $kw) {
            if (str_contains($addr, $kw)) {
                return ['destination_code' => 'TLA', 'receiver_area' => 'TLA001'];
            }
        }

        // 4. Pemetaan Kota / Kabupaten Jawa Timur (100% Validasi Gateway J&T)
        $jatimMap = [
            'bojonegoro' => ['destination_code' => 'BJE', 'receiver_area' => 'BJE001'],
            'mojokerto'  => ['destination_code' => 'MJK', 'receiver_area' => 'MJK001'],
            'kemlagi'    => ['destination_code' => 'MJK', 'receiver_area' => 'MJK001'],
            'malang'     => ['destination_code' => 'MLG', 'receiver_area' => 'MLG001'],
            'batu'       => ['destination_code' => 'MLG', 'receiver_area' => 'MLG001'],
            'kediri'     => ['destination_code' => 'KDR', 'receiver_area' => 'KDR001'],
            'pare'       => ['destination_code' => 'KDR', 'receiver_area' => 'KDR001'],
            'blitar'     => ['destination_code' => 'BLT', 'receiver_area' => 'BLT001'],
            'wlingi'     => ['destination_code' => 'BLT', 'receiver_area' => 'BLT001'],
            'madiun'     => ['destination_code' => 'MDN', 'receiver_area' => 'MDN001'], // Teruji: MDN001 (Bukan MNI)
            'magetan'    => ['destination_code' => 'MDN', 'receiver_area' => 'MDN001'],
            'ngawi'      => ['destination_code' => 'MDN', 'receiver_area' => 'MDN001'],
            'ponorogo'   => ['destination_code' => 'MDN', 'receiver_area' => 'MDN001'],
            'pacitan'    => ['destination_code' => 'MDN', 'receiver_area' => 'MDN001'],
            'jombang'    => ['destination_code' => 'JBG', 'receiver_area' => 'JBG001'],
            'nganjuk'    => ['destination_code' => 'NGK', 'receiver_area' => 'NGK001'],
            'surabaya'   => ['destination_code' => 'SUB', 'receiver_area' => 'SUB001'],
            'sidoarjo'   => ['destination_code' => 'SDA', 'receiver_area' => 'SDA001'],
            'gresik'     => ['destination_code' => 'GSK', 'receiver_area' => 'GSK001'],
            'lamongan'   => ['destination_code' => 'BJE', 'receiver_area' => 'BJE001'],
            'tuban'      => ['destination_code' => 'BJE', 'receiver_area' => 'BJE001'],
            'pasuruan'   => ['destination_code' => 'PSR', 'receiver_area' => 'PSR001'],
            'probolinggo'=> ['destination_code' => 'PRO', 'receiver_area' => 'PRO001'], // Teruji: PRO001 (Bukan PBL)
            'lumajang'   => ['destination_code' => 'LMJ', 'receiver_area' => 'LMJ001'], // Teruji: LMJ001
            'jember'     => ['destination_code' => 'JBR', 'receiver_area' => 'JBR001'], // Teruji: JBR001 (Bukan JMB)
            'bondowoso'  => ['destination_code' => 'JBR', 'receiver_area' => 'JBR001'],
            'situbondo'  => ['destination_code' => 'JBR', 'receiver_area' => 'JBR001'],
            'banyuwangi' => ['destination_code' => 'BWX', 'receiver_area' => 'BWX001'],
            // Madura
            'sampang'    => ['destination_code' => 'SPG', 'receiver_area' => 'SPG001'],
            'bangkalan'  => ['destination_code' => 'SUB', 'receiver_area' => 'SUB001'],
            'pamekasan'  => ['destination_code' => 'SUB', 'receiver_area' => 'SUB001'],
            'sumenep'    => ['destination_code' => 'SUB', 'receiver_area' => 'SUB001'],
        ];

        foreach ($jatimMap as $keyword => $code) {
            if (str_contains($addr, $keyword)) {
                return $code;
            }
        }

        // 5. Pemetaan Kota Besar Nasional & Pulau Jawa Lainnya (100% Validasi Gateway J&T)
        $nationalMap = [
            'jakarta'    => ['destination_code' => 'JKT', 'receiver_area' => 'JKT001'],
            'bogor'      => ['destination_code' => 'BGR', 'receiver_area' => 'BGR001'],
            'depok'      => ['destination_code' => 'DPK', 'receiver_area' => 'DPK001'],
            'tangerang'  => ['destination_code' => 'TGR', 'receiver_area' => 'TGR001'], // Teruji: TGR001 (Bukan TNG)
            'tangsel'    => ['destination_code' => 'TGR', 'receiver_area' => 'TGR001'],
            'bekasi'     => ['destination_code' => 'BKS', 'receiver_area' => 'BKS001'],
            'cikarang'   => ['destination_code' => 'BKS', 'receiver_area' => 'BKS001'],
            'bandung'    => ['destination_code' => 'BDO', 'receiver_area' => 'BDO001'],
            'cimahi'     => ['destination_code' => 'BDO', 'receiver_area' => 'BDO001'],
            'cirebon'    => ['destination_code' => 'CRN', 'receiver_area' => 'CRN001'], // Teruji: CRN001 (Bukan CBN001)
            'semarang'   => ['destination_code' => 'SRG', 'receiver_area' => 'SRG001'], // Teruji: SRG001 (Bukan SMG)
            'solo'       => ['destination_code' => 'SOC', 'receiver_area' => 'SOC001'],
            'surakarta'  => ['destination_code' => 'SOC', 'receiver_area' => 'SOC001'],
            'kudus'      => ['destination_code' => 'KDS', 'receiver_area' => 'KDS001'], // Teruji: KDS001
            'yogyakarta' => ['destination_code' => 'JOG', 'receiver_area' => 'JOG001'],
            'jogja'      => ['destination_code' => 'JOG', 'receiver_area' => 'JOG001'],
            'sleman'     => ['destination_code' => 'JOG', 'receiver_area' => 'JOG001'],
            'bantul'     => ['destination_code' => 'JOG', 'receiver_area' => 'JOG001'],
            'serang'     => ['destination_code' => 'CLG', 'receiver_area' => 'CLG001'], // Teruji: CLG001
            'cilegon'    => ['destination_code' => 'CLG', 'receiver_area' => 'CLG001'],
            'denpasar'   => ['destination_code' => 'DPS', 'receiver_area' => 'DPS001'],
            'bali'       => ['destination_code' => 'DPS', 'receiver_area' => 'DPS001'],
            'mataram'    => ['destination_code' => 'MTX', 'receiver_area' => 'MTX001'],
            'lombok'     => ['destination_code' => 'MTX', 'receiver_area' => 'MTX001'],
            'kupang'     => ['destination_code' => 'KOE', 'receiver_area' => 'KOE001'],
            'medan'      => ['destination_code' => 'MES', 'receiver_area' => 'MES001'],
            'padang'     => ['destination_code' => 'PDG', 'receiver_area' => 'PDG001'],
            'pekanbaru'  => ['destination_code' => 'PKU', 'receiver_area' => 'PKU001'],
            'batam'      => ['destination_code' => 'BTH', 'receiver_area' => 'BTH001'],
            'palembang'  => ['destination_code' => 'PLM', 'receiver_area' => 'PLM001'],
            'lampung'    => ['destination_code' => 'TKG', 'receiver_area' => 'TKG001'],
            'pontianak'  => ['destination_code' => 'PNK', 'receiver_area' => 'PNK001'],
            'banjarmasin'=> ['destination_code' => 'BDJ', 'receiver_area' => 'BDJ001'],
            'balikpapan' => ['destination_code' => 'BPN', 'receiver_area' => 'BPN001'],
            'samarinda'  => ['destination_code' => 'SRI', 'receiver_area' => 'SRI001'],
            'makassar'   => ['destination_code' => 'UPG', 'receiver_area' => 'UPG001'],
            'manado'     => ['destination_code' => 'MDC', 'receiver_area' => 'MDC001'],
            'jayapura'   => ['destination_code' => 'DJJ', 'receiver_area' => 'DJJ001'],
            'aceh'       => ['destination_code' => 'BTJ', 'receiver_area' => 'BTJ001'],
        ];

        foreach ($nationalMap as $keyword => $code) {
            if (str_contains($addr, $keyword)) {
                return $code;
            }
        }

        // 6. Fallback Kontekstual Berdasarkan Kantor Cabang Mitra (jika alamat singkat tanpa nama kota)
        if (!empty($userBranch)) {
            $branchNormalized = strtolower(trim($userBranch));
            $branchMap = [
                'trenggalek' => ['destination_code' => 'TGK', 'receiver_area' => 'TGK001'],
                'kediri'     => ['destination_code' => 'KDR', 'receiver_area' => 'KDR001'],
                'madiun'     => ['destination_code' => 'MDN', 'receiver_area' => 'MDN001'],
                'solo'       => ['destination_code' => 'SOC', 'receiver_area' => 'SOC001'],
                'jogja'      => ['destination_code' => 'JOG', 'receiver_area' => 'JOG001'],
                'cirebon'    => ['destination_code' => 'CRN', 'receiver_area' => 'CRN001'],
                'kudus'      => ['destination_code' => 'KDS', 'receiver_area' => 'KDS001'],
                'bogor'      => ['destination_code' => 'BGR', 'receiver_area' => 'BGR001'],
                'serang'     => ['destination_code' => 'CLG', 'receiver_area' => 'CLG001'],
            ];

            if (isset($branchMap[$branchNormalized])) {
                return $branchMap[$branchNormalized];
            }
        }

        // 7. Fallback Default: Trenggalek (Basis Pusat Pabrik & Distribusi PT WOWIN)
        return ['destination_code' => 'TGK', 'receiver_area' => 'TGK001'];
    }

    /**
     * Pengecekan Tarif Langsung (Tariff Inquiry) ke Server J&T Express Production
     */
    public static function inquiryTariff(string $destAreaCode, float $weightKg = 1.0, string $sendSiteCode = 'JKT'): array
    {
        try {
            $endpoint = config('jnt.tariff_endpoint', 'https://partner-track.jet.co.id/jandt_track/inquiry.action');
            $key = config('jnt.tariff_key', '8yz0HYv4puRV');
            $customerName = config('jnt.username', 'SUB-PT-WOWINFOOD');

            $weightInt = (int) ceil(max(1.0, $weightKg));

            $payload = [
                'weight' => $weightInt,
                'sendSiteCode' => $sendSiteCode,
                'destAreaCode' => $destAreaCode,
                'cusName' => $customerName,
                'productType' => 'EZ',
            ];

            $jsonData = json_encode($payload);
            $sign = base64_encode(md5($jsonData . $key));

            $response = Http::asForm()->timeout(15)->post($endpoint, [
                'data' => $jsonData,
                'sign' => $sign,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Format stringified JSON array di dalam content jika ada
                if (isset($data['content']) && is_string($data['content'])) {
                    $decoded = json_decode($data['content'], true);
                    if (is_array($decoded)) {
                        $data['content'] = $decoded;
                    }
                }

                return [
                    'success' => true,
                    'data' => $data,
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menghubungi server tarif J&T (' . $response->status() . ').',
            ];
        } catch (\Exception $e) {
            Log::error('JntService inquiryTariff error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat memeriksa tarif J&T: ' . $e->getMessage(),
            ];
        }
    }
}

