import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../../../core/constants/api_constants.dart';
import '../../../core/theme/wowin_theme.dart';
import 'otp_verification_screen.dart';
import '../../../core/widgets/address_picker_bottom_sheet.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  static const Color wowinGreen = WowinColors.primary;

  final _formKey = GlobalKey<FormState>();

  final _usernameController = TextEditingController();
  final _emailController = TextEditingController();
  final _namaLengkapController = TextEditingController();
  final _passwordController = TextEditingController();
  final _namaTokoController = TextEditingController();
  final _alamatController = TextEditingController();
  final _noHpController = TextEditingController();
  final _namaSalesController = TextEditingController();

  String? _selectedCabang;
  bool _isObscure = true;
  bool _isLoading = false;

  final List<String> _cabangList = [
    'Trenggalek', 'Kediri', 'Madiun', 'Solo', 'Jogja',
    'Cirebon', 'Kudus', 'Bogor', 'Serang'
  ];

  @override
  void dispose() {
    _usernameController.dispose();
    _emailController.dispose();
    _namaLengkapController.dispose();
    _passwordController.dispose();
    _namaTokoController.dispose();
    _alamatController.dispose();
    _noHpController.dispose();
    _namaSalesController.dispose();
    super.dispose();
  }

  Future<void> _handleRegister() async {
    if (!_formKey.currentState!.validate()) return;
    if (_selectedCabang == null) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Silakan pilih Kantor Cabang!'), backgroundColor: Colors.red));
      return;
    }

    setState(() => _isLoading = true);

    try {
      final response = await http.post(
        Uri.parse('$baseUrl/register'),
        headers: {'Accept': 'application/json'},
        body: {
          'username': _usernameController.text.trim(),
          'email': _emailController.text.trim(),
          'nama_lengkap': _namaLengkapController.text.trim(),
          'password': _passwordController.text,
          'nama_toko': _namaTokoController.text.trim(),
          'alamat': _alamatController.text.trim(),
          'no_hp': _noHpController.text.trim(),
          'nama_sales': _namaSalesController.text.trim(), // Jika kosong, API akan menerima string kosong
          'kantor_cabang': _selectedCabang!,
        },
      );

      final data = json.decode(response.body);

      if (response.statusCode == 201 || response.statusCode == 200) {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Registrasi berhasil! Cek email Anda untuk aktivasi.'), backgroundColor: wowinGreen, duration: Duration(seconds: 4)),
        );

        // --- UBAH BAGIAN INI ---
        // Pindah ke layar OTP sambil membawa alamat email yang diketik
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(
            builder: (context) => OtpVerificationScreen(email: _emailController.text.trim()),
          ),
        );
        // -----------------------
      } else {
        if (!mounted) return;
        String errorMessage = data['message'] ?? 'Gagal melakukan registrasi.';
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(errorMessage), backgroundColor: Colors.red));
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Terjadi kesalahan jaringan.'), backgroundColor: Colors.red));
    } finally {
      setState(() => _isLoading = false);
    }
  }

  // --- FUNGSI BUILDER TEXTFIELD YANG SUDAH DIPERMAK ---
  Widget _buildTextField(String label, TextEditingController controller, IconData icon, {bool isPassword = false, TextInputType type = TextInputType.text, bool isOptional = false}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Text(label, style: TextStyle(fontWeight: FontWeight.w600, color: Colors.grey.shade800)),
              if (isOptional)
                Text(' (Opsional)', style: TextStyle(fontSize: 12, color: Colors.grey.shade500, fontStyle: FontStyle.italic)),
            ],
          ),
          const SizedBox(height: 8),
          TextFormField(
            controller: controller,
            obscureText: isPassword ? _isObscure : false,
            keyboardType: type,
            // Jika opsional, matikan kewajiban mengisi
            validator: isOptional ? null : (value) => value == null || value.isEmpty ? '$label wajib diisi' : null,
            decoration: InputDecoration(
              hintText: 'Masukkan $label',
              hintStyle: TextStyle(color: Colors.grey.shade400, fontSize: 14),
              prefixIcon: Icon(icon, color: wowinGreen, size: 22),
              suffixIcon: isPassword ? IconButton(
                icon: Icon(_isObscure ? Icons.visibility_off : Icons.visibility, color: Colors.grey),
                onPressed: () => setState(() => _isObscure = !_isObscure),
              ) : null,
              filled: true,
              fillColor: Colors.grey.shade50, // Latar abu-abu sangat muda agar elegan
              border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide(color: Colors.grey.shade200)),
              enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide(color: Colors.grey.shade200)),
              focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: wowinGreen, width: 1.5)),
              errorBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide(color: Colors.red.shade300)),
            ),
          ),
        ],
      ),
    );
  }

  // --- WIDGET KARTU PEMBUNGKUS SEKSI ---
  Widget _buildSectionCard({required String title, required IconData icon, required List<Widget> children}) {
    return Container(
      margin: const EdgeInsets.only(bottom: 24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: Colors.grey.shade200),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Padding(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(color: wowinGreen.withValues(alpha: 0.1), shape: BoxShape.circle),
                  child: Icon(icon, color: wowinGreen, size: 20),
                ),
                const SizedBox(width: 12),
                Text(title, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
              ],
            ),
            const SizedBox(height: 20),
            const Divider(height: 1),
            const SizedBox(height: 20),
            ...children,
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: WowinColors.background,
      appBar: WowinAppBar.standard(title: 'Daftar Akun Baru'),
      body: SafeArea(
        child: SingleChildScrollView(
          child: Column(
            children: [
              // Header melengkung di atas
              Container(
                width: double.infinity,
                padding: const EdgeInsets.fromLTRB(20, 14, 20, 32),
                decoration: const BoxDecoration(
                  gradient: WowinGradients.royalEmerald,
                  borderRadius: BorderRadius.vertical(bottom: Radius.circular(28)),
                ),
                child: const Text(
                  'Lengkapi data di bawah ini untuk bergabung menjadi Mitra Wowin Food.',
                  style: TextStyle(color: Colors.white, fontSize: 12.5, height: 1.4),
                ),
              ),

              // Form menjorok ke atas (menggunakan Transform)
              Transform.translate(
                offset: const Offset(0, -20),
                child: Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 20.0),
                  child: Form(
                    key: _formKey,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        _buildSectionCard(
                            title: 'Data Pribadi & Akses',
                            icon: Icons.person_pin,
                            children: [
                              _buildTextField('Nama Lengkap', _namaLengkapController, Icons.person),
                              _buildTextField('Username', _usernameController, Icons.alternate_email),
                              _buildTextField('Email', _emailController, Icons.email, type: TextInputType.emailAddress),
                              _buildTextField('Password (Min. 6 Karakter)', _passwordController, Icons.lock_outline, isPassword: true),
                            ]
                        ),

                        _buildSectionCard(
                            title: 'Data Kemitraan Toko',
                            icon: Icons.store_mall_directory,
                            children: [
                              _buildTextField('Nama Toko', _namaTokoController, Icons.storefront),
                              _buildTextField('Nomor Handphone (WA)', _noHpController, Icons.phone_android, type: TextInputType.phone),
                              _buildTextField('Alamat Lengkap', _alamatController, Icons.location_on),
                              const SizedBox(height: 2),
                              Align(
                                alignment: Alignment.centerRight,
                                child: TextButton.icon(
                                  onPressed: () async {
                                    final result = await AddressPickerBottomSheet.show(
                                      context,
                                      initialAddress: _alamatController.text,
                                      showSaveToProfileCheckbox: false,
                                    );
                                    if (result != null) {
                                      setState(() {
                                        _alamatController.text = result.fullAddress;
                                      });
                                    }
                                  },
                                  icon: const Icon(Icons.edit_location_alt_rounded, size: 15, color: wowinGreen),
                                  label: const Text(
                                    'Pilih Alamat Berjenjang (Provinsi & Kota)',
                                    style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: wowinGreen),
                                  ),
                                ),
                              ),

                              // --- KOLOM REFERRAL SEKARANG OPSIONAL ---
                              _buildTextField('Nama Sales / Referral', _namaSalesController, Icons.handshake, isOptional: true),

                              const Text('Kantor Cabang', style: TextStyle(fontWeight: FontWeight.w600)),
                              const SizedBox(height: 8),
                              DropdownButtonFormField<String>(
                                initialValue: _selectedCabang,
                                hint: const Text('Pilih Cabang Terdekat', style: TextStyle(fontSize: 14)),
                                decoration: InputDecoration(
                                  prefixIcon: const Icon(Icons.location_city, color: wowinGreen, size: 22),
                                  filled: true,
                                  fillColor: Colors.grey.shade50,
                                  border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide(color: Colors.grey.shade200)),
                                  enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide(color: Colors.grey.shade200)),
                                  focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: wowinGreen, width: 1.5)),
                                ),
                                items: _cabangList.map((String cabang) => DropdownMenuItem<String>(value: cabang, child: Text(cabang))).toList(),
                                onChanged: (newValue) => setState(() => _selectedCabang = newValue),
                                validator: (value) => value == null ? 'Cabang wajib dipilih' : null,
                                icon: const Icon(Icons.keyboard_arrow_down, color: Colors.grey),
                              ),
                            ]
                        ),

                        const SizedBox(height: 10),
                        SizedBox(
                          width: double.infinity,
                          height: 52,
                          child: ElevatedButton(
                            style: ElevatedButton.styleFrom(
                              backgroundColor: wowinGreen,
                              elevation: 2,
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                            ),
                            onPressed: _isLoading ? null : _handleRegister,
                            child: _isLoading
                                ? const SizedBox(height: 24, width: 24, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5))
                                : const Text('Daftar Akun Sekarang', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white, letterSpacing: 0.5)),
                          ),
                        ),
                        const SizedBox(height: 40),
                      ],
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}