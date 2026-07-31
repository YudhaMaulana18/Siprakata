import 'package:flutter/material.dart';
import '../../config/app_theme.dart';
import '../../services/api_service.dart';
import '../../models/jadwal_model.dart';
import '../../widgets/common_ui.dart';

class JadwalScreen extends StatefulWidget {
  final bool noScaffold;
  const JadwalScreen({super.key, this.noScaffold = false});

  @override
  State<JadwalScreen> createState() => _JadwalScreenState();
}

class _JadwalScreenState extends State<JadwalScreen> {
  final ApiService _api = ApiService();
  List<Jadwal> _jadwalList = [];
  bool _isLoading = true;

  static const Map<String, Color> _hariColors = {
    'senin': Color(0xFF4338CA),
    'selasa': Color(0xFF0369a1),
    'rabu': Color(0xFF059669),
    'kamis': Color(0xFFc026d3),
    'jumat': Color(0xFFe11d48),
    "jum'at": Color(0xFFe11d48),
    'sabtu': Color(0xFFea580c),
    'minggu': Color(0xFF64748b),
  };

  Color _hariColor(String hari) => _hariColors[hari.toLowerCase()] ?? AppColors.primary;

  @override
  void initState() {
    super.initState();
    _loadJadwal();
  }

  Future<void> _loadJadwal() async {
    setState(() => _isLoading = true);
    try {
      final result = await _api.getJadwalList();
      if (mounted && result['status'] == 'success') {
        setState(() {
          _jadwalList = (result['data'] as List)
              .map((e) => Jadwal.fromJson(e))
              .toList();
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  String _formatJam(String jam) {
    if (jam.length >= 5) return jam.substring(0, 5);
    return jam;
  }

  @override
  Widget build(BuildContext context) {
    final body = _isLoading
        ? const AppLoadingState(label: 'Memuat jadwal kuliah...')
        : _jadwalList.isEmpty
            ? const AppEmptyState(
                icon: Icons.schedule_outlined,
                title: 'Belum ada jadwal kuliah',
                subtitle: 'Jadwal yang sudah dibuat akan muncul di sini.',
              )
            : RefreshIndicator(
                onRefresh: _loadJadwal,
                color: AppColors.primary,
                child: SingleChildScrollView(
                  child: AppFadeIn(
                    child: Container(
                      margin: const EdgeInsets.all(12),
                      decoration: cardDecoration(elevated: true),
                      clipBehavior: Clip.antiAlias,
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const AppSectionHeader(
                            icon: Icons.calendar_month_rounded,
                            title: 'Daftar Jadwal Kuliah',
                            gradient: AppColors.statJadwal,
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
                                DataColumn(label: Text('Kode MK')),
                                DataColumn(label: Text('Mata Kuliah')),
                                DataColumn(label: Text('Dosen')),
                                DataColumn(label: Text('Hari')),
                                DataColumn(label: Text('Jam')),
                                DataColumn(label: Text('Ruangan')),
                                DataColumn(label: Text('Semester')),
                                DataColumn(label: Text('Tahun Ajaran')),
                              ],
                              rows: List.generate(_jadwalList.length, (i) {
                                final j = _jadwalList[i];
                                final mk = j.matakuliah;
                                final hariColor = _hariColor(j.hari);
                                return DataRow(
                                  color: WidgetStateProperty.all(zebraRowColor(i)),
                                  cells: [
                                    DataCell(Text('${i + 1}')),
                                    DataCell(Text(mk?.kodeMk ?? '-')),
                                    DataCell(Text(mk?.namaMk ?? '-', style: const TextStyle(fontWeight: FontWeight.w600))),
                                    DataCell(Text(j.dosen?.nama ?? '-')),
                                    DataCell(AppStatusPill(label: j.hari, color: hariColor)),
                                    DataCell(Row(
                                      mainAxisSize: MainAxisSize.min,
                                      children: [
                                        Icon(Icons.access_time_rounded, size: 13, color: AppColors.textLight),
                                        const SizedBox(width: 4),
                                        Text('${_formatJam(j.jamMulai)} - ${_formatJam(j.jamSelesai)}'),
                                      ],
                                    )),
                                    DataCell(Text(j.ruangan ?? '-')),
                                    DataCell(Text(j.semester ?? (mk?.semester?.toString() ?? '-'))),
                                    DataCell(Text(j.tahunAjaranRef?.tahun ?? '-')),
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

    if (widget.noScaffold) return body;
    return Scaffold(
      appBar: AppBar(title: const Text('Jadwal Kuliah')),
      body: body,
    );
  }
}