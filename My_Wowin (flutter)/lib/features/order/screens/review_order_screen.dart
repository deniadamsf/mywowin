import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../../core/theme/wowin_theme.dart';
import '../services/review_api.dart';

class ReviewOrderScreen extends StatefulWidget {
  final dynamic order;

  const ReviewOrderScreen({super.key, required this.order});

  @override
  State<ReviewOrderScreen> createState() => _ReviewOrderScreenState();
}

class _ReviewOrderScreenState extends State<ReviewOrderScreen> {
  static const Color wowinGreen = WowinColors.primary;

  int _selectedRating = 5;
  final TextEditingController _commentController = TextEditingController();
  final List<String> _selectedTags = [];
  final List<File> _attachedImages = [];
  bool _isAnonymous = false;
  bool _isSubmitting = false;

  final List<String> _availableTags = [
    'Kualitas Terjamin',
    'Rasa Nikmat',
    'Pengiriman Cepat',
    'Packing Rapi & Aman',
    'Pelayanan Ramah',
    'Sesuai Pesanan',
    'Harga Terbaik',
  ];

  final ImagePicker _picker = ImagePicker();

  @override
  void dispose() {
    _commentController.dispose();
    super.dispose();
  }

  String _getRatingLabel(int rating) {
    switch (rating) {
      case 1:
        return 'Sangat Kecewa';
      case 2:
        return 'Kurang Puas';
      case 3:
        return 'Cukup Baik';
      case 4:
        return 'Puas & Senang';
      case 5:
        return 'Sangat Puas! ⭐';
      default:
        return 'Puas';
    }
  }

  Color _getRatingColor(int rating) {
    switch (rating) {
      case 1:
      case 2:
        return Colors.orange.shade700;
      case 3:
        return Colors.amber.shade700;
      case 4:
      case 5:
      default:
        return wowinGreen;
    }
  }

  Future<void> _pickImage(ImageSource source) async {
    if (_attachedImages.length >= 3) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Maksimal 3 foto ulasan.')),
      );
      return;
    }

    try {
      final XFile? pickedFile = await _picker.pickImage(
        source: source,
        maxWidth: 1024,
        maxHeight: 1024,
        imageQuality: 80,
      );

      if (pickedFile != null) {
        setState(() {
          _attachedImages.add(File(pickedFile.path));
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

  void _showImagePickerModal() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (ctx) => SafeArea(
        child: Padding(
          padding: const EdgeInsets.symmetric(vertical: 20, horizontal: 16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text('Unggah Foto Ulasan', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
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

  Future<void> _submitReview() async {
    setState(() => _isSubmitting = true);

    // Konversi foto ke base64 jika ada
    List<String> photosBase64 = [];
    for (var file in _attachedImages) {
      try {
        final bytes = await file.readAsBytes();
        final base64String = 'data:image/jpeg;base64,${base64Encode(bytes)}';
        photosBase64.add(base64String);
      } catch (_) {}
    }

    final int orderId = int.tryParse(widget.order['id']?.toString() ?? '0') ?? 0;

    final result = await ReviewApi.submitOrderReview(
      orderId: orderId,
      rating: _selectedRating,
      komentar: _commentController.text.trim(),
      tags: _selectedTags,
      photosBase64: photosBase64,
      isAnonymous: _isAnonymous,
    );

    if (!mounted) return;
    setState(() => _isSubmitting = false);

    if (result['success'] == true) {
      final googleMapsUrl = result['data']?['google_maps_review_url'];
      final kantorCabang = result['data']?['kantor_cabang'] ?? 'Wowin';

      if (_selectedRating >= 4 && googleMapsUrl != null && googleMapsUrl.toString().isNotEmpty) {
        _showGoogleMapsCelebrationDialog(googleMapsUrl.toString(), kantorCabang.toString());
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(result['message'] ?? 'Ulasan berhasil disimpan!'),
            backgroundColor: wowinGreen,
          ),
        );
        Navigator.pop(context, true);
      }
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(result['message'] ?? 'Gagal menyimpan ulasan.'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  void _showGoogleMapsCelebrationDialog(String googleMapsUrl, String cabang) {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: Column(
          children: [
            Container(
              padding: const EdgeInsets.all(14),
              decoration: const BoxDecoration(
                color: Color(0xFFFFF8E1),
                shape: BoxShape.circle,
              ),
              child: const Icon(Icons.star_rounded, color: Colors.amber, size: 48),
            ),
            const SizedBox(height: 12),
            const Text(
              'Terima Kasih Banyak! 🎉',
              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18),
              textAlign: TextAlign.center,
            ),
          ],
        ),
        content: Text(
          'Ulasan bintang 5 Anda sangat berarti bagi kami. Mau bantu berikan ulasan singkat di Google Maps Cabang $cabang agar pelanggan lain mengetahui kualitas produk Wowin?',
          textAlign: TextAlign.center,
          style: const TextStyle(fontSize: 13, height: 1.4, color: WowinColors.textSecondary),
        ),
        actionsPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        actions: [
          Row(
            children: [
              Expanded(
                child: OutlinedButton(
                  onPressed: () {
                    Navigator.pop(ctx);
                    Navigator.pop(context, true);
                  },
                  style: OutlinedButton.styleFrom(
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                  ),
                  child: const Text('Nanti Saja', style: TextStyle(color: Colors.grey, fontSize: 12)),
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: ElevatedButton.icon(
                  onPressed: () async {
                    final Uri url = Uri.parse(googleMapsUrl);
                    if (await canLaunchUrl(url)) {
                      await launchUrl(url, mode: LaunchMode.externalApplication);
                    }
                    if (ctx.mounted) {
                      Navigator.pop(ctx);
                    }
                    if (mounted) {
                      Navigator.pop(context, true);
                    }
                  },
                  icon: const Icon(Icons.open_in_new, size: 14, color: Colors.white),
                  label: const Text('Buka Google', style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: Colors.white)),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF1B5E20),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final List<dynamic> orderItems = widget.order['items'] ?? widget.order['order_items'] ?? widget.order['details'] ?? [];

    return Scaffold(
      backgroundColor: WowinColors.background,
      appBar: WowinAppBar.standard(title: 'Beri Penilaian Pesanan'),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // --- HEADER ORDER INFO ---
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: Colors.grey.shade200),
              ),
              child: Row(
                children: [
                  Container(
                    padding: const EdgeInsets.all(10),
                    decoration: BoxDecoration(
                      color: wowinGreen.withValues(alpha: 0.1),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: const Icon(Icons.receipt_long_rounded, color: wowinGreen, size: 24),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          widget.order['invoice_number'] ?? '#INV-WOWIN',
                          style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: WowinColors.textPrimary),
                        ),
                        const SizedBox(height: 2),
                        Text(
                          '${orderItems.length} Produk dibeli',
                          style: const TextStyle(fontSize: 12, color: WowinColors.textSecondary),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 16),

            // --- STAR RATING CARD ---
            Container(
              width: double.infinity,
              padding: const EdgeInsets.symmetric(vertical: 24, horizontal: 16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: Colors.grey.shade200),
              ),
              child: Column(
                children: [
                  const Text(
                    'Bagaimana kepuasan Anda?',
                    style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: WowinColors.textPrimary),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    _getRatingLabel(_selectedRating),
                    style: TextStyle(
                      fontWeight: FontWeight.w800,
                      fontSize: 14,
                      color: _getRatingColor(_selectedRating),
                    ),
                  ),
                  const SizedBox(height: 16),

                  // 5 Star Buttons
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: List.generate(5, (index) {
                      final starNum = index + 1;
                      return GestureDetector(
                        onTap: () => setState(() => _selectedRating = starNum),
                        child: Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 4),
                          child: Icon(
                            starNum <= _selectedRating ? Icons.star_rounded : Icons.star_outline_rounded,
                            color: Colors.amber.shade500,
                            size: 44,
                          ),
                        ),
                      );
                    }),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 16),

            // --- QUICK CHIPS / TAGS ---
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: Colors.grey.shade200),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Pilih Tag Kepuasan:', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: WowinColors.textPrimary)),
                  const SizedBox(height: 10),
                  Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: _availableTags.map((tag) {
                      final isSelected = _selectedTags.contains(tag);
                      return FilterChip(
                        label: Text(tag),
                        selected: isSelected,
                        onSelected: (selected) {
                          setState(() {
                            if (selected) {
                              _selectedTags.add(tag);
                            } else {
                              _selectedTags.remove(tag);
                            }
                          });
                        },
                        selectedColor: const Color(0xFFE8F5E9),
                        checkmarkColor: wowinGreen,
                        labelStyle: TextStyle(
                          fontSize: 11,
                          fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                          color: isSelected ? wowinGreen : Colors.grey.shade800,
                        ),
                        backgroundColor: Colors.grey.shade100,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(20),
                          side: BorderSide(color: isSelected ? wowinGreen : Colors.transparent),
                        ),
                      );
                    }).toList(),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 16),

            // --- KOMENTAR & FOTO ---
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: Colors.grey.shade200),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Tulis Ulasan Anda (Opsional):', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: WowinColors.textPrimary)),
                  const SizedBox(height: 8),
                  TextField(
                    controller: _commentController,
                    maxLines: 4,
                    maxLength: 500,
                    decoration: InputDecoration(
                      hintText: 'Ceritakan pengalaman Anda mengenai produk, kemasan, atau rasa...',
                      hintStyle: TextStyle(fontSize: 12, color: Colors.grey.shade400),
                      filled: true,
                      fillColor: Colors.grey.shade50,
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: BorderSide(color: Colors.grey.shade200),
                      ),
                      enabledBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: BorderSide(color: Colors.grey.shade200),
                      ),
                      focusedBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: const BorderSide(color: wowinGreen),
                      ),
                    ),
                  ),

                  const SizedBox(height: 12),
                  const Text('Foto Produk / Unboxing:', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: WowinColors.textPrimary)),
                  const SizedBox(height: 8),

                  // Photo Thumbnails & Picker
                  Row(
                    children: [
                      ..._attachedImages.map((file) {
                        return Stack(
                          children: [
                            Container(
                              margin: const EdgeInsets.only(right: 8),
                              width: 65,
                              height: 65,
                              decoration: BoxDecoration(
                                borderRadius: BorderRadius.circular(10),
                                border: Border.all(color: Colors.grey.shade300),
                                image: DecorationImage(image: FileImage(file), fit: BoxFit.cover),
                              ),
                            ),
                            Positioned(
                              top: 2,
                              right: 10,
                              child: GestureDetector(
                                onTap: () => setState(() => _attachedImages.remove(file)),
                                child: Container(
                                  padding: const EdgeInsets.all(2),
                                  decoration: const BoxDecoration(color: Colors.red, shape: BoxShape.circle),
                                  child: const Icon(Icons.close, size: 12, color: Colors.white),
                                ),
                              ),
                            ),
                          ],
                        );
                      }),
                      if (_attachedImages.length < 3)
                        GestureDetector(
                          onTap: _showImagePickerModal,
                          child: Container(
                            width: 65,
                            height: 65,
                            decoration: BoxDecoration(
                              color: Colors.grey.shade50,
                              borderRadius: BorderRadius.circular(10),
                              border: Border.all(color: Colors.grey.shade300, style: BorderStyle.solid),
                            ),
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Icon(Icons.add_a_photo_outlined, size: 20, color: Colors.grey.shade600),
                                const SizedBox(height: 2),
                                Text('Tambah', style: TextStyle(fontSize: 9, color: Colors.grey.shade600)),
                              ],
                            ),
                          ),
                        ),
                    ],
                  ),
                ],
              ),
            ),

            const SizedBox(height: 16),

            // --- ANONYMOUS TOGGLE ---
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: Colors.grey.shade200),
              ),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text('Tampilkan sebagai Anonim', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                      Text('Nama Anda akan disamarkan (misal: B***i)', style: TextStyle(fontSize: 10.5, color: Colors.grey)),
                    ],
                  ),
                  Switch(
                    value: _isAnonymous,
                    activeThumbColor: wowinGreen,
                    activeTrackColor: wowinGreen.withValues(alpha: 0.4),
                    onChanged: (val) => setState(() => _isAnonymous = val),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 24),

            // --- SUBMIT BUTTON ---
            SizedBox(
              width: double.infinity,
              height: 50,
              child: ElevatedButton(
                onPressed: _isSubmitting ? null : _submitReview,
                style: ElevatedButton.styleFrom(
                  backgroundColor: wowinGreen,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                  elevation: 0,
                ),
                child: _isSubmitting
                    ? const SizedBox(width: 24, height: 24, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5))
                    : const Text(
                  'Kirim Penilaian',
                  style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: Colors.white),
                ),
              ),
            ),

            const SizedBox(height: 40),
          ],
        ),
      ),
    );
  }
}
