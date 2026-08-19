import 'package:flutter/material.dart';
import 'promo_detail_screen.dart';

class PromoScreen extends StatelessWidget {
  final List<dynamic> bundlings;

  const PromoScreen({super.key, required this.bundlings});

  static const Color wowinGreen = Color(0xFF1B5E20);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[50],
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
        title: const Text('Promo Spesial WOWIN', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 18)),
      ),
      body: bundlings.isEmpty
          ? const Center(
        child: Text(
          'Yah, belum ada promo spesial saat ini.',
          style: TextStyle(color: Colors.grey, fontSize: 16),
        ),
      )
          : ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: bundlings.length,
        itemBuilder: (context, index) {
          final bundling = bundlings[index];
          String imageUrl = 'https://mywowin.com/storage/${bundling['barang_bundling']}';
          num price = num.tryParse(bundling['price']?.toString() ?? '0') ?? 0;
          num priceBefore = num.tryParse(bundling['price_before']?.toString() ?? '0') ?? 0;

          return Container(
            margin: const EdgeInsets.only(bottom: 20),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: Colors.red.shade100, width: 1.5),
              boxShadow: [
                BoxShadow(color: Colors.red.withValues(alpha: 0.1), blurRadius: 10, offset: const Offset(0, 4))
              ],
            ),
            child: ClipRRect(
              borderRadius: BorderRadius.circular(16),
              child: InkWell(
                onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => PromoDetailScreen(bundling: bundling))),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // --- Gambar Promo Lebih Besar & Full Width ---
                    Image.network(
                      imageUrl,
                      width: double.infinity,
                      height: 200,
                      fit: BoxFit.cover,
                      errorBuilder: (ctx, err, stack) => Container(height: 200, color: Colors.grey[200], child: const Icon(Icons.image, size: 50, color: Colors.grey)),
                    ),
                    Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                            decoration: BoxDecoration(color: Colors.red[50], borderRadius: BorderRadius.circular(6)),
                            child: Text('SUPER DEAL 🔥', style: TextStyle(color: Colors.red[800], fontSize: 10, fontWeight: FontWeight.bold)),
                          ),
                          const SizedBox(height: 10),
                          Text(
                            bundling['nama_bundling'] ?? 'Promo Menarik',
                            style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, height: 1.2),
                          ),
                          const SizedBox(height: 16),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            crossAxisAlignment: CrossAxisAlignment.end,
                            children: [
                              Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  if (priceBefore > 0 && priceBefore > price)
                                    Text('Rp ${priceBefore.toStringAsFixed(0)}', style: const TextStyle(fontSize: 13, decoration: TextDecoration.lineThrough, color: Colors.grey)),
                                  Text('Rp ${price.toStringAsFixed(0)}', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 20, color: Colors.red)),
                                ],
                              ),
                              ElevatedButton(
                                onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (context) => PromoDetailScreen(bundling: bundling))),
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: Colors.red,
                                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                                  padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                                  elevation: 2,
                                ),
                                child: const Text('Beli Sekarang', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                              )
                            ],
                          ),
                        ],
                      ),
                    )
                  ],
                ),
              ),
            ),
          );
        },
      ),
    );
  }
}