/// Data Master Wilayah Administratif Republik Indonesia
/// Digunakan untuk pemilihan alamat berjenjang (Cascading Address Picker)
/// guna memastikan 100% akurasi zonasi tarif J&T Express VIP Jawara.
library;

class IndonesiaRegions {
  /// Daftar seluruh 38 Provinsi di Indonesia
  /// Prioritas Pulau Jawa & Madura diletakkan di bagian paling atas untuk kemudahan akses mayoritas pengguna Wowin.
  static const List<String> provinces = [
    'Jawa Timur',
    'Jawa Tengah',
    'D.I. Yogyakarta',
    'Jawa Barat',
    'DKI Jakarta',
    'Banten',
    'Bali',
    'Nusa Tenggara Barat',
    'Nusa Tenggara Timur',
    'Sumatera Utara',
    'Sumatera Barat',
    'Riau',
    'Kepulauan Riau',
    'Jambi',
    'Sumatera Selatan',
    'Bengkulu',
    'Lampung',
    'Kepulauan Bangka Belitung',
    'Aceh',
    'Kalimantan Barat',
    'Kalimantan Tengah',
    'Kalimantan Selatan',
    'Kalimantan Timur',
    'Kalimantan Utara',
    'Sulawesi Utara',
    'Sulawesi Tengah',
    'Sulawesi Selatan',
    'Sulawesi Tenggara',
    'Gorontalo',
    'Sulawesi Barat',
    'Maluku',
    'Maluku Utara',
    'Papua',
    'Papua Barat',
    'Papua Selatan',
    'Papua Tengah',
    'Papua Pegunungan',
    'Papua Barat Daya',
  ];

  /// Pemetaan Provinsi ke Daftar Kabupaten dan Kota Lengkap
  static const Map<String, List<String>> citiesByProvince = {
    'Jawa Timur': [
      'Kab. Bangkalan',
      'Kab. Banyuwangi',
      'Kab. Blitar',
      'Kab. Bojonegoro',
      'Kab. Bondowoso',
      'Kab. Gresik',
      'Kab. Jember',
      'Kab. Jombang',
      'Kab. Kediri',
      'Kab. Lamongan',
      'Kab. Lumajang',
      'Kab. Madiun',
      'Kab. Magetan',
      'Kab. Malang',
      'Kab. Mojokerto',
      'Kab. Nganjuk',
      'Kab. Ngawi',
      'Kab. Pacitan',
      'Kab. Pamekasan',
      'Kab. Pasuruan',
      'Kab. Ponorogo',
      'Kab. Probolinggo',
      'Kab. Sampang',
      'Kab. Sidoarjo',
      'Kab. Situbondo',
      'Kab. Sumenep',
      'Kab. Trenggalek',
      'Kab. Tuban',
      'Kab. Tulungagung',
      'Kota Batu',
      'Kota Blitar',
      'Kota Kediri',
      'Kota Madiun',
      'Kota Malang',
      'Kota Mojokerto',
      'Kota Pasuruan',
      'Kota Probolinggo',
      'Kota Surabaya',
    ],
    'Jawa Tengah': [
      'Kab. Banjarnegara',
      'Kab. Banyumas',
      'Kab. Batang',
      'Kab. Blora',
      'Kab. Boyolali',
      'Kab. Brebes',
      'Kab. Cilacap',
      'Kab. Demak',
      'Kab. Grobogan',
      'Kab. Jepara',
      'Kab. Karanganyar',
      'Kab. Kebumen',
      'Kab. Kendal',
      'Kab. Klaten',
      'Kab. Kudus',
      'Kab. Magelang',
      'Kab. Pati',
      'Kab. Pekalongan',
      'Kab. Pemalang',
      'Kab. Purbalingga',
      'Kab. Purworejo',
      'Kab. Rembang',
      'Kab. Semarang',
      'Kab. Sragen',
      'Kab. Sukoharjo',
      'Kab. Tegal',
      'Kab. Temanggung',
      'Kab. Wonogiri',
      'Kab. Wonosobo',
      'Kota Magelang',
      'Kota Pekalongan',
      'Kota Salatiga',
      'Kota Semarang',
      'Kota Surakarta (Solo)',
      'Kota Tegal',
    ],
    'D.I. Yogyakarta': [
      'Kab. Bantul',
      'Kab. Gunungkidul',
      'Kab. Kulon Progo',
      'Kab. Sleman',
      'Kota Yogyakarta',
    ],
    'Jawa Barat': [
      'Kab. Bandung',
      'Kab. Bandung Barat',
      'Kab. Bekasi',
      'Kab. Bogor',
      'Kab. Ciamis',
      'Kab. Cianjur',
      'Kab. Cirebon',
      'Kab. Garut',
      'Kab. Indramayu',
      'Kab. Karawang',
      'Kab. Kuningan',
      'Kab. Majalengka',
      'Kab. Pangandaran',
      'Kab. Purwakarta',
      'Kab. Subang',
      'Kab. Sukabumi',
      'Kab. Sumedang',
      'Kab. Tasikmalaya',
      'Kota Bandung',
      'Kota Banjar',
      'Kota Bekasi',
      'Kota Bogor',
      'Kota Cimahi',
      'Kota Cirebon',
      'Kota Depok',
      'Kota Sukabumi',
      'Kota Tasikmalaya',
    ],
    'DKI Jakarta': [
      'Kab. Kepulauan Seribu',
      'Kota Jakarta Barat',
      'Kota Jakarta Pusat',
      'Kota Jakarta Selatan',
      'Kota Jakarta Timur',
      'Kota Jakarta Utara',
    ],
    'Banten': [
      'Kab. Lebak',
      'Kab. Pandeglang',
      'Kab. Serang',
      'Kab. Tangerang',
      'Kota Cilegon',
      'Kota Serang',
      'Kota Tangerang',
      'Kota Tangerang Selatan',
    ],
    'Bali': [
      'Kab. Badung',
      'Kab. Bangli',
      'Kab. Buleleng',
      'Kab. Gianyar',
      'Kab. Jembrana',
      'Kab. Karangasem',
      'Kab. Klungkung',
      'Kab. Tabanan',
      'Kota Denpasar',
    ],
    'Nusa Tenggara Barat': [
      'Kab. Bima',
      'Kab. Dompu',
      'Kab. Lombok Barat',
      'Kab. Lombok Tengah',
      'Kab. Lombok Timur',
      'Kab. Lombok Utara',
      'Kab. Sumbawa',
      'Kab. Sumbawa Barat',
      'Kota Bima',
      'Kota Mataram',
    ],
    'Nusa Tenggara Timur': [
      'Kab. Alor',
      'Kab. Belu',
      'Kab. Ende',
      'Kab. Flores Timur',
      'Kab. Kupang',
      'Kab. Lembata',
      'Kab. Manggarai',
      'Kab. Manggarai Barat',
      'Kab. Manggarai Timur',
      'Kab. Nagekeo',
      'Kab. Ngada',
      'Kab. Rote Ndao',
      'Kab. Sabu Raijua',
      'Kab. Sikka',
      'Kab. Sumba Barat',
      'Kab. Sumba Barat Daya',
      'Kab. Sumba Tengah',
      'Kab. Sumba Timur',
      'Kab. Timor Tengah Selatan',
      'Kab. Timor Tengah Utara',
      'Kota Kupang',
    ],
    'Sumatera Utara': [
      'Kab. Asahan',
      'Kab. Dairi',
      'Kab. Deli Serdang',
      'Kab. Karo',
      'Kab. Labuhanbatu',
      'Kab. Langkat',
      'Kab. Mandailing Natal',
      'Kab. Nias',
      'Kab. Simalungun',
      'Kab. Tapanuli Selatan',
      'Kab. Tapanuli Tengah',
      'Kab. Tapanuli Utara',
      'Kab. Toba',
      'Kota Binjai',
      'Kota Medan',
      'Kota Padangsidimpuan',
      'Kota Pematangsiantar',
      'Kota Sibolga',
      'Kota Tanjungbalai',
      'Kota Tebing Tinggi',
    ],
    'Sumatera Barat': [
      'Kab. Agam',
      'Kab. Dharmasraya',
      'Kab. Lima Puluh Kota',
      'Kab. Padang Pariaman',
      'Kab. Pasaman',
      'Kab. Pesisir Selatan',
      'Kab. Sijunjung',
      'Kab. Solok',
      'Kab. Tanah Datar',
      'Kota Bukittinggi',
      'Kota Padang',
      'Kota Padang Panjang',
      'Kota Pariaman',
      'Kota Payakumbuh',
      'Kota Sawahlunto',
      'Kota Solok',
    ],
    'Riau': [
      'Kab. Bengkalis',
      'Kab. Indragiri Hilir',
      'Kab. Indragiri Hulu',
      'Kab. Kampar',
      'Kab. Pelalawan',
      'Kab. Rokan Hilir',
      'Kab. Rokan Hulu',
      'Kab. Siak',
      'Kota Dumai',
      'Kota Pekanbaru',
    ],
    'Kepulauan Riau': [
      'Kab. Bintan',
      'Kab. Karimun',
      'Kab. Natuna',
      'Kota Batam',
      'Kota Tanjungpinang',
    ],
    'Lampung': [
      'Kab. Lampung Barat',
      'Kab. Lampung Selatan',
      'Kab. Lampung Tengah',
      'Kab. Lampung Timur',
      'Kab. Lampung Utara',
      'Kab. Pesawaran',
      'Kab. Pringsewu',
      'Kab. Tanggamus',
      'Kota Bandar Lampung',
      'Kota Metro',
    ],
    'Sumatera Selatan': [
      'Kab. Banyuasin',
      'Kab. Lahat',
      'Kab. Muara Enim',
      'Kab. Musi Banyuasin',
      'Kab. Ogan Ilir',
      'Kab. Ogan Komering Ilir',
      'Kota Lubuklinggau',
      'Kota Pagar Alam',
      'Kota Palembang',
      'Kota Prabumulih',
    ],
    'Kalimantan Timur': [
      'Kab. Berau',
      'Kab. Kutai Barat',
      'Kab. Kutai Kartanegara',
      'Kab. Kutai Timur',
      'Kab. Paser',
      'Kota Balikpapan',
      'Kota Bontang',
      'Kota Samarinda',
    ],
    'Kalimantan Selatan': [
      'Kab. Banjar',
      'Kab. Barito Kuala',
      'Kab. Kotabaru',
      'Kab. Tanah Bumbu',
      'Kab. Tanah Laut',
      'Kota Banjarbaru',
      'Kota Banjarmasin',
    ],
    'Kalimantan Barat': [
      'Kab. Bengkayang',
      'Kab. Kapuas Hulu',
      'Kab. Ketapang',
      'Kab. Kubu Raya',
      'Kab. Sambas',
      'Kab. Sintang',
      'Kota Pontianak',
      'Kota Singkawang',
    ],
    'Sulawesi Selatan': [
      'Kab. Bantaeng',
      'Kab. Bone',
      'Kab. Bulukumba',
      'Kab. Gowa',
      'Kab. Luwu',
      'Kab. Maros',
      'Kab. Pinrang',
      'Kab. Sinjai',
      'Kab. Wajo',
      'Kota Makassar',
      'Kota Palopo',
      'Kota Parepare',
    ],
    'Sulawesi Utara': [
      'Kab. Bolaang Mongondow',
      'Kab. Minahasa',
      'Kab. Minahasa Selatan',
      'Kab. Minahasa Utara',
      'Kota Bitung',
      'Kota Kotamobagu',
      'Kota Manado',
      'Kota Tomohon',
    ],
  };

  /// Mengambil daftar kota berdasarkan nama provinsi.
  /// Jika provinsi tidak ada dalam kamus kota detail, kembalikan daftar kota umum provinsi tersebut.
  static List<String> getCities(String province) {
    if (citiesByProvince.containsKey(province)) {
      return citiesByProvince[province]!;
    }
    return [
      'Kota / Ibukota Provinsi $province',
      'Kabupaten Lainnya di $province',
    ];
  }

  /// Susun teks alamat lengkap berjenjang standar
  static String formatFullAddress({
    required String detailJalan,
    required String provinsi,
    required String kota,
    String? kecamatan,
    String? kelurahan,
    String? kodePos,
  }) {
    final parts = <String>[];
    if (detailJalan.trim().isNotEmpty) {
      parts.add(detailJalan.trim());
    }
    if (kelurahan != null && kelurahan.trim().isNotEmpty) {
      final k = kelurahan.trim();
      parts.add(k.toLowerCase().startsWith('desa') || k.toLowerCase().startsWith('kel') ? k : 'Ds/Kel. $k');
    }
    if (kecamatan != null && kecamatan.trim().isNotEmpty) {
      final kec = kecamatan.trim();
      parts.add(kec.toLowerCase().startsWith('kec') ? kec : 'Kec. $kec');
    }
    if (kota.trim().isNotEmpty) {
      parts.add(kota.trim());
    }
    if (provinsi.trim().isNotEmpty) {
      parts.add(provinsi.trim());
    }
    if (kodePos != null && kodePos.trim().isNotEmpty) {
      parts.add(kodePos.trim());
    }
    return parts.join(', ');
  }

  /// Periksa apakah nama provinsi berada di Jawa Timur
  static bool isProvinceJatim(String province) {
    final p = province.toLowerCase();
    return p.contains('jawa timur') || p.contains('jatim');
  }

  /// Periksa apakah nama provinsi berada di Pulau Jawa
  static bool isProvinceJava(String province) {
    final p = province.toLowerCase();
    return p.contains('jawa') || p.contains('jakarta') || p.contains('banten') || p.contains('yogyakarta') || p.contains('jogja') || p.contains('diy');
  }
}
