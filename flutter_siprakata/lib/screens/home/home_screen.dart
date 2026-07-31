import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/auth_provider.dart';
import '../../services/api_service.dart';
import '../../models/pengumuman_model.dart';
import '../../models/krs_model.dart';
import '../../models/user_model.dart';
import '../../config/app_theme.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final ApiService _api = ApiService();
  List<Pengumuman> _pengumuman = [];
  List<KRS> _recentKrs = [];
  int _krsCount = 0;
  int _totalSks = 0;
  int _jadwalCount = 0;
  int _presensiCount = 0;
  int _nilaiCount = 0;
  int _materiCount = 0;
  int _pengumumanCount = 0;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    setState(() => _isLoading = true);
    try {
      final results = await Future.wait([
        _api.getPengumumanList(),
        _api.getKRSList(),
        _api.getJadwalList(),
        _api.getPresensiList(),
        _api.getNilaiList(),
        _api.getMateriList(),
      ]);

      if (mounted) {
        setState(() {
          final pengumumanRes = results[0];
          final krsRes = results[1];
          final jadwalRes = results[2];
          final presensiRes = results[3];
          final nilaiRes = results[4];
          final materiRes = results[5];

          if (pengumumanRes['status'] == 'success' && pengumumanRes['data'] != null) {
            final list = (pengumumanRes['data'] as List)
                .map((e) => Pengumuman.fromJson(e))
                .toList();
            _pengumuman = list;
            _pengumumanCount = list.length;
          }
          if (krsRes['status'] == 'success' && krsRes['data'] != null) {
            final list = (krsRes['data'] as List)
                .map((e) => KRS.fromJson(e))
                .toList();
            _recentKrs = List.from(list)..sort((a, b) => b.id.compareTo(a.id));
            _krsCount = list.length;
            _totalSks = list.fold(0, (sum, k) => sum + (k.matakuliah?.sks ?? 0));
          }
          if (jadwalRes['status'] == 'success' && jadwalRes['data'] != null) {
            _jadwalCount = (jadwalRes['data'] as List).length;
          }
          if (presensiRes['status'] == 'success' && presensiRes['data'] != null) {
            _presensiCount = (presensiRes['data'] as List).length;
          }
          if (nilaiRes['status'] == 'success' && nilaiRes['data'] != null) {
            _nilaiCount = (nilaiRes['data'] as List).length;
          }
          if (materiRes['status'] == 'success' && materiRes['data'] != null) {
            _materiCount = (materiRes['data'] as List).length;
          }
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  String _getCurrentDate() {
    final now = DateTime.now();
    const months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu', 'Minggu'];
    return '${days[now.weekday - 1]}, ${now.day} ${months[now.month]} ${now.year}';
  }

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    final user = auth.user;

    return RefreshIndicator(
      onRefresh: _loadData,
      color: AppColors.accent,
      child: _isLoading
          ? const Center(child: CircularProgressIndicator(color: AppColors.accent))
          : SingleChildScrollView(
              physics: const AlwaysScrollableScrollPhysics(),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _buildWelcomeHeader(user),
                  _buildStatGrid(),
                  _buildContentRow(),
                ],
              ),
            ),
    );
  }

  Widget _buildWelcomeHeader(AppUser? user) {
    final isMobile = MediaQuery.of(context).size.width < 700;
    return Padding(
      padding: EdgeInsets.fromLTRB(isMobile ? 16 : 24, isMobile ? 16 : 24, isMobile ? 16 : 24, 0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Selamat datang, ${user?.name ?? 'Mahasiswa'}!',
            style: TextStyle(fontWeight: FontWeight.w800, fontSize: isMobile ? 17 : 20, color: AppColors.primary),
          ),
          const SizedBox(height: 4),
          Row(
            children: [
              Expanded(
                child: Text(
                  'Kelola data akademik Anda dari panel ini.',
                  style: TextStyle(fontSize: isMobile ? 12 : 14, color: AppColors.textMuted),
                ),
              ),
              const SizedBox(width: 8),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 7),
                decoration: BoxDecoration(
                  gradient: LinearGradient(colors: [AppColors.accent.withValues(alpha: 0.12), AppColors.accent2.withValues(alpha: 0.10)]),
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(color: AppColors.accent.withValues(alpha: 0.18)),
                ),
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Icon(Icons.calendar_today_rounded, size: isMobile ? 11 : 13, color: AppColors.accent),
                    const SizedBox(width: 6),
                    Text(_getCurrentDate(),
                      style: TextStyle(fontSize: isMobile ? 10 : 11, color: const Color(0xFFb45309), fontWeight: FontWeight.w600)),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  final List<_StatDef> _statDefs = const [
    _StatDef('Transaksi KRS', Icons.assignment_rounded, AppColors.statKrs),
    _StatDef('Total SKS', Icons.layers_rounded, AppColors.statSks),
    _StatDef('Jadwal Kuliah', Icons.calendar_month_rounded, AppColors.statJadwal),
    _StatDef('Presensi', Icons.fact_check_rounded, AppColors.statPresensi),
    _StatDef('Nilai', Icons.grade_rounded, AppColors.statNilai),
    _StatDef('Materi', Icons.menu_book_rounded, AppColors.statMateri),
    _StatDef('Pengumuman', Icons.campaign_rounded, AppColors.statPengumuman),
    _StatDef('Kelayakan', Icons.analytics_rounded, AppColors.statKelayakan),
  ];

  List<String> get _statValues => [
    '$_krsCount', '$_totalSks', '$_jadwalCount', '$_presensiCount',
    '$_nilaiCount', '$_materiCount', '$_pengumumanCount', '-',
  ];

  Widget _buildStatGrid() {
    final width = MediaQuery.of(context).size.width;
    final isMobile = width < 700;
    final hPad = isMobile ? 16.0 : 24.0;

    if (isMobile) {
      return Padding(
        padding: EdgeInsets.fromLTRB(0, 12, 0, 4),
        child: SizedBox(
          height: 80,
          child: ListView.builder(
            scrollDirection: Axis.horizontal,
            padding: EdgeInsets.symmetric(horizontal: hPad),
            itemCount: _statDefs.length,
            itemBuilder: (ctx, i) => Padding(
              padding: EdgeInsets.only(left: i > 0 ? 10 : 0),
              child: _buildMiniCard(_statDefs[i], _statValues[i]),
            ),
          ),
        ),
      );
    }

    return Padding(
      padding: EdgeInsets.fromLTRB(hPad, 20, hPad, 8),
      child: Column(
        children: [
          for (var r = 0; r < _statDefs.length; r += 4)
            Padding(
              padding: r > 0 ? const EdgeInsets.only(top: 20) : EdgeInsets.zero,
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  for (var c = 0; c < 4 && r + c < _statDefs.length; c++)
                    Expanded(
                      child: Padding(
                        padding: EdgeInsets.only(left: c > 0 ? 20 : 0),
                        child: _StaggerFadeIn(
                          index: r + c,
                          child: _buildStatCard(_statDefs[r + c], _statValues[r + c]),
                        ),
                      ),
                    ),
                ],
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildMiniCard(_StatDef def, String value) {
    return Container(
      width: 100,
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
      decoration: BoxDecoration(
        gradient: LinearGradient(colors: def.gradient, begin: Alignment.topLeft, end: Alignment.bottomRight),
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(color: def.gradient.first.withValues(alpha: 0.28), blurRadius: 14, offset: const Offset(0, 6), spreadRadius: -3),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text(value,
            style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w800, color: Colors.white, height: 1.1)),
          const SizedBox(height: 2),
          Text(def.label,
            style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w500, color: Colors.white, overflow: TextOverflow.ellipsis)),
        ],
      ),
    );
  }

  Widget _buildStatCard(_StatDef def, String value) {
    return _HoverLift(
      child: Container(
        padding: const EdgeInsets.fromLTRB(18, 18, 18, 16),
        decoration: BoxDecoration(
          gradient: LinearGradient(colors: def.gradient, begin: Alignment.topLeft, end: Alignment.bottomRight),
          borderRadius: BorderRadius.circular(18),
          boxShadow: [
            BoxShadow(color: def.gradient.first.withValues(alpha: 0.32), blurRadius: 20, offset: const Offset(0, 10), spreadRadius: -4),
          ],
        ),
        clipBehavior: Clip.antiAlias,
        child: Stack(
          children: [
            Positioned(right: -8, top: -8,
              child: Icon(def.icon, size: 60, color: Colors.white.withValues(alpha: 0.14)),
            ),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(value,
                  style: const TextStyle(fontSize: 26, fontWeight: FontWeight.w800, color: Colors.white, height: 1.1)),
                const SizedBox(height: 4),
                Text(def.label,
                  style: TextStyle(fontSize: 13, fontWeight: FontWeight.w500, color: Colors.white.withValues(alpha: 0.88))),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildContentRow() {
    final width = MediaQuery.of(context).size.width;
    final isWide = width >= 700;

    if (isWide) {
      return Padding(
        padding: const EdgeInsets.fromLTRB(24, 12, 24, 24),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Expanded(flex: 7, child: _buildKrsTable()),
            const SizedBox(width: 24),
            Expanded(flex: 4, child: _buildPengumumanSidebar()),
          ],
        ),
      );
    }
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 0, 16, 24),
      child: Column(
        children: [
          _buildKrsTable(),
          const SizedBox(height: 16),
          _buildPengumumanSidebar(),
        ],
      ),
    );
  }

  Widget _buildKrsTable() {
    return Container(
      decoration: cardDecoration(elevated: true),
      clipBehavior: Clip.antiAlias,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
            decoration: const BoxDecoration(
              border: Border(bottom: BorderSide(color: AppColors.border)),
            ),
            child: Row(
              children: [
                Container(
                  width: 30, height: 30,
                  decoration: BoxDecoration(color: AppColors.primary.withValues(alpha: 0.08), borderRadius: BorderRadius.circular(9)),
                  child: Icon(Icons.history_rounded, size: 15, color: AppColors.primary),
                ),
                const SizedBox(width: 10),
                const Text('Aktivitas Terbaru', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 14, color: AppColors.primary)),
              ],
            ),
          ),
          if (_recentKrs.isEmpty)
            const Padding(
              padding: EdgeInsets.all(32),
              child: Center(child: Text('Belum ada aktivitas KRS.', style: TextStyle(color: AppColors.textMuted, fontSize: 13))),
            )
          else
            SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              child: DataTable(
                headingRowColor: WidgetStateProperty.all(const Color(0xFFf8fafc)),
                headingTextStyle: const TextStyle(
                  fontWeight: FontWeight.w700, color: AppColors.textDark, fontSize: 11, letterSpacing: 0.3,
                ),
                dataTextStyle: const TextStyle(fontSize: 12, color: AppColors.textDark),
                columnSpacing: 24,
                horizontalMargin: 20,
                columns: const [
                  DataColumn(label: Text('#')),
                  DataColumn(label: Text('Mahasiswa')),
                  DataColumn(label: Text('Mata Kuliah')),
                  DataColumn(label: Text('Tahun Ajaran')),
                  DataColumn(label: Text('Status')),
                ],
                rows: _recentKrs.take(8).toList().asMap().entries.map((entry) {
                  final i = entry.key;
                  final k = entry.value;
                  final mk = k.matakuliah;
                  return DataRow(cells: [
                    DataCell(Text('${i + 1}', style: const TextStyle(color: AppColors.textMuted))),
                    DataCell(Text(k.mahasiswa?.nama ?? '-', style: const TextStyle(fontWeight: FontWeight.w600))),
                    DataCell(Text(mk?.namaMk ?? '-')),
                    DataCell(Text(k.tahunAjaran, style: const TextStyle(fontSize: 11))),
                    DataCell(_statusBadge(k.statusValidasi)),
                  ]);
                }).toList(),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildPengumumanSidebar() {
    return Container(
      decoration: cardDecoration(elevated: true),
      clipBehavior: Clip.antiAlias,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
            decoration: const BoxDecoration(
              border: Border(bottom: BorderSide(color: AppColors.border)),
            ),
            child: Row(
              children: [
                Container(
                  width: 30, height: 30,
                  decoration: BoxDecoration(color: AppColors.accent2.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(9)),
                  child: Icon(Icons.campaign_rounded, size: 15, color: AppColors.accent2),
                ),
                const SizedBox(width: 10),
                const Text('Pengumuman Terbaru', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 14, color: AppColors.primary)),
              ],
            ),
          ),
          if (_pengumuman.isEmpty)
            const Padding(
              padding: EdgeInsets.all(24),
              child: Center(child: Text('Belum ada pengumuman', style: TextStyle(color: AppColors.textMuted, fontSize: 13))),
            )
          else
            ..._pengumuman.take(5).toList().asMap().entries.map((entry) {
              final i = entry.key;
              final p = entry.value;
              final isLast = i == (_pengumuman.length < 5 ? _pengumuman.length : 5) - 1;
              return Container(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                decoration: isLast ? null : const BoxDecoration(
                  border: Border(bottom: BorderSide(color: Color(0xFFf1f5f9))),
                ),
                child: Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      width: 40, height: 40,
                      decoration: BoxDecoration(
                        color: AppColors.accent2.withValues(alpha: 0.1),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Icon(Icons.campaign_rounded, color: AppColors.accent2, size: 16),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(p.judul,
                            style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 13, color: AppColors.textDark, height: 1.3),
                            maxLines: 2, overflow: TextOverflow.ellipsis),
                          const SizedBox(height: 3),
                          Text(_formatRelativeDate(p.tglPosting),
                            style: const TextStyle(fontSize: 11, color: AppColors.textMuted)),
                        ],
                      ),
                    ),
                  ],
                ),
              );
            }),
        ],
      ),
    );
  }

  Widget _statusBadge(String? status) {
    Color bg;
    Color text;
    String label;
    switch (status?.toLowerCase()) {
      case 'disetujui':
        bg = const Color(0xFF10b981).withValues(alpha: 0.1);
        text = const Color(0xFF10b981);
        label = 'Disetujui';
        break;
      case 'ditolak':
        bg = const Color(0xFFef4444).withValues(alpha: 0.1);
        text = const Color(0xFFef4444);
        label = 'Ditolak';
        break;
      case 'pending':
        bg = const Color(0xFFf59e0b).withValues(alpha: 0.1);
        text = const Color(0xFFf59e0b);
        label = 'Pending';
        break;
      default:
        bg = AppColors.border.withValues(alpha: 0.3);
        text = AppColors.textMuted;
        label = status ?? '-';
    }
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      decoration: BoxDecoration(color: bg, borderRadius: BorderRadius.circular(6)),
      child: Text(label, style: TextStyle(fontWeight: FontWeight.w600, color: text, fontSize: 11)),
    );
  }

  String _formatRelativeDate(String dateStr) {
    try {
      final date = DateTime.parse(dateStr);
      final now = DateTime.now();
      final diff = now.difference(date);
      if (diff.inDays > 0) return '${diff.inDays} hari yang lalu';
      if (diff.inHours > 0) return '${diff.inHours} jam yang lalu';
      if (diff.inMinutes > 0) return '${diff.inMinutes} menit yang lalu';
      return 'Baru saja';
    } catch (e) {
      return dateStr;
    }
  }
}

class _StatDef {
  final String label;
  final IconData icon;
  final List<Color> gradient;
  const _StatDef(this.label, this.icon, this.gradient);
}

/// Fades and slides a card up into place, staggered by [index], to give
/// the dashboard a livelier "loaded in" feel instead of popping in flat.
class _StaggerFadeIn extends StatefulWidget {
  final int index;
  final Widget child;
  const _StaggerFadeIn({required this.index, required this.child});

  @override
  State<_StaggerFadeIn> createState() => _StaggerFadeInState();
}

class _StaggerFadeInState extends State<_StaggerFadeIn> {
  bool _visible = false;

  @override
  void initState() {
    super.initState();
    Future.delayed(Duration(milliseconds: 60 * widget.index), () {
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
        offset: _visible ? Offset.zero : const Offset(0, 0.12),
        duration: AppMotion.normal,
        curve: AppMotion.curve,
        child: widget.child,
      ),
    );
  }
}

/// Subtle scale-up on hover/press so cards feel tactile on web and touch.
class _HoverLift extends StatefulWidget {
  final Widget child;
  const _HoverLift({required this.child});

  @override
  State<_HoverLift> createState() => _HoverLiftState();
}

class _HoverLiftState extends State<_HoverLift> {
  bool _hover = false;

  @override
  Widget build(BuildContext context) {
    return MouseRegion(
      onEnter: (_) => setState(() => _hover = true),
      onExit: (_) => setState(() => _hover = false),
      child: AnimatedScale(
        scale: _hover ? 1.03 : 1.0,
        duration: AppMotion.fast,
        curve: Curves.easeOut,
        child: widget.child,
      ),
    );
  }
}