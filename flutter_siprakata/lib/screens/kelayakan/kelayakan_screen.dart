import 'dart:convert';
import 'package:flutter/material.dart';
import '../../config/app_theme.dart';
import '../../services/api_service.dart';
import '../../models/kelayakan_model.dart';
import '../../models/mahasiswa_model.dart';
import '../../models/matakuliah_model.dart';
import '../../widgets/common_ui.dart';

class KelayakanScreen extends StatefulWidget {
  final bool noScaffold;
  const KelayakanScreen({super.key, this.noScaffold = false});

  @override
  State<KelayakanScreen> createState() => _KelayakanScreenState();
}

class _KelayakanScreenState extends State<KelayakanScreen> {
  final ApiService _api = ApiService();
  List<Kelayakan> _kelayakanList = [];
  List<String> _tahunAjaranList = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadOptions();
  }

  Future<void> _loadOptions() async {
    try {
      final tahunResult = await _api.getTahunAjaranList();
      if (mounted && tahunResult['status'] == 'success') {
        setState(() {
          _tahunAjaranList = (tahunResult['data'] as List)
              .map((e) => e is Map ? (e['tahun']?.toString() ?? '') : e.toString())
              .where((s) => s.isNotEmpty)
              .toSet()
              .toList();
        });
      }
    } catch (_) {}
    _loadData();
  }

  Future<void> _loadData() async {
    setState(() => _isLoading = true);
    try {
      final result = await _api.getKelayakanList();
      if (mounted && result['status'] == 'success') {
        setState(() {
          _kelayakanList = (result['data'] as List)
              .map((e) => Kelayakan.fromJson(e))
              .toList();
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Color _getPredikatColor(String? predikat) {
    switch (predikat) {
      case 'lulus': return AppColors.green;
      case 'cukup': return AppColors.warning;
      case 'tidak_lulus': return AppColors.danger;
      default: return AppColors.textMuted;
    }
  }

  String _getPredikatLabel(String? predikat) {
    switch (predikat) {
      case 'lulus': return 'Lulus';
      case 'cukup': return 'Cukup';
      case 'tidak_lulus': return 'Tidak Lulus';
      default: return predikat ?? '-';
    }
  }

  int _countPredikat(String predikat) {
    return _kelayakanList.where((k) => (k.hasilPrediksi ?? '') == predikat).length;
  }

  void _showDetailDialog(Kelayakan k) {
    final detail = k.detailPerhitungan;
    final mhs = k.mahasiswa;

    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (ctx) {
        return Dialog(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
          child: ConstrainedBox(
            constraints: const BoxConstraints(maxWidth: 520, maxHeight: 600),
            child: Column(
              children: [
                Container(
                  padding: const EdgeInsets.fromLTRB(20, 16, 20, 12),
                  decoration: const BoxDecoration(
                    gradient: AppGradients.primary,
                    borderRadius: BorderRadius.only(topLeft: Radius.circular(20), topRight: Radius.circular(20)),
                  ),
                  child: Column(
                    children: [
                      Row(
                        children: [
                          Container(
                            width: 36, height: 36,
                            decoration: BoxDecoration(
                              color: Colors.white.withValues(alpha: 0.2),
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: const Icon(Icons.psychology_rounded, color: Colors.white, size: 18),
                          ),
                          const SizedBox(width: 12),
                          const Expanded(
                            child: Text('Detail Perhitungan Fuzzy\nPrediksi Kelulusan',
                              style: TextStyle(color: Colors.white, fontWeight: FontWeight.w700, fontSize: 14, height: 1.3)),
                          ),
                          GestureDetector(
                            onTap: () => Navigator.pop(ctx),
                            child: Container(
                              width: 30, height: 30,
                              decoration: BoxDecoration(
                                color: Colors.white.withValues(alpha: 0.2),
                                borderRadius: BorderRadius.circular(8),
                              ),
                              child: const Icon(Icons.close, size: 16, color: Colors.white),
                            ),
                          ),
                        ],
                      ),
                      if (mhs != null) ...[
                        const SizedBox(height: 8),
                        Row(
                          children: [
                            Container(
                              width: 28, height: 28,
                              decoration: BoxDecoration(
                                color: Colors.white.withValues(alpha: 0.2),
                                borderRadius: BorderRadius.circular(8),
                              ),
                              child: Text(mhs.nama.isNotEmpty ? mhs.nama[0].toUpperCase() : 'M',
                                style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12)),
                            ),
                            const SizedBox(width: 8),
                            Text(mhs.nama,
                              style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w600, fontSize: 13)),
                            const SizedBox(width: 6),
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                              decoration: BoxDecoration(
                                color: Colors.white.withValues(alpha: 0.15),
                                borderRadius: BorderRadius.circular(4),
                              ),
                              child: const Text('Mahasiswa', style: TextStyle(color: Colors.white, fontSize: 9)),
                            ),
                          ],
                        ),
                      ],
                    ],
                  ),
                ),
                Expanded(
                  child: SingleChildScrollView(
                    padding: const EdgeInsets.all(16),
                    child: _buildDetailContent(k, detail),
                  ),
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  Widget _buildDetailContent(Kelayakan k, dynamic detail) {
    if (detail == null) {
      return const Center(child: Text('Detail tidak tersedia', style: TextStyle(color: AppColors.textMuted)));
    }

    Map<String, dynamic> data;
    if (detail is String) {
      try {
        data = _parseJson(detail);
      } catch (_) {
        return const Center(child: Text('Gagal memproses detail', style: TextStyle(color: AppColors.textMuted)));
      }
    } else if (detail is Map) {
      data = detail.cast<String, dynamic>();
    } else {
      return const Center(child: Text('Format detail tidak dikenal', style: TextStyle(color: AppColors.textMuted)));
    }

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _detailSection('Data Mahasiswa', Icons.person_rounded, [
          _detailRow('NIM', k.mahasiswa?.nim ?? '-'),
          _detailRow('Nama', k.mahasiswa?.nama ?? '-'),
          _detailRow('Mata Kuliah', '${k.matakuliah?.namaMk ?? '-'} (${k.matakuliah?.sks ?? '-'} SKS)'),
          _detailRow('Tahun Ajaran', k.tahunAjaran),
          _detailRow('Semester', k.semester),
        ]),
        const SizedBox(height: 16),
        _detailSection('Input Fuzzy', Icons.input_rounded, [
          _detailRow('Kehadiran', '${k.kehadiran?.toStringAsFixed(1) ?? '-'}%'),
          _detailRow('Nilai Tugas', k.nilaiTugas?.toStringAsFixed(1) ?? '-'),
          _detailRow('Keaktifan Diskusi', k.keaktifanDiskusi?.toStringAsFixed(1) ?? '-'),
        ]),
        ..._buildFuzzificationSection(data, k.kehadiran ?? 0, k.nilaiTugas ?? 0, k.keaktifanDiskusi ?? 0),
        ..._buildRumusSection(),
        ..._buildRulesSection(data),
        ..._buildOutputSection(data, k),
      ],
    );
  }

  List<Widget> _buildFuzzificationSection(Map<String, dynamic> data, double kehadiran, double nilaiTugas, double keaktifan) {
    final fuzz = data['fuzzification'] ?? data['fuzzifikasi'];
    if (fuzz == null || fuzz is! Map) return [];

    final inputs = [
      ('Kehadiran', kehadiran, '${kehadiran.toStringAsFixed(1)}%', false),
      ('Nilai Tugas', nilaiTugas, nilaiTugas.toStringAsFixed(1), false),
      ('Keaktifan Diskusi', keaktifan, keaktifan.toStringAsFixed(1), true),
    ];

    return [
      const SizedBox(height: 16),
      _stepHeader('1', 'Fuzzification', AppColors.accent),
      const SizedBox(height: 8),
      LayoutBuilder(builder: (ctx, constraints) {
        final isWide = constraints.maxWidth > 480;
        if (isWide) {
          return Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: inputs.map((i) => Expanded(
              child: Padding(
                padding: EdgeInsets.only(left: inputs.indexOf(i) > 0 ? 8 : 0),
                child: _buildFuzzyCard(i.$1, i.$2, i.$3, fuzz, i.$4),
              ),
            )).toList(),
          );
        }
        return Column(
          children: inputs.map((i) => Padding(
            padding: EdgeInsets.only(top: inputs.indexOf(i) > 0 ? 8 : 0),
            child: _buildFuzzyCard(i.$1, i.$2, i.$3, fuzz, i.$4),
          )).toList(),
        );
      }),
    ];
  }

  Widget _buildFuzzyCard(String label, double value, String valueLabel, Map fuzz, bool isKeaktifan) {
    final fuzzKey = label == 'Kehadiran' ? 'kehadiran' : label == 'Nilai Tugas' ? 'nilai_tugas' : 'keaktifan_diskusi';
    final fuzzData = fuzz[fuzzKey] ?? fuzz[label.toLowerCase()];
    final Map<String, dynamic> memberships;
    if (fuzzData is Map) {
      memberships = fuzzData.cast<String, dynamic>();
    } else {
      memberships = {};
    }

    final mRendah = (memberships['Rendah']?.toDouble() ?? 0).clamp(0, 1).toDouble();
    final mSedang = (memberships['Sedang']?.toDouble() ?? 0).clamp(0, 1).toDouble();
    final mTinggi = (memberships['Tinggi']?.toDouble() ?? 0).clamp(0, 1).toDouble();

    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: const Color(0xFFf8fafc),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: AppColors.border),
      ),
      child: Column(
        children: [
          Text('$label = $valueLabel',
            style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 12, color: AppColors.primary),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 8),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
            children: [
              _badgeChip(mRendah.toStringAsFixed(0), 'Rendah', mRendah >= 0.5 ? AppColors.danger : AppColors.textLight),
              _badgeChip(mSedang.toStringAsFixed(0), 'Sedang', mSedang >= 0.5 ? AppColors.warning : AppColors.textLight),
              _badgeChip(mTinggi.toStringAsFixed(0), 'Tinggi', mTinggi >= 0.5 ? AppColors.green : AppColors.textLight),
            ],
          ),
          const SizedBox(height: 8),
            SizedBox(
              width: double.infinity,
              height: 100,
              child: CustomPaint(
                painter: _MembershipGraphPainter(value, isKeaktifan),
              ),
          ),
        ],
      ),
    );
  }

  Widget _badgeChip(String value, String label, Color color) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
          decoration: BoxDecoration(
            color: color == AppColors.textLight ? color.withValues(alpha: 0.3) : color,
            borderRadius: BorderRadius.circular(4),
          ),
          child: Text(value,
            style: TextStyle(
              fontSize: 11, fontWeight: FontWeight.w700,
              color: color == AppColors.textLight ? AppColors.textMuted : Colors.white,
            ),
          ),
        ),
        const SizedBox(height: 2),
        Text(label, style: const TextStyle(fontSize: 9, color: AppColors.textMuted)),
      ],
    );
  }

  Widget _stepHeader(String number, String title, Color color) {
    return Row(
      children: [
        Container(
          width: 28, height: 28,
          decoration: BoxDecoration(
            color: color.withValues(alpha: 0.1),
            borderRadius: BorderRadius.circular(6),
          ),
          child: Center(child: Text(number,
            style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13, color: color))),
        ),
        const SizedBox(width: 8),
        Expanded(child: Text(title,
          style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 14, color: AppColors.textDark))),
      ],
    );
  }

  List<Widget> _buildRumusSection() {
    return [
      const SizedBox(height: 8),
      _CollapsibleRumusWidget(),
    ];
  }

  List<Widget> _buildRulesSection(Map<String, dynamic> data) {
    final rules = data['rules'] ?? data['rules_aktif'];
    List rulesList;
    if (rules is List) {
      rulesList = rules.map((r) {
        if (r is String) {
          final parts = RegExp(r'^R(\d+):\s*(.*?)\s*\|\s*μ=([\d.]+)\s*→\s*(.*)$').firstMatch(r);
          if (parts != null) {
            return {
              'rule': 'R${parts.group(1)}',
              'kondisi': parts.group(2),
              'mu': double.tryParse(parts.group(3) ?? '0') ?? 0,
              'output': parts.group(4),
            };
          }
          return <String, dynamic>{};
        }
        return r;
      }).toList();
    } else {
      return [];
    }

    if (rulesList.isEmpty) return [];

    return [
      const SizedBox(height: 16),
      _stepHeader('2', 'Rules Aktif (${rulesList.length} dari 27 rules)', const Color(0xFF0ea5e9)),
      const SizedBox(height: 8),
      Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(10),
          border: Border.all(color: AppColors.border),
        ),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(10),
          child: Column(
            children: [
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                color: AppColors.primary,
                child: const Row(
                  children: [
                    Expanded(flex: 2, child: Text('Rule', style: TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w700))),
                    Expanded(flex: 4, child: Text('Kondisi (IF)', style: TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w700))),
                    Expanded(flex: 2, child: Text('μ (min)', style: TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w700))),
                    Expanded(flex: 2, child: Text('Output (THEN)', style: TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w700))),
                  ],
                ),
              ),
              ...List.generate(rulesList.length, (i) {
                final r = rulesList[i];
                final rMap = r is Map ? Map<String, dynamic>.from(r) : <String, dynamic>{};
                final isLast = i == rulesList.length - 1;
                return Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
                  decoration: isLast ? null : const BoxDecoration(border: Border(bottom: BorderSide(color: AppColors.border))),
                  child: Row(
                    children: [
                      Expanded(flex: 2, child: Text('${rMap['rule'] ?? rMap['kode'] ?? 'R${i + 1}'}', style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600))),
                      Expanded(flex: 4, child: Text('${rMap['kondisi'] ?? rMap['condition'] ?? '-'}', style: const TextStyle(fontSize: 10))),
                      Expanded(flex: 2, child: Text('${(rMap['mu'] ?? rMap['min'] ?? 0).toStringAsFixed(2)}', style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600))),
                      Expanded(flex: 2, child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 2),
                        decoration: BoxDecoration(
                          color: '${rMap['output']}'.contains('LULUS') ? AppColors.green.withValues(alpha: 0.1) : AppColors.danger.withValues(alpha: 0.1),
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: Text('${rMap['output'] ?? rMap['hasil'] ?? '-'}',
                          style: TextStyle(fontSize: 9, fontWeight: FontWeight.w600,
                            color: '${rMap['output']}'.contains('LULUS') ? AppColors.green : AppColors.danger),
                        ),
                      )),
                    ],
                  ),
                );
              }),
            ],
          ),
        ),
      ),
    ];
  }

  List<Widget> _buildOutputSection(Map<String, dynamic> data, Kelayakan k) {
    final output = data['output'] ?? data;
    final skor = (output['skor_prediksi'] ?? output['skor'] ?? k.skorPrediksi ?? 0).toDouble();
    final hasil = output['hasil'] ?? output['hasil_prediksi'] ?? k.hasilPrediksi ?? '-';
    final hasilStr = '$hasil'.toUpperCase();
    final mkName = k.matakuliah?.namaMk ?? '-';

    Color hasilColor;
    Color hasilBg;
    Color hasilBorder;
    if (hasilStr.contains('LULUS') && !hasilStr.contains('TIDAK')) {
      hasilColor = AppColors.green;
      hasilBg = const Color(0xFFf0fdf4);
      hasilBorder = const Color(0xFFbbf7d0);
    } else if (hasilStr.contains('CUKUP')) {
      hasilColor = AppColors.warning;
      hasilBg = const Color(0xFFFFF8E1);
      hasilBorder = const Color(0xFFfde68a);
    } else {
      hasilColor = AppColors.danger;
      hasilBg = const Color(0xFFfff1f2);
      hasilBorder = const Color(0xFFfecaca);
    }

    return [
      const SizedBox(height: 16),
      _stepHeader('3', 'Output: Grafik Fungsi Keanggotaan & Hasil', const Color(0xFF10b981)),
      const SizedBox(height: 8),
      Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: const Color(0xFFf8fafc),
          borderRadius: BorderRadius.circular(18),
          border: Border.all(color: AppColors.border),
        ),
        child: Column(
          children: [
            const Text('Skor Prediksi', style: TextStyle(fontSize: 12, color: AppColors.textMuted, fontWeight: FontWeight.w600)),
            const SizedBox(height: 4),
            Text('${skor.toStringAsFixed(2)}',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.w800, color: hasilColor)),
            const SizedBox(height: 12),
            SizedBox(
              width: double.infinity,
              height: 110,
              child: CustomPaint(
                painter: _OutputGraphPainter(skor, hasilColor),
              ),
            ),
          ],
        ),
      ),
      const SizedBox(height: 16),
      Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: hasilBg,
          borderRadius: BorderRadius.circular(18),
          border: Border.all(color: hasilBorder),
        ),
        child: Column(
          children: [
            Container(
              width: 56, height: 56,
              decoration: BoxDecoration(
                gradient: LinearGradient(colors: [hasilColor, hasilColor.withValues(alpha: 0.7)]),
                borderRadius: BorderRadius.circular(18),
                boxShadow: [BoxShadow(color: hasilColor.withValues(alpha: 0.35), blurRadius: 16, offset: const Offset(0, 6))],
              ),
              child: Icon(
                hasilStr.contains('TIDAK') ? Icons.close_rounded :
                hasilStr.contains('CUKUP') ? Icons.remove_rounded : Icons.check_rounded,
                color: Colors.white, size: 28,
              ),
            ),
            const SizedBox(height: 12),
            Text('Skor Prediksi:',
              style: TextStyle(fontSize: 13, color: hasilColor.withValues(alpha: 0.8))),
            Text(skor.toStringAsFixed(2),
              style: TextStyle(fontSize: 22, fontWeight: FontWeight.w800, color: hasilColor)),
            const SizedBox(height: 8),
            Text('DIPREDIKSI $hasilStr mata kuliah $mkName.',
              textAlign: TextAlign.center,
              style: TextStyle(fontWeight: FontWeight.w800, fontSize: 15, color: hasilColor)),
          ],
        ),
      ),
    ];
  }

  Widget _detailSection(String title, IconData icon, List<Widget> rows) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: AppColors.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
            decoration: const BoxDecoration(
              color: Color(0xFFf8fafc),
              borderRadius: BorderRadius.only(topLeft: Radius.circular(10), topRight: Radius.circular(10)),
              border: Border(bottom: BorderSide(color: AppColors.border)),
            ),
            child: Row(
              children: [
                Icon(icon, size: 14, color: AppColors.primary),
                const SizedBox(width: 8),
                Text(title, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 12, color: AppColors.primary)),
              ],
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(14),
            child: Column(children: rows),
          ),
        ],
      ),
    );
  }

  Widget _detailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(width: 100, child: Text(label, style: const TextStyle(fontSize: 11, color: AppColors.textMuted))),
          Expanded(child: Text(value, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600, color: AppColors.textDark))),
        ],
      ),
    );
  }

  Map<String, dynamic> _parseJson(String json) {
    // ignore: avoid_dynamic_calls
    return (jsonDecode(json) as Map<String, dynamic>).cast<String, dynamic>();
  }

  Future<void> _showAnalisisManualDialog() async {
    final formKey = GlobalKey<FormState>();
    bool submitting = false;
    List<Mahasiswa> mahasiswaList = [];
    List<Matakuliah> filteredMatakuliah = [];

    int? selectedMahasiswaId;
    int? selectedMatakuliahId;
    String selectedTahunAjaran = _tahunAjaranList.isNotEmpty ? _tahunAjaranList.first : '2025/2026';
    String selectedSemester = 'Ganjil';

    final result = await _api.getKelayakanCreate();
    if (!mounted) return;
    if (result['status'] == 'success') {
      mahasiswaList = (result['data']['mahasiswa'] as List)
          .map((e) => Mahasiswa.fromJson(e)).toList();
      // ignore: unused_local_variable
      final matakuliahList = [];
    }

    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (ctx) {
        return StatefulBuilder(
          builder: (context, setDialogState) {
            return Dialog(
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
              child: ConstrainedBox(
                constraints: const BoxConstraints(maxWidth: 500, maxHeight: 520),
                child: Form(
                  key: formKey,
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Container(
                        padding: const EdgeInsets.fromLTRB(24, 20, 24, 14),
                        decoration: const BoxDecoration(
                          border: Border(bottom: BorderSide(color: AppColors.border)),
                        ),
                        child: Row(
                          children: [
                            Container(
                              width: 32, height: 32,
                              decoration: BoxDecoration(
                                color: AppColors.accent.withValues(alpha: 0.1),
                                borderRadius: BorderRadius.circular(8),
                              ),
                              child: const Icon(Icons.calculate_rounded, size: 16, color: AppColors.accent),
                            ),
                            const SizedBox(width: 12),
                            const Text('Analisis Manual', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16, color: AppColors.primary)),
                          ],
                        ),
                      ),
                      Flexible(
                        child: SingleChildScrollView(
                          padding: const EdgeInsets.fromLTRB(24, 16, 24, 8),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text('Mahasiswa', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: AppColors.textDark)),
                              const SizedBox(height: 6),
                              DropdownButtonFormField<int>(
                                initialValue: selectedMahasiswaId,
                                items: mahasiswaList.map((m) => DropdownMenuItem(
                                  value: m.id,
                                  child: Text('${m.nama} (${m.nim})', style: const TextStyle(fontSize: 13)),
                                )).toList(),
                                onChanged: (v) async {
                                  setDialogState(() {
                                    selectedMahasiswaId = v;
                                    selectedMatakuliahId = null;
                                    filteredMatakuliah = [];
                                  });
                                  if (v != null) {
                                    final mkResult = await _api.getMatakuliahByMahasiswa(v);
                                    if (ctx.mounted && mkResult['status'] == 'success') {
                                      setDialogState(() {
                                        filteredMatakuliah = (mkResult['data'] as List)
                                            .map((e) => Matakuliah.fromJson(e)).toList();
                                      });
                                    }
                                  }
                                },
                                validator: (v) => v == null ? 'Pilih mahasiswa' : null,
                                decoration: const InputDecoration(
                                  hintText: '-- Pilih Mahasiswa --',
                                  contentPadding: EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                                ),
                                isExpanded: true,
                              ),
                              const SizedBox(height: 16),
                              const Text('Mata Kuliah', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: AppColors.textDark)),
                              const SizedBox(height: 6),
                              DropdownButtonFormField<int>(
                                initialValue: selectedMatakuliahId,
                                items: [
                                  const DropdownMenuItem(value: null, child: Text('-- Pilih Mahasiswa Terlebih Dahulu --', style: TextStyle(fontSize: 13))),
                                  ...filteredMatakuliah.map((mk) => DropdownMenuItem(
                                    value: mk.id,
                                    child: Text('${mk.kodeMk} - ${mk.namaMk} (${mk.sks} SKS)', style: const TextStyle(fontSize: 13)),
                                  )),
                                ],
                                onChanged: (v) => setDialogState(() => selectedMatakuliahId = v),
                                validator: (v) => v == null ? 'Pilih mata kuliah' : null,
                                decoration: const InputDecoration(
                                  contentPadding: EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                                ),
                                isExpanded: true,
                              ),
                              const SizedBox(height: 16),
                              const Text('Tahun Ajaran', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: AppColors.textDark)),
                              const SizedBox(height: 6),
                              DropdownButtonFormField<String>(
                                initialValue: selectedTahunAjaran,
                                items: _tahunAjaranList.map((t) => DropdownMenuItem(
                                  value: t,
                                  child: Text(t, style: const TextStyle(fontSize: 13)),
                                )).toList(),
                                onChanged: (v) {
                                  if (v != null) setDialogState(() => selectedTahunAjaran = v);
                                },
                                validator: (v) => v == null ? 'Pilih tahun ajaran' : null,
                                decoration: const InputDecoration(
                                  contentPadding: EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                                ),
                                isExpanded: true,
                              ),
                              const SizedBox(height: 16),
                              const Text('Semester', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: AppColors.textDark)),
                              const SizedBox(height: 6),
                              DropdownButtonFormField<String>(
                                initialValue: selectedSemester,
                                items: const [
                                  DropdownMenuItem(value: 'Ganjil', child: Text('Ganjil', style: TextStyle(fontSize: 13))),
                                  DropdownMenuItem(value: 'Genap', child: Text('Genap', style: TextStyle(fontSize: 13))),
                                ],
                                onChanged: (v) {
                                  if (v != null) setDialogState(() => selectedSemester = v);
                                },
                                decoration: const InputDecoration(
                                  contentPadding: EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                                ),
                                isExpanded: true,
                              ),
                              const SizedBox(height: 24),
                            ],
                          ),
                        ),
                      ),
                      Container(
                        padding: const EdgeInsets.fromLTRB(24, 12, 24, 16),
                        decoration: const BoxDecoration(
                          border: Border(top: BorderSide(color: AppColors.border)),
                        ),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.end,
                          children: [
                            TextButton(
                              onPressed: submitting ? null : () => Navigator.pop(context),
                              child: const Text('Batal'),
                            ),
                            const SizedBox(width: 12),
                            ElevatedButton(
                              onPressed: submitting ? null : () async {
                                if (!formKey.currentState!.validate()) return;
                                setDialogState(() => submitting = true);
                                try {
                                  final res = await _api.kelayakanProses(
                                    mahasiswaId: selectedMahasiswaId!,
                                    matakuliahId: selectedMatakuliahId!,
                                    tahunAjaran: selectedTahunAjaran,
                                    semester: selectedSemester,
                                  );
                                  if (!context.mounted) return;
                                  Navigator.pop(context);
                                  if (res['status'] == 'success') {
                                    _loadData();
                                    ScaffoldMessenger.of(context).showSnackBar(
                                      const SnackBar(content: Text('Analisis berhasil diproses'), backgroundColor: AppColors.green),
                                    );
                                  } else {
                                    ScaffoldMessenger.of(context).showSnackBar(
                                      SnackBar(content: Text(res['message'] ?? 'Gagal memproses'), backgroundColor: AppColors.danger),
                                    );
                                  }
                                } catch (e) {
                                  if (!context.mounted) return;
                                  Navigator.pop(context);
                                  ScaffoldMessenger.of(context).showSnackBar(
                                    SnackBar(content: Text('Error: $e'), backgroundColor: AppColors.danger),
                                  );
                                }
                              },
                              child: submitting
                                  ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                                  : const Text('Hitung Prediksi'),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            );
          },
        );
      },
    );
  }

  Future<void> _showBatchSemuaDialog() async {
    final ta = _tahunAjaranList.isNotEmpty ? _tahunAjaranList.first : '2025/2026';
    final smt = 'Ganjil';

    final confirmed = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: Row(
          children: [
            Icon(Icons.bolt_rounded, color: AppColors.warning),
            const SizedBox(width: 10),
            const Text('Batch Semua', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w700)),
          ],
        ),
        content: Text('Analisis batch untuk semua kombinasi mahasiswa × mata kuliah?\n\nTahun Ajaran: $ta\nSemester: $smt'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Batal')),
          ElevatedButton(
            onPressed: () => Navigator.pop(ctx, true),
            style: ElevatedButton.styleFrom(backgroundColor: AppColors.warning),
            child: const Text('Proses Batch'),
          ),
        ],
      ),
    );
    if (confirmed != true) return;
    if (!mounted) return;

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Memproses batch...'), backgroundColor: AppColors.primary),
    );
    try {
      final result = await _api.kelayakanBatch(tahunAjaran: ta, semester: smt);
      if (!mounted) return;
      if (result['status'] == 'success') {
        _loadData();
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(result['message'] ?? 'Batch selesai'), backgroundColor: AppColors.green),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(result['message'] ?? 'Gagal'), backgroundColor: AppColors.danger),
        );
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error: $e'), backgroundColor: AppColors.danger),
        );
      }
    }
  }

  Widget _buildBody() {
    if (_isLoading) {
      return const AppLoadingState(label: 'Memuat data prediksi...');
    }
    return RefreshIndicator(
      onRefresh: _loadData,
      color: AppColors.primary,
      child: SingleChildScrollView(
        padding: const EdgeInsets.all(12),
        child: Column(
          children: [
            LayoutBuilder(builder: (context, constraints) {
              final isWide = constraints.maxWidth > 600;
              final cards = [
                _buildStatCard('Total Dianalisis', '${_kelayakanList.length}', AppColors.statKelayakan, Icons.people_rounded),
                _buildStatCard('Lulus', '${_countPredikat('lulus')}', AppColors.statNilai, Icons.check_circle_rounded),
                _buildStatCard('Cukup', '${_countPredikat('cukup')}', const [Color(0xFFd97706), Color(0xFFfbbf24)], Icons.remove_circle_outline_rounded),
                _buildStatCard('Tidak Lulus', '${_countPredikat('tidak_lulus')}', AppColors.statPresensi, Icons.cancel_rounded),
              ];
              if (isWide) {
                return Row(children: cards.map((c) => Expanded(child: Padding(padding: const EdgeInsets.symmetric(horizontal: 4), child: c))).toList());
              }
              return Wrap(
                runSpacing: 8,
                spacing: 8,
                children: cards.map((c) => SizedBox(width: (constraints.maxWidth - 8) / 2, child: c)).toList(),
              );
            }),
            const SizedBox(height: 14),
            Row(
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                _buildActionButton(Icons.calculate_rounded, 'Analisis Manual', AppGradients.primary, _showAnalisisManualDialog),
                const SizedBox(width: 8),
                _buildActionButton(Icons.bolt_rounded, 'Batch Semua', LinearGradient(colors: [AppColors.warning, AppColors.accentLight]), _showBatchSemuaDialog),
              ],
            ),
            const SizedBox(height: 14),
            if (_kelayakanList.isEmpty)
              const Padding(
                padding: EdgeInsets.only(top: 32),
                child: AppEmptyState(
                  icon: Icons.analytics_outlined,
                  title: 'Belum ada data prediksi',
                  subtitle: 'Jalankan Analisis Manual atau Batch Semua untuk mulai.',
                ),
              )
            else
              AppFadeIn(
                child: Container(
                decoration: cardDecoration(elevated: true),
                clipBehavior: Clip.antiAlias,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const AppSectionHeader(
                      icon: Icons.analytics_rounded,
                      title: 'Daftar Prediksi Kelulusan',
                      gradient: AppColors.statKelayakan,
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
                          DataColumn(label: Text('NIM')),
                          DataColumn(label: Text('Nama Mahasiswa')),
                          DataColumn(label: Text('Mata Kuliah')),
                          DataColumn(label: Text('Kehadiran')),
                          DataColumn(label: Text('Nilai\nTugas')),
                          DataColumn(label: Text('Keaktifan')),
                          DataColumn(label: Text('Skor')),
                          DataColumn(label: Text('Hasil')),
                          DataColumn(label: Text('Aksi')),
                        ],
                        rows: List.generate(_kelayakanList.length, (i) {
                          final k = _kelayakanList[i];
                          final mhs = k.mahasiswa;
                          final predikatColor = _getPredikatColor(k.hasilPrediksi);
                          final kehadiranPct = k.kehadiran ?? 0;
                          final kehadiranColor = kehadiranPct >= 75 ? AppColors.green : kehadiranPct >= 50 ? AppColors.warning : AppColors.danger;
                          return DataRow(
                            color: WidgetStateProperty.all(zebraRowColor(i)),
                            cells: [
                            DataCell(Text('${i + 1}')),
                            DataCell(Text(mhs?.nim ?? '-', style: const TextStyle(fontWeight: FontWeight.w500))),
                            DataCell(Text(mhs?.nama ?? '-', style: const TextStyle(fontWeight: FontWeight.w500))),
                            DataCell(Text(k.matakuliah?.namaMk ?? '-', style: const TextStyle(fontWeight: FontWeight.w600))),
                            DataCell(AppStatusPill(label: '${kehadiranPct.toStringAsFixed(1)}%', color: kehadiranColor)),
                            DataCell(Text(k.nilaiTugas?.toStringAsFixed(1) ?? '-', style: const TextStyle(fontWeight: FontWeight.w600))),
                            DataCell(Text(k.keaktifanDiskusi?.toStringAsFixed(1) ?? '-', style: const TextStyle(fontWeight: FontWeight.w600))),
                            DataCell(Text(
                              k.skorPrediksi?.toStringAsFixed(2) ?? '-',
                              style: TextStyle(
                                fontWeight: FontWeight.w700,
                                color: (k.skorPrediksi ?? 0) >= 60 ? AppColors.green : (k.skorPrediksi ?? 0) >= 40 ? AppColors.warning : AppColors.danger,
                              ),
                            )),
                            DataCell(Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                Icon(
                                  k.hasilPrediksi == 'lulus' ? Icons.check_circle_rounded :
                                  k.hasilPrediksi == 'cukup' ? Icons.remove_circle_outline_rounded : Icons.cancel_rounded,
                                  size: 13, color: predikatColor,
                                ),
                                const SizedBox(width: 5),
                                AppStatusPill(label: _getPredikatLabel(k.hasilPrediksi), color: predikatColor),
                              ],
                            )),
                            DataCell(
                              GestureDetector(
                                onTap: () => _showDetailDialog(k),
                                child: Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                                  decoration: BoxDecoration(
                                    color: AppColors.primary.withValues(alpha: 0.08),
                                    borderRadius: BorderRadius.circular(20),
                                    border: Border.all(color: AppColors.primary.withValues(alpha: 0.2)),
                                  ),
                                  child: const Row(
                                    mainAxisSize: MainAxisSize.min,
                                    children: [
                                      Icon(Icons.visibility_rounded, size: 13, color: AppColors.primary),
                                      SizedBox(width: 4),
                                      Text('Detail', style: TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: AppColors.primary)),
                                    ],
                                  ),
                                ),
                              ),
                            ),
                          ]);
                        }),
                      ),
                    ),
                  ],
                ),
                ),
              ),
          ],
        ),
      ),
    );
  }

  Widget _buildActionButton(IconData icon, String label, Gradient gradient, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 9),
        decoration: BoxDecoration(
          gradient: gradient,
          borderRadius: BorderRadius.circular(20),
          boxShadow: [
            BoxShadow(color: Colors.black.withValues(alpha: 0.12), blurRadius: 10, offset: const Offset(0, 4)),
          ],
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, size: 16, color: Colors.white),
            const SizedBox(width: 6),
            Text(label, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w600, fontSize: 13)),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final body = _buildBody();
    if (widget.noScaffold) return body;
    return Scaffold(
      appBar: AppBar(title: const Text('Prediksi Kelulusan')),
      body: body,
    );
  }

  Widget _buildStatCard(String label, String value, List<Color> gradient, IconData icon) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        gradient: LinearGradient(colors: gradient, begin: Alignment.topLeft, end: Alignment.bottomRight),
        borderRadius: BorderRadius.circular(18),
        boxShadow: [
          BoxShadow(color: gradient.first.withValues(alpha: 0.3), blurRadius: 16, offset: const Offset(0, 8), spreadRadius: -4),
        ],
      ),
      clipBehavior: Clip.antiAlias,
      child: Stack(
        children: [
          Positioned(right: -6, top: -6, child: Icon(icon, size: 52, color: Colors.white.withValues(alpha: 0.14))),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Icon(icon, color: Colors.white, size: 20),
              const SizedBox(height: 10),
              Text(value, style: const TextStyle(fontSize: 22, fontWeight: FontWeight.w800, color: Colors.white, height: 1.1)),
              const SizedBox(height: 2),
              Text(label, style: TextStyle(fontSize: 12, fontWeight: FontWeight.w500, color: Colors.white.withValues(alpha: 0.88))),
            ],
          ),
        ],
      ),
    );
  }
}

class _CollapsibleRumusWidget extends StatefulWidget {
  @override
  State<_CollapsibleRumusWidget> createState() => _CollapsibleRumusWidgetState();
}

class _CollapsibleRumusWidgetState extends State<_CollapsibleRumusWidget> {
  bool _expanded = false;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        GestureDetector(
          onTap: () => setState(() => _expanded = !_expanded),
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
            decoration: BoxDecoration(
              border: Border.all(color: AppColors.border),
              borderRadius: BorderRadius.circular(8),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(Icons.code_rounded, size: 14, color: AppColors.textMuted),
                const SizedBox(width: 6),
                Text(_expanded ? 'Sembunyikan Rumus Fungsi Keanggotaan' : 'Lihat Rumus Fungsi Keanggotaan',
                  style: const TextStyle(fontSize: 12, color: AppColors.textMuted, fontWeight: FontWeight.w500)),
                const SizedBox(width: 4),
                Icon(_expanded ? Icons.expand_less : Icons.expand_more, size: 14, color: AppColors.textMuted),
              ],
            ),
          ),
        ),
        if (_expanded) ...[
          const SizedBox(height: 8),
          _rumusCard('Kehadiran (0\u2013100%)', [
            _rumusItem('Rendah', 'Linear Turun', AppColors.danger, [
              'μ = 1          jika x \u2264 50',
              'μ = (60\u2212x)/10  jika 50 < x < 60',
              'μ = 0          jika x \u2265 60',
            ]),
            _rumusItem('Sedang', 'Trapesium', AppColors.warning, [
              'μ = 0          jika x \u2264 50',
              'μ = (x\u221250)/10  jika 50 < x \u2264 60',
              'μ = 1          jika 60 < x \u2264 75',
              'μ = (85\u2212x)/10  jika 75 < x < 85',
              'μ = 0          jika x \u2265 85',
            ]),
            _rumusItem('Tinggi', 'Linear Naik', AppColors.green, [
              'μ = 0          jika x \u2264 75',
              'μ = (x\u221275)/10  jika 75 < x < 85',
              'μ = 1          jika x \u2265 85',
            ]),
          ]),
          const SizedBox(height: 8),
          _rumusCard('Nilai Tugas (0\u2013100)', [
            _rumusItem('Rendah', 'Linear Turun', AppColors.danger, [
              'μ = 1          jika x \u2264 50',
              'μ = (60\u2212x)/10  jika 50 < x < 60',
              'μ = 0          jika x \u2265 60',
            ]),
            _rumusItem('Sedang', 'Trapesium', AppColors.warning, [
              'μ = 0          jika x \u2264 50',
              'μ = (x\u221250)/10  jika 50 < x \u2264 60',
              'μ = 1          jika 60 < x \u2264 75',
              'μ = (85\u2212x)/10  jika 75 < x < 85',
              'μ = 0          jika x \u2265 85',
            ]),
            _rumusItem('Tinggi', 'Linear Naik', AppColors.green, [
              'μ = 0          jika x \u2264 75',
              'μ = (x\u221275)/10  jika 75 < x < 85',
              'μ = 1          jika x \u2265 85',
            ]),
          ]),
          const SizedBox(height: 8),
          _rumusCard('Keaktifan Diskusi (0\u2013100)', [
            _rumusItem('Rendah', 'Linear Turun', AppColors.danger, [
              'μ = 1          jika x \u2264 40',
              'μ = (50\u2212x)/10  jika 40 < x < 50',
              'μ = 0          jika x \u2265 50',
            ]),
            _rumusItem('Sedang', 'Trapesium', AppColors.warning, [
              'μ = 0          jika x \u2264 40',
              'μ = (x\u221240)/10  jika 40 < x \u2264 50',
              'μ = 1          jika 50 < x \u2264 70',
              'μ = (80\u2212x)/10  jika 70 < x < 80',
              'μ = 0          jika x \u2265 80',
            ]),
            _rumusItem('Tinggi', 'Linear Naik', AppColors.green, [
              'μ = 0          jika x \u2264 70',
              'μ = (x\u221270)/10  jika 70 < x < 80',
              'μ = 1          jika x \u2265 80',
            ]),
          ]),
          const SizedBox(height: 8),
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              border: Border.all(color: AppColors.border),
              borderRadius: BorderRadius.circular(8),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Defuzzifikasi \u2014 Weighted Average',
                  style: TextStyle(fontWeight: FontWeight.w700, fontSize: 12, color: AppColors.primary)),
                const SizedBox(height: 6),
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: const Color(0xFFf8fafc),
                    borderRadius: BorderRadius.circular(6),
                    border: Border.all(color: AppColors.border),
                  ),
                  child: const Text(
                    'Skor = \u03a3(\u03bc\u1d62 \u00d7 centroid\u1d62) / \u03a3(\u03bc\u1d62)\n\n'
                    'Centroid: Tidak Lulus = 20, Cukup = 50, Lulus = 80\n'
                    'Threshold: Skor \u2265 50 \u2192 LULUS, Skor < 50 \u2192 TIDAK LULUS',
                    style: TextStyle(fontSize: 11, color: AppColors.textDark, height: 1.5),
                  ),
                ),
              ],
            ),
          ),
        ],
      ],
    );
  }

  Widget _rumusCard(String title, List<Widget> items) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        border: Border.all(color: AppColors.border),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(title, style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 12, color: AppColors.primary)),
          const SizedBox(height: 6),
          ...items,
        ],
      ),
    );
  }

  Widget _rumusItem(String label, String shape, Color color, List<String> formulas) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 1),
                decoration: BoxDecoration(color: color, borderRadius: BorderRadius.circular(3)),
                child: Text(label, style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.w600)),
              ),
              const SizedBox(width: 4),
              Text('($shape)', style: const TextStyle(fontSize: 10, color: AppColors.textMuted)),
            ],
          ),
          const SizedBox(height: 2),
          Container(
            padding: const EdgeInsets.all(6),
            decoration: BoxDecoration(
              color: const Color(0xFFf8fafc),
              borderRadius: BorderRadius.circular(4),
              border: Border.all(color: AppColors.border),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: formulas.map((f) => Text(f, style: const TextStyle(fontSize: 10, fontFamily: 'monospace', color: AppColors.textDark, height: 1.4))).toList(),
            ),
          ),
        ],
      ),
    );
  }
}

class _MembershipGraphPainter extends CustomPainter {
  final double value;
  final bool isKeaktifan;

  _MembershipGraphPainter(this.value, this.isKeaktifan);

  @override
  void paint(Canvas canvas, Size size) {
    final w = size.width;
    final h = size.height;
    final margin = const Offset(30, 15);
    final graphW = w - margin.dx * 2;
    final graphH = h - margin.dy * 2 - 12;
    final baseY = margin.dy + graphH;

    double xPos(double v) => margin.dx + (v / 100) * graphW;
    double yPos(double mu) => baseY - mu * graphH;

    canvas.drawLine(Offset(margin.dx, baseY), Offset(margin.dx + graphW, baseY), Paint()..color = const Color(0xFFcbd5e1)..strokeWidth = 1);

    // Subtle horizontal gridlines at 25/50/75/100% membership for readability.
    final gridPaint = Paint()..color = const Color(0xFFe2e8f0)..strokeWidth = 1;
    for (final mu in [0.25, 0.5, 0.75, 1.0]) {
      canvas.drawLine(Offset(margin.dx, yPos(mu)), Offset(margin.dx + graphW, yPos(mu)), gridPaint);
    }

    late List<({double x1, double x2, double x3, double x4})> sets;
    if (isKeaktifan) {
      sets = [
        (x1: 0, x2: 0, x3: 40, x4: 50),
        (x1: 40, x2: 50, x3: 70, x4: 80),
        (x1: 70, x2: 80, x3: 100, x4: 100),
      ];
    } else {
      sets = [
        (x1: 0, x2: 0, x3: 50, x4: 60),
        (x1: 50, x2: 60, x3: 75, x4: 85),
        (x1: 75, x2: 85, x3: 100, x4: 100),
      ];
    }

    final colors = [AppColors.danger, AppColors.warning, AppColors.green];
    for (var i = 0; i < 3; i++) {
      final s = sets[i];
      final path = Path()
        ..moveTo(xPos(s.x1), yPos(0))
        ..lineTo(xPos(s.x2), yPos(1))
        ..lineTo(xPos(s.x3), yPos(1))
        ..lineTo(xPos(s.x4), yPos(0))
        ..close();
      final fillPaint = Paint()
        ..color = colors[i].withValues(alpha: 0.15)
        ..style = PaintingStyle.fill;
      canvas.drawPath(path, fillPaint);
      final strokePaint = Paint()
        ..color = colors[i]
        ..strokeWidth = 2.5
        ..style = PaintingStyle.stroke;
      canvas.drawPath(path, strokePaint);
    }

    final markerX = xPos(value.clamp(0, 100));
    final dashPaint = Paint()
      ..color = const Color(0xFF4338CA)
      ..strokeWidth = 2;
    final yStart = margin.dy;
    for (var y = yStart; y < baseY; y += 6) {
      final endY = (y + 3).clamp(yStart, baseY);
      canvas.drawLine(Offset(markerX, y), Offset(markerX, endY), dashPaint);
    }
    canvas.drawCircle(Offset(markerX, baseY), 6, Paint()..color = Colors.white);
    canvas.drawCircle(Offset(markerX, baseY), 6, Paint()..color = const Color(0xFF4338CA).withValues(alpha: 0.25)..style = PaintingStyle.stroke..strokeWidth = 2);
    canvas.drawCircle(Offset(markerX, baseY), 4, Paint()..color = const Color(0xFF4338CA));

    final labelStyle = TextStyle(color: const Color(0xFF4338CA), fontSize: 10, fontWeight: FontWeight.w800);
    final tp = TextPainter(text: TextSpan(text: '${value.toInt()}', style: labelStyle), textDirection: TextDirection.ltr)..layout();
    tp.paint(canvas, Offset(markerX - tp.width / 2, baseY + 4));

    _drawAxisLabel(canvas, '0', margin.dx, baseY + 3);
    _drawAxisLabel(canvas, '50', margin.dx + graphW / 2, baseY + 3);
    _drawAxisLabel(canvas, '100', margin.dx + graphW, baseY + 3);
  }

  void _drawAxisLabel(Canvas canvas, String text, double x, double y) {
    final tp = TextPainter(
      text: TextSpan(text: text, style: const TextStyle(fontSize: 8, color: Color(0xFF64748b))),
      textDirection: TextDirection.ltr,
    )..layout();
    tp.paint(canvas, Offset(x - tp.width / 2, y));
  }

  @override
  bool shouldRepaint(_MembershipGraphPainter old) => old.value != value || old.isKeaktifan != isKeaktifan;
}

class _OutputGraphPainter extends CustomPainter {
  final double score;
  final Color scoreColor;

  _OutputGraphPainter(this.score, this.scoreColor);

  @override
  void paint(Canvas canvas, Size size) {
    final w = size.width;
    final h = size.height;
    final margin = const Offset(10, 20);
    final graphW = w - margin.dx * 2;
    final graphH = h - margin.dy - 15;
    final baseY = margin.dy + graphH;

    double xPos(double v) => margin.dx + (v / 100) * graphW;
    double yPos(double mu) => baseY - mu * graphH;

    canvas.drawLine(Offset(margin.dx, baseY), Offset(margin.dx + graphW, baseY), Paint()..color = const Color(0xFFcbd5e1)..strokeWidth = 1);

    final gridPaint = Paint()..color = const Color(0xFFe2e8f0)..strokeWidth = 1;
    for (final mu in [0.25, 0.5, 0.75, 1.0]) {
      canvas.drawLine(Offset(margin.dx, yPos(mu)), Offset(margin.dx + graphW, yPos(mu)), gridPaint);
    }

    final sets = [
      (x1: 0.0, x2: 0.0, x3: 30.0, x4: 40.0, color: AppColors.danger, label: 'Tidak Lulus'),
      (x1: 30.0, x2: 40.0, x3: 60.0, x4: 70.0, color: AppColors.warning, label: 'Cukup'),
      (x1: 60.0, x2: 70.0, x3: 100.0, x4: 100.0, color: AppColors.green, label: 'Lulus'),
    ];

    for (final s in sets) {
      final path = Path()
        ..moveTo(xPos(s.x1), yPos(0))
        ..lineTo(xPos(s.x2), yPos(1))
        ..lineTo(xPos(s.x3), yPos(1))
        ..lineTo(xPos(s.x4), yPos(0))
        ..close();
      final fillPaint = Paint()
        ..color = s.color.withValues(alpha: 0.12)
        ..style = PaintingStyle.fill;
      canvas.drawPath(path, fillPaint);
      final strokePaint = Paint()
        ..color = s.color
        ..strokeWidth = 2.5
        ..style = PaintingStyle.stroke;
      canvas.drawPath(path, strokePaint);

      final labelTp = TextPainter(
        text: TextSpan(text: s.label, style: TextStyle(fontSize: 8, color: s.color, fontWeight: FontWeight.w600)),
        textDirection: TextDirection.ltr,
      )..layout();
      labelTp.paint(canvas, Offset(xPos((s.x2 + s.x3) / 2) - labelTp.width / 2, 2));
    }

    final markerX = xPos(score.clamp(0, 100));
    final dashPaint = Paint()
      ..color = const Color(0xFF4338CA)
      ..strokeWidth = 2;
    final yStart = margin.dy;
    for (var y = yStart; y < baseY; y += 6) {
      final endY = (y + 3).clamp(yStart, baseY);
      canvas.drawLine(Offset(markerX, y), Offset(markerX, endY), dashPaint);
    }
    canvas.drawCircle(Offset(markerX, baseY), 6, Paint()..color = Colors.white);
    canvas.drawCircle(Offset(markerX, baseY), 6, Paint()..color = const Color(0xFF4338CA).withValues(alpha: 0.25)..style = PaintingStyle.stroke..strokeWidth = 2);
    canvas.drawCircle(Offset(markerX, baseY), 4, Paint()..color = const Color(0xFF4338CA));

    final scoreTp = TextPainter(
      text: TextSpan(text: score.toStringAsFixed(1), style: const TextStyle(fontSize: 10, color: Color(0xFF4338CA), fontWeight: FontWeight.w800)),
      textDirection: TextDirection.ltr,
    )..layout();
    scoreTp.paint(canvas, Offset(markerX - scoreTp.width / 2, baseY + 4));

    _drawAxisLabel(canvas, '0', margin.dx, baseY + 3);
    _drawAxisLabel(canvas, '50', margin.dx + graphW / 2, baseY + 3);
    _drawAxisLabel(canvas, '100', margin.dx + graphW, baseY + 3);
  }

  void _drawAxisLabel(Canvas canvas, String text, double x, double y) {
    final tp = TextPainter(
      text: TextSpan(text: text, style: const TextStyle(fontSize: 8, color: Color(0xFF64748b))),
      textDirection: TextDirection.ltr,
    )..layout();
    tp.paint(canvas, Offset(x - tp.width / 2, y));
  }

  @override
  bool shouldRepaint(_OutputGraphPainter old) => old.score != score;
}