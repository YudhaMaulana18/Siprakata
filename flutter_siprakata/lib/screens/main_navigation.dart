import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../config/app_theme.dart';
import '../providers/auth_provider.dart';
import 'home/home_screen.dart';
import 'jadwal/jadwal_screen.dart';
import 'krs/krs_screen.dart';
import 'krs/validasi_krs_screen.dart';
import 'nilai/nilai_screen.dart';
import 'presensi/presensi_screen.dart';
import 'materi/materi_screen.dart';
import 'pengumuman/pengumuman_screen.dart';
import 'kelayakan/kelayakan_screen.dart';

class MainNavigation extends StatefulWidget {
  const MainNavigation({super.key});

  @override
  State<MainNavigation> createState() => _MainNavigationState();
}

class _MainNavigationState extends State<MainNavigation> {
  int _currentIndex = 0;
  final GlobalKey<ScaffoldState> _scaffoldKey = GlobalKey<ScaffoldState>();

  final List<Widget> _screens = const [
    HomeScreen(),
    JadwalScreen(noScaffold: true),
    KRSScreen(noScaffold: true),
    NilaiScreen(noScaffold: true),
    PresensiScreen(noScaffold: true),
    MateriScreen(noScaffold: true),
    PengumumanScreen(noScaffold: true),
    KelayakanScreen(noScaffold: true),
    ValidasiKRSScreen(noScaffold: true),
  ];

  static const List<String> _pageTitles = [
    'Dashboard',
    'Jadwal Kuliah',
    'Kartu Rencana Studi (KRS)',
    'Nilai',
    'Presensi',
    'Materi Kuliah',
    'Pengumuman',
    'Prediksi Kelayakan',
    'Validasi KRS',
  ];

  static const List<IconData> _pageIcons = [
    Icons.dashboard_rounded,
    Icons.calendar_view_week_rounded,
    Icons.assignment_rounded,
    Icons.grade_rounded,
    Icons.fact_check_rounded,
    Icons.menu_book_rounded,
    Icons.campaign_rounded,
    Icons.analytics_rounded,
    Icons.verified_user_rounded,
  ];

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    final width = MediaQuery.of(context).size.width;
    final isDesktop = width >= 700;

    if (isDesktop) return _buildDesktopLayout(auth);
    return _buildMobileLayout(auth);
  }

  Widget _buildDesktopLayout(AuthProvider auth) {
    return Scaffold(
      key: _scaffoldKey,
      body: Row(
        children: [
          _buildSidebar(auth),
          Expanded(
            child: Column(
              children: [
                _buildTopBar(auth),
                Expanded(child: _screens[_currentIndex]),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildTopBar(AuthProvider auth) {
    return Container(
      height: 64,
      padding: const EdgeInsets.symmetric(horizontal: 8),
      decoration: BoxDecoration(
        color: Colors.white,
        boxShadow: [
          BoxShadow(color: AppColors.primary.withValues(alpha: 0.05), blurRadius: 12, offset: const Offset(0, 2)),
        ],
      ),
      child: Row(
        children: [
          const SizedBox(width: 12),
          Expanded(
            child: Row(
              children: [
                Container(
                  width: 38, height: 38,
                  decoration: BoxDecoration(
                    gradient: AppGradients.primary,
                    borderRadius: BorderRadius.circular(12),
                    boxShadow: [
                      BoxShadow(color: AppColors.primary.withValues(alpha: 0.28), blurRadius: 14, offset: const Offset(0, 6)),
                    ],
                  ),
                  child: Icon(_pageIcons[_currentIndex], size: 18, color: Colors.white),
                ),
                const SizedBox(width: 12),
                Text(_pageTitles[_currentIndex],
                  style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 18, color: AppColors.textDark, letterSpacing: -0.3),
                ),
              ],
            ),
          ),
          Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              Column(
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text(auth.user?.name ?? '',
                    style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600, color: AppColors.textDark)),
                  Text(auth.user?.roleLabel ?? 'Mahasiswa', style: const TextStyle(fontSize: 10, color: AppColors.textMuted)),
                ],
              ),
              const SizedBox(width: 10),
              Container(
                width: 38, height: 38,
                decoration: BoxDecoration(
                  gradient: AppGradients.accent,
                  borderRadius: BorderRadius.circular(12),
                  boxShadow: [
                    BoxShadow(color: AppColors.accent.withValues(alpha: 0.3), blurRadius: 12, offset: const Offset(0, 4)),
                  ],
                ),
                child: Center(
                  child: Text(
                    (auth.user?.name ?? 'M')[0].toUpperCase(),
                    style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 14),
                  ),
                ),
              ),
              const SizedBox(width: 4),
              IconButton(
                icon: const Icon(Icons.logout_rounded, size: 18, color: AppColors.textMuted),
                tooltip: 'Logout',
                onPressed: () async {
                  if (!await _confirmLogout()) return;
                  if (!context.mounted) return;
                  await auth.logout();
                },
              ),
              const SizedBox(width: 12),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildMobileLayout(AuthProvider auth) {
    return Scaffold(
      key: _scaffoldKey,
      appBar: AppBar(
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.menu_rounded, color: AppColors.textMuted),
          onPressed: () => _scaffoldKey.currentState?.openDrawer(),
        ),
        title: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 30, height: 30,
              decoration: BoxDecoration(
                gradient: AppGradients.primary,
                borderRadius: BorderRadius.circular(9),
              ),
              child: Icon(_pageIcons[_currentIndex], size: 15, color: Colors.white),
            ),
            const SizedBox(width: 8),
            Flexible(
              child: Text(
                _pageTitles[_currentIndex],
                overflow: TextOverflow.ellipsis,
                style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w700),
              ),
            ),
          ],
        ),
        actions: [
          Padding(
            padding: const EdgeInsets.only(right: 8),
            child: Container(
              width: 30, height: 30,
              decoration: const BoxDecoration(
                gradient: LinearGradient(colors: [AppColors.primary, AppColors.primaryLighter]),
                borderRadius: BorderRadius.all(Radius.circular(8)),
              ),
              child: Center(
                child: Text(
                  (auth.user?.name ?? 'M')[0].toUpperCase(),
                  style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12),
                ),
              ),
            ),
          ),
        ],
      ),
      drawer: _buildDrawer(context),
      body: IndexedStack(index: _currentIndex, children: _screens),
    );
  }

  Widget _buildSidebar(AuthProvider auth) {
    return Container(
      width: 244,
      decoration: const BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topCenter,
          end: Alignment.bottomCenter,
          colors: [Color(0xFF1e1b4b), Color(0xFF2e1065)],
        ),
      ),
      child: Column(
        children: [
          _buildSidebarBrand(),
          Expanded(
            child: ListView(
              padding: EdgeInsets.zero,
              children: [
                _sidebarItem(0, Icons.home_outlined, Icons.home_rounded, 'Dashboard'),
                if (auth.user?.isStaff == true) ...[
                  _sidebarSection('Manajemen'),
                  _sidebarItem(8, Icons.verified_user_outlined, Icons.verified_user_rounded, 'Validasi KRS'),
                ],
                _sidebarSection('Kegiatan Belajar'),
                _sidebarItem(2, Icons.assignment_outlined, Icons.assignment_rounded, 'Transaksi KRS'),
                _sidebarItem(1, Icons.calendar_view_week_outlined, Icons.calendar_view_week_rounded, 'Jadwal Kuliah'),
                _sidebarItem(4, Icons.fact_check_outlined, Icons.fact_check_rounded, 'Presensi'),
                _sidebarItem(5, Icons.description_outlined, Icons.description_rounded, 'Materi Kuliah'),
                _sidebarItem(3, Icons.trending_up_outlined, Icons.trending_up_rounded, 'Nilai'),
                _sidebarItem(6, Icons.campaign_outlined, Icons.campaign_rounded, 'Pengumuman'),
                _sidebarSection('Analisis'),
                _sidebarItem(7, Icons.analytics_outlined, Icons.analytics_rounded, 'Kelayakan Mahasiswa'),
              ],
            ),
          ),
          _buildSidebarFooter(auth),
        ],
      ),
    );
  }

  Widget _buildSidebarBrand() {
    return Container(
      padding: const EdgeInsets.fromLTRB(20, 20, 20, 16),
      decoration: const BoxDecoration(
        border: Border(bottom: BorderSide(color: Colors.white12)),
      ),
      child: Row(
        children: [
          Container(
            width: 42, height: 42,
            decoration: BoxDecoration(
              gradient: AppGradients.accent,
              borderRadius: BorderRadius.circular(13),
              boxShadow: [
                BoxShadow(color: AppColors.accent.withValues(alpha: 0.35), blurRadius: 14, offset: const Offset(0, 5)),
              ],
            ),
            child: const Icon(Icons.school, color: Colors.white, size: 22),
          ),
          const SizedBox(width: 12),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text('SIPRAKATA', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w800, fontSize: 16, letterSpacing: -0.3)),
              Text('Sistem Akademik', style: TextStyle(color: AppColors.accentLight, fontSize: 11, fontWeight: FontWeight.w500)),
            ],
          ),
        ],
      ),
    );
  }

  Widget _sidebarSection(String title) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(20, 20, 20, 4),
      child: Text(
        title.toUpperCase(),
        style: TextStyle(
          fontSize: 10,
          fontWeight: FontWeight.w700,
          color: AppColors.accentLight,
          letterSpacing: 1.5,
        ),
      ),
    );
  }

  Widget _sidebarItem(int index, IconData outlinedIcon, IconData filledIcon, String title) {
    final isActive = _currentIndex == index;
    return AnimatedContainer(
      duration: AppMotion.normal,
      curve: AppMotion.curve,
      margin: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
      decoration: BoxDecoration(
        gradient: isActive
            ? LinearGradient(colors: [AppColors.accent.withValues(alpha: 0.22), AppColors.accent.withValues(alpha: 0.05)])
            : null,
        borderRadius: BorderRadius.circular(10),
        border: isActive ? Border(left: BorderSide(color: AppColors.accent, width: 3)) : null,
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(10),
          onTap: () => setState(() => _currentIndex = index),
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 11),
            child: Row(
              children: [
                Icon(isActive ? filledIcon : outlinedIcon, color: isActive ? AppColors.accentLight : Colors.white60, size: 18),
                const SizedBox(width: 12),
                Text(title,
                  style: TextStyle(
                    fontSize: 13,
                    fontWeight: isActive ? FontWeight.w600 : FontWeight.w400,
                    color: isActive ? Colors.white : Colors.white70,
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildSidebarFooter(AuthProvider auth) {
    return Container(
      decoration: const BoxDecoration(
        border: Border(top: BorderSide(color: Colors.white12)),
      ),
      padding: const EdgeInsets.fromLTRB(16, 8, 16, 12),
      child: SizedBox(
        width: double.infinity,
        child: OutlinedButton.icon(
          onPressed: () async {
            if (!await _confirmLogout()) return;
            if (!context.mounted) return;
            await auth.logout();
          },
          icon: const Icon(Icons.logout_rounded, size: 16),
          label: const Text('Keluar'),
          style: OutlinedButton.styleFrom(
            foregroundColor: Colors.white70,
            side: const BorderSide(color: Colors.white24),
            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
          ),
        ),
      ),
    );
  }

  Widget _buildDrawer(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    return Drawer(
      child: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: [Color(0xFF1e1b4b), Color(0xFF2e1065)],
          ),
        ),
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.fromLTRB(20, 48, 20, 16),
              decoration: const BoxDecoration(
                border: Border(bottom: BorderSide(color: Colors.white12)),
              ),
              child: Row(
                children: [
                  Container(
                    width: 42, height: 42,
                    decoration: BoxDecoration(
                      gradient: AppGradients.accent,
                      borderRadius: BorderRadius.circular(13),
                      boxShadow: [
                        BoxShadow(color: AppColors.accent.withValues(alpha: 0.35), blurRadius: 14, offset: const Offset(0, 5)),
                      ],
                    ),
                    child: const Icon(Icons.school, color: Colors.white, size: 22),
                  ),
                  const SizedBox(width: 14),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('SIPRAKATA', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w800, fontSize: 16)),
                      Text('Sistem Akademik', style: TextStyle(color: AppColors.accentLight, fontSize: 11, fontWeight: FontWeight.w500)),
                    ],
                  ),
                ],
              ),
            ),
            Expanded(
              child: ListView(
                padding: EdgeInsets.zero,
                children: [
                  _drawerItem(0, Icons.home_rounded, 'Dashboard'),
                  if (auth.user?.isStaff == true) ...[
                    _drawerSection('Manajemen'),
                    _drawerItem(8, Icons.verified_user_rounded, 'Validasi KRS'),
                  ],
                  _drawerSection('Kegiatan Belajar'),
                  _drawerItem(2, Icons.assignment_rounded, 'Transaksi KRS'),
                  _drawerItem(1, Icons.calendar_view_week_rounded, 'Jadwal Kuliah'),
                  _drawerItem(4, Icons.fact_check_rounded, 'Presensi'),
                  _drawerItem(5, Icons.description_rounded, 'Materi Kuliah'),
                  _drawerItem(3, Icons.trending_up_rounded, 'Nilai'),
                  _drawerItem(6, Icons.campaign_rounded, 'Pengumuman'),
                  _drawerSection('Analisis'),
                  _drawerItem(7, Icons.analytics_rounded, 'Kelayakan Mahasiswa'),
                ],
              ),
            ),
            Container(
              decoration: const BoxDecoration(
                border: Border(top: BorderSide(color: Colors.white12)),
              ),
              padding: const EdgeInsets.fromLTRB(16, 8, 16, 12),
              child: SizedBox(
                width: double.infinity,
                child: OutlinedButton.icon(
                  onPressed: () async {
                    if (!await _confirmLogout()) return;
                    if (!context.mounted) return;
                    Navigator.pop(context);
                    await auth.logout();
                  },
                  icon: const Icon(Icons.logout_rounded, size: 16),
                  label: const Text('Keluar'),
                  style: OutlinedButton.styleFrom(
                    foregroundColor: Colors.white70,
                    side: const BorderSide(color: Colors.white24),
                    padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Future<bool> _confirmLogout() {
    return showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Logout'),
        content: const Text('Yakin ingin keluar?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Batal')),
          TextButton(onPressed: () => Navigator.pop(ctx, true), child: const Text('Ya', style: TextStyle(color: Colors.red))),
        ],
      ),
    ).then((v) => v == true);
  }

  Widget _drawerSection(String title) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(20, 20, 20, 4),
      child: Text(
        title.toUpperCase(),
        style: TextStyle(
          fontSize: 10,
          fontWeight: FontWeight.w700,
          color: AppColors.accentLight,
          letterSpacing: 1.5,
        ),
      ),
    );
  }

  Widget _drawerItem(int index, IconData icon, String title) {
    final isActive = _currentIndex == index;
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
      decoration: BoxDecoration(
        gradient: isActive
            ? LinearGradient(colors: [AppColors.accent.withValues(alpha: 0.22), AppColors.accent.withValues(alpha: 0.05)])
            : null,
        borderRadius: BorderRadius.circular(10),
        border: isActive ? Border(left: BorderSide(color: AppColors.accent, width: 3)) : null,
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(10),
          onTap: () {
            Navigator.pop(context);
            setState(() => _currentIndex = index);
          },
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 11),
            child: Row(
              children: [
                Icon(icon, color: isActive ? AppColors.accentLight : Colors.white60, size: 20),
                const SizedBox(width: 14),
                Text(title,
                  style: TextStyle(
                    fontSize: 13.5,
                    fontWeight: isActive ? FontWeight.w600 : FontWeight.w400,
                    color: isActive ? Colors.white : Colors.white70,
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}