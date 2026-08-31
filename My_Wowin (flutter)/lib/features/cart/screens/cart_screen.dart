import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:intl/intl.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../../core/theme/wowin_theme.dart';
import '../providers/cart_provider.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/services/cache_service.dart';
import '../../../core/widgets/wowin_cached_image.dart';
import '../../../core/widgets/offline_indicator.dart';
import '../../order/screens/history_screen.dart';
import '../../order/screens/order_detail_screen.dart';

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
  int _userPoints = 0;
  Map<String, dynamic>? _userProfile;

  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      ref.read(cartProvider.notifier).fetchCart();
      _fetchUserProfile();
    });
  }

  Future<void> _fetchUserProfile() async {
    final cached = await CacheService.getUserProfile();
    if (cached != null && mounted) {
      setState(() {
        _userProfile = cached;
        _userPoints = int.tryParse(cached['total_points']?.toString() ?? '0') ?? 0;
      });
    }

    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');
      if (token == null) return;

      final res = await http.get(
        Uri.parse('$baseUrl/profile'),
        headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'},
      ).timeout(const Duration(seconds: 6));

      if (res.statusCode == 200) {
        final data = json.decode(res.body);
        if (data['data'] != null) {
          await CacheService.saveUserProfile(data['data']);
          if (mounted) {
            setState(() {
              _userProfile = data['data'];
              _userPoints = int.tryParse(data['data']['total_points']?.toString() ?? '0') ?? 0;
            });
          }
        }
      }
    } catch (_) {}
  }

  // --- FUNGSI MEMUNCULKAN JENDELA KONFIRMASI CHECKOUT ---
  void _showCheckoutBottomSheet(BuildContext context, double totalBelanja) {
    String selectedPayment = 'transfer';
    bool usePoints = false;
    final noteController = TextEditingController();
    final cartItems = ref.read(cartProvider).items;

    // Ambil data profil & alamat pengiriman pembeli secara aman (anti-crash)
    final dynamic membership = _userProfile?['membership'];
    final String namaPenerima = (membership != null && membership['nama_toko'] != null && membership['nama_toko'].toString().trim().isNotEmpty)
        ? membership['nama_toko'].toString()
        : ((_userProfile != null && _userProfile!['nama_lengkap'] != null && _userProfile!['nama_lengkap'].toString().trim().isNotEmpty)
            ? _userProfile!['nama_lengkap'].toString()
            : ((_userProfile != null && _userProfile!['name'] != null && _userProfile!['name'].toString().trim().isNotEmpty)
                ? _userProfile!['name'].toString()
                : 'Pembeli Wowin'));
    final String noHp = (membership != null && membership['no_hp'] != null && membership['no_hp'].toString().trim().isNotEmpty)
        ? membership['no_hp'].toString()
        : ((membership != null && membership['nomor_hp'] != null && membership['nomor_hp'].toString().trim().isNotEmpty)
            ? membership['nomor_hp'].toString()
            : ((_userProfile != null && _userProfile!['no_hp'] != null && _userProfile!['no_hp'].toString().trim().isNotEmpty)
                ? _userProfile!['no_hp'].toString()
                : ((_userProfile != null && _userProfile!['phone_number'] != null && _userProfile!['phone_number'].toString().trim().isNotEmpty)
                    ? _userProfile!['phone_number'].toString()
                    : ((_userProfile != null && _userProfile!['phone'] != null && _userProfile!['phone'].toString().trim().isNotEmpty)
                        ? _userProfile!['phone'].toString()
                        : ((_userProfile != null && _userProfile!['no_telp'] != null && _userProfile!['no_telp'].toString().trim().isNotEmpty)
                            ? _userProfile!['no_telp'].toString()
                            : '-')))));
    final String alamatPengiriman = (membership != null && membership['alamat'] != null && membership['alamat'].toString().trim().isNotEmpty)
        ? membership['alamat'].toString()
        : ((_userProfile != null && _userProfile!['alamat'] != null && _userProfile!['alamat'].toString().trim().isNotEmpty)
            ? _userProfile!['alamat'].toString()
            : '-');
    final String levelMembership = (membership != null && membership['level_membership'] != null)
        ? membership['level_membership'].toString().toUpperCase()
        : 'BRONZE';

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
      builder: (BuildContext ctx) {
        return StatefulBuilder(
          builder: (BuildContext context, StateSetter setModalState) {
            final double pointDiscount = (usePoints && _userPoints > 0)
                ? (_userPoints > totalBelanja ? totalBelanja : _userPoints.toDouble())
                : 0.0;
            final double finalAmount = (totalBelanja - pointDiscount).clamp(0.0, double.infinity);

            return Container(
              constraints: BoxConstraints(maxHeight: MediaQuery.of(context).size.height * 0.88),
              padding: EdgeInsets.only(
                bottom: MediaQuery.of(context).viewInsets.bottom + 16,
                left: 20,
                right: 20,
                top: 16,
              ),
              child: SingleChildScrollView(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // --- HEADER KONFIRMASI ---
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Row(
                          children: [
                            Icon(Icons.assignment_turned_in_rounded, color: wowinGreen, size: 22),
                            SizedBox(width: 8),
                            Text('Konfirmasi Pesanan', style: TextStyle(fontSize: 16.5, fontWeight: FontWeight.bold, color: WowinColors.textPrimary)),
                          ],
                        ),
                        IconButton(
                          icon: const Icon(Icons.close, color: Colors.grey),
                          onPressed: () => Navigator.pop(ctx),
                        ),
                      ],
                    ),
                    Text(
                      'Pastikan alamat pengiriman dan rincian belanja Anda sudah sesuai.',
                      style: TextStyle(fontSize: 11.5, color: Colors.grey.shade600),
                    ),
                    const SizedBox(height: 14),

                    // --- 1. KARTU ALAMAT PENGIRIMAN PEMBELI ---
                    Container(
                      padding: const EdgeInsets.all(14),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(14),
                        border: Border.all(color: Colors.green.shade300, width: 1.2),
                        boxShadow: [
                          BoxShadow(
                            color: wowinGreen.withValues(alpha: 0.05),
                            blurRadius: 8,
                            offset: const Offset(0, 2),
                          ),
                        ],
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              const Icon(Icons.location_on_rounded, color: wowinGreen, size: 18),
                              const SizedBox(width: 6),
                              const Text(
                                'Alamat Pengiriman',
                                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: wowinGreen),
                              ),
                              const Spacer(),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2.5),
                                decoration: BoxDecoration(
                                  color: Colors.amber.shade50,
                                  borderRadius: BorderRadius.circular(6),
                                  border: Border.all(color: Colors.amber.shade200),
                                ),
                                child: Text(
                                  levelMembership,
                                  style: TextStyle(fontSize: 9, fontWeight: FontWeight.w900, color: Colors.amber.shade900),
                                ),
                              ),
                            ],
                          ),
                          const Divider(height: 14),
                          Text(
                            namaPenerima,
                            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13.5),
                          ),
                          const SizedBox(height: 2),
                          Text(
                            'No. Telp / WA: $noHp',
                            style: TextStyle(fontSize: 11.5, color: Colors.grey.shade700),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            alamatPengiriman,
                            style: TextStyle(fontSize: 12, color: Colors.grey.shade800, height: 1.3),
                          ),
                          if (alamatPengiriman == '-' || alamatPengiriman.isEmpty) ...[
                            const SizedBox(height: 8),
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                              decoration: BoxDecoration(
                                color: Colors.orange.shade50,
                                borderRadius: BorderRadius.circular(6),
                                border: Border.all(color: Colors.orange.shade200),
                              ),
                              child: Row(
                                children: [
                                  const Icon(Icons.warning_amber_rounded, size: 13, color: Colors.orange),
                                  const SizedBox(width: 6),
                                  Expanded(
                                    child: Text(
                                      'Alamat belum diatur. Silakan lengkapi di profil atau tulis di catatan pesanan.',
                                      style: TextStyle(fontSize: 10, color: Colors.orange.shade900),
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ],
                      ),
                    ),
                    const SizedBox(height: 14),

                    // --- 2. KARTU RINGKASAN PRODUK YANG DIBELI ---
                    if (cartItems.isNotEmpty) ...[
                      Container(
                        padding: const EdgeInsets.all(12),
                        decoration: BoxDecoration(
                          color: Colors.grey.shade50,
                          borderRadius: BorderRadius.circular(12),
                          border: Border.all(color: Colors.grey.shade200),
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Text(
                                  'Daftar Produk (${cartItems.length} Item)',
                                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12.5),
                                ),
                                Text(
                                  _currencyFormat.format(totalBelanja),
                                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12.5, color: wowinGreen),
                                ),
                              ],
                            ),
                            const Divider(height: 12),
                            ...cartItems.take(3).map((item) {
                              final product = item is Map ? item['product'] : null;
                              final bundling = item is Map ? item['bundling'] : null;
                              final String name = (item is Map ? item['product_name']?.toString() : null) ??
                                  (product is Map ? product['nama_produk']?.toString() : null) ??
                                  (bundling is Map ? bundling['nama_bundling']?.toString() : null) ??
                                  'Produk Wowin';
                              final int qty = int.tryParse(item is Map ? item['quantity']?.toString() ?? item['qty']?.toString() ?? '1' : '1') ?? 1;
                              final String unit = (item is Map ? item['unit']?.toString() ?? 'pcs' : 'pcs').toLowerCase();
                              final num singlePrice = num.tryParse(item is Map ? item['price']?.toString() ?? '0' : '0') ?? 0;
                              final num itemPrice = singlePrice * qty;

                              return Padding(
                                padding: const EdgeInsets.only(bottom: 4),
                                child: Row(
                                  children: [
                                    Expanded(
                                      child: Text(
                                        '• $name ($qty $unit)',
                                        maxLines: 1,
                                        overflow: TextOverflow.ellipsis,
                                        style: TextStyle(fontSize: 11, color: Colors.grey.shade800),
                                      ),
                                    ),
                                    Text(
                                      _currencyFormat.format(itemPrice),
                                      style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600),
                                    ),
                                  ],
                                ),
                              );
                            }),
                            if (cartItems.length > 3)
                              Padding(
                                padding: const EdgeInsets.only(top: 2),
                                child: Text(
                                  '+ ${cartItems.length - 3} produk lainnya dalam keranjang',
                                  style: TextStyle(fontSize: 10.5, fontStyle: FontStyle.italic, color: Colors.grey.shade600),
                                ),
                              ),
                          ],
                        ),
                      ),
                      const SizedBox(height: 14),
                    ],

                    // --- 3. OPSI TUKAR POIN LOYALITAS ---
                    if (_userPoints > 0) ...[
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                        decoration: BoxDecoration(
                          color: const Color(0xFFFFF8E1),
                          borderRadius: BorderRadius.circular(12),
                          border: Border.all(color: const Color(0xFFFFD54F)),
                        ),
                        child: Row(
                          children: [
                            const Icon(Icons.stars_rounded, color: Color(0xFFFFA000), size: 28),
                            const SizedBox(width: 10),
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  const Text(
                                    'Gunakan Poin Loyalitas',
                                    style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFF5D4037)),
                                  ),
                                  Text(
                                    'Tersedia: $_userPoints Poin (Hemat ${_currencyFormat.format(_userPoints > totalBelanja ? totalBelanja : _userPoints)})',
                                    style: TextStyle(fontSize: 11, color: Colors.grey.shade700),
                                  ),
                                ],
                              ),
                            ),
                            Switch(
                              value: usePoints,
                              activeThumbColor: wowinGreen,
                              onChanged: (val) => setModalState(() => usePoints = val),
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(height: 14),
                    ],

                    // --- 4. PILIHAN METODE PEMBAYARAN ---
                    const Text('Metode Pembayaran:', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                    const SizedBox(height: 8),
                    Container(
                      decoration: BoxDecoration(border: Border.all(color: Colors.grey.shade300), borderRadius: BorderRadius.circular(12)),
                      child: RadioGroup<String>(
                        groupValue: selectedPayment,
                        onChanged: (val) {
                          if (val != null) setModalState(() => selectedPayment = val);
                        },
                        child: Column(
                          children: [
                            RadioListTile<String>(
                              title: const Text('Transfer Bank (BCA / BRI)', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600)),
                              subtitle: Text('Instruksi rekening muncul setelah konfirmasi', style: TextStyle(fontSize: 10.5, color: Colors.grey.shade600)),
                              value: 'transfer',
                              activeColor: wowinGreen,
                            ),
                            const Divider(height: 1),
                            RadioListTile<String>(
                              title: const Text('Cash on Delivery (COD)', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600)),
                              subtitle: Text('Bayar tunai ke kurir saat barang tiba', style: TextStyle(fontSize: 10.5, color: Colors.grey.shade600)),
                              value: 'cod',
                              activeColor: wowinGreen,
                            ),
                            const Divider(height: 1),
                            RadioListTile<String>(
                              title: const Text('Pesan via WhatsApp', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600)),
                              subtitle: Text('Langsung terhubung dengan Admin Wowin', style: TextStyle(fontSize: 10.5, color: Colors.grey.shade600)),
                              value: 'wa',
                              activeColor: wowinGreen,
                            ),
                          ],
                        ),
                      ),
                    ),

                    const SizedBox(height: 14),
                    const Text('Catatan Tambahan (Opsional):', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                    const SizedBox(height: 6),
                    TextField(
                      controller: noteController,
                      maxLines: 2,
                      decoration: InputDecoration(
                        hintText: 'Contoh: Titip di pos satpam atau patokan toko...',
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: wowinGreen)),
                        contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                      ),
                    ),
                    const SizedBox(height: 14),

                    // --- 5. RINCIAN NOMINAL TRANSAKSI LENGKAP ---
                    Container(
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: Colors.grey.shade50,
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: Colors.grey.shade200),
                      ),
                      child: Column(
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text('Subtotal Belanja', style: TextStyle(fontSize: 12, color: Colors.grey.shade600)),
                              Text(_currencyFormat.format(totalBelanja), style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600)),
                            ],
                          ),
                          const SizedBox(height: 4),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text('Ongkos Kirim', style: TextStyle(fontSize: 12, color: Colors.grey.shade600)),
                              const Text('Gratis (Kurir Wowin)', style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: wowinGreen)),
                            ],
                          ),
                          if (usePoints && pointDiscount > 0) ...[
                            const SizedBox(height: 4),
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                const Text('Potongan Poin', style: TextStyle(fontSize: 12, color: Color(0xFF2E7D32), fontWeight: FontWeight.w600)),
                                Text('- ${_currencyFormat.format(pointDiscount)}', style: const TextStyle(fontSize: 12, color: Color(0xFF2E7D32), fontWeight: FontWeight.bold)),
                              ],
                            ),
                          ],
                          const Divider(height: 14),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              const Text('Total Pembayaran', style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold)),
                              Text(_currencyFormat.format(finalAmount), style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.orange)),
                            ],
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 18),

                    // --- 6. TOMBOL KONFIRMASI & BUAT PESANAN ---
                    SizedBox(
                      width: double.infinity,
                      height: 50,
                      child: ElevatedButton(
                        style: ElevatedButton.styleFrom(
                          backgroundColor: wowinGreen,
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                          elevation: 2,
                        ),
                        onPressed: _isProcessingCheckout ? null : () async {
                          Navigator.pop(ctx); // Tutup modal konfirmasi
                          await _processCheckout(selectedPayment, noteController.text, usePoints);
                        },
                        child: _isProcessingCheckout
                            ? const SizedBox(width: 24, height: 24, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5))
                            : const Text('Konfirmasi & Buat Pesanan', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 15)),
                      ),
                    ),
                    const SizedBox(height: 16),
                  ],
                ),
              ),
            );
          },
        );
      },
    );
  }

  // --- FUNGSI MENGIRIM DATA KE SERVER / OFFLINE FALLBACK ---
  Future<void> _processCheckout(String paymentMethod, String note, bool usePoints) async {
    setState(() => _isProcessingCheckout = true);

    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');

      final response = await http.post(
        Uri.parse('$baseUrl/checkout'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: {
          'metode_pembayaran': paymentMethod,
          'catatan': note,
          'use_points': usePoints ? '1' : '0',
        },
      ).timeout(const Duration(seconds: 8));

      final data = json.decode(response.body);

      if (response.statusCode == 200) {
        if (!mounted) return;
        ref.read(cartProvider.notifier).fetchCart(); // Refresh keranjang
        _fetchUserProfile(); // Refresh saldo poin & profil
        final orderData = data['data'];
        _showOrderSuccessModal(context, orderData, paymentMethod);
      } else {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(data['message'] ?? 'Gagal membuat pesanan'), backgroundColor: Colors.red),
        );
      }
    } catch (e) {
      if (!mounted) return;
      // Jika jaringan gagal / offline, tampilkan modal opsi draf & WhatsApp
      _showOfflineOrderOptionsModal(context, paymentMethod, note);
    } finally {
      if (mounted) setState(() => _isProcessingCheckout = false);
    }
  }

  // --- MODAL PENANGANAN CHECKOUT SAAT OFFLINE ---
  void _showOfflineOrderOptionsModal(BuildContext context, String paymentMethod, String note) {
    final cartItems = ref.read(cartProvider).items;
    final double subtotal = ref.read(cartProvider).subtotal;

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
      builder: (ctx) {
        return Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                width: 60,
                height: 60,
                decoration: BoxDecoration(
                  color: Colors.orange.shade50,
                  shape: BoxShape.circle,
                  border: Border.all(color: Colors.orange.shade200),
                ),
                child: const Icon(Icons.wifi_off_rounded, color: Colors.orange, size: 32),
              ),
              const SizedBox(height: 16),
              const Text(
                'Koneksi Internet Terputus',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: WowinColors.textPrimary),
              ),
              const SizedBox(height: 8),
              Text(
                'Pesanan belum bisa dikirim ke server pusat secara online. Anda dapat langsung mengirim draf pesanan ini ke WhatsApp Admin Wowin sekarang.',
                textAlign: TextAlign.center,
                style: TextStyle(fontSize: 13, color: Colors.grey.shade600, height: 1.4),
              ),
              const SizedBox(height: 20),
              // Tombol Kirim WA Langsung
              SizedBox(
                width: double.infinity,
                child: ElevatedButton.icon(
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF2E7D32),
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                  onPressed: () {
                    Navigator.pop(ctx);
                    _sendOfflineOrderToWhatsApp(cartItems, subtotal, paymentMethod, note);
                  },
                  icon: const Icon(Icons.chat_rounded, color: Colors.white, size: 18),
                  label: const Text('Kirim Pesanan via WhatsApp Sekarang', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 13.5)),
                ),
              ),
              const SizedBox(height: 10),
              // Tombol Simpan Draf
              SizedBox(
                width: double.infinity,
                child: OutlinedButton(
                  style: OutlinedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    side: BorderSide(color: Colors.grey.shade300),
                  ),
                  onPressed: () {
                    Navigator.pop(ctx);
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(
                        content: Text('Draf belanja tersimpan aman di keranjang Anda.'),
                        backgroundColor: wowinGreen,
                      ),
                    );
                  },
                  child: Text('Simpan di Keranjang (Checkout Nanti)', style: TextStyle(color: Colors.grey.shade700, fontWeight: FontWeight.bold, fontSize: 13)),
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  Future<void> _sendOfflineOrderToWhatsApp(List<dynamic> items, double subtotal, String paymentMethod, String note) async {
    const String waNumber = '6281216301220';
    final dynamic membership = _userProfile?['membership'];
    final String nama = (membership != null && membership['nama_toko'] != null && membership['nama_toko'].toString().trim().isNotEmpty)
        ? membership['nama_toko'].toString()
        : (_userProfile?['nama_lengkap'] ?? _userProfile?['name'] ?? 'Mitra Wowin');
    final String hp = (membership != null && membership['no_hp'] != null && membership['no_hp'].toString().trim().isNotEmpty)
        ? membership['no_hp'].toString()
        : (membership?['nomor_hp'] ?? _userProfile?['no_hp'] ?? _userProfile?['phone_number'] ?? _userProfile?['phone'] ?? _userProfile?['no_telp'] ?? '-');
    final String alamat = (membership != null && membership['alamat'] != null && membership['alamat'].toString().trim().isNotEmpty)
        ? membership['alamat'].toString()
        : (_userProfile?['alamat'] ?? '-');

    final StringBuffer sb = StringBuffer();
    sb.writeln('Halo Admin Wowin Food, saya ingin membuat pesanan (Mode Offline):');
    sb.writeln('');
    sb.writeln('👤 *Nama Pembeli:* $nama');
    sb.writeln('📞 *No HP/WA:* $hp');
    sb.writeln('📍 *Alamat:* $alamat');
    sb.writeln('');
    sb.writeln('🛒 *Rincian Belanja:*');

    for (var item in items) {
      final product = item is Map ? item['product'] : null;
      final bundling = item is Map ? item['bundling'] : null;
      final String name = (item is Map ? item['product_name']?.toString() : null) ??
          (product is Map ? product['nama_produk']?.toString() : null) ??
          (bundling is Map ? bundling['nama_bundling']?.toString() : null) ??
          'Produk';
      final int qty = int.tryParse(item is Map ? item['quantity']?.toString() ?? '1' : '1') ?? 1;
      final String unit = item is Map ? item['unit']?.toString() ?? 'pcs' : 'pcs';
      final num price = num.tryParse(item is Map ? item['price']?.toString() ?? '0' : '0') ?? 0;
      final num itemTotal = price * qty;
      sb.writeln('• $name ($qty $unit) - ${_currencyFormat.format(itemTotal)}');
    }

    sb.writeln('');
    sb.writeln('💰 *Total Estimasi:* ${_currencyFormat.format(subtotal)}');
    sb.writeln('💳 *Metode Pembayaran:* ${paymentMethod.toUpperCase()}');
    if (note.trim().isNotEmpty) {
      sb.writeln('📝 *Catatan:* $note');
    }

    final Uri url = Uri.parse('https://wa.me/$waNumber?text=${Uri.encodeComponent(sb.toString())}');
    if (await canLaunchUrl(url)) {
      await launchUrl(url, mode: LaunchMode.externalApplication);
    }
  }

  // --- MODAL DIALOG SUKSES CHECKOUT & INSTRUKSI PEMBAYARAN ---
  void _showOrderSuccessModal(BuildContext context, dynamic orderData, String paymentMethod) {
    final invoiceNumber = orderData?['invoice_number'] ?? 'INV-${DateTime.now().millisecondsSinceEpoch}';
    final total = num.tryParse(orderData?['total']?.toString() ?? orderData?['paid_amount']?.toString() ?? '0') ?? 0;
    final formattedTotal = _currencyFormat.format(total);

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      isDismissible: false,
      enableDrag: false,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
      builder: (BuildContext sheetCtx) {
        return SafeArea(
          child: Padding(
            padding: const EdgeInsets.fromLTRB(20, 24, 20, 20),
            child: SingleChildScrollView(
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                  // Icon Sukses
                  Container(
                    width: 64,
                    height: 64,
                    decoration: BoxDecoration(
                      color: const Color(0xFFE8F5E9),
                      shape: BoxShape.circle,
                      border: Border.all(color: const Color(0xFFA5D6A7), width: 2),
                    ),
                    child: const Icon(Icons.check_circle_rounded, color: wowinGreen, size: 40),
                  ),
                  const SizedBox(height: 14),

                  const Text(
                    'Pesanan Berhasil Dibuat!',
                    style: TextStyle(fontSize: 19, fontWeight: FontWeight.bold, color: Color(0xFF1B5E20)),
                  ),
                  const SizedBox(height: 6),
                  Text(
                    'Nomor Pesanan: $invoiceNumber',
                    style: TextStyle(fontSize: 12, color: Colors.grey.shade600, fontWeight: FontWeight.w600),
                  ),
                  const SizedBox(height: 12),

                  // Badge Status
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                    decoration: BoxDecoration(
                      color: const Color(0xFFFFF3E0),
                      borderRadius: BorderRadius.circular(20),
                      border: Border.all(color: const Color(0xFFFFCC80)),
                    ),
                    child: const Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(Icons.hourglass_top_rounded, color: Colors.orange, size: 14),
                        SizedBox(width: 6),
                        Text(
                          'STATUS: BELUM BAYAR / MENUNGGU PEMBAYARAN',
                          style: TextStyle(color: Color(0xFFE65100), fontSize: 10.5, fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 16),

                  // Ringkasan Total Tagihan
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 16),
                    decoration: BoxDecoration(
                      color: Colors.grey.shade50,
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(color: Colors.grey.shade200),
                    ),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Text('Total Tagihan', style: TextStyle(fontSize: 13, color: Colors.grey)),
                        Text(
                          formattedTotal,
                          style: const TextStyle(fontSize: 17, fontWeight: FontWeight.w900, color: Colors.orange),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 16),

                  // --- KARTU INSTRUKSI BERDASARKAN METODE PEMBAYARAN ---
                  if (paymentMethod == 'transfer') ...[
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.all(14),
                      decoration: BoxDecoration(
                        color: const Color(0xFFE3F2FD),
                        borderRadius: BorderRadius.circular(14),
                        border: Border.all(color: const Color(0xFF90CAF9)),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Row(
                            children: [
                              Icon(Icons.account_balance_rounded, color: Color(0xFF1565C0), size: 18),
                              SizedBox(width: 8),
                              Text(
                                'Rekening Tujuan Transfer',
                                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFF0D47A1)),
                              ),
                            ],
                          ),
                          const SizedBox(height: 10),
                          _buildBankItem(context, 'Bank BCA', '0891234567', 'PT WOWIN PURNOMO PUTERA'),
                          const Divider(height: 16, color: Color(0xFFBBDEFB)),
                          _buildBankItem(context, 'Bank BRI', '0123-01-000456-53-0', 'PT SANKE BERSINAR TERANG'),
                          const SizedBox(height: 8),
                          Text(
                            'Catatan: Silakan transfer tepat $formattedTotal lalu konfirmasi via WhatsApp.',
                            style: TextStyle(fontSize: 11, fontStyle: FontStyle.italic, color: Colors.grey.shade700),
                          ),
                        ],
                      ),
                    ),
                  ] else if (paymentMethod == 'cod') ...[
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.all(14),
                      decoration: BoxDecoration(
                        color: const Color(0xFFFFF8E1),
                        borderRadius: BorderRadius.circular(14),
                        border: Border.all(color: const Color(0xFFFFD54F)),
                      ),
                      child: Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Icon(Icons.local_shipping_rounded, color: Color(0xFFF57F17), size: 28),
                          const SizedBox(width: 10),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                const Text(
                                  'Cash on Delivery (COD)',
                                  style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFFE65100)),
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  'Pesanan akan disiapkan dan diantar ke alamat Anda. Mohon siapkan uang pas sebesar $formattedTotal saat kurir tiba.',
                                  style: TextStyle(fontSize: 11.5, color: Colors.grey.shade800),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                  ] else ...[
                    // Pesan via WA
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.all(14),
                      decoration: BoxDecoration(
                        color: const Color(0xFFE8F5E9),
                        borderRadius: BorderRadius.circular(14),
                        border: Border.all(color: const Color(0xFFA5D6A7)),
                      ),
                      child: Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Icon(Icons.chat_rounded, color: wowinGreen, size: 28),
                          const SizedBox(width: 10),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                const Text(
                                  'Pesan via WhatsApp',
                                  style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: wowinGreen),
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  'Pesanan tersimpan di sistem. Klik tombol WhatsApp di bawah untuk konfirmasi langsung ke Admin Wowin.',
                                  style: TextStyle(fontSize: 11.5, color: Colors.grey.shade800),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],

                  const SizedBox(height: 20),

                  // Tombol WhatsApp
                  SizedBox(
                    width: double.infinity,
                    height: 46,
                    child: OutlinedButton.icon(
                      style: OutlinedButton.styleFrom(
                        foregroundColor: const Color(0xFF1B5E20),
                        side: const BorderSide(color: Color(0xFF2E7D32), width: 1.5),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      ),
                      onPressed: () => _openWhatsAppOrder(invoiceNumber, formattedTotal, paymentMethod),
                      icon: const Icon(Icons.chat_rounded, color: Color(0xFF2E7D32), size: 18),
                      label: Text(
                        paymentMethod == 'transfer'
                            ? 'Konfirmasi Bayar via WhatsApp'
                            : (paymentMethod == 'cod' ? 'Hubungi Admin via WhatsApp' : 'Kirim Pesanan ke WhatsApp'),
                        style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
                      ),
                    ),
                  ),
                  const SizedBox(height: 10),

                  // Tombol Ke Riwayat Pesanan (Tombol Utama)
                  SizedBox(
                    width: double.infinity,
                    height: 48,
                    child: ElevatedButton.icon(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: wowinGreen,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                        elevation: 2,
                      ),
                      onPressed: () {
                        Navigator.pop(sheetCtx); // Tutup modal
                        Navigator.pushReplacement(
                          context,
                          MaterialPageRoute(
                            builder: (context) => orderData != null
                                ? OrderDetailScreen(order: orderData)
                                : const HistoryScreen(),
                          ),
                        );
                      },
                      icon: const Icon(Icons.receipt_long_rounded, color: Colors.white, size: 18),
                      label: const Text(
                        'Lihat Riwayat & Lacak Pesanan',
                        style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 14),
                      ),
                    ),
                  ),
                  const SizedBox(height: 8),

                  // Tombol Tutup / Beranda
                  TextButton(
                    onPressed: () {
                      Navigator.pop(sheetCtx);
                      Navigator.pop(context); // Kembali ke katalog
                    },
                    child: Text('Kembali ke Beranda', style: TextStyle(color: Colors.grey.shade600, fontSize: 12)),
                  ),
                ],
              ),
            ),
          ),
        );
      },
    );
  }

  Widget _buildBankItem(BuildContext context, String bankName, String accountNumber, String accountHolder) {
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

  Future<void> _openWhatsAppOrder(String invoice, String total, String method) async {
    const String waNumber = '6281216301220';
    final dynamic membership = _userProfile?['membership'];
    final String nama = (membership != null && membership['nama_toko'] != null && membership['nama_toko'].toString().trim().isNotEmpty)
        ? membership['nama_toko'].toString()
        : (_userProfile?['nama_lengkap'] ?? _userProfile?['name'] ?? 'Mitra Wowin');
    final String hp = (membership != null && membership['no_hp'] != null && membership['no_hp'].toString().trim().isNotEmpty)
        ? membership['no_hp'].toString()
        : (membership?['nomor_hp'] ?? _userProfile?['no_hp'] ?? _userProfile?['phone_number'] ?? _userProfile?['phone'] ?? _userProfile?['no_telp'] ?? '-');

    String message = 'Halo Admin Wowin Food, saya baru saja membuat pesanan:\n\n'
        '• *No. Invoice:* $invoice\n'
        '• *Nama / Toko:* $nama\n'
        '• *No. HP/WA:* $hp\n'
        '• *Total:* $total\n'
        '• *Metode:* ${method.toUpperCase()}\n\n'
        'Mohon bantuan untuk konfirmasi dan proses pesanan saya. Terima kasih!';
    final Uri url = Uri.parse('https://wa.me/$waNumber?text=${Uri.encodeComponent(message)}');
    if (await canLaunchUrl(url)) {
      await launchUrl(url, mode: LaunchMode.externalApplication);
    }
  }

  final NumberFormat _currencyFormat = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

  // --- DIALOG KONFIRMASI HAPUS SATU ITEM ---
  void _confirmDeleteItem(int cartId, String itemName) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Hapus Item?', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18)),
        content: Text('Apakah Anda yakin ingin menghapus "$itemName" dari keranjang?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Batal', style: TextStyle(color: Colors.grey)),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.red,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
            ),
            onPressed: () async {
              Navigator.pop(ctx);
              final success = await ref.read(cartProvider.notifier).removeItem(cartId: cartId);
              if (mounted && success) {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Item berhasil dihapus dari keranjang.'), duration: Duration(seconds: 2)),
                );
              }
            },
            child: const Text('Hapus', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }

  // --- DIALOG KONFIRMASI KOSONGKAN KERANJANG ---
  void _confirmClearCart() {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Kosongkan Keranjang?', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18)),
        content: const Text('Seluruh produk di dalam keranjang belanja akan dihapus. Lanjutkan?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Batal', style: TextStyle(color: Colors.grey)),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.red,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
            ),
            onPressed: () async {
              Navigator.pop(ctx);
              final success = await ref.read(cartProvider.notifier).clearCart();
              if (mounted && success) {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Keranjang berhasil dikosongkan.'), duration: Duration(seconds: 2)),
                );
              }
            },
            child: const Text('Kosongkan', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final cartState = ref.watch(cartProvider);

    return Scaffold(
      backgroundColor: WowinColors.background,
      appBar: WowinAppBar.standard(
        title: 'Keranjang Belanja',
        actions: [
          if (cartState.items.isNotEmpty)
            IconButton(
              tooltip: 'Kosongkan Keranjang',
              icon: const Icon(Icons.delete_sweep_outlined, color: Colors.white, size: 22),
              onPressed: _confirmClearCart,
            ),
        ],
      ),
      body: Column(
        children: [
          const OfflineBanner(),
          Expanded(
            child: cartState.isLoading || _isProcessingCheckout
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
                  Text('Keranjang Anda masih kosong', style: TextStyle(fontSize: 16, color: Colors.grey[600], fontWeight: FontWeight.w500)),
                  const SizedBox(height: 8),
                  Text('Pilih produk dari katalog untuk mulai belanja', style: TextStyle(fontSize: 13, color: Colors.grey[400])),
                ],
              ),
            )
                : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: cartState.items.length,
              itemBuilder: (context, index) {
                final item = cartState.items[index];
                final int cartId = int.tryParse(item['id'].toString()) ?? 0;
                final product = item['product'];
                final bundling = item['bundling'];

                final int qty = int.tryParse(item['quantity'].toString()) ?? 0;
                final double price = double.tryParse(item['price'].toString()) ?? 0.0;
                final double subtotalItem = qty * price;

                String imageUrl = '';
                String itemName = 'Item Wowin';
                bool isPromo = false;

                if (product != null) {
                  itemName = product['nama_produk'] ?? 'Produk Wowin';
                  if (product['images'] != null && product['images'] is List && (product['images'] as List).isNotEmpty) {
                    final imgPath = product['images'][0]['image_url']?.toString() ?? '';
                    if (imgPath.isNotEmpty) {
                      imageUrl = imgPath.startsWith('http') ? imgPath : 'https://mywowin.com/storage/$imgPath';
                    }
                  }
                } else if (bundling != null) {
                  isPromo = true;
                  itemName = bundling['nama_bundling'] ?? 'Promo Spesial';
                  if (bundling['barang_bundling'] != null && bundling['barang_bundling'].toString().isNotEmpty) {
                    final imgPath = bundling['barang_bundling'].toString();
                    imageUrl = imgPath.startsWith('http') ? imgPath : 'https://mywowin.com/storage/$imgPath';
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
                            child: WowinCachedImage(
                              imageUrl: imageUrl,
                              width: 85,
                              height: 85,
                              fit: BoxFit.cover,
                              errorWidget: Container(
                                width: 85,
                                height: 85,
                                color: Colors.grey[100],
                                child: const Icon(Icons.image, color: Colors.grey),
                              ),
                            ),
                          ),
                        ),
                        const SizedBox(width: 14),

                  // --- AREA DETAIL TEKS & KONTROL ---
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Label Penanda & Tombol Hapus
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
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
                            InkWell(
                              onTap: () => _confirmDeleteItem(cartId, itemName),
                              borderRadius: BorderRadius.circular(20),
                              child: Padding(
                                padding: const EdgeInsets.all(4.0),
                                child: Icon(Icons.delete_outline, size: 20, color: Colors.red.shade400),
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 6),

                        // Nama Item
                        Text(itemName, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, height: 1.2), maxLines: 2, overflow: TextOverflow.ellipsis),
                        const SizedBox(height: 6),

                        // Harga Satuan & Unit
                        Text(
                          '${_currencyFormat.format(price)} / ${item['unit']}',
                          style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: isPromo ? Colors.red.shade600 : Colors.orange.shade800),
                        ),
                        const SizedBox(height: 10),

                        // Baris Subtotal & Quantity Stepper
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          crossAxisAlignment: CrossAxisAlignment.center,
                          children: [
                            // Total per Item
                            Text(
                              _currencyFormat.format(subtotalItem),
                              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: isPromo ? Colors.red.shade700 : wowinGreen),
                            ),

                            // Stepper Kontrol: [-] [qty] [+]
                            Container(
                              decoration: BoxDecoration(
                                color: Colors.grey[100],
                                borderRadius: BorderRadius.circular(10),
                                border: Border.all(color: Colors.grey.shade300),
                              ),
                              child: Row(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  // Tombol Kurang (-)
                                  InkWell(
                                    onTap: () {
                                      if (qty > 1) {
                                        ref.read(cartProvider.notifier).updateQuantity(cartId: cartId, newQuantity: qty - 1);
                                      } else {
                                        _confirmDeleteItem(cartId, itemName);
                                      }
                                    },
                                    borderRadius: const BorderRadius.horizontal(left: Radius.circular(10)),
                                    child: Padding(
                                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                      child: Icon(qty > 1 ? Icons.remove : Icons.delete_outline, size: 16, color: qty > 1 ? Colors.grey[800] : Colors.red),
                                    ),
                                  ),

                                  // Teks Jumlah
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                                    child: Text(
                                      '$qty',
                                      style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13),
                                    ),
                                  ),

                                  // Tombol Tambah (+)
                                  InkWell(
                                    onTap: () {
                                      ref.read(cartProvider.notifier).updateQuantity(cartId: cartId, newQuantity: qty + 1);
                                    },
                                    borderRadius: const BorderRadius.horizontal(right: Radius.circular(10)),
                                    child: Padding(
                                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                      child: Icon(Icons.add, size: 16, color: Colors.grey[800]),
                                    ),
                                  ),
                                ],
                              ),
                            ),
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
    ),
  ],
),

      bottomNavigationBar: SafeArea(
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
          decoration: BoxDecoration(
            color: Colors.white,
            boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), spreadRadius: 1, blurRadius: 10, offset: const Offset(0, -5))],
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
                  Text(_currencyFormat.format(cartState.subtotal), style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.orange)),
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