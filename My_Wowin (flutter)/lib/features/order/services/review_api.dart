import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/api_constants.dart';

class ReviewApi {
  static Future<String?> _getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }

  /// Mengirim ulasan & rating untuk pesanan
  static Future<Map<String, dynamic>> submitOrderReview({
    required int orderId,
    required int rating,
    String? komentar,
    List<String>? tags,
    List<String>? photosBase64,
    bool isAnonymous = false,
  }) async {
    try {
      final token = await _getToken();
      if (token == null) {
        return {'success': false, 'message': 'Sesi login tidak ditemukan. Silakan login kembali.'};
      }

      final response = await http.post(
        Uri.parse('$baseUrl/orders/$orderId/reviews'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode({
          'rating': rating,
          'komentar': komentar,
          'tags': tags ?? [],
          'foto': photosBase64 ?? [],
          'is_anonymous': isAnonymous,
        }),
      ).timeout(const Duration(seconds: 15));

      final data = jsonDecode(response.body);
      if (response.statusCode == 200 || response.statusCode == 201) {
        return {
          'success': true,
          'message': data['message'] ?? 'Ulasan berhasil disimpan.',
          'data': data['data'],
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal mengirim ulasan.',
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Terjadi kesalahan: $e'};
    }
  }

  /// Mengecek status ulasan untuk pesanan tertentu
  static Future<Map<String, dynamic>> getOrderReview(int orderId) async {
    try {
      final token = await _getToken();
      if (token == null) {
        return {'success': false, 'has_reviewed': false};
      }

      final response = await http.get(
        Uri.parse('$baseUrl/orders/$orderId/reviews'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      }
      return {'success': false, 'has_reviewed': false};
    } catch (e) {
      return {'success': false, 'has_reviewed': false};
    }
  }

  /// Mengambil ulasan publik untuk suatu produk
  static Future<Map<String, dynamic>> getProductReviews(int productId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/products/$productId/reviews'),
        headers: {'Accept': 'application/json'},
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      }
      return {'success': false};
    } catch (e) {
      return {'success': false};
    }
  }
}
