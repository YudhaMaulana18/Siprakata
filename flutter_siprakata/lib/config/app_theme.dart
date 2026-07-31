import 'package:flutter/material.dart';

/// Soft, layered "modern elevated" card shadow — tinted with the brand
/// color instead of flat black for a livelier, less corporate feel.
BoxDecoration cardDecoration({double radius = 20, bool elevated = false}) => BoxDecoration(
  color: AppColors.bgCard,
  borderRadius: BorderRadius.circular(radius),
  border: Border.all(color: AppColors.border),
  boxShadow: elevated
      ? [
          BoxShadow(color: AppColors.primary.withValues(alpha: 0.10), blurRadius: 28, offset: const Offset(0, 12), spreadRadius: -6),
          BoxShadow(color: AppColors.primary.withValues(alpha: 0.05), blurRadius: 8, offset: const Offset(0, 2)),
        ]
      : [
          BoxShadow(color: AppColors.primary.withValues(alpha: 0.06), blurRadius: 16, offset: const Offset(0, 4), spreadRadius: -4),
          BoxShadow(color: const Color(0xFF0f172a).withValues(alpha: 0.03), blurRadius: 4, offset: const Offset(0, 1)),
        ],
);

/// Glassy translucent decoration for bars/overlays over colored backgrounds.
BoxDecoration glassDecoration({double radius = 20, Color tint = Colors.white}) => BoxDecoration(
  color: tint.withValues(alpha: 0.10),
  borderRadius: BorderRadius.circular(radius),
  border: Border.all(color: tint.withValues(alpha: 0.18)),
);

class AppColors {
  AppColors._();

  // Core brand — vibrant indigo/violet replaces the old flat navy.
  static const Color primary = Color(0xFF4338CA);
  static const Color primaryLight = Color(0xFF6366F1);
  static const Color primaryLighter = Color(0xFF818CF8);
  static const Color primaryDark = Color(0xFF1e1b4b);

  // Accent — warm amber, brightened for more energy.
  static const Color accent = Color(0xFFF59E0B);
  static const Color accentLight = Color(0xFFFCD34D);

  // Secondary vibrant accent used for extra pops of color (badges, CTAs).
  static const Color accent2 = Color(0xFFEC4899);
  static const Color accent2Light = Color(0xFFF9A8D4);

  static const Color bgBody = Color(0xFFF3F4FB);
  static const Color bgCard = Color(0xFFFFFFFF);
  static const Color textDark = Color(0xFF1E1B2E);
  static const Color textMuted = Color(0xFF64748b);
  static const Color textLight = Color(0xFF94a3b8);
  static const Color border = Color(0xFFE7E8F3);

  static const List<Color> statMahasiswa = [Color(0xFF4338CA), Color(0xFF6366F1)];
  static const List<Color> statDosen = [Color(0xFFea580c), Color(0xFFfb923c)];
  static const List<Color> statMatakuliah = [Color(0xFF4338CA), Color(0xFF818CF8)];
  static const List<Color> statKrs = [Color(0xFF7c3aed), Color(0xFFc084fc)];
  static const List<Color> statSks = [Color(0xFF0891b2), Color(0xFF67e8f9)];
  static const List<Color> statJadwal = [Color(0xFF0369a1), Color(0xFF38bdf8)];
  static const List<Color> statPresensi = [Color(0xFFe11d48), Color(0xFFfb7185)];
  static const List<Color> statNilai = [Color(0xFF2563eb), Color(0xFF60a5fa)];
  static const List<Color> statMateri = [Color(0xFF059669), Color(0xFF34d399)];
  static const List<Color> statPengumuman = [Color(0xFFc026d3), Color(0xFFe879f9)];
  static const List<Color> statKelayakan = [Color(0xFF4338CA), Color(0xFF818CF8)];
  static const List<Color> statProdi = [Color(0xFFea580c), Color(0xFFfb923c)];
  static const List<Color> statRuangan = [Color(0xFF4338ca), Color(0xFF818cf8)];
  static const List<Color> statTahunAjaran = [Color(0xFF0e7490), Color(0xFF22d3ee)];

  static const Color green = Color(0xFF10b981);
  static const Color success = Color(0xFF10b981);
  static const Color warning = Color(0xFFf59e0b);
  static const Color danger = Color(0xFFef4444);
  static const Color info = Color(0xFF06b6d4);
}

/// Reusable brand gradients so screens can opt into the livelier look
/// without hard-coding hex values.
class AppGradients {
  AppGradients._();

  static const LinearGradient primary = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [AppColors.primary, AppColors.primaryLight],
  );

  static const LinearGradient hero = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [Color(0xFF312e81), Color(0xFF4338CA), Color(0xFF6d28d9)],
  );

  static const LinearGradient accent = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [AppColors.accent, AppColors.accent2],
  );
}

/// Standard motion constants so animations feel consistent across screens.
class AppMotion {
  AppMotion._();
  static const Duration fast = Duration(milliseconds: 180);
  static const Duration normal = Duration(milliseconds: 320);
  static const Duration slow = Duration(milliseconds: 560);
  static const Curve curve = Curves.easeOutCubic;
}

class AppTheme {
  AppTheme._();

  static ThemeData get theme => ThemeData(
        useMaterial3: true,
        fontFamily: 'Inter',
        brightness: Brightness.light,
        scaffoldBackgroundColor: AppColors.bgBody,
        colorScheme: ColorScheme.fromSeed(
          seedColor: AppColors.primary,
          primary: AppColors.primary,
          secondary: AppColors.accent,
          surface: AppColors.bgCard,
          brightness: Brightness.light,
        ),
        appBarTheme: const AppBarTheme(
          backgroundColor: Colors.white,
          foregroundColor: AppColors.primary,
          centerTitle: true,
          elevation: 0,
          scrolledUnderElevation: 1,
          titleTextStyle: TextStyle(
            fontFamily: 'Inter',
            fontSize: 16,
            fontWeight: FontWeight.w700,
            color: AppColors.primary,
          ),
        ),
        cardTheme: CardThemeData(
          color: AppColors.bgCard,
          elevation: 0,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(18),
            side: const BorderSide(color: AppColors.border, width: 1),
          ),
          margin: const EdgeInsets.only(bottom: 10),
        ),
        navigationBarTheme: NavigationBarThemeData(
          backgroundColor: Colors.white,
          elevation: 8,
          indicatorColor: AppColors.primary.withValues(alpha: 0.12),
          labelTextStyle: WidgetStateProperty.resolveWith((states) {
            if (states.contains(WidgetState.selected)) {
              return const TextStyle(
                color: AppColors.primary,
                fontWeight: FontWeight.w600,
                fontSize: 12,
              );
            }
            return const TextStyle(
              color: AppColors.textMuted,
              fontSize: 12,
            );
          }),
        ),
        elevatedButtonTheme: ElevatedButtonThemeData(
          style: ElevatedButton.styleFrom(
            backgroundColor: AppColors.primary,
            foregroundColor: Colors.white,
            elevation: 0,
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(12),
            ),
            textStyle: const TextStyle(
              fontFamily: 'Inter',
              fontWeight: FontWeight.w600,
            ),
          ),
        ),
        outlinedButtonTheme: OutlinedButtonThemeData(
          style: OutlinedButton.styleFrom(
            foregroundColor: AppColors.primary,
            side: const BorderSide(color: AppColors.primary),
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(12),
            ),
          ),
        ),
        inputDecorationTheme: InputDecorationTheme(
          filled: true,
          fillColor: const Color(0xFFF7F7FD),
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: const BorderSide(color: AppColors.border),
          ),
          enabledBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: const BorderSide(color: AppColors.border),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: const BorderSide(color: AppColors.primaryLight, width: 2),
          ),
          contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          labelStyle: const TextStyle(color: AppColors.textMuted, fontSize: 14),
          hintStyle: const TextStyle(color: AppColors.textLight, fontSize: 14),
        ),
        dividerTheme: const DividerThemeData(
          color: AppColors.border,
          thickness: 1,
        ),
      );
}