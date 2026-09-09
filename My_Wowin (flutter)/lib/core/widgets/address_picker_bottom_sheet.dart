import 'package:flutter/material.dart';
import '../data/indonesia_regions.dart';
import '../services/region_service.dart';

/// Hasil data dari pemilihan alamat berjenjang lengkap
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

/// Bottom Sheet untuk memilih alamat berjenjang sampai ke Desa/Kelurahan (Shopee Style)
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

  final RegionService _regionService = RegionService();

  String? _selectedProvince;
  String? _selectedProvinceId;

  String? _selectedCity;
  String? _selectedCityId;

  String? _selectedDistrict;
  String? _selectedDistrictId;

  String? _selectedVillage;
  String? _selectedVillageId;

  late TextEditingController _detailJalanController;
  late TextEditingController _kodePosController;
  bool _saveToProfile = true;

  @override
  void initState() {
    super.initState();
    _detailJalanController = TextEditingController();
    _kodePosController = TextEditingController();

    _initFromExistingAddress(widget.initialAddress);
  }

  /// Mencoba mengenali bagian alamat lama jika ada
  Future<void> _initFromExistingAddress(String? address) async {
    if (address == null || address.trim().isEmpty || address.trim() == '-') {
      // Default ke Jawa Timur
      setState(() {
        _selectedProvince = 'Jawa Timur';
        _selectedProvinceId = '35';
      });
      _prewarmCities('35');
      return;
    }

    final lower = address.toLowerCase();

    // 1. Cek Provinsi
    String? matchedProvince;
    String? matchedProvinceId;
    for (final p in IndonesiaRegions.provinces) {
      if (lower.contains(p.toLowerCase())) {
        matchedProvince = p;
        matchedProvinceId = IndonesiaRegions.provinceIds[p] ?? '35';
        break;
      }
    }

    matchedProvince ??= 'Jawa Timur';
    matchedProvinceId ??= '35';

    setState(() {
      _selectedProvince = matchedProvince;
      _selectedProvinceId = matchedProvinceId;
    });

    // 2. Cek Kota / Kabupaten
    final cities = await _regionService.getRegencies(matchedProvinceId);
    if (!mounted) return;

    for (final c in cities) {
      final cleanName = c.name.replaceAll('KABUPATEN ', '').replaceAll('KOTA ', '').toLowerCase();
      if (lower.contains(cleanName)) {
        setState(() {
          _selectedCity = c.formattedName;
          _selectedCityId = c.id;
        });
        break;
      }
    }

    // Masukkan teks lama ke detail jalan jika belum diubah
    if (_detailJalanController.text.isEmpty) {
      _detailJalanController.text = address;
    }
  }

  void _prewarmCities(String provinceId) {
    _regionService.getRegencies(provinceId);
  }

  @override
  void dispose() {
    _detailJalanController.dispose();
    _kodePosController.dispose();
    super.dispose();
  }

  /// Menampilkan modal pencarian dan pemilihan wilayah generik
  Future<RegionItem?> _showSearchableModal({
    required String title,
    required String searchHint,
    required Future<List<RegionItem>> fetchFuture,
    String? selectedId,
    bool isProvince = false,
  }) async {
    return showModalBottomSheet<RegionItem>(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (ctx) {
        String searchQuery = '';
        return FutureBuilder<List<RegionItem>>(
          future: fetchFuture,
          builder: (context, snapshot) {
            return StatefulBuilder(
              builder: (context, setModalState) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return Container(
                    height: MediaQuery.of(context).size.height * 0.45,
                    padding: const EdgeInsets.all(24),
                    child: Center(
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          const CircularProgressIndicator(color: primaryGreen),
                          const SizedBox(height: 16),
                          Text('Memuat data $title...', style: TextStyle(color: Colors.grey[700], fontSize: 13)),
                        ],
                      ),
                    ),
                  );
                }

                if (snapshot.hasError || !snapshot.hasData || snapshot.data!.isEmpty) {
                  return Container(
                    height: MediaQuery.of(context).size.height * 0.4,
                    padding: const EdgeInsets.all(24),
                    child: Center(
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Icon(Icons.wifi_off_rounded, size: 40, color: Colors.grey[400]),
                          const SizedBox(height: 12),
                          Text(
                            'Gagal memuat data $title.',
                            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            'Periksa koneksi internet Anda.',
                            style: TextStyle(color: Colors.grey[600], fontSize: 12),
                          ),
                          const SizedBox(height: 16),
                          ElevatedButton(
                            style: ElevatedButton.styleFrom(backgroundColor: primaryGreen),
                            onPressed: () => Navigator.pop(ctx),
                            child: const Text('Tutup', style: TextStyle(color: Colors.white)),
                          ),
                        ],
                      ),
                    ),
                  );
                }

                final allItems = snapshot.data!;
                final filtered = allItems.where((item) {
                  final q = searchQuery.toLowerCase();
                  return item.formattedName.toLowerCase().contains(q) || item.name.toLowerCase().contains(q);
                }).toList();

                return Container(
                  height: MediaQuery.of(context).size.height * 0.75,
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
                  child: Column(
                    children: [
                      Container(
                        width: 40,
                        height: 4,
                        decoration: BoxDecoration(color: Colors.grey.shade300, borderRadius: BorderRadius.circular(2)),
                      ),
                      const SizedBox(height: 12),
                      Row(
                        children: [
                          Expanded(
                            child: Text(
                              title,
                              style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                            ),
                          ),
                          IconButton(
                            icon: const Icon(Icons.close, size: 20),
                            onPressed: () => Navigator.pop(ctx),
                          ),
                        ],
                      ),
                      const SizedBox(height: 8),
                      TextField(
                        decoration: InputDecoration(
                          hintText: searchHint,
                          hintStyle: TextStyle(fontSize: 13, color: Colors.grey.shade400),
                          prefixIcon: const Icon(Icons.search, size: 20, color: primaryGreen),
                          isDense: true,
                          filled: true,
                          fillColor: Colors.grey.shade50,
                          border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: BorderSide(color: Colors.grey.shade300)),
                          enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: BorderSide(color: Colors.grey.shade300)),
                          focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: primaryGreen, width: 1.5)),
                          contentPadding: const EdgeInsets.symmetric(vertical: 10, horizontal: 12),
                        ),
                        onChanged: (val) => setModalState(() => searchQuery = val),
                      ),
                      const SizedBox(height: 10),
                      Expanded(
                        child: filtered.isEmpty
                            ? Center(
                                child: Text('Tidak ditemukan pencarian "$searchQuery"', style: TextStyle(color: Colors.grey[500], fontSize: 13)),
                              )
                            : ListView.separated(
                                itemCount: filtered.length,
                                separatorBuilder: (_, _) => const Divider(height: 1),
                                itemBuilder: (context, index) {
                                  final item = filtered[index];
                                  final isSelected = item.id == selectedId || item.formattedName == selectedId;
                                  final isJatim = isProvince && IndonesiaRegions.isProvinceJatim(item.formattedName);
                                  final isJawa = isProvince && IndonesiaRegions.isProvinceJava(item.formattedName);

                                  return ListTile(
                                    contentPadding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                                    title: Text(
                                      item.formattedName,
                                      style: TextStyle(
                                        fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                                        color: isSelected ? primaryGreen : Colors.black87,
                                        fontSize: 14,
                                      ),
                                    ),
                                    trailing: isProvince
                                        ? (isJatim
                                            ? _buildTag('Jatim/Madura: Rp 4.500', Colors.green)
                                            : (isJawa ? _buildTag('Jawa: Rp 9.500', Colors.blue) : null))
                                        : (isSelected ? const Icon(Icons.check_circle, color: primaryGreen, size: 20) : null),
                                    onTap: () => Navigator.pop(ctx, item),
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
      },
    );
  }

  /// 1. Pilih Provinsi
  Future<void> _pickProvince() async {
    final item = await _showSearchableModal(
      title: 'Pilih Provinsi',
      searchHint: 'Cari nama provinsi...',
      fetchFuture: _regionService.getProvinces(),
      selectedId: _selectedProvinceId,
      isProvince: true,
    );

    if (item != null) {
      setState(() {
        _selectedProvince = item.formattedName;
        _selectedProvinceId = item.id;
        // Reset pilihan di bawahnya
        _selectedCity = null;
        _selectedCityId = null;
        _selectedDistrict = null;
        _selectedDistrictId = null;
        _selectedVillage = null;
        _selectedVillageId = null;
      });
      // Pre-warm data Kab/Kota
      _regionService.getRegencies(item.id);
    }
  }

  /// 2. Pilih Kabupaten / Kota
  Future<void> _pickCity() async {
    if (_selectedProvinceId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Harap pilih Provinsi terlebih dahulu!'), backgroundColor: Colors.orange),
      );
      return;
    }

    final item = await _showSearchableModal(
      title: 'Pilih Kab/Kota di $_selectedProvince',
      searchHint: 'Cari kabupaten atau kota...',
      fetchFuture: _regionService.getRegencies(_selectedProvinceId!),
      selectedId: _selectedCityId,
    );

    if (item != null) {
      setState(() {
        _selectedCity = item.formattedName;
        _selectedCityId = item.id;
        // Reset pilihan di bawahnya
        _selectedDistrict = null;
        _selectedDistrictId = null;
        _selectedVillage = null;
        _selectedVillageId = null;
      });
      // Pre-warm data Kecamatan
      _regionService.getDistricts(item.id);
    }
  }

  /// 3. Pilih Kecamatan
  Future<void> _pickDistrict() async {
    if (_selectedCityId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Harap pilih Kabupaten/Kota terlebih dahulu!'), backgroundColor: Colors.orange),
      );
      return;
    }

    final item = await _showSearchableModal(
      title: 'Pilih Kecamatan di $_selectedCity',
      searchHint: 'Cari nama kecamatan...',
      fetchFuture: _regionService.getDistricts(_selectedCityId!),
      selectedId: _selectedDistrictId,
    );

    if (item != null) {
      setState(() {
        _selectedDistrict = item.formattedName;
        _selectedDistrictId = item.id;
        // Reset pilihan di bawahnya
        _selectedVillage = null;
        _selectedVillageId = null;
      });
      // Pre-warm data Desa/Kelurahan
      _regionService.getVillages(item.id);
    }
  }

  /// 4. Pilih Desa / Kelurahan
  Future<void> _pickVillage() async {
    if (_selectedDistrictId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Harap pilih Kecamatan terlebih dahulu!'), backgroundColor: Colors.orange),
      );
      return;
    }

    final item = await _showSearchableModal(
      title: 'Pilih Desa/Kelurahan di Kec. $_selectedDistrict',
      searchHint: 'Cari nama desa atau kelurahan...',
      fetchFuture: _regionService.getVillages(_selectedDistrictId!),
      selectedId: _selectedVillageId,
    );

    if (item != null) {
      setState(() {
        _selectedVillage = item.formattedName;
        _selectedVillageId = item.id;
      });
    }
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
      kelurahan: _selectedVillage,
      kecamatan: _selectedDistrict,
      kota: _selectedCity ?? '',
      provinsi: _selectedProvince ?? '',
      kodePos: _kodePosController.text,
    );
  }

  void _submit() {
    if (_selectedProvince == null || _selectedCity == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Harap pilih Provinsi dan Kabupaten/Kota!'), backgroundColor: Colors.red),
      );
      return;
    }

    if (_selectedDistrict == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Harap pilih Kecamatan!'), backgroundColor: Colors.red),
      );
      return;
    }

    if (_selectedVillage == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Harap pilih Desa / Kelurahan!'), backgroundColor: Colors.red),
      );
      return;
    }

    if (_detailJalanController.text.trim().isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Harap isi detail alamat/nama jalan/RT/RW/nomor rumah!'), backgroundColor: Colors.red),
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
        kecamatan: _selectedDistrict!,
        kelurahan: _selectedVillage!,
        detailJalan: _detailJalanController.text.trim(),
        kodePos: _kodePosController.text.trim(),
        saveToProfile: _saveToProfile,
      ),
    );
  }

  Widget _buildSelectionTile({
    required String step,
    required String title,
    required String? value,
    required VoidCallback onTap,
    required bool isEnabled,
    String? placeholder,
  }) {
    final hasValue = value != null && value.isNotEmpty;

    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            '$step. $title *',
            style: TextStyle(
              fontSize: 12.5,
              fontWeight: FontWeight.w600,
              color: isEnabled ? Colors.black87 : Colors.grey.shade400,
            ),
          ),
          const SizedBox(height: 4),
          InkWell(
            onTap: isEnabled ? onTap : null,
            borderRadius: BorderRadius.circular(10),
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
              decoration: BoxDecoration(
                border: Border.all(color: hasValue ? primaryGreen.withValues(alpha: 0.5) : Colors.grey.shade300),
                borderRadius: BorderRadius.circular(10),
                color: isEnabled ? (hasValue ? Colors.green.shade50.withValues(alpha: 0.3) : Colors.grey.shade50) : Colors.grey.shade100,
              ),
              child: Row(
                children: [
                  Icon(
                    hasValue ? Icons.check_circle : Icons.location_city_outlined,
                    size: 18,
                    color: hasValue ? primaryGreen : (isEnabled ? Colors.grey.shade600 : Colors.grey.shade400),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: Text(
                      hasValue ? value : (placeholder ?? 'Pilih $title...'),
                      style: TextStyle(
                        fontSize: 13.5,
                        color: hasValue ? Colors.black87 : (isEnabled ? Colors.grey.shade600 : Colors.grey.shade400),
                        fontWeight: hasValue ? FontWeight.w600 : FontWeight.normal,
                      ),
                    ),
                  ),
                  Icon(
                    Icons.arrow_drop_down,
                    color: isEnabled ? primaryGreen : Colors.grey.shade400,
                  ),
                ],
              ),
            ),
          ),
        ],
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
        maxHeight: MediaQuery.of(context).size.height * 0.92,
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          // Handle drag
          Container(
            width: 40,
            height: 4,
            decoration: BoxDecoration(color: Colors.grey.shade300, borderRadius: BorderRadius.circular(2)),
          ),
          const SizedBox(height: 12),

          // Header
          Row(
            children: [
              const Icon(Icons.location_on, color: primaryGreen, size: 24),
              const SizedBox(width: 8),
              const Expanded(
                child: Text(
                  'Atur Alamat Berjenjang Lengkap',
                  style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                ),
              ),
              IconButton(
                icon: const Icon(Icons.close, size: 20),
                onPressed: () => Navigator.pop(context),
              ),
            ],
          ),

          // Badge Info Tarif J&T Jawara
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
                  _buildSelectionTile(
                    step: '1',
                    title: 'Provinsi',
                    value: _selectedProvince,
                    onTap: _pickProvince,
                    isEnabled: true,
                  ),

                  // 2. Pilih Kabupaten / Kota
                  _buildSelectionTile(
                    step: '2',
                    title: 'Kabupaten / Kota',
                    value: _selectedCity,
                    onTap: _pickCity,
                    isEnabled: _selectedProvinceId != null,
                    placeholder: _selectedProvinceId == null ? 'Pilih Provinsi dahulu' : 'Pilih Kabupaten / Kota...',
                  ),

                  // 3. Pilih Kecamatan (Cascading Picker)
                  _buildSelectionTile(
                    step: '3',
                    title: 'Kecamatan',
                    value: _selectedDistrict,
                    onTap: _pickDistrict,
                    isEnabled: _selectedCityId != null,
                    placeholder: _selectedCityId == null ? 'Pilih Kab/Kota dahulu' : 'Pilih Kecamatan...',
                  ),

                  // 4. Pilih Desa / Kelurahan (Cascading Picker)
                  _buildSelectionTile(
                    step: '4',
                    title: 'Desa / Kelurahan',
                    value: _selectedVillage,
                    onTap: _pickVillage,
                    isEnabled: _selectedDistrictId != null,
                    placeholder: _selectedDistrictId == null ? 'Pilih Kecamatan dahulu' : 'Pilih Desa / Kelurahan...',
                  ),

                  // 5. Kode Pos (Opsional / Manual)
                  Padding(
                    padding: const EdgeInsets.only(bottom: 12),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('5. Kode Pos (Opsional)', style: TextStyle(fontSize: 12.5, fontWeight: FontWeight.w600, color: Colors.black87)),
                        const SizedBox(height: 4),
                        TextField(
                          controller: _kodePosController,
                          keyboardType: TextInputType.number,
                          decoration: InputDecoration(
                            hintText: 'Contoh: 66371',
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

                  // 6. Detail Jalan / Nama Jalan / Patokan
                  Padding(
                    padding: const EdgeInsets.only(bottom: 12),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('6. Detail Alamat / Nama Jalan / Patokan *', style: TextStyle(fontSize: 12.5, fontWeight: FontWeight.w600, color: Colors.black87)),
                        const SizedBox(height: 4),
                        TextField(
                          controller: _detailJalanController,
                          maxLines: 2,
                          decoration: InputDecoration(
                            hintText: 'Contoh: Jl. Raya Pogalan No. 45, RT 02/RW 01, Toko Berkah (depan apotek)',
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

                  // Pratinjau Alamat Lengkap
                  if (preview.trim().isNotEmpty) ...[
                    Container(
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: Colors.grey.shade50,
                        borderRadius: BorderRadius.circular(10),
                        border: Border.all(color: Colors.grey.shade200),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              Icon(Icons.visibility_outlined, size: 14, color: Colors.grey.shade700),
                              const SizedBox(width: 4),
                              Text('Pratinjau Alamat Lengkap:', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Colors.grey.shade700)),
                            ],
                          ),
                          const SizedBox(height: 6),
                          Text(
                            preview,
                            style: const TextStyle(fontSize: 12.5, color: Colors.black87, height: 1.4, fontWeight: FontWeight.w500),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 12),
                  ],

                  // Checkbox Simpan ke Profil
                  if (widget.showSaveToProfileCheckbox)
                    CheckboxListTile(
                      value: _saveToProfile,
                      onChanged: (val) => setState(() => _saveToProfile = val ?? true),
                      title: const Text('Simpan alamat ini ke profil akun saya', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w500)),
                      controlAffinity: ListTileControlAffinity.leading,
                      contentPadding: EdgeInsets.zero,
                      activeColor: primaryGreen,
                      dense: true,
                    ),
                ],
              ),
            ),
          ),

          const SizedBox(height: 10),

          // Tombol Simpan Alamat
          SizedBox(
            width: double.infinity,
            height: 48,
            child: ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: primaryGreen,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                elevation: 2,
              ),
              onPressed: _submit,
              child: const Text(
                'Gunakan Alamat Ini',
                style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Colors.white),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
