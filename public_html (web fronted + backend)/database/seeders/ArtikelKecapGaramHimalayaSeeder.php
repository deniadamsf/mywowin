<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artikel;
use App\Models\User;
use Illuminate\Support\Str;

class ArtikelKecapGaramHimalayaSeeder extends Seeder
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

        $judul = 'Kecap Manis Garam Himalaya: Solusi Lezat Lebih Sehat';
        $slug = 'kecap-manis-garam-himalaya-solusi-lezat-lebih-sehat';

        $fotoArtikel = [
            'foto_artikel/cover-kecap-manis-garam-himalaya-solusi-lezat-lebih-sehat.jpg',
            'foto_artikel/kandungan-mineral-garam-himalaya-kecap-manis-wowin.jpg',
            'foto_artikel/kreasi-masakan-sehat-dengan-kecap-manis-wowin.jpg',
        ];

        $kontenBlok = [
            [
                'type' => 'text',
                'value' => '<p>Tren gaya hidup sehat yang kian berkembang mendorong banyak keluarga dan pelaku bisnis kuliner mencari kecap manis garam himalaya sebagai solusi lezat lebih sehat di meja makan sehari-hari. Kesadaran masyarakat modern terhadap kualitas bahan asupan makanan kini tidak lagi terbatas pada pemilihan sayuran organik atau pemangkasan lemak jenuh semata, melainkan merambah hingga ke bumbu dapur utama yang dikonsumsi setiap hari.</p>

<p>Kecap manis merupakan bumbu cair khas Nusantara yang hampir tidak pernah absen dari dapur keluarga Indonesia. Mulai dari menu tumisan sederhana, semur, ayam bakar madu, hingga aneka cocolan camilan, kecap manis selalu hadir menyempurnakan rasa. Namun, banyak produk kecap konvensional di pasaran yang menggunakan garam meja olahan (refined table salt) berkadar natrium tinggi serta pemanis sintetis berlebih, yang jika dikonsumsi berlebihan dalam jangka panjang dapat memicu kekhawatiran terhadap tekanan darah tinggi dan kesehatan ginjal.</p>

<p>Sebagai pionir inovasi bumbu dapur bermutu tinggi, PT Wowin Purnomo Putera mempersembahkan varian unggulan Kecap Manis Wowin yang diformulasikan secara khusus dengan kandungan garam pink Himalaya murni, gula kelapa alami, dan kacang kedelai pilihan. Produk ini telah resmi terdaftar di <a href="https://cekbpom.pom.go.id/" target="_blank" rel="nofollow noopener noreferrer" class="text-emerald-600 underline font-medium">BPOM RI</a> dan mengantongi sertifikasi <a href="https://halal.go.id/" target="_blank" rel="nofollow noopener noreferrer" class="text-emerald-600 underline font-medium">Halal Kemenag RI / MUI</a>, memberikan rasa aman dan ketenangan pikiran bagi seluruh anggota keluarga.</p>

<div class="p-5 my-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-950">
    <h3 class="text-base font-bold text-emerald-900 mb-2 flex items-center gap-2">
        <i class="fas fa-list-ul text-emerald-600"></i> Daftar Isi Panduan (Navigasi Cepat)
    </h3>
    <ul class="space-y-1.5 text-xs sm:text-sm">
        <li><a href="#mengapa-garam-himalaya" class="text-emerald-800 hover:text-emerald-950 underline font-medium">1. Mengapa Memilih Kecap Manis Garam Himalaya untuk Masakan Sehari-hari?</a></li>
        <li><a href="#keunggulan-kecap-wowin-himalaya" class="text-emerald-800 hover:text-emerald-950 underline font-medium">2. Keunggulan Formulasi Alami Kecap Manis Wowin dengan Garam Himalaya</a></li>
        <li><a href="#tabel-komparasi-garam" class="text-emerald-800 hover:text-emerald-950 underline font-medium">3. Tabel Komparasi Kecap Garam Himalaya vs Kecap Garam Konvensional</a></li>
        <li><a href="#manfaat-kesehatan-himalaya" class="text-emerald-800 hover:text-emerald-950 underline font-medium">4. Manfaat Nyata Kecap Manis Garam Himalaya bagi Kesehatan Tubuh</a></li>
        <li><a href="#kreasi-resep-masakan-sehat" class="text-emerald-800 hover:text-emerald-950 underline font-medium">5. Kreasi Resep Masakan Sehat Lezat Berbasis Kecap Manis Wowin</a></li>
        <li><a href="#faq-garam-himalaya" class="text-emerald-800 hover:text-emerald-950 underline font-medium">6. Pertanyaan Populer Seputar Kecap Manis Garam Himalaya (FAQ)</a></li>
        <li><a href="#kesimpulan-dan-cara-beli" class="text-emerald-800 hover:text-emerald-950 underline font-medium">7. Kesimpulan & Panduan Mendapatkan Produk Resmi Kecap Manis Wowin</a></li>
    </ul>
</div>

<h2 id="mengapa-garam-himalaya">Mengapa Memilih Kecap Manis Garam Himalaya untuk Masakan Sehari-hari?</h2>

<p>Garam Himalaya murni dipanen dari tambang kristal garam purba di kaki pegunungan Himalaya yang telah terbentuk selama jutaan tahun silam. Berbeda dengan garam dapur biasa yang melewati proses pemutihan kimiawi (*bleaching*) dan pemanasan ekstrem yang melucuti sebagian besar kandungan mineral alaminya, kristal garam pink Himalaya mempertahankan keaslian alaminya secara utuh.</p>

<p>Karakteristik warna merah muda yang menawan pada garam ini berasal dari konsentrasi puluhan mineral mikro esensial alami, terutama zat besi, magnesium, kalium, dan kalsium. Ketika diintegrasikan ke dalam proses pembuatan kecap manis berkualitas, garam Himalaya memberikan sensasi rasa asin yang lembut (*mellow saltiness*), bersih, dan tidak meninggalkan rasa pahit atau getir di lidah.</p>

<p>Penggunaan bumbu berbahan baku garam Himalaya membantu menciptakan hidangan rumahan dengan profil rasa yang lebih bulat dan elegan. Bagi keluarga yang sedang menjalani diet seimbang atau mengontrol asupan natrium tanpa mau mengorbankan kelezatan masakan, kehadiran produk ini menjadi jawaban yang sangat dinantikan.</p>

<h2 id="keunggulan-kecap-wowin-himalaya">Keunggulan Formulasi Alami Kecap Manis Wowin dengan Garam Himalaya</h2>

<p>Kecap Manis Wowin memadukan kearifan tradisi pembuatan kecap Nusantara dengan standar kebersihan teknologi pengolahan pangan modern. Ada tiga pilar keunggulan yang menjadikan produk ini pilihan utama keluarga sadar kesehatan:</p>

<h3 id="mineral-mikro-himalaya">1. Kandungan 84 Mineral Mikro Alami Tanpa Bahan Kimia Pemutih</h3>

<p>Garam pink Himalaya yang digunakan dalam Kecap Manis Wowin mengandung 84 trace minerals yang bermanfaat mendukung keseimbangan elektrolit tubuh dan menjaga hidrasi selular yang optimal. Ketiadaan zat aditif anti-gumpal sintetis memastikan bahwa setiap tetes kecap yang Anda sajikan benar-benar murni dan aman bagi pencernaan anak-anak maupun orang tua.</p>

<h3 id="kedelai-gula-kelapa-murni">2. Fermentasi Kacang Kedelai Pilihan & Gula Kelapa Murni</h3>

<p>Fondasi rasa legit Kecap Wowin bersumber dari rebusan nira kelapa murni dan kedelai berkualitas yang difermentasi secara alami. Gula kelapa murni dikenal memiliki indeks glikemik yang lebih bersahabat dibandingkan gula pasir tebu rafinasi, sehingga tidak menyebabkan lonjakan gula darah secara drastis setelah bersantap.</p>

<h3 id="tekstur-kental-pekat">3. Tekstur Kental Alami dengan Kilau Warna Hitam Menggoda</h3>

<p>Kekentalan Kecap Manis Wowin didapatkan dari reduksi karamelisasi gula kelapa alami, bukan dari penambahan zat pengental pati kanji berlebihan. Hasilnya adalah cairan kecap yang pekat berkilau, melekat sempurna pada lauk pauk, dan mengeluarkan aroma harum karamel yang sangat khas saat terkena panas api masakan.</p>

<p>Pilihan kemasan botol kaca elegan tersedia dalam ukuran <a href="/products/52" class="text-emerald-600 font-bold hover:underline">Kecap Manis Wowin 380 Gram</a> dan kemasan praktis <a href="/products/51" class="text-emerald-600 font-bold hover:underline">Kecap Manis Wowin 192 Gram</a>, serta kemasan isi ulang hemat <a href="/products/11" class="text-emerald-600 font-bold hover:underline">Kecap Manis Wowin Pouch 830 Gram</a> untuk kebutuhan dapur harian.</p>

<h2 id="tabel-komparasi-garam">Tabel Komparasi Kecap Garam Himalaya vs Kecap Garam Konvensional</h2>

<p>Berikut adalah perbandingan mendalam antara formula kecap manis berbasis garam Himalaya dengan kecap dapur konvensional pada umumnya:</p>

<div class="overflow-x-auto my-6">
    <table class="w-full text-xs sm:text-sm text-left border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <thead class="bg-emerald-900 text-white font-semibold">
            <tr>
                <th class="py-3 px-4">Aspek Perbandingan</th>
                <th class="py-3 px-4">Kecap Manis Wowin (Garam Himalaya)</th>
                <th class="py-3 px-4">Kecap Manis Konvensional Biasa</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
            <tr class="hover:bg-emerald-50/50">
                <td class="py-3 px-4 font-semibold text-gray-800">Jenis Garam yang Digunakan</td>
                <td class="py-3 px-4 text-emerald-800 font-medium">Garam Kristal Pink Himalaya Murni</td>
                <td class="py-3 px-4 text-gray-600">Garam Meja Industri Hasil Rafinasi</td>
            </tr>
            <tr class="hover:bg-emerald-50/50">
                <td class="py-3 px-4 font-semibold text-gray-800">Kandungan Mineral Alami</td>
                <td class="py-3 px-4 text-emerald-800 font-medium">Kaya 84 Trace Minerals (Mg, Ca, K, Fe)</td>
                <td class="py-3 px-4 text-gray-600">Hampir murni Natrium Klorida (NaCl) terisolasi</td>
            </tr>
            <tr class="hover:bg-emerald-50/50">
                <td class="py-3 px-4 font-semibold text-gray-800">Bahan Baku Pemanis Utama</td>
                <td class="py-3 px-4 text-emerald-800 font-medium">100% Gula Kelapa Murni Alami</td>
                <td class="py-3 px-4 text-gray-600">Campuran Gula Pasir Tebu / Sirup Fruktosa</td>
            </tr>
            <tr class="hover:bg-emerald-50/50">
                <td class="py-3 px-4 font-semibold text-gray-800">Profil Rasa Asin Gurih</td>
                <td class="py-3 px-4 text-emerald-800 font-medium">Gurih lembut, bulat alami, tidak getir</td>
                <td class="py-3 px-4 text-gray-600">Asin tajam menusuk, terkadang menyengat di lidah</td>
            </tr>
            <tr class="hover:bg-emerald-50/50">
                <td class="py-3 px-4 font-semibold text-gray-800">Dampak pada Kesehatan Harian</td>
                <td class="py-3 px-4 text-emerald-800 font-medium">Mendukung metabolisme & ramah pembuluh darah</td>
                <td class="py-3 px-4 text-gray-600">Berisiko memicu retensi cairan jika berlebih</td>
            </tr>
            <tr class="hover:bg-emerald-50/50">
                <td class="py-3 px-4 font-semibold text-gray-800">Kemasan Khusus Konsumen</td>
                <td class="py-3 px-4 font-bold text-emerald-700">Botol 380g, Botol 192g, Pouch 830g</td>
                <td class="py-3 px-4 text-gray-600">Botol Kaca / Plastik Polos Retail</td>
            </tr>
        </tbody>
    </table>
</div>

<h2 id="manfaat-kesehatan-himalaya">Manfaat Nyata Kecap Manis Garam Himalaya bagi Kesehatan Tubuh</h2>

<p>Beralih menggunakan kecap manis garam himalaya membawa dampak positif yang nyata bagi kebugaran jangka panjang keluarga Anda:</p>

<ul>
    <li><strong>Mendukung Keseimbangan Elektrolit Tubuh:</strong> Kandungan mineral kalium dan natrium alami dalam rasio yang seimbang membantu menjaga keseimbangan cairan intraseluler dan mencegah dehidrasi.</li>
    <li><strong>Membantu Mengontrol Tekanan Darah:</strong> Berkat profil mineral yang lengkap, garam Himalaya tidak membebani dinding arteri pembuluh darah secara mendadak sebagaimana garam meja murni yang telah kehilangan mikronutrien alaminya.</li>
    <li><strong>Membantu Proses Detoksifikasi Alami:</strong> Mineral mikro berperan penting sebagai kofaktor enzim yang membantu fungsi organ hati dan ginjal dalam menyaring zat sisa metabolisme tubuh.</li>
    <li><strong>Meningkatkan Kualitas Penyerapan Nutrisi:</strong> Kedelai hasil fermentasi alami kaya akan asam amino esensial yang meningkatkan penyerapan vitamin dan mineral dari sayuran serta lauk yang Anda masak.</li>
</ul>

<h2 id="kreasi-resep-masakan-sehat">Kreasi Resep Masakan Sehat Lezat Berbasis Kecap Manis Wowin</h2>

<p>Menerapkan pola makan sehat tidak berarti masakan Anda harus hambar. Cobalah tiga kreasi resep praktis berikut menggunakan Kecap Manis Wowin:</p>

<h3 id="resep-ayam-madu-himalaya">1. Resep Ayam Panggang Madu Garam Himalaya</h3>

<p>Menu favorit anak-anak dan keluarga yang kaya protein dan antioksidan alami:</p>

<ol class="space-y-2 text-sm text-gray-700 pl-4 list-decimal">
    <li>Siapkan 500 gram dada ayam tanpa kulit atau paha ayam fillet, potong sesuai selera.</li>
    <li>Buat bumbu rendaman: campurkan 3 sendok makan <strong>Kecap Manis Wowin</strong>, 1 sendok makan madu murni, 2 siung bawang putih parut, 1 ruas jahe cincang, dan 1 sendok teh minyak wijen.</li>
    <li>Marinasi potongan ayam selama 30 menit di dalam kulkas agar bumbu meresap hingga ke dalam serat daging.</li>
    <li>Panggang ayam di atas wajan anti lengket atau oven pada suhu 180°C selama 20 menit hingga lapisan luar berwarna cokelat keemasan berkilau. Sajikan dengan taburan biji wijen sangrai.</li>
</ol>

<h3 id="resep-tumis-pelangi">2. Resep Tumis Sayur Pelangi Tahu Organik Bumbu Kecap</h3>

<p>Kombinasi serat, vitamin, dan protein nabati yang sangat cepat dimasak untuk makan malam sehat:</p>

<ul class="space-y-2 text-sm text-gray-700 pl-4 list-disc">
    <li>Tumis 3 siung bawang merah dan 2 siung bawang putih dengan sedikit minyak zaitun atau minyak kelapa hingga harum.</li>
    <li>Masukkan potongan wortel, brokoli hijau, jagung muda, dan jamur kancing. Beri sedikit air kaldu jamur.</li>
    <li>Tambahkan potongan tahu organik panggang, lalu tuangkan 2 sendok makan Kecap Manis Wowin dan sedikit lada hitam bubuk. Aduk cepat di atas api besar selama 2 menit agar sayuran tetap renyah dan berwarna cerah.</li>
</ul>

<h3 id="resep-sambal-cocol-sehat">3. Tips Menyajikan Sambal Cocol Sehat Rendah Natrium</h3>

<p>Untuk melengkapi tempe mendoan atau tahu kukus, buat sambal cocol segar dari irisan cabai rawit merah, bawang merah segar, perasan jeruk nipis, dan siraman Kecap Manis Wowin. Karakter rasa gurih garam Himalaya membuat Anda tidak perlu lagi menambahkan penyedap rasa buatan (MSG) ke dalam sambal.</p>

<h2 id="faq-garam-himalaya">Pertanyaan Populer Seputar Kecap Manis Garam Himalaya (FAQ)</h2>

<h3>Apakah Kecap Manis Wowin Garam Himalaya aman dikonsumsi oleh penderita hipertensi?</h3>
<p>Kecap Manis Wowin menggunakan garam pink Himalaya alami yang memiliki profil mineral lebih ramah bagi tubuh dibandingkan garam dapur rafinasi. Namun, bagi penderita hipertensi atau gangguan ginjal stadium tertentu, konsumsi tetap dianjurkan dalam takaran wajar sesuai anjuran dokter atau ahli gizi Anda.</p>

<h3>Di mana saya bisa membeli Kecap Manis Wowin kemasan botol Garam Himalaya?</h3>
<p>Produk resmi dapat dibeli langsung melalui toko resmi online My Wowin, agen distributor bahan pangan terdekat di kota Anda, atau melalui layanan pesanan WhatsApp customer care PT Wowin Purnomo Putera.</p>

<h3>Apakah Kecap Manis Wowin Garam Himalaya juga tersedia dalam kemasan jerigen untuk restoran sehat?</h3>
<p>Tentu saja. Bagi pengelola restoran vegan, katering sehat, rumah sakit, dan hotel yang mengusung menu *healthy food*, kami menyediakan kemasan komersial <a href="/products/16" class="text-emerald-600 font-bold hover:underline">Manis Wowin Jirigen 6,2 Kg</a> serta <a href="/products/17" class="text-emerald-600 font-bold hover:underline">Manis Wowin Jirigen Jumbo 26 Kg</a> dengan harga grosir langsung dari pabrik.</p>

<h2 id="kesimpulan-dan-cara-beli">Kesimpulan & Panduan Mendapatkan Produk Resmi Kecap Manis Wowin</h2>

<p>Menjadikan kecap manis garam himalaya sebagai bumbu andalan keluarga merupakan langkah bijak yang menyatukan kenikmatan rasa tradisional dengan komitmen hidup sehat. Dengan perpaduan garam pink Himalaya kaya mineral, fermentasi kedelai pilihan, dan legitnya gula kelapa alami, Kecap Manis Wowin membuktikan bahwa hidup sehat tidak harus mengorbankan kelezatan masakan.</p>

<p>Temukan juga inspirasi resep dan panduan bumbu dapur komersial kami lainnya pada artikel <a href="/artikels/pilihan-kecap-manis-kental-ekonomis-untuk-nasi-goreng" class="text-emerald-600 font-bold hover:underline">pilihan kecap manis kental ekonomis untuk nasi goreng</a> dan ulasan <a href="/artikels/rekomendasi-merk-kecap-manis-yang-bagus-untuk-sate" class="text-emerald-600 font-bold hover:underline">rekomendasi merk kecap manis yang bagus untuk sate</a>.</p>

<div class="p-6 my-8 rounded-2xl bg-gradient-to-r from-emerald-800 via-teal-900 to-slate-900 text-white shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">
    <div>
        <h3 class="text-lg font-bold text-yellow-400 mb-1">Hadirkan Kecap Manis Wowin Garam Himalaya di Dapur Anda!</h3>
        <p class="text-xs sm:text-sm text-emerald-100 max-w-xl">Dapatkan kemurnian rasa manis gurih alami dari PT Wowin Purnomo Putera. Tersedia kemasan botol kaca 380g, 192g, pouch isi ulang, dan jerigen pasokan resto.</p>
    </div>
    <div class="shrink-0 flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Wowin,%20saya%20tertarik%20dengan%20produk%20Kecap%20Manis%20Wowin%20Garam%20Himalaya" 
           target="_blank" 
           class="px-5 py-3 rounded-full bg-yellow-400 hover:bg-yellow-300 text-emerald-950 font-bold text-xs sm:text-sm text-center shadow-lg transition transform hover:scale-105">
            <i class="fab fa-whatsapp mr-1.5 text-base"></i> Pesan via WhatsApp Sekarang
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