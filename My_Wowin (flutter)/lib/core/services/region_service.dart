import 'dart:convert';
import 'package:http/http.dart' as http;
import '../data/indonesia_regions.dart';

class RegionItem {
  final String id;
  final String name;

  RegionItem({required this.id, required this.name});

  factory RegionItem.fromJson(Map<String, dynamic> json) {
    return RegionItem(
      id: json['id']?.toString() ?? '',
      name: json['name']?.toString() ?? '',
    );
  }

  String get formattedName => IndonesiaRegions.formatRegionName(name);

  @override
  String toString() => formattedName;
}

class RegionService {
  static final RegionService _instance = RegionService._internal();
  factory RegionService() => _instance;
  RegionService._internal();

  static const String primaryHostingerBase = 'https://mywowin.com/api/regions';
  static const String fallbackEmsifaBase = 'https://www.emsifa.com/api-wilayah-indonesia/api';

  final Map<String, List<RegionItem>> _regencyCache = {};
  final Map<String, List<RegionItem>> _districtCache = {};
  final Map<String, List<RegionItem>> _villageCache = {};
  List<RegionItem>? _provinceCache;

  /// Ambil daftar Provinsi
  Future<List<RegionItem>> getProvinces() async {
    if (_provinceCache != null && _provinceCache!.isNotEmpty) {
      return _provinceCache!;
    }

    try {
      final res = await http.get(Uri.parse('$primaryHostingerBase/provinces')).timeout(const Duration(seconds: 4));
      if (res.statusCode == 200) {
        final List list = json.decode(res.body);
        _provinceCache = list.map((e) => RegionItem.fromJson(e)).toList();
        return _provinceCache!;
      }
    } catch (_) {}

    try {
      final res = await http.get(Uri.parse('$fallbackEmsifaBase/provinces.json')).timeout(const Duration(seconds: 4));
      if (res.statusCode == 200) {
        final List list = json.decode(res.body);
        _provinceCache = list.map((e) => RegionItem.fromJson(e)).toList();
        return _provinceCache!;
      }
    } catch (_) {}

    // Fallback lokal jika jaringan offline
    _provinceCache = IndonesiaRegions.provinces.map((p) {
      final id = IndonesiaRegions.provinceIds[p] ?? '35';
      return RegionItem(id: id, name: p);
    }).toList();

    return _provinceCache!;
  }

  /// Ambil daftar Kabupaten/Kota berdasarkan ID Provinsi
  Future<List<RegionItem>> getRegencies(String provinceId) async {
    if (_regencyCache.containsKey(provinceId)) {
      return _regencyCache[provinceId]!;
    }

    try {
      final res = await http.get(Uri.parse('$primaryHostingerBase/regencies/$provinceId')).timeout(const Duration(seconds: 5));
      if (res.statusCode == 200) {
        final List list = json.decode(res.body);
        final items = list.map((e) => RegionItem.fromJson(e)).toList();
        _regencyCache[provinceId] = items;
        return items;
      }
    } catch (_) {}

    try {
      final res = await http.get(Uri.parse('$fallbackEmsifaBase/regencies/$provinceId.json')).timeout(const Duration(seconds: 5));
      if (res.statusCode == 200) {
        final List list = json.decode(res.body);
        final items = list.map((e) => RegionItem.fromJson(e)).toList();
        _regencyCache[provinceId] = items;
        return items;
      }
    } catch (_) {}

    return [];
  }

  /// Ambil daftar Kecamatan berdasarkan ID Kabupaten/Kota
  Future<List<RegionItem>> getDistricts(String regencyId) async {
    if (_districtCache.containsKey(regencyId)) {
      return _districtCache[regencyId]!;
    }

    try {
      final res = await http.get(Uri.parse('$primaryHostingerBase/districts/$regencyId')).timeout(const Duration(seconds: 5));
      if (res.statusCode == 200) {
        final List list = json.decode(res.body);
        final items = list.map((e) => RegionItem.fromJson(e)).toList();
        _districtCache[regencyId] = items;
        return items;
      }
    } catch (_) {}

    try {
      final res = await http.get(Uri.parse('$fallbackEmsifaBase/districts/$regencyId.json')).timeout(const Duration(seconds: 5));
      if (res.statusCode == 200) {
        final List list = json.decode(res.body);
        final items = list.map((e) => RegionItem.fromJson(e)).toList();
        _districtCache[regencyId] = items;
        return items;
      }
    } catch (_) {}

    return [];
  }

  /// Ambil daftar Desa/Kelurahan berdasarkan ID Kecamatan
  Future<List<RegionItem>> getVillages(String districtId) async {
    if (_villageCache.containsKey(districtId)) {
      return _villageCache[districtId]!;
    }

    try {
      final res = await http.get(Uri.parse('$primaryHostingerBase/villages/$districtId')).timeout(const Duration(seconds: 5));
      if (res.statusCode == 200) {
        final List list = json.decode(res.body);
        final items = list.map((e) => RegionItem.fromJson(e)).toList();
        _villageCache[districtId] = items;
        return items;
      }
    } catch (_) {}

    try {
      final res = await http.get(Uri.parse('$fallbackEmsifaBase/villages/$districtId.json')).timeout(const Duration(seconds: 5));
      if (res.statusCode == 200) {
        final List list = json.decode(res.body);
        final items = list.map((e) => RegionItem.fromJson(e)).toList();
        _villageCache[districtId] = items;
        return items;
      }
    } catch (_) {}

    return [];
  }
}
