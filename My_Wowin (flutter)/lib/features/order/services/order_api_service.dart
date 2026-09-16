import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/api_constants.dart';

class OrderApiService {
  /// Unggah file bukti transfer bank untuk pesanan tertentu
  static Future<Map<String, dynamic>> uploadPaymentProof({
    required int orderId,
    required File imageFile,
  }) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');

      if (token == null) {
        return {
          'success': false,
          'message': 'Sesi login telah berakhir. Silakan login kembali.',
        };
      }

      final uri = Uri.parse('$baseUrl/orders/$orderId/upload-proof');
      final request = http.MultipartRequest('POST', uri);

      request.headers['Authorization'] = 'Bearer $token';
      request.headers['Accept'] = 'application/json';

      request.files.add(
        await http.MultipartFile.fromPath('bukti_transfer', imageFile.path),
      );

      final streamedResponse = await request.send().timeout(const Duration(seconds: 25));
      final response = await http.Response.fromStream(streamedResponse);

      final data = json.decode(response.body);

      if (response.statusCode == 200 && data['status'] == 'success') {
        return {
          'success': true,
          'message': data['message'] ?? 'Bukti transfer berhasil diunggah!',
          'data': data['data'],
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal mengunggah bukti transfer.',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan jaringan atau ukuran file terlalu besar.',
      };
    }
  }

  /// Ambil data pesanan terbaru milik pengguna
  static Future<Map<String, dynamic>?> fetchLatestOrder(int orderId) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');
      if (token == null) return null;

      final res = await http.get(
        Uri.parse('$baseUrl/orders'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      ).timeout(const Duration(seconds: 8));

      if (res.statusCode == 200) {
        final body = json.decode(res.body);
        final list = body['data'] as List<dynamic>? ?? [];
        return list.firstWhere(
          (o) => (o['id'] ?? '').toString() == orderId.toString(),
          orElse: () => null,
        );
      }
    } catch (_) {}
    return null;
  }

  /// Ambil pelacakan live pengiriman J&T Express secara langsung
  static Future<Map<String, dynamic>> fetchLiveTracking(int orderId) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');
      if (token == null) {
        return {
          'success': false,
          'message': 'Sesi login telah berakhir. Silakan login kembali.',
        };
      }

      final res = await http.get(
        Uri.parse('$baseUrl/orders/$orderId/track-live'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      ).timeout(const Duration(seconds: 15));

      final body = json.decode(res.body);
      if (res.statusCode == 200 && (body['status'] == 'success' || body['success'] == true)) {
        return {
          'success': true,
          'data': body['data'],
        };
      } else {
        return {
          'success': false,
          'message': body['message'] ?? 'Informasi pelacakan belum tersedia.',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Gagal terhubung ke server tracking. Periksa koneksi internet Anda.',
      };
    }
  }
}
