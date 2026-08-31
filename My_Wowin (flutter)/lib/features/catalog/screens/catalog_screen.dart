import 'dart:async';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:url_launcher/url_launcher.dart';
import 'package:intl/intl.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/theme/wowin_theme.dart';
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
import '../../../core/services/cache_service.dart';
import '../../../core/widgets/wowin_cached_image.dart';
import '../../../core/widgets/offline_indicator.dart';

class CatalogScreen extends ConsumerStatefulWidget {
  const CatalogScreen({super.key});

  @override
  ConsumerState<CatalogScreen> createState() => _CatalogScreenState();
}

class _CatalogScreenState extends ConsumerState<CatalogScreen> {
  static const Color wowinGreen = Color(0xFF1B5E20);
  static const wowinGradient = LinearGradient(
    colors: [Color(0xFF0A4A1A), Color(0xFF2E7D32)],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  int _selectedIndex = 0;
  String _activeTab = 'Rekomendasi';
  String _activeCategory = 'Semua Produk';

  List<dynamic> _heroList = [];
  List<dynamic> _bundlingList = [];
  bool _isLoadingBanners = true;
  String _userPoints = '0';
  String _userName = 'Mitra Wowin';
  int _currentBannerIndex = 0;
  late PageController _bannerPageController;
  Timer? _bannerTimer;

  final NumberFormat _currencyFormat = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

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
    _bannerPageController = PageController();
    _startBannerTimer();
    _fetchBanners();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _checkDailyReward();
      ref.read(cartProvider.notifier).fetchCart();
      _fetchUserPoints();
    });
  }

  void _startBannerTimer() {
    _bannerTimer?.cancel();
    _bannerTimer = Timer.periodic(const Duration(seconds: 4), (_) {
      if (!mounted) return;
      if (_heroList.length > 1 && _bannerPageController.hasClients) {
        final nextPage = (_currentBannerIndex + 1) % _heroList.length;
        _bannerPageController.animateToPage(
          nextPage,
          duration: const Duration(milliseconds: 600),
          curve: Curves.easeInOutCubic,
        );
      }
    });
  }

  @override
  void dispose() {
    _bannerTimer?.cancel();
    _bannerPageController.dispose();
    super.dispose();
  }

  Future<void> _checkDailyReward() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');
    if (token == null) return;

    final String today = DateTime.now().toIso8601String().substring(0, 10);
    final String? lastClaimDate = prefs.getString('last_reward_claim_date');

    if (lastClaimDate != today) {
      _showRewardPopup();
    }
  }

  Future<void> _fetchBanners() async {
    // 1. Muat dari Cache Lokal terlebih dahulu agar instan
    final cachedHeroes = await CacheService.getHeroes();
    final cachedBundlings = await CacheService.getBundlings();
    final cachedCats = await CacheService.getCategories();

    if (mounted && (cachedHeroes != null || cachedBundlings != null || cachedCats != null)) {
      setState(() {
        if (cachedHeroes != null) _heroList = cachedHeroes;
        if (cachedBundlings != null) _bundlingList = cachedBundlings;
        if (cachedCats != null && cachedCats.isNotEmpty) {
          _categoryList = [{'name': 'Semua Produk'}, ...cachedCats];
        }
        _isLoadingBanners = false;
      });
    }

    // 2. Fetch live data dari server
    try {
      final heroRes = await http.get(Uri.parse('$baseUrl/heroes'), headers: {'Accept': 'application/json'}).timeout(const Duration(seconds: 6));
      final bundleRes = await http.get(Uri.parse('$baseUrl/bundlings'), headers: {'Accept': 'application/json'}).timeout(const Duration(seconds: 6));

      try {
        final catRes = await http.get(Uri.parse('$baseUrl/categories'), headers: {'Accept': 'application/json'}).timeout(const Duration(seconds: 6));
        if (catRes.statusCode == 200) {
          final catData = json.decode(catRes.body);
          if (catData['data'] != null && catData['data'].isNotEmpty) {
            final cats = catData['data'] as List<dynamic>;
            _categoryList = [{'name': 'Semua Produk'}, ...cats];
            await CacheService.saveCategories(cats);
          }
        }
      } catch (_) {}

      if (mounted) {
        setState(() {
          if (heroRes.statusCode == 200) {
            final data = json.decode(heroRes.body);
            final heroes = data['data'] ?? data ?? [];
            _heroList = heroes;
            CacheService.saveHeroes(heroes);
          }
          if (bundleRes.statusCode == 200) {
            final data = json.decode(bundleRes.body);
            final bundlings = data['data'] ?? data ?? [];
            _bundlingList = bundlings;
            CacheService.saveBundlings(bundlings);
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
    // 1. Muat profil offline dari cache jika ada
    final cachedProfile = await CacheService.getUserProfile();
    if (cachedProfile != null && mounted) {
      setState(() {
        _userPoints = cachedProfile['total_points']?.toString() ?? '0';
        _userName = cachedProfile['name'] ?? 'Mitra Wowin';
      });
    }

    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');
    if (token == null) return;

    try {
      final response = await http.get(
        Uri.parse('$baseUrl/profile'),
        headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'},
      ).timeout(const Duration(seconds: 6));

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['data'] != null) {
          await CacheService.saveUserProfile(data['data']);
        }
        if (mounted) {
          setState(() {
            _userPoints = data['data']['total_points']?.toString() ?? '0';
            _userName = data['data']['name'] ?? 'Mitra Wowin';
          });

          if (data['data']['has_claimed_daily_today'] == true) {
            final String today = DateTime.now().toIso8601String().substring(0, 10);
            prefs.setString('last_reward_claim_date', today);
          }
        }
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
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24)),
          elevation: 12,
          child: Container(
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24)),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Align(
                  alignment: Alignment.topRight,
                  child: GestureDetector(
                    onTap: () => Navigator.pop(context),
                    child: Container(
                      padding: const EdgeInsets.all(4),
                      decoration: BoxDecoration(color: Colors.grey.shade100, shape: BoxShape.circle),
                      child: Icon(Icons.close, color: Colors.grey[600], size: 20),
                    ),
                  ),
                ),
                Container(
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    gradient: WowinGradients.goldBadge,
                    shape: BoxShape.circle,
                    boxShadow: [
                      BoxShadow(
                        color: WowinColors.gold.withValues(alpha: 0.35),
                        blurRadius: 16,
                        offset: const Offset(0, 4),
                      )
                    ],
                  ),
                  child: const Icon(Icons.monetization_on, color: Colors.white, size: 50),
                ),
                const SizedBox(height: 20),
                const Text(
                  'Klaim Poin Harian Mitra!',
                  style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: WowinColors.textPrimary),
                  textAlign: TextAlign.center,
                ),
                const SizedBox(height: 10),
                Text(
                  'Selamat! Anda mendapatkan +100 koin loyalitas hari ini. Kumpulkan dan tukarkan dengan berbagai hadiah reward eksklusif dari Wowin Food! Poin direset setiap jam 12 malam.',
                  style: TextStyle(fontSize: 13.5, color: Colors.grey[600], height: 1.45),
                  textAlign: TextAlign.center,
                ),
                const SizedBox(height: 24),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: () async {
                      final prefs = await SharedPreferences.getInstance();
                      final token = prefs.getString('auth_token');
                      if (token == null) return;

                      try {
                        final response = await http.post(
                          Uri.parse('$baseUrl/claim-reward'),
                          headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer $token'
                          },
                        );

                        if (response.statusCode == 200) {
                          final String today = DateTime.now().toIso8601String().substring(0, 10);
                          await prefs.setString('last_reward_claim_date', today);
                          await _fetchUserPoints();

                          if (context.mounted) {
                            Navigator.pop(context);
                            final data = json.decode(response.body);
                            ScaffoldMessenger.of(context).showSnackBar(
                              SnackBar(
                                content: Text(data['message'] ?? 'Hore! +100 Koin harian berhasil diklaim!', style: const TextStyle(fontWeight: FontWeight.bold)),
                                backgroundColor: wowinGreen,
                                duration: const Duration(seconds: 3),
                              ),
                            );
                          }
                        } else {
                          final String today = DateTime.now().toIso8601String().substring(0, 10);
                          await prefs.setString('last_reward_claim_date', today);
                          if (context.mounted) {
                            Navigator.pop(context);
                            final data = json.decode(response.body);
                            ScaffoldMessenger.of(context).showSnackBar(
                              SnackBar(content: Text(data['message'] ?? 'Reward harian sudah diklaim untuk hari ini.')),
                            );
                          }
                        }
                      } catch (e) {
                        if (context.mounted) {
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(content: Text('Terjadi kesalahan jaringan.')),
                          );
                        }
                      }
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: wowinGreen,
                      padding: const EdgeInsets.symmetric(vertical: 15),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                      elevation: 2,
                    ),
                    child: const Text('Klaim +100 Poin Sekarang', style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Colors.white)),
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
    setState(() => _selectedIndex = index);
  }

  Future<void> _openWhatsApp([String? customMessage]) async {
    const String waNumber = '6281216301220';
    final String text = customMessage ?? 'Halo Admin Wowin Food, saya ingin bertanya seputar produk dan promo kemitraan di aplikasi.';
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
      try {
        await launchUrl(httpsUri, mode: LaunchMode.externalApplication);
      } catch (e) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('Tidak dapat membuka WhatsApp otomatis. Hubungi WA: 081216301220'),
              backgroundColor: Colors.orange,
            ),
          );
        }
      }
    }
  }

  void _showNotificationSheet() {
    final authState = ref.read(authProvider);
    if (!authState.isAuthenticated) {
      Navigator.push(context, MaterialPageRoute(builder: (context) => const LoginScreen()));
      return;
    }

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) {
        return Container(
          height: MediaQuery.of(context).size.height * 0.65,
          decoration: const BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
          ),
          child: Column(
            children: [
              Center(
                child: Container(
                  margin: const EdgeInsets.only(top: 12, bottom: 16),
                  width: 48,
                  height: 5,
                  decoration: BoxDecoration(color: Colors.grey.shade300, borderRadius: BorderRadius.circular(10)),
                ),
              ),
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 20),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text('Notifikasi Saya', style: TextStyle(fontSize: 16.5, fontWeight: FontWeight.bold, color: WowinColors.textPrimary)),
                    TextButton(
                      onPressed: () => Navigator.pop(context),
                      child: const Text('Tutup', style: TextStyle(color: wowinGreen, fontWeight: FontWeight.bold)),
                    )
                  ],
                ),
              ),
              const Divider(height: 1),
              Expanded(
                child: FutureBuilder<List<dynamic>>(
                  future: _fetchNotifications(),
                  builder: (context, snapshot) {
                    if (snapshot.connectionState == ConnectionState.waiting) {
                      return const Center(child: CircularProgressIndicator(color: wowinGreen));
                    }
                    if (snapshot.hasError || !snapshot.hasData || snapshot.data!.isEmpty) {
                      return Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.notifications_off_outlined, size: 50, color: Colors.grey[300]),
                            const SizedBox(height: 10),
                            Text('Belum ada notifikasi baru', style: TextStyle(color: Colors.grey[500], fontSize: 13)),
                          ],
                        ),
                      );
                    }

                    final notifs = snapshot.data!;
                    return ListView.builder(
                      padding: const EdgeInsets.all(16),
                      itemCount: notifs.length,
                      itemBuilder: (context, index) {
                        final notif = notifs[index];
                        return _buildNotificationItem(
                          icon: Icons.notifications_active_outlined,
                          color: wowinGreen,
                          title: notif['title'] ?? 'Info Pesanan',
                          subtitle: notif['message'] ?? 'Ada pembaruan status pada akun Anda.',
                          time: notif['created_at'] != null ? notif['created_at'].toString().substring(0, 10) : 'Baru saja',
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

  Future<List<dynamic>> _fetchNotifications() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');
    if (token == null) return [];

    try {
      final response = await http.get(
        Uri.parse('$baseUrl/notifications'),
        headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'},
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
            child: Icon(icon, color: color, size: 22),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13.5, color: WowinColors.textPrimary)),
                const SizedBox(height: 4),
                Text(subtitle, style: TextStyle(fontSize: 12, color: Colors.grey.shade700, height: 1.35)),
                const SizedBox(height: 6),
                Text(time, style: TextStyle(fontSize: 10.5, color: Colors.grey.shade500)),
              ],
            ),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final authState = ref.watch(authProvider);

    return PopScope(
      canPop: _selectedIndex == 0,
      onPopInvokedWithResult: (didPop, result) {
        if (!didPop && _selectedIndex != 0) {
          setState(() => _selectedIndex = 0);
        }
      },
      child: Scaffold(
        backgroundColor: WowinColors.background,
        body: IndexedStack(
          index: _selectedIndex,
          children: [
            // Tab 0: Katalog / Beranda Utama
            _buildCatalogBody(authState),

            // Tab 1: Live Chat
            authState.isAuthenticated
                ? const LiveChatScreen(showBackButton: false)
                : _buildLoginRequiredView('Live Chat Mitra', Icons.chat_bubble_outline_rounded),

            // Tab 2: Riwayat Pesanan
            authState.isAuthenticated
                ? const HistoryScreen(showBackButton: false)
                : _buildLoginRequiredView('Riwayat Pesanan', Icons.receipt_long_rounded),

            // Tab 3: Akun & Membership
            authState.isAuthenticated
                ? const ProfileScreen(showBackButton: false)
                : _buildLoginRequiredView('Profil Mitra', Icons.person_outline_rounded),
          ],
        ),

      // BOTTOM NAVIGATION BAR (PERMANEN, TIDAK AKAN HILANG SAAT GANTI TAB)
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.06),
              blurRadius: 12,
              offset: const Offset(0, -3),
            )
          ],
        ),
        child: BottomNavigationBar(
          type: BottomNavigationBarType.fixed,
          backgroundColor: Colors.white,
          elevation: 0,
          currentIndex: _selectedIndex,
          selectedItemColor: wowinGreen,
          unselectedItemColor: Colors.grey[400],
          selectedFontSize: 11.5,
          unselectedFontSize: 11.5,
          selectedLabelStyle: const TextStyle(fontWeight: FontWeight.bold),
          onTap: _onBottomNavTapped,
          items: const [
            BottomNavigationBarItem(icon: Icon(Icons.storefront_rounded), label: 'Beranda'),
            BottomNavigationBarItem(icon: Icon(Icons.chat_bubble_outline_rounded), label: 'Live Chat'),
            BottomNavigationBarItem(icon: Icon(Icons.receipt_long_rounded), label: 'Pesanan'),
            BottomNavigationBarItem(icon: Icon(Icons.person_outline_rounded), label: 'Akun Mitra'),
          ],
        ),
      ),
    ),
  );
}

  Widget _buildLoginRequiredView(String title, IconData icon) {
    return Scaffold(
      backgroundColor: WowinColors.background,
      appBar: WowinAppBar.standard(title: title),
      body: Center(
        child: Padding(
          padding: const EdgeInsets.all(32.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: wowinGreen.withValues(alpha: 0.08),
                  shape: BoxShape.circle,
                ),
                child: Icon(icon, size: 56, color: wowinGreen),
              ),
              const SizedBox(height: 20),
              Text(
                'Akses $title',
                style: const TextStyle(fontSize: 16.5, fontWeight: FontWeight.bold, color: WowinColors.textPrimary),
              ),
              const SizedBox(height: 8),
              Text(
                'Silakan masuk dengan akun mitra Wowin Food Anda untuk mengakses menu ini.',
                textAlign: TextAlign.center,
                style: TextStyle(fontSize: 13, color: Colors.grey[600], height: 1.4),
              ),
              const SizedBox(height: 28),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (context) => const LoginScreen())),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: wowinGreen,
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                  child: const Text('Masuk ke Akun', style: TextStyle(fontSize: 14.5, fontWeight: FontWeight.bold, color: Colors.white)),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildCatalogBody(AuthState authState) {
    final catalogState = ref.watch(catalogProvider);

    return Scaffold(
      backgroundColor: WowinColors.background,
      appBar: AppBar(
        elevation: 0,
        scrolledUnderElevation: 0,
        titleSpacing: 16,
        backgroundColor: WowinColors.primaryDark,
        flexibleSpace: Container(
          decoration: const BoxDecoration(
            gradient: WowinGradients.royalEmerald,
          ),
        ),
        title: Row(
          children: [
            Expanded(
              child: GestureDetector(
                onTap: () {
                  Navigator.push(context, MaterialPageRoute(builder: (context) => const AllProductsScreen()));
                },
                child: Container(
                  height: 40,
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(12),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withValues(alpha: 0.06),
                        blurRadius: 6,
                        offset: const Offset(0, 2),
                      )
                    ],
                  ),
                  child: Row(
                    children: [
                      const SizedBox(width: 12),
                      const Icon(Icons.search, color: wowinGreen, size: 18),
                      const SizedBox(width: 8),
                      Text('Cari produk Wowin Food...', style: TextStyle(color: Colors.grey[500], fontSize: 13)),
                    ],
                  ),
                ),
              ),
            ),
            const SizedBox(width: 12),
            GestureDetector(
              onTap: () {
                Navigator.push(context, MaterialPageRoute(builder: (context) => authState.isAuthenticated ? const CartScreen() : const LoginScreen()));
              },
              child: Badge(
                isLabelVisible: ref.watch(cartProvider).items.isNotEmpty,
                label: Text(ref.watch(cartProvider).items.length.toString(), style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold)),
                backgroundColor: WowinColors.gold,
                child: const Icon(Icons.shopping_cart_outlined, color: Colors.white, size: 22),
              ),
            ),
            const SizedBox(width: 12),
            GestureDetector(
              onTap: _showNotificationSheet,
              child: const Icon(Icons.notifications_none_rounded, color: Colors.white, size: 22),
            ),
          ],
        ),
      ),

      body: Column(
        children: [
          const OfflineBanner(),
          Expanded(
            child: catalogState.when(
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

                final homepageGridData = gridData.take(10).toList();

                return SingleChildScrollView(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // 1. EXECUTIVE VIP MEMBER STATUS CARD
                      _buildExecutiveMemberCard(authState),

                      // 2. HERO BANNER CAROUSEL (INTERACTIVE PRO MAX)
                      Container(
                        height: 200,
                        width: double.infinity,
                        margin: const EdgeInsets.fromLTRB(16, 14, 16, 8),
                        child: _isLoadingBanners
                            ? const Center(child: CircularProgressIndicator(color: wowinGreen))
                            : _heroList.isEmpty
                            ? _buildFallbackHero(context)
                            : Stack(
                          children: [
                            PageView.builder(
                              controller: _bannerPageController,
                              itemCount: _heroList.length,
                              onPageChanged: (idx) => setState(() => _currentBannerIndex = idx),
                              itemBuilder: (context, index) {
                                final hero = _heroList[index];
                                final String eventName = hero['nama_event'] ?? 'Dapatkan Penawaran Spesial Wowin';
                                return Container(
                                  margin: const EdgeInsets.symmetric(horizontal: 2),
                                  decoration: BoxDecoration(
                                    borderRadius: BorderRadius.circular(20),
                                    boxShadow: [
                                      BoxShadow(
                                        color: Colors.black.withValues(alpha: 0.12),
                                        blurRadius: 14,
                                        offset: const Offset(0, 5),
                                      )
                                    ],
                                  ),
                                  child: ClipRRect(
                                    borderRadius: BorderRadius.circular(20),
                                    child: Stack(
                                      fit: StackFit.expand,
                                      children: [
                                        // Background Image
                                        WowinCachedImage(
                                          imageUrl: 'https://mywowin.com/storage/${hero['gambar_hero']}',
                                          fit: BoxFit.cover,
                                          errorWidget: _buildFallbackHero(context),
                                        ),

                                        // Dual-layer Gradient Overlay for readability
                                        Container(
                                          decoration: BoxDecoration(
                                            gradient: LinearGradient(
                                              colors: [
                                                Colors.black.withValues(alpha: 0.88),
                                                Colors.black.withValues(alpha: 0.45),
                                                Colors.transparent,
                                              ],
                                              stops: const [0.0, 0.48, 1.0],
                                              begin: Alignment.bottomLeft,
                                              end: Alignment.topRight,
                                            ),
                                          ),
                                        ),

                                        // Top Right Glow Accent
                                        Positioned(
                                          top: -25,
                                          right: -25,
                                          child: Container(
                                            width: 110,
                                            height: 110,
                                            decoration: BoxDecoration(
                                              shape: BoxShape.circle,
                                              color: Colors.white.withValues(alpha: 0.08),
                                            ),
                                          ),
                                        ),

                                        // Tap surface with InkWell for tactile feedback
                                        Material(
                                          color: Colors.transparent,
                                          child: InkWell(
                                            borderRadius: BorderRadius.circular(20),
                                            onTap: () {
                                              HapticFeedback.lightImpact();
                                              _openWhatsApp('Halo Admin Wowin Food, saya ingin bertanya seputar promo *$eventName*');
                                            },
                                            child: Padding(
                                              padding: const EdgeInsets.all(16.0),
                                              child: Column(
                                                crossAxisAlignment: CrossAxisAlignment.start,
                                                mainAxisAlignment: MainAxisAlignment.end,
                                                children: [
                                                  // Badge
                                                  Container(
                                                    padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 4),
                                                    decoration: BoxDecoration(
                                                      gradient: const LinearGradient(
                                                        colors: [Color(0xFF2E7D32), Color(0xFF1B5E20)],
                                                      ),
                                                      borderRadius: BorderRadius.circular(8),
                                                      border: Border.all(color: Colors.white.withValues(alpha: 0.35), width: 1),
                                                      boxShadow: [
                                                        BoxShadow(
                                                          color: Colors.black.withValues(alpha: 0.2),
                                                          blurRadius: 4,
                                                          offset: const Offset(0, 2),
                                                        ),
                                                      ],
                                                    ),
                                                    child: const Row(
                                                      mainAxisSize: MainAxisSize.min,
                                                      children: [
                                                        Icon(Icons.stars_rounded, color: Colors.amberAccent, size: 12),
                                                        SizedBox(width: 4),
                                                        Text(
                                                          'PROMO RESMI',
                                                          style: TextStyle(
                                                            color: Colors.white,
                                                            fontSize: 9.5,
                                                            fontWeight: FontWeight.w900,
                                                            letterSpacing: 0.6,
                                                          ),
                                                        ),
                                                      ],
                                                    ),
                                                  ),
                                                  const SizedBox(height: 7),

                                                  // Event Name
                                                  Text(
                                                    eventName,
                                                    style: const TextStyle(
                                                      color: Colors.white,
                                                      fontSize: 17,
                                                      fontWeight: FontWeight.w800,
                                                      height: 1.25,
                                                      shadows: [
                                                        Shadow(
                                                          color: Colors.black54,
                                                          blurRadius: 6,
                                                          offset: Offset(0, 2),
                                                        ),
                                                      ],
                                                    ),
                                                    maxLines: 2,
                                                    overflow: TextOverflow.ellipsis,
                                                  ),
                                                  const SizedBox(height: 10),

                                                  // Interactive CTA Button (Hubungi Admin via WA)
                                                  Container(
                                                    padding: const EdgeInsets.symmetric(horizontal: 13, vertical: 7),
                                                    decoration: BoxDecoration(
                                                      color: Colors.white,
                                                      borderRadius: BorderRadius.circular(20),
                                                      boxShadow: [
                                                        BoxShadow(
                                                          color: const Color(0xFF25D366).withValues(alpha: 0.35),
                                                          blurRadius: 8,
                                                          offset: const Offset(0, 3),
                                                        ),
                                                      ],
                                                    ),
                                                    child: const Row(
                                                      mainAxisSize: MainAxisSize.min,
                                                      children: [
                                                        Icon(Icons.chat_rounded, color: Color(0xFF25D366), size: 14),
                                                        SizedBox(width: 6),
                                                        Text(
                                                          'Hubungi Admin (WA)',
                                                          style: TextStyle(
                                                            color: Color(0xFF0F5132),
                                                            fontSize: 11.5,
                                                            fontWeight: FontWeight.w800,
                                                          ),
                                                        ),
                                                        SizedBox(width: 5),
                                                        Icon(Icons.arrow_forward_ios_rounded, color: Color(0xFF0F5132), size: 10),
                                                      ],
                                                    ),
                                                  ),
                                                ],
                                              ),
                                            ),
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                );
                              },
                            ),

                            // Interactive Carousel Page Indicator Pills
                            if (_heroList.length > 1)
                              Positioned(
                                bottom: 12,
                                right: 16,
                                child: Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                  decoration: BoxDecoration(
                                    color: Colors.black.withValues(alpha: 0.45),
                                    borderRadius: BorderRadius.circular(12),
                                  ),
                                  child: Row(
                                    mainAxisSize: MainAxisSize.min,
                                    children: List.generate(_heroList.length, (idx) {
                                      final bool isSelected = _currentBannerIndex == idx;
                                      return GestureDetector(
                                        onTap: () {
                                          _bannerPageController.animateToPage(
                                            idx,
                                            duration: const Duration(milliseconds: 400),
                                            curve: Curves.easeInOut,
                                          );
                                        },
                                        child: AnimatedContainer(
                                          duration: const Duration(milliseconds: 300),
                                          margin: const EdgeInsets.symmetric(horizontal: 2.5),
                                          width: isSelected ? 18 : 6,
                                          height: 6,
                                          decoration: BoxDecoration(
                                            color: isSelected ? const Color(0xFF25D366) : Colors.white.withValues(alpha: 0.6),
                                            borderRadius: BorderRadius.circular(3),
                                          ),
                                        ),
                                      );
                                    }),
                                  ),
                                ),
                              ),
                          ],
                        ),
                      ),

                // 3. EXECUTIVE QUICK ACTION SQUIRCLE TILES
                Padding(
                  padding: const EdgeInsets.fromLTRB(16, 12, 16, 12),
                  child: Row(
                    children: [
                      _buildExecutiveActionTile(
                        title: 'Katalog',
                        subtitle: 'Kategori',
                        icon: Icons.grid_view_rounded,
                        primaryColor: const Color(0xFF0F766E),
                        bgGradient: const [Color(0xFFE6FFFA), Color(0xFFCCFBF1)],
                        onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => const CategoryScreen())),
                      ),
                      const SizedBox(width: 10),
                      _buildExecutiveActionTile(
                        title: 'Promo',
                        subtitle: 'Super Deal',
                        icon: Icons.local_fire_department_rounded,
                        primaryColor: const Color(0xFFE11D48),
                        bgGradient: const [Color(0xFFFFF1F2), Color(0xFFFFE4E6)],
                        onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => PromoScreen(bundlings: _bundlingList))),
                      ),
                      const SizedBox(width: 10),
                      _buildExecutiveActionTile(
                        title: 'Mitra VIP',
                        subtitle: 'Akses Khusus',
                        icon: Icons.workspace_premium_rounded,
                        primaryColor: const Color(0xFF7C3AED),
                        bgGradient: const [Color(0xFFF5F3FF), Color(0xFFEDE9FE)],
                        onTap: () {
                          if (authState.isAuthenticated) {
                            setState(() => _selectedIndex = 3);
                          } else {
                            Navigator.push(context, MaterialPageRoute(builder: (context) => const LoginScreen()));
                          }
                        },
                      ),
                      const SizedBox(width: 10),
                      _buildExecutiveActionTile(
                        title: 'Rewards',
                        subtitle: 'Tukar Poin',
                        icon: Icons.stars_rounded,
                        primaryColor: const Color(0xFFD97706),
                        bgGradient: const [Color(0xFFFFFBEB), Color(0xFFFEF3C7)],
                        onTap: () {
                          if (authState.isAuthenticated) {
                            Navigator.push(context, MaterialPageRoute(builder: (context) => const RewardScreen()));
                          } else {
                            Navigator.push(context, MaterialPageRoute(builder: (context) => const LoginScreen()));
                          }
                        },
                      ),
                    ],
                  ),
                ),

                // 4. PROMO BUNDLING HORIZONTAL STRIP
                if (_bundlingList.isNotEmpty) ...[
                  Padding(
                    padding: const EdgeInsets.fromLTRB(16, 8, 16, 10),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Row(
                          children: [
                            Icon(Icons.local_offer, color: WowinColors.promoRed, size: 18),
                            SizedBox(width: 6),
                            Text('Paket Bundling Spesial', style: TextStyle(fontSize: 15.5, fontWeight: FontWeight.bold, color: WowinColors.textPrimary)),
                          ],
                        ),
                        InkWell(
                          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => PromoScreen(bundlings: _bundlingList))),
                          child: const Row(
                            children: [
                              Text('Lihat Semua', style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: wowinGreen)),
                              SizedBox(width: 2),
                              Icon(Icons.arrow_forward_ios, size: 10, color: wowinGreen),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                  SizedBox(
                    height: 140,
                    child: ListView.builder(
                      scrollDirection: Axis.horizontal,
                      padding: const EdgeInsets.symmetric(horizontal: 16),
                      itemCount: _bundlingList.length,
                      itemBuilder: (context, index) {
                        final bundling = _bundlingList[index];
                        return GestureDetector(
                          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => PromoDetailScreen(bundling: bundling))),
                          child: Container(
                            width: 260,
                            margin: const EdgeInsets.only(right: 12),
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(16),
                              border: Border.all(color: WowinColors.promoRedSoft),
                              boxShadow: [
                                BoxShadow(
                                  color: Colors.black.withValues(alpha: 0.04),
                                  blurRadius: 10,
                                  offset: const Offset(0, 4),
                                )
                              ],
                            ),
                            child: ClipRRect(
                              borderRadius: BorderRadius.circular(16),
                              child: WowinCachedImage(
                                imageUrl: 'https://mywowin.com/storage/${bundling['barang_bundling']}',
                                fit: BoxFit.cover,
                                errorWidget: _buildFallbackBundling(context),
                              ),
                            ),
                          ),
                        );
                      },
                    ),
                  ),
                ],

                const SizedBox(height: 16),

                // 5. CATEGORY FILTER PILLS
                SizedBox(
                  height: 38,
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
                            if (_activeTab == 'Promo') _activeTab = 'Rekomendasi';
                          });
                        },
                        child: AnimatedContainer(
                          duration: const Duration(milliseconds: 200),
                          margin: const EdgeInsets.only(right: 8),
                          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                          alignment: Alignment.center,
                          decoration: BoxDecoration(
                            gradient: isActive ? wowinGradient : null,
                            color: isActive ? null : Colors.white,
                            borderRadius: BorderRadius.circular(20),
                            border: Border.all(color: isActive ? Colors.transparent : Colors.grey.shade300),
                            boxShadow: [
                              if (isActive)
                                BoxShadow(
                                  color: wowinGreen.withValues(alpha: 0.25),
                                  blurRadius: 8,
                                  offset: const Offset(0, 2),
                                )
                            ],
                          ),
                          child: Text(
                            catName,
                            style: TextStyle(
                              fontSize: 12.5,
                              fontWeight: isActive ? FontWeight.bold : FontWeight.w500,
                              color: isActive ? Colors.white : WowinColors.textPrimary,
                            ),
                          ),
                        ),
                      );
                    },
                  ),
                ),

                const SizedBox(height: 16),

                // 6. CLEAN SECTION HEADER: "Katalog Produk" + "Lihat Semua >"
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text(
                        'Katalog Produk',
                        style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: WowinColors.textPrimary),
                      ),
                      InkWell(
                        onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => const AllProductsScreen())),
                        child: const Row(
                          children: [
                            Text('Lihat Semua', style: TextStyle(fontSize: 12.5, fontWeight: FontWeight.bold, color: wowinGreen)),
                            SizedBox(width: 4),
                            Icon(Icons.arrow_forward_ios, size: 11, color: wowinGreen),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),

                const SizedBox(height: 10),

                // SUB-TABS: Rekomendasi | Terlaris | Promo
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  child: Row(
                    children: [
                      _buildTabItem('Rekomendasi'),
                      const SizedBox(width: 20),
                      _buildTabItem('Terlaris'),
                      const SizedBox(width: 20),
                      _buildTabItem('Promo'),
                    ],
                  ),
                ),

                const SizedBox(height: 14),

                homepageGridData.isEmpty
                    ? const Padding(
                  padding: EdgeInsets.symmetric(vertical: 40),
                  child: Center(
                    child: Text('Kategori ini belum memiliki produk.', style: TextStyle(color: Colors.grey, fontSize: 14)),
                  ),
                )
                    : GridView.builder(
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 2,
                    crossAxisSpacing: 12,
                    mainAxisSpacing: 12,
                    childAspectRatio: isPromoTab ? 0.53 : 0.58,
                  ),
                  itemCount: homepageGridData.length,
                  itemBuilder: (context, index) {
                    if (isPromoTab) {
                      final bundling = homepageGridData[index];
                      return _buildBundlingCard(context, bundling);
                    } else {
                      final product = homepageGridData[index];
                      String imageUrl = '';
                      if (product['images'] != null && product['images'] is List && (product['images'] as List).isNotEmpty) {
                        final imgPath = product['images'][0]['image_url']?.toString() ?? '';
                        if (imgPath.isNotEmpty) {
                          imageUrl = imgPath.startsWith('http') ? imgPath : 'https://mywowin.com/storage/$imgPath';
                        }
                      }
                      return _buildProductCard(context, product, imageUrl);
                    }
                  },
                ),
                const SizedBox(height: 30),
              ],
            ),
          );
        },
      ),
    ),
  ],
),
);
  }

  Widget _buildExecutiveMemberCard(AuthState authState) {
    return Container(
      margin: const EdgeInsets.fromLTRB(16, 14, 16, 0),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: Colors.grey.shade200),
        boxShadow: [
          BoxShadow(
            color: wowinGreen.withValues(alpha: 0.06),
            blurRadius: 14,
            offset: const Offset(0, 5),
          )
        ],
      ),
      child: Padding(
        padding: const EdgeInsets.all(14.0),
        child: Row(
          children: [
            Container(
              width: 44,
              height: 44,
              decoration: BoxDecoration(
                gradient: wowinGradient,
                shape: BoxShape.circle,
                boxShadow: [
                  BoxShadow(
                    color: wowinGreen.withValues(alpha: 0.3),
                    blurRadius: 8,
                    offset: const Offset(0, 2),
                  )
                ],
              ),
              child: const Icon(Icons.person_rounded, color: Colors.white, size: 24),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: InkWell(
                onTap: () {
                  if (authState.isAuthenticated) {
                    setState(() => _selectedIndex = 3);
                  } else {
                    Navigator.push(context, MaterialPageRoute(builder: (context) => const LoginScreen()));
                  }
                },
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      authState.isAuthenticated ? _userName : 'Selamat Datang!',
                      style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13.5, color: WowinColors.textPrimary),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 2),
                    Row(
                      children: [
                        Icon(
                          authState.isAuthenticated ? Icons.verified : Icons.lock_outline,
                          size: 13,
                          color: authState.isAuthenticated ? wowinGreen : Colors.grey,
                        ),
                        const SizedBox(width: 4),
                        Text(
                          authState.isAuthenticated ? 'Mitra Terverifikasi' : 'Ketuk untuk Masuk',
                          style: TextStyle(
                            fontSize: 11,
                            fontWeight: FontWeight.w600,
                            color: authState.isAuthenticated ? wowinGreen : Colors.grey[600],
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
            InkWell(
              onTap: () {
                if (authState.isAuthenticated) {
                  Navigator.push(context, MaterialPageRoute(builder: (context) => const RewardScreen()));
                } else {
                  Navigator.push(context, MaterialPageRoute(builder: (context) => const LoginScreen()));
                }
              },
              borderRadius: BorderRadius.circular(12),
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    colors: [Color(0xFFFFFBEB), Color(0xFFFEF3C7)],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: WowinColors.gold.withValues(alpha: 0.3)),
                ),
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Icon(Icons.monetization_on, color: WowinColors.gold, size: 18),
                    const SizedBox(width: 6),
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('POIN', style: TextStyle(fontSize: 9, fontWeight: FontWeight.w900, color: WowinColors.goldDark, letterSpacing: 0.5)),
                        Text(
                          authState.isAuthenticated ? _userPoints : '0',
                          style: const TextStyle(fontSize: 13, fontWeight: FontWeight.bold, color: WowinColors.goldDark),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildExecutiveActionTile({
    required String title,
    required String subtitle,
    required IconData icon,
    required Color primaryColor,
    required List<Color> bgGradient,
    required VoidCallback onTap,
  }) {
    return Expanded(
      child: GestureDetector(
        onTap: onTap,
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 8),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: Colors.grey.shade200),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withValues(alpha: 0.03),
                blurRadius: 8,
                offset: const Offset(0, 3),
              )
            ],
          ),
          child: Column(
            children: [
              Container(
                width: 42,
                height: 42,
                decoration: BoxDecoration(
                  gradient: LinearGradient(colors: bgGradient, begin: Alignment.topLeft, end: Alignment.bottomRight),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Icon(icon, color: primaryColor, size: 22),
              ),
              const SizedBox(height: 8),
              Text(
                title,
                textAlign: TextAlign.center,
                style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 11.5, color: WowinColors.textPrimary),
              ),
              const SizedBox(height: 2),
              Text(
                subtitle,
                textAlign: TextAlign.center,
                style: TextStyle(fontSize: 9.5, color: Colors.grey[500]),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildTabItem(String title) {
    bool isActive = _activeTab == title;
    return GestureDetector(
      onTap: () {
        setState(() {
          _activeTab = title;
          if (title == 'Promo') _activeCategory = 'Semua Produk';
        });
      },
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: TextStyle(
              fontSize: isActive ? 14.5 : 13.5,
              fontWeight: isActive ? FontWeight.bold : FontWeight.w500,
              color: isActive ? wowinGreen : Colors.grey[400],
            ),
          ),
          const SizedBox(height: 4),
          AnimatedContainer(
            duration: const Duration(milliseconds: 250),
            height: 3,
            width: isActive ? 28 : 0,
            decoration: BoxDecoration(
              color: wowinGreen,
              borderRadius: BorderRadius.circular(2),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildProductCard(BuildContext context, dynamic product, String imageUrl) {
    final String namaKategori = product['category']?['name'] ?? 'Produk';
    final num hargaPcs = num.tryParse(product['harga_pcs']?.toString() ?? '0') ?? 0;

    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: Colors.grey.shade200),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.03),
            blurRadius: 8,
            offset: const Offset(0, 3),
          )
        ],
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(16),
          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => ProductDetailScreen(product: product))),
          child: ClipRRect(
            borderRadius: BorderRadius.circular(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // --- GAMBAR PRODUK RATIO 1:1.15 ---
                AspectRatio(
                  aspectRatio: 1.15,
                  child: Stack(
                    fit: StackFit.expand,
                    children: [
                      Container(
                        color: const Color(0xFFFAFAFA),
                        padding: const EdgeInsets.all(8),
                        child: WowinCachedImage(
                          imageUrl: imageUrl,
                          fit: BoxFit.contain,
                          errorWidget: Container(
                            color: Colors.grey.shade100,
                            child: const Icon(Icons.image, color: Colors.grey, size: 36),
                          ),
                        ),
                      ),
                      Positioned(
                        top: 6,
                        left: 6,
                        child: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                          decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.95),
                            borderRadius: BorderRadius.circular(5),
                            border: Border.all(color: Colors.grey.shade200),
                          ),
                          child: Text(
                            namaKategori.toUpperCase(),
                            style: const TextStyle(color: wowinGreen, fontSize: 8, fontWeight: FontWeight.w900),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
                Expanded(
                  child: Padding(
                    padding: const EdgeInsets.fromLTRB(10, 8, 10, 10),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Text(
                              product['nama_produk'] ?? 'Produk Wowin',
                              maxLines: 2,
                              overflow: TextOverflow.ellipsis,
                              style: const TextStyle(fontSize: 12.5, height: 1.2, fontWeight: FontWeight.bold, color: WowinColors.textPrimary),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              _currencyFormat.format(hargaPcs),
                              style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 13.5, color: wowinGreen),
                            ),
                          ],
                        ),
                        Container(
                          width: double.infinity,
                          padding: const EdgeInsets.symmetric(vertical: 6),
                          decoration: BoxDecoration(
                            gradient: wowinGradient,
                            borderRadius: BorderRadius.circular(8),
                          ),
                          alignment: Alignment.center,
                          child: const Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(Icons.add_shopping_cart, color: Colors.white, size: 13),
                              SizedBox(width: 4),
                              Text('Beli', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 11.5)),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
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
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: WowinColors.promoRedSoft),
        boxShadow: [
          BoxShadow(
            color: WowinColors.promoRed.withValues(alpha: 0.06),
            blurRadius: 8,
            offset: const Offset(0, 3),
          )
        ],
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(16),
          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => PromoDetailScreen(bundling: bundling))),
          child: ClipRRect(
            borderRadius: BorderRadius.circular(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // --- GAMBAR BUNDLING + BADGE OVERLAY ---
                AspectRatio(
                  aspectRatio: 1.15,
                  child: Stack(
                    fit: StackFit.expand,
                    children: [
                      Container(
                        color: const Color(0xFFFAFAFA),
                        padding: const EdgeInsets.all(8),
                        child: WowinCachedImage(
                          imageUrl: imageUrl,
                          fit: BoxFit.contain,
                          errorWidget: Container(
                            color: Colors.grey.shade100,
                            child: const Icon(Icons.image, color: Colors.grey, size: 36),
                          ),
                        ),
                      ),
                      Positioned(
                        top: 6,
                        left: 6,
                        child: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2.5),
                          decoration: BoxDecoration(
                            color: WowinColors.promoRed,
                            borderRadius: BorderRadius.circular(5),
                            boxShadow: [
                              BoxShadow(
                                color: WowinColors.promoRed.withValues(alpha: 0.3),
                                blurRadius: 4,
                                offset: const Offset(0, 2),
                              ),
                            ],
                          ),
                          child: const Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Icon(Icons.local_fire_department, color: Colors.white, size: 10),
                              SizedBox(width: 2),
                              Text(
                                'PROMO BUNDLE',
                                style: TextStyle(color: Colors.white, fontSize: 8, fontWeight: FontWeight.w900, letterSpacing: 0.3),
                              ),
                            ],
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
                Expanded(
                  child: Padding(
                    padding: const EdgeInsets.fromLTRB(10, 8, 10, 10),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Text(
                              bundling['nama_bundling'] ?? 'Promo Menarik',
                              maxLines: 2,
                              overflow: TextOverflow.ellipsis,
                              style: const TextStyle(fontSize: 12.5, height: 1.2, fontWeight: FontWeight.bold, color: WowinColors.textPrimary),
                            ),
                            const SizedBox(height: 3),
                            if (priceBefore > 0 && priceBefore > price)
                              Text(
                                _currencyFormat.format(priceBefore),
                                style: const TextStyle(fontSize: 9.5, decoration: TextDecoration.lineThrough, color: Colors.grey),
                              ),
                            Text(
                              _currencyFormat.format(price),
                              style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 13.5, color: WowinColors.promoRed),
                            ),
                          ],
                        ),
                        Container(
                          width: double.infinity,
                          padding: const EdgeInsets.symmetric(vertical: 6),
                          decoration: BoxDecoration(
                            gradient: WowinGradients.superDeal,
                            borderRadius: BorderRadius.circular(8),
                          ),
                          alignment: Alignment.center,
                          child: const Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(Icons.percent_rounded, color: Colors.white, size: 12),
                              SizedBox(width: 4),
                              Text('Lihat Promo', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 11.5)),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildFallbackHero(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(20),
        gradient: const LinearGradient(
          colors: [Color(0xFF073A14), Color(0xFF1B5E20), Color(0xFF2E7D32)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        boxShadow: [
          BoxShadow(
            color: wowinGreen.withValues(alpha: 0.25),
            blurRadius: 14,
            offset: const Offset(0, 5),
          ),
        ],
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(20),
          onTap: () {
            HapticFeedback.lightImpact();
            _openWhatsApp('Halo Admin Wowin Food, saya ingin informasi Kemitraan Distributor Resmi Wowin.');
          },
          child: Stack(
            children: [
              // Ambient Decorative Vector & Circles
              Positioned(
                right: -30,
                bottom: -30,
                child: Icon(Icons.storefront_rounded, size: 140, color: Colors.white.withValues(alpha: 0.1)),
              ),
              Positioned(
                top: -20,
                right: 40,
                child: Container(
                  width: 80,
                  height: 80,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: Colors.greenAccent.withValues(alpha: 0.08),
                  ),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(18.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.end,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 4),
                      decoration: BoxDecoration(
                        color: Colors.white.withValues(alpha: 0.2),
                        borderRadius: BorderRadius.circular(8),
                        border: Border.all(color: Colors.white.withValues(alpha: 0.3)),
                      ),
                      child: const Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Icon(Icons.workspace_premium_rounded, color: Colors.amberAccent, size: 13),
                          SizedBox(width: 4),
                          Text('MEMBER EXCLUSIVE', style: TextStyle(color: Colors.white, fontSize: 9.5, fontWeight: FontWeight.bold, letterSpacing: 0.5)),
                        ],
                      ),
                    ),
                    const SizedBox(height: 10),
                    const Text(
                      'Kemitraan Distributor\nWowin Food Resmi',
                      style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold, height: 1.25),
                    ),
                    const SizedBox(height: 12),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 7),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(20),
                        boxShadow: [
                          BoxShadow(
                            color: const Color(0xFF25D366).withValues(alpha: 0.35),
                            blurRadius: 8,
                            offset: const Offset(0, 3),
                          ),
                        ],
                      ),
                      child: const Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Icon(Icons.chat_rounded, color: Color(0xFF25D366), size: 14),
                          SizedBox(width: 6),
                          Text(
                            'Hubungi Admin (WA)',
                            style: TextStyle(color: Color(0xFF0F5132), fontSize: 12, fontWeight: FontWeight.w800),
                          ),
                          SizedBox(width: 5),
                          Icon(Icons.arrow_forward_ios_rounded, color: Color(0xFF0F5132), size: 10),
                        ],
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

  Widget _buildFallbackBundling(BuildContext context) {
    return Container(
      width: 260,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(16),
        gradient: WowinGradients.superDeal,
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(16),
          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => PromoScreen(bundlings: _bundlingList))),
          child: Stack(
            children: [
              Positioned(
                right: -20,
                bottom: -20,
                child: Icon(Icons.local_shipping, size: 100, color: Colors.white.withValues(alpha: 0.15)),
              ),
              Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                      decoration: BoxDecoration(color: Colors.white.withValues(alpha: 0.2), borderRadius: BorderRadius.circular(4)),
                      child: const Text('PROMO SPESIAL', style: TextStyle(color: Colors.white, fontSize: 9, fontWeight: FontWeight.bold)),
                    ),
                    const SizedBox(height: 8),
                    const Text('PAKET BUNDLING\nHEMAT WOWIN', style: TextStyle(color: Colors.white, fontSize: 15, fontWeight: FontWeight.bold, height: 1.2)),
                    const SizedBox(height: 4),
                    Text('Bebas Ongkir & Poin Ekstra', style: TextStyle(color: Colors.white.withValues(alpha: 0.85), fontSize: 11)),
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