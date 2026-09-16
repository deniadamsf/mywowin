import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/theme/wowin_theme.dart';
import 'package:intl/intl.dart';
import 'order_detail_screen.dart';
import 'review_order_screen.dart';

class HistoryScreen extends StatefulWidget {
  final bool showBackButton;
  const HistoryScreen({super.key, this.showBackButton = true});

  @override
  State<HistoryScreen> createState() => _HistoryScreenState();
}

class _HistoryScreenState extends State<HistoryScreen> {
  static const Color wowinGreen = WowinColors.primary;

  List<dynamic> _orders = [];
  bool _isLoading = true;
  String? _errorMessage;

  @override
  void initState() {
    super.initState();
    _fetchOrderHistory();
  }

  Future<void> _fetchOrderHistory() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');

      if (token == null) {
        if (mounted) {
          setState(() {
            _errorMessage = "Silakan login terlebih dahulu untuk melihat pesanan.";
            _isLoading = false;
          });
        }
        return;
      }

      final response = await http.get(
        Uri.parse('$baseUrl/orders'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      ).timeout(const Duration(seconds: 8));

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (mounted) {
          setState(() {
            _orders = data['data'] ?? [];
            _isLoading = false;
            _errorMessage = null;
          });
        }
      } else {
        if (mounted) {
          setState(() {
            _errorMessage = "Gagal memuat data pesanan.";
            _isLoading = false;
          });
        }
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _errorMessage = "Terjadi kesalahan jaringan atau koneksi lambat.";
          _isLoading = false;
        });
      }
    } finally {
      if (mounted && _isLoading) {
        setState(() => _isLoading = false);
      }
    }
  }

  // Fungsi cerdas untuk memberi warna status
  Color _getStatusColor(dynamic order) {
    final status = (order['status'] ?? '').toString().toLowerCase();
    final paymentStatus = (order['payment_status'] ?? '').toString().toLowerCase();
    final hasProof = order['bukti_transfer'] != null && order['bukti_transfer'].toString().isNotEmpty;

    if (status == 'canceled' || paymentStatus == 'expired') {
      return Colors.red;
    }
    if (paymentStatus == 'waiting_confirmation' || (hasProof && paymentStatus != 'paid' && status != 'paid')) {
      return Colors.blue.shade700;
    }
    if (paymentStatus == 'rejected') {
      return Colors.deepOrange;
    }
    if (paymentStatus == 'paid' || status == 'paid' || status == 'completed' || status == 'dikirim' || status == 'selesai') {
      return wowinGreen;
    }
    return Colors.orange;
  }

  String _getStatusText(dynamic order) {
    final status = (order['status'] ?? '').toString().toLowerCase();
    final paymentStatus = (order['payment_status'] ?? '').toString().toLowerCase();
    final hasProof = order['bukti_transfer'] != null && order['bukti_transfer'].toString().isNotEmpty;

    if (status == 'canceled' || paymentStatus == 'expired') {
      return 'DIBATALKAN';
    }
    if (paymentStatus == 'waiting_confirmation' || (hasProof && paymentStatus != 'paid' && status != 'paid')) {
      return 'MENUNGGU VERIFIKASI';
    }
    if (paymentStatus == 'rejected') {
      return 'BUKTI DITOLAK';
    }
    if (paymentStatus == 'paid' || status == 'paid' || status == 'completed' || status == 'selesai') {
      return 'LUNAS / SELESAI';
    }
    if (status == 'shipped' || status == 'dikirim') {
      return 'DIKIRIM';
    }
    return 'BELUM BAYAR';
  }

  // Fungsi untuk memformat tanggal
  String _formatDate(String dateString) {
    try {
      final date = DateTime.parse(dateString);
      return DateFormat('dd MMM yyyy, HH:mm').format(date);
    } catch (e) {
      return dateString;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: WowinColors.background,
      appBar: WowinAppBar.standard(
        title: 'Riwayat Pesanan',
        automaticallyImplyLeading: widget.showBackButton,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator(color: wowinGreen))
          : _errorMessage != null
          ? Center(child: Text(_errorMessage!, style: const TextStyle(color: Colors.grey)))
          : _orders.isEmpty
          ? Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.receipt_long_outlined, size: 80, color: Colors.grey[300]),
            const SizedBox(height: 16),
            Text('Belum ada riwayat pesanan', style: TextStyle(fontSize: 16, color: Colors.grey[600])),
          ],
        ),
      )
          : RefreshIndicator(
        color: wowinGreen,
        onRefresh: _fetchOrderHistory,
        child: ListView.builder(
          padding: const EdgeInsets.all(16),
          itemCount: _orders.length,
          itemBuilder: (context, index) {
            final order = _orders[index];
            final statusColor = _getStatusColor(order);
            final statusText = _getStatusText(order);

            return Container(
              margin: const EdgeInsets.only(bottom: 16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: Colors.grey.shade200),
                boxShadow: [
                  BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 10, offset: const Offset(0, 4))
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // --- HEADER CARD (Berwarna sedikit lebih gelap) ---
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                    decoration: BoxDecoration(
                      color: Colors.grey.shade50,
                      borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
                      border: Border(bottom: BorderSide(color: Colors.grey.shade100)),
                    ),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Row(
                          children: [
                            const Icon(Icons.receipt_long, color: wowinGreen, size: 20),
                            const SizedBox(width: 8),
                            Text(
                              order['invoice_number'] ?? 'INV-UNKNOWN',
                              style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13, letterSpacing: 0.5),
                            ),
                          ],
                        ),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                          decoration: BoxDecoration(
                            color: statusColor.withValues(alpha: 0.1),
                            borderRadius: BorderRadius.circular(20),
                          ),
                          child: Text(
                            statusText,
                            style: TextStyle(color: statusColor, fontSize: 10, fontWeight: FontWeight.bold),
                          ),
                        ),
                      ],
                    ),
                  ),

                  // --- BODY CARD (Isi Pesanan) ---
                  Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            Container(
                              padding: const EdgeInsets.all(10),
                              decoration: BoxDecoration(
                                color: Colors.green.shade50,
                                shape: BoxShape.circle,
                              ),
                              child: const Icon(Icons.local_shipping_outlined, color: wowinGreen, size: 24),
                            ),
                            const SizedBox(width: 16),
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text('Waktu Pemesanan', style: TextStyle(color: Colors.grey[500], fontSize: 11)),
                                  const SizedBox(height: 2),
                                  Text(_formatDate(order['created_at']), style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                                  const SizedBox(height: 8),
                                  Text('Metode Pembayaran', style: TextStyle(color: Colors.grey[500], fontSize: 11)),
                                  const SizedBox(height: 2),
                                  Text((order['payment_method'] ?? '-').toUpperCase(), style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                                ],
                              ),
                            ),
                          ],
                        ),

                        const SizedBox(height: 16),
                        const Divider(height: 1, thickness: 1),
                        const SizedBox(height: 16),

                        // --- FOOTER CARD (Total & Tombol Action) ---
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          crossAxisAlignment: CrossAxisAlignment.end,
                          children: [
                            Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text('Total Belanja', style: TextStyle(color: Colors.grey[500], fontSize: 12)),
                                const SizedBox(height: 4),
                                Text(
                                  'Rp ${order['total'] ?? 0}',
                                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: Colors.orange),
                                ),
                              ],
                            ),
                            Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                if (['selesai', 'completed', 'lunas', 'dikirim'].contains((order['status'] ?? '').toString().toLowerCase())) ...[
                                  OutlinedButton.icon(
                                    onPressed: () async {
                                      final refreshed = await Navigator.push(
                                        context,
                                        MaterialPageRoute(
                                          builder: (context) => ReviewOrderScreen(order: order),
                                        ),
                                      );
                                      if (refreshed == true) {
                                        _fetchOrderHistory();
                                      }
                                    },
                                    icon: const Icon(Icons.star_rounded, size: 15, color: Color(0xFFFFA000)),
                                    label: const Text('Nilai', style: TextStyle(color: Color(0xFFFFA000), fontWeight: FontWeight.bold, fontSize: 11.5)),
                                    style: OutlinedButton.styleFrom(
                                      side: const BorderSide(color: Color(0xFFFFCA28)),
                                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                                      minimumSize: const Size(0, 36),
                                    ),
                                  ),
                                  const SizedBox(width: 8),
                                ],
                                ElevatedButton(
                                  onPressed: () {
                                    Navigator.push(context, MaterialPageRoute(
                                        builder: (context) => OrderDetailScreen(order: order)
                                    )).then((_) => _fetchOrderHistory());
                                  },
                                  style: ElevatedButton.styleFrom(
                                    backgroundColor: wowinGreen,
                                    elevation: 0,
                                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                                    minimumSize: const Size(0, 36),
                                  ),
                                  child: const Text('Detail', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12)),
                                ),
                              ],
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            );
          },
        ),
      ),
    );
  }
}