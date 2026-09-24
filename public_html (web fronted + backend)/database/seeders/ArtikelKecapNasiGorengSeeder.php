<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artikel;
use App\Models\User;
use Illuminate\Support\Str;

class ArtikelKecapNasiGorengSeeder extends Seeder
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

        $judul = 'Pilihan Kecap Manis Kental Ekonomis untuk Nasi Goreng';
        $slug = 'pilihan-kecap-manis-kental-ekonomis-untuk-nasi-goreng';

        $fotoArtikel = [
            'foto_artikel/cover-pilihan-kecap-manis-kental-ekonomis-untuk-nasi-goreng.jpg',
            'foto_artikel/proses-memasak-nasi-goreng-wajan-wok-hei-kecap-manis.jpg',
            'foto_artikel/sajian-nasi-goreng-spesial-bumbu-kecap-manis-kental.jpg',
        ];

        $kontenBlok = [
            [
                'type' => 'text',
                'value' => '<p>Menemukan <strong>pilihan kecap manis kental ekonomis untuk nasi goreng</strong> merupakan kunci rahasia paling fundamental bagi para pedagang nasi goreng gerobak, pemilik warung tenda malam, pengelola depot *chinese food*, hingga restoran keluarga di seluruh pelosok Indonesia. Dalam seni memasak nasi goreng Nusantara, kecap manis bukan sekadar pemberi warna cokelat, melainkan jiwa yang menyatukan bumbu aromatik, menghasilkan aroma bakaran wajan (*smoky wok hei*), dan memberi lapisan rasa manis gurih yang meresap ke dalam setiap butir nasi tanpa membuatnya lembek atau menggumpal.</p>

<p>Kendala klasik yang kerap menguras modal pedagang adalah kecap retail yang terlalu encer sehingga membutuhkan takaran berlebihan untuk mendapatkan warna cokelat yang diinginkan, atau sebaliknya, kecap dengan kandungan sirup gula tinggi yang sangat mudah hangus menjadi kerak pahit saat wajan baja dipanaskan pada temperatur tinggi. Selain itu, lonjakan biaya bahan baku menuntut para pengusaha kuliner untuk lebih cermat menghitung Harga Pokok Penjualan (HPP) per porsi agar usaha tetap mencetak profit maksimal.</p>

<p>Menjawab kebutuhan efisiensi dan kelezatan tersebut, <strong>PT Wowin Purnomo Putera</strong> menghadirkan rangkaian kecap manis bermutu tinggi dengan viskositas kental alami dan daya tahan panas luar biasa, yang telah teruji secara klinis memiliki izin edar resmi <a href="https://cekbpom.pom.go.id/" target="_blank" rel="nofollow noopener noreferrer" class="text-emerald-600 underline font-medium">BPOM RI</a> serta sertifikat <a href="https://halal.go.id/" target="_blank" rel="nofollow noopener noreferrer" class="text-emerald-600 underline font-medium">Halal Kemenag RI / MUI</a>.</p>

<div class="p-5 my-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-950">
    <h3 class="text-base font-bold text-emerald-900 mb-2 flex items-center gap-2">
        <i class="fas fa-list-ul text-emerald-600"></i> Daftar Isi Panduan (Navigasi Cepat)
    </h3>
    <ul class="space-y-1.5 text-xs sm:text-sm">
        <li><a href="#kriteria-kecap-nasgor" class="text-emerald-800 hover:text-emerald-950 underline font-medium">1. Kriteria Penting Memilih Kecap Manis Kental Ekonomis untuk Nasi Goreng</a></li>
        <li><a href="#tiga-pilihan-kecap-nasgor" class="text-emerald-800 hover:text-emerald-950 underline font-medium">2. 3 Pilihan Merk Kecap Manis Kental Ekonomis untuk Nasi Goreng dari Wowin Food</a></li>
        <li><a href="#tabel-komparasi-nasgor" class="text-emerald-800 hover:text-emerald-950 underline font-medium">3. Tabel Komparasi Karakteristik Varian Kecap Manis Nasi Goreng</a></li>
        <li><a href="#teknik-wok-hei-nasgor" class="text-emerald-800 hover:text-emerald-950 underline font-medium">4. Teknik Rahasia Menumis Nasi Goreng Wok Hei Tidak Menggumpal</a></li>
        <li><a href="#simulasi-hpp-nasgor" class="text-emerald-800 hover:text-emerald-950 underline font-medium">5. Simulasi Analisis HPP Penggunaan Bumbu Kecap per 100 Porsi Nasi Goreng</a></li>
        <li><a href="#faq-nasgor" class="text-emerald-800 hover:text-emerald-950 underline font-medium">6. Pertanyaan Populer Seputar Kecap Manis Nasi Goreng (FAQ)</a></li>
        <li><a href="#kesimpulan-nasgor" class="text-emerald-800 hover:text-emerald-950 underline font-medium">7. Kesimpulan & Cara Order Pasokan Grosir Pabrik Langsung</a></li>
    </ul>
</div>

<h2 id="kriteria-kecap-nasgor">Kriteria Penting Memilih Kecap Manis Kental Ekonomis untuk Nasi Goreng</h2>

<p>Koki restoran komersial dan pedagang nasi goreng sukses selalu memegang empat standar baku saat menentukan pasokan kecap manis dapur mereka:</p>

<ul>
    <li><strong>Ketahanan Panas Tinggi (High Wok-Heat Tolerance):</strong> Memasak nasi goreng legendaris membutuhkan nyala api kompor gas bertekanan tinggi (*high pressure burner*). Kecap berkualitas tidak boleh cepat gosong menjadi abu hitam pahit, melainkan terkaramelisasi perlahan membentuk aroma wangi asap yang menggugah selera.</li>
    <li><strong>Viskositas Kental Alami & Daya Sebar Cepat:</strong> Tekstur kental yang seimbang memungkinkan kecap menyelimuti setiap butir beras (*grain by grain*) secara merata dalam hitungan detik pengadukan tanpa membuat nasi menjadi basah benyek atau saling menempel.</li>
    <li><strong>Kadar Gula Aren Kelapa Murni & Ekstrak Kedelai Seimbang:</strong> Paduan gula kelapa murni dan kedelai pilihan menghasilkan warna cokelat gelap keemasan (*golden-brown glaze*) yang cantik dan rasa gurih manis mendalam (*savory-sweet umami*) yang tidak membuat tenggorokan gatal.</li>
    <li><strong>Efisiensi Kemasan Komersial Jerigen:</strong> Menggunakan kemasan jerigen ukuran 6,2 kg atau 26 kg memangkas biaya operasional secara drastis dibandingkan membeli kemasan botol kaca kecil atau pouch retail harian.</li>
</ul>

<h2 id="tiga-pilihan-kecap-nasgor">3 Pilihan Merk Kecap Manis Kental Ekonomis untuk Nasi Goreng dari Wowin Food</h2>

<p>PT Wowin Purnomo Putera menyediakan tiga varian <strong>pilihan kecap manis kental ekonomis untuk nasi goreng</strong> yang dirancang secara spesifik sesuai segmen pasar kuliner Anda:</p>

<h3 id="kecap-jangkar-nasgor">1. Kecap Manis Jangkar: Legenda Pedagang Nasi Goreng Juara Tahan Api Wok Hei</h3>

<p>Bagi pedagang nasi goreng kaki lima, gerobak keliling, warung tenda pinggir jalan, dan warung makan serba seribu, <strong>Kecap Manis Jangkar</strong> dengan slogan legendaris <em>"Pasti Enaakk"</em> adalah senjata rahasia paling diburu di pasar.</p>

<p>Keunggulan utama Kecap Jangkar terletak pada daya tahannya yang luar biasa terhadap jilatan api wajan baja panas. Ketika kecap dituangkan melingkar di bibir wajan yang membara, kecap langsung mendesis dan menguapkan aroma karamel sangai harum tanpa meninggalkan noda kerak gosong pahit. Warnanya yang cokelat tua berkilau membuat tampilan nasi goreng tampak sangat menggoda hanya dengan takaran 1 hingga 1,5 sendok makan per porsi.</p>

<p>Varian ini menjadi juara efisiensi HPP terfavorit melalui kemasan <a href="/products/45" class="text-emerald-600 font-bold hover:underline">KM Jangkar Jirigen 6.200 ml (6,2 Kg)</a> dan drum pasokan pabrik <a href="/products/46" class="text-emerald-600 font-bold hover:underline">KM Jangkar Jirigen Jumbo 26 Kg</a>.</p>

<h3 id="kecap-rajaku-nasgor">2. Kecap Manis Rajaku: Gurih Kedelai Mantap untuk Nasi Goreng Restoran & Hotel</h3>

<p>Untuk restoran keluarga, kafe modern, depot kuliner oriental, dan hotel yang menyajikan menu nasi goreng seafood, nasi goreng oriental, dan mie goreng spesial, <strong>Kecap Manis Rajaku Premium Gold</strong> adalah pilihan ideal.</p>

<p>Mengandung persentase sari kedelai tinggi hingga <strong>22%</strong>, Rajaku menghadirkan lapisan rasa gurih umami yang sangat kaya. Kecap ini tidak dominan manis pekat semata, melainkan memiliki kedalaman rasa gurih kedelai yang menyatu sempurna dengan aroma bawang putih, minyak wijen, dan saus tiram.</p>

<p>Teksturnya yang kental lembut sangat mudah larut dan terserap ke dalam nasi pera, menjadikannya andalan koki profesional melalui kemasan <a href="/products/28" class="text-emerald-600 font-bold hover:underline">Rajaku Premium Gold Jerigen 6,2 Kg</a> serta kemasan botol praktis untuk meja makan pengunjung.</p>

<h3 id="kecap-wowin-nasgor">3. Kecap Manis Wowin: Karamelisasi Hitam Pekat untuk Nasi Goreng Spesial Babat & Kambing</h3>

<p>Bagi pengusaha kuliner yang mengusung menu *signature* premium seperti nasi goreng kambing kebon sirih, nasi goreng babat gongso khas Semarang, nasi goreng buntut, atau nasi goreng mawut istimewa, <strong>Kecap Manis Wowin</strong> (*Grade Premium*) adalah standar tertinggi cita rasa.</p>

<p>Dibuat dari 100% gula kelapa murni tanpa campuran pemanis sintetis, Kecap Wowin memiliki tingkat kekentalan dan kepekatan warna hitam paling pekat di kelasnya. Aroma manis legit alami kelapanya mampu menundukkan aroma tajam daging kambing dan jeroan sapi, menghasilkan sajian nasi goreng dengan kilau visual mewah (*glossy finish*).</p>

<p>Tersedia dalam kemasan ekonomis <a href="/products/16" class="text-emerald-600 font-bold hover:underline">Manis Wowin Jirigen 6.200 ml (6,2 Kg)</a> serta jerigen industri <a href="/products/17" class="text-emerald-600 font-bold hover:underline">Manis Wowin Jirigen Jumbo 26 Kg</a>.</p>

<h2 id="tabel-komparasi-nasgor">Tabel Komparasi Karakteristik Varian Kecap Manis Nasi Goreng</h2>

<p>Simak perbandingan detail karakteristik teknis ketiga produk kecap berikut agar Anda dapat menentukan pilihan yang paling pas untuk karakter wajan usaha Anda:</p>

<div class="overflow-x-auto my-6">
    <table class="w-full text-xs sm:text-sm text-left border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <thead class="bg-emerald-900 text-white font-semibold">
            <tr>
                <th class="py-3 px-4">Parameter Kualitas</th>
                <th class="py-3 px-4">Kecap Manis Jangkar</th>
                <th class="py-3 px-4">Kecap Manis Rajaku</th>
                <th class="py-3 px-4">Kecap Manis Wowin</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
            <tr class="hover:bg-emerald-50/50">
                <td class="py-3 px-4 font-semibold text-gray-800">Segmentasi Utama</td>
                <td class="py-3 px-4 text-emerald-800 font-bold">Juara Hemat HPP Pedagang</td>
                <td class="py-3 px-4 text-blue-800 font-bold">Resto, Kafe, & Depot Kuliner</td>
                <td class="py-3 px-4 text-purple-800 font-bold">Menu Premium Signature & Hotel</td>
            </tr>
            <tr class="hover:bg-emerald-50/50">
                <td class="py-3 px-4 font-semibold text-gray-800">Tingkat Viskositas</td>
                <td class="py-3 px-4">Kental Pas Cepat Merata</td>
                <td class="py-3 px-4">Kental Lembut & Gurih</td>
                <td class="py-3 px-4">Ekstra Kental Hitam Pekat</td>
            </tr>
            <tr class="hover:bg-emerald-50/50">
                <td class="py-3 px-4 font-semibold text-gray-800">Karakter Aroma Api Wajan</td>
                <td class="py-3 px-4">Aroma Wok Hei Kuat & Smoky</td>
                <td class="py-3 px-4">Harum Sedap Gurih Kedelai</td>
                <td class="py-3 px-4">Wangi Karamel Gula Kelapa Legit</td>
            </tr>
            <tr class="hover:bg-emerald-50/50">
                <td class="py-3 px-4 font-semibold text-gray-800">Warna pada Butiran Nasi</td>
                <td class="py-3 px-4">Cokelat Keemasan Merata</td>
                <td class="py-3 px-4">Cokelat Terang Mengkilap</td>
                <td class="py-3 px-4">Cokelat Gelap Eksklusif</td>
            </tr>
            <tr class="hover:bg-emerald-50/50">
                <td class="py-3 px-4 font-semibold text-gray-800">Rekomendasi Menu Nasi Goreng</td>
                <td class="py-3 px-4">Nasgor Jawa, Nasgor Mawut, Mie Goreng</td>
                <td class="py-3 px-4">Nasgor Seafood, Nasgor Hongkong, Kwetiau</td>
                <td class="py-3 px-4">Nasgor Babat, Nasgor Kambing, Nasgor Sapi</td>
            </tr>
            <tr class="hover:bg-emerald-50/50">
                <td class="py-3 px-4 font-semibold text-gray-800">Format Kemasan Paling Irit</td>
                <td class="py-3 px-4 font-bold text-emerald-700">Jerigen 6,2 Kg & 26 Kg</td>
                <td class="py-3 px-4 font-bold text-emerald-700">Jerigen 6,2 Kg & Botol</td>
                <td class="py-3 px-4 font-bold text-emerald-700">Jerigen 6,2 Kg & 26 Kg</td>
            </tr>
        </tbody>
    </table>
</div>

<h2 id="teknik-wok-hei-nasgor">Teknik Rahasia Menumis Nasi Goreng Wok Hei Tidak Menggumpal</h2>

<p>Kecap manis terbaik akan bekerja maksimal apabila diiringi dengan teknik pengolahan wajan yang tepat. Ikuti tiga prosedur operasional standar (SOP) dari koki berpengalaman berikut:</p>

<h3 id="sop-nasi-pera">1. SOP Persiapan Nasi Pera Dingin Dapur Komersial</h3>

<p>Kunci utama nasi goreng yang tidak lembek adalah menggunakan beras pera dengan kadar amilosa tinggi (seperti beras IR 64 pera atau beras setra ramos). Setelah matang di penanak nasi (*rice cooker*), angin-anginkan nasi di atas nampan lebar dan simpan di ruangan sejuk ber-AC atau chiller semalaman. Butiran nasi yang dingin dan kering akan menyerap kecap manis dengan sempurna tanpa mengeluarkan pati lengket.</p>

<h3 id="timing-tuang-kecap">2. Timing Emas Penuangan Kecap Manis di Pinggiran Wajan Panas</h3>

<p>Jangan pernah menuangkan kecap manis langsung ke atas gundukan nasi dingin di tengah wajan! Cara profesional:</p>

<ul class="space-y-2 text-sm text-gray-700 pl-4 list-disc">
    <li>Besarkan nyala api kompor hingga wajan baja mengeluarkan kepulan asap tipis.</li>
    <li>Sisihkan nasi ke satu sisi wajan, lalu tuangkan <strong>Kecap Manis Jangkar</strong> melingkar di dinding wajan panas (*wok seasoning ring*).</li>
    <li>Biarkan kecap mendesis dan berbusa karamel selama 1–2 detik, lalu segera aduk cepat beras ke arah cairan karamel tersebut. Teknik ini menghasilkan aroma bakaran asap (*wok hei*) yang harum semerbak ke seluruh area warung.</li>
</ul>

<h3 id="racikan-baceman">3. Formula Racikan Bumbu Dasar Baceman Bawang & Kecap</h3>

<p>Untuk mempercepat pelayanan saat antrean pembeli membludak, siapkan baceman bawang putih dan kemiri yang difermentasi minyak kelapa selama minimal 24 jam. Saat menumis, padukan 1 sendok makan baceman bawang dengan kecap manis kental dan sedikit kecap asin atau saus tiram untuk menghasilkan ledakan cita rasa gurih yang konsisten di setiap piring.</p>

<h2 id="simulasi-hpp-nasgor">Simulasi Analisis HPP Penggunaan Bumbu Kecap per 100 Porsi Nasi Goreng</h2>

<p>Mari kita hitung secara riil seberapa besar penghematan biaya bahan baku Anda per 100 porsi piring nasi goreng:</p>

<div class="overflow-x-auto my-6">
    <table class="w-full text-xs sm:text-sm text-left border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <thead class="bg-gray-100 text-gray-800 font-semibold">
            <tr>
                <th class="py-3 px-4">Komponen Penggunaan</th>
                <th class="py-3 px-4">Kecap Botol Retail Biasa (Encer)</th>
                <th class="py-3 px-4">Kecap Manis Jerigen 6,2 Kg (Wowin Food)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
            <tr>
                <td class="py-3 px-4 font-medium text-gray-700">Takaran Kecap per Porsi Nasi Goreng</td>
                <td class="py-3 px-4 text-red-600">25 ml (butuh banyak karena encer)</td>
                <td class="py-3 px-4 text-emerald-600 font-semibold">15 ml (pekat & hemat)</td>
            </tr>
            <tr>
                <td class="py-3 px-4 font-medium text-gray-700">Total Kebutuhan per 100 Porsi</td>
                <td class="py-3 px-4">2.500 ml (2,5 Liter)</td>
                <td class="py-3 px-4 font-semibold text-emerald-700">1.500 ml (1,5 Liter)</td>
            </tr>
            <tr>
                <td class="py-3 px-4 font-medium text-gray-700">Biaya Kecap per 100 Porsi</td>
                <td class="py-3 px-4 text-red-600 font-bold">Rp 87.500 - Rp 100.000</td>
                <td class="py-3 px-4 text-emerald-600 font-bold">Rp 37.500 - Rp 45.000</td>
            </tr>
            <tr class="bg-emerald-50">
                <td class="py-3 px-4 font-bold text-emerald-950">Potensi Hemat per Bulan (3.000 Porsi)</td>
                <td class="py-3 px-4 text-gray-500">Biaya Bahan Baku Boros</td>
                <td class="py-3 px-4 font-black text-emerald-700 text-sm sm:text-base">Hemat Rp 1.500.000 - Rp 1.650.000 / Bulan!</td>
            </tr>
        </tbody>
    </table>
</div>

<p>Efisiensi lebih dari <strong>Rp 1,5 juta setiap bulannya</strong> hanya dari efisiensi pemakaian kecap manis memberikan kelonggaran finansial yang luar biasa untuk mengembangkan omzet usaha kuliner Anda.</p>

<h2 id="faq-nasgor">Pertanyaan Populer Seputar Kecap Manis Nasi Goreng (FAQ)</h2>

<h3>Mengapa nasi goreng sering menjadi lembek dan basah setelah diberi kecap manis?</h3>
<p>Kondisi nasi lembek terjadi jika kecap manis yang digunakan memiliki kadar air terlalu tinggi (encer), beras yang dimasak terlalu pulen, atau kecap dituangkan terlalu banyak ke atas nasi dingin. Gunakan kecap kental pekat seperti Wowin atau Jangkar dengan takaran efisien agar warna cokelat tercapai tanpa menambah kadar air pada nasi.</p>

<h3>Berapa lama masa simpan kecap manis jerigen setelah segel dibuka di dapur warung nasi goreng?</h3>
<p>Kecap manis produksi PT Wowin Purnomo Putera memiliki daya tahan 12 hingga 24 bulan pada suhu ruang normal berkat proses pemanasan higienis berstandar BPOM RI. Pastikan tutup jerigen selalu dirapatkan kembali setelah digunakan untuk mencegah masuknya uap air dan debu dapur.</p>

<h3>Bagaimana cara memesan kemasan jerigen partai besar untuk suplai rutin cabang warung nasi goreng?</h3>
<p>Anda dapat langsung menghubungi layanan WhatsApp distributor resmi kami untuk mendapatkan skema harga grosir pabrik, pengiriman gratis untuk area tertentu, dan kemudahan jadwal suplai rutin mingguan.</p>

<h2 id="kesimpulan-nasgor">Kesimpulan & Cara Order Pasokan Grosir Pabrik Langsung</h2>

<p>Memilih <strong>pilihan kecap manis kental ekonomis untuk nasi goreng</strong> yang tepat terbukti melipatgandakan kepuasan pelanggan sekaligus melindungi margin keuntungan warung Anda. Ketangguhan api wajan dari <a href="/products/45" class="text-emerald-600 font-bold hover:underline">Kecap Manis Jangkar Jerigen</a>, kemewahan gurih kedelai dari <a href="/products/28" class="text-emerald-600 font-bold hover:underline">Kecap Manis Rajaku Jerigen</a>, serta kepekatan karamel dari <a href="/products/16" class="text-emerald-600 font-bold hover:underline">Kecap Manis Wowin Jerigen</a> siap menjadi mitra setia kesuksesan wajan nasi goreng Anda.</p>

<p>Lengkapi wawasan kuliner Anda dengan artikel terpopuler lainnya: panduan <a href="/artikels/rekomendasi-merk-kecap-manis-yang-bagus-untuk-sate" class="text-emerald-600 font-bold hover:underline">rekomendasi merk kecap manis yang bagus untuk sate</a> serta ulasan <a href="/artikels/kecap-manis-jerigen-6-kg-murah-katering-resto" class="text-emerald-600 font-bold hover:underline">kecap manis jerigen 6 kg murah untuk katering & resto</a>.</p>

<div class="p-6 my-8 rounded-2xl bg-gradient-to-r from-emerald-800 via-teal-900 to-slate-900 text-white shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">
    <div>
        <h3 class="text-lg font-bold text-yellow-400 mb-1">Dapatkan Harga Grosir Pabrik Kecap Nasi Goreng Sekarang!</h3>
        <p class="text-xs sm:text-sm text-emerald-100 max-w-xl">Bermitra langsung dengan PT Wowin Purnomo Putera. Nikmati harga pabrik termurah kemasan jerigen 6,2 Kg dan 26 Kg dengan garansi mutu BPOM dan Halal.</p>
    </div>
    <div class="shrink-0 flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Wowin,%20saya%20pedagang/pengusaha%20nasi%20goreng%20tertarik%20dengan%20pasokan%20kecap%20manis%20jerigen%20kental%20ekonomis" 
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