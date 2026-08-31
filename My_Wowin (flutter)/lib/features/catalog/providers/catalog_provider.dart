import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import '../../../core/constants/api_constants.dart';
import '../../../core/services/cache_service.dart';

// Provider Katalog dengan Pola Cache-First (Offline-First Resilient)
// Membaca cache lokal terlebih dahulu untuk kecepatan instan, lalu memperbarui data saat online
final catalogProvider = FutureProvider<Map<String, dynamic>>((ref) async {
  // 1. Periksa ketersediaan cache lokal
  final cachedData = await CacheService.getCatalog();

  try {
    final url = Uri.parse('$baseUrl/catalog');
    final response = await http.get(
      url,
      headers: {'Accept': 'application/json'},
    ).timeout(const Duration(seconds: 8));

    if (response.statusCode == 200) {
      final responseData = json.decode(response.body);
      if (responseData['success'] == true && responseData['data'] != null) {
        final liveData = responseData['data'] as Map<String, dynamic>;
        // Simpan data terbaru ke cache lokal
        await CacheService.saveCatalog(liveData);
        return liveData;
      }
    }
  } catch (e) {
    debugPrint('Gagal terhubung ke API katalog live, menggunakan cache lokal: $e');
  }

  // 2. Jika jaringan offline atau server down, kembalikan data cache
  if (cachedData != null) {
    return cachedData;
  }

  // 3. Hanya lempar error jika belum pernah ada cache sama sekali (buka perdana tanpa internet)
  throw Exception('Katalog belum tersimpan. Harap hubungkan internet untuk sinkronisasi awal.');
});