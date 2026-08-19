import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../providers/catalog_provider.dart';
import 'all_products_screen.dart';

class CategoryScreen extends ConsumerWidget {
  const CategoryScreen({super.key});

  static const Color wowinGreen = Color(0xFF1B5E20);

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final catalogState = ref.watch(catalogProvider);

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
                end: Alignment.bottomRight
            ),
          ),
        ),
        title: const Text('Kategori Produk', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 18)),
      ),
      body: catalogState.when(
        loading: () => const Center(child: CircularProgressIndicator(color: wowinGreen)),
        error: (error, stack) => Center(child: Text('Error: $error')),
        data: (data) {
          // Mengambil data kategori dari API Catalog
          final categories = data['categories'] as List<dynamic>? ?? [];

          if (categories.isEmpty) {
            return const Center(child: Text('Belum ada kategori.'));
          }

          return GridView.builder(
            padding: const EdgeInsets.all(16),
            gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisCount: 2,
              crossAxisSpacing: 16,
              mainAxisSpacing: 16,
              childAspectRatio: 0.85,
            ),
            itemCount: categories.length,
            itemBuilder: (context, index) {
              final category = categories[index];
              final catName = category['name'] ?? 'Tanpa Nama';

              // Mengambil gambar dari foto_kategori
              String imageUrl = 'https://via.placeholder.com/150';
              if (category['foto_kategori'] != null) {
                imageUrl = 'https://mywowin.com/storage/${category['foto_kategori']}';
              }

              return GestureDetector(
                onTap: () {
                  // Saat diklik, lempar nama kategori ke halaman AllProductsScreen agar otomatis terfilter
                  Navigator.push(context, MaterialPageRoute(
                      builder: (context) => AllProductsScreen(initialCategory: catName)
                  ));
                },
                child: Container(
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(16),
                    boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 10, offset: const Offset(0, 4))],
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.stretch,
                    children: [
                      Expanded(
                        child: ClipRRect(
                          borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
                          child: Image.network(
                            imageUrl,
                            fit: BoxFit.cover,
                            errorBuilder: (ctx, err, stack) => const Icon(Icons.image, color: Colors.grey, size: 50),
                          ),
                        ),
                      ),
                      Padding(
                        padding: const EdgeInsets.all(12.0),
                        child: Text(
                          catName,
                          textAlign: TextAlign.center,
                          style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: Colors.black87),
                        ),
                      ),
                    ],
                  ),
                ),
              );
            },
          );
        },
      ),
    );
  }
}