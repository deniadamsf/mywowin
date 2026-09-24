<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artikel;
use App\Models\User;
use Illuminate\Support\Str;

class ArtikelSupplierSaosBaksoSeeder extends Seeder
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

        $judul = 'Supplier Saos Sambal dan Tomat untuk Pedagang Bakso';
        $slug = 'supplier-saos-sambal-dan-tomat-pedagang-bakso';

        $fotoArtikel = [
            'foto_artikel/cover-supplier-saos-sambal-dan-tomat-pedagang-bakso.jpg',
            'foto_artikel/saos-sambal-cabe-tani-dan-raja-tomat-racikan-bakso.jpg',
            'foto_artikel/supplier-saos-sambal-dan-tomat-pelengkap-kecap-bakso.jpg',
        ];

        $kontenBlok = [
            [
                'type' => 'text',
                'value' => '<p>Menemukan mitra <strong>supplier saos sambal dan tomat</strong> yang mampu menyediakan pasokan bumbu berkualitas stabil dengan harga grosir pabrik langsung adalah kunci keberhasilan para pedagang bakso di seluruh Indonesia. Dalam bisnis kuliner bakso, racikan saos sambal dan saos tomat di atas meja makan bukan hanya sekadar pelengkap visual, melainkan penentu utama kenikmatan kuah kaldu yang membuat pelanggan selalu rindu untuk datang kembali.</p>

<p>Kombinasi saos sambal pedas gurih, saos tomat manis segar, kecap manis legit, dan sedikit tetesan cuka makan merupakan formula legendaris kuah bakso nusantara. Apabila salah satu komponen saos memiliki mutu buruk—misalnya beraroma asam menyengat, terlalu encer, atau meninggalkan sensasi getir di lidah—seluruh kenikmatan daging bakso sapi yang Anda olah dengan susah payah dapat rusak seketika.</p>

<p>Oleh sebab itu, cerdas dalam memilih mitra pabrik dan distributor bahan baku kuliner merupakan langkah fundamental. Artikel ini mengupas tuntas kriteria pemilihan bumbu saos komersial, perbandingan varian resmi dari <strong>PT Wowin Purnomo Putera</strong>, serta simulasi penghematan biaya operasional (HPP) untuk meningkatkan keuntungan bersih warung bakso Anda.</p>

<div class="p-5 my-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-950">
    <h3 class="text-base font-bold text-emerald-900 mb-2 flex items-center gap-2">
        <i class="fas fa-list-ul text-emerald-600"></i> Daftar Isi Artikel (Navigasi Cepat)
    </h3>
    <ul class="space-y-1.5 text-xs sm:text-sm">
        <li><a href="#tantangan" class="text-emerald-800 hover:text-emerald-950 underline font-medium">1. Tantangan Pedagang Bakso dalam Memilih Saos Sambal dan Tomat Berkualitas</a></li>
        <li><a href="#produk-saos" class="text-emerald-800 hover:text-emerald-950 underline font-medium">2. Keunggulan Saos Cabe Tani & Raja Tomat dari Supplier Resmi Wowin Food</a></li>
        <li><a href="#tabel-komparasi" class="text-emerald-800 hover:text-emerald-950 underline font-medium">3. Tabel Perbandingan Karakteristik Saos Cabe Tani & Raja Tomat</a></li>
        <li><a href="#simulasi-hpp" class="text-emerald-800 hover:text-emerald-950 underline font-medium">4. Simulasi Analisis HPP Pemakaian Saos per 100 Porsi Bakso</a></li>
        <li><a href="#tips-racikan" class="text-emerald-800 hover:text-emerald-950 underline font-medium">5. Tips Manajemen Meja Saji & Formula Racikan Kuah Bakso Juara</a></li>
        <li><a href="#faq-section" class="text-emerald-800 hover:text-emerald-950 underline font-medium">6. Pertanyaan Seputar Supplier Saos Sambal dan Tomat Pedagang Bakso (FAQ)</a></li>
        <li><a href="#kesimpulan" class="text-emerald-800 hover:text-emerald-950 underline font-medium">7. Kesimpulan & Panduan Order Grosir Pabrik Langsung</a></li>
    </ul>
</div>

<h2 id="tantangan">Tantangan Pedagang Bakso dalam Memilih Saos Sambal dan Tomat Berkualitas</h2>

<p>Banyak pengusaha kuliner bakso mengeluhkan ketidakstabilan bahan baku saos di pasar tradisional. Masalah yang kerap dihadapi antara lain harga eceran yang fluktuatif, tekstur saos yang terlalu cair sehingga boros saat dituang konsumen, hingga saos yang cepat mengendap dan basi jika terkena udara meja warung.</p>

<p>Bagi pedagang bakso profesional, saos komersial wajib memenuhi standar operasional berikut:</p>

<ul>
    <li><strong>Kekentalan yang Pas (Mudah Berbaur di Kuah Panas):</strong> Saos tidak boleh terlalu encer seperti air, namun juga tidak boleh menggumpal kaku. Viskositas ideal memungkinkan saos mengalir mulus dari botol meja saji dan langsung larut merata saat diaduk bersama kuah kaldu sapi panas.</li>
    <li><strong>Warna Merah Segar Alami (Menggugah Selera Makan):</strong> Visual warna kuah bakso yang merah merona segar memberikan daya tarik psikologis yang kuat. Warna merah alami saos cabai dan tomat yang berkualitas membuat semangkuk bakso terlihat jauh lebih lezat dan mengundang decak kagum pembeli.</li>
    <li><strong>Keseimbangan Asam, Manis, dan Pedas Gurih:</strong> Saos sambal dan saos tomat bermutu tinggi tidak boleh meninggalkan rasa pahit atau getir di tenggorokan. Bahan baku cabai asli dan pasta tomat segar menciptakan profil rasa gurih alami yang memperkuat citarasa rempah kuah bakso.</li>
    <li><strong>Stabilitas & Ketahanan Simpan Higienis:</strong> Saos yang disimpan di botol meja warung harus tahan terhadap suhu ruangan tropis, tidak mudah berbusa, dan tidak berubah aroma menjadi tengik dalam siklus pemakaian harian.</li>
</ul>'
            ],
            [
                'type' => 'image',
                'value' => 'foto_artikel/saos-sambal-cabe-tani-dan-raja-tomat-racikan-bakso.jpg'
            ],
            [
                'type' => 'text',
                'value' => '<h2 id="produk-saos">Keunggulan Saos Cabe Tani & Raja Tomat dari Supplier Resmi Wowin Food</h2>

<p>Sebagai produsen bumbu masak dan kondimen terpercaya, <strong>PT Wowin Purnomo Putera</strong> melalui brand <strong>Wowin Food</strong> menjadi solusi utama sebagai <strong>supplier saos sambal dan tomat</strong> bagi ribuan pedagang bakso solo, bakso malang, bakso aci, bakso urat, dan warung mie bakso di seluruh Indonesia.</p>

<p>Berikut adalah lini produk saos unggulan yang dirancang khusus untuk memenuhi standar cita rasa dan efisiensi HPP pedagang kuliner:</p>

<h3 id="cabe-tani">1. Saos Sambal Cabe Tani: Pedas Segar Mantap & Warna Alami</h3>

<p>Kondimen pedas adalah roh dari semangkuk bakso kuah gurih. <strong>Saos Cabe Tani</strong> diproduksi dari ekstrak cabai merah pilihan berkualitas tinggi yang dipadukan dengan rempah bawang putih alami, menghasilkan aroma pedas gurih yang segar dan menggigit.</p>

<p>Keunggulan Saos Cabe Tani bagi pedagang bakso terletak pada konsistensi kekentalannya yang pas serta warnanya yang merah oranye menyala alami. Saat disiramkan ke atas butiran bakso urat atau bakso halus, saos menempel sempurna sebelum berbaur harmonis dengan kaldu gurih.</p>

<p>Untuk operasional meja makan harian, tersedia kemasan botol praktis <a href="/products/42" class="text-emerald-600 font-bold hover:underline">Saos Cabe Tani Botol 500 ml</a> serta kemasan isi ulang ekonomis <a href="/products/40" class="text-emerald-600 font-bold hover:underline">Saos Cabe Tani Ball 600 gram</a> dan jerigen pasokan dapur.</p>

<h3 id="raja-tomat">2. Saos Raja Tomat: Asam Manis Gurih Penyeimbang Kaldu Daging</h3>

<p>Banyak pelanggan warung bakso menyukai perpaduan rasa asam manis yang lembut untuk mengimbangi pekatnya lemak kaldu sumsum sapi. <strong>Saos Raja Tomat</strong> hadir dengan konsentrat pasta tomat bermutu tinggi yang memberikan rasa asam segar manis alami tanpa pemanis buatan berlebih.</p>

<p>Warna merah pekat dari Saos Raja Tomat memberikan sentuhan kilau yang sangat menarik saat dicampurkan ke dalam mangkok bakso. Karakter asam manis alaminya membuat kuah bakso terasa lebih segar, tidak enek, dan ramah di lambung anak-anak maupun orang dewasa.</p>

<p>Dapatkan harga grosir langsung dari pabrik untuk varian kemasan <a href="/products/37" class="text-emerald-600 font-bold hover:underline">Saos Raja Tomat Botol 500 ml</a> dan kemasan bantal hemat <a href="/products/36" class="text-emerald-600 font-bold hover:underline">Saos Raja Tomat Ball 660 ml</a>.</p>

<h3 id="cuka-rajaku">3. Cuka Makan Rajaku: Pelengkap Kesegaran Kuah Bakso Autentik</h3>

<p>Racikan meja warung bakso tidak lengkap tanpa kehadiran cuka makan yang higienis. <strong>Cuka Makan Rajaku</strong> memiliki tingkat keasaman terukur 5% yang jernih, bersih, dan segar.</p>

<p>Hanya dengan beberapa tetes, <a href="/products/35" class="text-emerald-600 font-bold hover:underline">Cuka Makan Rajaku 150 ml</a> mampu mengangkat seluruh aroma bumbu kaldu, menyeimbangkan rasa gurih saos sambal dan manisnya kecap, serta menghadirkan kesegaran asam yang bersih di rongga mulut.</p>'
            ],
            [
                'type' => 'image',
                'value' => 'foto_artikel/supplier-saos-sambal-dan-tomat-pelengkap-kecap-bakso.jpg'
            ],
            [
                'type' => 'text',
                'value' => '<h2 id="tabel-komparasi">Tabel Perbandingan Karakteristik Saos Cabe Tani & Raja Tomat</h2>

<p>Sebagai mitra <strong>supplier saos sambal dan tomat</strong> terpercaya, Wowin Food menghadirkan spesifikasi produk yang jelas untuk mempermudah perhitungan operasional usaha kuliner Anda:</p>

<div class="overflow-x-auto my-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="mobile-table-hint">Geser tabel ke samping <i class="fas fa-arrows-alt-h ml-1"></i></div>
    <table class="w-full min-w-[620px] text-left border-collapse text-xs sm:text-sm">
        <thead>
            <tr class="bg-emerald-800 text-white">
                <th class="py-3 px-4 font-semibold">Parameter Uji</th>
                <th class="py-3 px-4 font-semibold">Saos Sambal Cabe Tani</th>
                <th class="py-3 px-4 font-semibold">Saos Raja Tomat</th>
                <th class="py-3 px-4 font-semibold">Cuka Makan Rajaku</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-700">
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Bahan Baku Inti</td>
                <td class="py-3 px-4">Cabai merah pilihan, bawang putih, bumbu rempah</td>
                <td class="py-3 px-4">Pasta tomat alami, gula tebu, rempah bumbu</td>
                <td class="py-3 px-4">Asam asetat pangan fermentasi murni 5%</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Profil Rasa Utama</td>
                <td class="py-3 px-4">Pedas mantap, gurih sedap, aroma cabai segar</td>
                <td class="py-3 px-4">Asam segar manis lembut, kaya aroma tomat</td>
                <td class="py-3 px-4">Asam jernih segar, mengangkat rasa kaldu</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Tampilan & Warna</td>
                <td class="py-3 px-4">Merah cabai cerah alami, tekstur lembut</td>
                <td class="py-3 px-4">Merah tua merona mengkilap (glossy)</td>
                <td class="py-3 px-4">Cairan bening kristal higienis</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Fungsi pada Menu Bakso</td>
                <td class="py-3 px-4">Pemicu rasa pedas utama & sambal cocolan</td>
                <td class="py-3 px-4">Penyeimbang lemak kaldu & pemberi warna kuah</td>
                <td class="py-3 px-4">Pemberi aksen asam segar peredam lemak</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Pilihan Kemasan</td>
                <td class="py-3 px-4">Botol 500 ml, Ball 600 gr, Jerigen pasokan</td>
                <td class="py-3 px-4">Botol 500 ml, Ball 660 ml, Jerigen pasokan</td>
                <td class="py-3 px-4">Botol kaca/plastik food-grade 150 ml</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Efisiensi Biaya HPP</td>
                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-xs font-bold">Sangat Hemat</span></td>
                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 text-xs font-bold">Sangat Hemat</span></td>
                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-purple-100 text-purple-800 text-xs font-bold">Irit Bertahan Lama</span></td>
            </tr>
        </tbody>
    </table>
</div>

<h2 id="simulasi-hpp">Simulasi Analisis HPP Pemakaian Saos Sambal dan Tomat per 100 Porsi Bakso</h2>

<p>Bagi pedagang bakso, menghitung pengeluaran bumbu meja saji secara cermat akan berdampak langsung pada laba harian. Mengambil pasokan saos secara grosir dari produsen langsung memangkas biaya hingga 30% dibanding membeli eceran di toko kelontong.</p>

<p>Berikut adalah estimasi simulasi pemakaian saos sambal dan tomat untuk 100 porsi bakso:</p>

<div class="overflow-x-auto my-6 rounded-2xl border border-gray-200 shadow-sm">
    <div class="mobile-table-hint">Geser tabel ke samping <i class="fas fa-arrows-alt-h ml-1"></i></div>
    <table class="w-full min-w-[620px] text-left border-collapse text-xs sm:text-sm">
        <thead>
            <tr class="bg-emerald-800 text-white">
                <th class="py-3 px-4 font-semibold">Komponen Bumbu Meja</th>
                <th class="py-3 px-4 font-semibold">Takaran / Porsi</th>
                <th class="py-3 px-4 font-semibold">Kebutuhan / 100 Porsi</th>
                <th class="py-3 px-4 font-semibold">Estimasi Biaya Total</th>
                <th class="py-3 px-4 font-semibold">Biaya HPP / Porsi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-700">
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Saos Sambal Cabe Tani</td>
                <td class="py-3 px-4">12 ml</td>
                <td class="py-3 px-4">1.200 ml (2,4 botol)</td>
                <td class="py-3 px-4">Rp 14.500 - Rp 16.800</td>
                <td class="py-3 px-4 font-bold text-emerald-700">Rp 145 - Rp 168</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Saos Raja Tomat</td>
                <td class="py-3 px-4">12 ml</td>
                <td class="py-3 px-4">1.200 ml (2,4 botol)</td>
                <td class="py-3 px-4">Rp 14.500 - Rp 16.800</td>
                <td class="py-3 px-4 font-bold text-emerald-700">Rp 145 - Rp 168</td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold text-gray-900">Cuka Makan Rajaku</td>
                <td class="py-3 px-4">1 - 2 tetes (1 ml)</td>
                <td class="py-3 px-4">100 ml (0,6 botol)</td>
                <td class="py-3 px-4">Rp 3.900 - Rp 4.500</td>
                <td class="py-3 px-4 font-bold text-emerald-700">Rp 39 - Rp 45</td>
            </tr>
            <tr class="bg-gray-50 font-bold text-gray-900">
                <td class="py-3 px-4" colspan="3">Total Estimasi HPP Saos & Cuka per Mangkok Bakso</td>
                <td class="py-3 px-4 text-emerald-800">Rp 32.900 - Rp 38.100</td>
                <td class="py-3 px-4 text-emerald-800">Rp 329 - Rp 381</td>
            </tr>
        </tbody>
    </table>
</div>

<p>Dengan total beban HPP saos dan cuka hanya sekitar <strong>Rp 300-an per mangkok</strong>, pedagang bakso dapat memberikan pengalaman cita rasa premium yang sangat memuaskan tanpa membebani biaya produksi harian.</p>

<h2 id="tips-racikan">Tips Manajemen Meja Saji & Formula Racikan Kuah Bakso Juara</h2>

<p>Pelanggan warung bakso sangat menghargai kebersihan dan kemudahan saat meracik bumbu mangkok mereka. Terapkan tata kelola bumbu meja saji profesional berikut:</p>

<h3 id="formula-kuah">Formula Racikan Kuah Bakso Merah Merona Idaman Pelanggan</h3>

<ol class="space-y-2 text-sm text-gray-700 pl-4 list-decimal">
    <li><strong>Tuang Saos Sambal Cabe Tani Terlebih Dahulu:</strong> Berikan 1 hingga 2 sendok makan Saos Cabe Tani ke dalam mangkok kosong bersama sejumput lada bubuk dan irisan seledri.</li>
    <li><strong>Tambahkan Saos Raja Tomat & Kecap Manis:</strong> Masukkan 1 sendok makan Saos Raja Tomat dan 1 sendok makan <a href="/products/28" class="text-emerald-600 font-bold hover:underline">Kecap Manis Rajaku</a> untuk menghasilkan warna cokelat kemerahan yang pekat mengkilap.</li>
    <li><strong>Beri Tetesan Cuka Makan Rajaku:</strong> Tambahkan 2 tetes Cuka Makan Rajaku, lalu siram dengan kaldu sapi mendidih dari panci kuah bakso. Aduk perlahan hingga terbentuk emulsi kuah merah yang harum, gurih, pedas, dan segar.</li>
</ol>

<h3 id="manajemen-botol">Standar Kebersihan Botol Saos Meja Warung Bakso (Anti-Mampet & Anti-Basi)</h3>

<ul>
    <li><strong>Pilih Botol Pencet (Squeeze Bottle) Bertutup Rapat:</strong> Gunakan botol bertutup corong agar debu tidak masuk dan saos tidak mengering di ujung nozzle yang sering menyebabkan mampet.</li>
    <li><strong>Sistem Rotasi FIFO (First In, First Out):</strong> Selalu cuci bersih botol saos meja saji secara berkala sebelum melakukan isi ulang dari pasokan kemasan ball atau jerigen.</li>
    <li><strong>Simpan Stok Cadangan di Tempat Sejuk:</strong> Simpan stok kartonan Saos Cabe Tani dan Raja Tomat di tempat yang kering dan terhindar dari paparan terik sinar matahari langsung untuk menjaga stabilitas warna.</li>
</ul>

<h2 id="faq-section">Pertanyaan Seputar Supplier Saos Sambal dan Tomat Pedagang Bakso (FAQ)</h2>

<h3>Apakah Saos Cabe Tani dan Saos Raja Tomat sudah terdaftar di BPOM RI dan bersertifikat Halal?</h3>
<p>Seluruh varian produk saos sambal dan tomat yang diproduksi oleh PT Wowin Purnomo Putera telah memiliki izin edar resmi dari BPOM RI dan sertifikasi Halal MUI, sehingga 100% aman, higienis, dan terjamin kehalalannya untuk seluruh konsumen.</p>

<h3>Bagaimana cara pedagang bakso mendapatkan harga grosir langsung dari pabrik Wowin Food?</h3>
<p>Pedagang bakso dapat melakukan pemesanan partai grosir langsung melalui website resmi mywowin.com atau berkonsultasi dengan tim Customer Service resmi PT Wowin Purnomo Putera via WhatsApp untuk mendapatkan skema harga grosir pabrik dan opsi pengiriman ke seluruh kota di Indonesia.</p>

<h3>Apakah tersedia paket kombinasi saos, kecap manis, dan cuka makan untuk mitra pedagang kuliner?</h3>
<p>Ya, Wowin Food menyediakan beragam paket bundle hemat bumbu kuliner yang menggabungkan Saos Cabe Tani, Saos Raja Tomat, Cuka Makan Rajaku, serta varian Kecap Manis Wowin, Rajaku, dan Jangkar dalam satu pengiriman praktis.</p>

<h2 id="kesimpulan">Kesimpulan dan Panduan Order Grosir Pabrik Langsung</h2>

<p>Kemitraan yang solid dengan <strong>supplier saos sambal dan tomat</strong> yang terpercaya adalah pondasi kokoh untuk memenangkan persaingan usaha bakso. Keunggulan rasa autentik dari <a href="/products/42" class="text-emerald-600 font-bold hover:underline">Saos Cabe Tani</a>, kesegaran dari <a href="/products/37" class="text-emerald-600 font-bold hover:underline">Saos Raja Tomat</a>, serta legitimasi cita rasa dari <a href="/products/28" class="text-emerald-600 font-bold hover:underline">Kecap Manis Rajaku</a> siap membawa warung bakso Anda menjadi primadona kuliner di kota Anda.</p>

<p>Simak juga artikel panduan kami tentang <a href="/artikels/rekomendasi-kecap-manis-usaha-bakso-mie-ayam" class="text-emerald-600 font-bold hover:underline">rekomendasi kecap manis untuk usaha bakso & mie ayam</a> serta artikel mitra <a href="/artikels/supplier-kecap-manis-jerigen-murah-untuk-usaha-kuliner" class="text-emerald-600 font-bold hover:underline">supplier kecap manis jerigen murah untuk usaha kuliner</a>.</p>

<div class="p-6 my-8 rounded-2xl bg-gradient-to-r from-red-800 via-rose-900 to-amber-950 text-white shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">
    <div>
        <h3 class="text-lg font-bold text-yellow-400 mb-1">Dapatkan Pasokan Saos Sambal & Tomat Grosir Pabrik Sekarang!</h3>
        <p class="text-xs sm:text-sm text-rose-100 max-w-xl">Ambil kesempatan emas bermitra langsung dengan produsen saos dan kecap PT Wowin Purnomo Putera. Dapatkan harga khusus pedagang kuliner dan diskon volume order.</p>
    </div>
    <div class="shrink-0 flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Wowin,%20saya%20pedagang%20bakso%20ingin%20order%20grosir%20saos%20sambal%20dan%20tomat" 
           target="_blank" 
           class="px-5 py-3 rounded-full bg-yellow-400 hover:bg-yellow-300 text-red-950 font-bold text-xs sm:text-sm text-center shadow-lg transition transform hover:scale-105">
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
