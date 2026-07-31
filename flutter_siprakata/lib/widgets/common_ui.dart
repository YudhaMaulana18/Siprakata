import 'package:flutter/material.dart';
import '../config/app_theme.dart';

/// Branded empty-state: icon sits inside a soft gradient badge instead of
/// a bare grey icon, with a gentle fade/scale-in so it doesn't just pop.
class AppEmptyState extends StatefulWidget {
  final IconData icon;
  final String title;
  final String? subtitle;
  const AppEmptyState({super.key, required this.icon, required this.title, this.subtitle});

  @override
  State<AppEmptyState> createState() => _AppEmptyStateState();
}

class _AppEmptyStateState extends State<AppEmptyState> {
  bool _visible = false;

  @override
  void initState() {
    super.initState();
    Future.delayed(const Duration(milliseconds: 40), () {
      if (mounted) setState(() => _visible = true);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Center(
      child: AnimatedOpacity(
        opacity: _visible ? 1 : 0,
        duration: AppMotion.normal,
        child: AnimatedScale(
          scale: _visible ? 1 : 0.9,
          duration: AppMotion.normal,
          curve: AppMotion.curve,
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Container(
                width: 96, height: 96,
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    colors: [AppColors.primary.withValues(alpha: 0.08), AppColors.accent2.withValues(alpha: 0.08)],
                  ),
                  shape: BoxShape.circle,
                ),
                child: Icon(widget.icon, size: 40, color: AppColors.primaryLight),
              ),
              const SizedBox(height: 18),
              Text(widget.title, style: const TextStyle(color: AppColors.textDark, fontSize: 15, fontWeight: FontWeight.w600)),
              if (widget.subtitle != null) ...[
                const SizedBox(height: 6),
                Text(widget.subtitle!, style: const TextStyle(color: AppColors.textMuted, fontSize: 13)),
              ],
            ],
          ),
        ),
      ),
    );
  }
}

/// Branded loading indicator so every screen doesn't just show a bare spinner.
class AppLoadingState extends StatelessWidget {
  final String? label;
  const AppLoadingState({super.key, this.label});

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          SizedBox(
            width: 40, height: 40,
            child: CircularProgressIndicator(strokeWidth: 3, color: AppColors.primary),
          ),
          if (label != null) ...[
            const SizedBox(height: 14),
            Text(label!, style: const TextStyle(color: AppColors.textMuted, fontSize: 13)),
          ],
        ],
      ),
    );
  }
}

/// Section header with a gradient icon badge, replacing the old flat
/// tinted-square icon used across every list screen.
class AppSectionHeader extends StatelessWidget {
  final IconData icon;
  final String title;
  final Widget? action;
  final List<Color>? gradient;
  const AppSectionHeader({super.key, required this.icon, required this.title, this.action, this.gradient});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
      decoration: const BoxDecoration(
        border: Border(bottom: BorderSide(color: AppColors.border)),
      ),
      child: Row(
        children: [
          Container(
            width: 32, height: 32,
            decoration: BoxDecoration(
              gradient: LinearGradient(colors: gradient ?? [AppColors.primary, AppColors.primaryLight]),
              borderRadius: BorderRadius.circular(10),
              boxShadow: [
                BoxShadow(color: (gradient?.first ?? AppColors.primary).withValues(alpha: 0.28), blurRadius: 10, offset: const Offset(0, 4)),
              ],
            ),
            child: Icon(icon, size: 16, color: Colors.white),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Text(title, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 14, color: AppColors.primary)),
          ),
          if (action != null) action!,
        ],
      ),
    );
  }
}

/// Soft rounded status/category pill used for grades, attendance, priority, etc.
class AppStatusPill extends StatelessWidget {
  final String label;
  final Color color;
  const AppStatusPill({super.key, required this.label, required this.color});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 4),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.12),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: color.withValues(alpha: 0.25)),
      ),
      child: Text(label, style: TextStyle(fontWeight: FontWeight.w700, color: color, fontSize: 11)),
    );
  }
}

/// Fades + slides content up into place; wrap list cards / table containers
/// with this so screens feel like they "settle in" rather than pop flat.
class AppFadeIn extends StatefulWidget {
  final Widget child;
  final int index;
  const AppFadeIn({super.key, required this.child, this.index = 0});

  @override
  State<AppFadeIn> createState() => _AppFadeInState();
}

class _AppFadeInState extends State<AppFadeIn> {
  bool _visible = false;

  @override
  void initState() {
    super.initState();
    Future.delayed(Duration(milliseconds: 50 + 50 * widget.index), () {
      if (mounted) setState(() => _visible = true);
    });
  }

  @override
  Widget build(BuildContext context) {
    return AnimatedOpacity(
      opacity: _visible ? 1 : 0,
      duration: AppMotion.normal,
      curve: AppMotion.curve,
      child: AnimatedSlide(
        offset: _visible ? Offset.zero : const Offset(0, 0.05),
        duration: AppMotion.normal,
        curve: AppMotion.curve,
        child: widget.child,
      ),
    );
  }
}

/// Zebra-striped row background for DataTable rows so wide tables are
/// easier to scan — replaces flat solid-white rows.
Color? zebraRowColor(int index) =>
    index.isOdd ? AppColors.bgBody.withValues(alpha: 0.5) : null;