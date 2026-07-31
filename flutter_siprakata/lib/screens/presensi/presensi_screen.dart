import 'package:flutter/material.dart';
import '../../config/app_theme.dart';
import '../../services/api_service.dart';
import '../../models/presensi_model.dart';
import '../../widgets/common_ui.dart';

class PresensiScreen extends StatefulWidget {
  final bool noScaffold;
  const PresensiScreen({super.key, this.noScaffold = false});

  @override
  State<PresensiScreen> createState() => _PresensiScreenState();
}

class _PresensiScreenState extends State<PresensiScreen> {
  final ApiService _api = ApiService();
  List<Presensi> _presensiList = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    setState(() => _isLoading = true);
    try {
      final result = await _api.getPresensiList();
      if (mounted) {
        if (result['status'] == 'success') {
          setState(() {
            _presensiList = (result['data'] as List)
                .map((e) => Presensi.fromJson(e))
                .toList();
            _isLoading = false;
          });
        } else {
          setState(() => _isLoading = false);
        }
      }
    } catch (e) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'hadir': return AppColors.green;
      case 'izin': return const Color(0xFF0ea5e9);
      case 'sakit': return AppColors.warning;
      default: return AppColors.danger;
    }
  }

  IconData _getStatusIcon(String status) {
    switch (status) {
      case 'hadir': return Icons.check_circle_rounded;
      case 'izin': return Icons.info_rounded;
      case 'sakit': return Icons.local_hospital_rounded;
      default: return Icons.cancel_rounded;
    }
  }

  String _formatDate(String date) {
    try {
      final dt = DateTime.parse(date);
      const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
      return '${dt.day.toString().padLeft(2, '0')} ${months[dt.month - 1]} ${dt.year}';
    } catch (_) {
      return date;
    }
  }

  Widget _buildBody() {
    if (_isLoading) return const AppLoadingState(label: 'Memuat presensi...');
    if (_presensiList.isEmpty) {
      return const AppEmptyState(
        icon: Icons.fact_check_outlined,
        title: 'Belum ada presensi',
        subtitle: 'Riwayat kehadiran akan muncul di sini.',
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
                  icon: Icons.fact_check_rounded,
                  title: 'Riwayat Presensi',
                  gradient: AppColors.statPresensi,
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
                      DataColumn(label: Text('Mahasiswa')),
                      DataColumn(label: Text('Mata Kuliah')),
                      DataColumn(label: Text('Tanggal')),
                      DataColumn(label: Text('Pertemuan')),
                      DataColumn(label: Text('Status')),
                      DataColumn(label: Text('Keterangan')),
                    ],
                    rows: List.generate(_presensiList.length, (i) {
                      final p = _presensiList[i];
                      final mhs = p.mahasiswa;
                      final statusColor = _getStatusColor(p.statusHadir);
                      final statusLabel = p.statusHadir.isNotEmpty ? p.statusHadir[0].toUpperCase() + p.statusHadir.substring(1) : '-';
                      return DataRow(
                        color: WidgetStateProperty.all(zebraRowColor(i)),
                        cells: [
                          DataCell(Text('${i + 1}')),
                          DataCell(Text(mhs?.nama ?? '-', style: const TextStyle(fontWeight: FontWeight.w500))),
                          DataCell(Text(p.jadwal?.matakuliah?.namaMk ?? '-', style: const TextStyle(fontWeight: FontWeight.w500))),
                          DataCell(Text(_formatDate(p.tanggal))),
                          DataCell(Center(child: Text('Ke-${p.pertemuanKe}'))),
                          DataCell(Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Icon(_getStatusIcon(p.statusHadir), size: 13, color: statusColor),
                              const SizedBox(width: 5),
                              AppStatusPill(label: statusLabel, color: statusColor),
                            ],
                          )),
                          DataCell(Text(p.keterangan ?? '-')),
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
      appBar: AppBar(title: const Text('Presensi')),
      body: body,
    );
  }
}