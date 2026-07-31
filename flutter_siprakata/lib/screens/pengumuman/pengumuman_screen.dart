import 'package:flutter/material.dart';
import '../../config/app_theme.dart';
import '../../services/api_service.dart';
import '../../models/pengumuman_model.dart';
import '../../widgets/common_ui.dart';

class PengumumanScreen extends StatefulWidget {
  final bool noScaffold;
  const PengumumanScreen({super.key, this.noScaffold = false});

  @override
  State<PengumumanScreen> createState() => _PengumumanScreenState();
}

class _PengumumanScreenState extends State<PengumumanScreen> {
  final ApiService _api = ApiService();
  List<Pengumuman> _pengumumanList = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    setState(() => _isLoading = true);
    try {
      final result = await _api.getPengumumanList();
      if (mounted && result['status'] == 'success') {
        setState(() {
          _pengumumanList = (result['data'] as List)
              .map((e) => Pengumuman.fromJson(e))
              .toList();
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Color _getPrioritasColor(String prioritas) {
    switch (prioritas) {
      case 'tinggi': return AppColors.danger;
      case 'sedang': return AppColors.warning;
      default: return AppColors.textMuted;
    }
  }

  String _getKelas(Pengumuman p) {
    try {
      if (p.jadwal is Map) {
        final j = p.jadwal as Map;
        if (j['matakuliah'] is Map) {
          return (j['matakuliah'] as Map)['nama_mk'] ?? 'Semua Kelas';
        }
      }
    } catch (_) {}
    return 'Semua Kelas';
  }

  String _formatDate(String date) {
    try {
      final dt = DateTime.parse(date);
      return '${dt.day.toString().padLeft(2, '0')}/${dt.month.toString().padLeft(2, '0')}/${dt.year}';
    } catch (_) {
      return date;
    }
  }

  Widget _buildBody() {
    if (_isLoading) return const AppLoadingState(label: 'Memuat pengumuman...');
    if (_pengumumanList.isEmpty) {
      return const AppEmptyState(
        icon: Icons.campaign_outlined,
        title: 'Belum ada pengumuman',
        subtitle: 'Pengumuman dari dosen akan tampil di sini.',
      );
    }
    return RefreshIndicator(
      onRefresh: _loadData,
      color: AppColors.primary,
      child: SingleChildScrollView(
        padding: const EdgeInsets.all(12),
        child: AppFadeIn(
          child: Container(
            decoration: cardDecoration(elevated: true),
            clipBehavior: Clip.antiAlias,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const AppSectionHeader(
                  icon: Icons.campaign_rounded,
                  title: 'Daftar Pengumuman',
                  gradient: AppColors.statPengumuman,
                ),
                SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: DataTable(
                    headingRowColor: WidgetStateProperty.all(AppColors.primary),
                    headingTextStyle: const TextStyle(
                      fontWeight: FontWeight.w700,
                      color: Colors.white,
                      fontSize: 11,
                      letterSpacing: 0.3,
                    ),
                    dataTextStyle: const TextStyle(fontSize: 12, color: AppColors.textDark),
                    columnSpacing: 16,
                    columns: const [
                      DataColumn(label: Text('No')),
                      DataColumn(label: Text('Judul')),
                      DataColumn(label: Text('Dosen')),
                      DataColumn(label: Text('Kelas')),
                      DataColumn(label: Text('Prioritas')),
                      DataColumn(label: Text('Tgl Posting')),
                      DataColumn(label: Text('Kadaluarsa')),
                    ],
                    rows: List.generate(_pengumumanList.length, (i) {
                      final p = _pengumumanList[i];
                      final prioritasColor = _getPrioritasColor(p.prioritas);
                      return DataRow(
                        color: WidgetStateProperty.all(zebraRowColor(i)),
                        cells: [
                          DataCell(Text('${i + 1}')),
                          DataCell(Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Text(p.judul, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 12)),
                              if (p.isi.isNotEmpty)
                                Text(p.isi.length > 50 ? '${p.isi.substring(0, 50)}...' : p.isi,
                                  style: TextStyle(fontSize: 10, color: AppColors.textMuted)),
                            ],
                          )),
                          DataCell(Text(p.dosen?.nama ?? '-')),
                          DataCell(Text(_getKelas(p))),
                          DataCell(AppStatusPill(
                            label: p.prioritas[0].toUpperCase() + p.prioritas.substring(1),
                            color: prioritasColor,
                          )),
                          DataCell(Text(_formatDate(p.tglPosting.isNotEmpty ? p.tglPosting : p.createdAt))),
                          DataCell(Text(p.tglKadaluarsa != null && p.tglKadaluarsa!.isNotEmpty ? _formatDate(p.tglKadaluarsa!) : '-')),
                        ],
                      );
                    }),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final body = _buildBody();
    if (widget.noScaffold) return body;
    return Scaffold(
      appBar: AppBar(title: const Text('Pengumuman')),
      body: body,
    );
  }
}