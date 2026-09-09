import 'package:flutter/material.dart';
import '../data/indonesia_regions.dart';

/// Hasil data dari pemilihan alamat berjenjang
class AddressPickerResult {
  final String fullAddress;
  final String provinsi;
  final String kota;
  final String kecamatan;
  final String kelurahan;
  final String detailJalan;
  final String kodePos;
  final bool saveToProfile;

  AddressPickerResult({
    required this.fullAddress,
    required this.provinsi,
    required this.kota,
    required this.kecamatan,
    required this.kelurahan,
    required this.detailJalan,
    required this.kodePos,
    required this.saveToProfile,
  });
}

/// Bottom Sheet untuk memilih alamat berjenjang (Shopee Style)
class AddressPickerBottomSheet extends StatefulWidget {
  final String? initialAddress;
  final bool showSaveToProfileCheckbox;

  const AddressPickerBottomSheet({
    super.key,
    this.initialAddress,
    this.showSaveToProfileCheckbox = true,
  });

  /// Helper statis untuk menampilkan modal bottom sheet
  static Future<AddressPickerResult?> show(
    BuildContext context, {
    String? initialAddress,
    bool showSaveToProfileCheckbox = true,
  }) {
    return showModalBottomSheet<AddressPickerResult>(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) => AddressPickerBottomSheet(
        initialAddress: initialAddress,
        showSaveToProfileCheckbox: showSaveToProfileCheckbox,
      ),
    );
  }

  @override
  State<AddressPickerBottomSheet> createState() => _AddressPickerBottomSheetState();
}

class _AddressPickerBottomSheetState extends State<AddressPickerBottomSheet> {
  static const Color primaryGreen = Color(0xFF16782D);

  String? _selectedProvince;
  String? _selectedCity;
  late TextEditingController _kecamatanController;
  late TextEditingController _kelurahanController;
  late TextEditingController _detailJalanController;
  late TextEditingController _kodePosController;
  bool _saveToProfile = true;

  @override
  void initState() {
    super.initState();
    _kecamatanController = TextEditingController();
    _kelurahanController = TextEditingController();
    _detailJalanController = TextEditingController();
    _kodePosController = TextEditingController();

    _parseInitialAddress(widget.initialAddress);
  }

  /// Mencoba mengenali bagian alamat lama jika ada
  void _parseInitialAddress(String? address) {
    if (address == null || address.trim().isEmpty || address.trim() == '-') {
      _selectedProvince = 'Jawa Timur';
      _selectedCity = 'Kab. Trenggalek';
      return;
    }

    final lower = address.toLowerCase();

    // Cek provinsi
    for (final p in IndonesiaRegions.provinces) {
      if (lower.contains(p.toLowerCase())) {
        _selectedProvince = p;
        break;
      }
    }

    _selectedProvince ??= 'Jawa Timur';

    // Cek kota
    final cities = IndonesiaRegions.getCities(_selectedProvince!);
    for (final c in cities) {
      final cleanCityName = c.replaceAll('Kab. ', '').replaceAll('Kota ', '').toLowerCase();
      if (lower.contains(cleanCityName)) {
        _selectedCity = c;
        break;
      }
    }

    _selectedCity ??= cities.isNotEmpty ? cities.first : null;

    // Masukkan alamat lama sebagai detail awal
    _detailJalanController.text = address;
  }

  @override
  void dispose() {
    _kecamatanController.dispose();
    _kelurahanController.dispose();
    _detailJalanController.dispose();
    _kodePosController.dispose();
    super.dispose();
  }

  /// Dialog pencarian & pemilihan Provinsi
  void _pickProvince() {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (ctx) {
        String searchQuery = '';
        return StatefulBuilder(
          builder: (context, setModalState) {
            final filtered = IndonesiaRegions.provinces
                .where((p) => p.toLowerCase().contains(searchQuery.toLowerCase()))
                .toList();

            return Container(
              height: MediaQuery.of(context).size.height * 0.7,
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
              child: Column(
                children: [
                  Container(width: 40, height: 4, decoration: BoxDecoration(color: Colors.grey.shade300, borderRadius: BorderRadius.circular(2))),
                  const SizedBox(height: 12),
                  const Text('Pilih Provinsi', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  TextField(
                    decoration: InputDecoration(
                      hintText: 'Cari provinsi...',
                      prefixIcon: const Icon(Icons.search, size: 20),
                      isDense: true,
                      border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                      contentPadding: const EdgeInsets.symmetric(vertical: 10, horizontal: 12),
                    ),
                    onChanged: (val) => setModalState(() => searchQuery = val),
                  ),
                  const SizedBox(height: 8),
                  Expanded(
                    child: ListView.separated(
                      itemCount: filtered.length,
                      separatorBuilder: (_, _) => const Divider(height: 1),
                      itemBuilder: (context, index) {
                        final prov = filtered[index];
                        final isSelected = prov == _selectedProvince;
                        final isJawa = IndonesiaRegions.isProvinceJava(prov);
                        final isJatim = IndonesiaRegions.isProvinceJatim(prov);

                        return ListTile(
                          contentPadding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                          title: Text(
                            prov,
                            style: TextStyle(
                              fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                              color: isSelected ? primaryGreen : Colors.black87,
                            ),
                          ),
                          trailing: isJatim
                              ? _buildTag('Jatim/Madura: Rp 4.500', Colors.green)
                              : (isJawa ? _buildTag('Jawa: Rp 9.500', Colors.blue) : null),
                          onTap: () {
                            Navigator.pop(ctx);
                            setState(() {
                              _selectedProvince = prov;
                              final cities = IndonesiaRegions.getCities(prov);
                              _selectedCity = cities.isNotEmpty ? cities.first : null;
                            });
                          },
                        );
                      },
                    ),
                  ),
                ],
              ),
            );
          },
        );
      },
    );
  }

  /// Dialog pencarian & pemilihan Kota / Kabupaten
  void _pickCity() {
    if (_selectedProvince == null) return;
    final cities = IndonesiaRegions.getCities(_selectedProvince!);

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (ctx) {
        String searchQuery = '';
        return StatefulBuilder(
          builder: (context, setModalState) {
            final filtered = cities
                .where((c) => c.toLowerCase().contains(searchQuery.toLowerCase()))
                .toList();

            return Container(
              height: MediaQuery.of(context).size.height * 0.7,
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
              child: Column(
                children: [
                  Container(width: 40, height: 4, decoration: BoxDecoration(color: Colors.grey.shade300, borderRadius: BorderRadius.circular(2))),
                  const SizedBox(height: 12),
                  Text('Pilih Kab/Kota di $_selectedProvince', style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  TextField(
                    decoration: InputDecoration(
                      hintText: 'Cari kota atau kabupaten...',
                      prefixIcon: const Icon(Icons.search, size: 20),
                      isDense: true,
                      border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                      contentPadding: const EdgeInsets.symmetric(vertical: 10, horizontal: 12),
                    ),
                    onChanged: (val) => setModalState(() => searchQuery = val),
                  ),
                  const SizedBox(height: 8),
                  Expanded(
                    child: ListView.separated(
                      itemCount: filtered.length,
                      separatorBuilder: (_, _) => const Divider(height: 1),
                      itemBuilder: (context, index) {
                        final city = filtered[index];
                        final isSelected = city == _selectedCity;
                        return ListTile(
                          contentPadding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                          title: Text(
                            city,
                            style: TextStyle(
                              fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                              color: isSelected ? primaryGreen : Colors.black87,
                            ),
                          ),
                          trailing: isSelected ? const Icon(Icons.check_circle, color: primaryGreen, size: 20) : null,
                          onTap: () {
                            Navigator.pop(ctx);
                            setState(() => _selectedCity = city);
                          },
                        );
                      },
                    ),
                  ),
                ],
              ),
            );
          },
        );
      },
    );
  }

  Widget _buildTag(String text, MaterialColor color) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
      decoration: BoxDecoration(
        color: color.shade50,
        borderRadius: BorderRadius.circular(4),
        border: Border.all(color: color.shade200),
      ),
      child: Text(text, style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: color.shade800)),
    );
  }

  String _buildPreview() {
    return IndonesiaRegions.formatFullAddress(
      detailJalan: _detailJalanController.text,
      kelurahan: _kelurahanController.text,
      kecamatan: _kecamatanController.text,
      kota: _selectedCity ?? '',
      provinsi: _selectedProvince ?? '',
      kodePos: _kodePosController.text,
    );
  }

  void _submit() {
    if (_selectedProvince == null || _selectedCity == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Harap pilih Provinsi dan Kota/Kabupaten!'), backgroundColor: Colors.red),
      );
      return;
    }

    if (_detailJalanController.text.trim().isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Harap isi detail alamat/nama jalan/patokan!'), backgroundColor: Colors.red),
      );
      return;
    }

    final fullAddress = _buildPreview();

    Navigator.pop(
      context,
      AddressPickerResult(
        fullAddress: fullAddress,
        provinsi: _selectedProvince!,
        kota: _selectedCity!,
        kecamatan: _kecamatanController.text.trim(),
        kelurahan: _kelurahanController.text.trim(),
        detailJalan: _detailJalanController.text.trim(),
        kodePos: _kodePosController.text.trim(),
        saveToProfile: _saveToProfile,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final preview = _buildPreview();
    final isJatim = _selectedProvince != null && IndonesiaRegions.isProvinceJatim(_selectedProvince!);
    final isJawa = _selectedProvince != null && IndonesiaRegions.isProvinceJava(_selectedProvince!);

    return Container(
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      padding: EdgeInsets.only(
        top: 16,
        left: 20,
        right: 20,
        bottom: MediaQuery.of(context).viewInsets.bottom + 16,
      ),
      constraints: BoxConstraints(
        maxHeight: MediaQuery.of(context).size.height * 0.9,
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          // Handle drag
          Container(width: 40, height: 4, decoration: BoxDecoration(color: Colors.grey.shade300, borderRadius: BorderRadius.circular(2))),
          const SizedBox(height: 12),

          // Header
          Row(
            children: [
              const Icon(Icons.location_on, color: primaryGreen, size: 24),
              const SizedBox(width: 8),
              const Expanded(
                child: Text(
                  'Atur Alamat Pengiriman Berjenjang',
                  style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                ),
              ),
              IconButton(
                icon: const Icon(Icons.close, size: 20),
                onPressed: () => Navigator.pop(context),
              ),
            ],
          ),

          // Badge Info Tarif
          Container(
            margin: const EdgeInsets.symmetric(vertical: 8),
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
            decoration: BoxDecoration(
              color: isJatim ? Colors.green.shade50 : (isJawa ? Colors.blue.shade50 : Colors.orange.shade50),
              borderRadius: BorderRadius.circular(8),
              border: Border.all(
                color: isJatim ? Colors.green.shade200 : (isJawa ? Colors.blue.shade200 : Colors.orange.shade200),
              ),
            ),
            child: Row(
              children: [
                Icon(
                  Icons.local_shipping_outlined,
                  size: 18,
                  color: isJatim ? Colors.green.shade800 : (isJawa ? Colors.blue.shade800 : Colors.orange.shade800),
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: Text(
                    isJatim
                        ? 'Zona VIP Jatim & Madura: Rp 4.500 / Kg'
                        : (isJawa
                            ? 'Zona VIP Pulau Jawa: Rp 9.500 / Kg'
                            : 'Zona Reguler Luar Jawa: Estimasi Rp 25.000 / Kg'),
                    style: TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.bold,
                      color: isJatim ? Colors.green.shade900 : (isJawa ? Colors.blue.shade900 : Colors.orange.shade900),
                    ),
                  ),
                ),
              ],
            ),
          ),

          Expanded(
            child: SingleChildScrollView(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const SizedBox(height: 8),

                  // 1. Pilih Provinsi
                  const Text('1. Provinsi *', style: TextStyle(fontSize: 12.5, fontWeight: FontWeight.w600, color: Colors.black87)),
                  const SizedBox(height: 4),
                  InkWell(
                    onTap: _pickProvince,
                    borderRadius: BorderRadius.circular(10),
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                      decoration: BoxDecoration(
                        border: Border.all(color: Colors.grey.shade300),
                        borderRadius: BorderRadius.circular(10),
                        color: Colors.grey.shade50,
                      ),
                      child: Row(
                        children: [
                          Expanded(
                            child: Text(
                              _selectedProvince ?? 'Pilih Provinsi...',
                              style: TextStyle(
                                fontSize: 13.5,
                                color: _selectedProvince != null ? Colors.black87 : Colors.grey,
                                fontWeight: _selectedProvince != null ? FontWeight.w500 : FontWeight.normal,
                              ),
                            ),
                          ),
                          const Icon(Icons.arrow_drop_down, color: primaryGreen),
                        ],
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),

                  // 2. Pilih Kabupaten / Kota
                  const Text('2. Kabupaten / Kota *', style: TextStyle(fontSize: 12.5, fontWeight: FontWeight.w600, color: Colors.black87)),
                  const SizedBox(height: 4),
                  InkWell(
                    onTap: _pickCity,
                    borderRadius: BorderRadius.circular(10),
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                      decoration: BoxDecoration(
                        border: Border.all(color: Colors.grey.shade300),
                        borderRadius: BorderRadius.circular(10),
                        color: Colors.grey.shade50,
                      ),
                      child: Row(
                        children: [
                          Expanded(
                            child: Text(
                              _selectedCity ?? 'Pilih Kabupaten / Kota...',
                              style: TextStyle(
                                fontSize: 13.5,
                                color: _selectedCity != null ? Colors.black87 : Colors.grey,
                                fontWeight: _selectedCity != null ? FontWeight.w500 : FontWeight.normal,
                              ),
                            ),
                          ),
                          const Icon(Icons.arrow_drop_down, color: primaryGreen),
                        ],
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),

                  // 3. Kecamatan & Kode Pos
                  Row(
                    children: [
                      Expanded(
                        flex: 2,
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text('3. Kecamatan', style: TextStyle(fontSize: 12.5, fontWeight: FontWeight.w600, color: Colors.black87)),
                            const SizedBox(height: 4),
                            TextField(
                              controller: _kecamatanController,
                              decoration: InputDecoration(
                                hintText: 'Contoh: Pogalan',
                                hintStyle: const TextStyle(fontSize: 12.5, color: Colors.grey),
                                isDense: true,
                                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                                contentPadding: const EdgeInsets.symmetric(vertical: 10, horizontal: 12),
                              ),
                              onChanged: (_) => setState(() {}),
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        flex: 1,
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text('Kode Pos', style: TextStyle(fontSize: 12.5, fontWeight: FontWeight.w600, color: Colors.black87)),
                            const SizedBox(height: 4),
                            TextField(
                              controller: _kodePosController,
                              keyboardType: TextInputType.number,
                              decoration: InputDecoration(
                                hintText: '66371',
                                hintStyle: const TextStyle(fontSize: 12.5, color: Colors.grey),
                                isDense: true,
                                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                                contentPadding: const EdgeInsets.symmetric(vertical: 10, horizontal: 12),
                              ),
                              onChanged: (_) => setState(() {}),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),

                  // 4. Desa / Kelurahan
                  const Text('4. Desa / Kelurahan (Opsional)', style: TextStyle(fontSize: 12.5, fontWeight: FontWeight.w600, color: Colors.black87)),
                  const SizedBox(height: 4),
                  TextField(
                    controller: _kelurahanController,
                    decoration: InputDecoration(
                      hintText: 'Contoh: Ngetal / Duwet',
                      hintStyle: const TextStyle(fontSize: 12.5, color: Colors.grey),
                      isDense: true,
                      border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                      contentPadding: const EdgeInsets.symmetric(vertical: 10, horizontal: 12),
                    ),
                    onChanged: (_) => setState(() {}),
                  ),
                  const SizedBox(height: 12),

                  // 5. Detail Jalan / Rumah
                  const Text('5. Detail Alamat / Nama Jalan / Patokan *', style: TextStyle(fontSize: 12.5, fontWeight: FontWeight.w600, color: Colors.black87)),
                  const SizedBox(height: 4),
                  TextField(
                    controller: _detailJalanController,
                    maxLines: 2,
                    decoration: InputDecoration(
                      hintText: 'Nama jalan, nomor bangunan, RT/RW, warna pagar, patokan toko...',
                      hintStyle: const TextStyle(fontSize: 12.5, color: Colors.grey),
                      border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                      contentPadding: const EdgeInsets.all(12),
                    ),
                    onChanged: (_) => setState(() {}),
                  ),
                  const SizedBox(height: 14),

                  // Live Preview Alamat
                  if (preview.isNotEmpty) ...[
                    Container(
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: Colors.grey.shade100,
                        borderRadius: BorderRadius.circular(10),
                        border: Border.all(color: Colors.grey.shade300),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Row(
                            children: [
                              Icon(Icons.visibility, size: 14, color: Colors.black54),
                              SizedBox(width: 4),
                              Text('Pratinjau Alamat Lengkap:', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Colors.black54)),
                            ],
                          ),
                          const SizedBox(height: 4),
                          Text(preview, style: const TextStyle(fontSize: 12.5, color: Colors.black87, height: 1.3)),
                        ],
                      ),
                    ),
                    const SizedBox(height: 12),
                  ],

                  // Checkbox Simpan ke Profil
                  if (widget.showSaveToProfileCheckbox) ...[
                    CheckboxListTile(
                      contentPadding: EdgeInsets.zero,
                      controlAffinity: ListTileControlAffinity.leading,
                      activeColor: primaryGreen,
                      dense: true,
                      value: _saveToProfile,
                      onChanged: (val) => setState(() => _saveToProfile = val ?? true),
                      title: const Text(
                        'Simpan sebagai alamat utama di profil saya',
                        style: TextStyle(fontSize: 12.5, fontWeight: FontWeight.w500),
                      ),
                    ),
                  ],
                ],
              ),
            ),
          ),

          const SizedBox(height: 10),

          // Tombol Simpan & Terapkan
          SizedBox(
            width: double.infinity,
            height: 48,
            child: ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: primaryGreen,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              ),
              onPressed: _submit,
              child: const Text(
                'Simpan & Terapkan Alamat',
                style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 14.5),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
