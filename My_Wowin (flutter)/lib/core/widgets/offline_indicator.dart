import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../services/connectivity_service.dart';

/// Banner indikator status offline yang muncul otomatis di bagian atas layar saat internet terputus
class OfflineBanner extends ConsumerWidget {
  const OfflineBanner({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final isOnline = ref.watch(isOnlineProvider);

    return AnimatedCrossFade(
      firstChild: const SizedBox.shrink(),
      secondChild: Container(
        width: double.infinity,
        padding: const EdgeInsets.symmetric(vertical: 6, horizontal: 16),
        decoration: BoxDecoration(
          color: const Color(0xFFFFF3E0),
          border: Border(
            bottom: BorderSide(color: Colors.orange.shade300, width: 1),
          ),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.wifi_off_rounded, color: Colors.orange.shade800, size: 14),
            const SizedBox(width: 8),
            Text(
              'Mode Offline — Menampilkan data katalog tersimpan di HP',
              style: TextStyle(
                fontSize: 11,
                fontWeight: FontWeight.w600,
                color: Colors.orange.shade900,
              ),
            ),
          ],
        ),
      ),
      crossFadeState: isOnline ? CrossFadeState.showFirst : CrossFadeState.showSecond,
      duration: const Duration(milliseconds: 300),
    );
  }
}
