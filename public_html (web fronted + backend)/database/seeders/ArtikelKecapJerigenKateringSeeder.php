<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artikel;
use App\Models\User;
use Illuminate\Support\Str;

class ArtikelKecapJerigenKateringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('role', 'superadmin')
            ->orWhere('role', 'admin')
            ->first();

        $userId = $adminUser ? $adminUser->id : 24;

        $judul = 'Kecap Manis Jerigen 6 Kg Murah untuk Katering & Resto';
        $slug = 'kecap-manis-jerigen-6-kg-murah-katering-resto';

        $fotoArtikel = [
            'foto_artikel/cover-kecap-manis-jerigen-6-kg-murah-katering-resto.jpg',
            'foto_artikel/kecap-manis-jerigen-6-kg-dapur-masak-katering.jpg',
            'foto_artikel/kecap-manis-jerigen-6-kg-prasmanan-katering-resto.jpg',
        ];

        $kontenBlok = [
            [
                'type' => 'text',
                'value' => '<p>Mencari pasokan <strong>kecap manis jerigen 6 kg</strong> yang berkualitas prima dengan harga terjangkau merupakan prioritas utama bagi para pengusaha katering pernikahan, katering harian pabrik, restoran, dan rumah makan prasmanan di Indonesia. Dalam industri jasa boga dan kuliner komersial, kecap manis adalah bumbu induk yang menentukan cita rasa puluhan menu legendaris, mulai dari ayam bakar, semur daging sapi, sate, tongseng, hingga aneka tumisan dan mie goreng pesta.</p>

<p>Tantangan terbesar yang dihadapi para pemilik katering dan chef restoran adalah menjaga konsistensi rasa masakan dalam porsi ratusan hingga ribuan porsi sekaligus, tanpa membuat Harga Pokok Penjualan (HPP) membengkak. Penggunaan kemasan botol retail kecil jelas tidak efisien dan membuang banyak biaya kemasan, sementara kecap curah tanpa izin edar resmi berisiko merusak reputasi bisnis Anda.</p>

<p>Hadir sebagai solusi bagi pelaku industri kuliner profesional, <strong>PT Wowin Purnomo Putera</strong> menyediakan varian kemasan jerigen ukuran 6,2 kg (6.200 ml) yang dirancang khusus dengan pegangan ergonomis, formula tahan panas tinggi, dan rasa gurih legit alami berstandar BPOM RI serta Halal MUI.</p>

<div class="p-5 my-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-950">
    <h3 class="text-base font-bold text-emerald-900 mb-2 flex items-center gap-2">
        <i class="fas fa-list-ul text-emerald-600"></i> Daftar Isi Artikel (Navigasi Cepat)
    </h3>
    <ul class="space-y-1.5 text-xs sm:text-sm">
        <li><a href="#alasan" class="text-emerald-800 hover:text-emerald-950 underline font-medium">1. Mengapa Katering & Resto Wajib Menggunakan Kemasan Jerigen 6 Kg?</a></li>
        <li><a href="#varian" class="text-emerald-800 hover:text-emerald-950 underline font-medium">2. Pilihan 3 Merek Resmi Wowin Food Kemasan Jerigen 6,2 Kg</a></li>
        <li><a href="#tabel-komparasi" class="text-emerald-800 hover:text-emerald-950 underline font-medium">3. Tabel Spesifikasi & Aplikasi Menu Katering Varian Jerigen</a></li>
        <li><a href="#simulasi-hpp" class="text-emerald-800 hover:text-emerald-950 underline font-medium">4. Simulasi Analisis HPP Penggunaan Kecap per 500 Porsi Nasi Kotak</a></li>
        <li><a href="#tips-glazing" class="text-emerald-800 hover:text-emerald-950 underline font-medium">5. Rahasia Glazing Ayam Bakar & SOP Penyimpanan Jerigen Dapur</a></li>
        <li><a href="#faq-section" class="text-emerald-800 hover:text-emerald-950 underline font-medium">6. Pertanyaan Seputar Pasokan Kecap Jerigen Katering & Resto (FAQ)</a></li>
        <li><a href="#kesimpulan" class="text-emerald-800 hover:text-emerald-950 underline font-medium">7. Kesimpulan & Panduan Pembelian Grosir Pabrik Langsung</a></li>
    </ul>
</div>

<h2 id="alasan">Mengapa Katering & Resto Wajib Menggunakan Kemasan Jerigen 6 Kg?</h2>

<p>Ukuran kemasan jerigen 6.200 ml (sering disebut oleh para praktisi pasar sebagai <strong>kecap manis jerigen 6 kg</strong>) adalah ukuran paling ideal bagi operasional dapur komersial karena menawarkan tiga keunggulan strategis:</p>

<ul>
    <li><strong>Ergonomi Dapur & Kemudahan Penuangan:</strong> Dilengkapi pegangan atas (*sturdy handle*), jerigen 6,2 kg sangat mudah diangkat dan dituang langsung oleh juru masak ke wajan besar (*wok*) tanpa memerlukan pompa elektrik khusus atau alat bantu tambahan.</li>
    <li><strong>Efisiensi Anggaran HPP Signifikan:</strong> Dibandingkan membeli kemasan botol kaca atau botol plastik retail 500 ml di supermarket, pembelian kemasan jerigen memangkas biaya bahan baku per mililiter hingga <strong>30% – 38%</strong>. Dalam skala katering mingguan, efisiensi ini menghemat jutaan rupiah.</li>
    <li><strong>Stabilitas Takaran Bumbu Resep Skala Besar:</strong> Formula bumbu dapur katering membutuhkan standarisasi rasa. Dengan pasokan jerigen berkualitas pabrik yang konsisten, rasa masakan antara batch pertama dan batch berikutnya akan selalu seragam.</li>
    <li><strong>Mengurangi Sampah Kemasan di Dapur:</strong> Menggunakan 1 jerigen setara dengan mengeliminasi belasan botol plastik kecil, menjadikan area persiapan dapur restoran jauh lebih bersih, rapi, dan higienis.</li>
</ul>'
            ],
            [
                'type' => 'image',
                'value' => 'foto_artikel/kecap-manis-jerigen-6-kg-dapur-masak-katering.jpg'
            ],
            [
                'type' => 'text',
                'value' => '<h2 id="varian">Pilihan 3 Merek Resmi Wowin Food Kemasan Jerigen 6,2 Kg</h2>

<p>Setiap konsep menu katering dan restoran memiliki karakteristik kebutuhan bumbu yang berbeda. PT Wowin Purnomo Putera menghadirkan 3 lini produk <strong>kecap manis jerigen 6 kg</strong> resmi yang dirancang untuk menjawab spesifikasi dapur Anda:</p>

<h3 id="wowin-premium">1. Kecap Manis Wowin Premium Jerigen (Grade Tertinggi / Karamel Legit)</h3>

<p>Untuk hidangan katering pernikahan (*wedding banquet*), hotel berbintang, dan menu *signature* restoran, Kecap Manis Wowin adalah tolok ukur kesempurnaan rasa.</p>

<p>Dibuat dari 100% gula kelapa murni dan kedelai hitam pilihan, varian ini memiliki tekstur paling kental dengan warna hitam pekat berkilau. Keistimewaan utamanya terletak pada aroma karamel kelapa alami yang sangat wangi ketika dipanaskan.</p>

<p>Kecap Wowin sangat irit pemakaian: cukup 1 sendok makan untuk memberi warna gelap berkilau pada semur daging sapi atau ayam bakar madu, mengungguli kecap biasa yang encer. Tersedia langsung dalam kemasan <a href="/products/16" class="text-emerald-600 font-bold hover:underline">Kecap Manis Wowin Jerigen 6.200 ml</a> dan kemasan isi ulang <a href="/products/11" class="text-emerald-600 font-bold hover:underline">Wowin Pouch 600 ml</a>.</p>

<h3 id="rajaku-gold">2. Kecap Manis Rajaku Premium Gold Jerigen (Varian Serbaguna Favorit Chef)</h3>

<p>Kecap Manis Rajaku Premium Gold merupakan varian serbaguna yang paling banyak digunakan oleh katering prasmanan nusantara, kedai bakso, dan restoran *family style*.</p>

<p>Keunggulan utamanya adalah kandungan sari kedelai tinggi mencapai <strong>22%</strong>. Perpaduan rasa manis gurih umaminya meresap sempurna ke dalam serat daging ayam marinasi, olahan sate, bistik, hingga tumisan sayur katering seperti capcay dan cah buncis daging cincang.</p>

<p>Kemasan <a href="/products/28" class="text-emerald-600 font-bold hover:underline">Kecap Manis Rajaku Jerigen 6.200 ml</a> dan kemasan pouch <a href="/products/25" class="text-emerald-600 font-bold hover:underline">Rajaku Pouch 550 ml</a> memberikan keseimbangan sempurna antara kemewahan rasa dan efisiensi modal produksi.</p>

<h3 id="jangkar-merah">3. Kecap Manis Jangkar Merah Jerigen (Ekonomis Juara HPP Pesta & Katering Harian)</h3>

<p>Bagi pengusaha katering nasi kotak volume tinggi, katering harian karyawan pabrik, atau restoran nasi goreng dan mie cepat saji, Kecap Manis Jangkar adalah senjata rahasia meraih laba maksimal.</p>

<p>Varian legendaris berlambang jangkar laut emas ini memiliki ketahanan panas paling tangguh terhadap api wajan besar (*high wok-heat resistant*). Kecap tidak cepat berkerak hitam pahit saat dipakai menumis nasi goreng atau mie goreng hajatan ratusan porsi, melainkan menciptakan aroma asap wajan (*wok hei*) yang sangat harum menggugah selera.</p>

<p>Pasokan grosir tersedia dalam ukuran praktis <a href="/products/45" class="text-emerald-600 font-bold hover:underline">Kecap Manis Jangkar Jerigen 6.200 ml</a>, jerigen jumbo 26 kg untuk dapur sentral, serta kemasan ekonomis <a href="/products/47" class="text-emerald-600 font-bold hover:underline">Jangkar Pouch Merah 500 ml</a>.</p>'
            ],
            [
                'type' => 'image',
                'value' => 'foto_artikel/kecap-manis-jerigen-6-kg-prasmanan-katering-resto.jpg'
            ],
            [
                'type' => 'text',
                'value' => '<h2 id="tabel-komparasi">Tabel Spesifikasi & Aplikasi Menu Katering Varian Jerigen</h2>

<p>Untuk memudahkan kepala juru masak (head chef) menentukan pilihan yang tepat, berikut rangkuman komparasi karakteristik 3 merek resmi Wowin Food kemasan jerigen 6,2 kg:</p>

<div class="overflow-x-auto my-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="mobile-table-hint">Geser tabel ke samping <i class="fas fa-arrows-alt-h ml-1"></i></div>
    <table class="w-full min-w-[620px] text-left border-collapse text-xs sm:text-sm">
        <thead>
            <tr class="bg-emerald-800 text-white">
                <th class="py-3 px-4 font-semibold">Parameter Uji</th>
                <th class="py-3 px-4 font-semibold">Wowin Premium</th>
                <th class="py-3 px-4 font-semibold">Rajaku Premium Gold</th>
                <th class="py-3 px-4 font-semibold">Jangkar Pasti Enaakk</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-700">
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Bahan Baku Inti</td>
                <td class="py-3 px-4">Gula kelapa murni, kedelai pilihan</td>
                <td class="py-3 px-4">Kedelai tinggi (22%), gula aren kelapa</td>
                <td class="py-3 px-4">Gula kelapa, sari kedelai, garam, rempah</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Kekentalan Tekstur</td>
                <td class="py-3 px-4">Ekstra kental pekat (viskositas tertinggi)</td>
                <td class="py-3 px-4">Kental seimbang, mudah meresap marinasi</td>
                <td class="py-3 px-4">Kental pas untuk wajan panas cepat</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Ketahanan Api Wajan</td>
                <td class="py-3 px-4">Sangat baik pada api sedang-panas</td>
                <td class="py-3 px-4">Sangat baik untuk ungkep & marinasi</td>
                <td class="py-3 px-4">Luar biasa tangguh pada api wajan tinggi</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Aplikasi Menu Terbaik</td>
                <td class="py-3 px-4">Ayam bakar madu, semur daging pengantin, bistik</td>
                <td class="py-3 px-4">Sate ayam katering, tongseng, tumis capcay</td>
                <td class="py-3 px-4">Nasi goreng hajatan, mie goreng, katering pabrik</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Level Efisiensi HPP</td>
                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-purple-100 text-purple-800 text-xs font-bold">Premium Ekonomis</span></td>
                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 text-xs font-bold">Tinggi (Optimal)</span></td>
                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-xs font-bold">Juara Hemat HPP</span></td>
            </tr>
        </tbody>
    </table>
</div>

<h2 id="simulasi-hpp">Simulasi Analisis HPP Penggunaan Kecap per 500 Porsi Nasi Kotak</h2>

<p>Kunci profitabilitas katering bertumpu pada kontrol biaya bumbu tanpa mengorbankan kepuasan lidah tamu undangan. Pembelian <strong>kecap manis jerigen 6 kg</strong> memberikan margin laba yang jauh lebih besar.</p>

<p>Berikut adalah simulasi kalkulasi nyata penggunaan kecap jerigen ukuran 6.200 ml untuk pesanan katering 500 porsi:</p>

<div class="overflow-x-auto my-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="mobile-table-hint">Geser tabel ke samping <i class="fas fa-arrows-alt-h ml-1"></i></div>
    <table class="w-full min-w-[620px] text-left border-collapse text-xs sm:text-sm">
        <thead>
            <tr class="bg-emerald-800 text-white">
                <th class="py-3 px-4 font-semibold">Menu Utama Katering</th>
                <th class="py-3 px-4 font-semibold">Kebutuhan Bumbu / Porsi</th>
                <th class="py-3 px-4 font-semibold">Total Takaran (500 Porsi)</th>
                <th class="py-3 px-4 font-semibold">Estimasi Biaya Bumbu Kecap</th>
                <th class="py-3 px-4 font-semibold">Biaya HPP / Porsi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-700">
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Ayam Bakar Bumbu Kecap (500 potong)</td>
                <td class="py-3 px-4">15 ml (ungkep & oles)</td>
                <td class="py-3 px-4">7.500 ml (1,2 jerigen)</td>
                <td class="py-3 px-4">Rp 150.000 - Rp 175.000</td>
                <td class="py-3 px-4 font-bold text-emerald-700">Rp 300 - Rp 350</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Semur Daging Sapi Prasmanan</td>
                <td class="py-3 px-4">12 ml</td>
                <td class="py-3 px-4">6.000 ml (0,97 jerigen)</td>
                <td class="py-3 px-4">Rp 120.000 - Rp 140.000</td>
                <td class="py-3 px-4 font-bold text-emerald-700">Rp 240 - Rp 280</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Mie Goreng Pesta / Hajatan</td>
                <td class="py-3 px-4">10 ml</td>
                <td class="py-3 px-4">5.000 ml (0,8 jerigen)</td>
                <td class="py-3 px-4">Rp 99.000 - Rp 115.000</td>
                <td class="py-3 px-4 font-bold text-emerald-700">Rp 198 - Rp 230</td>
            </tr>
            <tr class="bg-gray-50 font-bold text-gray-900">
                <td class="py-3 px-4" colspan="3">Rata-rata Komponen Bumbu Kecap Manis Jerigen per Porsi</td>
                <td class="py-3 px-4 text-emerald-800">Hemat Hingga 38%</td>
                <td class="py-3 px-4 text-emerald-800">Rp 200 - Rp 350 / Porsi</td>
            </tr>
        </tbody>
    </table>
</div>

<p>Dengan biaya kecap manis hanya <strong>Rp 200 hingga Rp 350 per kotak nasi</strong>, Anda dapat menyajikan menu dengan tampilan visual mewah berkilau (*glistening*) dan rasa manis gurih mantap tanpa khawatir biaya produksi melambung.</p>

<h2 id="tips-glazing">Rahasia Glazing Ayam Bakar & SOP Penyimpanan Jerigen Dapur</h2>

<p>Untuk hasil masakan berkelas restoran bintang lima, terapkan dua panduan teknis dari Executive Chef Wowin Food berikut:</p>

<h3 id="formula-glazing">Formula Glazing Ayam Bakar Katering Mengkilap & Meresap</h3>

<ol class="space-y-2 text-sm text-gray-700 pl-4 list-decimal">
    <li><strong>Tahap Ungkep Daging Ayam:</strong> Rebus ayam bersama bumbu rempah halus dan campurkan <strong>Kecap Manis Rajaku</strong> sebanyak 60% dari total takaran resep. Biarkan air bumbu menyusut perlahan hingga meresap sampai ke serat tulang ayam.</li>
    <li><strong>Racikan Olesan Akhir (Glaze):</strong> Sisa kuah kental ungkepan dicampur dengan <strong>Kecap Manis Wowin Premium</strong>, sedikit minyak bawang, dan mentega.</li>
    <li><strong>Pembakaran Cepat:</strong> Oleskan bumbu glaze pada menit-menit terakhir pembakaran di atas arang atau panggangan gas. Karamelisasi alami gula kelapa Wowin akan langsung membentuk lapisan karamel cokelat tua mengilap tanpa risiko hangus pahit.</li>
</ol>

<h3 id="sop-gudang">Standar Operasional Prosedur (SOP) Penyimpanan Jerigen di Dapur Katering</h3>

<ul>
    <li><strong>Gunakan Palet Kayu atau Rak Dapur Stainless:</strong> Hindari meletakkan jerigen kecap langsung di atas lantai semen yang dingin dan lembap agar suhu kecap tetap stabil dan tidak memicu pengembunan di dalam jerigen.</li>
    <li><strong>Pastikan Tutup Ulir Rapat Setelah Digunakan:</strong> Selalu tutup rapat mulut jerigen setelah pemakaian untuk menjaga kualitas aroma karamel alami dan menghindari kontaminasi udara luar.</li>
    <li><strong>Sistem Rotasi Stok FIFO (First In, First Out):</strong> Beri label tanggal penerimaan barang pada bodi jerigen, gunakan stok yang masuk lebih awal untuk menjaga kesegaran bahan baku.</li>
</ul>

<h2 id="faq-section">Pertanyaan Seputar Pasokan Kecap Jerigen Katering & Resto (FAQ)</h2>

<h3>Apakah kecap manis jerigen Wowin Food memiliki legalitas izin edar BPOM dan sertifikat Halal?</h3>
<p>Seluruh varian produk Wowin, Rajaku, dan Jangkar diproduksi oleh PT Wowin Purnomo Putera dengan menerapkan standar keamanan pangan ketat, berizin edar resmi BPOM RI, dan telah mengantongi sertifikat Halal MUI yang berlaku nasional.</p>

<h3>Berapa minimal order pasokan jerigen untuk pengiriman ke luar kota katering atau restoran?</h3>
<p>Kami melayani pembelian mulai dari partai kecil (beberapa karton/jerigen) untuk kebutuhan uji coba hingga pasokan rutin puluhan jerigen per bulan dengan dukungan ekspedisi kargo rekanan terpercaya ke seluruh Indonesia.</p>

<h3>Apakah tersedia kemasan yang lebih besar untuk dapur sentral katering skala ribuan porsi?</h3>
<p>Ya, untuk katering industri, katering haji/umrah, dan pabrik pengolahan makanan skala besar, PT Wowin Purnomo Putera menyediakan kemasan drum jerigen industri jumbo 26.000 ml (26 Kg) dengan harga grosir pabrik yang sangat kompetitif.</p>

<h2 id="kesimpulan">Kesimpulan & Panduan Pembelian Grosir Pabrik Langsung</h2>

<p>Memilih <strong>kecap manis jerigen 6 kg</strong> yang tepat bukan sekadar urusan belanja bumbu, melainkan keputusan bisnis strategis yang menjamin kualitas rasa masakan katering Anda tetap konsisten dan disukai pelanggan. Kelezatan karamel dari <a href="/products/16" class="text-emerald-600 font-bold hover:underline">Kecap Manis Wowin Jerigen</a>, fleksibilitas rasa dari <a href="/products/28" class="text-emerald-600 font-bold hover:underline">Kecap Manis Rajaku Jerigen</a>, serta keunggulan efisiensi HPP dari <a href="/products/45" class="text-emerald-600 font-bold hover:underline">Kecap Manis Jangkar Jerigen</a> adalah mitra terbaik kesuksesan usaha kuliner Anda.</p>

<p>Lengkapi juga kebutuhan bumbu meja saji Anda dengan ulasan <a href="/artikels/supplier-saos-sambal-dan-tomat-pedagang-bakso" class="text-emerald-600 font-bold hover:underline">supplier saos sambal dan tomat untuk pedagang bakso</a> serta panduan <a href="/artikels/rekomendasi-kecap-manis-usaha-bakso-mie-ayam" class="text-emerald-600 font-bold hover:underline">rekomendasi kecap manis untuk usaha bakso & mie ayam</a>.</p>

<div class="p-6 my-8 rounded-2xl bg-gradient-to-r from-emerald-800 via-teal-900 to-slate-900 text-white shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">
    <div>
        <h3 class="text-lg font-bold text-yellow-400 mb-1">Dapatkan Penawaran Harga Grosir Jerigen Khusus Katering & Resto!</h3>
        <p class="text-xs sm:text-sm text-emerald-100 max-w-xl">Bermitra langsung dengan produsen PT Wowin Purnomo Putera. Dapatkan sampel produk, potongan harga volume order, dan kemudahan pengiriman rutin ke dapur Anda.</p>
    </div>
    <div class="shrink-0 flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Wowin,%20saya%20pengusaha%20katering/resto%20tertarik%20dengan%20kecap%20manis%20jerigen%206%20kg" 
           target="_blank" 
           class="px-5 py-3 rounded-full bg-yellow-400 hover:bg-yellow-300 text-emerald-950 font-bold text-xs sm:text-sm text-center shadow-lg transition transform hover:scale-105">
            <i class="fab fa-whatsapp mr-1.5 text-base"></i> Hubungi CS via WhatsApp
        </a>
        <a href="/products" 
           class="px-5 py-3 rounded-full bg-white/20 hover:bg-white/30 text-white font-semibold text-xs sm:text-sm text-center border border-white/30 transition">
            Lihat Katalog Produk
        </a>
    </div>
</div>'
            ]
        ];

        Artikel::updateOrCreate(
            ['slug' => $slug],
            [
                'id' => (string) Str::uuid(),
                'user_id' => $userId,
                'judul' => $judul,
                'slug' => $slug,
                'isi' => $kontenBlok,
                'foto_artikel' => $fotoArtikel,
            ]
        );
    }
}
