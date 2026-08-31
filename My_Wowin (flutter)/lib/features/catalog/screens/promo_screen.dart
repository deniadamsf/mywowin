import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../../../core/theme/wowin_theme.dart';
import '../../../core/widgets/wowin_cached_image.dart';
import '../../../core/widgets/offline_indicator.dart';
import 'promo_detail_screen.dart';

class PromoScreen extends StatelessWidget {
  final List<dynamic> bundlings;

  const PromoScreen({super.key, required this.bundlings});

  @override
  Widget build(BuildContext context) {
    final currencyFormatter = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

    return Scaffold(
      backgroundColor: WowinColors.background,
      appBar: WowinAppBar.standard(title: 'Promo Spesial Wowin'),
      body: Column(
        children: [
          const OfflineBanner(),
          Expanded(
            child: bundlings.isEmpty
                ? Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(Icons.local_offer_outlined, size: 70, color: Colors.grey.shade300),
                        const SizedBox(height: 14),
                        const Text(
                          'Belum ada promo spesial saat ini.',
                          style: TextStyle(color: WowinColors.textSecondary, fontSize: 15, fontWeight: FontWeight.w500),
                        ),
                      ],
                    ),
                  )
                : ListView.builder(
                    padding: const EdgeInsets.all(18),
                    itemCount: bundlings.length,
                    itemBuilder: (context, index) {
                      final bundling = bundlings[index];
                      String imageUrl = 'https://mywowin.com/storage/${bundling['barang_bundling']}';
                      num price = num.tryParse(bundling['price']?.toString() ?? '0') ?? 0;
                      num priceBefore = num.tryParse(bundling['price_before']?.toString() ?? '0') ?? 0;

                      return Container(
                        margin: const EdgeInsets.only(bottom: 22),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(20),
                          border: Border.all(color: WowinColors.promoRedSoft, width: 1.2),
                          boxShadow: [
                            BoxShadow(
                              color: WowinColors.promoRed.withValues(alpha: 0.08),
                              blurRadius: 16,
                              offset: const Offset(0, 8),
                            )
                          ],
                        ),
                        child: ClipRRect(
                          borderRadius: BorderRadius.circular(20),
                          child: InkWell(
                            onTap: () => Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) => PromoDetailScreen(bundling: bundling),
                              ),
                            ),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                // --- GAMBAR BANNER PROMO (ASPECT RATIO 16:9) ---
                                Stack(
                                  children: [
                                    AspectRatio(
                                      aspectRatio: 16 / 9,
                                      child: WowinCachedImage(
                                        imageUrl: imageUrl,
                                        width: double.infinity,
                                        fit: BoxFit.cover,
                                        errorWidget: Container(
                                          color: Colors.grey.shade100,
                                          child: const Icon(Icons.image, size: 50, color: Colors.grey),
                                        ),
                                      ),
                                    ),
                                    Positioned(
                                      top: 14,
                                      left: 14,
                                      child: Container(
                                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                                        decoration: BoxDecoration(
                                          gradient: WowinGradients.superDeal,
                                          borderRadius: BorderRadius.circular(8),
                                          boxShadow: [
                                            BoxShadow(
                                              color: WowinColors.promoRed.withValues(alpha: 0.4),
                                              blurRadius: 8,
                                              offset: const Offset(0, 3),
                                            )
                                          ],
                                        ),
                                        child: const Row(
                                          mainAxisSize: MainAxisSize.min,
                                          children: [
                                            Icon(Icons.local_fire_department, color: Colors.white, size: 14),
                                            SizedBox(width: 4),
                                            Text(
                                              'SUPER DEAL',
                                              style: TextStyle(
                                                color: Colors.white,
                                                fontSize: 10,
                                                fontWeight: FontWeight.w900,
                                                letterSpacing: 0.8,
                                              ),
                                            ),
                                          ],
                                        ),
                                      ),
                                    ),
                                  ],
                                ),

                                // --- DETAIL DESKRIPSI & HARGA ---
                                Padding(
                                  padding: const EdgeInsets.all(16.0),
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        bundling['nama_bundling'] ?? 'Promo Menarik',
                                        style: const TextStyle(
                                          fontSize: 17,
                                          fontWeight: FontWeight.bold,
                                          color: WowinColors.textPrimary,
                                          height: 1.25,
                                        ),
                                      ),
                                      const SizedBox(height: 14),

                                      Row(
                                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                        crossAxisAlignment: CrossAxisAlignment.end,
                                        children: [
                                          Column(
                                            crossAxisAlignment: CrossAxisAlignment.start,
                                            children: [
                                              if (priceBefore > 0 && priceBefore > price)
                                                Text(
                                                  currencyFormatter.format(priceBefore),
                                                  style: const TextStyle(
                                                    fontSize: 12,
                                                    decoration: TextDecoration.lineThrough,
                                                    color: WowinColors.textMuted,
                                                  ),
                                                ),
                                              Text(
                                                currencyFormatter.format(price),
                                                style: const TextStyle(
                                                  fontWeight: FontWeight.w900,
                                                  fontSize: 20,
                                                  color: WowinColors.promoRed,
                                                ),
                                              ),
                                            ],
                                          ),
                                          ElevatedButton.icon(
                                            onPressed: () => Navigator.push(
                                              context,
                                              MaterialPageRoute(
                                                builder: (context) => PromoDetailScreen(bundling: bundling),
                                              ),
                                            ),
                                            style: ElevatedButton.styleFrom(
                                              backgroundColor: WowinColors.primary,
                                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                                              padding: const EdgeInsets.symmetric(horizontal: 18, vertical: 11),
                                              elevation: 2,
                                            ),
                                            icon: const Icon(Icons.shopping_bag_outlined, color: Colors.white, size: 16),
                                            label: const Text(
                                              'Lihat Promo',
                                              style: TextStyle(
                                                color: Colors.white,
                                                fontWeight: FontWeight.bold,
                                                fontSize: 13,
                                              ),
                                            ),
                                          ),
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
          ),
        ],
      ),
    );
  }
}