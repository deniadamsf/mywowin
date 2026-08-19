import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/api_constants.dart';
import 'package:intl/intl.dart';

class RewardScreen extends StatefulWidget {
  const RewardScreen({super.key});

  @override
  State<RewardScreen> createState() => _RewardScreenState();
}

class _RewardScreenState extends State<RewardScreen> {
  static const Color wowinDarkGreen = Color(0xFF0B5C20);
  static const Color wowinLightGreen = Color(0xFF16782D);

  List<dynamic> _allRewards = [];
  List<dynamic> _displayedRewards = [];
  int _userPoints = 0;
  bool _isLoading = true;
  String _searchQuery = '';
  String _selectedFilter = 'Semua';

  @override
  void initState() {
    super.initState();
    _fetchData();
  }

  // --- PERBAIKAN: MEMISAHKAN TARIKAN DATA AGAR ANTI-GAGAL ---
  Future<void> _fetchData() async {
    setState(() => _isLoading = true);

    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    if (token == null) {
      if (mounted) setState(() => _isLoading = false);
      return;
    }

    // 1. Tarik Data Profil (Untuk Poin)
    try {
      final profileRes = await http.get(
        Uri.parse('$baseUrl/profile'),
        headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'},
      );

      if (profileRes.statusCode == 200) {
        final profileData = json.decode(profileRes.body);
        if (profileData['data'] != null) {
          final data = profileData['data'];
          setState(() {
            // Langsung ambil total_points sebagai saldo utama untuk ditukar hadiah
            _userPoints = int.tryParse((data['total_points'] ?? 0).toString()) ?? 0;
          });
        }
      }
    } catch (e) {
      debugPrint('Gagal tarik profil: $e');
    }

    // 2. Tarik Data Reward (Untuk Daftar Hadiah)
    try {
      final rewardRes = await http.get(
        Uri.parse('$baseUrl/rewards?search=$_searchQuery'),
        headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'},
      );

      if (rewardRes.statusCode == 200) {
        final rewardData = json.decode(rewardRes.body);
        setState(() {
          _allRewards = rewardData['data'] ?? [];
          _applyFilter(_selectedFilter);
        });
      }
    } catch (e) {
      debugPrint('Gagal tarik reward: $e');
    }

    // Matikan loading setelah keduanya selesai dicoba
    if (mounted) {
      setState(() => _isLoading = false);
    }
  }

  void _applyFilter(String filterName) {
    setState(() {
      _selectedFilter = filterName;
      _displayedRewards = List.from(_allRewards);

      if (filterName == 'Poin Terendah') {
        _displayedRewards.sort((a, b) => (int.tryParse(a['points_required'].toString()) ?? 0).compareTo(int.tryParse(b['points_required'].toString()) ?? 0));
      } else if (filterName == 'Poin Tertinggi') {
        _displayedRewards.sort((a, b) => (int.tryParse(b['points_required'].toString()) ?? 0).compareTo(int.tryParse(a['points_required'].toString()) ?? 0));
      }
    });
  }

  Future<void> _claimReward(int rewardId) async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    showDialog(context: context, barrierDismissible: false, builder: (c) => const Center(child: CircularProgressIndicator(color: wowinLightGreen)));

    try {
      final response = await http.post(
        Uri.parse('$baseUrl/rewards/claim'),
        headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'},
        body: {'reward_id': rewardId.toString()},
      );

      Navigator.pop(context);
      final data = json.decode(response.body);

      if (response.statusCode == 200) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(data['message']), backgroundColor: wowinLightGreen));
        _fetchData();
      } else {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(data['message'] ?? 'Gagal klaim'), backgroundColor: Colors.red));
      }
    } catch (e) {
      Navigator.pop(context);
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Terjadi kesalahan jaringan'), backgroundColor: Colors.red));
    }
  }

  void _showCaraKerjaDialog() {
    showDialog(
      context: context,
      builder: (ctx) => Dialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        insetPadding: const EdgeInsets.all(16),
        child: Container(
          width: double.infinity,
          constraints: BoxConstraints(maxHeight: MediaQuery.of(context).size.height * 0.85),
          decoration: BoxDecoration(borderRadius: BorderRadius.circular(16), color: Colors.white),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                padding: const EdgeInsets.all(20),
                decoration: const BoxDecoration(
                  gradient: LinearGradient(colors: [wowinDarkGreen, wowinLightGreen], begin: Alignment.centerLeft, end: Alignment.centerRight),
                  borderRadius: BorderRadius.only(topLeft: Radius.circular(16), topRight: Radius.circular(16)),
                ),
                child: Row(
                  children: [
                    Container(
                      padding: const EdgeInsets.all(6),
                      decoration: BoxDecoration(color: Colors.white.withValues(alpha: 0.2), shape: BoxShape.circle),
                      child: const Icon(Icons.info_outline, color: Colors.white, size: 24),
                    ),
                    const SizedBox(width: 12),
                    const Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Cara Kerja', style: TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.bold)),
                          Text('Program MyWowin Rewards', style: TextStyle(color: Colors.white70, fontSize: 13)),
                        ],
                      ),
                    ),
                    IconButton(
                      icon: const Icon(Icons.close, color: Colors.white),
                      onPressed: () => Navigator.pop(ctx),
                      padding: EdgeInsets.zero,
                      constraints: const BoxConstraints(),
                    )
                  ],
                ),
              ),

              Flexible(
                child: SingleChildScrollView(
                  padding: const EdgeInsets.all(20),
                  child: Column(
                    children: [
                      _buildStep(1, 'Kumpulkan Poin', 'Dapatkan poin setiap kali Anda melakukan login pada website MyWowin selama 30 hari berturut-turut. Setiap 1000 poin setara dengan Rp 1.000.'),
                      _buildStep(2, 'Pilih Reward', 'Pilih hadiah yang Anda inginkan dari berbagai pilihan yang tersedia. Pastikan poin Anda mencukupi serta sesuai dengan s&k yang ada.'),
                      _buildStep(3, 'Klaim Reward', 'Klik tombol "Klaim" pada reward yang Anda inginkan. Poin akan otomatis terpotong dari total poin Anda.'),
                      _buildStep(4, 'Nikmati Hadiah', 'Hadiah reward selama 30 hari berturut-turut akan dikirim kan melalui cabang terdekat dari cabang PT. Wowin Purnomo Putera sesuai Kantor Cabang terdekat Anda.'),

                      const SizedBox(height: 16),
                      Container(
                        padding: const EdgeInsets.all(16),
                        decoration: BoxDecoration(color: Colors.grey[50], borderRadius: BorderRadius.circular(12), border: Border.all(color: Colors.grey.shade200)),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Row(
                              children: [
                                Icon(Icons.lightbulb, color: Colors.amber, size: 20),
                                SizedBox(width: 8),
                                Text('Tips', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                              ],
                            ),
                            const SizedBox(height: 12),
                            _buildTip('Poin akan pada setelan awal jika Anda tidak login secara berturut-turut selama 30 hari.'),
                            _buildTip('Dapatkan poin bonus dengan mengikuti kegiatan promosi MyWowin.'),
                            _buildTip('Reward yang telah diklaim berlaku selama 30 hari.'),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),

              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(color: Colors.grey[50], borderRadius: const BorderRadius.only(bottomLeft: Radius.circular(16), bottomRight: Radius.circular(16))),
                child: SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: () => Navigator.pop(ctx),
                    style: ElevatedButton.styleFrom(backgroundColor: wowinLightGreen, padding: const EdgeInsets.symmetric(vertical: 14), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8))),
                    child: const Text('Mengerti', style: TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold)),
                  ),
                ),
              )
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildStep(int number, String title, String desc) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 20),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 32, height: 32,
            alignment: Alignment.center,
            decoration: const BoxDecoration(color: wowinLightGreen, shape: BoxShape.circle),
            child: Text('$number', style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 16)),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: Colors.black87)),
                const SizedBox(height: 4),
                Text(desc, style: TextStyle(fontSize: 13, color: Colors.grey[600], height: 1.5)),
              ],
            ),
          )
        ],
      ),
    );
  }

  Widget _buildTip(String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Padding(padding: EdgeInsets.only(top: 2), child: Icon(Icons.check_circle, color: wowinLightGreen, size: 16)),
          const SizedBox(width: 8),
          Expanded(child: Text(text, style: TextStyle(fontSize: 13, color: Colors.grey[700], height: 1.4))),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final formattedPoints = NumberFormat('#,##0', 'en_US').format(_userPoints);

    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        elevation: 0,
        flexibleSpace: Container(
          decoration: const BoxDecoration(
            gradient: LinearGradient(colors: [wowinDarkGreen, wowinLightGreen], begin: Alignment.centerLeft, end: Alignment.centerRight),
          ),
        ),
        iconTheme: const IconThemeData(color: Colors.white),
        title: const Text('Rewards Program', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
      ),
      body: RefreshIndicator(
        color: wowinLightGreen,
        onRefresh: _fetchData,
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                width: double.infinity,
                decoration: const BoxDecoration(
                  gradient: LinearGradient(colors: [wowinDarkGreen, wowinLightGreen], begin: Alignment.centerLeft, end: Alignment.centerRight),
                ),
                padding: const EdgeInsets.only(left: 20, right: 20, top: 10, bottom: 30),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('WOWINFood Rewards', style: TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    const Text('Nikmati berbagai hadiah menarik dari usaha kuliner terbaik dengan menukarkan poin Anda.', style: TextStyle(color: Colors.white70, fontSize: 14, height: 1.5)),
                    const SizedBox(height: 20),
                    Row(
                      children: [
                        ElevatedButton.icon(
                          onPressed: () {},
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.white, foregroundColor: wowinLightGreen, elevation: 0,
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                          ),
                          icon: const Icon(Icons.card_giftcard, size: 18), label: const Text('Lihat Rewards', style: TextStyle(fontWeight: FontWeight.bold)),
                        ),
                        const SizedBox(width: 12),
                        OutlinedButton.icon(
                          onPressed: _showCaraKerjaDialog,
                          style: OutlinedButton.styleFrom(
                            foregroundColor: Colors.white, side: const BorderSide(color: Colors.white),
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                          ),
                          icon: const Icon(Icons.info_outline, size: 18), label: const Text('Cara Kerja', style: TextStyle(fontWeight: FontWeight.bold)),
                        ),
                      ],
                    ),
                    const SizedBox(height: 24),

                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.symmetric(vertical: 24, horizontal: 20),
                      decoration: BoxDecoration(
                        color: Colors.white.withValues(alpha: 0.1),
                        border: Border.all(color: Colors.white.withValues(alpha: 0.2)),
                        borderRadius: BorderRadius.circular(16),
                      ),
                      child: Column(
                        children: [
                          Container(
                            padding: const EdgeInsets.all(8),
                            decoration: BoxDecoration(color: Colors.white.withValues(alpha: 0.2), shape: BoxShape.circle),
                            child: const Icon(Icons.monetization_on, color: Colors.yellow, size: 35),
                          ),
                          const SizedBox(height: 12),
                          const Text('Poin Anda', style: TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold)),
                          Text(formattedPoints, style: const TextStyle(color: Colors.white, fontSize: 42, fontWeight: FontWeight.bold, height: 1.2)),
                          const SizedBox(height: 12),
                          // --- PROGRESS BAR DINAMIS (Sesuai perhitungan Web) ---
                          Container(
                            height: 6,
                            width: double.infinity,
                            alignment: Alignment.centerLeft,
                            decoration: BoxDecoration(
                                color: Colors.white.withValues(alpha: 0.2),
                                borderRadius: BorderRadius.circular(10)
                            ),
                            child: FractionallySizedBox(
                              // Logika Web: Poin 1000 = 100% (atau 1.0 di Flutter)
                              widthFactor: (_userPoints / 1000).clamp(0.0, 1.0),
                              child: Container(
                                decoration: BoxDecoration(
                                    color: Colors.yellow,
                                    borderRadius: BorderRadius.circular(10)
                                ),
                              ),
                            ),
                          ),
                          const SizedBox(height: 10),
                          const Text('Kumpulkan lebih banyak poin untuk rewards eksklusif', style: TextStyle(color: Colors.white70, fontSize: 12), textAlign: TextAlign.center),
                        ],
                      ),
                    ),
                  ],
                ),
              ),

              Padding(
                padding: const EdgeInsets.all(20.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Explore Rewards', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.black87)),
                    const SizedBox(height: 16),

                    SingleChildScrollView(
                      scrollDirection: Axis.horizontal,
                      child: Row(
                        children: [
                          _buildFilterButton('Semua', Icons.grid_view),
                          _buildFilterButton('Poin Terendah', Icons.arrow_downward),
                          _buildFilterButton('Poin Tertinggi', Icons.arrow_upward),
                          _buildFilterButton('Terbaru', Icons.access_time),
                        ],
                      ),
                    ),
                    const SizedBox(height: 16),

                    TextField(
                      onSubmitted: (value) {
                        setState(() => _searchQuery = value);
                        _fetchData();
                      },
                      decoration: InputDecoration(
                        hintText: 'Cari rewards...',
                        prefixIcon: const Icon(Icons.search, color: Colors.grey),
                        filled: true, fillColor: Colors.white,
                        contentPadding: const EdgeInsets.symmetric(vertical: 0),
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(8), borderSide: BorderSide(color: Colors.grey.shade300)),
                        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(8), borderSide: BorderSide(color: Colors.grey.shade300)),
                      ),
                    ),
                    const SizedBox(height: 20),

                    if (_isLoading)
                      const Center(child: Padding(padding: EdgeInsets.all(20.0), child: CircularProgressIndicator(color: wowinLightGreen)))
                    else if (_displayedRewards.isEmpty)
                      const Center(child: Padding(padding: EdgeInsets.all(40.0), child: Text('Belum ada reward yang tersedia.', style: TextStyle(color: Colors.grey))))
                    else
                      ListView.builder(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: _displayedRewards.length,
                        itemBuilder: (context, index) {
                          final reward = _displayedRewards[index];

                          final int pointCost = int.tryParse(reward['points_required'].toString()) ?? 0;
                          final bool isEnough = _userPoints >= pointCost;
                          final formattedCost = NumberFormat('#,##0', 'en_US').format(pointCost);

                          String imageUrl = 'https://via.placeholder.com/150';
                          if (reward['foto_rewards'] != null) {
                            imageUrl = 'https://mywowin.com/storage/${reward['foto_rewards']}';
                          }

                          return Card(
                            margin: const EdgeInsets.only(bottom: 16), elevation: 0.5,
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12), side: BorderSide(color: Colors.grey.shade200)),
                            child: Padding(
                              padding: const EdgeInsets.all(16.0),
                              child: Row(
                                crossAxisAlignment: CrossAxisAlignment.center,
                                children: [
                                  ClipRRect(
                                    borderRadius: BorderRadius.circular(8),
                                    child: Image.network(
                                      imageUrl, width: 65, height: 65, fit: BoxFit.cover,
                                      errorBuilder: (ctx, err, stack) => Container(width: 65, height: 65, color: Colors.blue[50], child: const Icon(Icons.image, color: Colors.blue)),
                                    ),
                                  ),
                                  const SizedBox(width: 16),
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Text(reward['nama_reward'] ?? 'Nama Reward', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: Colors.black87)),
                                        const SizedBox(height: 4),
                                        Text(reward['deskripsi'] ?? '-', maxLines: 2, overflow: TextOverflow.ellipsis, style: TextStyle(fontSize: 12, color: Colors.grey[600])),
                                        const SizedBox(height: 8),
                                        Row(
                                          children: [
                                            const Icon(Icons.access_time, size: 12, color: Colors.grey),
                                            const SizedBox(width: 4),
                                            Text('Berlaku 30 hari', style: TextStyle(fontSize: 11, color: Colors.grey[500])),
                                          ],
                                        )
                                      ],
                                    ),
                                  ),
                                  const SizedBox(width: 10),
                                  Column(
                                    crossAxisAlignment: CrossAxisAlignment.end,
                                    children: [
                                      Row(
                                        children: [
                                          const Icon(Icons.monetization_on, color: Colors.amber, size: 16),
                                          const SizedBox(width: 4),
                                          Text(formattedCost, style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.black87, fontSize: 16)),
                                        ],
                                      ),
                                      const SizedBox(height: 8),
                                      ElevatedButton.icon(
                                        onPressed: isEnough ? () => _claimReward(reward['id']) : null,
                                        style: ElevatedButton.styleFrom(
                                          backgroundColor: isEnough ? wowinLightGreen : Colors.grey[100],
                                          foregroundColor: isEnough ? Colors.white : Colors.grey[400],
                                          elevation: 0, padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 0),
                                          minimumSize: const Size(0, 32), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6)),
                                        ),
                                        icon: Icon(isEnough ? Icons.lock_open : Icons.lock, size: 12),
                                        label: Text(isEnough ? 'Klaim' : 'Tidak Cukup', style: const TextStyle(fontSize: 11, fontWeight: FontWeight.bold)),
                                      ),
                                      const SizedBox(height: 6),
                                      Text('${formattedPoints}/$formattedCost', style: TextStyle(fontSize: 10, color: Colors.grey[500])),
                                    ],
                                  )
                                ],
                              ),
                            ),
                          );
                        },
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

  Widget _buildFilterButton(String title, IconData icon) {
    bool isSelected = _selectedFilter == title;
    return Padding(
      padding: const EdgeInsets.only(right: 8.0),
      child: InkWell(
        onTap: () => _applyFilter(title),
        borderRadius: BorderRadius.circular(8),
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
          decoration: BoxDecoration(
            color: isSelected ? wowinLightGreen : Colors.white,
            border: Border.all(color: isSelected ? wowinLightGreen : Colors.grey.shade300),
            borderRadius: BorderRadius.circular(8),
          ),
          child: Row(
            children: [
              Icon(icon, size: 16, color: isSelected ? Colors.white : Colors.grey[700]),
              const SizedBox(width: 6),
              Text(
                title,
                style: TextStyle(
                  color: isSelected ? Colors.white : Colors.grey[800],
                  fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                  fontSize: 13,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}