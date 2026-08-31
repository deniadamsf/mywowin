import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:intl/intl.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/theme/wowin_theme.dart';
import '../../../core/widgets/wowin_cached_image.dart';

class RewardScreen extends StatefulWidget {
  const RewardScreen({super.key});

  @override
  State<RewardScreen> createState() => _RewardScreenState();
}

class _RewardScreenState extends State<RewardScreen> with SingleTickerProviderStateMixin {
  List<dynamic> _allRewards = [];
  List<dynamic> _displayedRewards = [];
  int _userPoints = 0;
  bool _isLoading = true;
  String _searchQuery = '';
  String _selectedFilter = 'Semua';

  late AnimationController _animController;
  late Animation<double> _fadeAnimation;

  @override
  void initState() {
    super.initState();
    _animController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 600),
    );
    _fadeAnimation = CurvedAnimation(parent: _animController, curve: Curves.easeOut);
    _fetchData();
  }

  @override
  void dispose() {
    _animController.dispose();
    super.dispose();
  }

  Future<void> _fetchData() async {
    setState(() => _isLoading = true);

    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    if (token == null) {
      if (mounted) setState(() => _isLoading = false);
      return;
    }

    // 1. Tarik Data Profil (Saldo Poin Pengguna)
    try {
      final profileRes = await http.get(
        Uri.parse('$baseUrl/profile'),
        headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'},
      );

      if (profileRes.statusCode == 200) {
        final profileData = json.decode(profileRes.body);
        if (profileData['data'] != null) {
          final data = profileData['data'];
          if (mounted) {
            setState(() {
              _userPoints = int.tryParse((data['total_points'] ?? 0).toString()) ?? 0;
            });
          }
        }
      }
    } catch (e) {
      debugPrint('Gagal tarik profil poin: $e');
    }

    // 2. Tarik Data Reward (Daftar Hadiah)
    try {
      final rewardRes = await http.get(
        Uri.parse('$baseUrl/rewards?search=$_searchQuery'),
        headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'},
      );

      if (rewardRes.statusCode == 200) {
        final rewardData = json.decode(rewardRes.body);
        if (mounted) {
          setState(() {
            _allRewards = rewardData['data'] ?? [];
            _applyFilter(_selectedFilter);
          });
        }
      }
    } catch (e) {
      debugPrint('Gagal tarik reward: $e');
    }

    if (mounted) {
      setState(() => _isLoading = false);
      _animController.forward(from: 0.0);
    }
  }

  void _applyFilter(String filterName) {
    setState(() {
      _selectedFilter = filterName;
      _displayedRewards = List.from(_allRewards);

      if (filterName == 'Poin Terendah') {
        _displayedRewards.sort((a, b) => (int.tryParse(a['points_required'].toString()) ?? 0)
            .compareTo(int.tryParse(b['points_required'].toString()) ?? 0));
      } else if (filterName == 'Poin Tertinggi') {
        _displayedRewards.sort((a, b) => (int.tryParse(b['points_required'].toString()) ?? 0)
            .compareTo(int.tryParse(a['points_required'].toString()) ?? 0));
      }
    });
  }

  Future<void> _claimReward(int rewardId, String rewardName) async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    if (!mounted) return;

    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (c) => const Center(
        child: CircularProgressIndicator(color: WowinColors.accentMint),
      ),
    );

    try {
      final response = await http.post(
        Uri.parse('$baseUrl/rewards/claim'),
        headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'},
        body: {'reward_id': rewardId.toString()},
      );

      if (mounted) Navigator.pop(context);
      final data = json.decode(response.body);

      if (response.statusCode == 200) {
        if (!mounted) return;
        _showSuccessClaimDialog(rewardName);
        _fetchData();
      } else {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(data['message'] ?? 'Gagal klaim reward'),
            backgroundColor: WowinColors.promoRed,
          ),
        );
      }
    } catch (e) {
      if (mounted) Navigator.pop(context);
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Terjadi kesalahan jaringan.'),
          backgroundColor: WowinColors.promoRed,
        ),
      );
    }
  }

  void _showSuccessClaimDialog(String rewardName) {
    showDialog(
      context: context,
      builder: (ctx) => Dialog(
        backgroundColor: Colors.transparent,
        child: WowinGlassCard(
          borderRadius: 24,
          padding: const EdgeInsets.all(24),
          backgroundColor: Colors.white.withValues(alpha: 0.95),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  gradient: WowinGradients.goldBadge,
                  shape: BoxShape.circle,
                  boxShadow: [
                    BoxShadow(
                      color: WowinColors.gold.withValues(alpha: 0.4),
                      blurRadius: 16,
                      offset: const Offset(0, 4),
                    )
                  ],
                ),
                child: const Icon(Icons.celebration, color: Colors.white, size: 40),
              ),
              const SizedBox(height: 18),
              const Text(
                'Selamat! 🎉',
                style: TextStyle(
                  fontSize: 22,
                  fontWeight: FontWeight.bold,
                  color: WowinColors.textPrimary,
                ),
              ),
              const SizedBox(height: 8),
              Text(
                'Anda berhasil mengklaim "$rewardName". Silakan hubungi admin cabang Wowin untuk pengambilan.',
                textAlign: TextAlign.center,
                style: const TextStyle(
                  fontSize: 13,
                  color: WowinColors.textSecondary,
                  height: 1.5,
                ),
              ),
              const SizedBox(height: 24),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  style: ElevatedButton.styleFrom(
                    backgroundColor: WowinColors.primary,
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                  onPressed: () => Navigator.pop(ctx),
                  child: const Text(
                    'Tutup & Selesai',
                    style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 15),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  void _showCaraKerjaDialog() {
    showDialog(
      context: context,
      builder: (ctx) => Dialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        insetPadding: const EdgeInsets.all(16),
        child: Container(
          width: double.infinity,
          constraints: BoxConstraints(maxHeight: MediaQuery.of(context).size.height * 0.85),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(20),
            color: Colors.white,
          ),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                padding: const EdgeInsets.all(20),
                decoration: const BoxDecoration(
                  gradient: WowinGradients.royalEmerald,
                  borderRadius: BorderRadius.only(
                    topLeft: Radius.circular(20),
                    topRight: Radius.circular(20),
                  ),
                ),
                child: Row(
                  children: [
                    Container(
                      padding: const EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: Colors.white.withValues(alpha: 0.15),
                        shape: BoxShape.circle,
                        border: Border.all(color: Colors.white.withValues(alpha: 0.3)),
                      ),
                      child: const Icon(Icons.stars_rounded, color: WowinColors.goldLight, size: 24),
                    ),
                    const SizedBox(width: 14),
                    const Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Cara Kerja Reward', style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
                          Text('Program Loyalitas Wowin Food', style: TextStyle(color: Colors.white70, fontSize: 12)),
                        ],
                      ),
                    ),
                    IconButton(
                      icon: const Icon(Icons.close, color: Colors.white),
                      onPressed: () => Navigator.pop(ctx),
                    )
                  ],
                ),
              ),
              Flexible(
                child: SingleChildScrollView(
                  padding: const EdgeInsets.all(20),
                  child: Column(
                    children: [
                      _buildStep(1, 'Kumpulkan Poin Harian', 'Buka katalog dan klaim bonus +100 poin harian Anda setiap hari. Poin direset otomatis setiap jam 12 malam.'),
                      _buildStep(2, 'Pilih Hadiah Spesial', 'Pilih merchandise eksklusif atau voucher produk yang tersedia pada daftar reward.'),
                      _buildStep(3, 'Tukar Poin Instan', 'Klik tombol "Klaim" pada hadiah yang diinginkan saat saldo poin Anda mencukupi.'),
                      _buildStep(4, 'Pengambilan di Cabang', 'Tunjukkan bukti klaim ke kantor cabang PT Wowin Purnomo Putera terdekat Anda.'),
                    ],
                  ),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(16),
                child: SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: () => Navigator.pop(ctx),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: WowinColors.primary,
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                    child: const Text('Saya Mengerti', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildStep(int number, String title, String desc) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 18),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 30,
            height: 30,
            alignment: Alignment.center,
            decoration: BoxDecoration(
              gradient: WowinGradients.goldBadge,
              shape: BoxShape.circle,
              boxShadow: [
                BoxShadow(
                  color: WowinColors.gold.withValues(alpha: 0.3),
                  blurRadius: 8,
                  offset: const Offset(0, 2),
                )
              ],
            ),
            child: Text('$number', style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 14)),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: WowinColors.textPrimary)),
                const SizedBox(height: 4),
                Text(desc, style: const TextStyle(fontSize: 12, color: WowinColors.textSecondary, height: 1.4)),
              ],
            ),
          )
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: WowinColors.background,
      body: RefreshIndicator(
        color: WowinColors.accentMint,
        onRefresh: _fetchData,
        child: CustomScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          slivers: [
            // --- APP BAR & HERO GLASS BANNER ---
            SliverAppBar(
              expandedHeight: 330,
              pinned: true,
              elevation: 0,
              scrolledUnderElevation: 0,
              backgroundColor: WowinColors.primaryDark,
              iconTheme: const IconThemeData(color: Colors.white, size: 20),
              title: const Text(
                'Loyalitas Mitra',
                style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 16.5, letterSpacing: -0.2),
              ),
              flexibleSpace: FlexibleSpaceBar(
                background: Stack(
                  fit: StackFit.expand,
                  children: [
                    // Background Multi-gradient
                    Container(
                      decoration: const BoxDecoration(
                        gradient: WowinGradients.royalEmerald,
                      ),
                    ),

                    // Decorative Ambient Glow Circles
                    Positioned(
                      top: -40,
                      right: -30,
                      child: Container(
                        width: 200,
                        height: 200,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          color: WowinColors.accentMint.withValues(alpha: 0.15),
                        ),
                      ),
                    ),
                    Positioned(
                      bottom: 20,
                      left: -20,
                      child: Container(
                        width: 160,
                        height: 160,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          color: WowinColors.gold.withValues(alpha: 0.12),
                        ),
                      ),
                    ),

                    // Content Container
                    SafeArea(
                      child: Padding(
                        padding: const EdgeInsets.fromLTRB(20, 50, 20, 20),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            // Header Top Title & Action
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                const Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      'WOWIN REWARDS',
                                      style: TextStyle(
                                        color: WowinColors.goldLight,
                                        fontSize: 11,
                                        fontWeight: FontWeight.w800,
                                        letterSpacing: 1.2,
                                      ),
                                    ),
                                    SizedBox(height: 2),
                                    Text(
                                      'Loyalitas Mitra',
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontSize: 18,
                                        fontWeight: FontWeight.bold,
                                        letterSpacing: -0.3,
                                      ),
                                    ),
                                  ],
                                ),
                                InkWell(
                                  onTap: _showCaraKerjaDialog,
                                  borderRadius: BorderRadius.circular(20),
                                  child: Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                                    decoration: BoxDecoration(
                                      color: Colors.white.withValues(alpha: 0.15),
                                      borderRadius: BorderRadius.circular(20),
                                      border: Border.all(color: Colors.white.withValues(alpha: 0.3)),
                                    ),
                                    child: const Row(
                                      mainAxisSize: MainAxisSize.min,
                                      children: [
                                        Icon(Icons.help_outline, color: Colors.white, size: 14),
                                        SizedBox(width: 4),
                                        Text('Info', style: TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.w600)),
                                      ],
                                    ),
                                  ),
                                ),
                              ],
                            ),

                            const Spacer(),

                            // --- GLASSMORPHISM FROSTED POINT CARD ---
                            WowinGlassCard(
                              borderRadius: 20,
                              blurSigma: 16,
                              padding: const EdgeInsets.all(20),
                              backgroundColor: Colors.white.withValues(alpha: 0.14),
                              border: Border.all(color: Colors.white.withValues(alpha: 0.25), width: 1.2),
                              shadows: [
                                BoxShadow(
                                  color: Colors.black.withValues(alpha: 0.15),
                                  blurRadius: 20,
                                  offset: const Offset(0, 10),
                                )
                              ],
                              child: Column(
                                children: [
                                  Row(
                                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                    children: [
                                      Row(
                                        children: [
                                          Container(
                                            padding: const EdgeInsets.all(8),
                                            decoration: BoxDecoration(
                                              gradient: WowinGradients.goldBadge,
                                              shape: BoxShape.circle,
                                              boxShadow: [
                                                BoxShadow(
                                                  color: WowinColors.gold.withValues(alpha: 0.4),
                                                  blurRadius: 10,
                                                  offset: const Offset(0, 3),
                                                )
                                              ],
                                            ),
                                            child: const Icon(Icons.monetization_on, color: Colors.white, size: 22),
                                          ),
                                          const SizedBox(width: 10),
                                          const Text(
                                            'Saldo Poin Aktif',
                                            style: TextStyle(
                                              color: Colors.white70,
                                              fontSize: 13,
                                              fontWeight: FontWeight.w600,
                                            ),
                                          ),
                                        ],
                                      ),
                                      Container(
                                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                        decoration: BoxDecoration(
                                          color: WowinColors.accentMint.withValues(alpha: 0.25),
                                          borderRadius: BorderRadius.circular(8),
                                          border: Border.all(color: WowinColors.accentMint.withValues(alpha: 0.5)),
                                        ),
                                        child: const Text(
                                          'TERVERIFIKASI',
                                          style: TextStyle(
                                            color: WowinColors.accentMint,
                                            fontSize: 10,
                                            fontWeight: FontWeight.bold,
                                            letterSpacing: 0.5,
                                          ),
                                        ),
                                      )
                                    ],
                                  ),
                                  const SizedBox(height: 12),
                                  Row(
                                    crossAxisAlignment: CrossAxisAlignment.baseline,
                                    textBaseline: TextBaseline.alphabetic,
                                    children: [
                                      WowinAnimatedCounter(
                                        targetValue: _userPoints,
                                        style: const TextStyle(
                                          fontSize: 38,
                                          fontWeight: FontWeight.w900,
                                          color: Colors.white,
                                          letterSpacing: -1,
                                        ),
                                      ),
                                      const SizedBox(width: 8),
                                      const Text(
                                        'POIN',
                                        style: TextStyle(
                                          color: WowinColors.goldLight,
                                          fontSize: 14,
                                          fontWeight: FontWeight.bold,
                                          letterSpacing: 1,
                                        ),
                                      ),
                                    ],
                                  ),
                                  const SizedBox(height: 14),

                                  // Animated Dynamic Tier Progress
                                  ClipRRect(
                                    borderRadius: BorderRadius.circular(10),
                                    child: Container(
                                      height: 6,
                                      color: Colors.white.withValues(alpha: 0.15),
                                      alignment: Alignment.centerLeft,
                                      child: FractionallySizedBox(
                                        widthFactor: (_userPoints / 1000).clamp(0.05, 1.0),
                                        child: Container(
                                          decoration: const BoxDecoration(
                                            gradient: WowinGradients.goldBadge,
                                          ),
                                        ),
                                      ),
                                    ),
                                  ),
                                  const SizedBox(height: 8),
                                  Row(
                                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                    children: [
                                      const Text('Klaim reward impian Anda', style: TextStyle(color: Colors.white60, fontSize: 11)),
                                      Text('Target: 1.000 Poin', style: TextStyle(color: WowinColors.goldLight.withValues(alpha: 0.8), fontSize: 11, fontWeight: FontWeight.w600)),
                                    ],
                                  ),
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

            // --- FILTER & SEARCH BAR SECTION ---
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.fromLTRB(20, 20, 20, 10),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Pilihan Hadiah Eksklusif',
                      style: TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        color: WowinColors.textPrimary,
                      ),
                    ),
                    const SizedBox(height: 14),

                    // Filter Pills Horizontal
                    SingleChildScrollView(
                      scrollDirection: Axis.horizontal,
                      child: Row(
                        children: [
                          _buildFilterPill('Semua', Icons.auto_awesome),
                          _buildFilterPill('Poin Terendah', Icons.arrow_downward),
                          _buildFilterPill('Poin Tertinggi', Icons.arrow_upward),
                        ],
                      ),
                    ),
                    const SizedBox(height: 14),

                    // Modern Search Box
                    Container(
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(14),
                        border: Border.all(color: WowinColors.border),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withValues(alpha: 0.03),
                            blurRadius: 10,
                            offset: const Offset(0, 4),
                          )
                        ],
                      ),
                      child: TextField(
                        onSubmitted: (value) {
                          setState(() => _searchQuery = value);
                          _fetchData();
                        },
                        decoration: InputDecoration(
                          hintText: 'Cari nama hadiah / produk...',
                          hintStyle: const TextStyle(fontSize: 13, color: WowinColors.textMuted),
                          prefixIcon: const Icon(Icons.search, color: WowinColors.primaryLight, size: 20),
                          border: InputBorder.none,
                          contentPadding: const EdgeInsets.symmetric(vertical: 14, horizontal: 16),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),

            // --- REWARD LIST / EMPTY / LOADING STATE ---
            if (_isLoading)
              const SliverFillRemaining(
                child: Center(
                  child: CircularProgressIndicator(color: WowinColors.accentMint),
                ),
              )
            else if (_displayedRewards.isEmpty)
              SliverFillRemaining(
                child: Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.card_giftcard_outlined, size: 64, color: Colors.grey.shade300),
                      const SizedBox(height: 12),
                      const Text(
                        'Belum ada reward yang cocok.',
                        style: TextStyle(color: WowinColors.textSecondary, fontSize: 15, fontWeight: FontWeight.w500),
                      ),
                    ],
                  ),
                ),
              )
            else
              SliverPadding(
                padding: const EdgeInsets.fromLTRB(20, 10, 20, 30),
                sliver: SliverList(
                  delegate: SliverChildBuilderDelegate(
                    (context, index) {
                      final reward = _displayedRewards[index];
                      final int pointCost = int.tryParse(reward['points_required'].toString()) ?? 0;
                      final bool isEnough = _userPoints >= pointCost;
                      final formattedCost = NumberFormat('#,##0', 'en_US').format(pointCost);

                      String imageUrl = '';
                      if (reward['foto_rewards'] != null && reward['foto_rewards'].toString().isNotEmpty) {
                        final foto = reward['foto_rewards'].toString();
                        imageUrl = foto.startsWith('http') ? foto : 'https://mywowin.com/storage/$foto';
                      }

                      return FadeTransition(
                        opacity: _fadeAnimation,
                        child: Container(
                          margin: const EdgeInsets.only(bottom: 16),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(18),
                            border: Border.all(
                              color: isEnough ? WowinColors.accentMint.withValues(alpha: 0.3) : WowinColors.border,
                              width: isEnough ? 1.4 : 1.0,
                            ),
                            boxShadow: [
                              BoxShadow(
                                color: isEnough
                                    ? WowinColors.primary.withValues(alpha: 0.06)
                                    : Colors.black.withValues(alpha: 0.03),
                                blurRadius: 14,
                                offset: const Offset(0, 6),
                              )
                            ],
                          ),
                          child: Padding(
                            padding: const EdgeInsets.all(14.0),
                            child: Row(
                              crossAxisAlignment: CrossAxisAlignment.center,
                              children: [
                                // Gambar Reward dengan Rounded Clip
                                Container(
                                  width: 80,
                                  height: 80,
                                  decoration: BoxDecoration(
                                    borderRadius: BorderRadius.circular(14),
                                    color: Colors.grey.shade50,
                                    border: Border.all(color: Colors.grey.shade100),
                                  ),
                                  child: ClipRRect(
                                    borderRadius: BorderRadius.circular(14),
                                    child: imageUrl.isNotEmpty
                                        ? WowinCachedImage(
                                            imageUrl: imageUrl,
                                            fit: BoxFit.cover,
                                            errorWidget: Container(
                                              color: WowinColors.primaryDark.withValues(alpha: 0.05),
                                              child: const Icon(Icons.card_giftcard, color: WowinColors.primaryLight, size: 30),
                                            ),
                                          )
                                        : Container(
                                            color: WowinColors.primaryDark.withValues(alpha: 0.05),
                                            child: const Icon(Icons.card_giftcard, color: WowinColors.primaryLight, size: 30),
                                          ),
                                  ),
                                ),
                                const SizedBox(width: 14),

                                // Detail Reward
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        reward['nama_reward'] ?? 'Nama Hadiah',
                                        maxLines: 1,
                                        overflow: TextOverflow.ellipsis,
                                        style: const TextStyle(
                                          fontWeight: FontWeight.bold,
                                          fontSize: 14,
                                          color: WowinColors.textPrimary,
                                        ),
                                      ),
                                      const SizedBox(height: 4),
                                      Text(
                                        reward['deskripsi'] ?? '-',
                                        maxLines: 2,
                                        overflow: TextOverflow.ellipsis,
                                        style: const TextStyle(
                                          fontSize: 11.5,
                                          color: WowinColors.textSecondary,
                                          height: 1.3,
                                        ),
                                      ),
                                      const SizedBox(height: 8),

                                      // Point Tag Badge
                                      Row(
                                        children: [
                                          const Icon(Icons.monetization_on, color: WowinColors.gold, size: 14),
                                          const SizedBox(width: 4),
                                          Text(
                                            '$formattedCost Poin',
                                            style: const TextStyle(
                                              fontWeight: FontWeight.bold,
                                              fontSize: 13,
                                              color: WowinColors.goldDark,
                                            ),
                                          ),
                                        ],
                                      ),
                                    ],
                                  ),
                                ),
                                const SizedBox(width: 10),

                                // Tombol Klaim Berstatus
                                ElevatedButton(
                                  onPressed: isEnough
                                      ? () => _claimReward(reward['id'], reward['nama_reward'] ?? 'Reward')
                                      : null,
                                  style: ElevatedButton.styleFrom(
                                    backgroundColor: WowinColors.primary,
                                    disabledBackgroundColor: Colors.grey.shade100,
                                    foregroundColor: Colors.white,
                                    disabledForegroundColor: Colors.grey.shade400,
                                    elevation: isEnough ? 2 : 0,
                                    padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                                  ),
                                  child: Text(
                                    isEnough ? 'Klaim' : 'Kurang',
                                    style: TextStyle(
                                      fontSize: 12,
                                      fontWeight: FontWeight.bold,
                                      color: isEnough ? Colors.white : Colors.grey.shade400,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ),
                      );
                    },
                    childCount: _displayedRewards.length,
                  ),
                ),
              ),
          ],
        ),
      ),
    );
  }

  Widget _buildFilterPill(String title, IconData icon) {
    final bool isSelected = _selectedFilter == title;
    return Padding(
      padding: const EdgeInsets.only(right: 8.0),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 250),
        child: InkWell(
          onTap: () => _applyFilter(title),
          borderRadius: BorderRadius.circular(20),
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
            decoration: BoxDecoration(
              gradient: isSelected ? WowinGradients.royalEmerald : null,
              color: isSelected ? null : Colors.white,
              borderRadius: BorderRadius.circular(20),
              border: Border.all(
                color: isSelected ? Colors.transparent : WowinColors.border,
              ),
              boxShadow: [
                if (isSelected)
                  BoxShadow(
                    color: WowinColors.primary.withValues(alpha: 0.25),
                    blurRadius: 8,
                    offset: const Offset(0, 3),
                  )
              ],
            ),
            child: Row(
              children: [
                Icon(icon, size: 14, color: isSelected ? Colors.white : WowinColors.textSecondary),
                const SizedBox(width: 6),
                Text(
                  title,
                  style: TextStyle(
                    color: isSelected ? Colors.white : WowinColors.textPrimary,
                    fontWeight: isSelected ? FontWeight.bold : FontWeight.w500,
                    fontSize: 12,
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}