import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import 'package:flutter_html/flutter_html.dart';
import '../../../core/theme/wowin_theme.dart';
import '../../cart/providers/cart_provider.dart';
import '../../auth/providers/auth_provider.dart';
import '../../auth/screens/login_screen.dart';
import '../../../core/widgets/wowin_cached_image.dart';
import '../../order/services/review_api.dart';

class ProductDetailScreen extends ConsumerStatefulWidget {
  final Map<String, dynamic> product;

  const ProductDetailScreen({super.key, required this.product});

  @override
  ConsumerState<ProductDetailScreen> createState() => _ProductDetailScreenState();
}

class _ProductDetailScreenState extends ConsumerState<ProductDetailScreen> {
  static const Color wowinGreen = WowinColors.primary;

  bool _isAdding = false;
  String _selectedUnit = 'pcs';
  int _quantity = 1;

  Map<String, dynamic>? _reviewsData;
  bool _isLoadingReviews = true;

  @override
  void initState() {
    super.initState();
    _loadReviews();
  }

  Future<void> _loadReviews() async {
    final productId = widget.product['id_product'] ?? widget.product['id'];
    if (productId != null) {
      final res = await ReviewApi.getProductReviews(int.parse(productId.toString()));
      if (mounted && res['success'] == true) {
        setState(() {
          _reviewsData = res['data'];
          _isLoadingReviews = false;
        });
      } else {
        if (mounted) setState(() => _isLoadingReviews = false);
      }
    }
  }

  final NumberFormat _currency = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

  Future<void> _handleAddToCart() async {
    final authState = ref.read(authProvider);
    if (!authState.isAuthenticated) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Silakan login terlebih dahulu untuk berbelanja!')),
      );
      Navigator.push(context, MaterialPageRoute(builder: (context) => const LoginScreen()));
      return;
    }

    setState(() => _isAdding = true);

    final success = await ref.read(cartProvider.notifier).addToCart(
      productId: widget.product['id_product'] ?? widget.product['id'],
      quantity: _quantity,
      unit: _selectedUnit,
      productData: widget.product,
    );

    if (!mounted) return;
    setState(() => _isAdding = false);

    if (success) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('$_quantity $_selectedUnit berhasil ditambahkan ke keranjang!', style: const TextStyle(fontWeight: FontWeight.bold)),
          backgroundColor: wowinGreen,
          duration: const Duration(seconds: 2),
        ),
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Gagal menambahkan produk ke keranjang.'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final product = widget.product;

    final double hargaPcs = double.tryParse(product['harga_pcs']?.toString() ?? '0') ?? 0;
    final double hargaKarton = double.tryParse(product['harga']?.toString() ?? '0') ?? 0;
    final int isiKarton = int.tryParse(product['isi_karton']?.toString() ?? '1') ?? 1;
    final String namaKategori = product['category']?['name'] ?? 'Produk Resmi';

    final double currentPrice = _selectedUnit == 'pcs' ? hargaPcs : hargaKarton;
    final double totalPrice = currentPrice * _quantity;

    String imageUrl = '';
    if (product['images'] != null && product['images'] is List && (product['images'] as List).isNotEmpty) {
      final imgPath = product['images'][0]['image_url']?.toString() ?? '';
      if (imgPath.isNotEmpty) {
        imageUrl = imgPath.startsWith('http') ? imgPath : 'https://mywowin.com/storage/$imgPath';
      }
    }

    return Scaffold(
      backgroundColor: WowinColors.background,
      appBar: WowinAppBar.standard(title: 'Detail Produk'),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // ==========================================================
            // 1. PRODUCT SHOWCASE IMAGE (PRESISE 1:1 SQUARE)
            // ==========================================================
            Container(
              width: double.infinity,
              color: Colors.white,
              child: AspectRatio(
                aspectRatio: 1.0,
                child: Stack(
                  fit: StackFit.expand,
                  children: [
                    Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: WowinCachedImage(
                        imageUrl: imageUrl,
                        fit: BoxFit.contain,
                        errorWidget: const Icon(Icons.image_not_supported, size: 80, color: Colors.grey),
                      ),
                    ),
                  Positioned(
                    top: 14,
                    left: 14,
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                      decoration: BoxDecoration(
                        color: wowinGreen.withValues(alpha: 0.1),
                        borderRadius: BorderRadius.circular(8),
                        border: Border.all(color: wowinGreen.withValues(alpha: 0.25)),
                      ),
                      child: Text(
                        namaKategori.toUpperCase(),
                        style: const TextStyle(color: wowinGreen, fontSize: 10, fontWeight: FontWeight.w900),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),

            const SizedBox(height: 14),

            // ==========================================================
            // 2. PRODUCT INFO CARD
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
                  Text(
                    product['nama_produk'] ?? 'Nama Produk',
                    style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, height: 1.3, color: WowinColors.textPrimary),
                  ),
                  const SizedBox(height: 6),
                  Row(
                    children: [
                      const Icon(Icons.star_rounded, color: Colors.amber, size: 18),
                      const SizedBox(width: 4),
                      Text(
                        '${_reviewsData?['average_rating'] ?? 5.0}',
                        style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: WowinColors.textPrimary),
                      ),
                      const SizedBox(width: 6),
                      Text(
                        '(${_reviewsData?['total_reviews'] ?? 0} ulasan)',
                        style: const TextStyle(fontSize: 12, color: WowinColors.textSecondary),
                      ),
                      const Spacer(),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                        decoration: BoxDecoration(color: const Color(0xFFE8F5E9), borderRadius: BorderRadius.circular(6)),
                        child: const Text('Terverifikasi', style: TextStyle(color: wowinGreen, fontSize: 10, fontWeight: FontWeight.bold)),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  Text(
                    _currency.format(currentPrice),
                    style: const TextStyle(fontSize: 24, fontWeight: FontWeight.w900, color: wowinGreen),
                  ),
                  const SizedBox(height: 16),

                  // Unit Selector Options
                  const Text('Pilihan Satuan Pembelian:', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: WowinColors.textPrimary)),
                  const SizedBox(height: 10),
                  Row(
                    children: [
                      Expanded(
                        child: _buildUnitCard(
                          unitKey: 'pcs',
                          title: 'Satuan (Pcs)',
                          priceText: _currency.format(hargaPcs),
                          subtitle: 'Eceran',
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: _buildUnitCard(
                          unitKey: 'karton',
                          title: 'Karton (Dus)',
                          priceText: _currency.format(hargaKarton),
                          subtitle: 'Isi $isiKarton pcs',
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),

            const SizedBox(height: 14),

            // ==========================================================
            // 3. OFFICIAL CERTIFICATION & DISTRIBUTOR CARD
            // ==========================================================
            Container(
              margin: const EdgeInsets.symmetric(horizontal: 16),
              padding: const EdgeInsets.all(16),
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
                    child: const Icon(Icons.verified_outlined, color: wowinGreen, size: 22),
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
                          'Produk Asli & Tersertifikasi Resmi BPOM / Halal',
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
            // 4. SPESIFIKASI DETAIL
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
                  const Text('Spesifikasi Produk', style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: WowinColors.textPrimary)),
                  const SizedBox(height: 12),
                  _buildSpecRow('Isi per Karton', '${product['isi_karton'] ?? '-'} pcs'),
                  const Divider(height: 18),
                  _buildSpecRow(
                    'Volume / Berat',
                    '${product['isi_ml'] != null ? '${product['isi_ml']} ml' : '-'} / ${product['berat'] != null && double.tryParse(product['berat'].toString()) != null && double.parse(product['berat'].toString()) > 0 ? '${double.parse(product['berat'].toString()).toInt()} gr' : '-'}',
                  ),
                  const Divider(height: 18),
                  _buildSpecRow('No. BPOM', product['no_bpom'] ?? '-'),
                  const Divider(height: 18),
                  _buildSpecRow('Sertifikasi Halal', product['no_halal'] ?? '-'),
                ],
              ),
            ),

            const SizedBox(height: 14),

            // ==========================================================
            // 5. DESKRIPSI PRODUK
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
                  const Text('Deskripsi & Petunjuk Penggunaan', style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: WowinColors.textPrimary)),
                  const SizedBox(height: 10),
                  Html(
                    data: product['rekom_guna'] ?? '<p>Tidak ada deskripsi khusus untuk produk ini.</p>',
                    style: {
                      "body": Style(
                        margin: Margins.zero,
                        padding: HtmlPaddings.zero,
                        fontSize: FontSize(13.5),
                        color: WowinColors.textSecondary,
                        lineHeight: const LineHeight(1.5),
                      ),
                    },
                  ),
                ],
              ),
            ),

            const SizedBox(height: 14),

            // ==========================================================
            // 6. ULASAN & PENILAIAN PELANGGAN
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
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text(
                        'Ulasan Pelanggan',
                        style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: WowinColors.textPrimary),
                      ),
                      Row(
                        children: [
                          const Icon(Icons.star_rounded, color: Colors.amber, size: 18),
                          const SizedBox(width: 4),
                          Text(
                            '${_reviewsData?['average_rating'] ?? 5.0}',
                            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
                          ),
                          Text(
                            ' (${_reviewsData?['total_reviews'] ?? 0})',
                            style: const TextStyle(fontSize: 11, color: Colors.grey),
                          ),
                        ],
                      ),
                    ],
                  ),
                  const SizedBox(height: 14),

                  if (_isLoadingReviews)
                    const Center(child: Padding(padding: EdgeInsets.all(20), child: CircularProgressIndicator(color: wowinGreen)))
                  else if (_reviewsData == null || (_reviewsData!['reviews'] as List).isEmpty)
                    Center(
                      child: Padding(
                        padding: const EdgeInsets.symmetric(vertical: 20),
                        child: Column(
                          children: [
                            Icon(Icons.rate_review_outlined, size: 36, color: Colors.grey.shade300),
                            const SizedBox(height: 8),
                            const Text('Belum ada ulasan untuk produk ini', style: TextStyle(color: Colors.grey, fontSize: 12)),
                          ],
                        ),
                      ),
                    )
                  else
                    ...(_reviewsData!['reviews'] as List).take(5).map((rev) {
                      final List<dynamic> tags = rev['tags'] ?? [];
                      final List<dynamic> photos = rev['foto'] ?? [];

                      return Container(
                        padding: const EdgeInsets.symmetric(vertical: 12),
                        decoration: BoxDecoration(
                          border: Border(bottom: BorderSide(color: Colors.grey.shade100)),
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Row(
                                  children: [
                                    CircleAvatar(
                                      radius: 14,
                                      backgroundColor: wowinGreen.withValues(alpha: 0.1),
                                      child: Text(
                                        (rev['user_name'] ?? 'U').toString().substring(0, 1).toUpperCase(),
                                        style: const TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: wowinGreen),
                                      ),
                                    ),
                                    const SizedBox(width: 8),
                                    Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Text(
                                          rev['user_name'] ?? 'Pengguna Wowin',
                                          style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold),
                                        ),
                                        Text(
                                          rev['created_at'] ?? '',
                                          style: TextStyle(fontSize: 9.5, color: Colors.grey.shade400),
                                        ),
                                      ],
                                    ),
                                  ],
                                ),
                                Row(
                                  children: List.generate(5, (starIdx) {
                                    return Icon(
                                      starIdx < (rev['rating'] ?? 5) ? Icons.star_rounded : Icons.star_outline_rounded,
                                      color: Colors.amber,
                                      size: 14,
                                    );
                                  }),
                                ),
                              ],
                            ),

                            if (tags.isNotEmpty) ...[
                              const SizedBox(height: 8),
                              Wrap(
                                spacing: 4,
                                runSpacing: 4,
                                children: tags.map((t) => Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                                  decoration: BoxDecoration(
                                    color: Colors.grey.shade100,
                                    borderRadius: BorderRadius.circular(4),
                                  ),
                                  child: Text('✓ $t', style: TextStyle(fontSize: 9.5, color: Colors.grey.shade700)),
                                )).toList(),
                              ),
                            ],

                            if (rev['komentar'] != null && rev['komentar'].toString().isNotEmpty) ...[
                              const SizedBox(height: 8),
                              Text(
                                rev['komentar'],
                                style: TextStyle(fontSize: 12, color: Colors.grey.shade800, height: 1.3),
                              ),
                            ],

                            if (photos.isNotEmpty) ...[
                              const SizedBox(height: 8),
                              Row(
                                children: photos.map((img) => Container(
                                  margin: const EdgeInsets.only(right: 6),
                                  width: 48,
                                  height: 48,
                                  decoration: BoxDecoration(
                                    borderRadius: BorderRadius.circular(8),
                                    border: Border.all(color: Colors.grey.shade200),
                                  ),
                                  child: ClipRRect(
                                    borderRadius: BorderRadius.circular(7),
                                    child: Image.network(img.toString(), fit: BoxFit.cover, errorBuilder: (c, e, s) => const Icon(Icons.image, size: 20, color: Colors.grey)),
                                  ),
                                )).toList(),
                              ),
                            ],

                            if (rev['balasan_admin'] != null && rev['balasan_admin'].toString().isNotEmpty) ...[
                              const SizedBox(height: 8),
                              Container(
                                padding: const EdgeInsets.all(8),
                                decoration: BoxDecoration(
                                  color: const Color(0xFFF1F8E9),
                                  borderRadius: BorderRadius.circular(8),
                                  border: const Border(left: BorderSide(color: wowinGreen, width: 3)),
                                ),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    const Text('Respon Wowin:', style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: wowinGreen)),
                                    const SizedBox(height: 2),
                                    Text(rev['balasan_admin'], style: TextStyle(fontSize: 11, color: Colors.grey.shade800)),
                                  ],
                                ),
                              ),
                            ],
                          ],
                        ),
                      );
                    }),
                ],
              ),
            ),

            const SizedBox(height: 100),
          ],
        ),
      ),

      // ==========================================================
      // 6. STICKY BOTTOM ACTION BAR (TOTAL + CTA)
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
                      icon: const Icon(Icons.remove, size: 18),
                      onPressed: _quantity > 1 ? () => setState(() => _quantity--) : null,
                      color: _quantity > 1 ? WowinColors.textPrimary : Colors.grey.shade400,
                    ),
                    Text('$_quantity', style: const TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: WowinColors.textPrimary)),
                    IconButton(
                      icon: const Icon(Icons.add, size: 18),
                      onPressed: () => setState(() => _quantity++),
                      color: wowinGreen,
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 14),

              // Tombol Add to Cart dengan Subtotal
              Expanded(
                child: SizedBox(
                  height: 46,
                  child: ElevatedButton(
                    onPressed: _isAdding ? null : _handleAddToCart,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: wowinGreen,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      elevation: 0,
                    ),
                    child: _isAdding
                        ? const SizedBox(width: 22, height: 22, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5))
                        : Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Icon(Icons.add_shopping_cart, color: Colors.white, size: 18),
                        const SizedBox(width: 8),
                        Text(
                          'Beli • ${_currency.format(totalPrice)}',
                          style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.white),
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

  Widget _buildUnitCard({
    required String unitKey,
    required String title,
    required String priceText,
    required String subtitle,
  }) {
    final isSelected = _selectedUnit == unitKey;

    return GestureDetector(
      onTap: () => setState(() => _selectedUnit = unitKey),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: isSelected ? wowinGreen.withValues(alpha: 0.06) : Colors.grey.shade50,
          borderRadius: BorderRadius.circular(14),
          border: Border.all(
            color: isSelected ? wowinGreen : Colors.grey.shade300,
            width: isSelected ? 1.5 : 1,
          ),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  title,
                  style: TextStyle(
                    fontSize: 12.5,
                    fontWeight: FontWeight.bold,
                    color: isSelected ? wowinGreen : WowinColors.textPrimary,
                  ),
                ),
                Icon(
                  isSelected ? Icons.check_circle : Icons.radio_button_unchecked,
                  color: isSelected ? wowinGreen : Colors.grey.shade400,
                  size: 18,
                ),
              ],
            ),
            const SizedBox(height: 6),
            Text(
              priceText,
              style: TextStyle(
                fontSize: 14,
                fontWeight: FontWeight.w900,
                color: isSelected ? wowinGreen : WowinColors.textPrimary,
              ),
            ),
            const SizedBox(height: 2),
            Text(
              subtitle,
              style: TextStyle(fontSize: 10.5, color: Colors.grey.shade600),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSpecRow(String label, String value) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(label, style: const TextStyle(fontSize: 12.5, color: WowinColors.textSecondary)),
        Text(value, style: const TextStyle(fontSize: 12.5, fontWeight: FontWeight.bold, color: WowinColors.textPrimary)),
      ],
    );
  }
}