-- ==============================================================================
-- SCRIPT PENYESUAIAN HARGA PRODUK MY WOWIN BERDASARKAN "HARGA WEB.xlsx"
-- Tanggal: 16 September 2026
-- Logika: Harga DB (Karton) = Harga Web (Satuan) x isi_karton
-- ==============================================================================

START TRANSACTION;

-- 1. Cuka Makan Rajaku (Excel No. 3: CUKA MAKAN RAJAKU)
-- DB ID: 35 | Nama: Cuka Makan Rajaku | 150 ml | Isi Karton: 12 pcs
-- Harga Web: Rp 6.500 | Perhitungan: 6.500 x 12 = Rp 78.000,00
UPDATE `products` 
SET `harga` = 78000.00, `updated_at` = NOW() 
WHERE `id_product` = 35;

-- 2. Kecap Manis Rajaku Hijau (Excel No. 4: RAJAKU HIJAU 690GRAM)
-- DB ID: 54 | Nama: Kecap Manis Rajaku Hijau | 500 ml | Isi Karton: 20 pcs
-- Harga Web: Rp 14.300 | Perhitungan: 14.300 x 20 = Rp 286.000,00
UPDATE `products` 
SET `harga` = 286000.00, `updated_at` = NOW() 
WHERE `id_product` = 54;

-- 3. Saos Raja Tomat Botol (Excel No. 6: RAJA TOMAT 500ML)
-- DB ID: 37 | Nama: Saos Raja Tomat Botol | 500 ml | Isi Karton: 30 pcs
-- Harga Web: Rp 6.999 | Perhitungan: 6.999 x 30 = Rp 209.970,00
UPDATE `products` 
SET `harga` = 209970.00, `updated_at` = NOW() 
WHERE `id_product` = 37;

-- 4. Saos Cabe Tani Ball (Excel No. 7: CABE TANI 600GR)
-- DB ID: 40 | Nama: Saos Cabe Tani Ball | 600 gram | Isi Karton: 12 pcs
-- Harga Web: Rp 5.999 | Perhitungan: 5.999 x 12 = Rp 71.988,00
UPDATE `products` 
SET `harga` = 71988.00, `updated_at` = NOW() 
WHERE `id_product` = 40;

-- 5. Saos Cabe Tani Botol (Excel No. 9: CABE TANI 500ML)
-- DB ID: 42 | Nama: Saos Cabe Tani | 500 ml | Isi Karton: 30 pcs
-- Harga Web: Rp 6.999 | Perhitungan: 6.999 x 30 = Rp 209.970,00
UPDATE `products` 
SET `harga` = 209970.00, `updated_at` = NOW() 
WHERE `id_product` = 42;

-- 6. Saos Raja Tomat Ball (Excel No. 10: RAJA TOMAT 600GR)
-- DB ID: 36 | Nama: Saos Raja Tomat Ball | 660 ml | Isi Karton: 12 pcs
-- Harga Web: Rp 5.999 | Perhitungan: 5.999 x 12 = Rp 71.988,00
UPDATE `products` 
SET `harga` = 71988.00, `updated_at` = NOW() 
WHERE `id_product` = 36;

-- 7. KM Jangkar Merah (Excel No. 12: JANGKAR MERAH 690GR)
-- DB ID: 47 | Nama: KM Jangkar Merah | 500 ml | Isi Karton: 20 pcs
-- Harga Web: Rp 9.999 | Perhitungan: 9.999 x 20 = Rp 199.980,00
UPDATE `products` 
SET `harga` = 199980.00, `updated_at` = NOW() 
WHERE `id_product` = 47;

-- 8. Wowin Premium Jirigen (Excel No. 13: WOWIN PREMIUM JIRIGEN)
-- DB ID: 16 | Nama: Manis Wowin Jirigen | 6.200 ml | Isi Karton: 2 pcs
-- Harga Web: Rp 198.999 | Perhitungan: 198.999 x 2 = Rp 397.998,00
UPDATE `products` 
SET `harga` = 397998.00, `updated_at` = NOW() 
WHERE `id_product` = 16;

-- 9. Kecap Manis Wowin Pouch (Excel No. 14: WOWIN POUCH 600GR)
-- DB ID: 11 | Nama: Kecap Manis Wowin Pouch | 600 ml | Isi Karton: 12 pcs
-- Harga Web: Rp 25.500 | Perhitungan: 25.500 x 12 = Rp 306.000,00
UPDATE `products` 
SET `harga` = 306000.00, `updated_at` = NOW() 
WHERE `id_product` = 11;

-- 10. Wowin Botol Plastik (Excel No. 15: WOWIN 690GR)
-- DB ID: 12 | Nama: Wowin Botol Plastik | 500 ml | Isi Karton: 20 pcs
-- Harga Web: Rp 24.999 | Perhitungan: 24.999 x 20 = Rp 499.980,00
UPDATE `products` 
SET `harga` = 499980.00, `updated_at` = NOW() 
WHERE `id_product` = 12;

-- 11. Kecap Manis Wowin 380 Gram (Excel No. 18: WOWIN 380GR)
-- DB ID: 52 | Nama: Kecap Manis Wowin 380 Gram | 380 gram | Isi Karton: 24 pcs
-- Harga Web: Rp 14.999 | Perhitungan: 14.999 x 24 = Rp 359.976,00
UPDATE `products` 
SET `harga` = 359976.00, `updated_at` = NOW() 
WHERE `id_product` = 52;

-- 12. Rajaku Premium Gold (Excel No. 23: RAJAKU PREMIUM GOLD 700GR)
-- DB ID: 25 | Nama: Rajaku Premium Gold | 550 ml | Isi Karton: 12 pcs
-- Harga Web: Rp 17.500 | Perhitungan: 17.500 x 12 = Rp 210.000,00
UPDATE `products` 
SET `harga` = 210000.00, `updated_at` = NOW() 
WHERE `id_product` = 25;

-- 13. Jirigen Etiket Hijau (Excel No. 30: RAJAKU HIJAU JIRIGEN)
-- DB ID: 21 | Nama: Jirigen Etiket Hijau | 6.200 ml | Isi Karton: 2 pcs
-- Harga Web: Rp 141.000 | Perhitungan: 141.000 x 2 = Rp 282.000,00
UPDATE `products` 
SET `harga` = 282000.00, `updated_at` = NOW() 
WHERE `id_product` = 21;

-- 14. Rajaku Premium Gold Jirigen (Excel No. 32: RAJAKU PREMIUM GOLD JIRIGEN)
-- DB ID: 28 | Nama: Rajaku Premium Gold | 6.200 ml | Isi Karton: 2 pcs
-- Harga Web: Rp 145.000 | Perhitungan: 145.000 x 2 = Rp 290.000,00
UPDATE `products` 
SET `harga` = 290000.00, `updated_at` = NOW() 
WHERE `id_product` = 28;

-- 15. KM Jangkar Jirigen (Excel No. 33: JANGKAR MERAH JIRIGEN)
-- DB ID: 45 | Nama: KM Jangkar Jirigen | 6.200 ml | Isi Karton: 2 pcs
-- Harga Web: Rp 124.500 | Perhitungan: 124.500 x 2 = Rp 249.000,00
UPDATE `products` 
SET `harga` = 249000.00, `updated_at` = NOW() 
WHERE `id_product` = 45;

-- 16. Kecap Manis Wowin 192 Gram (Excel No. 35: WOWIN 192GR)
-- DB ID: 51 | Nama: Kecap Manis Wowin 192 Gram | 192 gram | Isi Karton: 48 pcs
-- Harga Web: Rp 8.888 | Perhitungan: 8.888 x 48 = Rp 426.624,00
UPDATE `products` 
SET `harga` = 426624.00, `updated_at` = NOW() 
WHERE `id_product` = 51;

COMMIT;

-- ==============================================================================
-- Verifikasi hasil pembaruan: Pastikan (harga / isi_karton) = Harga Satuan Web
-- ==============================================================================
SELECT 
    `id_product`, 
    `nama_produk`, 
    `isi_karton`, 
    `harga` AS `harga_karton`, 
    ROUND(`harga` / `isi_karton`, 2) AS `harga_pcs`
FROM `products` 
WHERE `id_product` IN (11, 12, 16, 21, 25, 28, 35, 36, 37, 40, 42, 45, 47, 51, 52, 54)
ORDER BY `id_product` ASC;
