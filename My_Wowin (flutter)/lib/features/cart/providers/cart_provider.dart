import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/api_constants.dart';

// 1. Konstanta Gradasi Wowin Food (Bisa dipanggil nanti di UI)
const wowinGradient = LinearGradient(
  colors: [
    Color(0xFF1B5E20), // Hijau Gelap (Dark Green)
    Color(0xFF4CAF50), // Hijau Muda (Light Green)
  ],
  begin: Alignment.topLeft,
  end: Alignment.bottomRight,
);

// 2. State untuk menyimpan kondisi keranjang
class CartState {
  final bool isLoading;
  final List<dynamic> items;
  final double subtotal;
  final String? errorMessage;

  CartState({
    this.isLoading = false,
    this.items = const [],
    this.subtotal = 0.0,
    this.errorMessage,
  });

  CartState copyWith({
    bool? isLoading,
    List<dynamic>? items,
    double? subtotal,
    String? errorMessage,
  }) {
    return CartState(
      isLoading: isLoading ?? this.isLoading,
      items: items ?? this.items,
      subtotal: subtotal ?? this.subtotal,
      errorMessage: errorMessage,
    );
  }
}

// 3. Notifier menggunakan standar Riverpod 2.0+
class CartNotifier extends Notifier<CartState> {
  @override
  CartState build() {
    return CartState(); // Mengembalikan state keranjang kosong saat pertama kali dimuat
  }

  // Fungsi untuk menarik data keranjang dari server
  Future<void> fetchCart() async {
    state = state.copyWith(isLoading: true, errorMessage: null);

    try {
      // Ambil token dari brankas HP
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');

      // Jika belum login, hentikan proses
      if (token == null) {
        state = state.copyWith(
            isLoading: false,
            errorMessage: 'Silakan login terlebih dahulu untuk melihat keranjang.'
        );
        return;
      }

      final url = Uri.parse('$baseUrl/cart');
      final response = await http.get(
        url,
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token', // Sertakan token kunci
        },
      );

      final responseData = json.decode(response.body);

      if (response.statusCode == 200 && responseData['success'] == true) {
        state = state.copyWith(
          isLoading: false,
          items: responseData['data'] ?? [],
          // Mengamankan konversi tipe data subtotal
          subtotal: (responseData['subtotal'] ?? 0).toDouble(),
        );
      } else {
        state = state.copyWith(
          isLoading: false,
          errorMessage: responseData['message'] ?? 'Gagal memuat keranjang.',
        );
      }
    } catch (e) {
      state = state.copyWith(
          isLoading: false,
          errorMessage: 'Terjadi kesalahan jaringan. Cek koneksi Anda.'
      );
    }
  }

  // Fungsi untuk menambah produk ke keranjang
  Future<bool> addToCart({
    required int productId,
    required int quantity,
    required String unit
  }) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');

      if (token == null) return false;

      final url = Uri.parse('$baseUrl/cart');
      final response = await http.post(
        url,
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: {
          'product_id': productId.toString(),
          'quantity': quantity.toString(),
          'unit': unit, // 'pcs' atau 'karton'
        },
      );

      final responseData = json.decode(response.body);

      if (response.statusCode == 200 && responseData['success'] == true) {
        // Jika berhasil ditambah, otomatis tarik ulang data keranjang terbaru
        await fetchCart();
        return true;
      }
      return false;
    } catch (e) {
      return false;
    }
  }
}

// 4. Provider untuk dipanggil dari UI
final cartProvider = NotifierProvider<CartNotifier, CartState>(() {
  return CartNotifier();
});