import 'package:flutter/material.dart';
import '../../config/app_theme.dart';
import '../../services/api_service.dart';
import '../../models/krs_model.dart';
import '../../widgets/common_ui.dart';

class NilaiScreen extends StatefulWidget {
  final bool noScaffold;
  const NilaiScreen({super.key, this.noScaffold = false});

  @override
  State<NilaiScreen> createState() => _NilaiScreenState();
}

class _NilaiScreenState extends State<NilaiScreen> {
  final ApiService _api = ApiService();
  List<Nilai> _nilaiList = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    setState(() => _isLoading = true);
    try {
      final result = await _api.getNilaiList();
      if (mounted && result['status'] == 'success') {
        setState(() {
          _nilaiList = (result['data'] as List)
              .map((e) => Nilai.fromJson(e))
              .toList();
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Color _getGradeColor(String? grade) {
    switch (grade) {
      case 'A': return AppColors.green;
      case 'B+': case 'B': return const Color(0xFF2563eb);
      case 'C+': case 'C': return AppColors.warning;
      case 'D': case 'E': return AppColors.danger;
      default: return AppColors.textMuted;
    }
  }

  @override
  Widget build(BuildContext context) {
    if (widget.noScaffold) return _buildBody();
    return Scaffold(
      appBar: AppBar(title: const Text('Nilai')),
      body: _buildBody(),
    );
  }

  Widget _buildBody() {
    if (_isLoading) return const AppLoadingState(label: 'Memuat nilai...');
    if (_nilaiList.isEmpty) {
      return const AppEmptyState(
        icon: Icons.grade_outlined,
        title: 'Belum ada nilai',
        subtitle: 'Nilai yang sudah diinput dosen akan tampil di sini.',
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
                  icon: Icons.grade_rounded,
                  title: 'Daftar Nilai',
                  gradient: AppColors.statNilai,
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
                      DataColumn(label: Text('Dosen')),
                      DataColumn(label: Text('Tugas\n(30%)')),
                      DataColumn(label: Text('UTS\n(30%)')),
                      DataColumn(label: Text('UAS\n(40%)')),
                      DataColumn(label: Text('Nilai\nAkhir')),
                      DataColumn(label: Text('Grade')),
                    ],
                    rows: List.generate(_nilaiList.length, (i) {
                      final n = _nilaiList[i];
                      final mhs = n.krs?.mahasiswa;
                      final mk = n.krs?.matakuliah;
                      final dosen = n.krs?.dosen;
                      final gradeColor = _getGradeColor(n.grade);
                      return DataRow(
                        color: WidgetStateProperty.all(zebraRowColor(i)),
                        cells: [
                          DataCell(Text('${i + 1}')),
                          DataCell(Text(mhs?.nama ?? '-', style: const TextStyle(fontWeight: FontWeight.w500))),
                          DataCell(Text(mk?.namaMk ?? '-', style: const TextStyle(fontWeight: FontWeight.w500))),
                          DataCell(Text(dosen?.nama ?? '-')),
                          DataCell(Text(n.nilaiTugas?.toStringAsFixed(0) ?? '-')),
                          DataCell(Text(n.nilaiUts?.toStringAsFixed(0) ?? '-')),
                          DataCell(Text(n.nilaiUas?.toStringAsFixed(0) ?? '-')),
                          DataCell(Text(n.nilaiAkhir?.toStringAsFixed(2) ?? '-', style: const TextStyle(fontWeight: FontWeight.w700))),
                          DataCell(AppStatusPill(label: n.grade ?? '-', color: gradeColor)),
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
}