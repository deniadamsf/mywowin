import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../providers/cart_provider.dart';
import '../../../core/constants/api_constants.dart';
class CartScreen extends ConsumerStatefulWidget {
  const CartScreen({super.key});

  @override
  ConsumerState<CartScreen> createState() => _CartScreenState();
}

class _CartScreenState extends ConsumerState<CartScreen> {
  static const Color wowinGreen = Color(0xFF1B5E20); // Disesuaikan
  static const wowinGradient = LinearGradient(
    colors: [Color(0xFF0A4A1A), Color(0xFF2E7D32)], // Sama persis dengan catalog_screen
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  bool _isProcessingCheckout = false;

  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(cartProvider.notifier).fetchCart());
  }

  // --- FUNGSI MEMUNCULKAN JENDELA CHECKOUT ---
  void _showCheckoutBottomSheet(BuildContext context, double totalBelanja) {
    String selectedPayment = 'transfer';
    final noteController = TextEditingController();

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (BuildContext ctx) {
        return StatefulBuilder(
          builder: (BuildContext context, StateSetter setModalState) {
            return Padding(
              padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom, left: 20, right: 20, top: 20),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Detail Pengiriman & Pembayaran', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 20),

                  const Text('Metode Pembayaran:', style: TextStyle(fontWeight: FontWeight.w600)),
                  const SizedBox(height: 10),
                  // Pilihan Pembayaran
                  Container(
                    decoration: BoxDecoration(border: Border.all(color: Colors.grey.shade300), borderRadius: BorderRadius.circular(12)),
                    child: Column(
                      children: [
                        RadioListTile(
                          title: const Text('Transfer Bank', style: TextStyle(fontSize: 14)),
                          value: 'transfer', groupValue: selectedPayment, activeColor: wowinGreen,
                          onChanged: (val) => setModalState(() => selectedPayment = val.toString()),
                        ),
                        const Divider(height: 1),
                        RadioListTile(
                          title: const Text('Cash on Delivery (COD)', style: TextStyle(fontSize: 14)),
                          value: 'cod', groupValue: selectedPayment, activeColor: wowinGreen,
                          onChanged: (val) => setModalState(() => selectedPayment = val.toString()),
                        ),
                        const Divider(height: 1),
                        RadioListTile(
                          title: const Text('Pesan via WhatsApp', style: TextStyle(fontSize: 14)),
                          value: 'wa', groupValue: selectedPayment, activeColor: wowinGreen,
                          onChanged: (val) => setModalState(() => selectedPayment = val.toString()),
                        ),
                      ],
                    ),
                  ),

                  const SizedBox(height: 20),
                  const Text('Catatan Tambahan (Opsional):', style: TextStyle(fontWeight: FontWeight.w600)),
                  const SizedBox(height: 8),
                  TextField(
                    controller: noteController,
                    maxLines: 2,
                    decoration: InputDecoration(
                      hintText: 'Contoh: Titip di pos satpam ya mas.',
                      border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                      focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: wowinGreen)),
                    ),
                  ),
                  const SizedBox(height: 24),

                  // Tombol Proses
                  SizedBox(
                    width: double.infinity,
                    height: 50,
                    child: ElevatedButton(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: wowinGreen,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      ),
                      onPressed: _isProcessingCheckout ? null : () async {
                        Navigator.pop(ctx); // Tutup modal
                        await _processCheckout(selectedPayment, noteController.text);
                      },
                      child: const Text('Buat Pesanan Sekarang', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 16)),
                    ),
                  ),
                  const SizedBox(height: 20),
                ],
              ),
            );
          },
        );
      },
    );
  }

  // --- FUNGSI MENGIRIM DATA KE SERVER ---
  Future<void> _processCheckout(String paymentMethod, String note) async {
    setState(() => _isProcessingCheckout = true);

    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');

      final response = await http.post(
        Uri.parse('$baseUrl/checkout'), // Memanggil rute API baru kita
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: {
          'metode_pembayaran': paymentMethod,
          'catatan': note,
        },
      );

      final data = json.decode(response.body);

      if (response.statusCode == 200) {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Hore! Pesanan berhasil dibuat!'), backgroundColor: wowinGreen),
        );
        ref.read(cartProvider.notifier).fetchCart(); // Refresh keranjang (sekarang kosong)
        Navigator.pop(context); // Kembali ke beranda
      } else {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(data['message'] ?? 'Gagal membuat pesanan'), backgroundColor: Colors.red),
        );
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Terjadi kesalahan jaringan.'), backgroundColor: Colors.red),
      );
    } finally {
      setState(() => _isProcessingCheckout = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final cartState = ref.watch(cartProvider);

    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        elevation: 0,
        iconTheme: const IconThemeData(color: Colors.white),
        flexibleSpace: Container(decoration: const BoxDecoration(gradient: wowinGradient)),
        title: const Text('Keranjang Belanja', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, letterSpacing: 0.5)),
      ),
      body: cartState.isLoading || _isProcessingCheckout
          ? const Center(child: CircularProgressIndicator(color: wowinGreen))
          : cartState.errorMessage != null
          ? Center(child: Padding(padding: const EdgeInsets.all(24.0), child: Text(cartState.errorMessage!, textAlign: TextAlign.center)))
          : cartState.items.isEmpty
          ? Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.shopping_cart_outlined, size: 80, color: Colors.grey[300]),
            const SizedBox(height: 16),
            Text('Keranjang Anda masih kosong', style: TextStyle(fontSize: 16, color: Colors.grey[600])),
          ],
        ),
      )
          : ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: cartState.items.length,
        itemBuilder: (context, index) {
          final item = cartState.items[index];
          final product = item['product'];
          final bundling = item['bundling']; // Menangkap data relasi promo

          final int qty = int.tryParse(item['quantity'].toString()) ?? 0;
          final double price = double.tryParse(item['price'].toString()) ?? 0.0;
          final double subtotalItem = qty * price;

          String imageUrl = 'https://via.placeholder.com/150';
          String itemName = 'Item Wowin';
          bool isPromo = false;

          // Logika pemisah: Apakah ini Produk Biasa atau Promo Bundling?
          if (product != null) {
            itemName = product['nama_produk'] ?? 'Produk Wowin';
            if (product['images'] != null && product['images'].isNotEmpty) {
              imageUrl = 'https://mywowin.com/storage/${product['images'][0]['image_url']}';
            }
          } else if (bundling != null) {
            isPromo = true;
            itemName = bundling['nama_bundling'] ?? 'Promo Spesial';
            if (bundling['barang_bundling'] != null) {
              imageUrl = 'https://mywowin.com/storage/${bundling['barang_bundling']}';
            }
          }

          return Container(
            margin: const EdgeInsets.only(bottom: 16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: isPromo ? Colors.red.shade100 : Colors.grey.shade200),
              boxShadow: [
                BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 10, offset: const Offset(0, 4))
              ],
            ),
            child: Padding(
              padding: const EdgeInsets.all(12.0),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // --- AREA GAMBAR ---
                  Container(
                    decoration: BoxDecoration(
                      color: Colors.grey[50],
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(color: Colors.grey.shade100),
                    ),
                    child: ClipRRect(
                      borderRadius: BorderRadius.circular(12),
                      child: Image.network(
                        imageUrl, width: 85, height: 85, fit: BoxFit.cover,
                        errorBuilder: (ctx, err, stack) => Container(width: 85, height: 85, color: Colors.grey[100], child: const Icon(Icons.image, color: Colors.grey)),
                      ),
                    ),
                  ),
                  const SizedBox(width: 16),

                  // --- AREA DETAIL TEKS ---
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Label Penanda
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                          decoration: BoxDecoration(
                              color: isPromo ? Colors.red[50] : Colors.green[50],
                              borderRadius: BorderRadius.circular(4)
                          ),
                          child: Text(
                              isPromo ? 'PROMO BUNDLING' : 'PRODUK REGULER',
                              style: TextStyle(color: isPromo ? Colors.red[700] : wowinGreen, fontSize: 9, fontWeight: FontWeight.bold)
                          ),
                        ),
                        const SizedBox(height: 6),

                        // Nama Item
                        Text(itemName, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, height: 1.2), maxLines: 2, overflow: TextOverflow.ellipsis),
                        const SizedBox(height: 10),

                        // Area Harga & Kuantitas
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          crossAxisAlignment: CrossAxisAlignment.end,
                          children: [
                            Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text('Rp ${price.toStringAsFixed(0)}', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: isPromo ? Colors.red : Colors.orange)),
                                const SizedBox(height: 2),
                                Text('${qty}x ${item['unit']}', style: TextStyle(color: Colors.grey[500], fontSize: 12)),
                              ],
                            ),
                            // Kotak Subtotal
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                              decoration: BoxDecoration(
                                  color: isPromo ? Colors.red.withValues(alpha: 0.1) : Colors.orange.withValues(alpha: 0.1),
                                  borderRadius: BorderRadius.circular(8)
                              ),
                              child: Text(
                                  'Rp ${subtotalItem.toStringAsFixed(0)}',
                                  style: TextStyle(color: isPromo ? Colors.red[700] : Colors.orange, fontWeight: FontWeight.bold, fontSize: 13)
                              ),
                            )
                          ],
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          );
        },
      ),

      bottomNavigationBar: SafeArea(
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
          decoration: BoxDecoration(
            color: Colors.white,
            boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.05), spreadRadius: 1, blurRadius: 10, offset: const Offset(0, -5))],
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Total Belanja', style: TextStyle(color: Colors.grey[600], fontSize: 13)),
                  const SizedBox(height: 4),
                  Text('Rp ${cartState.subtotal}', style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.orange)),
                ],
              ),
              Material(
                color: Colors.transparent,
                child: Ink(
                  decoration: BoxDecoration(
                    gradient: cartState.items.isEmpty ? null : wowinGradient,
                    color: cartState.items.isEmpty ? Colors.grey[400] : null,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: InkWell(
                    // --- MENGHUBUNGKAN TOMBOL CHECKOUT ---
                    onTap: cartState.items.isEmpty ? null : () => _showCheckoutBottomSheet(context, cartState.subtotal),
                    borderRadius: BorderRadius.circular(12),
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 14),
                      child: const Text('Checkout', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 16, letterSpacing: 0.5)),
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