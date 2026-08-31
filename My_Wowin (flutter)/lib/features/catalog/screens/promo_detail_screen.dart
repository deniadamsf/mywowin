import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:intl/intl.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/theme/wowin_theme.dart';
import '../../../core/widgets/wowin_cached_image.dart';
import '../../auth/providers/auth_provider.dart';
import '../../auth/screens/login_screen.dart';

class PromoDetailScreen extends ConsumerStatefulWidget {
  final dynamic bundling;
  const PromoDetailScreen({super.key, required this.bundling});

  @override
  ConsumerState<PromoDetailScreen> createState() => _PromoDetailScreenState();
}

class _PromoDetailScreenState extends ConsumerState<PromoDetailScreen> {
  static const Color wowinGreen = WowinColors.primary;

  int _qty = 1;
  final NumberFormat _currency = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

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

    int discountPercent = 0;
    if (priceBefore > 0 && priceBefore > price) {
      discountPercent = (((priceBefore - price) / priceBefore) * 100).round();
    }

    return Scaffold(
      backgroundColor: WowinColors.background,
      appBar: WowinAppBar.standard(title: 'Detail Promo Bundling'),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // ==========================================================
            // 1. BANNER PROMO (FIT 16:9 DENGAN TAMPILAN FULL ELEGAN)
            // ==========================================================
            Container(
              width: double.infinity,
              margin: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(16),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.08),
                    blurRadius: 16,
                    offset: const Offset(0, 6),
                  ),
                ],
              ),
              child: ClipRRect(
                borderRadius: BorderRadius.circular(16),
                child: AspectRatio(
                  aspectRatio: 16 / 9,
                  child: WowinCachedImage(
                    imageUrl: imageUrl,
                    fit: BoxFit.cover,
                    errorWidget: Container(
                      color: Colors.grey.shade200,
                      child: const Center(
                        child: Icon(Icons.image, size: 60, color: Colors.grey),
                      ),
                    ),
                  ),
                ),
              ),
            ),

            // ==========================================================
            // 2. KARTU DETAIL PROMO & HARGA
            // ==========================================================
            Container(
              margin: const EdgeInsets.symmetric(horizontal: 16),
              padding: const EdgeInsets.all(18),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(18),
                border: Border.all(color: Colors.grey.shade200),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.03),
                    blurRadius: 10,
                    offset: const Offset(0, 4),
                  ),
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Badge Kategori Promo
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: WowinColors.promoRedSoft,
                      borderRadius: BorderRadius.circular(6),
                    ),
                    child: const Text(
                      'PROMO BUNDLING SPESIAL',
                      style: TextStyle(
                        color: WowinColors.promoRed,
                        fontSize: 10,
                        fontWeight: FontWeight.bold,
                        letterSpacing: 0.5,
                      ),
                    ),
                  ),
                  const SizedBox(height: 10),

                  // Judul Bundling
                  Text(
                    bundling['nama_bundling'] ?? 'Paket Promo',
                    style: const TextStyle(
                      fontSize: 19,
                      fontWeight: FontWeight.bold,
                      height: 1.3,
                      color: WowinColors.textPrimary,
                    ),
                  ),
                  const SizedBox(height: 14),

                  // Baris Harga & Diskon
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.end,
                    children: [
                      Text(
                        _currency.format(price),
                        style: const TextStyle(
                          fontSize: 24,
                          fontWeight: FontWeight.w900,
                          color: WowinColors.promoRed,
                        ),
                      ),
                      if (priceBefore > 0 && priceBefore > price) ...[
                        const SizedBox(width: 10),
                        Text(
                          _currency.format(priceBefore),
                          style: TextStyle(
                            fontSize: 14,
                            decoration: TextDecoration.lineThrough,
                            color: Colors.grey.shade500,
                          ),
                        ),
                        const SizedBox(width: 8),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                          decoration: BoxDecoration(
                            color: Colors.green.shade50,
                            borderRadius: BorderRadius.circular(6),
                            border: Border.all(color: Colors.green.shade200),
                          ),
                          child: Text(
                            'Hemat $discountPercent%',
                            style: TextStyle(
                              color: Colors.green.shade800,
                              fontSize: 11,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ),
                      ],
                    ],
                  ),
                ],
              ),
            ),

            const SizedBox(height: 14),

            // ==========================================================
            // 3. KARTU RESMI DISTRIBUTOR
            // ==========================================================
            Container(
              margin: const EdgeInsets.symmetric(horizontal: 16),
              padding: const EdgeInsets.all(14),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: Colors.grey.shade200),
              ),
              child: Row(
                children: [
                  Container(
                    width: 42,
                    height: 42,
                    decoration: BoxDecoration(
                      color: wowinGreen.withValues(alpha: 0.1),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: const Icon(Icons.verified, color: wowinGreen, size: 22),
                  ),
                  const SizedBox(width: 12),
                  const Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'PT WOWIN PURNOMO PUTERA',
                          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: WowinColors.textPrimary),
                        ),
                        SizedBox(height: 2),
                        Text(
                          'Distributor & Pabrik Resmi • Pengiriman Cepat',
                          style: TextStyle(fontSize: 11, color: WowinColors.textSecondary),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 14),

            // ==========================================================
            // 4. KARTU SYARAT & KETENTUAN
            // ==========================================================
            Container(
              margin: const EdgeInsets.symmetric(horizontal: 16),
              padding: const EdgeInsets.all(18),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(18),
                border: Border.all(color: Colors.grey.shade200),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Row(
                    children: [
                      Icon(Icons.info_outline, size: 18, color: WowinColors.textPrimary),
                      SizedBox(width: 8),
                      Text(
                        'Syarat & Ketentuan Promo',
                        style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: WowinColors.textPrimary),
                      ),
                    ],
                  ),
                  const SizedBox(height: 10),
                  Text(
                    _parseHtmlString(bundling['syarat_ketentuan']?.toString() ?? bundling['deskripsi']?.toString() ?? 'Tidak ada syarat & ketentuan khusus. Promo berlaku selama persediaan masih ada.'),
                    style: const TextStyle(fontSize: 13.5, color: WowinColors.textSecondary, height: 1.5),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 100),
          ],
        ),
      ),

      // ==========================================================
      // 5. STICKY BOTTOM ACTION BAR
      // ==========================================================
      bottomNavigationBar: Container(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.06),
              blurRadius: 12,
              offset: const Offset(0, -3),
            ),
          ],
        ),
        child: SafeArea(
          child: Row(
            children: [
              // Stepper Kuantitas
              Container(
                height: 46,
                decoration: BoxDecoration(
                  border: Border.all(color: Colors.grey.shade300),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Row(
                  children: [
                    IconButton(
                      onPressed: () => setState(() => _qty > 1 ? _qty-- : null),
                      icon: const Icon(Icons.remove, size: 18),
                      color: _qty > 1 ? WowinColors.textPrimary : Colors.grey.shade400,
                    ),
                    Text(
                      '$_qty',
                      style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: WowinColors.textPrimary),
                    ),
                    IconButton(
                      onPressed: () => setState(() => _qty++),
                      icon: const Icon(Icons.add, size: 18),
                      color: WowinColors.primary,
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 14),

              // Tombol Tambah ke Keranjang
              Expanded(
                child: SizedBox(
                  height: 46,
                  child: ElevatedButton(
                    onPressed: () async {
                      final authState = ref.read(authProvider);

                      if (!authState.isAuthenticated) {
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(content: Text('Silakan login terlebih dahulu!')),
                        );
                        Navigator.push(context, MaterialPageRoute(builder: (context) => const LoginScreen()));
                        return;
                      }

                      final messenger = ScaffoldMessenger.of(context);
                      final navigator = Navigator.of(context);

                      showDialog(
                        context: context,
                        barrierDismissible: false,
                        builder: (dlgCtx) => const Center(child: CircularProgressIndicator(color: wowinGreen)),
                      );

                      try {
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

                        navigator.pop();

                        if (response.statusCode == 200 || response.statusCode == 201) {
                          messenger.showSnackBar(
                            const SnackBar(
                              content: Text('Promo bundling berhasil ditambahkan ke keranjang!'),
                              backgroundColor: wowinGreen,
                            ),
                          );
                        } else {
                          String errorMessage = 'Gagal menambahkan ke keranjang.';
                          try {
                            final errorData = json.decode(response.body);
                            errorMessage = errorData['message'] ?? errorMessage;
                          } catch (_) {}

                          messenger.showSnackBar(
                            SnackBar(content: Text(errorMessage), backgroundColor: WowinColors.promoRed),
                          );
                        }
                      } catch (e) {
                        navigator.pop();
                        messenger.showSnackBar(
                          const SnackBar(content: Text('Terjadi kesalahan jaringan.'), backgroundColor: WowinColors.promoRed),
                        );
                      }
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: WowinColors.promoRed,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      elevation: 0,
                    ),
                    child: const Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(Icons.add_shopping_cart, color: Colors.white, size: 18),
                        SizedBox(width: 8),
                        Text(
                          '+ Keranjang',
                          style: TextStyle(fontSize: 14.5, fontWeight: FontWeight.bold, color: Colors.white),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}