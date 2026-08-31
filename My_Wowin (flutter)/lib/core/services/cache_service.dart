import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:shared_preferences/shared_preferences.dart';

/// Service untuk menangani penyimpanan lokal data aplikasi (Offline-First / Cache-First)
class CacheService {
  static const String _keyCatalog = 'offline_cache_catalog';
  static const String _keyHeroes = 'offline_cache_heroes';
  static const String _keyBundlings = 'offline_cache_bundlings';
  static const String _keyCategories = 'offline_cache_categories';
  static const String _keyCartItems = 'offline_cache_cart_items';
  static const String _keyCartSubtotal = 'offline_cache_cart_subtotal';
  static const String _keyUserProfile = 'offline_cache_user_profile';
  static const String _keyLastSync = 'offline_cache_last_sync';

  static SharedPreferences? _prefs;

  static Future<SharedPreferences> _getPrefs() async {
    _prefs ??= await SharedPreferences.getInstance();
    return _prefs!;
  }

  // ===========================================================================
  // 1. KATALOG UTAMA (HERO, CATEGORIES, PRODUCTS)
  // ===========================================================================

  static Future<void> saveCatalog(Map<String, dynamic> data) async {
    try {
      final prefs = await _getPrefs();
      await prefs.setString(_keyCatalog, json.encode(data));
      await prefs.setString(_keyLastSync, DateTime.now().toIso8601String());
    } catch (e) {
      debugPrint('Error CacheService saveCatalog: $e');
    }
  }

  static Future<Map<String, dynamic>?> getCatalog() async {
    try {
      final prefs = await _getPrefs();
      final String? jsonString = prefs.getString(_keyCatalog);
      if (jsonString != null && jsonString.isNotEmpty) {
        return json.decode(jsonString) as Map<String, dynamic>;
      }
    } catch (e) {
      debugPrint('Error CacheService getCatalog: $e');
    }
    return null;
  }

  // ===========================================================================
  // 2. HERO BANNER & BUNDLING & KATEGORI
  // ===========================================================================

  static Future<void> saveHeroes(List<dynamic> heroes) async {
    try {
      final prefs = await _getPrefs();
      await prefs.setString(_keyHeroes, json.encode(heroes));
    } catch (e) {
      debugPrint('Error CacheService saveHeroes: $e');
    }
  }

  static Future<List<dynamic>?> getHeroes() async {
    try {
      final prefs = await _getPrefs();
      final String? jsonString = prefs.getString(_keyHeroes);
      if (jsonString != null && jsonString.isNotEmpty) {
        return json.decode(jsonString) as List<dynamic>;
      }
    } catch (e) {
      debugPrint('Error CacheService getHeroes: $e');
    }
    return null;
  }

  static Future<void> saveBundlings(List<dynamic> bundlings) async {
    try {
      final prefs = await _getPrefs();
      await prefs.setString(_keyBundlings, json.encode(bundlings));
    } catch (e) {
      debugPrint('Error CacheService saveBundlings: $e');
    }
  }

  static Future<List<dynamic>?> getBundlings() async {
    try {
      final prefs = await _getPrefs();
      final String? jsonString = prefs.getString(_keyBundlings);
      if (jsonString != null && jsonString.isNotEmpty) {
        return json.decode(jsonString) as List<dynamic>;
      }
    } catch (e) {
      debugPrint('Error CacheService getBundlings: $e');
    }
    return null;
  }

  static Future<void> saveCategories(List<dynamic> categories) async {
    try {
      final prefs = await _getPrefs();
      await prefs.setString(_keyCategories, json.encode(categories));
    } catch (e) {
      debugPrint('Error CacheService saveCategories: $e');
    }
  }

  static Future<List<dynamic>?> getCategories() async {
    try {
      final prefs = await _getPrefs();
      final String? jsonString = prefs.getString(_keyCategories);
      if (jsonString != null && jsonString.isNotEmpty) {
        return json.decode(jsonString) as List<dynamic>;
      }
    } catch (e) {
      debugPrint('Error CacheService getCategories: $e');
    }
    return null;
  }

  // ===========================================================================
  // 3. KERANJANG BELANJA OFFLINE (OFFLINE CART)
  // ===========================================================================

  static Future<void> saveOfflineCart(List<dynamic> items, double subtotal) async {
    try {
      final prefs = await _getPrefs();
      await prefs.setString(_keyCartItems, json.encode(items));
      await prefs.setDouble(_keyCartSubtotal, subtotal);
    } catch (e) {
      debugPrint('Error CacheService saveOfflineCart: $e');
    }
  }

  static Future<Map<String, dynamic>> getOfflineCart() async {
    try {
      final prefs = await _getPrefs();
      final String? jsonString = prefs.getString(_keyCartItems);
      final double subtotal = prefs.getDouble(_keyCartSubtotal) ?? 0.0;
      if (jsonString != null && jsonString.isNotEmpty) {
        final List<dynamic> items = json.decode(jsonString) as List<dynamic>;
        return {'items': items, 'subtotal': subtotal};
      }
    } catch (e) {
      debugPrint('Error CacheService getOfflineCart: $e');
    }
    return {'items': [], 'subtotal': 0.0};
  }

  static Future<void> clearOfflineCart() async {
    try {
      final prefs = await _getPrefs();
      await prefs.remove(_keyCartItems);
      await prefs.remove(_keyCartSubtotal);
    } catch (e) {
      debugPrint('Error CacheService clearOfflineCart: $e');
    }
  }

  // ===========================================================================
  // 4. PROFIL USER & SALDO POIN OFFLINE
  // ===========================================================================

  static Future<void> saveUserProfile(Map<String, dynamic> profile) async {
    try {
      final prefs = await _getPrefs();
      await prefs.setString(_keyUserProfile, json.encode(profile));
    } catch (e) {
      debugPrint('Error CacheService saveUserProfile: $e');
    }
  }

  static Future<Map<String, dynamic>?> getUserProfile() async {
    try {
      final prefs = await _getPrefs();
      final String? jsonString = prefs.getString(_keyUserProfile);
      if (jsonString != null && jsonString.isNotEmpty) {
        return json.decode(jsonString) as Map<String, dynamic>;
      }
    } catch (e) {
      debugPrint('Error CacheService getUserProfile: $e');
    }
    return null;
  }

  // ===========================================================================
  // 5. TIMESTAMP & UTILITAS
  // ===========================================================================

  static Future<DateTime?> getLastSyncTime() async {
    try {
      final prefs = await _getPrefs();
      final String? iso = prefs.getString(_keyLastSync);
      if (iso != null) return DateTime.tryParse(iso);
    } catch (_) {}
    return null;
  }

  static Future<void> clearAllCache() async {
    try {
      final prefs = await _getPrefs();
      await prefs.remove(_keyCatalog);
      await prefs.remove(_keyHeroes);
      await prefs.remove(_keyBundlings);
      await prefs.remove(_keyCategories);
      await prefs.remove(_keyCartItems);
      await prefs.remove(_keyCartSubtotal);
      await prefs.remove(_keyUserProfile);
      await prefs.remove(_keyLastSync);
    } catch (e) {
      debugPrint('Error CacheService clearAllCache: $e');
    }
  }
}
