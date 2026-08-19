import 'dart:convert';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/api_constants.dart';

class AuthState {
  final bool isLoading;
  final bool isAuthenticated;
  final String? token;
  final String? errorMessage;

  AuthState({
    this.isLoading = false,
    this.isAuthenticated = false,
    this.token,
    this.errorMessage,
  });

  AuthState copyWith({bool? isLoading, bool? isAuthenticated, String? token, String? errorMessage}) {
    return AuthState(
      isLoading: isLoading ?? this.isLoading,
      isAuthenticated: isAuthenticated ?? this.isAuthenticated,
      token: token ?? this.token,
      errorMessage: errorMessage,
    );
  }
}

// 1. MENGGUNAKAN 'Notifier' SEBAGAI STANDAR BARU
class AuthNotifier extends Notifier<AuthState> {

  // 2. Wajib menggunakan fungsi build() untuk nilai awal
  @override
  AuthState build() {
    _checkToken(); // Otomatis cek token saat aplikasi pertama kali dibuka
    return AuthState(); // Mengembalikan nilai default (belum login)
  }

  Future<void> _checkToken() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');
    if (token != null) {
      state = state.copyWith(isAuthenticated: true, token: token);
    }
  }

  // Tambahkan parameter rememberMe
  Future<bool> login(String username, String password, {bool rememberMe = false}) async {
    state = state.copyWith(isLoading: true, errorMessage: null);

    try {
      final url = Uri.parse('$baseUrl/login');
      final response = await http.post(
        url,
        headers: {'Accept': 'application/json'},
        body: {
          'username': username,
          'password': password,
        },
      );

      final responseData = json.decode(response.body);

      if (response.statusCode == 200 && responseData['token'] != null) {

        // Hanya simpan token ke brankas HP secara permanen JIKA 'Ingat Saya' dicentang
        if (rememberMe) {
          final prefs = await SharedPreferences.getInstance();
          await prefs.setString('auth_token', responseData['token']);
        }

        state = state.copyWith(
            isLoading: false,
            isAuthenticated: true,
            token: responseData['token']
        );
        return true;
      } else {
        state = state.copyWith(
            isLoading: false,
            errorMessage: responseData['message'] ?? 'Login Gagal. Periksa kembali data Anda.'
        );
        return false;
      }
    } catch (e) {
      // Mengembalikan pesan error UI yang rapi (Error debug disembunyikan)
      state = state.copyWith(
          isLoading: false,
          errorMessage: 'Gagal terhubung ke server. Periksa koneksi internet.'
      );
      return false;
    }
  }

  Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
    state = AuthState();
  }

  // Fungsi tambahan untuk login otomatis dari halaman OTP
  void manualLogin(String token) {
    state = state.copyWith(
        isLoading: false,
        isAuthenticated: true,
        token: token
    );
  }
}

// 3. MENGGUNAKAN 'NotifierProvider'
final authProvider = NotifierProvider<AuthNotifier, AuthState>(() {
  return AuthNotifier();
});