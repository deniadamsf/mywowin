import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../providers/catalog_provider.dart';
import '../../auth/providers/auth_provider.dart';
import '../../auth/screens/login_screen.dart';

class PromoDetailScreen extends ConsumerStatefulWidget {
  final dynamic bundling;
  const PromoDetailScreen({super.key, required this.bundling});

  @override
  ConsumerState<PromoDetailScreen> createState() => _PromoDetailScreenState();
}

class _PromoDetailScreenState extends ConsumerState<PromoDetailScreen> {
  static const Color wowinGreen = Color(0xFF1B5E20);
  int _qty = 1;

  // Fungsi untuk membuang tag HTML (seperti <p>, <b>) dari Rich Text Editor Laravel
  String _parseHtmlString(String htmlString) {
    RegExp exp = RegExp(r"<[^>]*>", multiLine: true, caseSensitive: true);
    return htmlString.replaceAll(exp, '').replaceAll('&nbsp;', ' ').trim();
  }

  @override
  Widget build(BuildContext context) {
    final bundling = widget.bundling;
    String imageUrl = 'https://mywowin.com/storage/${bundling['barang_bundling']}';
    num price = num.tryParse(bundling['price']?.toString() ?? '0') ?? 0;
    num priceBefore = num.tryParse(bundling['price_before']?.toString() ?? '0') ?? 0;

    // Hitung persentase diskon
    int discountPercent = 0;
    if (priceBefore > 0 && priceBefore > price) {
      discountPercent = (((priceBefore - price) / priceBefore) * 100).round();
    }

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        elevation: 0,
        backgroundColor: Colors.transparent,
        iconTheme: const IconThemeData(color: Colors.white),
        flexibleSpace: Container(
          decoration: const BoxDecoration(
            gradient: LinearGradient(
              colors: [Color(0xFF0A4A1A), Color(0xFF2E7D32)],
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
            ),
          ),
        ),
        title: const Text('Detail Promo', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 18)),
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // --- GAMBAR PROMO ---
            Container(
              width: double.infinity,
              color: Colors.grey[50],
              padding: const EdgeInsets.all(16),
              child: Image.network(
                imageUrl,
                fit: BoxFit.contain,
                height: 300,
                errorBuilder: (ctx, err, stack) => const Icon(Icons.image, size: 100, color: Colors.grey),
              ),
            ),

            // --- INFO PROMO ---
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(bundling['nama_bundling'] ?? 'Paket Promo', style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold, height: 1.2)),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      Text('Rp ${price.toStringAsFixed(0)}', style: const TextStyle(fontSize: 26, fontWeight: FontWeight.bold, color: Colors.red)),
                      const SizedBox(width: 12),
                      if (priceBefore > 0 && priceBefore > price) ...[
                        Text('Rp ${priceBefore.toStringAsFixed(0)}', style: const TextStyle(fontSize: 14, decoration: TextDecoration.lineThrough, color: Colors.grey)),
                        const SizedBox(width: 8),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                          decoration: BoxDecoration(color: Colors.green[50], borderRadius: BorderRadius.circular(4)),
                          child: Text('Hemat $discountPercent%', style: TextStyle(color: Colors.green[700], fontSize: 11, fontWeight: FontWeight.bold)),
                        ),
                      ]
                    ],
                  ),
                  const SizedBox(height: 16),

                  // --- INFO TOKO (Mirip Web) ---
                  Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                        color: Colors.white,
                        border: Border.all(color: Colors.grey.shade200),
                        borderRadius: BorderRadius.circular(8)
                    ),
                    child: Row(
                      children: [
                        const Icon(Icons.storefront, color: Colors.grey, size: 28),
                        const SizedBox(width: 12),
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: const [
                            Text('PT. Wowin Purnomo Putera', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                            Text('Pengiriman pada hari yang sama.', style: TextStyle(fontSize: 11, color: Colors.grey)),
                          ],
                        )
                      ],
                    ),
                  ),

                  const SizedBox(height: 24),
                  const Text('Syarat & Ketentuan', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  Text(
                    // Memanggil field syarat_ketentuan atau deskripsi dari API
                    _parseHtmlString(bundling['syarat_ketentuan']?.toString() ?? bundling['deskripsi']?.toString() ?? 'Tidak ada syarat & ketentuan khusus.'),
                    style: const TextStyle(fontSize: 14, color: Colors.black87, height: 1.5),
                  ),
                  const SizedBox(height: 40),
                ],
              ),
            )
          ],
        ),
      ),

      // --- TOMBOL BELI STICKY DI BAWAH ---
      bottomNavigationBar: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 10, offset: const Offset(0, -4))],
        ),
        child: Row(
          children: [
            // Jumlah
            Container(
              height: 48,
              decoration: BoxDecoration(border: Border.all(color: Colors.grey.shade300), borderRadius: BorderRadius.circular(8)),
              child: Row(
                children: [
                  IconButton(onPressed: () => setState(() => _qty > 1 ? _qty-- : null), icon: const Icon(Icons.remove, size: 20)),
                  Text('$_qty', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                  IconButton(onPressed: () => setState(() => _qty++), icon: const Icon(Icons.add, size: 20)),
                ],
              ),
            ),
            const SizedBox(width: 16),
            // Tombol Add to Cart
            Expanded(
              child: ElevatedButton(
                onPressed: () async {
                  final authState = ref.read(authProvider);

                  // 1. Wajib Login
                  if (!authState.isAuthenticated) {
                    ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Silakan login terlebih dahulu!')));
                    Navigator.push(context, MaterialPageRoute(builder: (context) => const LoginScreen()));
                    return;
                  }

                  // 2. Munculkan Loading
                  showDialog(context: context, barrierDismissible: false, builder: (context) => const Center(child: CircularProgressIndicator(color: wowinGreen)));

                  try {
                    // Gunakan id_bundling, atau fallback ke id jika Laravel mengirimkan format yang berbeda
                    final idBundling = bundling['id_bundling'] ?? bundling['id'];

                    final response = await http.post(
                      Uri.parse('$baseUrl/carts/addBundling'),
                      headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ${authState.token}',
                      },
                      body: json.encode({
                        'bundling_id': idBundling,
                        'quantity': _qty,
                      }),
                    );

                    if (mounted) Navigator.pop(context); // Tutup Loading

                    if (response.statusCode == 200 || response.statusCode == 201) {
                      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Yeay! Promo berhasil masuk keranjang!'), backgroundColor: wowinGreen));
                    } else {
                      // Tangkap pesan error ASLI dari API Laravel agar kita tahu penyebab pastinya
                      String errorMessage = 'Error ${response.statusCode}: Gagal masuk keranjang.';
                      try {
                        final errorData = json.decode(response.body);
                        errorMessage = errorData['message'] ?? errorMessage;
                      } catch (_) {}

                      ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(content: Text(errorMessage), backgroundColor: Colors.red, duration: const Duration(seconds: 4))
                      );
                    }
                  } catch (e) {
                    if (mounted) Navigator.pop(context);
                    ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Error jaringan: $e'), backgroundColor: Colors.red));
                  }
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.red,
                  padding: const EdgeInsets.symmetric(vertical: 14),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                ),
                child: const Text('+ Keranjang', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white)),
              ),
            )
          ],
        ),
      ),
    );
  }
}