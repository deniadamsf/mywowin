import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'core/constants/api_constants.dart';
import 'features/chat/providers/chat_provider.dart';
import 'features/splash/screens/splash_screen.dart';
// WAJIB IMPORT HALAMAN CHAT-NYA DI SINI:
import 'features/chat/screens/live_chat_screen.dart';

import 'core/theme/wowin_theme.dart';

// --- 1. BUAT KUNCI MASTER NAVIGASI ---
final GlobalKey<NavigatorState> navigatorKey = GlobalKey<NavigatorState>();

Future<void> _firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  await Firebase.initializeApp();
  debugPrint("Sinyal Background Diterima: ${message.messageId}");
}

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await Firebase.initializeApp();
  FirebaseMessaging.onBackgroundMessage(_firebaseMessagingBackgroundHandler);

  runApp(
    const ProviderScope(
      child: MyApp(),
    ),
  );
}

class MyApp extends ConsumerStatefulWidget {
  const MyApp({super.key});

  @override
  ConsumerState<MyApp> createState() => _MyAppState();
}

class _MyAppState extends ConsumerState<MyApp> {
  @override
  void initState() {
    super.initState();
    _setupFirebaseMessaging();
  }

  // Helper untuk lompat ke halaman chat
  void _navigateToChat() {
    if (navigatorKey.currentState != null) {
      navigatorKey.currentState!.push(
        MaterialPageRoute(builder: (context) => const LiveChatScreen()),
      );
    }
  }

  // Kirim FCM Token ke backend agar push notification balasan CS dapat sampai
  Future<void> _syncFcmToken(String token) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('device_fcm_token', token);
      final authToken = prefs.getString('auth_token');
      if (authToken != null && authToken.isNotEmpty) {
        await http.post(
          Uri.parse('$baseUrl/fcm-token'),
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer $authToken',
          },
          body: json.encode({'fcm_token': token}),
        ).timeout(const Duration(seconds: 6));
      }
    } catch (e) {
      debugPrint("Error sinkronisasi FCM Token: $e");
    }
  }

  Future<void> _setupFirebaseMessaging() async {
    FirebaseMessaging messaging = FirebaseMessaging.instance;

    NotificationSettings settings = await messaging.requestPermission(
      alert: true,
      badge: true,
      sound: true,
    );

    if (settings.authorizationStatus == AuthorizationStatus.authorized) {
      String? token = await messaging.getToken();
      debugPrint("=================================");
      debugPrint("FCM TOKEN HP INI: $token");
      debugPrint("=================================");
      if (token != null && token.isNotEmpty) {
        _syncFcmToken(token);
      }
    }

    // Tangani jika ada pergantian token FCM dari Google
    messaging.onTokenRefresh.listen((newToken) {
      if (newToken.isNotEmpty) {
        _syncFcmToken(newToken);
      }
    });

    // --- KONDISI 1: NOTIFIKASI DIKLIK SAAT APLIKASI BERJALAN DI BACKGROUND ---
    FirebaseMessaging.onMessageOpenedApp.listen((RemoteMessage message) {
      _navigateToChat();
    });

    // --- KONDISI 2: NOTIFIKASI DIKLIK SAAT APLIKASI TERTUTUP TOTAL (KILLED) ---
    FirebaseMessaging.instance.getInitialMessage().then((RemoteMessage? message) {
      if (message != null) {
        // Beri jeda 1 detik agar mesin Flutter selesai merender UI awal dulu
        Future.delayed(const Duration(seconds: 1), () {
          _navigateToChat();
        });
      }
    });

    // --- KONDISI 3: PESAN MASUK SAAT APLIKASI SEDANG DIBUKA (FOREGROUND) ---
    FirebaseMessaging.onMessage.listen((RemoteMessage message) {
      // Refresh chat secara langsung jika ada pesan baru
      ref.read(chatProvider.notifier).fetchChats(silent: true);

      if (message.notification != null) {
        if (!mounted) return;
        // Tampilkan Snackbar yang BISA DIKLIK tombol "Buka"-nya
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('${message.notification?.title}: ${message.notification?.body}'),
            backgroundColor: WowinColors.primary,
            duration: const Duration(seconds: 5),
            behavior: SnackBarBehavior.floating,
            action: SnackBarAction(
              label: 'BUKA',
              textColor: Colors.white,
              onPressed: () {
                _navigateToChat();
              },
            ),
          ),
        );
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      navigatorKey: navigatorKey,
      title: 'My Wowin',
      debugShowCheckedModeBanner: false,
      builder: (context, child) {
        final mediaQuery = MediaQuery.of(context);
        final clampedTextScaler = mediaQuery.textScaler.clamp(
          minScaleFactor: 0.85,
          maxScaleFactor: 1.15,
        );
        return MediaQuery(
          data: mediaQuery.copyWith(textScaler: clampedTextScaler),
          child: child!,
        );
      },
      theme: ThemeData(
        useMaterial3: true,
        textTheme: GoogleFonts.outfitTextTheme(),
        colorScheme: ColorScheme.fromSeed(
          seedColor: WowinColors.primary,
          primary: WowinColors.primary,
          surface: WowinColors.surface,
        ),
        scaffoldBackgroundColor: WowinColors.background,
        appBarTheme: AppBarTheme(
          elevation: 0,
          scrolledUnderElevation: 0,
          backgroundColor: WowinColors.primaryDark,
          foregroundColor: Colors.white,
          centerTitle: false,
          iconTheme: const IconThemeData(color: Colors.white, size: 20),
          titleTextStyle: GoogleFonts.outfit(
            color: Colors.white,
            fontSize: 16.5,
            fontWeight: FontWeight.w700,
            letterSpacing: -0.2,
          ),
        ),
      ),
      home: const SplashScreen(),
    );
  }
}