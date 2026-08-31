import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/theme/wowin_theme.dart';
import '../providers/auth_provider.dart';
import 'package:intl/intl.dart';
import 'reward_screen.dart';
import 'edit_profile_screen.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../order/screens/history_screen.dart';

class ProfileScreen extends ConsumerStatefulWidget {
  final bool showBackButton;
  const ProfileScreen({super.key, this.showBackButton = true});

  @override
  ConsumerState<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends ConsumerState<ProfileScreen> {
  static const Color primaryGreen = WowinColors.primaryDark;

  Map<String, dynamic>? _userData;
  bool _isLoading = true;

  final List<Map<String, dynamic>> _membershipTiers = [
    {
      'name': 'Bronze', 'subtitle': 'STARTER', 'icon': Icons.emoji_events,
      'color': Colors.orange[700], 'bg': Colors.orange[50], 'border': Colors.orange[200],
      'min_purchase': 0, 'portal_fee': 0,
      'benefits': [{'qty': 10, 'disc': 1.5}, {'qty': 20, 'disc': 2.0}, {'qty': 30, 'disc': 2.5}, {'qty': 50, 'disc': 4.5}]
    },
    {
      'name': 'Silver', 'subtitle': 'GROWTH', 'icon': Icons.shield,
      'color': Colors.grey[700], 'bg': Colors.grey[100], 'border': Colors.grey[300],
      'min_purchase': 30, 'portal_fee': 50000,
      'benefits': [{'qty': 10, 'disc': 1.85}, {'qty': 20, 'disc': 2.6}, {'qty': 30, 'disc': 3.1}, {'qty': 50, 'disc': 5.1}]
    },
    {
      'name': 'Gold', 'subtitle': 'PREMIUM', 'icon': Icons.emoji_events,
      'color': Colors.yellow[700], 'bg': Colors.yellow[50], 'border': Colors.yellow[400],
      'min_purchase': 50, 'portal_fee': 100000,
      'benefits': [{'qty': 10, 'disc': 1.95}, {'qty': 20, 'disc': 3.2}, {'qty': 30, 'disc': 3.7}, {'qty': 50, 'disc': 5.7}]
    },
    {
      'name': 'Platinum', 'subtitle': 'ELITE', 'icon': Icons.diamond,
      'color': Colors.blue[600], 'bg': Colors.blue[50], 'border': Colors.blue[300],
      'min_purchase': 75, 'portal_fee': 200000,
      'benefits': [{'qty': 10, 'disc': 2.05}, {'qty': 20, 'disc': 3.3}, {'qty': 30, 'disc': 4.3}, {'qty': 50, 'disc': 6.3}]
    },
    {
      'name': 'Diamond', 'subtitle': 'ULTIMATE', 'icon': Icons.auto_awesome,
      'color': Colors.purple[600], 'bg': Colors.purple[50], 'border': Colors.purple[300],
      'min_purchase': 100, 'portal_fee': 500000,
      'benefits': [{'qty': 10, 'disc': 2.55}, {'qty': 20, 'disc': 4.3}, {'qty': 30, 'disc': 5.8}, {'qty': 50, 'disc': 8.3}]
    },
  ];

  @override
  void initState() {
    super.initState();
    _fetchProfile();
  }

  Future<void> _fetchProfile() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');

      // 1. Muat dari cache lokal terlebih dahulu jika ada agar UI langsung tampil instan
      final cachedProfileStr = prefs.getString('cached_user_profile');
      if (cachedProfileStr != null) {
        try {
          final cachedProfile = json.decode(cachedProfileStr);
          if (mounted && cachedProfile is Map<String, dynamic>) {
            setState(() {
              _userData = cachedProfile;
              _isLoading = false;
            });
          }
        } catch (_) {}
      }

      if (token == null) {
        if (mounted) setState(() => _isLoading = false);
        return;
      }

      final response = await http.get(
        Uri.parse('$baseUrl/profile'),
        headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'},
      ).timeout(const Duration(seconds: 8));

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (mounted && data['data'] != null) {
          setState(() {
            _userData = data['data'];
            _isLoading = false;
          });
          await prefs.setString('cached_user_profile', json.encode(data['data']));
        }
      } else {
        if (mounted) setState(() => _isLoading = false);
      }
    } catch (e) {
      if (mounted) setState(() => _isLoading = false);
    } finally {
      if (mounted && _isLoading) {
        setState(() => _isLoading = false);
      }
    }
  }

  // --- FUNGSI CUSTOMER CARE / BANTUAN WOWIN ---
  void _contactCS() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
      builder: (ctx) => Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Center(
              child: Container(
                width: 44,
                height: 5,
                decoration: BoxDecoration(color: Colors.grey[300], borderRadius: BorderRadius.circular(10)),
              ),
            ),
            const SizedBox(height: 18),
            const Text(
              'Layanan Pelanggan & Mitra Wowin',
              style: TextStyle(fontSize: 17, fontWeight: FontWeight.bold, color: WowinColors.textPrimary),
            ),
            const SizedBox(height: 6),
            Text(
              'Tim Customer Care PT Wowin Purnomo Putera siap membantu operasional dan pesanan Anda.',
              style: TextStyle(fontSize: 12.5, color: Colors.grey[600]),
            ),
            const SizedBox(height: 20),
            ListTile(
              contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 4),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14), side: BorderSide(color: Colors.green.shade200)),
              tileColor: Colors.green.shade50,
              leading: const CircleAvatar(
                backgroundColor: Color(0xFF25D366),
                child: Icon(Icons.chat_rounded, color: Colors.white, size: 20),
              ),
              title: const Text('WhatsApp Customer Care', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
              subtitle: const Text('0812-1630-1220 (Respon Cepat)', style: TextStyle(fontSize: 12, color: Colors.black54)),
              trailing: const Icon(Icons.arrow_forward_ios, size: 14, color: Color(0xFF25D366)),
              onTap: () async {
                Navigator.pop(ctx);
                const String waNumber = '6281216301220';
                const String text = 'Halo Tim CS Wowin Food, saya membutuhkan bantuan seputar akun/aplikasi/pesanan saya.';
                final Uri httpsUri = Uri.parse('https://wa.me/$waNumber?text=${Uri.encodeComponent(text)}');
                final Uri appUri = Uri.parse('whatsapp://send?phone=$waNumber&text=${Uri.encodeComponent(text)}');
                try {
                  if (await canLaunchUrl(httpsUri)) {
                    await launchUrl(httpsUri, mode: LaunchMode.externalApplication);
                  } else if (await canLaunchUrl(appUri)) {
                    await launchUrl(appUri, mode: LaunchMode.externalApplication);
                  } else {
                    await launchUrl(httpsUri, mode: LaunchMode.externalApplication);
                  }
                } catch (_) {
                  await launchUrl(httpsUri, mode: LaunchMode.externalApplication);
                }
              },
            ),
            const SizedBox(height: 12),
            ListTile(
              contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 4),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14), side: BorderSide(color: Colors.blue.shade200)),
              tileColor: Colors.blue.shade50,
              leading: const CircleAvatar(
                backgroundColor: Colors.blue,
                child: Icon(Icons.email_outlined, color: Colors.white, size: 20),
              ),
              title: const Text('Email Resmi Bantuan', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
              subtitle: const Text('cs@mywowin.com / admin@mywowin.com', style: TextStyle(fontSize: 12, color: Colors.black54)),
              trailing: const Icon(Icons.arrow_forward_ios, size: 14, color: Colors.blue),
              onTap: () async {
                Navigator.pop(ctx);
                final Uri emailUri = Uri(
                  scheme: 'mailto',
                  path: 'cs@mywowin.com',
                  query: 'subject=Bantuan Aplikasi Wowin Food',
                );
                try {
                  await launchUrl(emailUri);
                } catch (_) {}
              },
            ),
            const SizedBox(height: 12),
          ],
        ),
      ),
    );
  }

  // --- MODAL KEBIJAKAN PRIVASI ---
  void _showPrivacyPolicyModal(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
      builder: (ctx) => DraggableScrollableSheet(
        initialChildSize: 0.85,
        minChildSize: 0.5,
        maxChildSize: 0.95,
        expand: false,
        builder: (_, scrollController) => Padding(
          padding: const EdgeInsets.fromLTRB(20, 16, 20, 24),
          child: Column(
            children: [
              Container(
                width: 44,
                height: 5,
                decoration: BoxDecoration(color: Colors.grey[300], borderRadius: BorderRadius.circular(10)),
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(color: Colors.green.shade50, borderRadius: BorderRadius.circular(10)),
                    child: const Icon(Icons.privacy_tip_outlined, color: primaryGreen, size: 24),
                  ),
                  const SizedBox(width: 12),
                  const Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('Kebijakan Privasi', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: WowinColors.textPrimary)),
                        Text('PT WOWIN PURNOMO PUTERA', style: TextStyle(fontSize: 11.5, color: WowinColors.textSecondary, fontWeight: FontWeight.w600)),
                      ],
                    ),
                  ),
                  IconButton(
                    onPressed: () => Navigator.pop(ctx),
                    icon: const Icon(Icons.close_rounded, color: Colors.grey),
                  ),
                ],
              ),
              const Divider(height: 24),
              Expanded(
                child: ListView(
                  controller: scrollController,
                  children: [
                    _buildLegalSection(
                      '1. Pendahuluan',
                      'Selamat datang di aplikasi My Wowin. Kebijakan Privasi ini menjelaskan bagaimana PT Wowin Purnomo Putera ("Wowin Food", "Kami") mengumpulkan, menggunakan, menyimpan, dan melindungi data pribadi Anda saat menggunakan aplikasi dan layanan kami.',
                    ),
                    _buildLegalSection(
                      '2. Data Pribadi yang Kami Kumpulkan',
                      'Untuk mendukung proses transaksi kemitraan dan pengiriman produk, kami mengumpulkan data yang Anda berikan secara langsung, mencakup:\n'
                      '• Informasi Identitas: Nama lengkap, nama toko/usaha, alamat email, dan nomor telepon/WhatsApp.\n'
                      '• Informasi Pengiriman: Alamat lengkap toko/gudang dan titik pengantaran.\n'
                      '• Informasi Transaksi: Riwayat pesanan, pilihan metode pembayaran, catatan pesanan, serta akumulasi poin loyalty membership.',
                    ),
                    _buildLegalSection(
                      '3. Penggunaan Informasi',
                      'Kami menggunakan data pribadi Anda secara bertanggung jawab untuk tujuan:\n'
                      '• Memproses, memvalidasi, dan mengantarkan pesanan produk makanan & minuman Wowin.\n'
                      '• Menghitung tier membership (Bronze, Silver, Gold, Platinum, Diamond) dan diskon kuantitas.\n'
                      '• Mengirimkan notifikasi status pemesanan, verifikasi pembayaran, serta promo bundling spesial.\n'
                      '• Layanan bantuan dan penanganan keluhan pelanggan melalui Customer Care resmi.',
                    ),
                    _buildLegalSection(
                      '4. Perlindungan & Keamanan Data',
                      'Kami berkomitmen menjaga kerahasiaan data Anda dengan standar keamanan digital. Seluruh transmisi data dienkripsi dengan protokol HTTPS/TLS. Kami tidak akan pernah menjual, menyewakan, atau mendistribusikan data pribadi Anda kepada pihak ketiga untuk kepentingan pemasaran pihak lain.',
                    ),
                    _buildLegalSection(
                      '5. Hak Pengguna',
                      'Anda memiliki hak penuh untuk memeriksa, memperbarui data profil melalui menu Edit Profil di aplikasi, atau mengajukan permohonan penonaktifan akun dengan menghubungi Customer Service resmi kami.',
                    ),
                    _buildLegalSection(
                      '6. Kontak & Layanan Data',
                      'Apabila Anda memiliki pertanyaan mengenai Kebijakan Privasi ini atau pengelolaan data Anda, silakan hubungi kami melalui:\n'
                      '• Email: cs@mywowin.com / admin@mywowin.com\n'
                      '• WhatsApp Resmi: 081216301220\n'
                      '• Kantor: PT Wowin Purnomo Putera',
                    ),
                    const SizedBox(height: 20),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  // --- MODAL DISCLAIMER & KETENTUAN ---
  void _showDisclaimerModal(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
      builder: (ctx) => DraggableScrollableSheet(
        initialChildSize: 0.85,
        minChildSize: 0.5,
        maxChildSize: 0.95,
        expand: false,
        builder: (_, scrollController) => Padding(
          padding: const EdgeInsets.fromLTRB(20, 16, 20, 24),
          child: Column(
            children: [
              Container(
                width: 44,
                height: 5,
                decoration: BoxDecoration(color: Colors.grey[300], borderRadius: BorderRadius.circular(10)),
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(color: Colors.orange.shade50, borderRadius: BorderRadius.circular(10)),
                    child: const Icon(Icons.gavel_outlined, color: Colors.orange, size: 24),
                  ),
                  const SizedBox(width: 12),
                  const Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('Disclaimer & Syarat Ketentuan', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: WowinColors.textPrimary)),
                        Text('Ketentuan Penggunaan Aplikasi My Wowin', style: TextStyle(fontSize: 11.5, color: WowinColors.textSecondary, fontWeight: FontWeight.w600)),
                      ],
                    ),
                  ),
                  IconButton(
                    onPressed: () => Navigator.pop(ctx),
                    icon: const Icon(Icons.close_rounded, color: Colors.grey),
                  ),
                ],
              ),
              const Divider(height: 24),
              Expanded(
                child: ListView(
                  controller: scrollController,
                  children: [
                    _buildLegalSection(
                      '1. Kepemilikan Resmi & Hak Cipta',
                      'Aplikasi My Wowin, termasuk nama merek dagang, logo grafis, katalog produk, visualisasi materi promosi, dan sistem aplikasi adalah hak kekayaan intelektual resmi dari PT Wowin Purnomo Putera. Penggunaan, penggandaan, atau modifikasi tanpa izin tertulis dilarang keras.',
                    ),
                    _buildLegalSection(
                      '2. Akurasi Informasi & Harga Produk',
                      'Kami berupaya semaksimal mungkin memastikan data katalog, deskripsi produk, foto, dan harga (pcs maupun karton) ditampilkan secara akurat. Namun, harga grosir, skema promo bundling, dan ketersediaan stok dapat mengalami perubahan sewaktu-waktu sesuai ketentuan pabrik/distributor.',
                    ),
                    _buildLegalSection(
                      '3. Pengiriman & Pemeriksaan Barang',
                      'Pengiriman produk dilakukan oleh armada distribusi resmi Wowin atau jasa ekspedisi terpercaya. Mitra diwajibkan memeriksa kelengkapan dan kondisi fisik barang saat diterima. Klaim kerusakan atau ketidaksesuaian wajib dilaporkan maksimal 1x24 jam sejak barang diterima.',
                    ),
                    _buildLegalSection(
                      '4. Keamanan Akun & Kata Sandi',
                      'Pengguna bertanggung jawab penuh untuk menjaga kerahasiaan kata sandi (password) dan aktivitas transaksi yang terjadi pada akun miliknya. PT Wowin Purnomo Putera tidak bertanggung jawab atas kerugian yang ditimbulkan akibat kelalaian pemindahtanganan kredensial akun kepada pihak lain.',
                    ),
                    _buildLegalSection(
                      '5. Hak Perubahan Ketentuan',
                      'PT Wowin Purnomo Putera berhak melakukan penyesuaian, perbaikan fitur, maupun perubahan syarat & ketentuan ini sewaktu-waktu untuk meningkatkan efektivitas layanan dan kepatuhan hukum.',
                    ),
                    const SizedBox(height: 20),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  // --- MODAL PERMOHONAN HAPUS AKUN (GOOGLE PLAY COMPLIANCE) ---
  void _showDeleteAccountDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(color: Colors.red.shade50, borderRadius: BorderRadius.circular(10)),
              child: const Icon(Icons.warning_amber_rounded, color: Colors.red, size: 24),
            ),
            const SizedBox(width: 12),
            const Expanded(
              child: Text(
                'Hapus / Tutup Akun',
                style: TextStyle(fontSize: 17, fontWeight: FontWeight.bold, color: Colors.black87),
              ),
            ),
          ],
        ),
        content: const SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Perhatian Sebelum Mengajukan:',
                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13.5, color: Colors.red),
              ),
              SizedBox(height: 8),
              Text(
                '• Seluruh saldo poin loyalitas dan tier membership akan hangus secara permanen.\n'
                '• Akses riwayat pesanan dan nota belanja digital akan dinonaktifkan.\n'
                '• Sesuai kebijakan Google Play dan keamanan transaksi, permohonan penghapusan akun akan diverifikasi oleh Admin melalui formulir web resmi dalam 1–3 hari kerja untuk memastikan tidak ada pesanan tertunda.',
                style: TextStyle(fontSize: 12.5, color: Color(0xFF4B5563), height: 1.5),
              ),
            ],
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Batal', style: TextStyle(color: Colors.grey, fontWeight: FontWeight.bold)),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.red,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
            ),
            onPressed: () async {
              Navigator.pop(ctx);
              final Uri url = Uri.parse('https://mywowin.com/delete-account');
              try {
                if (await canLaunchUrl(url)) {
                  await launchUrl(url, mode: LaunchMode.externalApplication);
                } else {
                  await launchUrl(url, mode: LaunchMode.externalApplication);
                }
              } catch (_) {
                await launchUrl(url, mode: LaunchMode.externalApplication);
              }
            },
            child: const Text('Lanjut ke Form Web', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }

  Widget _buildLegalSection(String title, String content) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 18.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: WowinColors.textPrimary),
          ),
          const SizedBox(height: 6),
          Text(
            content,
            style: const TextStyle(fontSize: 12.5, color: Color(0xFF4B5563), height: 1.55),
          ),
        ],
      ),
    );
  }

  // --- FUNGSI POP-UP FORMULIR PENGAJUAN MITRA ---
  void _showPengajuanDialog() {
    final TextEditingController tokoController = TextEditingController();
    final TextEditingController hpController = TextEditingController();
    final TextEditingController alamatController = TextEditingController();

    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (dialogCtx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: const Text('Pengajuan Mitra', style: TextStyle(color: primaryGreen, fontWeight: FontWeight.bold)),
        content: SingleChildScrollView(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text('Dapatkan harga pabrik dan diskon grosir dengan mendaftar sebagai Mitra Wowin Food.', style: TextStyle(fontSize: 13, color: Colors.grey)),
              const SizedBox(height: 16),
              TextField(
                controller: tokoController,
                decoration: const InputDecoration(labelText: 'Nama Toko / Usaha', border: OutlineInputBorder(), isDense: true),
              ),
              const SizedBox(height: 12),
              TextField(
                controller: hpController,
                keyboardType: TextInputType.phone,
                decoration: const InputDecoration(labelText: 'Nomor WhatsApp', border: OutlineInputBorder(), isDense: true),
              ),
              const SizedBox(height: 12),
              TextField(
                controller: alamatController,
                maxLines: 2,
                decoration: const InputDecoration(labelText: 'Alamat Lengkap Toko', border: OutlineInputBorder(), isDense: true),
              ),
            ],
          ),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(dialogCtx), child: const Text('Batal', style: TextStyle(color: Colors.grey))),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: primaryGreen),
            onPressed: () async {
              if (tokoController.text.isEmpty || hpController.text.isEmpty || alamatController.text.isEmpty) {
                ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Harap lengkapi semua data!')));
                return;
              }

              final messenger = ScaffoldMessenger.of(context);
              Navigator.pop(dialogCtx); // Tutup dialog
              setState(() => _isLoading = true); // Tampilkan loading

              final prefs = await SharedPreferences.getInstance();
              final token = prefs.getString('auth_token');

              try {
                final response = await http.post(
                  Uri.parse('$baseUrl/request-membership'),
                  headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'},
                  body: {
                    'nama_toko': tokoController.text,
                    'no_hp': hpController.text,
                    'alamat': alamatController.text,
                  },
                );

                if (response.statusCode == 200) {
                  if (!mounted) return;
                  messenger.showSnackBar(const SnackBar(content: Text('Pengajuan berhasil dikirim!', style: TextStyle(fontWeight: FontWeight.bold)), backgroundColor: primaryGreen));
                  _fetchProfile(); // Tarik ulang data profil agar banner berubah jadi kuning
                } else {
                  if (!mounted) return;
                  setState(() => _isLoading = false);
                  messenger.showSnackBar(const SnackBar(content: Text('Gagal mengirim pengajuan. Coba lagi.')));
                }
              } catch (e) {
                if (!mounted) return;
                setState(() => _isLoading = false);
                messenger.showSnackBar(const SnackBar(content: Text('Terjadi kesalahan jaringan.')));
              }
            },
            child: const Text('Kirim Pengajuan', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }

  Future<void> _reapplyMembership() async {
    final messenger = ScaffoldMessenger.of(context);
    setState(() => _isLoading = true);
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    try {
      final response = await http.post(
        Uri.parse('$baseUrl/reapply-membership'),
        headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'},
      );

      if (response.statusCode == 200) {
        if (!mounted) return;
        messenger.showSnackBar(const SnackBar(content: Text('Pengajuan ulang berhasil! Menunggu ACC Admin.'), backgroundColor: primaryGreen));
        _fetchProfile(); // Tarik ulang data
      } else {
        if (!mounted) return;
        setState(() => _isLoading = false);
        messenger.showSnackBar(const SnackBar(content: Text('Gagal mengajukan ulang.')));
      }
    } catch (e) {
      if (!mounted) return;
      setState(() => _isLoading = false);
      messenger.showSnackBar(const SnackBar(content: Text('Terjadi kesalahan jaringan.')));
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const Scaffold(body: Center(child: CircularProgressIndicator(color: primaryGreen)));
    }

    final namaLengkap = _userData?['nama_lengkap'] ?? 'Member Wowin';
    final email = _userData?['email'] ?? '';
    final fotoProfile = _userData?['foto_profile'];
    final imageUrl = fotoProfile != null ? 'https://mywowin.com/storage/$fotoProfile' : '';

    // Identitas Member
    final memberId = _userData?['id']?.toString() ?? '-';
    final rawDate = _userData?['created_at'];
    String joinDate = '-';
    if (rawDate != null) {
      try {
        final date = DateTime.parse(rawDate);
        joinDate = DateFormat('dd MMM yyyy').format(date);
      } catch (_) {}
    }

    final membership = _userData?['membership'] is Map ? _userData!['membership'] as Map<String, dynamic> : <String, dynamic>{};
    final namaToko = membership['nama_toko'] ?? '-';
    final namaSales = membership['nama_sales'] ?? '-';
    final noHp = membership['no_hp'] ?? '-';
    final alamat = membership['alamat'] ?? '-';
    final currentLevel = (membership['level_membership'] ?? 'Bronze').toString().toLowerCase();

    // --- JIKA TIDAK ADA MEMBERSHIP, MAKA DIA BELUM PENGAJUAN ---
    final statusAcc = membership['status_acc'] ?? 'belum_pengajuan';

    final loginStreak = int.tryParse(_userData?['login_streak']?.toString() ?? '0') ?? 0;
    // Ubah penampung menjadi totalPoints dan ambil dari 'total_points'
    final totalPoints = int.tryParse(_userData?['total_points']?.toString() ?? '0') ?? 0;

    final progress = _userData?['progress'] ?? {};
    final totalBelanja = double.tryParse(progress['total_belanja']?.toString() ?? '0') ?? 0;
    final targetBerikutnya = double.tryParse(progress['target_berikutnya']?.toString() ?? '0') ?? 0;
    final sisaKebutuhan = double.tryParse(progress['sisa_kebutuhan']?.toString() ?? '0') ?? 0;
    final progressPercent = double.tryParse(progress['percentage']?.toString() ?? '0') ?? 0;
    final levelBerikutnya = progress['level_berikutnya'] ?? '';
    final isMaxLevel = progress['is_max_level'] ?? true;

    final currencyFormat = NumberFormat.currency(locale: 'id', symbol: 'Rp ', decimalDigits: 0);

    return Scaffold(
      backgroundColor: WowinColors.background,
      appBar: WowinAppBar.standard(
        title: 'Profil Membership',
        automaticallyImplyLeading: widget.showBackButton,
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // --- HEADER PROGRESS LEVEL ---
            Container(
              width: double.infinity,
              decoration: const BoxDecoration(gradient: WowinGradients.royalEmerald),
              padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 16),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: ['bronze', 'silver', 'gold', 'platinum', 'diamond'].map((level) {
                  final isActive = level == currentLevel;
                  return Column(
                    children: [
                      Container(
                        width: 40, height: 40,
                        decoration: BoxDecoration(
                          color: isActive ? Colors.white : Colors.white.withValues(alpha: 0.1),
                          shape: BoxShape.circle,
                          boxShadow: isActive ? [BoxShadow(color: Colors.white.withValues(alpha: 0.4), blurRadius: 8, spreadRadius: 1)] : null,
                        ),
                        child: Icon(
                          level == 'bronze' || level == 'gold' ? Icons.emoji_events : (level == 'silver' ? Icons.shield : Icons.diamond),
                          color: isActive ? (level == 'bronze' ? Colors.orange[800] : level == 'silver' ? Colors.grey[800] : level == 'gold' ? Colors.amber[600] : level == 'platinum' ? Colors.blue : Colors.purple) : Colors.white.withValues(alpha: 0.3),
                          size: 20,
                        ),
                      ),
                      const SizedBox(height: 6),
                      Text(
                        level.toUpperCase(),
                        style: TextStyle(fontSize: 9.5, fontWeight: isActive ? FontWeight.bold : FontWeight.w500, color: isActive ? Colors.white : Colors.white.withValues(alpha: 0.4)),
                      )
                    ],
                  );
                }).toList(),
              ),
            ),

            // --- PROFIL & STATISTIK (Login Streak & Poin) ---
            Container(
              color: Colors.white,
              padding: const EdgeInsets.all(24),
              child: Column(
                children: [
                  Stack(
                    clipBehavior: Clip.none,
                    children: [
                      Container(
                        width: 100, height: 100,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          border: Border.all(color: Colors.white, width: 4),
                          boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.1), blurRadius: 8)],
                          image: DecorationImage(image: NetworkImage(imageUrl), fit: BoxFit.cover),
                        ),
                      ),
                      Positioned(
                        bottom: -10, left: 0, right: 0,
                        child: Center(
                          child: Container(
                            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
                            decoration: BoxDecoration(color: primaryGreen, borderRadius: BorderRadius.circular(20), boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.2), blurRadius: 4)]),
                            child: Text(currentLevel.toUpperCase(), style: const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.bold)),
                          ),
                        ),
                      )
                    ],
                  ),
                  const SizedBox(height: 20),
                  Text(namaLengkap, style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: Colors.black87)),
                  const SizedBox(height: 4),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.email_outlined, size: 14, color: Colors.grey[600]),
                      const SizedBox(width: 6),
                      Text(email, style: TextStyle(color: Colors.grey[600], fontSize: 13)),
                    ],
                  ),
                  const SizedBox(height: 16), // Jarak disesuaikan

                  // --- TAMBAHAN TOMBOL EDIT PROFIL ---
                  SizedBox(
                    height: 36,
                    child: OutlinedButton.icon(
                      onPressed: () async {
                        // Buka halaman edit, dan tunggu hasilnya
                        final result = await Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => EditProfileScreen(userData: _userData ?? {}),
                          ),
                        );
                        // Jika kembali dengan membawa nilai 'true', otomatis refresh data
                        if (result == true) {
                          setState(() => _isLoading = true);
                          _fetchProfile();
                        }
                      },
                      icon: const Icon(Icons.edit_outlined, size: 16, color: primaryGreen),
                      label: const Text('Edit Profil & Foto', style: TextStyle(color: primaryGreen, fontSize: 13, fontWeight: FontWeight.bold)),
                      style: OutlinedButton.styleFrom(
                        side: const BorderSide(color: primaryGreen),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                      ),
                    ),
                  ),
                  // ------------------------------------

                  const SizedBox(height: 24),

                  Row(
                    children: [
                      Expanded(
                        child: Container(
                          padding: const EdgeInsets.all(16),
                          decoration: BoxDecoration(color: Colors.blue[50], borderRadius: BorderRadius.circular(16)),
                          child: Column(
                            children: [
                              Text('$loginStreak/30', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.blue[700])),
                              const SizedBox(height: 2),
                              Text('Login Streak', style: TextStyle(fontSize: 12, color: Colors.blue[600])),
                              const SizedBox(height: 10),
                              Container(
                                height: 6, width: double.infinity,
                                decoration: BoxDecoration(color: Colors.blue[200], borderRadius: BorderRadius.circular(10)),
                                child: FractionallySizedBox(
                                  alignment: Alignment.centerLeft,
                                  widthFactor: (loginStreak / 30).clamp(0.0, 1.0),
                                  child: Container(decoration: BoxDecoration(color: Colors.blue[600], borderRadius: BorderRadius.circular(10))),
                                ),
                              )
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Container(
                          padding: const EdgeInsets.all(16),
                          decoration: BoxDecoration(color: Colors.green[50], borderRadius: BorderRadius.circular(16)),
                          child: Column(
                            children: [
                              // Tampilkan Total Poin
                              Text('$totalPoints', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.green[700])),
                              const SizedBox(height: 2),
                              // Ubah judulnya
                              Text('Total Points', style: TextStyle(fontSize: 12, color: Colors.green[600])),
                              const SizedBox(height: 6),
                              const Icon(Icons.star, color: Colors.amber, size: 20),
                            ],
                          ),
                        ),
                      ),
                    ],
                  )
                ],
              ),
            ),

            const SizedBox(height: 12),

            // --- TAMBAHAN BARU: BANNER STATUS ACC MEMBER ---
            // 1. BANNER BIRU: JIKA BELUM PERNAH MENGAJUKAN SAMA SEKALI
            if (statusAcc == 'belum_pengajuan')
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(color: Colors.blue[50], border: Border.all(color: Colors.blue[300]!), borderRadius: BorderRadius.circular(12)),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Icon(Icons.storefront, color: Colors.blue[700], size: 24),
                        const SizedBox(width: 12),
                        const Expanded(child: Text('Dapatkan Harga Grosir!', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.blue, fontSize: 14))),
                      ],
                    ),
                    const SizedBox(height: 8),
                    const Text('Anda saat ini berbelanja sebagai Pelanggan Reguler. Ajukan kemitraan sekarang untuk mendapatkan harga diskon!', style: TextStyle(color: Colors.black87, fontSize: 12, height: 1.4)),
                    const SizedBox(height: 12),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        style: ElevatedButton.styleFrom(backgroundColor: Colors.blue[700], shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8))),
                        onPressed: _showPengajuanDialog, // <-- Panggil fungsi formulir
                        child: const Text('Ajukan Kemitraan', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                      ),
                    )
                  ],
                ),
              )
            // 2. BANNER KUNING: SEDANG MENUNGGU ACC
            else if (statusAcc == 'pending')
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.amber[50],
                  border: Border.all(color: Colors.amber[300]!),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Icon(Icons.hourglass_top, color: Colors.amber[700], size: 24),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Menunggu Persetujuan Admin', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.amber[900], fontSize: 14)),
                          const SizedBox(height: 4),
                          Text('Akun mitra Anda sedang ditinjau. Anda akan mendapatkan harga khusus/diskon grosir setelah akun di-ACC.', style: TextStyle(color: Colors.amber[800], fontSize: 12, height: 1.4)),
                        ],
                      ),
                    ),
                  ],
                ),
              )
            // 3. BANNER MERAH: PENGAJUAN DITOLAK ATAU MEMBER DINONAKTIFKAN
            else if (statusAcc == 'rejected')
                Container(
                  margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: Colors.red[50],
                    border: Border.all(color: Colors.red[300]!),
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          Icon(Icons.cancel_outlined, color: Colors.red[700], size: 24),
                          const SizedBox(width: 12),
                          Expanded(
                            child: Text(
                              'Status Kemitraan Dinonaktifkan',
                              style: TextStyle(fontWeight: FontWeight.bold, color: Colors.red[900], fontSize: 14),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 8),
                      Text(
                        'Pengajuan Anda ditolak atau status keanggotaan Anda telah dinonaktifkan oleh Admin. Anda tetap dapat berbelanja sebagai Pelanggan Reguler tanpa diskon mitra.',
                        style: TextStyle(color: Colors.red[800], fontSize: 12, height: 1.4),
                      ),
                      const SizedBox(height: 12),
                      SizedBox(
                        width: double.infinity,
                        child: ElevatedButton(
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.red[700],
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                          ),
                          // Sekarang hanya butuh 1-klik untuk kembalikan status ke Pending
                          onPressed: _reapplyMembership,
                          child: const Text('Ajukan Ulang Kemitraan', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                        ),
                      )
                    ],
                  ),
                ),

            // --- HANYA TAMPILKAN JIKA STATUS SUDAH DI-ACC ADMIN ---
            if (statusAcc == 'approved') ...[

              // --- INFORMASI MEMBERSHIP LIST ---
              Container(
                color: Colors.white,
                padding: const EdgeInsets.only(top: 24, left: 24, right: 24, bottom: 8),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Informasi Membership', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 16),
                    _buildInfoRow(Icons.storefront, 'Nama Toko', namaToko),
                    _buildInfoRow(Icons.badge_outlined, 'Nama Sales', namaSales),
                    _buildInfoRow(Icons.phone_outlined, 'Nomor Telepon', noHp),
                    _buildInfoRow(Icons.location_on_outlined, 'Alamat', alamat),
                  ],
                ),
              ),

              // --- KARTU MEMBERSHIP VIP WOWIN FOOD ---
              Container(
                color: Colors.white,
                padding: const EdgeInsets.only(bottom: 24),
                child: _buildDigitalCard(memberId, namaLengkap, namaToko, currentLevel, joinDate),
              ),

              const SizedBox(height: 12),

              // --- PROGRES MEMBERSHIP (KARTU UNGU) ---
              Container(
                color: Colors.white,
                padding: const EdgeInsets.all(24),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Container(
                          padding: const EdgeInsets.all(6),
                          decoration: BoxDecoration(color: Colors.purple[50], borderRadius: BorderRadius.circular(8)),
                          child: Icon(Icons.trending_up, color: Colors.purple[700], size: 20),
                        ),
                        const SizedBox(width: 12),
                        const Text('Progres Membership', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Color(0xFF6B21A8))),
                      ],
                    ),
                    const SizedBox(height: 24),

                    if (isMaxLevel)
                      Container(
                        padding: const EdgeInsets.all(16),
                        decoration: BoxDecoration(color: Colors.yellow[50], border: const Border(left: BorderSide(color: Colors.amber, width: 4)), borderRadius: BorderRadius.circular(8)),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text('👑 Selamat!', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.yellow[800], fontSize: 16)),
                            const SizedBox(height: 4),
                            Text('Anda telah mencapai level membership tertinggi: Diamond.', style: TextStyle(color: Colors.yellow[800], fontSize: 14)),
                          ],
                        ),
                      )
                    else
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            crossAxisAlignment: CrossAxisAlignment.end,
                            children: [
                              Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  const Text('Belanja Bulan Ini:', style: TextStyle(fontSize: 12, color: Colors.grey, fontWeight: FontWeight.w500)),
                                  Text(currencyFormat.format(totalBelanja), style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.purple[700])),
                                ],
                              ),
                              Column(
                                crossAxisAlignment: CrossAxisAlignment.end,
                                children: [
                                  Text('Target ${levelBerikutnya.toUpperCase()}:', style: const TextStyle(fontSize: 11, color: Colors.grey, fontStyle: FontStyle.italic)),
                                  Text(currencyFormat.format(targetBerikutnya), style: const TextStyle(fontSize: 13, fontWeight: FontWeight.bold, color: Colors.black87)),
                                ],
                              ),
                            ],
                          ),
                          const SizedBox(height: 12),

                          Container(
                            height: 20, width: double.infinity,
                            decoration: BoxDecoration(color: Colors.grey[200], borderRadius: BorderRadius.circular(20), boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.1), blurRadius: 2)]),
                            child: FractionallySizedBox(
                              alignment: Alignment.centerLeft,
                              widthFactor: (progressPercent / 100).clamp(0.0, 1.0),
                              child: Container(
                                decoration: BoxDecoration(
                                  gradient: LinearGradient(colors: [Colors.purple[500]!, Colors.purple[800]!]),
                                  borderRadius: BorderRadius.circular(20),
                                  boxShadow: [BoxShadow(color: Colors.purple.withValues(alpha: 0.3), blurRadius: 4, offset: const Offset(0, 2))],
                                ),
                                alignment: Alignment.center,
                                child: Text('${progressPercent.round()}%', style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold)),
                              ),
                            ),
                          ),
                          const SizedBox(height: 8),

                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text('LEVEL: ${currentLevel.toUpperCase()}', style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.grey[500], letterSpacing: 1)),
                              Text('MENUJU: ${levelBerikutnya.toUpperCase()}', style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.grey[500], letterSpacing: 1)),
                            ],
                          ),
                          const SizedBox(height: 16),

                          Container(
                            padding: const EdgeInsets.all(12),
                            decoration: BoxDecoration(color: Colors.purple[50], border: Border.all(color: Colors.purple[100]!), borderRadius: BorderRadius.circular(12)),
                            child: Row(
                              children: [
                                Icon(Icons.info, color: Colors.purple[600], size: 18),
                                const SizedBox(width: 12),
                                Expanded(
                                  child: RichText(
                                    text: TextSpan(
                                        style: TextStyle(color: Colors.grey[800], fontSize: 13, height: 1.4),
                                        children: [
                                          const TextSpan(text: 'Beli '),
                                          TextSpan(text: currencyFormat.format(sisaKebutuhan), style: const TextStyle(fontWeight: FontWeight.bold)),
                                          const TextSpan(text: ' lagi untuk naik ke level '),
                                          TextSpan(text: levelBerikutnya, style: const TextStyle(fontWeight: FontWeight.bold)),
                                        ]
                                    ),
                                  ),
                                )
                              ],
                            ),
                          )
                        ],
                      )
                  ],
                ),
              ),

              const SizedBox(height: 12),
            ], // <-- PENUTUP PEMBUNGKUS IF ACC

            // --- KEUNTUNGAN MEMBERSHIP (TIER CARDS) ---
            Container(
              color: Colors.white,
              padding: const EdgeInsets.symmetric(vertical: 24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 24),
                    child: Row(
                      children: [
                        Container(
                          padding: const EdgeInsets.all(8),
                          decoration: BoxDecoration(gradient: wowinGradient, borderRadius: BorderRadius.circular(12)),
                          child: const Icon(Icons.diamond, color: Colors.white, size: 20),
                        ),
                        const SizedBox(width: 16),
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text('Keuntungan Membership', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.black87)),
                            Text('MyWowin Partnership Program', style: TextStyle(fontSize: 12, color: Colors.grey[500])),
                          ],
                        )
                      ],
                    ),
                  ),
                  const SizedBox(height: 24),

                  SizedBox(
                    height: 380,
                    child: ListView.builder(
                      scrollDirection: Axis.horizontal,
                      padding: const EdgeInsets.symmetric(horizontal: 16),
                      itemCount: _membershipTiers.length + 1,
                      itemBuilder: (context, index) {

                        if (index == _membershipTiers.length) {
                          return _buildPrivilegeCard();
                        }

                        final tier = _membershipTiers[index];
                        final benefits = tier['benefits'] as List;

                        return Container(
                          width: 260,
                          margin: const EdgeInsets.symmetric(horizontal: 8),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(20),
                            border: Border.all(color: tier['border'], width: 2),
                            boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.03), blurRadius: 10, offset: const Offset(0, 4))],
                          ),
                          child: Stack(
                            children: [
                              Positioned.fill(
                                child: Container(
                                  decoration: BoxDecoration(borderRadius: BorderRadius.circular(18), color: tier['bg']!.withValues(alpha: 0.3)),
                                ),
                              ),
                              Padding(
                                padding: const EdgeInsets.all(16.0),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Row(
                                      children: [
                                        Container(
                                          width: 40, height: 40,
                                          decoration: BoxDecoration(color: tier['bg'], borderRadius: BorderRadius.circular(12), border: Border.all(color: tier['border'])),
                                          child: Icon(tier['icon'], color: tier['color'], size: 20),
                                        ),
                                        const SizedBox(width: 12),
                                        Column(
                                          crossAxisAlignment: CrossAxisAlignment.start,
                                          children: [
                                            Text(tier['subtitle'], style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.grey[500], letterSpacing: 1)),
                                            Text(tier['name'], style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.black87, height: 1.1)),
                                          ],
                                        )
                                      ],
                                    ),
                                    const SizedBox(height: 20),

                                    Expanded(
                                      child: Column(
                                        children: benefits.map((b) {
                                          return Container(
                                            margin: const EdgeInsets.only(bottom: 8),
                                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                                            decoration: BoxDecoration(color: Colors.white.withValues(alpha: 0.8), borderRadius: BorderRadius.circular(8), border: Border.all(color: Colors.grey[100]!)),
                                            child: Row(
                                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                              children: [
                                                Row(
                                                  children: [
                                                    Container(width: 6, height: 6, decoration: const BoxDecoration(color: primaryGreen, shape: BoxShape.circle)),
                                                    const SizedBox(width: 8),
                                                    Text('${b['qty']} Dus', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600, color: Colors.grey[700])),
                                                  ],
                                                ),
                                                Container(
                                                  padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                                                  decoration: BoxDecoration(color: Colors.green[50], borderRadius: BorderRadius.circular(4)),
                                                  child: Text('${b['disc']}%', style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: primaryGreen)),
                                                )
                                              ],
                                            ),
                                          );
                                        }).toList(),
                                      ),
                                    ),

                                    Container(
                                      padding: const EdgeInsets.only(top: 12),
                                      decoration: BoxDecoration(border: Border(top: BorderSide(color: Colors.grey[200]!))),
                                      child: Column(
                                        children: [
                                          Row(
                                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                            children: [
                                              Text('Min. Belanja:', style: TextStyle(fontSize: 11, color: Colors.grey[500], fontWeight: FontWeight.w500)),
                                              Text(tier['min_purchase'] > 0 ? '${tier['min_purchase']} Juta' : 'Tanpa Minimal', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: tier['color'])),
                                            ],
                                          ),
                                          const SizedBox(height: 8),
                                          if (tier['portal_fee'] > 0)
                                            Container(
                                              width: double.infinity,
                                              padding: const EdgeInsets.symmetric(vertical: 4),
                                              decoration: BoxDecoration(color: Colors.blue[50], borderRadius: BorderRadius.circular(20)),
                                              child: Row(
                                                mainAxisAlignment: MainAxisAlignment.center,
                                                children: [
                                                  Icon(Icons.door_sliding, size: 12, color: Colors.blue[700]),
                                                  const SizedBox(width: 4),
                                                  Text('Biaya Portal: Rp ${NumberFormat('#,##0', 'id').format(tier['portal_fee'])}', style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.blue[700])),
                                                ],
                                              ),
                                            )
                                          else
                                            const Center(child: Text('Gratis Biaya Portal', style: TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Colors.green))),
                                        ],
                                      ),
                                    )
                                  ],
                                ),
                              )
                            ],
                          ),
                        );
                      },
                    ),
                  )
                ],
              ),
            ),

            // --- LAYANAN LAINNYA & LOGOUT ---
            Container(
              color: Colors.grey[50],
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Layanan Lainnya', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.black87)),
                  const SizedBox(height: 16),

                  Container(
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: Colors.grey.shade200),
                      boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.02), blurRadius: 10, offset: const Offset(0, 4))],
                    ),
                    child: Column(
                      children: [
                        _buildGroupedMenu(context, Icons.shopping_bag_outlined, 'Pesanan Saya', () {
                          // Buka layar riwayat pesanan
                          Navigator.push(context, MaterialPageRoute(builder: (context) => const HistoryScreen()));
                        }),
                        Divider(height: 1, thickness: 1, color: Colors.grey[100], indent: 56),
                        _buildGroupedMenu(context, Icons.star_border, 'Klaim Reward Poin', () {
                          Navigator.push(context, MaterialPageRoute(builder: (context) => const RewardScreen()));
                        }),
                        Divider(height: 1, thickness: 1, color: Colors.grey[100], indent: 56),
                        _buildGroupedMenu(context, Icons.headset_mic_outlined, 'Hubungi CS Wowin', () {
                          _contactCS();
                        }),
                        Divider(height: 1, thickness: 1, color: Colors.grey[100], indent: 56),
                        _buildGroupedMenu(context, Icons.privacy_tip_outlined, 'Kebijakan Privasi', () {
                          _showPrivacyPolicyModal(context);
                        }),
                        Divider(height: 1, thickness: 1, color: Colors.grey[100], indent: 56),
                        _buildGroupedMenu(context, Icons.gavel_outlined, 'Disclaimer & Ketentuan', () {
                          _showDisclaimerModal(context);
                        }),
                        Divider(height: 1, thickness: 1, color: Colors.grey[100], indent: 56),
                        _buildGroupedMenu(
                          context,
                          Icons.person_remove_outlined,
                          'Hapus / Tutup Akun',
                          () {
                            _showDeleteAccountDialog(context);
                          },
                          iconColor: Colors.red.shade700,
                          iconBgColor: Colors.red.shade50,
                          textColor: Colors.red.shade700,
                        ),
                      ],
                    ),
                  ),

                  const SizedBox(height: 32),

                  Material(
                    color: Colors.transparent,
                    child: Ink(
                      decoration: BoxDecoration(color: Colors.red[50], borderRadius: BorderRadius.circular(12), border: Border.all(color: Colors.red.shade200)),
                      child: InkWell(
                        onTap: () async {
                          await ref.read(authProvider.notifier).logout();
                          if (context.mounted) {
                            Navigator.pop(context);
                            ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Anda telah keluar dari akun.'), backgroundColor: Colors.grey));
                          }
                        },
                        borderRadius: BorderRadius.circular(12),
                        child: Container(
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          alignment: Alignment.center,
                          child: const Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(Icons.logout, color: Colors.red, size: 20),
                              SizedBox(width: 8),
                              Text('Keluar (Logout)', style: TextStyle(color: Colors.red, fontWeight: FontWeight.bold, fontSize: 16)),
                            ],
                          ),
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 40),
                ],
              ),
            )
          ],
        ),
      ),
    );
  }

  // --- KARTU FISIK DIGITAL WOWIN FOOD ---
  Widget _buildDigitalCard(String memberId, String namaLengkap, String namaToko, String level, String joinDate) {
    // Memastikan huruf pertama kapital
    final displayLevel = level.isNotEmpty ? level[0].toUpperCase() + level.substring(1) : '';

    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 24),
      width: double.infinity,
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF0E4D1D), Color(0xFF1B6A2B)], // Gradasi hijau pekat
          begin: Alignment.bottomLeft,
          end: Alignment.topRight,
        ),
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(color: Colors.green.withValues(alpha: 0.3), blurRadius: 10, offset: const Offset(0, 5))
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(16),
        child: Stack(
          children: [
            // Lingkaran Hiasan Kanan Atas
            Positioned(
              right: -40, top: -50,
              child: Container(
                width: 180, height: 180,
                decoration: BoxDecoration(shape: BoxShape.circle, color: Colors.greenAccent.withValues(alpha: 0.08)),
              ),
            ),
            // Lingkaran Hiasan Kiri Bawah
            Positioned(
              left: -60, bottom: -60,
              child: Container(
                width: 200, height: 200,
                decoration: BoxDecoration(shape: BoxShape.circle, color: Colors.greenAccent.withValues(alpha: 0.08)),
              ),
            ),

            // Teks Identitas Kartu
            Padding(
              padding: const EdgeInsets.all(24.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('MEMBER ID', style: TextStyle(color: Colors.green[200], fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 1)),
                          const SizedBox(height: 2),
                          Text(memberId, style: const TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold)),
                          const SizedBox(height: 20),
                          Text(namaLengkap.toUpperCase(), style: const TextStyle(color: Colors.white, fontSize: 15, fontWeight: FontWeight.bold)),
                          const SizedBox(height: 2),
                          Text(namaToko, style: TextStyle(color: Colors.green[100], fontSize: 14)),
                        ],
                      ),
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.end,
                        children: [
                          Text('LEVEL', style: TextStyle(color: Colors.green[200], fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 1)),
                          const SizedBox(height: 2),
                          Text(displayLevel, style: const TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold)),
                          const SizedBox(height: 28),
                          Text(joinDate, style: const TextStyle(color: Colors.white, fontSize: 13, fontWeight: FontWeight.w500)),
                        ],
                      )
                    ],
                  ),
                  const SizedBox(height: 20),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.end,
                    children: [
                      const Text('Wowin Food', style: TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold)),
                      const SizedBox(width: 4),
                      Icon(Icons.verified, color: Colors.blue[200], size: 18),
                    ],
                  )
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildInfoRow(IconData icon, String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: TextStyle(fontSize: 12, fontWeight: FontWeight.w500, color: Colors.grey[500])),
          const SizedBox(height: 6),
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(color: Colors.grey[50], borderRadius: BorderRadius.circular(8), border: Border.all(color: Colors.grey[200]!)),
            child: Row(
              children: [
                Icon(icon, color: primaryGreen, size: 20),
                const SizedBox(width: 12),
                Expanded(child: Text(value, style: const TextStyle(color: Colors.black87, fontWeight: FontWeight.w500, fontSize: 14))),
              ],
            ),
          )
        ],
      ),
    );
  }

  Widget _buildGroupedMenu(
    BuildContext context,
    IconData icon,
    String title,
    VoidCallback onTap, {
    Color? iconColor,
    Color? iconBgColor,
    Color? textColor,
  }) {
    final effectiveIconColor = iconColor ?? primaryGreen;
    final effectiveBgColor = iconBgColor ?? primaryGreen.withValues(alpha: 0.1);
    final effectiveTextColor = textColor ?? Colors.black87;

    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
          child: Row(
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(color: effectiveBgColor, borderRadius: BorderRadius.circular(8)),
                child: Icon(icon, color: effectiveIconColor, size: 20),
              ),
              const SizedBox(width: 16),
              Expanded(child: Text(title, style: TextStyle(fontSize: 15, fontWeight: FontWeight.w500, color: effectiveTextColor))),
              const Icon(Icons.arrow_forward_ios, size: 14, color: Colors.grey),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildPrivilegeCard() {
    return Container(
      width: 260,
      margin: const EdgeInsets.symmetric(horizontal: 8),
      decoration: BoxDecoration(
        gradient: wowinGradient,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [BoxShadow(color: primaryGreen.withValues(alpha: 0.3), blurRadius: 12, offset: const Offset(0, 6))],
      ),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Container(
                  width: 40, height: 40,
                  decoration: BoxDecoration(color: Colors.white.withValues(alpha: 0.2), borderRadius: BorderRadius.circular(12)),
                  child: const Icon(Icons.star, color: Colors.yellow, size: 20),
                ),
                const SizedBox(width: 12),
                const Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text('EXCLUSIVE', style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.greenAccent, letterSpacing: 1)),
                    Text('Privilege+', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.white, height: 1.1)),
                  ],
                )
              ],
            ),
            const SizedBox(height: 24),
            Expanded(
              child: Column(
                children: [
                  _buildPrivilegeRow(Icons.local_shipping, 'Gratis Ongkir', 'Pengiriman terjadwal tanpa biaya'),
                  const SizedBox(height: 12),
                  _buildPrivilegeRow(Icons.event, 'Event VIP', 'Akses eksklusif acara WOWINFood'),
                  const SizedBox(height: 12),
                  _buildPrivilegeRow(Icons.card_giftcard, 'Bonus Poin', 'Tukar poin dengan hadiah menarik'),
                ],
              ),
            ),
            Container(
              padding: const EdgeInsets.only(top: 12),
              decoration: BoxDecoration(border: Border(top: BorderSide(color: Colors.white.withValues(alpha: 0.2)))),
              child: const Center(
                child: Text('*Berlaku untuk semua level membership', style: TextStyle(fontSize: 9, color: Colors.greenAccent, fontStyle: FontStyle.italic)),
              ),
            )
          ],
        ),
      ),
    );
  }

  Widget _buildPrivilegeRow(IconData icon, String title, String desc) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Container(
          padding: const EdgeInsets.all(6),
          decoration: BoxDecoration(color: Colors.yellow.withValues(alpha: 0.2), borderRadius: BorderRadius.circular(8)),
          child: Icon(icon, color: Colors.yellow, size: 16),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(title, style: const TextStyle(fontSize: 13, fontWeight: FontWeight.bold, color: Colors.white)),
              const SizedBox(height: 2),
              Text(desc, style: const TextStyle(fontSize: 10, color: Colors.white70, height: 1.2)),
            ],
          ),
        )
      ],
    );
  }
}