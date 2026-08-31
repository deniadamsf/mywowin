import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/theme/wowin_theme.dart';
import '../../../core/widgets/wowin_cached_image.dart';
import '../../../core/widgets/offline_indicator.dart';
import '../providers/catalog_provider.dart';
import 'all_products_screen.dart';

class CategoryScreen extends ConsumerWidget {
  const CategoryScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final catalogState = ref.watch(catalogProvider);

    return Scaffold(
      backgroundColor: WowinColors.background,
      appBar: WowinAppBar.standard(title: 'Kategori Produk Wowin'),
      body: Column(
        children: [
          const OfflineBanner(),
          Expanded(
            child: catalogState.when(
              loading: () => const Center(
                child: CircularProgressIndicator(color: WowinColors.accentMint),
              ),
              error: (error, stack) => Center(
                child: Padding(
                  padding: const EdgeInsets.all(24.0),
                  child: Text(
                    'Gagal memuat kategori: $error',
                    textAlign: TextAlign.center,
                    style: const TextStyle(color: WowinColors.textSecondary),
                  ),
                ),
              ),
              data: (data) {
                final categories = data['categories'] as List<dynamic>? ?? [];

                if (categories.isEmpty) {
                  return Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(Icons.category_outlined, size: 70, color: Colors.grey.shade300),
                        const SizedBox(height: 14),
                        const Text(
                          'Belum ada kategori tersedia.',
                          style: TextStyle(color: WowinColors.textSecondary, fontSize: 15, fontWeight: FontWeight.w500),
                        ),
                      ],
                    ),
                  );
                }

                return GridView.builder(
                  padding: const EdgeInsets.all(18),
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 2,
                    crossAxisSpacing: 16,
                    mainAxisSpacing: 16,
                    childAspectRatio: 0.88,
                  ),
                  itemCount: categories.length,
                  itemBuilder: (context, index) {
                    final category = categories[index];
                    final catName = category['name'] ?? 'Kategori';

                    String imageUrl = '';
                    if (category['foto_kategori'] != null && category['foto_kategori'].toString().isNotEmpty) {
                      final foto = category['foto_kategori'].toString();
                      imageUrl = foto.startsWith('http') ? foto : 'https://mywowin.com/storage/$foto';
                    }

                    return Container(
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(20),
                        border: Border.all(color: WowinColors.border, width: 1.1),
                        boxShadow: [
                          BoxShadow(
                            color: WowinColors.primary.withValues(alpha: 0.05),
                            blurRadius: 14,
                            offset: const Offset(0, 6),
                          )
                        ],
                      ),
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(20),
                        child: InkWell(
                          onTap: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) => AllProductsScreen(initialCategory: catName),
                              ),
                            );
                          },
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.stretch,
                            children: [
                              Expanded(
                                child: Stack(
                                  fit: StackFit.expand,
                                  children: [
                                    imageUrl.isNotEmpty
                                        ? WowinCachedImage(
                                            imageUrl: imageUrl,
                                            fit: BoxFit.contain,
                                            errorWidget: Container(
                                              color: WowinColors.primaryDark.withValues(alpha: 0.05),
                                              child: const Icon(Icons.restaurant_rounded, color: WowinColors.primaryLight, size: 40),
                                            ),
                                          )
                                        : Container(
                                            color: WowinColors.primaryDark.withValues(alpha: 0.05),
                                            child: const Icon(Icons.restaurant_rounded, color: WowinColors.primaryLight, size: 40),
                                          ),
                                    Positioned(
                                      bottom: 0,
                                      left: 0,
                                      right: 0,
                                      child: Container(
                                        height: 20,
                                        decoration: BoxDecoration(
                                          gradient: LinearGradient(
                                            colors: [
                                              Colors.transparent,
                                              Colors.black.withValues(alpha: 0.08),
                                            ],
                                            begin: Alignment.topCenter,
                                            end: Alignment.bottomCenter,
                                          ),
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
                                color: Colors.white,
                                child: Column(
                                  mainAxisSize: MainAxisSize.min,
                                  children: [
                                    Text(
                                      catName,
                                      textAlign: TextAlign.center,
                                      maxLines: 1,
                                      overflow: TextOverflow.ellipsis,
                                      style: const TextStyle(
                                        fontWeight: FontWeight.bold,
                                        fontSize: 13.5,
                                        color: WowinColors.textPrimary,
                                      ),
                                    ),
                                    const SizedBox(height: 2),
                                    const Row(
                                      mainAxisAlignment: MainAxisAlignment.center,
                                      children: [
                                        Text(
                                          'Jelajahi',
                                          style: TextStyle(
                                            fontSize: 11,
                                            color: WowinColors.accentMint,
                                            fontWeight: FontWeight.w600,
                                          ),
                                        ),
                                        SizedBox(width: 2),
                                        Icon(Icons.arrow_forward_ios, size: 10, color: WowinColors.accentMint),
                                      ],
                                    ),
                                  ],
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                    );
                  },
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}