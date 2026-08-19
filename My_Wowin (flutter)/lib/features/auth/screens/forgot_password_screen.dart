import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'dart:async'; // <-- TAMBAHAN WAJIB UNTUK TIMER
import '../../../core/constants/api_constants.dart';

class ForgotPasswordScreen extends StatefulWidget {
  const ForgotPasswordScreen({super.key});

  @override
  State<ForgotPasswordScreen> createState() => _ForgotPasswordScreenState();
}

class _ForgotPasswordScreenState extends State<ForgotPasswordScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _otpController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();

  bool _isOtpSent = false;
  bool _isObscure = true;
  bool _isConfirmObscure = true;
  bool _isLoading = false;

  // --- VARIABEL UNTUK TIMER RESEND ---
  Timer? _timer;
  int _resendTimer = 60;
  bool _canResend = false;

  static const Color wowinGreen = Color(0xFF1B5E20);
  static const wowinGradient = LinearGradient(
    colors: [Color(0xFF0A4A1A), Color(0xFF2E7D32)],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  @override
  void dispose() {
    _timer?.cancel(); // Pastikan timer dimatikan saat pindah halaman
    _emailController.dispose();
    _otpController.dispose();
    _passwordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  // --- FUNGSI MENGHITUNG MUNDUR TIMER ---
  void _startResendTimer() {
    setState(() {
      _resendTimer = 60;
      _canResend = false;
    });

    _timer?.cancel();
    _timer = Timer.periodic(const Duration(seconds: 1), (timer) {
      if (_resendTimer > 0) {
        setState(() {
          _resendTimer--;
        });
      } else {
        setState(() {
          _canResend = true;
        });
        timer.cancel();
      }
    });
  }

  // --- TAHAP 1: Minta OTP ---
  Future<void> _requestOtp() async {
    if (_emailController.text.trim().isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Email wajib diisi!'), backgroundColor: Colors.red));
      return;
    }

    setState(() => _isLoading = true);

    try {
      final response = await http.post(
        Uri.parse('$baseUrl/forgot-password-otp'),
        headers: {'Accept': 'application/json'},
        body: {'email': _emailController.text.trim()},
      );
      final data = json.decode(response.body);

      if (response.statusCode == 200) {
        setState(() {
          _isOtpSent = true;
        });

        _startResendTimer(); // Mulai timer 60 detik di sini!

        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(data['message'] ?? 'OTP terkirim!'), backgroundColor: wowinGreen),
        );
      } else {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(data['message'] ?? 'Email tidak ditemukan.'), backgroundColor: Colors.red));
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Error Server: $e'), backgroundColor: Colors.red, duration: const Duration(seconds: 10)));
    } finally {
      setState(() => _isLoading = false);
    }
  }

  // --- TAHAP 2: Simpan Password Baru ---
  Future<void> _handleResetPassword() async {
    if (!_formKey.currentState!.validate()) return;

    if (_passwordController.text != _confirmPasswordController.text) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Password baru dan konfirmasi tidak cocok!'), backgroundColor: Colors.red));
      return;
    }

    setState(() => _isLoading = true);

    try {
      final response = await http.post(
        Uri.parse('$baseUrl/reset-password'),
        headers: {'Accept': 'application/json'},
        body: {
          'email': _emailController.text.trim(),
          'otp': _otpController.text.trim(),
          'password': _passwordController.text,
          'password_confirmation': _confirmPasswordController.text,
        },
      );

      final data = json.decode(response.body);

      if (response.statusCode == 200) {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Password berhasil diubah! Silakan Login.'), backgroundColor: wowinGreen, duration: Duration(seconds: 4)),
        );
        Navigator.pop(context);
      } else {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(data['message'] ?? 'OTP Salah.'), backgroundColor: Colors.red));
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Terjadi kesalahan jaringan.'), backgroundColor: Colors.red));
    } finally {
      setState(() => _isLoading = false);
    }
  }

  Widget _buildTextField(String label, TextEditingController controller, IconData icon, {bool isPassword = false, bool isConfirm = false, TextInputType type = TextInputType.text, bool readOnly = false, int? maxLength}) {
    bool currentObscure = isConfirm ? _isConfirmObscure : _isObscure;
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: TextStyle(fontWeight: FontWeight.w600, color: Colors.grey.shade800)),
          const SizedBox(height: 8),
          TextFormField(
            controller: controller,
            obscureText: isPassword ? currentObscure : false,
            keyboardType: type,
            readOnly: readOnly,
            maxLength: maxLength,
            validator: (value) => value == null || value.isEmpty ? '$label wajib diisi' : null,
            decoration: InputDecoration(
              counterText: "",
              hintText: 'Masukkan $label',
              hintStyle: TextStyle(color: Colors.grey.shade400, fontSize: 14),
              prefixIcon: Icon(icon, color: readOnly ? Colors.grey : wowinGreen, size: 22),
              suffixIcon: isPassword ? IconButton(
                icon: Icon(currentObscure ? Icons.visibility_off : Icons.visibility, color: Colors.grey),
                onPressed: () => setState(() => isConfirm ? _isConfirmObscure = !_isConfirmObscure : _isObscure = !_isObscure),
              ) : null,
              filled: true,
              fillColor: readOnly ? Colors.grey.shade200 : Colors.grey.shade50,
              border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide(color: Colors.grey.shade200)),
              enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide(color: Colors.grey.shade200)),
              focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: wowinGreen, width: 1.5)),
            ),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey.shade50,
      appBar: AppBar(
        elevation: 0,
        iconTheme: const IconThemeData(color: Colors.white),
        flexibleSpace: Container(decoration: const BoxDecoration(gradient: wowinGradient)),
        title: const Text('Lupa Password', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 18)),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          child: Column(
            children: [
              Container(
                width: double.infinity,
                padding: const EdgeInsets.fromLTRB(24, 16, 24, 40),
                decoration: const BoxDecoration(gradient: wowinGradient, borderRadius: BorderRadius.vertical(bottom: Radius.circular(32))),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Atur Ulang Password', style: TextStyle(fontSize: 26, fontWeight: FontWeight.bold, color: Colors.white)),
                    const SizedBox(height: 8),
                    Text(
                      _isOtpSent
                          ? 'Kode OTP telah dikirim. Masukkan kode tersebut beserta password baru Anda di bawah ini.'
                          : 'Masukkan Email terdaftar Anda. Kami akan mengirimkan 6-digit kode OTP untuk mereset password.',
                      style: TextStyle(fontSize: 14, color: Colors.white.withValues(alpha: 0.9), height: 1.5),
                    ),
                  ],
                ),
              ),

              Transform.translate(
                offset: const Offset(0, -25),
                child: Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 20.0),
                  child: Container(
                    padding: const EdgeInsets.all(24),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 10, offset: const Offset(0, 4))],
                    ),
                    child: Form(
                      key: _formKey,
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          _buildTextField('Email Terdaftar', _emailController, Icons.email, type: TextInputType.emailAddress, readOnly: _isOtpSent),

                          if (_isOtpSent) ...[
                            const Padding(padding: EdgeInsets.symmetric(vertical: 8.0), child: Divider()),
                            _buildTextField('Kode OTP (6 Angka)', _otpController, Icons.message, type: TextInputType.number, maxLength: 6),
                            _buildTextField('Password Baru', _passwordController, Icons.lock_outline, isPassword: true),
                            _buildTextField('Konfirmasi Password', _confirmPasswordController, Icons.lock_reset, isPassword: true, isConfirm: true),
                          ],

                          const SizedBox(height: 24),

                          SizedBox(
                            width: double.infinity,
                            height: 52,
                            child: ElevatedButton(
                              style: ElevatedButton.styleFrom(
                                backgroundColor: wowinGreen,
                                elevation: 2,
                                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                              ),
                              onPressed: _isLoading ? null : (_isOtpSent ? _handleResetPassword : _requestOtp),
                              child: _isLoading
                                  ? const SizedBox(height: 24, width: 24, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5))
                                  : Text(
                                  _isOtpSent ? 'Simpan Password Baru' : 'Kirim Kode OTP',
                                  style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white)
                              ),
                            ),
                          ),

                          // --- AREA TOMBOL RESEND & GANTI EMAIL ---
                          if (_isOtpSent)
                            Padding(
                              padding: const EdgeInsets.only(top: 16.0),
                              child: Column(
                                children: [
                                  // 1. Tombol Kirim Ulang OTP
                                  TextButton(
                                    onPressed: _canResend ? () {
                                      _requestOtp(); // Panggil ulang fungsi kirim OTP
                                    } : null,
                                    child: Text(
                                      _canResend
                                          ? 'Tidak menerima kode? Kirim Ulang OTP'
                                          : 'Kirim Ulang OTP dalam $_resendTimer detik',
                                      style: TextStyle(
                                        color: _canResend ? wowinGreen : Colors.grey.shade500,
                                        fontWeight: _canResend ? FontWeight.bold : FontWeight.normal,
                                      ),
                                    ),
                                  ),

                                  // 2. Tombol Ganti Email
                                  TextButton(
                                    onPressed: () {
                                      setState(() {
                                        _isOtpSent = false;
                                        _timer?.cancel(); // Matikan timer jika batal
                                        _otpController.clear();
                                        _passwordController.clear();
                                        _confirmPasswordController.clear();
                                      });
                                    },
                                    child: const Text('Salah Email? Ganti Email', style: TextStyle(color: Colors.grey)),
                                  ),
                                ],
                              ),
                            )
                        ],
                      ),
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