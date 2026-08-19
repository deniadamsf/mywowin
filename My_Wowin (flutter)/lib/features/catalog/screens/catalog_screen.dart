import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:url_launcher/url_launcher.dart';
import '../providers/catalog_provider.dart';
import 'product_detail_screen.dart';
import 'all_products_screen.dart';
import '../../auth/screens/login_screen.dart';
import '../../cart/screens/cart_screen.dart';
import '../../cart/providers/cart_provider.dart';
import '../../auth/screens/profile_screen.dart';
import '../../auth/providers/auth_provider.dart';
import '../../order/screens/history_screen.dart';
import '../../auth/screens/reward_screen.dart';
import 'category_screen.dart';
import 'promo_screen.dart';
import 'promo_detail_screen.dart';
import '../../chat/screens/live_chat_screen.dart';
import 'package:shared_preferences/shared_preferences.dart';

class CatalogScreen extends ConsumerStatefulWidget {
  const CatalogScreen({super.key});

  @override
  ConsumerState<CatalogScreen> createState() => _CatalogScreenState();
}

class _CatalogScreenState extends ConsumerState<CatalogScreen> {
  static const Color wowinGreen = Color(0xFF1B5E20);
  int _selectedIndex = 0;

  // --- STATE UNTUK TAB & FILTER ---
  String _activeTab = 'Rekomendasi';
  String _activeCategory = 'Semua Produk';

  List<dynamic> _heroList = [];
  List<dynamic> _bundlingList = [];
  bool _isLoadingBanners = true;
  String _userPoints = '0'; // <-- TAMBAHAN: Untuk menyimpan poin asli dari database

  final List<Map<String, dynamic>> _menuItems = [
    {'icon': Icons.category, 'label': 'Kategori', 'color': Colors.green},
    {'icon': Icons.local_offer, 'label': 'Promo', 'color': Colors.red[600]},
    {'icon': Icons.diamond, 'label': 'Member VIP', 'color': Colors.purple}, // <-- UBAH JADI MEMBER VIP
    {'icon': Icons.monetization_on, 'label': 'Point Rewards', 'color': Colors.amber[600]},
  ];

  List<dynamic> _categoryList = [
    {'name': 'Semua Produk'},
    {'name': 'KM Rajaku'},
    {'name': 'KM Jangkar'},
    {'name': 'KM WOWIN'},
    {'name': 'Saos Sambal'},
    {'name': 'Saos Tomat'},
    {'name': 'Cuka'},
  ];

  @override
  void initState() {
    super.initState();
    _fetchBanners();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _checkDailyReward();
      ref.read(cartProvider.notifier).fetchCart();
      _fetchUserPoints(); // <-- TAMBAHAN: Tarik data poin saat beranda dimuat
    });
  }

  // --- TAMBAHAN BARU: Fungsi Pengecek Ingatan Klaim ---
  Future<void> _checkDailyReward() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    // Cegah pop-up muncul jika user belum login
    if (token == null) return;

    // --- TAMBAHKAN BARIS INI UNTUK TESTING (HAPUS LAGI NANTI) ---
    //await prefs.remove('last_reward_claim_date');

    final String today = DateTime.now().toIso8601String().substring(0, 10);
    final String? lastClaimDate = prefs.getString('last_reward_claim_date');

    // Jika user BELUM klaim hari ini, maka munculkan bannernya!
    if (lastClaimDate != today) {
      _showRewardPopup();
    }
  }

  Future<void> _fetchBanners() async {
    try {
      final heroRes = await http.get(Uri.parse('$baseUrl/heroes'), headers: {'Accept': 'application/json'});
      final bundleRes = await http.get(Uri.parse('$baseUrl/bundlings'), headers: {'Accept': 'application/json'});

      try {
        final catRes = await http.get(Uri.parse('$baseUrl/categories'), headers: {'Accept': 'application/json'});
        if (catRes.statusCode == 200) {
          final catData = json.decode(catRes.body);
          if (catData['data'] != null && catData['data'].isNotEmpty) {
            _categoryList = [{'name': 'Semua Produk'}, ...catData['data']];
          }
        }
      } catch (_) {}

      if (mounted) {
        setState(() {
          if (heroRes.statusCode == 200) {
            final data = json.decode(heroRes.body);
            _heroList = data['data'] ?? data ?? [];
          }
          if (bundleRes.statusCode == 200) {
            final data = json.decode(bundleRes.body);
            _bundlingList = data['data'] ?? data ?? [];
          }
          _isLoadingBanners = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() => _isLoadingBanners = false);
      }
    }
  }

  Future<void> _fetchUserPoints() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    // Jika tidak ada token (belum login), hentikan fungsi
    if (token == null) return;

    try {
      final response = await http.get(
        Uri.parse('$baseUrl/profile'),
        headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'},
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        setState(() {
          // Mengambil data total_points dari database
          _userPoints = data['data']['total_points']?.toString() ?? '0';
        });
      }
    } catch (e) {
      debugPrint("Gagal tarik poin: $e");
    }
  }

  void _showRewardPopup() {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (BuildContext context) {
        return Dialog(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
          elevation: 10,
          child: Container(
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(20)),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Align(
                  alignment: Alignment.topRight,
                  child: GestureDetector(
                    onTap: () => Navigator.pop(context),
                    child: Icon(Icons.close, color: Colors.grey[400], size: 24),
                  ),
                ),
                Container(
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(color: Colors.amber.withValues(alpha: 0.15), shape: BoxShape.circle),
                  child: const Icon(Icons.monetization_on, color: Colors.amber, size: 60),
                ),
                const SizedBox(height: 20),
                const Text('Reward 30 Hari Login!', style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: wowinGreen), textAlign: TextAlign.center),
                const SizedBox(height: 12),
                Text('Luar biasa! Anda telah setia login selama 30 hari berturut-turut. Klaim koin ekstra Anda sekarang untuk ditukarkan dengan produk Wowin Food favorit!', style: TextStyle(fontSize: 14, color: Colors.grey[700], height: 1.5), textAlign: TextAlign.center),
                const SizedBox(height: 28),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: () async {
                      final prefs = await SharedPreferences.getInstance();
                      final token = prefs.getString('auth_token');
                      if (token == null) return;

                      try {
                        // 1. Tembak API Laravel untuk menambah poin di Database
                        final response = await http.post(
                            Uri.parse('$baseUrl/claim-reward'),
                            headers: {
                              'Accept': 'application/json',
                              'Authorization': 'Bearer $token'
                            }
                        );

                        if (response.statusCode == 200) {
                          // 2. Catat di memori HP agar banner tidak muncul lagi hari ini
                          final String today = DateTime.now().toIso8601String().substring(0, 10);
                          await prefs.setString('last_reward_claim_date', today);

                          // 3. Tarik ulang data poin terbaru dari server agar angka di atas berubah
                          await _fetchUserPoints();

                          // 4. Tutup popup dan tampilkan pesan sukses
                          if (context.mounted) {
                            Navigator.pop(context);
                            ScaffoldMessenger.of(context).showSnackBar(const SnackBar(
                                content: Text('Hore! Koin berhasil diklaim!', style: TextStyle(fontWeight: FontWeight.bold)),
                                backgroundColor: wowinGreen,
                                duration: Duration(seconds: 3)
                            ));
                          }
                        } else {
                          if (context.mounted) ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Gagal mengklaim reward. Server error.')));
                        }
                      } catch (e) {
                        if (context.mounted) ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Terjadi kesalahan jaringan.')));
                      }
                    },
                    style: ElevatedButton.styleFrom(backgroundColor: wowinGreen, padding: const EdgeInsets.symmetric(vertical: 16), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12))),
                    child: const Text('Klaim Koin', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white)),
                  ),
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  void _onBottomNavTapped(int index) {
    if (index == 1) {
      // --- SEKARANG MENGARAH KE LIVE CHAT IN-APP KITA ---
      final authState = ref.read(authProvider);
      if (authState.isAuthenticated) {
        Navigator.push(context, MaterialPageRoute(builder: (context) => const LiveChatScreen()));
      } else {
        Navigator.push(context, MaterialPageRoute(builder: (context) => const LoginScreen()));
      }
    } else if (index == 2) {
      final authState = ref.read(authProvider);
      Navigator.push(context, MaterialPageRoute(builder: (context) => authState.isAuthenticated ? const HistoryScreen() : const LoginScreen()));
    } else if (index == 3) {
      final authState = ref.read(authProvider);
      Navigator.push(context, MaterialPageRoute(builder: (context) => authState.isAuthenticated ? const ProfileScreen() : const LoginScreen()));
    } else {
      setState(() => _selectedIndex = index);
    }
  }

  // --- TAMBAHAN BARU: FUNGSI TARIK NOTIFIKASI DARI LARAVEL ---
  Future<List<dynamic>> _fetchNotifications() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    if (token == null) return [];

    try {
      final response = await http.get(
        Uri.parse('$baseUrl/notifications'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        return data['data'] ?? [];
      }
    } catch (e) {
      debugPrint("Error Notif: $e");
    }
    return [];
  }

  Future<void> _openWhatsApp() async {
    const String waNumber = '6281216301220';
    const String text = 'Hai admin, aku mau dong dibuatin banner untuk usahaku!';
    final Uri url = Uri.parse('https://wa.me/$waNumber?text=${Uri.encodeComponent(text)}');

    if (await canLaunchUrl(url)) {
      await launchUrl(url, mode: LaunchMode.externalApplication);
    } else {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Tidak dapat membuka WhatsApp.')));
    }
  }

  Widget _buildTabItem(String title) {
    bool isActive = _activeTab == title;
    return GestureDetector(
      onTap: () {
        setState(() {
          _activeTab = title;
          // Pindah ke promo reset kategori
          if (title == 'Promo') {
            _activeCategory = 'Semua Produk';
          }
        });
      },
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: TextStyle(
              fontSize: isActive ? 16 : 15,
              fontWeight: isActive ? FontWeight.bold : FontWeight.w500,
              color: isActive ? wowinGreen : Colors.grey[400],
            ),
          ),
          const SizedBox(height: 6),
          AnimatedContainer(
            duration: const Duration(milliseconds: 300),
            curve: Curves.easeInOut,
            height: 3,
            width: isActive ? 40 : 0,
            color: wowinGreen,
          ),
        ],
      ),
    );
  }

  // --- FUNGSI MEMUNCULKAN POP-UP NOTIFIKASI DARI BAWAH ---
  void _showNotificationSheet() {
    final authState = ref.read(authProvider);

    // Jika belum login, lempar ke halaman login
    if (!authState.isAuthenticated) {
      Navigator.push(context, MaterialPageRoute(builder: (context) => const LoginScreen()));
      return;
    }

    showModalBottomSheet(
      context: context,
      isScrollControlled: true, // Agar tingginya bisa diatur maksimal
      backgroundColor: Colors.transparent, // Transparan agar ujungnya bisa melengkung
      builder: (context) {
        return Container(
          height: MediaQuery.of(context).size.height * 0.65, // Mengambil 65% tinggi layar
          decoration: const BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
          ),
          child: Column(
            children: [
              // Garis handle di ujung atas
              Center(
                child: Container(
                  margin: const EdgeInsets.only(top: 12, bottom: 16),
                  width: 50,
                  height: 5,
                  decoration: BoxDecoration(color: Colors.grey.shade300, borderRadius: BorderRadius.circular(10)),
                ),
              ),

              // Judul Notifikasi
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 20),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text('Notifikasi Saya', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.black87)),
                    TextButton(
                      onPressed: () => Navigator.pop(context),
                      child: const Text('Tutup', style: TextStyle(color: wowinGreen, fontWeight: FontWeight.bold)),
                    )
                  ],
                ),
              ),
              const Divider(),

              // --- MENGGUNAKAN DATA ASLI DARI DATABASE ---
              Expanded(
                child: FutureBuilder<List<dynamic>>(
                  future: _fetchNotifications(),
                  builder: (context, snapshot) {
                    // 1. Saat data sedang dimuat
                    if (snapshot.connectionState == ConnectionState.waiting) {
                      return const Center(child: CircularProgressIndicator(color: wowinGreen));
                    }

                    // 2. Jika belum ada notifikasi
                    if (snapshot.hasError || !snapshot.hasData || snapshot.data!.isEmpty) {
                      return Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.notifications_off_outlined, size: 60, color: Colors.grey.shade300),
                            const SizedBox(height: 12),
                            Text('Belum ada notifikasi baru', style: TextStyle(color: Colors.grey.shade500)),
                          ],
                        ),
                      );
                    }

                    // 3. Jika notifikasi berhasil didapat
                    final notifs = snapshot.data!;
                    return ListView.builder(
                      padding: const EdgeInsets.all(16),
                      itemCount: notifs.length,
                      itemBuilder: (context, index) {
                        final item = notifs[index];
                        final notifData = item['data'] ?? {};

                        // Cek waktu
                        final createdAt = DateTime.tryParse(item['created_at'] ?? '');
                        final timeStr = createdAt != null
                            ? "${createdAt.day}/${createdAt.month}/${createdAt.year}"
                            : "Baru saja";

                        // Cek jenis icon
                        IconData iconData = Icons.notifications_active;
                        Color iconColor = wowinGreen;

                        if (notifData['icon'] == 'chat') {
                          iconData = Icons.chat;
                          iconColor = Colors.blue;
                        } else if (notifData['icon'] == 'promo') {
                          iconData = Icons.campaign;
                          iconColor = Colors.red;
                        }

                        return _buildNotificationItem(
                          icon: iconData,
                          color: iconColor,
                          title: notifData['title'] ?? 'Info Wowin',
                          subtitle: notifData['message'] ?? '-',
                          time: timeStr,
                        );
                      },
                    );
                  },
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  // --- WIDGET BANTUAN UNTUK DESAIN KOTAK NOTIFIKASI ---
  Widget _buildNotificationItem({required IconData icon, required Color color, required String title, required String subtitle, required String time}) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.05),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: color.withValues(alpha: 0.15)),
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(color: color.withValues(alpha: 0.15), shape: BoxShape.circle),
            child: Icon(icon, color: color, size: 24),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                const SizedBox(height: 6),
                Text(subtitle, style: TextStyle(fontSize: 12, color: Colors.grey.shade700, height: 1.4)),
                const SizedBox(height: 8),
                Text(time, style: TextStyle(fontSize: 10, color: Colors.grey.shade500)),
              ],
            ),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final catalogState = ref.watch(catalogProvider);
    final authState = ref.watch(authProvider);

    return Scaffold(
      backgroundColor: Colors.white,

      appBar: AppBar(
        elevation: 0,
        titleSpacing: 16,
        backgroundColor: Colors.transparent,
        flexibleSpace: Container(
          decoration: const BoxDecoration(
            gradient: LinearGradient(colors: [Color(0xFF0A4A1A), Color(0xFF2E7D32)], begin: Alignment.topLeft, end: Alignment.bottomRight),
          ),
        ),
        title: Row(
          children: [
            Expanded(
              child: GestureDetector(
                onTap: () {
                  // MENGARAHKAN KE HALAMAN SEMUA PRODUK SAAT DIKLIK
                  Navigator.push(context, MaterialPageRoute(builder: (context) => const AllProductsScreen()));
                },
                child: Container(
                  height: 40,
                  decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(8)),
                  child: Row(
                    children: [
                      const SizedBox(width: 10),
                      Icon(Icons.search, color: Colors.grey[600], size: 20),
                      const SizedBox(width: 8),
                      Text('Cari di Wowin Food', style: TextStyle(color: Colors.grey[500], fontSize: 14)),
                    ],
                  ),
                ),
              ),
            ),
            const SizedBox(width: 16),
            GestureDetector(
              onTap: () {
                Navigator.push(context, MaterialPageRoute(builder: (context) => authState.isAuthenticated ? const CartScreen() : const LoginScreen()));
              },
              // Membungkus Icon dengan Badge dari CartProvider
              child: Badge(
                isLabelVisible: ref.watch(cartProvider).items.isNotEmpty,
                label: Text(ref.watch(cartProvider).items.length.toString()),
                backgroundColor: Colors.orange, // Warna oranye agar mencolok
                child: const Icon(Icons.shopping_cart_outlined, color: Colors.white, size: 24),
              ),
            ),
            const SizedBox(width: 16),
            GestureDetector(
              onTap: () {
                // Panggil Bottom Sheet Notifikasi!
                _showNotificationSheet();
              },
              child: const Icon(Icons.notifications_none, color: Colors.white, size: 24),
            ),
          ],
        ),
      ),

      body: catalogState.when(
        loading: () => const Center(child: CircularProgressIndicator(color: wowinGreen)),
        error: (error, stack) => Center(child: Text('Error: $error')),
        data: (data) {
          final allProducts = data['products'] as List<dynamic>? ?? [];

          bool isPromoTab = _activeTab == 'Promo';
          List<dynamic> gridData = [];

          if (isPromoTab) {
            gridData = _bundlingList;
          } else {
            gridData = List.from(allProducts);

            if (_activeCategory != 'Semua Produk') {
              gridData = gridData.where((p) {
                final catName = p['category']?['name']?.toString().toLowerCase() ?? '';
                return catName == _activeCategory.toLowerCase();
              }).toList();
            }

            if (_activeTab == 'Terlaris') {
              gridData = gridData.reversed.toList();
            }
          }

          // --- MEMBATASI HANYA 10 PRODUK DI BERANDA ---
          final homepageGridData = gridData.take(10).toList();

          return SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  decoration: BoxDecoration(border: Border(bottom: BorderSide(color: Colors.grey.shade200))),
                  child: IntrinsicHeight(
                    child: Row(
                      children: [
                        Expanded(
                          child: Material(
                            color: Colors.transparent,
                            child: InkWell(
                              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => authState.isAuthenticated ? const ProfileScreen() : const LoginScreen())),
                              child: Padding(
                                padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 16),
                                child: Row(
                                  children: [
                                    const Icon(Icons.badge_outlined, color: wowinGreen, size: 20),
                                    const SizedBox(width: 8),
                                    Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        const Text('Tipe Member', style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold)),
                                        Text(authState.isAuthenticated ? 'Member Aktif' : 'Belum Login', style: const TextStyle(fontSize: 11, color: wowinGreen)),
                                      ],
                                    ),
                                  ],
                                ),
                              ),
                            ),
                          ),
                        ),
                        VerticalDivider(color: Colors.grey.shade300, width: 1, thickness: 1),
                        Expanded(
                          child: Material(
                            color: Colors.transparent,
                            child: InkWell(
                              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => authState.isAuthenticated ? const RewardScreen() : const LoginScreen())),
                              child: Padding(
                                padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 16),
                                child: Row(
                                  children: [
                                    const Icon(Icons.star_rounded, color: Colors.amber, size: 22),
                                    const SizedBox(width: 8),
                                    Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        const Text('Wowin Points', style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold)),
                                        Text(authState.isAuthenticated ? '$_userPoints Points' : '0 Points', style: TextStyle(fontSize: 11, color: Colors.grey[600])),
                                      ],
                                    ),
                                  ],
                                ),
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),

                Container(
                  height: 240,
                  width: double.infinity,
                  margin: const EdgeInsets.only(top: 16, left: 16, right: 16),
                  child: _isLoadingBanners
                      ? const Center(child: CircularProgressIndicator(color: wowinGreen))
                      : _heroList.isEmpty
                      ? _buildFallbackHero(context)
                      : PageView.builder(
                    itemCount: _heroList.length,
                    itemBuilder: (context, index) {
                      final hero = _heroList[index];
                      return GestureDetector(
                        onTap: _openWhatsApp,
                        child: ClipRRect(
                          borderRadius: BorderRadius.circular(16),
                          child: Stack(
                            fit: StackFit.expand,
                            children: [
                              Image.network('https://mywowin.com/storage/${hero['gambar_hero']}', fit: BoxFit.cover, errorBuilder: (ctx, err, stack) => _buildFallbackHero(context)),
                              Container(decoration: BoxDecoration(gradient: LinearGradient(colors: [Colors.black.withValues(alpha: 0.8), Colors.transparent], begin: Alignment.bottomLeft, end: Alignment.topRight))),
                              Padding(
                                padding: const EdgeInsets.all(16.0),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  mainAxisAlignment: MainAxisAlignment.end,
                                  children: [
                                    Container(
                                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                      decoration: BoxDecoration(color: wowinGreen, borderRadius: BorderRadius.circular(4)),
                                      child: const Text('WOWIN EXCLUSIVE', style: TextStyle(color: Colors.white, fontSize: 9, fontWeight: FontWeight.bold)),
                                    ),
                                    const SizedBox(height: 8),
                                    Text(hero['nama_event'] ?? 'Dapatkan Penawaran Spesial', style: const TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.bold, height: 1.2), maxLines: 2, overflow: TextOverflow.ellipsis),
                                    const SizedBox(height: 12),
                                    Container(
                                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
                                      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(8)),
                                      child: Row(
                                        mainAxisSize: MainAxisSize.min,
                                        children: const [
                                          Icon(Icons.chat, color: wowinGreen, size: 14),
                                          SizedBox(width: 6),
                                          Text('Hubungi Admin (WA)', style: TextStyle(color: wowinGreen, fontSize: 12, fontWeight: FontWeight.bold)),
                                        ],
                                      ),
                                    )
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

                // --- ICON MENU ---
                Padding(
                  padding: const EdgeInsets.fromLTRB(16, 24, 16, 8),
                  child: Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: _menuItems.map((item) {
                      return Expanded(
                        child: GestureDetector(
                          behavior: HitTestBehavior.opaque, // Agar seluruh area kolom bisa diklik
                          onTap: () {
                            if (item['label'] == 'Kategori') {
                              // Buka halaman Kategori Bergambar
                              Navigator.push(context, MaterialPageRoute(builder: (context) => const CategoryScreen()));
                            } else if (item['label'] == 'Promo') {
                              // Buka Halaman Khusus Promo (Screen Sendiri) yang Keren!
                              Navigator.push(context, MaterialPageRoute(
                                  builder: (context) => PromoScreen(bundlings: _bundlingList)
                              ));
                            } else if (item['label'] == 'Member VIP') {
                              // Arahkan ke ProfileScreen karena Kartu Digital VIP ada di sana
                              if (authState.isAuthenticated) {
                                Navigator.push(context, MaterialPageRoute(builder: (context) => const ProfileScreen()));
                              } else {
                                Navigator.push(context, MaterialPageRoute(builder: (context) => const LoginScreen()));
                              }
                            } else if (item['label'] == 'Point Rewards') {
                              // Buka halaman Rewards (Cek login dulu)
                              if (authState.isAuthenticated) {
                                Navigator.push(context, MaterialPageRoute(builder: (context) => const RewardScreen()));
                              } else {
                                Navigator.push(context, MaterialPageRoute(builder: (context) => const LoginScreen()));
                              }
                            }
                          },
                          child: Column(
                            children: [
                              // --- DESAIN KHUSUS MEMBER VIP ---
                              Container(
                                padding: const EdgeInsets.all(12),
                                decoration: item['label'] == 'Member VIP'
                                    ? BoxDecoration(
                                    gradient: const LinearGradient(
                                      colors: [Color(0xFF6B21A8), Color(0xFF9333EA)], // Gradasi Ungu Mewah
                                      begin: Alignment.topLeft, end: Alignment.bottomRight,
                                    ),
                                    shape: BoxShape.circle,
                                    boxShadow: [
                                      BoxShadow(color: Colors.purple.withValues(alpha: 0.4), blurRadius: 8, offset: const Offset(0, 3))
                                    ]
                                )
                                    : BoxDecoration(
                                    color: item['color'].withValues(alpha: 0.1),
                                    shape: BoxShape.circle
                                ),
                                child: Icon(
                                    item['icon'],
                                    color: item['label'] == 'Member VIP' ? Colors.white : item['color'],
                                    size: 28
                                ),
                              ),
                              const SizedBox(height: 8),
                              Text(
                                  item['label'],
                                  textAlign: TextAlign.center,
                                  style: TextStyle(
                                    fontSize: 11,
                                    height: 1.2,
                                    fontWeight: item['label'] == 'Member VIP' ? FontWeight.bold : FontWeight.normal,
                                    color: item['label'] == 'Member VIP' ? Colors.purple[800] : Colors.black87,
                                  )
                              ),
                            ],
                          ),
                        ),
                      );
                    }).toList(),
                  ),
                ),

                Container(
                  height: 150,
                  margin: const EdgeInsets.only(top: 8, bottom: 16),
                  child: _isLoadingBanners
                      ? const Center(child: CircularProgressIndicator(color: wowinGreen))
                      : _bundlingList.isEmpty
                      ? ListView(scrollDirection: Axis.horizontal, padding: const EdgeInsets.symmetric(horizontal: 16), children: [_buildFallbackBundling(context)])
                      : ListView.builder(
                    scrollDirection: Axis.horizontal,
                    padding: const EdgeInsets.symmetric(horizontal: 16),
                    itemCount: _bundlingList.length,
                    itemBuilder: (context, index) {
                      final bundling = _bundlingList[index];
                      // Bungkus dengan GestureDetector agar bisa diklik
                      return GestureDetector(
                        onTap: () {
                          Navigator.push(context, MaterialPageRoute(
                              builder: (context) => PromoDetailScreen(bundling: bundling)
                          ));
                        },
                        child: Container(
                          width: 300,
                          margin: const EdgeInsets.only(right: 12),
                          child: ClipRRect(
                            borderRadius: BorderRadius.circular(16),
                            child: Image.network('https://mywowin.com/storage/${bundling['barang_bundling']}', fit: BoxFit.cover, errorBuilder: (ctx, err, stack) => _buildFallbackBundling(context)),
                          ),
                        ),
                      );
                    },
                  ),
                ),

                SizedBox(
                  height: 35,
                  child: ListView.builder(
                    scrollDirection: Axis.horizontal,
                    padding: const EdgeInsets.symmetric(horizontal: 16),
                    itemCount: _categoryList.length,
                    itemBuilder: (context, index) {
                      final catName = _categoryList[index]['name'];
                      final isActive = _activeCategory == catName;

                      return GestureDetector(
                        onTap: () {
                          setState(() {
                            _activeCategory = catName;
                            if (_activeTab == 'Promo') {
                              _activeTab = 'Rekomendasi';
                            }
                          });
                        },
                        child: Container(
                          margin: const EdgeInsets.only(right: 8),
                          padding: const EdgeInsets.symmetric(horizontal: 16),
                          alignment: Alignment.center,
                          decoration: BoxDecoration(
                            color: isActive ? wowinGreen : Colors.white,
                            borderRadius: BorderRadius.circular(20),
                            border: Border.all(color: isActive ? wowinGreen : Colors.grey.shade300),
                            boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.02), blurRadius: 4)],
                          ),
                          child: Text(
                            catName,
                            style: TextStyle(
                                fontSize: 13,
                                fontWeight: FontWeight.bold,
                                color: isActive ? Colors.white : Colors.grey[700]
                            ),
                          ),
                        ),
                      );
                    },
                  ),
                ),

                const SizedBox(height: 16),
                const Divider(thickness: 6, color: Color(0xFFF5F5F5), height: 30),

                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  child: SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        _buildTabItem('Rekomendasi'),
                        const SizedBox(width: 20),
                        _buildTabItem('Terlaris'),
                        const SizedBox(width: 20),
                        _buildTabItem('Promo'),
                        const SizedBox(width: 20),

                        GestureDetector(
                          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => const AllProductsScreen())),
                          child: Container(
                            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                            decoration: BoxDecoration(color: wowinGreen.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(20), border: Border.all(color: wowinGreen.withValues(alpha: 0.3))),
                            child: Row(
                              children: const [
                                Text('Semua Produk', style: TextStyle(fontSize: 13, fontWeight: FontWeight.bold, color: wowinGreen)),
                                SizedBox(width: 4),
                                Icon(Icons.arrow_forward_ios, size: 12, color: wowinGreen),
                              ],
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),

                const SizedBox(height: 12),

                homepageGridData.isEmpty
                    ? const Padding(
                  padding: EdgeInsets.symmetric(vertical: 40),
                  child: Center(
                    child: Text(
                      'Kategori ini belum memiliki produk.',
                      style: TextStyle(color: Colors.grey, fontSize: 14),
                    ),
                  ),
                )
                    : GridView.builder(
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                      crossAxisCount: 2, crossAxisSpacing: 12, mainAxisSpacing: 12, childAspectRatio: 0.58),
                  itemCount: homepageGridData.length, // MENGGUNAKAN VARIABEL YANG SUDAH DILIMIT 10
                  itemBuilder: (context, index) {
                    if (isPromoTab) {
                      final bundling = homepageGridData[index];
                      return _buildBundlingCard(context, bundling);
                    } else {
                      final product = homepageGridData[index];
                      String imageUrl = 'https://via.placeholder.com/150';
                      if (product['images'] != null && product['images'].isNotEmpty) {
                        imageUrl = 'https://mywowin.com/storage/${product['images'][0]['image_url']}';
                      }
                      return _buildTokopediaStyleCard(context, product, imageUrl);
                    }
                  },
                ),
                const SizedBox(height: 30),
              ],
            ),
          );
        },
      ),

      bottomNavigationBar: BottomNavigationBar(
        type: BottomNavigationBarType.fixed,
        currentIndex: _selectedIndex,
        selectedItemColor: wowinGreen,
        unselectedItemColor: Colors.grey[500],
        selectedFontSize: 12,
        unselectedFontSize: 12,
        onTap: _onBottomNavTapped,
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Home'),
          BottomNavigationBarItem(icon: Icon(Icons.chat_bubble_outline), label: 'Live Chat'),
          BottomNavigationBarItem(icon: Icon(Icons.receipt_long), label: 'Pesanan'),
          BottomNavigationBarItem(icon: Icon(Icons.person_outline), label: 'Akun'),
        ],
      ),
    );
  }

  Widget _buildBundlingCard(BuildContext context, dynamic bundling) {
    String imageUrl = 'https://mywowin.com/storage/${bundling['barang_bundling']}';
    num price = num.tryParse(bundling['price']?.toString() ?? '0') ?? 0;
    num priceBefore = num.tryParse(bundling['price_before']?.toString() ?? '0') ?? 0;

    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: Colors.red.shade100),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 4, offset: const Offset(0, 2))],
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(8),
          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => PromoDetailScreen(bundling: bundling))),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: ClipRRect(
                  borderRadius: const BorderRadius.vertical(top: Radius.circular(8)),
                  child: Image.network(imageUrl, width: double.infinity, fit: BoxFit.cover, errorBuilder: (ctx, err, stack) => const Icon(Icons.image, color: Colors.grey, size: 40)),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(10.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                      decoration: BoxDecoration(color: Colors.red[50], borderRadius: BorderRadius.circular(4)),
                      child: Text('PROMO BUNDLING', style: TextStyle(color: Colors.red[800], fontSize: 8, fontWeight: FontWeight.bold)),
                    ),
                    const SizedBox(height: 6),
                    Text(bundling['nama_bundling'] ?? 'Tanpa Nama', maxLines: 2, overflow: TextOverflow.ellipsis, style: const TextStyle(fontSize: 13, height: 1.2, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 6),
                    if (priceBefore > 0 && priceBefore > price)
                      Text('Rp ${priceBefore.toStringAsFixed(0)}', style: const TextStyle(fontSize: 10, decoration: TextDecoration.lineThrough, color: Colors.grey)),
                    Text('Rp ${price.toStringAsFixed(0)}', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: Colors.red)),
                    const SizedBox(height: 12),
                    Material(
                      color: Colors.transparent,
                      child: Ink(
                        decoration: BoxDecoration(color: Colors.red, borderRadius: BorderRadius.circular(6)),
                        child: InkWell(
                          borderRadius: BorderRadius.circular(6),
                          splashColor: Colors.white.withValues(alpha: 0.4),
                          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => PromoDetailScreen(bundling: bundling))),
                          child: Container(width: double.infinity, padding: const EdgeInsets.symmetric(vertical: 8), alignment: Alignment.center, child: const Text('Lihat Promo', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12))),
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

  Widget _buildTokopediaStyleCard(BuildContext context, dynamic product, String imageUrl) {
    final String namaKategori = product['category']?['name'] ?? 'Produk Pilihan';

    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: Colors.grey.shade200),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 4, offset: const Offset(0, 2))],
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(8),
          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => ProductDetailScreen(product: product))),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: ClipRRect(
                  borderRadius: const BorderRadius.vertical(top: Radius.circular(8)),
                  child: Image.network(imageUrl, width: double.infinity, fit: BoxFit.cover, errorBuilder: (ctx, err, stack) => const Icon(Icons.image, color: Colors.grey, size: 40)),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(10.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                      decoration: BoxDecoration(color: Colors.orange[50], borderRadius: BorderRadius.circular(4)),
                      child: Text(namaKategori.toUpperCase(), style: TextStyle(color: Colors.orange[800], fontSize: 8, fontWeight: FontWeight.bold)),
                    ),
                    const SizedBox(height: 6),
                    Text(product['nama_produk'] ?? 'Tanpa Nama', maxLines: 2, overflow: TextOverflow.ellipsis, style: const TextStyle(fontSize: 13, height: 1.2)),
                    const SizedBox(height: 6),
                    Text('Rp ${product['harga_pcs'] ?? 0}', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                    const SizedBox(height: 12),
                    Material(
                      color: Colors.transparent,
                      child: Ink(
                        decoration: BoxDecoration(color: wowinGreen, borderRadius: BorderRadius.circular(6)),
                        child: InkWell(
                          borderRadius: BorderRadius.circular(6),
                          splashColor: Colors.white.withValues(alpha: 0.4),
                          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => ProductDetailScreen(product: product))),
                          child: Container(width: double.infinity, padding: const EdgeInsets.symmetric(vertical: 8), alignment: Alignment.center, child: const Text('Beli', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12))),
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

  Widget _buildFallbackHero(BuildContext context) {
    return Container(
      decoration: BoxDecoration(borderRadius: BorderRadius.circular(16), gradient: const LinearGradient(colors: [Color(0xFF006064), Color(0xFF009688)], begin: Alignment.topLeft, end: Alignment.bottomRight)),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(16),
          onTap: _openWhatsApp,
          child: Stack(
            children: [
              Positioned(right: -20, bottom: -20, child: Icon(Icons.storefront, size: 120, color: Colors.white.withValues(alpha: 0.15))),
              Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.end,
                  children: [
                    Container(padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4), decoration: BoxDecoration(color: Colors.white.withValues(alpha: 0.2), borderRadius: BorderRadius.circular(4)), child: const Text('MEMBER EXCLUSIVE', style: TextStyle(color: Colors.white, fontSize: 9, fontWeight: FontWeight.bold))),
                    const SizedBox(height: 10),
                    const Text('Buat Usaha Anda\nMakin Dikenal!', style: TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.bold, height: 1.2)),
                    const SizedBox(height: 12),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8), decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(8)),
                      child: Row(mainAxisSize: MainAxisSize.min, children: const [Icon(Icons.chat, color: Color(0xFF006064), size: 14), SizedBox(width: 6), Text('Hubungi Admin (WA)', style: TextStyle(color: Color(0xFF006064), fontSize: 12, fontWeight: FontWeight.bold))]),
                    )
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildFallbackBundling(BuildContext context) {
    return Container(
      width: 310,
      decoration: BoxDecoration(borderRadius: BorderRadius.circular(16), gradient: const LinearGradient(colors: [Color(0xFFB71C1C), Color(0xFFE53935)], begin: Alignment.topLeft, end: Alignment.bottomRight)),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(16),
          onTap: () => ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Membuka Halaman Promo Bundling...'))),
          child: Stack(
            children: [
              Positioned(right: -20, bottom: -20, child: Icon(Icons.local_shipping, size: 120, color: Colors.white.withValues(alpha: 0.15))),
              Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Container(padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4), decoration: BoxDecoration(color: Colors.white.withValues(alpha: 0.2), borderRadius: BorderRadius.circular(4)), child: const Text('PROMO SPESIAL', style: TextStyle(color: Colors.white, fontSize: 9, fontWeight: FontWeight.bold))),
                    const SizedBox(height: 10),
                    const Text('PAKET BUNDLING\nMURAH LEBAY', style: TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold, height: 1.2)),
                    const SizedBox(height: 4),
                    Text('Bebas Ongkir Sepuasnya', style: TextStyle(color: Colors.white.withValues(alpha: 0.85), fontSize: 11)),
                    const Spacer(),
                    Container(padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6), decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(6)), child: const Text('Lihat Promo', style: TextStyle(color: Color(0xFFB71C1C), fontSize: 11, fontWeight: FontWeight.bold)))
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}