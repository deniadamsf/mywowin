import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../cart/providers/cart_provider.dart';
import 'package:flutter_html/flutter_html.dart';

class ProductDetailScreen extends ConsumerStatefulWidget {
  final Map<String, dynamic> product;

  const ProductDetailScreen({super.key, required this.product});

  @override
  ConsumerState<ProductDetailScreen> createState() => _ProductDetailScreenState();
}

class _ProductDetailScreenState extends ConsumerState<ProductDetailScreen> {
  static const Color wowinGreen = Color(0xFF2E7D32);
  static const wowinGradient = LinearGradient(
    colors: [Color(0xFF1B5E20), Color(0xFF4CAF50)],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  bool _isAdding = false;

  // Fitur Baru: State untuk Satuan & Jumlah
  String _selectedUnit = 'pcs';
  int _quantity = 1;

  Future<void> _handleAddToCart() async {
    setState(() => _isAdding = true);

    // Menembak API dengan jumlah dan satuan yang dipilih pelanggan!
    final success = await ref.read(cartProvider.notifier).addToCart(
        productId: widget.product['id_product'],
        quantity: _quantity,
        unit: _selectedUnit
    );

    if (!mounted) return;
    setState(() => _isAdding = false);

    if (success) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('$_quantity $_selectedUnit berhasil ditambahkan!', style: const TextStyle(fontWeight: FontWeight.bold)),
          backgroundColor: wowinGreen,
          duration: const Duration(seconds: 2),
        ),
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Gagal menambahkan. Pastikan Anda sudah login.'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final product = widget.product;

    // Menangkap Harga Satuan dan Harga Karton dari Backend
    final double hargaPcs = double.tryParse(product['harga_pcs'].toString()) ?? 0;
    final double hargaKarton = double.tryParse(product['harga'].toString()) ?? 0;
    final int isiKarton = int.tryParse(product['isi_karton'].toString()) ?? 1;

    // Kalkulasi Total Harga Real-time di Bawah
    final double currentPrice = _selectedUnit == 'pcs' ? hargaPcs : hargaKarton;
    final double totalPrice = currentPrice * _quantity;

    String imageUrl = 'https://via.placeholder.com/400';
    if (product['images'] != null && product['images'].isNotEmpty) {
      String path = product['images'][0]['image_url'];
      imageUrl = 'https://mywowin.com/storage/$path';
    }

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        iconTheme: const IconThemeData(color: Colors.black87),
      ),
      extendBodyBehindAppBar: true,
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // --- GAMBAR PRODUK ---
            Container(
              color: Colors.grey[50],
              width: double.infinity,
              height: 380,
              child: Image.network(
                imageUrl,
                fit: BoxFit.cover,
                errorBuilder: (context, error, stackTrace) =>
                const Icon(Icons.image_not_supported, size: 80, color: Colors.grey),
              ),
            ),

            Padding(
              padding: const EdgeInsets.all(20.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // --- NAMA & KATEGORI ---
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(color: wowinGreen.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(4)),
                    child: const Text('Produk Resmi', style: TextStyle(color: wowinGreen, fontSize: 11, fontWeight: FontWeight.bold)),
                  ),
                  const SizedBox(height: 10),
                  Text(
                    product['nama_produk'] ?? 'Nama Produk',
                    style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, height: 1.2),
                  ),
                  const SizedBox(height: 12),

                  // --- PILIHAN SATUAN (PCS / KARTON) ---
                  const Text('Pilih Satuan Pembelian:', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 14)),
                  const SizedBox(height: 10),
                  Row(
                    children: [
                      Expanded(child: _buildUnitOption('pcs', 'Satuan (Pcs)', hargaPcs)),
                      const SizedBox(width: 12),
                      Expanded(child: _buildUnitOption('karton', 'Karton (Dus)', hargaKarton, subtitle: 'Isi $isiKarton pcs')),
                    ],
                  ),
                  const SizedBox(height: 24),

                  const Divider(color: Colors.black12, thickness: 1),
                  const SizedBox(height: 16),

                  // --- DESKRIPSI PRODUK (Sama Dengan Web) ---
                  const Text('Deskripsi Produk', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),

                  // Menggunakan widget Html untuk menerjemahkan tag <p>, <ul>, <li> dari database
                  Html(
                    data: product['rekom_guna'] ?? 'Tidak ada deskripsi',
                    style: {
                      "body": Style(
                        margin: Margins.zero,
                        padding: HtmlPaddings.zero,
                        fontSize: FontSize(15.0),
                        color: Colors.black87,
                        lineHeight: const LineHeight(1.5),
                      ),
                      "ul": Style(
                        margin: Margins.only(top: 0, bottom: 0),
                      ),
                    },
                  ),

                  const SizedBox(height: 24),

                  // --- SPESIFIKASI DETAIL ---
                  const Text('Spesifikasi Detail', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                        color: Colors.grey[50],
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: Colors.grey.shade200)
                    ),
                    child: Column(
                      children: [
                        _buildSpecRow('Isi per Karton', '${product['isi_karton'] ?? '-'} pcs'),
                        const Divider(height: 16),
                        _buildSpecRow('Volume / Berat', '${product['isi_ml'] ?? '-'} / ${product['berat'] ?? '-'}'),
                        const Divider(height: 16),
                        _buildSpecRow('No. BPOM', product['no_bpom'] ?? '-'),
                        const Divider(height: 16),
                        _buildSpecRow('Sertifikasi Halal', product['no_halal'] ?? '-'),
                      ],
                    ),
                  ),
                  const SizedBox(height: 30),
                ],
              ),
            ),
          ],
        ),
      ),

      // --- PANEL BAWAH (QTY & TOTAL) ---
      bottomNavigationBar: SafeArea(
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
          decoration: BoxDecoration(
            color: Colors.white,
            boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), spreadRadius: 1, blurRadius: 10, offset: const Offset(0, -3))],
          ),
          child: Row(
            children: [
              // Pengatur Jumlah (Qty)
              Container(
                decoration: BoxDecoration(
                  border: Border.all(color: Colors.grey.shade300),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Row(
                  children: [
                    IconButton(
                      icon: const Icon(Icons.remove, size: 20),
                      onPressed: _quantity > 1 ? () => setState(() => _quantity--) : null,
                      color: _quantity > 1 ? Colors.black : Colors.grey,
                    ),
                    Text('$_quantity', style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                    IconButton(
                      icon: const Icon(Icons.add, size: 20),
                      onPressed: () => setState(() => _quantity++),
                      color: wowinGreen,
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 16),

              // Tombol Beli & Total Harga
              Expanded(
                child: Material(
                  color: Colors.transparent,
                  child: Ink(
                    decoration: BoxDecoration(gradient: wowinGradient, borderRadius: BorderRadius.circular(12)),
                    child: InkWell(
                      onTap: _isAdding ? null : _handleAddToCart,
                      borderRadius: BorderRadius.circular(12),
                      child: Container(
                        height: 50,
                        padding: const EdgeInsets.symmetric(horizontal: 12),
                        alignment: Alignment.center,
                        child: _isAdding
                            ? const SizedBox(width: 24, height: 24, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 3))
                            : Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            const Text('Tambah ke Keranjang', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w500, color: Colors.white70)),
                            Text('Rp $totalPrice', style: const TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Colors.white)),
                          ],
                        ),
                      ),
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

  // Widget Pembuat Tombol Pilihan Satuan (Interaktif)
  Widget _buildUnitOption(String value, String title, double price, {String? subtitle}) {
    final isSelected = _selectedUnit == value;
    return GestureDetector(
      onTap: () => setState(() => _selectedUnit = value),
      child: Container(
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: isSelected ? wowinGreen.withValues(alpha: 0.05) : Colors.white,
          border: Border.all(color: isSelected ? wowinGreen : Colors.grey.shade300, width: isSelected ? 2 : 1),
          borderRadius: BorderRadius.circular(8),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(title, style: TextStyle(fontSize: 13, color: isSelected ? wowinGreen : Colors.black87, fontWeight: isSelected ? FontWeight.bold : FontWeight.normal)),
            const SizedBox(height: 4),
            Text('Rp $price', style: const TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Colors.orange)),
            if (subtitle != null) ...[
              const SizedBox(height: 4),
              Text(subtitle, style: TextStyle(fontSize: 11, color: Colors.grey[600])),
            ]
          ],
        ),
      ),
    );
  }

  Widget _buildSpecRow(String title, String value) {
    return Row(
      children: [
        Expanded(flex: 2, child: Text(title, style: TextStyle(color: Colors.grey[600], fontSize: 13))),
        Expanded(flex: 3, child: Text(value, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13), textAlign: TextAlign.right)),
      ],
    );
  }
}