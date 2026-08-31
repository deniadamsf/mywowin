import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/services/cache_service.dart';

// 1. Konstanta Gradasi Wowin Food
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
  final bool isOfflineData;

  CartState({
    this.isLoading = false,
    this.items = const [],
    this.subtotal = 0.0,
    this.errorMessage,
    this.isOfflineData = false,
  });

  CartState copyWith({
    bool? isLoading,
    List<dynamic>? items,
    double? subtotal,
    String? errorMessage,
    bool? isOfflineData,
  }) {
    return CartState(
      isLoading: isLoading ?? this.isLoading,
      items: items ?? this.items,
      subtotal: subtotal ?? this.subtotal,
      errorMessage: errorMessage,
      isOfflineData: isOfflineData ?? this.isOfflineData,
    );
  }
}

// 3. Notifier Keranjang dengan Dukungan Offline-First
class CartNotifier extends Notifier<CartState> {
  @override
  CartState build() {
    _loadOfflineCartInitial();
    return CartState();
  }

  // Muat keranjang yang tersimpan di HP secara instan
  Future<void> _loadOfflineCartInitial() async {
    final offlineCart = await CacheService.getOfflineCart();
    final items = offlineCart['items'] as List<dynamic>? ?? [];
    final subtotal = (offlineCart['subtotal'] as num?)?.toDouble() ?? 0.0;
    if (items.isNotEmpty && state.items.isEmpty) {
      state = state.copyWith(
        items: items,
        subtotal: subtotal,
        isOfflineData: true,
      );
    }
  }

  // Fungsi untuk menarik data keranjang dari server dengan fallback cache
  Future<void> fetchCart() async {
    state = state.copyWith(isLoading: true, errorMessage: null);

    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');

      if (token == null) {
        // Jika belum login, tetap coba tampilkan keranjang draf lokal jika ada
        final offlineCart = await CacheService.getOfflineCart();
        state = state.copyWith(
          isLoading: false,
          items: offlineCart['items'] ?? [],
          subtotal: (offlineCart['subtotal'] ?? 0).toDouble(),
          isOfflineData: true,
        );
        return;
      }

      final url = Uri.parse('$baseUrl/cart');
      final response = await http.get(
        url,
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      ).timeout(const Duration(seconds: 8));

      final responseData = json.decode(response.body);

      if (response.statusCode == 200 && responseData['success'] == true) {
        final List<dynamic> items = responseData['data'] ?? [];
        final double subtotal = (responseData['subtotal'] ?? 0).toDouble();

        // Simpan keranjang terbaru ke cache lokal
        await CacheService.saveOfflineCart(items, subtotal);

        state = state.copyWith(
          isLoading: false,
          items: items,
          subtotal: subtotal,
          isOfflineData: false,
        );
      } else {
        // Server mengembalikan status non-200, gunakan data offline
        await _fallbackToOfflineCart();
      }
    } catch (e) {
      // Jaringan terputus / timeout / offline, beralih ke draf offline
      debugPrint('Gagal fetch cart live, beralih ke cache: $e');
      await _fallbackToOfflineCart();
    }
  }

  Future<void> _fallbackToOfflineCart() async {
    final offlineCart = await CacheService.getOfflineCart();
    final items = offlineCart['items'] as List<dynamic>? ?? [];
    final subtotal = (offlineCart['subtotal'] as num?)?.toDouble() ?? 0.0;

    state = state.copyWith(
      isLoading: false,
      items: items,
      subtotal: subtotal,
      isOfflineData: true,
    );
  }

  // Fungsi untuk menambah produk ke keranjang
  Future<bool> addToCart({
    required int productId,
    required int quantity,
    required String unit,
    Map<String, dynamic>? productData,
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
          'unit': unit,
        },
      ).timeout(const Duration(seconds: 8));

      final responseData = json.decode(response.body);

      if (response.statusCode == 200 && responseData['success'] == true) {
        await fetchCart();
        return true;
      }
      return false;
    } catch (e) {
      debugPrint('Koneksi offline saat addToCart: $e');
      // Jika offline dan ada data produk, simpan draf ke keranjang lokal
      if (productData != null) {
        final currentItems = List<dynamic>.from(state.items);
        final num price = (unit == 'karton')
            ? (num.tryParse(productData['harga']?.toString() ?? '0') ?? 0)
            : (num.tryParse(productData['harga_pcs']?.toString() ?? '0') ?? 0);

        final localItem = {
          'id': DateTime.now().millisecondsSinceEpoch,
          'product_id': productId,
          'product_name': productData['nama_produk'] ?? 'Produk Wowin',
          'quantity': quantity,
          'unit': unit,
          'price': price,
          'product': productData,
        };

        currentItems.add(localItem);
        double newSubtotal = 0.0;
        for (var it in currentItems) {
          final q = double.tryParse((it['quantity'] ?? 0).toString()) ?? 0.0;
          final p = double.tryParse((it['price'] ?? 0).toString()) ?? 0.0;
          newSubtotal += (q * p);
        }

        await CacheService.saveOfflineCart(currentItems, newSubtotal);
        state = state.copyWith(items: currentItems, subtotal: newSubtotal, isOfflineData: true);
        return true;
      }
      return false;
    }
  }

  // Fungsi untuk memperbarui kuantitas produk di keranjang (+ / -)
  Future<bool> updateQuantity({
    required int cartId,
    required int newQuantity,
  }) async {
    // 1. Update state & cache lokal secara instan (Optimistic UI)
    final currentItems = List<dynamic>.from(state.items);
    final index = currentItems.indexWhere((item) => item['id'] == cartId);

    if (index != -1) {
      if (newQuantity <= 0) {
        currentItems.removeAt(index);
      } else {
        final updatedItem = Map<String, dynamic>.from(currentItems[index]);
        updatedItem['quantity'] = newQuantity;
        currentItems[index] = updatedItem;
      }

      double newSubtotal = 0.0;
      for (var it in currentItems) {
        final q = double.tryParse((it['quantity'] ?? 0).toString()) ?? 0.0;
        final p = double.tryParse((it['price'] ?? 0).toString()) ?? 0.0;
        newSubtotal += (q * p);
      }

      await CacheService.saveOfflineCart(currentItems, newSubtotal);
      state = state.copyWith(items: currentItems, subtotal: newSubtotal);
    }

    // 2. Sinkronkan ke server jika ada jaringan
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');
      if (token == null) return true;

      final url = Uri.parse('$baseUrl/cart/$cartId');
      final response = await http.put(
        url,
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: {
          'quantity': newQuantity.toString(),
        },
      ).timeout(const Duration(seconds: 5));

      final responseData = json.decode(response.body);

      if (response.statusCode == 200 && responseData['success'] == true) {
        final List<dynamic> serverItems = responseData['data'] ?? [];
        final double serverSubtotal = (responseData['subtotal'] ?? 0).toDouble();
        await CacheService.saveOfflineCart(serverItems, serverSubtotal);
        state = state.copyWith(items: serverItems, subtotal: serverSubtotal, isOfflineData: false);
        return true;
      }
      return true; // Tetap berhasil di lokal
    } catch (e) {
      debugPrint('Sinkronisasi update kuantitas ke server ditunda (mode offline): $e');
      return true; // Berhasil di lokal
    }
  }

  // Fungsi untuk menghapus 1 item dari keranjang
  Future<bool> removeItem({required int cartId}) async {
    // 1. Hapus dari state & cache lokal instan
    final currentItems = List<dynamic>.from(state.items);
    currentItems.removeWhere((item) => item['id'] == cartId);

    double newSubtotal = 0.0;
    for (var it in currentItems) {
      final q = double.tryParse((it['quantity'] ?? 0).toString()) ?? 0.0;
      final p = double.tryParse((it['price'] ?? 0).toString()) ?? 0.0;
      newSubtotal += (q * p);
    }

    await CacheService.saveOfflineCart(currentItems, newSubtotal);
    state = state.copyWith(items: currentItems, subtotal: newSubtotal);

    // 2. Sinkronkan penghapusan ke server
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');
      if (token == null) return true;

      final url = Uri.parse('$baseUrl/cart/$cartId');
      final response = await http.delete(
        url,
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      ).timeout(const Duration(seconds: 5));

      final responseData = json.decode(response.body);

      if (response.statusCode == 200 && responseData['success'] == true) {
        final List<dynamic> serverItems = responseData['data'] ?? [];
        final double serverSubtotal = (responseData['subtotal'] ?? 0).toDouble();
        await CacheService.saveOfflineCart(serverItems, serverSubtotal);
        state = state.copyWith(items: serverItems, subtotal: serverSubtotal, isOfflineData: false);
      }
      return true;
    } catch (e) {
      debugPrint('Penghapusan item di server ditunda (mode offline): $e');
      return true;
    }
  }

  // Fungsi untuk mengosongkan seluruh keranjang
  Future<bool> clearCart() async {
    await CacheService.clearOfflineCart();
    state = state.copyWith(items: [], subtotal: 0.0);

    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');
      if (token == null) return true;

      final url = Uri.parse('$baseUrl/cart/clear/all');
      await http.delete(
        url,
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      ).timeout(const Duration(seconds: 5));
      return true;
    } catch (_) {
      return true;
    }
  }
}

// 4. Provider untuk dipanggil dari UI
final cartProvider = NotifierProvider<CartNotifier, CartState>(() {
  return CartNotifier();
});