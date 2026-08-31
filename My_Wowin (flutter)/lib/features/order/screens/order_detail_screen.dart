import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:intl/intl.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../../core/theme/wowin_theme.dart';
import 'review_order_screen.dart';

class OrderDetailScreen extends StatelessWidget {
  final dynamic order;
  const OrderDetailScreen({super.key, required this.order});

  static const Color wowinGreen = WowinColors.primary;

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

  Future<void> _contactAdmin(String invoice, [String? paymentMethod, String? total]) async {
    const String waNumber = '6281216301220';
    final String text = 'Halo Admin Wowin Food, saya ingin konfirmasi pesanan saya:\n\n'
        '• *No. Invoice:* $invoice\n'
        '${total != null ? '• *Total:* $total\n' : ''}'
        '${paymentMethod != null ? '• *Metode:* ${paymentMethod.toUpperCase()}\n' : ''}\n'
        'Mohon bantuan untuk diproses. Terima kasih!';
    final Uri url = Uri.parse('https://wa.me/$waNumber?text=${Uri.encodeComponent(text)}');

    if (await canLaunchUrl(url)) {
      await launchUrl(url, mode: LaunchMode.externalApplication);
    }
  }

  @override
  Widget build(BuildContext context) {
    final statusColor = _getStatusColor(order['status'] ?? 'Pending');
    final String paymentMethod = (order['payment_method'] ?? 'transfer').toString().toLowerCase();
    final bool isPending = (order['status'] ?? 'pending').toString().toLowerCase() == 'pending';

    final List<dynamic> orderItems = order['items'] ?? order['order_items'] ?? order['details'] ?? [];
    final num potonganPoin = num.tryParse(order['potongan_poin']?.toString() ?? '0') ?? 0;
    final int pointsUsed = int.tryParse(order['points_used']?.toString() ?? '0') ?? 0;

    return Scaffold(
      backgroundColor: WowinColors.background,
      appBar: WowinAppBar.standard(title: 'Detail Pesanan'),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // --- HEADER: INVOICE & STATUS ---
            Container(
              color: Colors.white,
              padding: const EdgeInsets.all(16),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('NOMOR PESANAN', style: TextStyle(fontSize: 9.5, color: Colors.grey, fontWeight: FontWeight.bold, letterSpacing: 0.5)),
                      const SizedBox(height: 4),
                      Text(order['invoice_number'] ?? '#INV-UNKNOWN', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14.5, color: WowinColors.textPrimary)),
                    ],
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                    decoration: BoxDecoration(color: statusColor.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(6)),
                    child: Text(
                      (order['status'] ?? 'Menunggu Pembayaran').toUpperCase(),
                      style: TextStyle(color: statusColor, fontSize: 10.5, fontWeight: FontWeight.bold),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 8),

            // --- KARTU INFORMASI PEMBAYARAN & REKENING ---
            Container(
              color: Colors.white,
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Icon(
                        paymentMethod == 'transfer'
                            ? Icons.account_balance_rounded
                            : (paymentMethod == 'cod' ? Icons.local_shipping_rounded : Icons.chat_rounded),
                        color: wowinGreen,
                        size: 20,
                      ),
                      const SizedBox(width: 8),
                      Text(
                        paymentMethod == 'transfer'
                            ? 'Pembayaran: Transfer Bank'
                            : (paymentMethod == 'cod' ? 'Pembayaran: Cash on Delivery (COD)' : 'Pemesanan via WhatsApp'),
                        style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                      ),
                    ],
                  ),
                  const SizedBox(height: 10),
                  if (paymentMethod == 'transfer' && isPending) ...[
                    Container(
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: const Color(0xFFE3F2FD),
                        borderRadius: BorderRadius.circular(10),
                        border: Border.all(color: const Color(0xFF90CAF9)),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('Rekening Tujuan Wowin:', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Color(0xFF0D47A1))),
                          const SizedBox(height: 8),
                          _buildBankRow(context, 'Bank BCA', '0891234567', 'PT WOWIN PURNOMO PUTERA'),
                          const Divider(height: 14, color: Color(0xFFBBDEFB)),
                          _buildBankRow(context, 'Bank BRI', '0123-01-000456-53-0', 'PT SANKE BERSINAR TERANG'),
                        ],
                      ),
                    ),
                  ] else if (paymentMethod == 'cod') ...[
                    Text(
                      'Pesanan akan diantar oleh kurir kami. Mohon siapkan uang pas saat barang diterima.',
                      style: TextStyle(fontSize: 12, color: Colors.grey.shade700),
                    ),
                  ],
                ],
              ),
            ),
            const SizedBox(height: 8),

            // --- DAFTAR PRODUK ---
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
                      child: Text('Detail item pesanan.', style: TextStyle(color: Colors.grey, fontStyle: FontStyle.italic)),
                    );
                  }

                  final item = orderItems[index];
                  final product = item['product'];
                  final bundling = item['bundling'];

                  String itemName = item['product_name'] ?? product?['nama_produk'] ?? bundling?['nama_bundling'] ?? 'Produk Wowin';
                  String unit = (item['unit'] ?? 'pcs').toString().toUpperCase();
                  String qty = (item['quantity'] ?? item['qty'] ?? 1).toString();
                  num price = num.tryParse(item['price']?.toString() ?? '0') ?? 0;

                  String displayName = '$itemName (${unit[0].toUpperCase()}${unit.substring(1).toLowerCase()})';

                  String imageUrl = '';
                  if (product != null && product['images'] != null && product['images'] is List && (product['images'] as List).isNotEmpty) {
                    final imgPath = product['images'][0]['image_url']?.toString() ?? '';
                    if (imgPath.isNotEmpty) {
                      imageUrl = imgPath.startsWith('http') ? imgPath : 'https://mywowin.com/storage/$imgPath';
                    }
                  } else if (bundling != null && bundling['barang_bundling'] != null && bundling['barang_bundling'].toString().isNotEmpty) {
                    final imgPath = bundling['barang_bundling'].toString();
                    imageUrl = imgPath.startsWith('http') ? imgPath : 'https://mywowin.com/storage/$imgPath';
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

            // --- BARIS RINCIAN POTONGAN POIN JIKA ADA ---
            if (potonganPoin > 0) ...[
              Container(
                margin: const EdgeInsets.only(top: 8),
                color: Colors.white,
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Row(
                      children: [
                        const Icon(Icons.stars_rounded, color: Color(0xFFFFA000), size: 18),
                        const SizedBox(width: 8),
                        Text('Potongan Poin ($pointsUsed Poin)', style: const TextStyle(fontSize: 13, color: Color(0xFF2E7D32), fontWeight: FontWeight.w600)),
                      ],
                    ),
                    Text(
                      '- Rp ${NumberFormat('#,###', 'id_ID').format(potonganPoin)}',
                      style: const TextStyle(fontSize: 13, fontWeight: FontWeight.bold, color: Color(0xFF2E7D32)),
                    ),
                  ],
                ),
              ),
            ],
            // --- KARTU PENILAIAN PESANAN (JIKA STATUS SELESAI / LUNAS / DIKIRIM) ---
            if (['selesai', 'completed', 'lunas', 'dikirim'].contains((order['status'] ?? '').toString().toLowerCase())) ...[
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    colors: [Color(0xFFFFF8E1), Colors.white],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: const Color(0xFFFFE082)),
                  boxShadow: [
                    BoxShadow(color: Colors.amber.withValues(alpha: 0.08), blurRadius: 8, offset: const Offset(0, 3)),
                  ],
                ),
                child: Row(
                  children: [
                    Container(
                      padding: const EdgeInsets.all(10),
                      decoration: BoxDecoration(
                        color: Colors.amber.shade100,
                        shape: BoxShape.circle,
                      ),
                      child: const Icon(Icons.star_rounded, color: Colors.amber, size: 26),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: const [
                          Text('Beri Penilaian Pesanan', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFF5D4037))),
                          SizedBox(height: 2),
                          Text('Bagikan ulasan Anda dan bantu kami meningkatkan pelayanan.', style: TextStyle(fontSize: 10.5, color: Color(0xFF8D6E63))),
                        ],
                      ),
                    ),
                    const SizedBox(width: 8),
                    ElevatedButton(
                      onPressed: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => ReviewOrderScreen(order: order),
                          ),
                        );
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFFFFA000),
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                        elevation: 0,
                      ),
                      child: const Text('Nilai', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                    ),
                  ],
                ),
              ),
            ],

            const SizedBox(height: 20),
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
                  backgroundColor: const Color(0xFF1B5E20),
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

  Widget _buildBankRow(BuildContext context, String bankName, String accountNumber, String accountHolder) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(bankName, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12, color: Color(0xFF0D47A1))),
            Text(accountNumber, style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w800, letterSpacing: 0.5)),
            Text('a.n. $accountHolder', style: TextStyle(fontSize: 10.5, color: Colors.grey.shade600)),
          ],
        ),
        InkWell(
          borderRadius: BorderRadius.circular(8),
          onTap: () {
            Clipboard.setData(ClipboardData(text: accountNumber.replaceAll('-', '').replaceAll(' ', '')));
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text('Nomor rekening $bankName berhasil disalin!'), backgroundColor: wowinGreen, duration: const Duration(seconds: 2)),
            );
          },
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(8),
              border: Border.all(color: const Color(0xFF90CAF9)),
            ),
            child: const Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(Icons.copy_rounded, size: 13, color: Color(0xFF1565C0)),
                SizedBox(width: 4),
                Text('Salin', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Color(0xFF1565C0))),
              ],
            ),
          ),
        ),
      ],
    );
  }
}