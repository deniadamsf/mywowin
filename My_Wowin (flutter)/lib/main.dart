import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'features/splash/screens/splash_screen.dart';
// WAJIB IMPORT HALAMAN CHAT-NYA DI SINI:
import 'features/chat/screens/live_chat_screen.dart';

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

class MyApp extends StatefulWidget {
  const MyApp({super.key});

  @override
  State<MyApp> createState() => _MyAppState();
}

class _MyAppState extends State<MyApp> {
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
    }

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
      if (message.notification != null) {
        // Tampilkan Snackbar yang BISA DIKLIK tombol "Buka"-nya
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('${message.notification?.title}: ${message.notification?.body}'),
            backgroundColor: Colors.green.shade800,
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
      // --- 2. PASANG KUNCI MASTERNYA DI SINI ---
      navigatorKey: navigatorKey,
      title: 'Wowin Food',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        primarySwatch: Colors.green,
        scaffoldBackgroundColor: Colors.grey[50],
      ),
      home: const SplashScreen(),
    );
  }
}