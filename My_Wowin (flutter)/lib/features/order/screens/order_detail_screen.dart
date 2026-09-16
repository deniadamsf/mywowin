import 'dart:async';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:image_picker/image_picker.dart';
import 'package:intl/intl.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../../core/theme/wowin_theme.dart';
import '../../../core/services/cache_service.dart';
import '../../cart/models/payment_method_model.dart';
import '../services/order_api_service.dart';
import 'review_order_screen.dart';

class OrderDetailScreen extends StatefulWidget {
  final dynamic order;
  const OrderDetailScreen({super.key, required this.order});

  @override
  State<OrderDetailScreen> createState() => _OrderDetailScreenState();
}

class _OrderDetailScreenState extends State<OrderDetailScreen> {
  static const Color wowinGreen = WowinColors.primary;

  late Map<String, dynamic> _order;
  Timer? _countdownTimer;
  Duration _remainingTime = Duration.zero;
  bool _isExpired = false;

  File? _selectedImage;
  bool _isUploading = false;
  final ImagePicker _picker = ImagePicker();

  @override
  void initState() {
    super.initState();
    _order = Map<String, dynamic>.from(widget.order as Map);
    _initCountdown();
  }

  @override
  void dispose() {
    _countdownTimer?.cancel();
    super.dispose();
  }

  void _initCountdown() {
    _calculateRemainingTime();
    _countdownTimer?.cancel();
    _countdownTimer = Timer.periodic(const Duration(seconds: 1), (timer) {
      if (mounted) {
        _calculateRemainingTime();
      }
    });
  }

  void _calculateRemainingTime() {
    final String paymentMethod = (_order['payment_method'] ?? 'transfer').toString().toLowerCase();
    final String paymentStatus = (_order['payment_status'] ?? 'pending').toString().toLowerCase();
    final String status = (_order['status'] ?? 'pending').toString().toLowerCase();

    // Hanya aktif jika transfer dan belum lunas/batal
    if (paymentMethod != 'transfer' || paymentStatus == 'paid' || status == 'paid' || status == 'completed') {
      return;
    }

    DateTime? deadline;
    final deadlineRaw = _order['payment_deadline'];
    if (deadlineRaw != null && deadlineRaw.toString().isNotEmpty) {
      deadline = DateTime.tryParse(deadlineRaw.toString())?.toLocal();
    }

    // Jika deadline tidak tersedia, hitung dari created_at + 24 jam
    if (deadline == null) {
      final createdAtRaw = _order['created_at'];
      if (createdAtRaw != null) {
        final createdAt = DateTime.tryParse(createdAtRaw.toString())?.toLocal();
        if (createdAt != null) {
          deadline = createdAt.add(const Duration(hours: 24));
        }
      }
    }

    if (deadline != null) {
      final now = DateTime.now();
      if (now.isAfter(deadline)) {
        setState(() {
          _remainingTime = Duration.zero;
          _isExpired = true;
          if (_order['status'] == 'pending' && (_order['bukti_transfer'] == null || _order['bukti_transfer'].toString().isEmpty)) {
            _order['status'] = 'canceled';
            _order['payment_status'] = 'expired';
          }
        });
      } else {
        setState(() {
          _remainingTime = deadline!.difference(now);
          _isExpired = false;
        });
      }
    }
  }

  String _formatDuration(Duration d) {
    if (d <= Duration.zero) return '00:00:00';
    final hours = d.inHours.toString().padLeft(2, '0');
    final minutes = (d.inMinutes % 60).toString().padLeft(2, '0');
    final seconds = (d.inSeconds % 60).toString().padLeft(2, '0');
    return '$hours : $minutes : $seconds';
  }

  Future<void> _pickImage(ImageSource source) async {
    try {
      final XFile? file = await _picker.pickImage(
        source: source,
        imageQuality: 85,
        maxWidth: 1600,
      );
      if (file != null) {
        setState(() {
          _selectedImage = File(file.path);
        });
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal memilih gambar: $e')),
        );
      }
    }
  }

  void _showImagePickerOptions() {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (ctx) => SafeArea(
        child: Padding(
          padding: const EdgeInsets.symmetric(vertical: 20, horizontal: 16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text(
                'Unggah Bukti Transfer',
                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
              ),
              const SizedBox(height: 16),
              ListTile(
                leading: const CircleAvatar(
                  backgroundColor: Color(0xFFE8F5E9),
                  child: Icon(Icons.camera_alt_rounded, color: wowinGreen),
                ),
                title: const Text('Ambil Foto dari Kamera'),
                onTap: () {
                  Navigator.pop(ctx);
                  _pickImage(ImageSource.camera);
                },
              ),
              ListTile(
                leading: const CircleAvatar(
                  backgroundColor: Color(0xFFE3F2FD),
                  child: Icon(Icons.photo_library_rounded, color: Colors.blue),
                ),
                title: const Text('Pilih dari Galeri'),
                onTap: () {
                  Navigator.pop(ctx);
                  _pickImage(ImageSource.gallery);
                },
              ),
            ],
          ),
        ),
      ),
    );
  }

  Future<void> _uploadProof() async {
    if (_selectedImage == null) return;

    final orderId = int.tryParse(_order['id']?.toString() ?? '0') ?? 0;
    if (orderId == 0) return;

    setState(() => _isUploading = true);

    final res = await OrderApiService.uploadPaymentProof(
      orderId: orderId,
      imageFile: _selectedImage!,
    );

    if (!mounted) return;
    setState(() => _isUploading = false);

    if (res['success'] == true) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(res['message'] ?? 'Bukti transfer berhasil dikirim!'),
          backgroundColor: wowinGreen,
        ),
      );

      setState(() {
        _order['payment_status'] = 'waiting_confirmation';
        if (res['data'] != null && res['data']['bukti_transfer'] != null) {
          _order['bukti_transfer'] = res['data']['bukti_transfer'];
          _order['bukti_transfer_url'] = res['data']['bukti_transfer_url'];
        }
        _order['rejection_reason'] = null;
        _selectedImage = null;
      });
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(res['message'] ?? 'Gagal mengunggah bukti transfer.'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  Color _getStatusColor(String status) {
    switch (status.toLowerCase()) {
      case 'menunggu pembayaran':
      case 'pending':
      case 'belum bayar':
        return Colors.orange;
      case 'lunas':
      case 'dikirim':
      case 'selesai':
      case 'paid':
        return wowinGreen;
      case 'dibatalkan':
      case 'batal':
      case 'failed':
      case 'canceled':
      case 'expired':
        return Colors.red;
      default:
        return Colors.green;
    }
  }

  Future<void> _contactAdmin(String invoice, [String? paymentMethod, String? total]) async {
    String waNumber = '62812106600';
    try {
      final cached = await CacheService.getPaymentMethods();
      if (cached != null) {
        final methods = cached.map((e) => PaymentMethodModel.fromJson(Map<String, dynamic>.from(e as Map))).toList();
        final wa = methods.where((m) => m.code == 'wa').firstOrNull;
        if (wa != null && wa.phoneNumber != null && wa.phoneNumber!.trim().isNotEmpty) {
          waNumber = wa.phoneNumber!.trim();
        }
      }
    } catch (_) {}

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

  void _showLiveTrackingSheet(BuildContext context, int orderId, String? noResi) {
    if (noResi == null || noResi.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Nomor resi belum tersedia')),
      );
      return;
    }

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) => _LiveTrackingBottomSheet(
        orderId: orderId,
        noResi: noResi,
        desCode: _order['jnt_des_code']?.toString(),
        invoiceNumber: _order['invoice_number']?.toString() ?? '',
      ),
    );
  }

  void _showFullImage(String imageUrl) {
    showDialog(
      context: context,
      builder: (ctx) => Dialog(
        backgroundColor: Colors.transparent,
        insetPadding: const EdgeInsets.all(12),
        child: Stack(
          alignment: Alignment.topRight,
          children: [
            InteractiveViewer(
              child: ClipRRect(
                borderRadius: BorderRadius.circular(16),
                child: Image.network(
                  imageUrl,
                  fit: BoxFit.contain,
                  errorBuilder: (context, error, stackTrace) => Container(
                    padding: const EdgeInsets.all(32),
                    color: Colors.white,
                    child: const Text('Gagal memuat gambar bukti transfer.'),
                  ),
                ),
              ),
            ),
            IconButton(
              onPressed: () => Navigator.pop(ctx),
              icon: const CircleAvatar(
                backgroundColor: Colors.black54,
                child: Icon(Icons.close, color: Colors.white, size: 20),
              ),
            ),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final String rawStatus = (_order['status'] ?? 'pending').toString();
    final String paymentMethod = (_order['payment_method'] ?? 'transfer').toString().toLowerCase();
    final String paymentStatus = (_order['payment_status'] ?? 'pending').toString().toLowerCase();
    final bool isCanceled = rawStatus.toLowerCase() == 'canceled' || paymentStatus == 'expired' || _isExpired;
    final bool isPaid = paymentStatus == 'paid' || rawStatus.toLowerCase() == 'paid' || rawStatus.toLowerCase() == 'completed';
    final bool hasProof = _order['bukti_transfer'] != null && _order['bukti_transfer'].toString().isNotEmpty;
    final bool isWaitingConfirmation = paymentStatus == 'waiting_confirmation' || (hasProof && !isPaid && !isCanceled);
    final bool isRejected = paymentStatus == 'rejected';

    final Color statusColor = isCanceled
        ? Colors.red
        : (isPaid
            ? wowinGreen
            : (isWaitingConfirmation ? Colors.blue.shade700 : _getStatusColor(rawStatus)));

    String displayStatusText = rawStatus.toUpperCase();
    if (isCanceled) {
      displayStatusText = 'DIBATALKAN';
    } else if (isPaid) {
      displayStatusText = 'LUNAS / SELESAI';
    } else if (isWaitingConfirmation) {
      displayStatusText = 'MENUNGGU VERIFIKASI';
    } else if (isRejected) {
      displayStatusText = 'BUKTI DITOLAK';
    }

    final List<dynamic> orderItems = _order['items'] ?? _order['order_items'] ?? _order['details'] ?? [];
    final num potonganPoin = num.tryParse(_order['potongan_poin']?.toString() ?? '0') ?? 0;
    final int pointsUsed = int.tryParse(_order['points_used']?.toString() ?? '0') ?? 0;

    String? proofImageUrl = _order['bukti_transfer_url'];
    if (proofImageUrl == null && hasProof) {
      final p = _order['bukti_transfer'].toString();
      proofImageUrl = p.startsWith('http') ? p : 'https://mywowin.com/storage/$p';
    }

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
                      Text(_order['invoice_number'] ?? '#INV-UNKNOWN', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14.5, color: WowinColors.textPrimary)),
                    ],
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                    decoration: BoxDecoration(color: statusColor.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(6)),
                    child: Text(
                      displayStatusText,
                      style: TextStyle(color: statusColor, fontSize: 10.5, fontWeight: FontWeight.bold),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 8),

            // --- BANNER HITUNG MUNDUR 24 JAM (KHUSUS TRANSFER BELUM LUNAS) ---
            if (paymentMethod == 'transfer' && !isPaid && !isCanceled) ...[
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                padding: const EdgeInsets.all(14),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    colors: [Color(0xFFFFF8E1), Color(0xFFFFECB3)],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                  borderRadius: BorderRadius.circular(14),
                  border: Border.all(color: const Color(0xFFFFCA28)),
                  boxShadow: [
                    BoxShadow(color: Colors.amber.withValues(alpha: 0.1), blurRadius: 6, offset: const Offset(0, 2)),
                  ],
                ),
                child: Row(
                  children: [
                    Container(
                      padding: const EdgeInsets.all(10),
                      decoration: const BoxDecoration(
                        color: Color(0xFFFF8F00),
                        shape: BoxShape.circle,
                      ),
                      child: const Icon(Icons.timer_outlined, color: Colors.white, size: 24),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text(
                            'Batas Waktu Pembayaran (24 Jam)',
                            style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12.5, color: Color(0xFFE65100)),
                          ),
                          const SizedBox(height: 2),
                          const Text(
                            'Selesaikan transfer sebelum batas waktu agar pesanan tidak dibatalkan otomatis.',
                            style: TextStyle(fontSize: 10.5, color: Color(0xFF6D4C41)),
                          ),
                          const SizedBox(height: 6),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(8),
                              border: Border.all(color: const Color(0xFFFFB300)),
                            ),
                            child: Text(
                              _formatDuration(_remainingTime),
                              style: const TextStyle(
                                fontWeight: FontWeight.w900,
                                fontSize: 14,
                                letterSpacing: 1.2,
                                color: Color(0xFFD84315),
                                fontFamily: 'monospace',
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ],

            // --- BANNER PESANAN DIBATALKAN KADALUARSA ---
            if (isCanceled) ...[
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                padding: const EdgeInsets.all(14),
                decoration: BoxDecoration(
                  color: const Color(0xFFFFEBEE),
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: const Color(0xFFEF9A9A)),
                ),
                child: const Row(
                  children: [
                    Icon(Icons.cancel_rounded, color: Color(0xFFC62828), size: 28),
                    SizedBox(width: 10),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Pesanan Telah Dibatalkan',
                            style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFFB71C1C)),
                          ),
                          SizedBox(height: 2),
                          Text(
                            'Batas waktu transfer 24 jam telah berakhir. Poin loyalitas Anda telah dikembalikan.',
                            style: TextStyle(fontSize: 11, color: Color(0xFF7F0000)),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ],

            // --- BANNER STATUS BUKTI TRANSFER ---
            if (paymentMethod == 'transfer') ...[
              if (isWaitingConfirmation) ...[
                Container(
                  margin: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                  padding: const EdgeInsets.all(14),
                  decoration: BoxDecoration(
                    color: const Color(0xFFE3F2FD),
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(color: const Color(0xFF90CAF9)),
                  ),
                  child: Row(
                    children: [
                      const Icon(Icons.hourglass_top_rounded, color: Color(0xFF1565C0), size: 26),
                      const SizedBox(width: 10),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: const [
                            Text(
                              'Bukti Transfer Terkirim',
                              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFF0D47A1)),
                            ),
                            SizedBox(height: 2),
                            Text(
                              'Mohon tunggu, Admin/Superadmin sedang memeriksa dan memverifikasi mutasi pembayaran Anda.',
                              style: TextStyle(fontSize: 11, color: Color(0xFF1565C0)),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ] else if (isRejected) ...[
                Container(
                  margin: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                  padding: const EdgeInsets.all(14),
                  decoration: BoxDecoration(
                    color: const Color(0xFFFFF3E0),
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(color: const Color(0xFFFFB74D)),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Row(
                        children: [
                          Icon(Icons.error_outline_rounded, color: Color(0xFFE65100), size: 22),
                          SizedBox(width: 8),
                          Text(
                            'Bukti Transfer Ditolak Admin',
                            style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFFE65100)),
                          ),
                        ],
                      ),
                      const SizedBox(height: 4),
                      Text(
                        'Alasan: ${_order['rejection_reason'] ?? 'Nominal tidak sesuai atau bukti tidak valid.'}',
                        style: const TextStyle(fontSize: 11.5, color: Color(0xFFBF360C)),
                      ),
                      const SizedBox(height: 4),
                      const Text(
                        'Silakan unggah ulang foto bukti transfer yang benar di bawah ini.',
                        style: TextStyle(fontSize: 11, fontStyle: FontStyle.italic, color: Colors.black54),
                      ),
                    ],
                  ),
                ),
              ] else if (isPaid) ...[
                Container(
                  margin: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                  padding: const EdgeInsets.all(14),
                  decoration: BoxDecoration(
                    color: const Color(0xFFE8F5E9),
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(color: const Color(0xFFA5D6A7)),
                  ),
                  child: const Row(
                    children: [
                      Icon(Icons.verified_rounded, color: Color(0xFF2E7D32), size: 26),
                      SizedBox(width: 10),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              'Pembayaran Terverifikasi (Lunas)',
                              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFF1B5E20)),
                            ),
                            SizedBox(height: 2),
                            Text(
                              'Pembayaran Anda telah diverifikasi oleh Super Admin. Pesanan siap diproses.',
                              style: TextStyle(fontSize: 11, color: Color(0xFF2E7D32)),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ],

            // --- KARTU REKENING TUJUAN TRANSFER ---
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
                  if (paymentMethod == 'transfer') ...[
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
                          FutureBuilder<List<dynamic>?>(
                            future: CacheService.getPaymentMethods(),
                            builder: (context, snapshot) {
                              List<BankAccountModel> banks = [];
                              if (snapshot.hasData && snapshot.data != null) {
                                final methods = snapshot.data!
                                    .map((e) => PaymentMethodModel.fromJson(Map<String, dynamic>.from(e as Map)))
                                    .toList();
                                final tf = methods.where((m) => m.code == 'transfer').firstOrNull;
                                if (tf != null) {
                                  banks = tf.bankAccounts.where((b) => b.isActive).toList();
                                }
                              }
                              if (banks.isEmpty) {
                                banks = [
                                  BankAccountModel(id: '1', bankName: 'Bank BCA', accountNumber: '0891234567', accountHolder: 'PT WOWIN PURNOMO PUTERA'),
                                  BankAccountModel(id: '2', bankName: 'Bank BRI', accountNumber: '0123-01-000456-53-0', accountHolder: 'PT SANKE BERSINAR TERANG'),
                                ];
                              }

                              return Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  for (int i = 0; i < banks.length; i++) ...[
                                    _buildBankRow(context, banks[i].bankName, banks[i].accountNumber, banks[i].accountHolder),
                                    if (i < banks.length - 1) const Divider(height: 14, color: Color(0xFFBBDEFB)),
                                  ],
                                ],
                              );
                            },
                          ),
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

            // --- SECTION UNGGAH BUKTI TRANSFER (IN-APP UPLOAD) ---
            if (paymentMethod == 'transfer') ...[
              Container(
                color: Colors.white,
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Row(
                      children: [
                        Icon(Icons.upload_file_rounded, color: wowinGreen, size: 20),
                        SizedBox(width: 8),
                        Text(
                          'Bukti Transfer Pembayaran',
                          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: WowinColors.textPrimary),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),

                    // Jika bukti sudah pernah diunggah di server
                    if (hasProof && proofImageUrl != null) ...[
                      Container(
                        padding: const EdgeInsets.all(12),
                        decoration: BoxDecoration(
                          color: Colors.grey.shade50,
                          borderRadius: BorderRadius.circular(12),
                          border: Border.all(color: Colors.grey.shade200),
                        ),
                        child: Row(
                          children: [
                            GestureDetector(
                              onTap: () => _showFullImage(proofImageUrl!),
                              child: Stack(
                                alignment: Alignment.bottomRight,
                                children: [
                                  ClipRRect(
                                    borderRadius: BorderRadius.circular(8),
                                    child: Image.network(
                                      proofImageUrl,
                                      width: 68,
                                      height: 68,
                                      fit: BoxFit.cover,
                                      errorBuilder: (context, error, stackTrace) => Container(
                                        width: 68,
                                        height: 68,
                                        color: Colors.grey.shade200,
                                        child: const Icon(Icons.image_not_supported, color: Colors.grey),
                                      ),
                                    ),
                                  ),
                                  Container(
                                    padding: const EdgeInsets.all(3),
                                    decoration: const BoxDecoration(
                                      color: Colors.black54,
                                      shape: BoxShape.circle,
                                    ),
                                    child: const Icon(Icons.search, size: 12, color: Colors.white),
                                  ),
                                ],
                              ),
                            ),
                            const SizedBox(width: 12),
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    isPaid ? 'Bukti Pembayaran Terverifikasi' : 'Bukti Transfer Terkirim',
                                    style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12.5),
                                  ),
                                  const SizedBox(height: 4),
                                  Text(
                                    isPaid
                                        ? 'Pembayaran telah disetujui Super Admin.'
                                        : 'Ketuk gambar untuk melihat pratinjau penuh.',
                                    style: TextStyle(fontSize: 11, color: Colors.grey.shade600),
                                  ),
                                ],
                              ),
                            ),
                            if (!isPaid && !isCanceled) ...[
                              TextButton.icon(
                                onPressed: _showImagePickerOptions,
                                icon: const Icon(Icons.edit, size: 14),
                                label: const Text('Ganti', style: TextStyle(fontSize: 12)),
                              ),
                            ],
                          ],
                        ),
                      ),
                      const SizedBox(height: 10),
                    ],

                    // Preview gambar yang baru dipilih pengguna (sebelum kirim)
                    if (_selectedImage != null) ...[
                      Container(
                        padding: const EdgeInsets.all(12),
                        decoration: BoxDecoration(
                          color: const Color(0xFFF1F8E9),
                          borderRadius: BorderRadius.circular(12),
                          border: Border.all(color: const Color(0xFFC8E6C9)),
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              children: [
                                ClipRRect(
                                  borderRadius: BorderRadius.circular(8),
                                  child: Image.file(
                                    _selectedImage!,
                                    width: 64,
                                    height: 64,
                                    fit: BoxFit.cover,
                                  ),
                                ),
                                const SizedBox(width: 12),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      const Text('Foto Baru Dipilih', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFF2E7D32))),
                                      const SizedBox(height: 2),
                                      Text(
                                        'File siap diunggah ke server.',
                                        style: TextStyle(fontSize: 11, color: Colors.grey.shade700),
                                      ),
                                    ],
                                  ),
                                ),
                                IconButton(
                                  onPressed: () => setState(() => _selectedImage = null),
                                  icon: const Icon(Icons.close, color: Colors.red),
                                  tooltip: 'Hapus',
                                ),
                              ],
                            ),
                            const SizedBox(height: 10),
                            SizedBox(
                              width: double.infinity,
                              height: 44,
                              child: ElevatedButton.icon(
                                onPressed: _isUploading ? null : _uploadProof,
                                icon: _isUploading
                                    ? const SizedBox(
                                        width: 16,
                                        height: 16,
                                        child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white),
                                      )
                                    : const Icon(Icons.cloud_upload_rounded, size: 18),
                                label: Text(
                                  _isUploading ? 'Sedang Mengunggah...' : 'Kirim Bukti Transfer Sekarang',
                                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12.5),
                                ),
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: wowinGreen,
                                  foregroundColor: Colors.white,
                                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ] else if (!hasProof && !isPaid && !isCanceled) ...[
                      // Box tombol upload jika belum ada bukti
                      InkWell(
                        onTap: _showImagePickerOptions,
                        borderRadius: BorderRadius.circular(12),
                        child: Container(
                          width: double.infinity,
                          padding: const EdgeInsets.symmetric(vertical: 24, horizontal: 16),
                          decoration: BoxDecoration(
                            color: const Color(0xFFF9FBE7),
                            borderRadius: BorderRadius.circular(12),
                            border: Border.all(color: const Color(0xFFDCE775), width: 1.5),
                          ),
                          child: Column(
                            children: [
                              Container(
                                padding: const EdgeInsets.all(12),
                                decoration: const BoxDecoration(
                                  color: Color(0xFFC0CA33),
                                  shape: BoxShape.circle,
                                ),
                                child: const Icon(Icons.add_a_photo_rounded, color: Colors.white, size: 28),
                              ),
                              const SizedBox(height: 10),
                              const Text(
                                'Klik untuk Unggah Bukti Transfer',
                                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFF33691E)),
                              ),
                              const SizedBox(height: 4),
                              Text(
                                'Dapat memilih dari Galeri atau Foto langsung via Kamera (Maks 5MB)',
                                style: TextStyle(fontSize: 11, color: Colors.grey.shade600),
                                textAlign: TextAlign.center,
                              ),
                            ],
                          ),
                        ),
                      ),
                    ],
                  ],
                ),
              ),
              const SizedBox(height: 8),
            ],

            // --- KARTU PENGIRIMAN LOGISTIK (J&T EXPRESS) ---
            Container(
              color: Colors.white,
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Row(
                        children: [
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                            decoration: BoxDecoration(
                              color: const Color(0xFFD32F2F),
                              borderRadius: BorderRadius.circular(6),
                            ),
                            child: const Text(
                              'J&T EXPRESS',
                              style: TextStyle(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 10, letterSpacing: 0.5),
                            ),
                          ),
                          const SizedBox(width: 8),
                          const Text(
                            'Layanan Reguler (EZ)',
                            style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: WowinColors.textPrimary),
                          ),
                        ],
                      ),
                      if (_order['total_weight_kg'] != null)
                        Text(
                          '${_order['total_weight_kg']} Kg',
                          style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12, color: Colors.grey),
                        ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  if (_order['no_resi'] != null && _order['no_resi'].toString().isNotEmpty) ...[
                    Container(
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: const Color(0xFFFFF5F5),
                        borderRadius: BorderRadius.circular(10),
                        border: Border.all(color: const Color(0xFFFFCDD2)),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('NOMOR RESI RESMI', style: TextStyle(fontSize: 10, color: Colors.grey, fontWeight: FontWeight.bold)),
                          const SizedBox(height: 4),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                _order['no_resi'].toString(),
                                style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15, letterSpacing: 1.2, color: Color(0xFFC62828)),
                              ),
                              InkWell(
                                onTap: () {
                                  Clipboard.setData(ClipboardData(text: _order['no_resi'].toString()));
                                  ScaffoldMessenger.of(context).showSnackBar(
                                    const SnackBar(content: Text('Nomor resi berhasil disalin!'), duration: Duration(seconds: 2)),
                                  );
                                },
                                child: Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                                  decoration: BoxDecoration(
                                    color: Colors.white,
                                    borderRadius: BorderRadius.circular(6),
                                    border: Border.all(color: const Color(0xFFEF9A9A)),
                                  ),
                                  child: const Row(
                                    mainAxisSize: MainAxisSize.min,
                                    children: [
                                      Icon(Icons.copy_rounded, size: 13, color: Color(0xFFC62828)),
                                      SizedBox(width: 4),
                                      Text('Salin', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Color(0xFFC62828))),
                                    ],
                                  ),
                                ),
                              ),
                            ],
                          ),
                          if (_order['jnt_des_code'] != null && _order['jnt_des_code'].toString().isNotEmpty) ...[
                            const SizedBox(height: 6),
                            Text('Kode Area: ${_order['jnt_des_code']}', style: const TextStyle(fontSize: 11, color: Colors.black54, fontFamily: 'monospace')),
                          ],
                          const SizedBox(height: 10),
                          SizedBox(
                            width: double.infinity,
                            child: ElevatedButton.icon(
                              onPressed: () => _showLiveTrackingSheet(
                                context,
                                int.tryParse(_order['id'].toString()) ?? 0,
                                _order['no_resi']?.toString(),
                              ),
                              icon: const Icon(Icons.radar_rounded, size: 16),
                              label: const Text('Lacak Perjalanan Paket', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
                              style: ElevatedButton.styleFrom(
                                backgroundColor: const Color(0xFFD32F2F),
                                foregroundColor: Colors.white,
                                elevation: 0,
                                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                                padding: const EdgeInsets.symmetric(vertical: 8),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ] else ...[
                    Container(
                      padding: const EdgeInsets.all(10),
                      decoration: BoxDecoration(
                        color: Colors.grey.shade50,
                        borderRadius: BorderRadius.circular(8),
                        border: Border.all(color: Colors.grey.shade200),
                      ),
                      child: Row(
                        children: [
                          const Icon(Icons.info_outline_rounded, size: 16, color: Colors.grey),
                          const SizedBox(width: 8),
                          Expanded(
                            child: Text(
                              !isPaid
                                  ? 'Nomor resi otomatis diterbitkan setelah pembayaran lunas diverifikasi.'
                                  : 'Pesanan sedang dipersiapkan di gudang untuk serah terima ke kurir J&T Express.',
                              style: TextStyle(fontSize: 11.5, color: Colors.grey.shade700),
                            ),
                          ),
                        ],
                      ),
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

                  return Padding(
                    padding: const EdgeInsets.all(16),
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.circular(8),
                          child: imageUrl.isNotEmpty
                              ? Image.network(
                                  imageUrl,
                                  width: 54,
                                  height: 54,
                                  fit: BoxFit.cover,
                                  errorBuilder: (context, error, stackTrace) => Container(width: 54, height: 54, color: Colors.grey.shade200, child: const Icon(Icons.image_not_supported, color: Colors.grey)),
                                )
                              : Container(width: 54, height: 54, color: Colors.grey.shade200, child: const Icon(Icons.shopping_bag, color: Colors.grey)),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(displayName, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: WowinColors.textPrimary)),
                              const SizedBox(height: 4),
                              Text('$qty x Rp ${NumberFormat('#,###', 'id_ID').format(price)}', style: TextStyle(fontSize: 12, color: Colors.grey.shade600)),
                            ],
                          ),
                        ),
                        Text(
                          'Rp ${NumberFormat('#,###', 'id_ID').format(price * (num.tryParse(qty) ?? 1))}',
                          style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: WowinColors.textPrimary),
                        ),
                      ],
                    ),
                  );
                },
              ),
            ),
            const SizedBox(height: 8),

            // --- RINCIAN BIAYA & POTONGAN ---
            Container(
              color: Colors.white,
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Rincian Pembayaran', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                  const SizedBox(height: 12),
                  if (pointsUsed > 0) ...[
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text('Potongan Poin ($pointsUsed Poin)', style: const TextStyle(fontSize: 12, color: Colors.amber)),
                        Text('- Rp ${NumberFormat('#,###', 'id_ID').format(potonganPoin)}', style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: Colors.amber)),
                      ],
                    ),
                    const SizedBox(height: 8),
                  ],
                  if (_order['shipping_cost'] != null) ...[
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Text('Ongkos Kirim J&T Express', style: TextStyle(fontSize: 12, color: Colors.grey)),
                        Text('Rp ${NumberFormat('#,###', 'id_ID').format(num.tryParse(_order['shipping_cost']?.toString() ?? '0'))}', style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold)),
                      ],
                    ),
                    const SizedBox(height: 8),
                  ],
                  const Divider(height: 16),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('Total Pembayaran', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                      Text(
                        'Rp ${NumberFormat('#,###', 'id_ID').format(num.tryParse(_order['total']?.toString() ?? '0'))}',
                        style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: wowinGreen),
                      ),
                    ],
                  ),
                ],
              ),
            ),

            // --- REVIEW / PENILAIAN ---
            if (isPaid) ...[
              const SizedBox(height: 8),
              Container(
                margin: const EdgeInsets.all(16),
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: const Color(0xFFFFFDE7),
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: const Color(0xFFFFE082)),
                ),
                child: Row(
                  children: [
                    const CircleAvatar(
                      backgroundColor: Color(0xFFFFECB3),
                      child: Icon(Icons.star_rounded, color: Colors.amber, size: 28),
                    ),
                    const SizedBox(width: 12),
                    const Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Beri Penilaian Pesanan', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFF5D4037))),
                          SizedBox(height: 2),
                          Text('Bagikan ulasan Anda dan bantu kami meningkatkan pelayanan.', style: TextStyle(fontSize: 10.5, color: Color(0xFF8D6E63))),
                        ],
                      ),
                    ),
                    ElevatedButton(
                      onPressed: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => ReviewOrderScreen(order: _order),
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

      // --- FOOTER: TOTAL BAYAR & TOMBOL WA ---
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
                  const SizedBox(height: 6),
                  const Text('TOTAL WAJIB BAYAR', style: TextStyle(fontSize: 10, color: Colors.grey)),
                  Text(
                    'Rp ${NumberFormat('#,###', 'id_ID').format(num.tryParse(_order['total']?.toString() ?? '0'))}',
                    style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.black),
                  ),
                ],
              ),
              ElevatedButton.icon(
                onPressed: () => _contactAdmin(
                  _order['invoice_number'] ?? '',
                  paymentMethod,
                  'Rp ${NumberFormat('#,###', 'id_ID').format(num.tryParse(_order['total']?.toString() ?? '0'))}',
                ),
                icon: const Icon(Icons.chat, color: Colors.white, size: 18),
                label: const Text('Bantuan CS', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12.5)),
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF1B5E20),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                  padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
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

class _LiveTrackingBottomSheet extends StatefulWidget {
  final int orderId;
  final String noResi;
  final String? desCode;
  final String invoiceNumber;

  const _LiveTrackingBottomSheet({
    required this.orderId,
    required this.noResi,
    this.desCode,
    required this.invoiceNumber,
  });

  @override
  State<_LiveTrackingBottomSheet> createState() => _LiveTrackingBottomSheetState();
}

class _LiveTrackingBottomSheetState extends State<_LiveTrackingBottomSheet> {
  bool _isLoading = true;
  String? _errorMessage;
  Map<String, dynamic>? _trackingData;

  @override
  void initState() {
    super.initState();
    _loadTracking();
  }

  Future<void> _loadTracking() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    final res = await OrderApiService.fetchLiveTracking(widget.orderId);
    if (!mounted) return;

    if (res['success'] == true && res['data'] != null) {
      setState(() {
        _isLoading = false;
        _trackingData = Map<String, dynamic>.from(res['data'] as Map);
      });
    } else {
      setState(() {
        _isLoading = false;
        _errorMessage = res['message']?.toString() ?? 'Gagal memuat status pelacakan.';
      });
    }
  }

  Future<void> _openExternalJnt() async {
    final Uri url = Uri.parse('https://www.jet.co.id/track?awb=${widget.noResi}');
    if (await canLaunchUrl(url)) {
      await launchUrl(url, mode: LaunchMode.externalApplication);
    }
  }

  @override
  Widget build(BuildContext context) {
    final checkpoints = (_trackingData?['checkpoints'] as List<dynamic>?) ?? [];
    final currentStatus = _trackingData?['status']?.toString() ?? 'Dalam Pengiriman';
    final isDelivered = _trackingData?['is_delivered'] == true;
    final isLive = _trackingData?['source'] == 'jnt_live';

    return Container(
      height: MediaQuery.of(context).size.height * 0.85,
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      child: Column(
        children: [
          // Drag handle
          Container(
            width: 40,
            height: 4,
            margin: const EdgeInsets.only(top: 12, bottom: 8),
            decoration: BoxDecoration(
              color: Colors.grey.shade300,
              borderRadius: BorderRadius.circular(2),
            ),
          ),

          // Header Bar
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(
                    color: const Color(0xFFD32F2F),
                    borderRadius: BorderRadius.circular(6),
                  ),
                  child: const Text(
                    'J&T',
                    style: TextStyle(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 11),
                  ),
                ),
                const SizedBox(width: 10),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Lacak Pengiriman J&T',
                        style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                      ),
                      if (widget.invoiceNumber.isNotEmpty)
                        Text(
                          'Invoice: #${widget.invoiceNumber}',
                          style: TextStyle(fontSize: 11, color: Colors.grey.shade600),
                        ),
                    ],
                  ),
                ),
                IconButton(
                  icon: const Icon(Icons.close_rounded),
                  onPressed: () => Navigator.pop(context),
                ),
              ],
            ),
          ),

          // AWB & Status Info Box
          Container(
            margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: const Color(0xFFFFF2F2),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: const Color(0xFFFFCDD2)),
            ),
            child: Row(
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text('No. Resi Pengiriman:', style: TextStyle(fontSize: 10.5, color: Colors.grey.shade700)),
                      const SizedBox(height: 2),
                      Row(
                        children: [
                          Text(
                            widget.noResi,
                            style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13, color: Color(0xFFB71C1C), letterSpacing: 0.5),
                          ),
                          if (widget.desCode != null && widget.desCode!.isNotEmpty) ...[
                            const SizedBox(width: 6),
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 5, vertical: 1),
                              decoration: BoxDecoration(
                                color: Colors.white,
                                borderRadius: BorderRadius.circular(4),
                                border: Border.all(color: Colors.red.shade200),
                              ),
                              child: Text(
                                widget.desCode!,
                                style: const TextStyle(fontSize: 9.5, fontWeight: FontWeight.bold, color: Colors.black87),
                              ),
                            ),
                          ],
                        ],
                      ),
                    ],
                  ),
                ),
                InkWell(
                  onTap: () {
                    Clipboard.setData(ClipboardData(text: widget.noResi));
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(
                        content: Text('Nomor resi berhasil disalin!'),
                        duration: Duration(seconds: 1),
                      ),
                    );
                  },
                  borderRadius: BorderRadius.circular(8),
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(8),
                      border: Border.all(color: Colors.red.shade200),
                    ),
                    child: const Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(Icons.copy_rounded, size: 12, color: Color(0xFFC62828)),
                        SizedBox(width: 4),
                        Text('Salin', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Color(0xFFC62828))),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          ),

          // Status Badge Bar
          if (!_isLoading && _errorMessage == null) ...[
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
              child: Row(
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                    decoration: BoxDecoration(
                      color: isDelivered ? Colors.green.shade50 : (isLive ? Colors.blue.shade50 : Colors.orange.shade50),
                      borderRadius: BorderRadius.circular(6),
                      border: Border.all(
                        color: isDelivered ? Colors.green.shade200 : (isLive ? Colors.blue.shade200 : Colors.orange.shade200),
                      ),
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(
                          isDelivered ? Icons.check_circle_rounded : (isLive ? Icons.local_shipping_rounded : Icons.inventory_2_rounded),
                          size: 13,
                          color: isDelivered ? Colors.green.shade700 : (isLive ? Colors.blue.shade700 : Colors.orange.shade800),
                        ),
                        const SizedBox(width: 5),
                        Text(
                          currentStatus,
                          style: TextStyle(
                            fontSize: 11,
                            fontWeight: FontWeight.bold,
                            color: isDelivered ? Colors.green.shade700 : (isLive ? Colors.blue.shade700 : Colors.orange.shade800),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const Spacer(),
                  IconButton(
                    icon: const Icon(Icons.refresh_rounded, size: 18),
                    onPressed: _loadTracking,
                    tooltip: 'Segarkan data',
                  ),
                ],
              ),
            ),
          ],

          const Divider(height: 1),

          // Main Body: Timeline Stepper / Loader / Error
          Expanded(
            child: _isLoading
                ? Center(
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const CircularProgressIndicator(color: Color(0xFFD32F2F)),
                        const SizedBox(height: 14),
                        Text(
                          'Menghubungi server tracking J&T Express...',
                          style: TextStyle(fontSize: 12, color: Colors.grey.shade600),
                        ),
                      ],
                    ),
                  )
                : _errorMessage != null
                    ? Center(
                        child: Padding(
                          padding: const EdgeInsets.all(24),
                          child: Column(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Icon(Icons.error_outline_rounded, size: 42, color: Colors.amber.shade700),
                              const SizedBox(height: 10),
                              Text(
                                _errorMessage!,
                                textAlign: TextAlign.center,
                                style: const TextStyle(fontSize: 12.5, color: Colors.black87),
                              ),
                              const SizedBox(height: 16),
                              ElevatedButton.icon(
                                onPressed: _loadTracking,
                                icon: const Icon(Icons.refresh, size: 15),
                                label: const Text('Coba Lagi', style: TextStyle(fontSize: 12)),
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: const Color(0xFFD32F2F),
                                  foregroundColor: Colors.white,
                                ),
                              ),
                            ],
                          ),
                        ),
                      )
                    : ListView.builder(
                        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
                        itemCount: checkpoints.length + (!isLive ? 1 : 0),
                        itemBuilder: (context, index) {
                          if (!isLive && index == checkpoints.length) {
                            return Container(
                              margin: const EdgeInsets.only(top: 8),
                              padding: const EdgeInsets.all(10),
                              decoration: BoxDecoration(
                                color: Colors.blue.shade50,
                                borderRadius: BorderRadius.circular(8),
                                border: Border.all(color: Colors.blue.shade100),
                              ),
                              child: Row(
                                children: [
                                  Icon(Icons.info_outline_rounded, size: 16, color: Colors.blue.shade700),
                                  const SizedBox(width: 8),
                                  Expanded(
                                    child: Text(
                                      'Resi J&T telah dibuat. Riwayat perjalanan fisik kurir akan otomatis muncul begitu paket discan di drop point / hub J&T.',
                                      style: TextStyle(fontSize: 11, color: Colors.blue.shade900),
                                    ),
                                  ),
                                ],
                              ),
                            );
                          }

                          final cp = checkpoints[index] as Map<String, dynamic>;
                          final bool isCurrent = cp['is_current'] == true;
                          final bool isDone = cp['is_completed'] == true;
                          final String title = cp['title']?.toString() ?? '';
                          final String time = cp['time']?.toString() ?? '';
                          final String desc = cp['description']?.toString() ?? '';
                          final String location = cp['location']?.toString() ?? '';

                          return IntrinsicHeight(
                            child: Row(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                // Left line + dot
                                SizedBox(
                                  width: 28,
                                  child: Column(
                                    children: [
                                      Container(
                                        width: 14,
                                        height: 14,
                                        decoration: BoxDecoration(
                                          shape: BoxShape.circle,
                                          color: isCurrent
                                              ? const Color(0xFFD32F2F)
                                              : (isDone ? Colors.green.shade600 : Colors.grey.shade300),
                                          border: Border.all(
                                            color: isCurrent
                                                ? Colors.red.shade100
                                                : (isDone ? Colors.green.shade100 : Colors.grey.shade100),
                                            width: 3,
                                          ),
                                        ),
                                      ),
                                      if (index < checkpoints.length - 1 || !isLive)
                                        Expanded(
                                          child: Container(
                                            width: 2,
                                            color: isDone ? Colors.green.shade200 : Colors.grey.shade300,
                                          ),
                                        ),
                                    ],
                                  ),
                                ),
                                const SizedBox(width: 8),
                                // Right details
                                Expanded(
                                  child: Padding(
                                    padding: const EdgeInsets.only(bottom: 20),
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Row(
                                          children: [
                                            Expanded(
                                              child: Text(
                                                title,
                                                style: TextStyle(
                                                  fontWeight: FontWeight.bold,
                                                  fontSize: 12.5,
                                                  color: isCurrent ? const Color(0xFFB71C1C) : Colors.black87,
                                                ),
                                              ),
                                            ),
                                            if (time.isNotEmpty && time != '-')
                                              Text(
                                                time,
                                                style: TextStyle(
                                                  fontSize: 10,
                                                  fontFamily: 'monospace',
                                                  color: Colors.grey.shade500,
                                                ),
                                              ),
                                          ],
                                        ),
                                        if (desc.isNotEmpty) ...[
                                          const SizedBox(height: 2),
                                          Text(
                                            desc,
                                            style: TextStyle(fontSize: 11.5, color: Colors.grey.shade700, height: 1.3),
                                          ),
                                        ],
                                        if (location.isNotEmpty && location != '-') ...[
                                          const SizedBox(height: 3),
                                          Row(
                                            children: [
                                              Icon(Icons.location_on_outlined, size: 12, color: Colors.grey.shade500),
                                              const SizedBox(width: 3),
                                              Expanded(
                                                child: Text(
                                                  location,
                                                  style: TextStyle(fontSize: 10.5, color: Colors.grey.shade600),
                                                ),
                                              ),
                                            ],
                                          ),
                                        ],
                                      ],
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          );
                        },
                      ),
          ),

          // Bottom Action Bar
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: Colors.grey.shade50,
              border: Border(top: BorderSide(color: Colors.grey.shade200)),
            ),
            child: Row(
              children: [
                Expanded(
                  child: OutlinedButton.icon(
                    onPressed: _openExternalJnt,
                    icon: const Icon(Icons.open_in_new_rounded, size: 14),
                    label: const Text('Buka Web Resmi J&T', style: TextStyle(fontSize: 11.5, fontWeight: FontWeight.bold)),
                    style: OutlinedButton.styleFrom(
                      foregroundColor: const Color(0xFFD32F2F),
                      side: const BorderSide(color: Color(0xFFEF9A9A)),
                      padding: const EdgeInsets.symmetric(vertical: 10),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                    ),
                  ),
                ),
                const SizedBox(width: 8),
                TextButton(
                  onPressed: () => Navigator.pop(context),
                  style: TextButton.styleFrom(
                    foregroundColor: Colors.grey.shade700,
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
                  ),
                  child: const Text('Tutup', style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold)),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}