import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:url_launcher/url_launcher.dart';

class OrderDetailScreen extends StatelessWidget {
  final dynamic order;
  const OrderDetailScreen({super.key, required this.order});

  static const Color wowinGreen = Color(0xFF1B5E20);
  static const wowinGradient = LinearGradient(
    colors: [Color(0xFF0A4A1A), Color(0xFF2E7D32)],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  Color _getStatusColor(String status) {
    switch (status.toLowerCase()) {
      case 'menunggu pembayaran':
      case 'pending':
      case 'belum bayar':
        return Colors.orange;
      case 'lunas':
      case 'dikirim':
      case 'selesai':
        return wowinGreen;
      case 'dibatalkan':
      case 'batal':
      case 'failed':
        return Colors.red;
      default:
        return Colors.green;
    }
  }

  Future<void> _contactAdmin(String invoice) async {
    const String waNumber = '6281216301220'; // Ganti dengan nomor WA CS Wowin
    final String text = 'Halo admin Wowin Food, saya mau konfirmasi mengenai pesanan saya dengan nomor invoice *$invoice*.';
    final Uri url = Uri.parse('https://wa.me/$waNumber?text=${Uri.encodeComponent(text)}');

    if (await canLaunchUrl(url)) {
      await launchUrl(url, mode: LaunchMode.externalApplication);
    }
  }

  @override
  Widget build(BuildContext context) {
    final statusColor = _getStatusColor(order['status'] ?? 'Pending');

    // Asumsi API mengirimkan array item di dalam 'items' atau 'order_items' atau 'details'
    final List<dynamic> orderItems = order['items'] ?? order['order_items'] ?? order['details'] ?? [];

    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        elevation: 0,
        iconTheme: const IconThemeData(color: Colors.white),
        flexibleSpace: Container(decoration: const BoxDecoration(gradient: wowinGradient)),
        title: const Text('Detail Pesanan', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 18)),
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // --- HEADER: INVOICE & STATUS (Sesuai Screenshot Web) ---
            Container(
              color: Colors.white,
              padding: const EdgeInsets.all(16),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('NOMOR PESANAN', style: TextStyle(fontSize: 10, color: Colors.grey, fontWeight: FontWeight.bold)),
                      const SizedBox(height: 4),
                      Text(order['invoice_number'] ?? '#INV-UNKNOWN', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                    ],
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(color: statusColor.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(6)),
                    child: Text(
                      (order['status'] ?? 'Menunggu Pembayaran').toUpperCase(),
                      style: TextStyle(color: statusColor, fontSize: 11, fontWeight: FontWeight.bold),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 8),

            // --- DAFTAR PRODUK (Sesuai Screenshot Web) ---
            Container(
              color: Colors.white,
              child: ListView.separated(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                itemCount: orderItems.isEmpty ? 1 : orderItems.length,
                separatorBuilder: (context, index) => const Divider(height: 1, indent: 16, endIndent: 16),
                itemBuilder: (context, index) {
                  if (orderItems.isEmpty) {
                    return const Padding(
                      padding: EdgeInsets.all(16.0),
                      child: Text('Detail item tidak tersedia dari server.', style: TextStyle(color: Colors.grey, fontStyle: FontStyle.italic)),
                    );
                  }

                  final item = orderItems[index];
                  final product = item['product'];
                  final bundling = item['bundling'];

                  String itemName = item['product_name'] ?? product?['nama_produk'] ?? bundling?['nama_bundling'] ?? 'Produk Wowin';
                  String unit = (item['unit'] ?? 'pcs').toString().toUpperCase();
                  String qty = (item['quantity'] ?? item['qty'] ?? 1).toString();
                  num price = num.tryParse(item['price']?.toString() ?? '0') ?? 0;

                  // Label nama dengan tambahan unit, contoh: "Kecap Manis Wowin Pouch (Pcs)"
                  String displayName = '$itemName (${unit[0].toUpperCase()}${unit.substring(1).toLowerCase()})';

                  String imageUrl = 'https://via.placeholder.com/150';
                  if (product != null && product['images'] != null && product['images'].isNotEmpty) {
                    imageUrl = 'https://mywowin.com/storage/${product['images'][0]['image_url']}';
                  } else if (bundling != null && bundling['barang_bundling'] != null) {
                    imageUrl = 'https://mywowin.com/storage/${bundling['barang_bundling']}';
                  }

                  bool isKarton = unit == 'KARTON';

                  return Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Gambar Produk
                        Container(
                          decoration: BoxDecoration(border: Border.all(color: Colors.grey.shade200), borderRadius: BorderRadius.circular(8)),
                          child: ClipRRect(
                            borderRadius: BorderRadius.circular(8),
                            child: Image.network(imageUrl, width: 60, height: 60, fit: BoxFit.cover, errorBuilder: (ctx, err, stack) => const Icon(Icons.image, color: Colors.grey, size: 40)),
                          ),
                        ),
                        const SizedBox(width: 16),

                        // Detail Nama & Qty
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(displayName, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                              const SizedBox(height: 8),
                              // Kotak Qty seperti di web
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                decoration: BoxDecoration(
                                  color: isKarton ? Colors.orange.shade50 : Colors.grey.shade100,
                                  borderRadius: BorderRadius.circular(4),
                                ),
                                child: Text(
                                  '$qty $unit',
                                  style: TextStyle(
                                      fontSize: 10,
                                      fontWeight: FontWeight.bold,
                                      color: isKarton ? Colors.orange.shade800 : Colors.grey.shade800
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),

                        // Harga
                        Text(
                            'Rp ${NumberFormat('#,###', 'id_ID').format(price * int.parse(qty))}',
                            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: wowinGreen)
                        ),
                      ],
                    ),
                  );
                },
              ),
            ),
          ],
        ),
      ),

      // --- FOOTER: TOTAL BAYAR & TOMBOL WA (Sesuai Screenshot Web) ---
      bottomNavigationBar: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 10, offset: const Offset(0, -4))],
        ),
        child: SafeArea(
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('PT. WOWIN PURNOMO PUTERA', style: TextStyle(fontSize: 10, color: Colors.grey, fontWeight: FontWeight.bold, letterSpacing: 0.5)),
                  const SizedBox(height: 8),
                  const Text('TOTAL WAJIB BAYAR', style: TextStyle(fontSize: 10, color: Colors.grey)),
                  Text(
                    'Rp ${NumberFormat('#,###', 'id_ID').format(num.tryParse(order['total']?.toString() ?? '0'))}',
                    style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.black),
                  ),
                ],
              ),
              ElevatedButton.icon(
                onPressed: () => _contactAdmin(order['invoice_number'] ?? ''),
                icon: const Icon(Icons.chat, color: Colors.white, size: 20),
                label: const Text('WhatsApp', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)), // --- UBAH TEKS DI SINI ---
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF1B5E20), // Warna hijau khas WhatsApp
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}