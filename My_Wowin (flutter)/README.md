# My Wowin — Aplikasi Mobile

Aplikasi e-commerce dan membership Flutter untuk PT Wowin Purnomo Putera.

## Tech Stack

- **Framework:** Flutter (Dart ^3.12.2)
- **State Management:** Riverpod
- **Backend:** REST API Laravel (Sanctum auth)
- **Push Notification:** Firebase Cloud Messaging
- **Cache:** cached_network_image, SharedPreferences

## Fitur

- Katalog produk (reguler & promo bundling)
- Keranjang belanja dengan mode offline
- Checkout multi-metode (Transfer, COD, WhatsApp)
- Poin reward & login streak harian
- Live chat dengan CS/Admin
- Kemitraan VIP (Bronze → Diamond)
- Registrasi & verifikasi OTP email

## Arsitektur

```
lib/
├── main.dart
├── api_service.dart
├── core/
│   ├── constants/
│   ├── data/
│   ├── network/
│   ├── services/       # CacheService, ConnectivityService
│   ├── theme/
│   ├── utils/
│   └── widgets/        # WowinCachedImage, OfflineBanner
└── features/
    ├── auth/
    ├── cart/
    ├── catalog/
    ├── chat/
    ├── order/
    └── splash/
```

## Setup

```bash
# Install dependencies
flutter pub get

# Jalankan di emulator / device
flutter run

# Build APK release
flutter build apk --release

# Build AAB (untuk Play Store)
flutter build appbundle --release
```

## Konfigurasi

- API base URL: lihat `lib/core/constants/`
- Firebase: konfigurasi `google-services.json` (Android) dan `GoogleService-Info.plist` (iOS)
- App icon: `assets/app_icon.png` — generate dengan `flutter pub run flutter_launcher_icons`
- Splash screen: `assets/logo_full.png` — generate dengan `flutter pub run flutter_native_splash:create`

## Versi

Saat ini: **1.0.5+6**
