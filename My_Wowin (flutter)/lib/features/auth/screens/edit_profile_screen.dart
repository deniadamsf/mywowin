import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;
import '../../../core/constants/api_constants.dart';
import '../../../core/theme/wowin_theme.dart';
import 'package:image_cropper/image_cropper.dart';

class EditProfileScreen extends StatefulWidget {
  final Map<String, dynamic> userData;

  const EditProfileScreen({super.key, required this.userData});

  @override
  State<EditProfileScreen> createState() => _EditProfileScreenState();
}

class _EditProfileScreenState extends State<EditProfileScreen> {
  static const Color primaryGreen = WowinColors.primaryDark;

  bool _isLoading = false;
  File? _imageFile;
  final ImagePicker _picker = ImagePicker();

  late TextEditingController _namaController;
  late TextEditingController _tokoController;
  late TextEditingController _hpController;
  late TextEditingController _alamatController;

  @override
  void initState() {
    super.initState();
    // Isi otomatis form dengan data yang ada
    _namaController = TextEditingController(text: widget.userData['nama_lengkap'] ?? '');

    final membership = widget.userData['membership'] ?? {};
    _tokoController = TextEditingController(text: membership['nama_toko'] ?? '');
    _hpController = TextEditingController(text: membership['no_hp'] ?? '');
    _alamatController = TextEditingController(text: membership['alamat'] ?? '');
  }

  @override
  void dispose() {
    _namaController.dispose();
    _tokoController.dispose();
    _hpController.dispose();
    _alamatController.dispose();
    super.dispose();
  }

  // Fungsi Membuka Galeri & Memotong Gambar 1:1
  Future<void> _pickImage() async {
    final XFile? pickedFile = await _picker.pickImage(
      source: ImageSource.gallery,
      imageQuality: 100, // Biarkan kualitas maksimal sebelum di-crop
    );

    if (pickedFile != null) {
      // --- PROSES CROP GAMBAR ---
      CroppedFile? croppedFile = await ImageCropper().cropImage(
        sourcePath: pickedFile.path,
        aspectRatio: const CropAspectRatio(ratioX: 1, ratioY: 1), // Kunci Rasio 1:1 (Persegi)
        uiSettings: [
          AndroidUiSettings(
            toolbarTitle: 'Potong Foto Profil',
            toolbarColor: primaryGreen, // Warna toolbar menyesuaikan tema aplikasi
            toolbarWidgetColor: Colors.white,
            initAspectRatio: CropAspectRatioPreset.square,
            lockAspectRatio: true, // Kunci agar user tidak bisa mengubah rasionya
            hideBottomControls: true, // Sembunyikan menu rasio lain agar UI bersih
          ),
          IOSUiSettings(
            title: 'Potong Foto Profil',
            aspectRatioLockEnabled: true, // Kunci rasio di iOS
            resetAspectRatioEnabled: false,
            aspectRatioPickerButtonHidden: true,
          ),
        ],
      );

      // Jika user menekan tombol centang/selesai memotong
      if (croppedFile != null) {
        setState(() {
          _imageFile = File(croppedFile.path);
        });
      }
    }
  }

  // Fungsi Simpan (Mengirim data + Gambar via Multipart)
  Future<void> _updateProfile() async {
    setState(() => _isLoading = true);

    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');

      // Menggunakan MultipartRequest karena ada file foto
      var request = http.MultipartRequest('POST', Uri.parse('$baseUrl/profile/update'));
      request.headers['Authorization'] = 'Bearer $token';
      request.headers['Accept'] = 'application/json';

      request.fields['nama_lengkap'] = _namaController.text.trim();
      request.fields['nama_toko'] = _tokoController.text.trim();
      request.fields['no_hp'] = _hpController.text.trim();
      request.fields['alamat'] = _alamatController.text.trim();

      // Jika user memilih gambar baru, tambahkan ke request
      if (_imageFile != null) {
        request.files.add(await http.MultipartFile.fromPath('foto_profile', _imageFile!.path));
      }

      var response = await request.send();
      if (response.statusCode == 200) {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Profil berhasil diperbarui!'), backgroundColor: primaryGreen));
        // Kembali ke halaman ProfileScreen dengan status true (minta refresh)
        Navigator.pop(context, true);
      } else {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Gagal memperbarui profil.'), backgroundColor: Colors.red));
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Terjadi kesalahan jaringan.'), backgroundColor: Colors.red));
    } finally {
      setState(() => _isLoading = false);
    }
  }

  Widget _buildTextField(String label, TextEditingController controller, IconData icon, {int maxLines = 1}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: TextStyle(fontWeight: FontWeight.w600, color: Colors.grey.shade800, fontSize: 13)),
          const SizedBox(height: 8),
          TextField(
            controller: controller,
            maxLines: maxLines,
            decoration: InputDecoration(
              hintText: 'Masukkan $label',
              prefixIcon: maxLines == 1 ? Icon(icon, color: primaryGreen, size: 20) : null,
              filled: true,
              fillColor: Colors.grey.shade50,
              contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide(color: Colors.grey.shade200)),
              enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide(color: Colors.grey.shade200)),
              focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: primaryGreen, width: 1.5)),
            ),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final fotoLama = widget.userData['foto_profile'];

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: WowinAppBar.standard(title: 'Edit Profil'),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: Column(
            children: [
              // --- AREA FOTO PROFIL ---
              Center(
                child: Stack(
                  children: [
                    Container(
                      width: 120, height: 120,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        color: WowinColors.primaryLight.withValues(alpha: 0.1),
                        border: Border.all(color: Colors.grey.shade200, width: 4),
                        image: _imageFile != null
                            ? DecorationImage(fit: BoxFit.cover, image: FileImage(_imageFile!))
                            : (fotoLama != null && fotoLama.toString().isNotEmpty
                                ? DecorationImage(fit: BoxFit.cover, image: NetworkImage('https://mywowin.com/storage/$fotoLama'))
                                : null),
                      ),
                      child: (_imageFile == null && (fotoLama == null || fotoLama.toString().isEmpty))
                          ? const Center(child: Icon(Icons.person, size: 60, color: WowinColors.primaryDark))
                          : null,
                    ),
                    Positioned(
                      bottom: 0, right: 0,
                      child: GestureDetector(
                        onTap: _pickImage,
                        child: Container(
                          padding: const EdgeInsets.all(10),
                          decoration: BoxDecoration(color: primaryGreen, shape: BoxShape.circle, border: Border.all(color: Colors.white, width: 2)),
                          child: const Icon(Icons.camera_alt, color: Colors.white, size: 20),
                        ),
                      ),
                    )
                  ],
                ),
              ),
              const SizedBox(height: 32),

              // --- FORM EDIT ---
              _buildTextField('Nama Lengkap', _namaController, Icons.person_outline),
              _buildTextField('Nama Toko', _tokoController, Icons.storefront),
              _buildTextField('Nomor HP (WhatsApp)', _hpController, Icons.phone_android),
              _buildTextField('Alamat Lengkap', _alamatController, Icons.location_on_outlined, maxLines: 3),

              const SizedBox(height: 32),

              // --- TOMBOL SIMPAN ---
              SizedBox(
                width: double.infinity,
                height: 52,
                child: ElevatedButton(
                  style: ElevatedButton.styleFrom(
                    backgroundColor: primaryGreen,
                    elevation: 2,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                  ),
                  onPressed: _isLoading ? null : _updateProfile,
                  child: _isLoading
                      ? const SizedBox(height: 24, width: 24, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5))
                      : const Text('Simpan Perubahan', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white)),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}