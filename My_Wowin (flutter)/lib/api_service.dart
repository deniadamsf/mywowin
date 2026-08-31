import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;

class ApiService {
  // Mengarah langsung ke API yang tadi kita buat di hosting
  static const String baseUrl = 'https://mywowin.com/api';

  Future<Map<String, dynamic>> login(String username, String password) async {
    final url = Uri.parse('$baseUrl/login');

    try {
      final response = await http.post(
        url,
        headers: {
          'Accept': 'application/json', // Memaksa Laravel membalas dengan JSON
        },
        body: {
          'username': username,
          'password': password,
        },
      );

      final responseData = json.decode(response.body);

      if (response.statusCode == 200) {
        // Jika login berhasil dan token didapatkan
        debugPrint('Token: ${responseData['token']}');
        return {
          'success': true,
          'message': responseData['message'],
          'token': responseData['token'],
        };
      } else {
        // Jika password salah atau akun belum aktif
        return {
          'success': false,
          'message': responseData['message'] ?? 'Login Gagal',
        };
      }
    } catch (e) {
      // Jika internet putus atau server down
      return {
        'success': false,
        'message': 'Gagal terhubung ke server. Periksa koneksi internet.',
      };
    }
  }
  // Fungsi untuk mengambil data Katalog Publik (Tanpa Token)
  Future<Map<String, dynamic>> fetchCatalog() async {
    final url = Uri.parse('$baseUrl/catalog');

    try {
      final response = await http.get(
        url,
        headers: {
          'Accept': 'application/json', // Memastikan respons dalam format JSON
        },
      );

      final responseData = json.decode(response.body);

      if (response.statusCode == 200 && responseData['success'] == true) {
        return {
          'success': true,
          'data': responseData['data'], // Berisi hero, categories, bundlings, dan products
        };
      } else {
        return {
          'success': false,
          'message': responseData['message'] ?? 'Gagal memuat katalog',
        };
      }
    } catch (e) {
      debugPrint('Error Fetch Catalog: $e');
      return {
        'success': false,
        'message': 'Gagal terhubung ke server. Periksa koneksi internet Anda.',
      };
    }
  }
}