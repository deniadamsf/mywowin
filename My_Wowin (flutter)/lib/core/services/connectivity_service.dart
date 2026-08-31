import 'package:connectivity_plus/connectivity_plus.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

/// Provider yang menyediakan Stream status koneksi internet (Online/Offline) secara real-time
final connectivityStreamProvider = StreamProvider<bool>((ref) async* {
  final connectivity = Connectivity();

  // Cek status koneksi pertama kali saat aplikasi dibuka
  final initialResults = await connectivity.checkConnectivity();
  yield _isNetworkConnected(initialResults);

  // Pantau perubahan status koneksi secara berkelanjutan
  await for (final results in connectivity.onConnectivityChanged) {
    yield _isNetworkConnected(results);
  }
});

/// Helper boolean provider untuk mempermudah pembacaan di widget (ref.watch(isOnlineProvider))
final isOnlineProvider = Provider<bool>((ref) {
  final asyncValue = ref.watch(connectivityStreamProvider);
  return asyncValue.value ?? true; // Default anggap online sampai hasil stream didapat
});

bool _isNetworkConnected(List<ConnectivityResult> results) {
  if (results.isEmpty) return false;
  return results.any((result) =>
      result == ConnectivityResult.mobile ||
      result == ConnectivityResult.wifi ||
      result == ConnectivityResult.ethernet ||
      result == ConnectivityResult.vpn);
}
