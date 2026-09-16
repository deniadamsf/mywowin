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

  // Tambahkan parameter rememberMe (default true untuk persistensi dan kompatibilitas)
  Future<bool> login(String username, String password, {bool rememberMe = true}) async {
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
        final token = responseData['token'].toString();

        // Selalu simpan token ke SharedPreferences agar seluruh fitur aplikasi
        // (Profil, Keranjang, Checkout, Riwayat, Chat) dapat mengakses API
        // dan sesi login tidak ter-logout sendiri saat membuka ulang aplikasi.
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('auth_token', token);

        // Sinkronisasi FCM token perangkat ke server untuk notifikasi push real-time
        final deviceFcmToken = prefs.getString('device_fcm_token');
        if (deviceFcmToken != null && deviceFcmToken.isNotEmpty) {
          try {
            http.post(
              Uri.parse('$baseUrl/fcm-token'),
              headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': 'Bearer $token',
              },
              body: json.encode({'fcm_token': deviceFcmToken}),
            );
          } catch (_) {}
        }

        state = state.copyWith(
            isLoading: false,
            isAuthenticated: true,
            token: token
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
    await prefs.remove('cached_user_profile');
    state = AuthState();
  }

  // Fungsi tambahan untuk login otomatis dari halaman OTP
  Future<void> manualLogin(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
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