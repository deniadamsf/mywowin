<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artikel;
use App\Models\User;
use Illuminate\Support\Str;

class ArtikelRekomendasiKecapBaksoSeeder extends Seeder
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

        $judul = 'Rekomendasi Kecap Manis untuk Usaha Bakso & Mie Ayam';
        $slug = 'rekomendasi-kecap-manis-usaha-bakso-mie-ayam';

        $fotoArtikel = [
            'foto_artikel/cover-rekomendasi-kecap-manis-usaha-bakso-mie-ayam.jpg',
            'foto_artikel/rekomendasi-kecap-manis-wowin-tumis-ayam-mie.jpg',
            'foto_artikel/rekomendasi-kecap-manis-rajaku-bakso-mie-ayam.jpg',
        ];

        $kontenBlok = [
            [
                'type' => 'text',
                'value' => '<p>Mencari <strong>rekomendasi kecap manis</strong> dengan cita rasa gurih legit, aroma sedap, dan efisiensi biaya bahan baku tertinggi adalah langkah krusial bagi setiap pengusaha kuliner bakso dan mie ayam di Indonesia. Kualitas kecap manis yang Anda gunakan langsung menentukan loyalitas pelanggan, karena lidah konsumen Indonesia sangat peka terhadap keseimbangan rasa kuah dan bumbu daging.</p>

<p>Dalam bisnis bakso dan mie ayam, kecap manis bukan sekadar pemanis pelengkap di atas meja saji. Lebih dari itu, kecap manis merupakan bahan baku inti (<em>core ingredient</em>) dalam meracik bumbu tumis topping ayam mie ayam serta penyeimbang rasa umami pada semangkuk kuah kaldu bakso sapi hangat.</p>

<p>Banyak pengusaha kuliner pemula melakukan kesalahan fatal dengan memilih kecap manis yang terlalu encer, dominan pemanis sintetis, atau mudah berbusa saat terkena kuah panas. Akibatnya, pemakaian kecap menjadi sangat boros, kuah bakso berubah keruh kehitaman tanpa rasa gurih, dan margin laba kotor usaha kuliner Anda tergerus drastis.</p>

<div class="p-5 my-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-950">
    <h3 class="text-base font-bold text-emerald-900 mb-2 flex items-center gap-2">
        <i class="fas fa-list-ul text-emerald-600"></i> Daftar Isi Artikel (Navigasi Cepat)
    </h3>
    <ul class="space-y-1.5 text-xs sm:text-sm">
        <li><a href="#kriteria" class="text-emerald-800 hover:text-emerald-950 underline font-medium">1. Kriteria Memilih Rekomendasi Kecap Manis untuk Usaha Bakso & Mie Ayam</a></li>
        <li><a href="#produk-wowin" class="text-emerald-800 hover:text-emerald-950 underline font-medium">2. 3 Pilihan Rekomendasi Kecap Manis Terbaik dari Wowin Food</a></li>
        <li><a href="#tabel-komparasi" class="text-emerald-800 hover:text-emerald-950 underline font-medium">3. Tabel Perbandingan Karakteristik Varian Kecap Manis</a></li>
        <li><a href="#simulasi-hpp" class="text-emerald-800 hover:text-emerald-950 underline font-medium">4. Simulasi Analisis HPP Pemakaian Kecap per 100 Porsi</a></li>
        <li><a href="#resep-topping" class="text-emerald-800 hover:text-emerald-950 underline font-medium">5. Formula Bumbu Topping Mie Ayam Mengkilap & Racikan Meja Higienis</a></li>
        <li><a href="#faq-section" class="text-emerald-800 hover:text-emerald-950 underline font-medium">6. Pertanyaan Seputar Rekomendasi Kecap Manis Pedagang (FAQ)</a></li>
        <li><a href="#kesimpulan" class="text-emerald-800 hover:text-emerald-950 underline font-medium">7. Kesimpulan & Cara Pemesanan Grosir Pabrik Langsung</a></li>
    </ul>
</div>

<h2 id="kriteria">Kriteria Utama dan Rekomendasi Kecap Manis untuk Usaha Bakso & Mie Ayam</h2>

<p>Sebelum memilih merek untuk pasokan dapur resto Anda, pahami bahwa karakteristik kecap untuk keperluan komersial berbeda signifikan dengan konsumsi rumahan tangga biasa. Pedagang membutuhkan formula yang konsisten, tahan panas tinggi, dan hemat takaran.</p>

<p>Berikut adalah 4 kriteria baku dalam menentukan <strong>rekomendasi kecap manis</strong> bermutu tinggi untuk operasional warung bakso dan kedai mie ayam:</p>

<ul>
    <li><strong>Kekentalan Alami (Viskositas Tinggi dari Gula Merah Murni):</strong> Kecap yang mengandalkan gula merah kelapa murni memiliki kekentalan pekat alami tanpa campuran pengental berlebih. Karakter ini membuat kecap menempel sempurna pada butiran mie dan bakso, bukan langsung larut hilang begitu disiram sedikit kuah.</li>
    <li><strong>Kadar Ekstrak Kedelai Tinggi & Rasa Gurih Umami Alami:</strong> Kecap manis unggul memiliki fermentasi sari kedelai hitam pilihan yang kuat. Gurih alami kedelai memangkas kebutuhan bumbu penyedap sintetis tambahan, sehingga cita rasa kuah bakso tetap bersih, lembut di tenggorokan, dan tidak memicu rasa haus berlebihan.</li>
    <li><strong>Ketahanan Panas Wajan Tinggi (Heat-Resistant / Anti-Pahit):</strong> Saat menumis topping ayam mie ayam, kecap akan mengalami kontak langsung dengan minyak mendidih dan wajan panas. Kecap berkualitas tinggi tidak akan cepat gosong atau meninggalkan rasa getir terbakar (<em>burnt bitter taste</em>), melainkan menciptakan karamelisasi wangi (<em>wok hei</em>) yang khas.</li>
    <li><strong>Warna Hitam Mengkilap (Glistening Finish):</strong> Visual makanan memegang peranan 50% dalam memancing selera makan pembeli. Kecap manis terbaik memberikan lapisan cokelat tua berkilau pada potongan daging ayam mie ayam, membuatnya tampak segar, mewah, dan premium.</li>
</ul>'
            ],
            [
                'type' => 'image',
                'value' => 'foto_artikel/rekomendasi-kecap-manis-wowin-tumis-ayam-mie.jpg'
            ],
            [
                'type' => 'text',
                'value' => '<h2 id="produk-wowin">3 Pilihan Rekomendasi Kecap Manis Terbaik dari Wowin Food</h2>

<p>Sebagai produsen kecap dan bumbu masak terpercaya di Indonesia, <strong>PT Wowin Purnomo Putera</strong> menghadirkan lini produk kecap manis jerigen dan kemasan pouch isi ulang yang dirancang khusus untuk efisiensi bisnis HPP pedagang kuliner nusantara.</p>

<p>Berikut adalah ulasan mendalam 3 varian resmi Wowin Food yang menjadi <strong>rekomendasi kecap manis</strong> terdepan bagi ribuan pengusaha bakso dan mie ayam di berbagai daerah:</p>

<h3 id="rajaku">1. Kecap Manis Rajaku Premium Gold (Spesialis Kuah Bakso & Mie Ayam)</h3>

<p>Kecap Manis Rajaku Premium Gold merupakan varian favorit utama para pedagang bakso solo, bakso malang, dan mie ayam gerobak hingga restoran. Produk ini memiliki kadar sari kedelai tinggi mencapai <strong>22%</strong> dengan paduan gula aren kelapa berkualitas.</p>

<p>Karakteristik rasanya sangat seimbang: perpaduan manis gurih yang pas dan tidak menusuk tenggorokan. Saat dituang ke dalam semangkuk kaldu bakso panas, Rajaku langsung menyatu dengan lembut tanpa merusak aroma alami kaldu sumsum sapi.</p>

<p>Untuk kebutuhan komersial harian yang praktis dan ekonomis, Anda dapat memesan langsung <a href="/products/28" class="text-emerald-600 font-bold hover:underline">Kecap Manis Rajaku Jerigen 6.200 ml</a> atau varian kemasan isi ulang <a href="/products/25" class="text-emerald-600 font-bold hover:underline">Rajaku Pouch 550 ml</a> dengan harga grosir pabrik langsung.</p>

<h3 id="wowin">2. Kecap Manis Wowin Premium (Grade Tertinggi Manis Legit & Kental)</h3>

<p>Bagi Anda yang mengelola kedai mie ayam pangsit premium, bakso iga, atau usaha kuliner yang mengedepankan kualitas kelas atas, Kecap Manis Wowin adalah standar emas yang tak tertandingi.</p>

<p>Diproduksi dari 100% gula kelapa murni tanpa pemanis buatan, Kecap Manis Wowin memiliki tingkat viskositas paling pekat dengan warna hitam mengilap sempurna. Cita rasanya manis legit murni dengan sentuhan aroma karamel kelapa alami yang sangat menggoda.</p>

<p>Keunggulan utama Wowin terletak pada efisiensi pemakaian: hanya dengan takaran 1 sendok makan, Anda sudah mendapatkan warna cokelat pekat dan rasa manis yang setara dengan 2 hingga 3 sendok kecap biasa di pasaran. Tersedia dalam ukuran pasokan industri <a href="/products/16" class="text-emerald-600 font-bold hover:underline">Kecap Manis Wowin Jerigen 6.200 ml</a> serta kemasan praktis <a href="/products/11" class="text-emerald-600 font-bold hover:underline">Wowin Pouch 600 ml</a>.</p>

<h3 id="jangkar">3. Kecap Manis Jangkar (Solusi Efisiensi Biaya HPP Pedagang Juara)</h3>

<p>Bagi pelaku usaha kuliner skala besar, jaringan cabang mie ayam baso, atau pedagang kaki lima yang mengejar margin keuntungan maksimal, Kecap Manis Jangkar adalah pilihan paling cerdas.</p>

<p>Mengusung tagline <em>"Pasti Enaakk"</em> dengan lambang jangkar laut kuning emas legendaris, varian Jangkar memiliki ketahanan panas paling tangguh terhadap api besar. Karakter ini menjadikannya sangat unggul untuk menumis bumbu dasar minyak ayam mie ayam dan tumisan daging ayam cincang dalam jumlah puluhan kilogram sekaligus.</p>

<p>Harga per liter yang sangat kompetitif menjadikan varian ini favorit untuk menekan HPP secara signifikan. Pasokan tersedia lengkap dalam kemasan <a href="/products/45" class="text-emerald-600 font-bold hover:underline">Kecap Manis Jangkar Jerigen 6.200 ml</a>, jerigen drum industri 26 kg, dan kemasan ekonomis <a href="/products/47" class="text-emerald-600 font-bold hover:underline">Jangkar Pouch Merah 500 ml</a>.</p>'
            ],
            [
                'type' => 'image',
                'value' => 'foto_artikel/rekomendasi-kecap-manis-rajaku-bakso-mie-ayam.jpg'
            ],
            [
                'type' => 'text',
                'value' => '<h2 id="tabel-komparasi">Tabel Perbandingan Karakteristik Varian Kecap Manis Wowin Food</h2>

<p>Untuk membantu Anda menentukan <strong>rekomendasi kecap manis</strong> mana yang paling sesuai dengan konsep menu dan anggaran usaha Anda, berikut rangkuman tabel komparasi lengkapnya:</p>

<div class="overflow-x-auto my-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="mobile-table-hint">Geser tabel ke samping <i class="fas fa-arrows-alt-h ml-1"></i></div>
    <table class="w-full min-w-[620px] text-left border-collapse text-xs sm:text-sm">
        <thead>
            <tr class="bg-emerald-800 text-white">
                <th class="py-3 px-4 font-semibold">Parameter Uji</th>
                <th class="py-3 px-4 font-semibold">Rajaku Premium Gold</th>
                <th class="py-3 px-4 font-semibold">Wowin Premium</th>
                <th class="py-3 px-4 font-semibold">Jangkar Pasti Enaakk</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-700">
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Profil Rasa Inti</td>
                <td class="py-3 px-4">Manis gurih umami seimbang (Kedelai 22%)</td>
                <td class="py-3 px-4">Manis legit pekat, aroma karamel kelapa murni</td>
                <td class="py-3 px-4">Manis mantap, gurih merata, tahan panas wajan</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Tingkat Kekentalan</td>
                <td class="py-3 px-4">Kental sedang ke pekat (mudah larut di kuah)</td>
                <td class="py-3 px-4">Paling kental pekat (viskositas tertinggi)</td>
                <td class="py-3 px-4">Kental standar operasional wajan cepat</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Aplikasi Terbaik</td>
                <td class="py-3 px-4">Racikan kuah bakso meja saji & mie ayam reguler</td>
                <td class="py-3 px-4">Topping mie ayam pangsit spesial & cocolan bakso</td>
                <td class="py-3 px-4">Tumisan bumbu minyak ayam & produksi massal</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Pilihan Kemasan</td>
                <td class="py-3 px-4">Jerigen 6.200 ml, Jerigen 26 kg, Pouch 550 ml</td>
                <td class="py-3 px-4">Jerigen 6.200 ml, Botol 500 ml, Pouch 600 ml</td>
                <td class="py-3 px-4">Jerigen 6.200 ml, Jerigen 26 kg, Pouch 500 ml</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Tingkat Efisiensi HPP</td>
                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 text-xs font-bold">Tinggi (Optimal)</span></td>
                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-purple-100 text-purple-800 text-xs font-bold">Premium Ekonomis</span></td>
                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-xs font-bold">Juara Hemat HPP</span></td>
            </tr>
        </tbody>
    </table>
</div>

<h2 id="simulasi-hpp">Simulasi Analisis HPP Pemakaian Kecap per 100 Porsi Bakso & Mie Ayam</h2>

<p>Kunci sukses dalam mengelola bisnis kuliner yang menguntungkan adalah kontrol presisi pada Harga Pokok Penjualan (HPP). Menggunakan kemasan jerigen ukuran 6,2 kg (6.200 ml) memangkas pengeluaran bumbu hingga 35% jika dibandingkan dengan membeli botol eceran di warung.</p>

<p>Berikut adalah simulasi kalkulasi pemakaian <strong>rekomendasi kecap manis</strong> kemasan jerigen 6.200 ml untuk 100 porsi hidangan:</p>

<div class="overflow-x-auto my-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="mobile-table-hint">Geser tabel ke samping <i class="fas fa-arrows-alt-h ml-1"></i></div>
    <table class="w-full min-w-[620px] text-left border-collapse text-xs sm:text-sm">
        <thead>
            <tr class="bg-emerald-800 text-white">
                <th class="py-3 px-4 font-semibold">Jenis Menu Usaha</th>
                <th class="py-3 px-4 font-semibold">Takaran Rata-rata</th>
                <th class="py-3 px-4 font-semibold">Total Kebutuhan / 100 Porsi</th>
                <th class="py-3 px-4 font-semibold">Estimasi Biaya HPP Kecap</th>
                <th class="py-3 px-4 font-semibold">Biaya per Porsi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-700">
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Mie Ayam (Bumbu Topping Ayam)</td>
                <td class="py-3 px-4">15 ml / porsi</td>
                <td class="py-3 px-4">1.500 ml (1,5 Liter)</td>
                <td class="py-3 px-4">Rp 30.100 - Rp 35.000</td>
                <td class="py-3 px-4 font-bold text-emerald-700">Rp 301 - Rp 350</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Bakso Sapi (Racikan Meja Pelanggan)</td>
                <td class="py-3 px-4">10 - 12 ml / porsi</td>
                <td class="py-3 px-4">1.100 ml (1,1 Liter)</td>
                <td class="py-3 px-4">Rp 22.000 - Rp 25.700</td>
                <td class="py-3 px-4 font-bold text-emerald-700">Rp 220 - Rp 257</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Mie Bakso Komplit Spesial</td>
                <td class="py-3 px-4">20 ml / porsi</td>
                <td class="py-3 px-4">2.000 ml (2,0 Liter)</td>
                <td class="py-3 px-4">Rp 40.100 - Rp 46.700</td>
                <td class="py-3 px-4 font-bold text-emerald-700">Rp 401 - Rp 467</td>
            </tr>
        </tbody>
    </table>
</div>

<p>Dari simulasi hitungan di atas, biaya komponen kecap manis berkualitas tinggi hanya berkisar antara <strong>Rp 200 hingga Rp 450 per mangkok</strong>. Dengan biaya yang sangat terjangkau ini, Anda menyuguhkan cita rasa restoran mewah yang membuat pelanggan setia terus datang kembali.</p>

<h2 id="resep-topping">Panduan Penggunaan Rekomendasi Kecap Manis pada Topping & Meja Saji</h2>

<p>Agar hasil olahan masakan Anda mencapai kenikmatan maksimal, ikuti dua tips praktis penerapan dari chef profesional Wowin Food berikut:</p>

<h3 id="formula-ayam">Formula Bumbu Marinasi Topping Daging Ayam Mie Ayam Mengkilap</h3>

<ol class="space-y-2 text-sm text-gray-700 pl-4 list-decimal">
    <li><strong>Tumis Bumbu Halus Terlebih Dahulu:</strong> Bawang merah, bawang putih, kemiri, jahe, kunyit, dan daun jeruk ditumis hingga benar-benar matang dan mengeluarkan aroma harum minyak alami.</li>
    <li><strong>Karamelisasi Awal dengan Kecap:</strong> Masukkan potongan daging ayam dadu, lalu tuangkan <strong>Kecap Manis Wowin atau Rajaku</strong> di pinggiran wajan panas. Teknik ini menciptakan karamelisasi wangi tanpa membakar daging.</li>
    <li><strong>Ungkep dengan Api Kecil:</strong> Tambahkan sedikit air kaldu ayam, daun bawang, dan bumbu garam secukupnya. Tutup wajan dan biarkan bumbu meresap perlahan sampai kuah mengental pekat dan daging ayam berwarna cokelat tua mengkilap.</li>
</ol>

<h3 id="manajemen-meja">Manajemen Penyajian Kecap Botol Meja Warung Bakso yang Higienis</h3>

<p>Kecap yang disajikan di meja makan pelanggan harus selalu tampil prima dan mengundang selera. Ikuti standar operasional berikut:</p>

<ul>
    <li><strong>Gunakan Botol Kaca atau Plastik Food-Grade Bertutup Corong:</strong> Hindari membiarkan botol kecap terbuka tanpa tutup agar terhindar dari debu dan lalat.</li>
    <li><strong>Rotasi Isi Ulang Harian (Metode FIFO):</strong> Habiskan dan bersihkan botol kecap meja saji sebelum menuangkan pasokan baru dari jerigen 6,2 kg. Hal ini menjaga kecap manis tetap segar, tidak masam, dan tidak mengkristal di mulut botol.</li>
    <li><strong>Simpan Jerigen Pasokan di Ruang Kering Sejuk:</strong> Simpan jerigen Wowin Food di atas palet atau meja dapur, hindari kontak langsung dengan lantai semen dingin untuk menjaga konsistensi tekstur.</li>
</ul>

<h2 id="faq-section">Pertanyaan Seputar Rekomendasi Kecap Manis untuk Pedagang Kuliner (FAQ)</h2>

<h3>Apakah kecap manis jerigen Wowin Food sudah memiliki sertifikasi Halal dan izin edar resmi BPOM RI?</h3>
<p>Ya, seluruh lini produk kecap manis Wowin, Rajaku, dan Jangkar yang diproduksi oleh PT Wowin Purnomo Putera telah memiliki sertifikasi Halal resmi dari BPOM RI dan MUI, menjamin kebersihan, higienitas, dan kepatuhan standar keamanan pangan nasional.</p>

<h3>Mengapa memilih kecap jerigen 6,2 kg atau 26 kg lebih menguntungkan dibanding botol kecil?</h3>
<p>Kecap kemasan jerigen dirancang khusus untuk operasional usaha kuliner B2B sehingga memangkas biaya kemasan berlebih. Biaya per mililiter jauh lebih murah hingga 35% dibandingkan kemasan botol retail, menghemat anggaran operasional jutaan rupiah setiap bulannya.</p>

<h3>Bagaimana cara pedagang bakso dan mie ayam memesan pasokan kecap manis langsung dari pabrik?</h3>
<p>Pemesanan grosir pabrik dapat dilakukan secara mudah dan aman melalui situs resmi mywowin.com atau dengan menghubungi Customer Service resmi PT Wowin Purnomo Putera via WhatsApp untuk pengiriman ke seluruh wilayah Indonesia.</p>

<h2 id="kesimpulan">Kesimpulan dan Cara Pemesanan Grosir Resmi Pabrik</h2>

<p>Memilih <strong>rekomendasi kecap manis</strong> yang tepat merupakan investasi strategis paling menguntungkan bagi kelangsungan usaha bakso dan mie ayam Anda. Dengan kelezatan rasa autentik dari <a href="/products/28" class="text-emerald-600 font-bold hover:underline">Kecap Manis Rajaku</a>, kekentalan premium dari <a href="/products/16" class="text-emerald-600 font-bold hover:underline">Kecap Manis Wowin</a>, dan efisiensi HPP juara dari <a href="/products/45" class="text-emerald-600 font-bold hover:underline">Kecap Manis Jangkar</a>, hidangan Anda akan selalu dirindukan pelanggan.</p>

<p>Pelajari juga artikel panduan mitra kuliner kami sebelumnya mengenai <a href="/artikels/supplier-kecap-manis-jerigen-murah-untuk-usaha-kuliner" class="text-emerald-600 font-bold hover:underline">supplier kecap manis jerigen murah untuk usaha kuliner</a> untuk strategi belanja bahan baku yang lebih hemat.</p>

<div class="p-6 my-8 rounded-2xl bg-gradient-to-r from-emerald-800 to-teal-900 text-white shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">
    <div>
        <h3 class="text-lg font-bold text-yellow-400 mb-1">Siap Tingkatkan Omzet Warung Bakso & Mie Ayam Anda?</h3>
        <p class="text-xs sm:text-sm text-emerald-100 max-w-xl">Dapatkan harga grosir pabrik langsung untuk kemasan Jerigen 6.200 ml dan Jerigen Industri 26 kg. Hubungi tim sales kami sekarang untuk konsultasi sampel dan pengiriman ke kota Anda.</p>
    </div>
    <div class="shrink-0 flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Wowin,%20saya%20tertarik%20dengan%20rekomendasi%20kecap%20manis%20untuk%20usaha%20kuliner%20saya" 
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
