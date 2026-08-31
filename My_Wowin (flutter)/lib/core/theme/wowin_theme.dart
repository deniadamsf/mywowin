import 'dart:ui';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

/// ==============================================================================
/// MY WOWIN FOOD - PREMIUM BRAND DESIGN SYSTEM & THEME
/// ==============================================================================
/// Sistem desain visual eksklusif untuk PT Wowin Purnomo Putera (My Wowin).
/// Menggabungkan Royal Emerald Green, Vivid Jade, Warm Gold/Amber,
/// serta komponen Glassmorphism (Frosted Glass) & Animasi interaktif modern.
/// ==============================================================================

class WowinColors {
  // Palet Hijau Asli Panduan UI My Wowin
  static const Color primaryDark = Color(0xFF0A4A1A);      // Hijau Gelap Asli
  static const Color primary = Color(0xFF1B5E20);          // Wowin Green Standar
  static const Color primaryLight = Color(0xFF2E7D32);     // Hijau Terang Asli
  static const Color accentMint = Color(0xFF4CAF50);       // Hijau Muda Aksen

  // Palet Aksen Emas & Amber (Reward, Koin, Poin Mitra)
  static const Color gold = Color(0xFFF59E0B);             // Rich Amber Gold
  static const Color goldLight = Color(0xFFFDE68A);        // Soft Champagne Gold
  static const Color goldDark = Color(0xFFB45309);         // Deep Warm Gold

  // Palet Promo & Aksen Diskon
  static const Color promoRed = Color(0xFFD32F2F);         // Merah Promo
  static const Color promoRedSoft = Color(0xFFFFEBEE);     // Background Lembut Promo

  // Neutral & Surface Modern
  static const Color background = Color(0xFFF8FAFC);       // Soft Canvas
  static const Color surface = Color(0xFFFFFFFF);          // Card Pure White
  static const Color textPrimary = Color(0xFF0F172A);      // Obsidian Black
  static const Color textSecondary = Color(0xFF64748B);    // Slate Grey
  static const Color textMuted = Color(0xFF94A3B8);        // Light Slate
  static const Color border = Color(0xFFE2E8F0);           // Fine Hairline
}

class WowinGradients {
  // Gradasi Utama Header & Hero Sesuai Panduan UI Asli My Wowin
  static const LinearGradient royalEmerald = LinearGradient(
    colors: [
      Color(0xFF0A4A1A),
      Color(0xFF2E7D32),
    ],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  // Gradasi Kartu Poin Emas
  static const LinearGradient goldBadge = LinearGradient(
    colors: [
      Color(0xFFF59E0B),
      Color(0xFFD97706),
    ],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  // Gradasi Kartu Promo Super Deal
  static const LinearGradient superDeal = LinearGradient(
    colors: [
      Color(0xFFBE123C),
      Color(0xFFE11D48),
      Color(0xFFFB7185),
    ],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  // Gradasi Frosted Translucent (Untuk overlay glass)
  static const LinearGradient glassOverlay = LinearGradient(
    colors: [
      Color(0x33FFFFFF),
      Color(0x0DFFFFFF),
    ],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );
}

/// Alias konstanta gradasi untuk kompatibilitas ke seluruh screen
const LinearGradient wowinGradient = WowinGradients.royalEmerald;

/// ==============================================================================
/// STANDAR TIPOGRAFI OUTFIT (UI/UX PRO MAX SCALE)
/// ==============================================================================
class WowinTypography {
  static TextStyle get headerTitle => GoogleFonts.outfit(
    color: Colors.white,
    fontSize: 16.5,
    fontWeight: FontWeight.w700,
    letterSpacing: -0.2,
  );

  static TextStyle get heroTitle => GoogleFonts.outfit(
    color: Colors.white,
    fontSize: 18.0,
    fontWeight: FontWeight.w700,
    letterSpacing: -0.3,
  );

  static TextStyle get heroSubtitle => GoogleFonts.outfit(
    color: Colors.white.withValues(alpha: 0.9),
    fontSize: 12.5,
    fontWeight: FontWeight.w400,
    height: 1.4,
  );

  static TextStyle get sectionTitle => GoogleFonts.outfit(
    color: WowinColors.textPrimary,
    fontSize: 15.5,
    fontWeight: FontWeight.w700,
    letterSpacing: -0.2,
  );
}

/// ==============================================================================
/// STANDAR APP BAR MY WOWIN (PRESISI & SERAGAM)
/// ==============================================================================
class WowinAppBar {
  static PreferredSizeWidget standard({
    required String title,
    List<Widget>? actions,
    Widget? leading,
    bool automaticallyImplyLeading = true,
    PreferredSizeWidget? bottom,
    double elevation = 0,
  }) {
    return AppBar(
      elevation: elevation,
      scrolledUnderElevation: 0,
      backgroundColor: WowinColors.primaryDark,
      automaticallyImplyLeading: automaticallyImplyLeading,
      leading: leading,
      centerTitle: false,
      iconTheme: const IconThemeData(color: Colors.white, size: 20),
      flexibleSpace: Container(
        decoration: const BoxDecoration(
          gradient: WowinGradients.royalEmerald,
        ),
      ),
      title: Text(
        title,
        style: WowinTypography.headerTitle,
      ),
      actions: actions,
      bottom: bottom,
    );
  }
}

/// ==============================================================================
/// KOMPONEN GLASSMORPHISM (FROSTED GLASS CONTAINER)
/// ==============================================================================
class WowinGlassCard extends StatelessWidget {
  final Widget child;
  final double borderRadius;
  final EdgeInsetsGeometry? padding;
  final EdgeInsetsGeometry? margin;
  final double blurSigma;
  final Color? backgroundColor;
  final Border? border;
  final List<BoxShadow>? shadows;
  final VoidCallback? onTap;

  const WowinGlassCard({
    super.key,
    required this.child,
    this.borderRadius = 20,
    this.padding = const EdgeInsets.all(16),
    this.margin,
    this.blurSigma = 16,
    this.backgroundColor,
    this.border,
    this.shadows,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    Widget content = Container(
      margin: margin,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(borderRadius),
        boxShadow: shadows ?? [
          BoxShadow(
            color: const Color(0xFF064E3B).withValues(alpha: 0.08),
            blurRadius: 20,
            offset: const Offset(0, 8),
          )
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(borderRadius),
        child: BackdropFilter(
          filter: ImageFilter.blur(sigmaX: blurSigma, sigmaY: blurSigma),
          child: Container(
            padding: padding,
            decoration: BoxDecoration(
              color: backgroundColor ?? Colors.white.withValues(alpha: 0.85),
              borderRadius: BorderRadius.circular(borderRadius),
              border: border ?? Border.all(
                color: Colors.white.withValues(alpha: 0.6),
                width: 1.2,
              ),
            ),
            child: child,
          ),
        ),
      ),
    );

    if (onTap != null) {
      return Material(
        color: Colors.transparent,
        child: InkWell(
          onTap: onTap,
          borderRadius: BorderRadius.circular(borderRadius),
          child: content,
        ),
      );
    }

    return content;
  }
}

/// ==============================================================================
/// KOMPONEN ANIMASI ANGKA POIN (ANIMATED ROLLING COUNTER)
/// ==============================================================================
class WowinAnimatedCounter extends StatelessWidget {
  final int targetValue;
  final TextStyle? style;
  final Duration duration;

  const WowinAnimatedCounter({
    super.key,
    required this.targetValue,
    this.style,
    this.duration = const Duration(milliseconds: 1200),
  });

  @override
  Widget build(BuildContext context) {
    return TweenAnimationBuilder<double>(
      tween: Tween<double>(begin: 0, end: targetValue.toDouble()),
      duration: duration,
      curve: Curves.easeOutExpo,
      builder: (context, value, child) {
        final int currentInt = value.round();
        // Format pemisah ribuan
        final formatted = currentInt.toString().replaceAllMapped(
          RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'),
          (Match m) => '${m[1]}.',
        );

        return Text(
          formatted,
          style: style ?? const TextStyle(
            fontSize: 36,
            fontWeight: FontWeight.bold,
            color: Colors.white,
            letterSpacing: -0.5,
          ),
        );
      },
    );
  }
}
