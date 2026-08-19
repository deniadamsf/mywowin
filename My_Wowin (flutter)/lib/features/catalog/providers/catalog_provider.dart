import 'dart:convert';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;

// 1. URL Konstanta
const String baseUrl = 'https://mywowin.com/api';

// 2. Provider untuk mengambil data dari Laravel
// FutureProvider otomatis mengurus status Loading dan Error untuk kita!
final catalogProvider = FutureProvider<Map<String, dynamic>>((ref) async {
  final url = Uri.parse('$baseUrl/catalog');

  final response = await http.get(
    url,
    headers: {'Accept': 'application/json'},
  );

  final responseData = json.decode(response.body);

  if (response.statusCode == 200 && responseData['success'] == true) {
    return responseData['data']; // Mengembalikan object hero, categories, products
  } else {
    throw Exception(responseData['message'] ?? 'Gagal memuat katalog');
  }
});