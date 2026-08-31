import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/theme/wowin_theme.dart';
import '../providers/auth_provider.dart';
import 'register_screen.dart';
import 'forgot_password_screen.dart';

class LoginScreen extends ConsumerStatefulWidget {
  const LoginScreen({super.key});

  @override
  ConsumerState<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends ConsumerState<LoginScreen> {
  final _usernameController = TextEditingController();
  final _passwordController = TextEditingController();

  bool _isObscure = true;
  bool _rememberMe = false;

  static const Color wowinGreen = WowinColors.primary;

  @override
  void dispose() {
    _usernameController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  void _handleLogin() async {
    final username = _usernameController.text.trim();
    final password = _passwordController.text.trim();

    if (username.isEmpty || password.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Username dan Password wajib diisi!'), backgroundColor: Colors.red),
      );
      return;
    }

    final success = await ref.read(authProvider.notifier).login(
      username,
      password,
      rememberMe: _rememberMe,
    );

    if (success) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Login Berhasil! Selamat berbelanja.'), backgroundColor: wowinGreen),
      );
      Navigator.pop(context);
    } else {
      if (!mounted) return;
      final errorMsg = ref.read(authProvider).errorMessage;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(errorMsg ?? 'Login Gagal. Periksa kembali data Anda.'), backgroundColor: Colors.red),
      );
    }
  }

  // --- DESAIN INPUT YANG SAMA DENGAN REGISTER ---
  Widget _buildTextField(String label, TextEditingController controller, IconData icon, {bool isPassword = false}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: TextStyle(fontWeight: FontWeight.w600, color: Colors.grey.shade800)),
          const SizedBox(height: 8),
          TextField(
            controller: controller,
            obscureText: isPassword ? _isObscure : false,
            decoration: InputDecoration(
              hintText: 'Masukkan $label',
              hintStyle: TextStyle(color: Colors.grey.shade400, fontSize: 14),
              prefixIcon: Icon(icon, color: wowinGreen, size: 22),
              suffixIcon: isPassword ? IconButton(
                icon: Icon(_isObscure ? Icons.visibility_off : Icons.visibility, color: Colors.grey),
                onPressed: () => setState(() => _isObscure = !_isObscure),
              ) : null,
              filled: true,
              fillColor: Colors.grey.shade50,
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
    final authState = ref.watch(authProvider);

    return Scaffold(
      backgroundColor: WowinColors.background,
      appBar: WowinAppBar.standard(title: 'Masuk ke Akun'),
      body: SafeArea(
        child: SingleChildScrollView(
          child: Column(
            children: [
              // --- HEADER MELENGKUNG ---
              Container(
                width: double.infinity,
                padding: const EdgeInsets.fromLTRB(20, 14, 20, 36),
                decoration: const BoxDecoration(
                  gradient: WowinGradients.royalEmerald,
                  borderRadius: BorderRadius.vertical(bottom: Radius.circular(28)),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Selamat Datang Kembali!',
                      style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.white, letterSpacing: -0.3),
                    ),
                    const SizedBox(height: 6),
                    Text(
                      'Silakan masuk untuk melanjutkan belanja produk Wowin Food pilihan Anda.',
                      style: TextStyle(fontSize: 12.5, color: Colors.white.withValues(alpha: 0.9), height: 1.4),
                    ),
                  ],
                ),
              ),

              // --- FLOATING CARD (KARTU LOGIN) ---
              Transform.translate(
                offset: const Offset(0, -25),
                child: Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 20.0),
                  child: Container(
                    padding: const EdgeInsets.all(24),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      boxShadow: [
                        BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 10, offset: const Offset(0, 4))
                      ],
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        _buildTextField('Username atau Email', _usernameController, Icons.person_outline),
                        _buildTextField('Password', _passwordController, Icons.lock_outline, isPassword: true),

                        // --- INGAT SAYA & LUPA PASSWORD ---
                        Row(
                          children: [
                            SizedBox(
                              height: 24,
                              width: 24,
                              child: Checkbox(
                                value: _rememberMe,
                                activeColor: wowinGreen,
                                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(4)),
                                onChanged: (value) {
                                  setState(() => _rememberMe = value ?? false);
                                },
                              ),
                            ),
                            const SizedBox(width: 8),
                            Text('Ingat Saya', style: TextStyle(fontSize: 14, color: Colors.grey.shade700)),
                            const Spacer(),
                            TextButton(
                              onPressed: () {
                                Navigator.push(context, MaterialPageRoute(builder: (context) => const ForgotPasswordScreen()));
                              },
                              style: TextButton.styleFrom(
                                padding: EdgeInsets.zero,
                                minimumSize: const Size(50, 30),
                                alignment: Alignment.centerRight,
                              ),
                              child: const Text('Lupa Password?', style: TextStyle(color: wowinGreen, fontWeight: FontWeight.w600)),
                            ),
                          ],
                        ),
                        const SizedBox(height: 32),

                        // --- TOMBOL LOGIN ---
                        SizedBox(
                          width: double.infinity,
                          height: 52,
                          child: ElevatedButton(
                            style: ElevatedButton.styleFrom(
                              backgroundColor: wowinGreen,
                              elevation: 2,
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                            ),
                            onPressed: authState.isLoading ? null : _handleLogin,
                            child: authState.isLoading
                                ? const SizedBox(height: 24, width: 24, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5))
                                : const Text('Masuk Sekarang', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white, letterSpacing: 0.5)),
                          ),
                        ),
                        const SizedBox(height: 24),

                        // --- LINK KE DAFTAR BARU ---
                        Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Text('Belum punya akun? ', style: TextStyle(color: Colors.grey.shade700)),
                            GestureDetector(
                              onTap: () {
                                Navigator.push(context, MaterialPageRoute(builder: (context) => const RegisterScreen()));
                              },
                              child: const Text(
                                'Daftar Sekarang',
                                style: TextStyle(color: wowinGreen, fontWeight: FontWeight.bold, fontSize: 15),
                              ),
                            ),
                          ],
                        ),
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