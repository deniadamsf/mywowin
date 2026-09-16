# 🎨 PANDUAN & ATURAN BAKU GENERATE GAMBAR PRODUK MY WOWIN
**Standar Operasional Pembuatan Aset Visual AI Realistis Berbasis File PNG Produk Resmi PT Wowin Purnomo Putera**

---

## 📌 1. Prinsip Utama: Realistis, Presisi, & Menyatu Selaras

1. **Wajib Berbasis File PNG Produk Asli (`ImagePaths`)**:
   - Pembuatan gambar produk **TIDAK BOLEH** hanya mengandalkan prompt teks semata, karena dapat menghasilkan bentuk kemasan dan label fiktif/palsu.
   - Gambar AI **WAJIB** menyertakan path file PNG kemasan asli dari direktori:
     `public_html (web fronted + backend)/storage/app/public/product_images/`
   - AI bertugas mengekstrak kemasan asli, membersihkan latar putih/stiker katalog, dan menyatukannya secara fotorealistis ke dalam latar belakang lingkungan nyata.

2. **Larangan Keras Merek Kompetitor**:
   - Seluruh elemen visual (kemasan, latar belakang, properti dapur) **HARUS BERSIH** dari merek luar manapun (*Bango, ABC, Sedaap, dll.*).
   - Hanya merek resmi PT Wowin Purnomo Putera: **Wowin**, **Rajaku**, dan **Jangkar**.

---

## ⚖️ 2. Standar Proporsi Ukuran Jerigen (Besar vs Kecil)

Dalam satu frame yang menampilkan lebih dari satu ukuran, perbandingan skala fisik harus **akurat dan masuk akal secara matematis**:

| Varian Kemasan | Volume & Berat | Karakteristik Fisik Kemasan | Skala Proporsional |
| :--- | :--- | :--- | :--- |
| **Jerigen Kecil** | **6.200 ml / 6,2 Kg** | • Jerigen jinjing HDPE transparan/putih<br/>• Memiliki pegangan (*handle*) di sisi atas<br/>• Tutup ulir putih standar | Tampak kompak, mudah diangkat satu tangan, ideal diletakkan di atas meja saji atau *prep table*. |
| **Jerigen Besar (Jumbo)** | **26.000 ml / 26 Kg** | • Jerigen drum kubik industri besar<br/>• Bodi tebal, kokoh, berprofil tinggi<br/>• Tutup ulir besar warna merah di atas | **Volume >4x lipat** dari jerigen kecil. Harus tampak masif, kokoh, berdiri jauh lebih tinggi dan lebar. |

> [!IMPORTANT]
> **Aturan Skala Visual:** Jangan pernah membuat jerigen 26 kg memiliki tinggi yang sama dengan jerigen 6,2 kg. Jerigen 26 kg adalah kemasan industri berat seberat karung beras jumbo.

---

## 🏷️ 3. Akurasi Komposisi Bahan & Ciri Khas Label Produk

Setiap varian merek memiliki identitas visual dan bahan baku khas yang harus tercermin dalam konten visual:

### 1. Kecap Manis Wowin (Grade Premium)
* **Warna & Ciri Label**: Label hijau cerah berbingkai kuning, dengan oval merah bertuliskan **WOWIN** (huruf kapital putih dengan aksen centang merah), tagline *"Sahabat Hidangan Anda"*, logo Halal & BPOM.
* **Komposisi Bahan Baku**: Gula Merah Kelapa Murni, Kacang Kedelai Pilihan, Garam, Pengawet Natrium Benzoat.
* **Karakter Tekstur**: Paling kental, hitam pekat mengkilap (*glistening*), rasa manis legit gurih alami.

### 2. Kecap Manis Rajaku Premium Gold (Grade Menengah Seimbang)
* **Warna & Ciri Label**: Label dasar hitam eksklusif bergradasi emas, menampilkan maskot **Raja Bermahkota Merah** berpose mengacungkan jempol, tulisan *"Rajaku Premium"*, dan logo Wowin kecil di kanan atas.
* **Komposisi Bahan Baku**: Gula Merah, Kacang Kedelai tinggi (**22%**), Air, Garam, Pengawet Natrium Benzoat.
* **Karakter Tekstur**: Kental seimbang, manis gurih meresap, sangat cocok untuk kuah mie ayam, bakso, dan bumbu marinasi.

### 3. Kecap Manis Jangkar (Grade Ekonomis Juara HPP)
* **Warna & Ciri Label**: Label merah gradasi elegan dengan lambang **Jangkar Laut Kuning Emas** di dalam lingkaran merah-biru, tulisan *"Kecap Kedelai Manis JANGKAR"*, tagline *"Pasti Enaakk"*.
* **Komposisi Bahan Baku**: Gula Merah, Air, Kacang Kedelai, Garam, Pengawet Natrium Benzoat.
* **Karakter Tekstur**: Tahan panas wajan tinggi (*wok hei*), warna cokelat tua karamel merata, sangat irit untuk pedagang nasi goreng dan sate.

---

## 🛠️ 4. Teknik Prompting & Parameter Eksekusi (`generate_image`)

### 4.1. Parameter Wajib
1. **`AspectRatio`**:
   - Gunakan `'16:9'` untuk Hero Cover dan gambar lanskap postingan artikel web.
   - Gunakan `'1:1'` atau `'4:3'` untuk katalog e-commerce / mobile feed.
2. **`ImagePaths`**:
   - Masukkan 1 sampai maksimal 3 path mutlak file PNG produk resmi.
   - Contoh:
     ```json
     [
       "d:\\MYWOWIN\\public_html (web fronted + backend)\\storage\\app\\public\\product_images\\ve1w6WrBST6n0tq0It7krOTcsLXL5tKnrlFkbh2h.png",
       "d:\\MYWOWIN\\public_html (web fronted + backend)\\storage\\app\\public\\product_images\\FCDFdA6yBhkWlchsPCaRTMVOrJDIjDnxCAHJS5cG.png"
     ]
     ```

### 4.2. Elemen Wajib dalam Teks Prompt
Setiap prompt pembuatan gambar harus memuat 5 struktur kalimat:
1. **Deklarasi Produk Acuan**: Sebutkan secara spesifik bahwa AI harus menggunakan produk asli dari reference images (label, warna tutup, dan bentuk botol/jerigen).
2. **Instruksi Pembersihan Latar**: *"Cleanly remove all artificial white backgrounds, side banners, and graphic borders from the reference images"*.
3. **Instruksi Skala & Proporsi Ukuran**: Jelaskan perbedaan ukuran jerigen besar (26 kg) vs jerigen kecil (6,2 kg).
4. **Instruksi Lingkungan & Pencahayaan (*Photorealistic Blending*)**:
   - Latar belakang nyata: meja kayu dapur restoran (*rustic wooden prep table*), meja stainless steel dapur komersial, atau suasana warung kuliner.
   - Pencahayaan: *warm culinary commercial lighting, soft ambient contact shadows, realistic surface reflections, shallow depth of field*.
5. **Elemen Pelengkap Kuliner Alami**: Bumbu dapur asli Indonesia (gula aren kelapa, mangkuk kecap kental mengkilap, biji kedelai, cabai merah, bawang merah, sate ayam bakar, wajan nasi goreng mengepul).

---

## 🖼️ 5. Standar Multi-Gambar dalam 1 Postingan Artikel

Setiap artikel komersial/edukasi di web My Wowin **WAJIB** menyertakan minimal 3 gambar selaras:

1. **Gambar 1 (Cover / Hero Header)**:
   - Menampilkan produk utama jerigen besar (26 kg) dan jerigen kecil (6,2 kg) secara proporsional di atas meja kayu dapur dengan semangkuk kecap kental dan bahan baku kedelai & gula merah.
2. **Gambar 2 (In-Article: Proses Memasak / Wok Scene)**:
   - Menampilkan jerigen Jangkar/Wowin bersama koki yang sedang memasak nasi goreng wajan mengepul (*wok hei*) atau membakar sate dengan karamelisasi kecap mengkilap.
3. **Gambar 3 (In-Article: Hasil Menu Kuliner / Meja Pelanggan)**:
   - Menampilkan jerigen Rajaku/Wowin di samping hidangan jadi yang menggugah selera (mie ayam komplit ceker, bakso kuah gurih) untuk memperkuat selera makan pembaca.

---

## 📋 6. Template Prompt Baku Siap Pakai

### Template A: Perbandingan Jerigen Besar (26 kg) vs Kecil (6,2 kg)
```text
Photorealistic commercial product shot in an authentic Indonesian restaurant kitchen. Features both sweet soy sauce jerrycans from the reference images: the small 6.2 kg jerrycan with handle and [Label Description], and the massive 26 kg jumbo jerrycan with red cap and [Label Description]. CRITICAL SIZE PROPORTION: The 26 kg jumbo jerrycan is massive and much larger (over 4 times the volume of the small jerrycan), standing tall. The 6.2 kg small jerrycan is placed beside it in the foreground. Cleanly remove all white background and side graphics from the references, blending both actual products naturally onto a solid rustic wooden table. In front, a small ceramic dipping dish filled with thick dark caramelized sweet soy sauce, natural palm sugar blocks (gula aren), and soy beans. Warm commercial kitchen lighting, realistic reflections, depth of field, authentic product scale. No competitor brands.
```

### Template B: Aplikasi Dapur Wajan / Nasi Goreng
```text
Photorealistic commercial kitchen scene in an authentic Indonesian culinary eatery. Prominently feature the exact [Product Name] jerrycan from the reference image, cleanly isolated from its white background. Place the jerrycan on a stainless steel cooking prep station next to a chef wok-frying Indonesian nasi goreng with rising fragrant steam, fresh shallots, garlic, and sliced red chilies. Warm golden kitchen lighting, realistic stainless steel reflections and contact shadows. No competitor brands.
```
