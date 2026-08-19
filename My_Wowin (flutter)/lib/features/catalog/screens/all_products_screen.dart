import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../providers/catalog_provider.dart';
import 'product_detail_screen.dart';
import '../../cart/screens/cart_screen.dart';
import '../../cart/providers/cart_provider.dart';
import '../../auth/providers/auth_provider.dart';
import '../../auth/screens/login_screen.dart';

class AllProductsScreen extends ConsumerStatefulWidget {
  final String initialCategory;
  // Jika tidak ada parameter (dibuka dari menu bawah), defaultnya 'Semua Kategori'
  const AllProductsScreen({super.key, this.initialCategory = 'Semua Kategori'});

  @override
  ConsumerState<AllProductsScreen> createState() => _AllProductsScreenState();
}

class _AllProductsScreenState extends ConsumerState<AllProductsScreen> {
  static const Color wowinGreen = Color(0xFF1B5E20);
  bool _isKartonPrice = false;
  late String _activeCategory;

  String _searchQuery = ''; // <-- TAMBAHAN: Untuk menyimpan teks pencarian

  @override
  void initState() {
    super.initState();
    _activeCategory = widget.initialCategory; // Ambil kategori yang diklik
  }

  @override
  Widget build(BuildContext context) {
    final catalogState = ref.watch(catalogProvider);

    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        elevation: 0,
        backgroundColor: Colors.transparent,
        iconTheme: const IconThemeData(color: Colors.white),
        flexibleSpace: Container(
          decoration: const BoxDecoration(
            gradient: LinearGradient(colors: [Color(0xFF0A4A1A), Color(0xFF2E7D32)], begin: Alignment.topLeft, end: Alignment.bottomRight),
          ),
        ),
        // --- UBAH TITLE MENJADI KOLOM PENCARIAN AKTIF ---
        title: Container(
          height: 40,
          decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(8)),
          child: TextField(
            autofocus: true, // <-- KUNCI: Keyboard langsung muncul!
            decoration: const InputDecoration(
              hintText: 'Cari produk Wowin...',
              border: InputBorder.none,
              prefixIcon: Icon(Icons.search, color: Colors.grey, size: 20),
              contentPadding: EdgeInsets.symmetric(vertical: 10), // Teks pas di tengah
            ),
            onChanged: (value) {
              setState(() {
                _searchQuery = value; // Simpan kata kunci setiap kali mengetik
              });
            },
          ),
        ),
        actions: [
          // --- UBAH IKON KERANJANG DENGAN BADGE ANGKA ---
          IconButton(
            icon: Badge(
              isLabelVisible: ref.watch(cartProvider).items.isNotEmpty,
              label: Text(ref.watch(cartProvider).items.length.toString()),
              backgroundColor: Colors.orange,
              child: const Icon(Icons.shopping_cart_outlined, color: Colors.white),
            ),
            onPressed: () {
              // Arahkan ke CartScreen dengan cek Auth (sama seperti beranda)
              final authState = ref.read(authProvider);
              Navigator.push(context, MaterialPageRoute(
                  builder: (context) => authState.isAuthenticated ? const CartScreen() : const LoginScreen()
              ));
            },
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: Column(
        children: [
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            decoration: BoxDecoration(color: Colors.white, boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 4, offset: const Offset(0, 2))]),
            child: Row(
              children: [
                Expanded(
                  child: Container(
                    height: 40,
                    decoration: BoxDecoration(color: Colors.grey[200], borderRadius: BorderRadius.circular(20)),
                    child: Stack(
                      children: [
                        AnimatedAlign(
                          duration: const Duration(milliseconds: 250),
                          curve: Curves.easeInOut,
                          alignment: _isKartonPrice ? Alignment.centerRight : Alignment.centerLeft,
                          child: FractionallySizedBox(
                            widthFactor: 0.5,
                            child: Container(decoration: BoxDecoration(color: wowinGreen, borderRadius: BorderRadius.circular(20), boxShadow: [BoxShadow(color: wowinGreen.withValues(alpha: 0.3), blurRadius: 4, offset: const Offset(0, 2))])),
                          ),
                        ),
                        Row(
                          children: [
                            Expanded(child: GestureDetector(behavior: HitTestBehavior.opaque, onTap: () => setState(() => _isKartonPrice = false), child: Center(child: Text('Harga Pcs', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: !_isKartonPrice ? Colors.white : Colors.grey[600]))))),
                            Expanded(child: GestureDetector(behavior: HitTestBehavior.opaque, onTap: () => setState(() => _isKartonPrice = true), child: Center(child: Text('Harga Karton', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: _isKartonPrice ? Colors.white : Colors.grey[600]))))),
                          ],
                        ),
                      ],
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                GestureDetector(
                  onTap: () => _showFilterBottomSheet(context),
                  child: Container(
                    height: 40, padding: const EdgeInsets.symmetric(horizontal: 16),
                    decoration: BoxDecoration(border: Border.all(color: Colors.grey[300]!), borderRadius: BorderRadius.circular(20), color: Colors.white),
                    child: Row(children: [Icon(Icons.tune, size: 16, color: Colors.grey[700]), const SizedBox(width: 6), Text('Filter', style: TextStyle(color: Colors.grey[700], fontWeight: FontWeight.bold, fontSize: 13))]),
                  ),
                ),
              ],
            ),
          ),
          Expanded(
            child: catalogState.when(
              loading: () => const Center(child: CircularProgressIndicator(color: wowinGreen)),
              error: (error, stack) => Center(child: Text('Error: $error')),
              data: (data) {
                List<dynamic> products = data['products'] as List<dynamic>? ?? [];

                // 1. Filter Kategori
                if (_activeCategory != 'Semua Kategori') {
                  products = products.where((p) =>
                  p['category']?['name']?.toString().toLowerCase() == _activeCategory.toLowerCase()
                  ).toList();
                }

                // 2. Filter Pencarian Real-Time (JIKA ADA TEKS YANG DIKETIK)
                if (_searchQuery.isNotEmpty) {
                  products = products.where((p) {
                    final namaProduk = p['nama_produk']?.toString().toLowerCase() ?? '';
                    return namaProduk.contains(_searchQuery.toLowerCase());
                  }).toList();
                }

                if (products.isEmpty) {
                  return const Center(child: Text('Produk yang Anda cari tidak ditemukan.', style: TextStyle(color: Colors.grey)));
                }

                return GridView.builder(
                  padding: const EdgeInsets.all(16),
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(crossAxisCount: 2, crossAxisSpacing: 12, mainAxisSpacing: 12, childAspectRatio: 0.55),
                  itemCount: products.length,
                  itemBuilder: (context, index) {
                    final product = products[index];
                    String imageUrl = 'https://via.placeholder.com/150';
                    if (product['images'] != null && product['images'].isNotEmpty) {
                      imageUrl = 'https://mywowin.com/storage/${product['images'][0]['image_url']}';
                    }
                    return _buildProductCard(context, product, imageUrl);
                  },
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildProductCard(BuildContext context, dynamic product, String imageUrl) {
    final num hargaPcs = num.tryParse(product['harga_pcs']?.toString() ?? '0') ?? 0;
    final num hargaKarton = num.tryParse(product['harga']?.toString() ?? '0') ?? 0;
    final String isiKarton = product['isi_karton']?.toString() ?? '1';
    final num currentPrice = _isKartonPrice ? hargaKarton : hargaPcs;
    final String priceLabel = _isKartonPrice ? '/karton (isi $isiKarton)' : '/pcs';

    // --- LABEL KATEGORI DINAMIS ---
    final String namaKategori = product['category']?['name'] ?? 'Produk Pilihan';

    return Container(
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(12), border: Border.all(color: Colors.grey.shade200), boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 8, offset: const Offset(0, 4))]),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(12),
          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => ProductDetailScreen(product: product))),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: Container(
                  padding: const EdgeInsets.all(12.0), // Memberi jarak aman agar gambar tidak mentok pinggir
                  decoration: const BoxDecoration(
                    color: Colors.white, // Latar belakang putih bersih
                    borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
                  ),
                  child: Image.network(
                    imageUrl,
                    fit: BoxFit.contain, // INI KUNCINYA: Gambar akan tampil utuh 100% tanpa terpotong!
                    errorBuilder: (ctx, err, stack) => const Icon(Icons.image, color: Colors.grey, size: 50),
                  ),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(12.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                      decoration: BoxDecoration(color: Colors.orange[50], borderRadius: BorderRadius.circular(4)),
                      child: Text(namaKategori.toUpperCase(), style: TextStyle(color: Colors.orange[800], fontSize: 8, fontWeight: FontWeight.bold)),
                    ),
                    const SizedBox(height: 6),
                    Text(product['nama_produk'] ?? 'Tanpa Nama', maxLines: 2, overflow: TextOverflow.ellipsis, style: const TextStyle(fontSize: 13, height: 1.2, fontWeight: FontWeight.w500)),
                    const SizedBox(height: 8),
                    Text('Rp ${currentPrice.toStringAsFixed(0)}', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: Colors.black87)),
                    Text(priceLabel, style: TextStyle(color: Colors.grey[500], fontSize: 10)),
                    const SizedBox(height: 12),
                    Material(
                      color: Colors.transparent,
                      child: Ink(
                        decoration: BoxDecoration(color: wowinGreen, borderRadius: BorderRadius.circular(8)),
                        child: InkWell(
                          borderRadius: BorderRadius.circular(8),
                          splashColor: Colors.white.withValues(alpha: 0.4),
                          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => ProductDetailScreen(product: product))),
                          child: Container(width: double.infinity, padding: const EdgeInsets.symmetric(vertical: 8), alignment: Alignment.center, child: const Text('+ Keranjang', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12))),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  void _showFilterBottomSheet(BuildContext context) {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (BuildContext context) {
        return Container(
          padding: const EdgeInsets.all(24),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text('Filter Produk', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.pop(context)),
                ],
              ),
              const Divider(),
              const SizedBox(height: 12),
              const Text('Kategori', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
              const SizedBox(height: 12),
              Wrap(
                spacing: 8,
                runSpacing: 8,
                children: [
                  _buildFilterChip('Semua Kategori', true),
                  _buildFilterChip('KM Rajaku', false),
                  _buildFilterChip('KM Jangkar', false),
                  _buildFilterChip('Saos Sambal', false),
                  _buildFilterChip('Cuka', false),
                ],
              ),
              const SizedBox(height: 24),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  style: ElevatedButton.styleFrom(backgroundColor: wowinGreen, padding: const EdgeInsets.symmetric(vertical: 14), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12))),
                  onPressed: () => Navigator.pop(context),
                  child: const Text('Terapkan Filter', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                ),
              )
            ],
          ),
        );
      },
    );
  }

  Widget _buildFilterChip(String label, bool isSelected) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      decoration: BoxDecoration(color: isSelected ? wowinGreen : Colors.white, border: Border.all(color: isSelected ? wowinGreen : Colors.grey[300]!), borderRadius: BorderRadius.circular(20)),
      child: Text(label, style: TextStyle(color: isSelected ? Colors.white : Colors.grey[700], fontSize: 12, fontWeight: isSelected ? FontWeight.bold : FontWeight.normal)),
    );
  }
}