import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../config/app_theme.dart';
import '../../services/api_service.dart';
import '../../providers/auth_provider.dart';
import '../../models/krs_model.dart';
import '../../models/mahasiswa_model.dart';
import '../../models/matakuliah_model.dart';
import '../../widgets/common_ui.dart';

class KRSScreen extends StatefulWidget {
  final bool noScaffold;
  const KRSScreen({super.key, this.noScaffold = false});

  @override
  State<KRSScreen> createState() => _KRSScreenState();
}

class _KRSScreenState extends State<KRSScreen> {
  final ApiService _api = ApiService();
  List<KRS> _krsList = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    setState(() => _isLoading = true);
    try {
      final result = await _api.getKRSList();
      if (mounted && result['status'] == 'success') {
        setState(() {
          _krsList = (result['data'] as List)
              .map((e) => KRS.fromJson(e))
              .toList();
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Color _getValidasiColor(String? status) {
    switch (status) {
      case 'disetujui': return AppColors.green;
      case 'ditolak': return AppColors.danger;
      default: return AppColors.warning;
    }
  }

  String _formatDate(String? date) {
    if (date == null || date.isEmpty) return '-';
    try {
      final d = DateTime.parse(date);
      return '${d.day.toString().padLeft(2, '0')}/${d.month.toString().padLeft(2, '0')}/${d.year}';
    } catch (_) {
      return date;
    }
  }

  void _showAjukanKRSDialog() {
    final auth = context.read<AuthProvider>();
    showDialog(
      context: context,
      builder: (ctx) => _AjukanKRSDialog(
        api: _api,
        mahasiswaId: auth.mahasiswa?.id ?? auth.user?.id ?? 0,
        mahasiswaNama: auth.mahasiswa?.nama ?? auth.user?.name ?? '-',
        onSubmitted: () {
          _loadData();
        },
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    if (widget.noScaffold) return _buildBody();
    return Scaffold(
      appBar: AppBar(title: const Text('Kartu Rencana Studi')),
      body: _buildBody(),
    );
  }

  Widget _buildBody() {
    if (_isLoading) return const AppLoadingState(label: 'Memuat data KRS...');
    if (_krsList.isEmpty) {
      return Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const Expanded(
            child: AppEmptyState(
              icon: Icons.assignment_outlined,
              title: 'Belum ada KRS',
              subtitle: 'Ajukan KRS pertamamu untuk mulai mengisi rencana studi.',
            ),
          ),
          Padding(
            padding: const EdgeInsets.only(bottom: 32),
            child: _buildAjukanButton(),
          ),
        ],
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
                AppSectionHeader(
                  icon: Icons.assignment_rounded,
                  title: 'Daftar KRS (Kartu Rencana Studi)',
                  gradient: AppColors.statKrs,
                  action: _buildAjukanButton(),
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
                    columnSpacing: 14,
                    columns: const [
                      DataColumn(label: Text('No')),
                      DataColumn(label: Text('Mahasiswa')),
                      DataColumn(label: Text('Kode MK')),
                      DataColumn(label: Text('Mata Kuliah')),
                      DataColumn(label: Text('SKS')),
                      DataColumn(label: Text('Dosen')),
                      DataColumn(label: Text('Thn\nAjaran')),
                      DataColumn(label: Text('Semester')),
                      DataColumn(label: Text('Status')),
                      DataColumn(label: Text('Validasi')),
                      DataColumn(label: Text('Catatan')),
                      DataColumn(label: Text('Tgl\nValidasi')),
                    ],
                    rows: List.generate(_krsList.length, (i) {
                      final k = _krsList[i];
                      final mk = k.matakuliah;
                      final mhs = k.mahasiswa;
                      final validasiColor = _getValidasiColor(k.statusValidasi);
                      final isActive = k.status == 'aktif';
                      return DataRow(
                        color: WidgetStateProperty.all(zebraRowColor(i)),
                        cells: [
                          DataCell(Text('${i + 1}')),
                          DataCell(Text(mhs?.nama ?? '-', style: const TextStyle(fontWeight: FontWeight.w500))),
                          DataCell(Text(mk?.kodeMk ?? '-')),
                          DataCell(Text(mk?.namaMk ?? '-', style: const TextStyle(fontWeight: FontWeight.w500))),
                          DataCell(AppStatusPill(label: '${mk?.sks ?? 0} SKS', color: const Color(0xFFb45309))),
                          DataCell(Text(k.dosen?.nama ?? '-')),
                          DataCell(Text(k.tahunAjaran)),
                          DataCell(Text(k.semester)),
                          DataCell(AppStatusPill(
                            label: isActive ? 'Aktif' : 'Selesai',
                            color: isActive ? AppColors.primary : AppColors.textMuted,
                          )),
                          DataCell(Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Icon(
                                k.statusValidasi == 'disetujui' ? Icons.check_circle_rounded :
                                k.statusValidasi == 'ditolak' ? Icons.cancel_rounded : Icons.access_time_rounded,
                                size: 13, color: validasiColor,
                              ),
                              const SizedBox(width: 5),
                              AppStatusPill(
                                label: k.statusValidasi == 'disetujui' ? 'Disetujui' :
                                       k.statusValidasi == 'ditolak' ? 'Ditolak' : 'Pending',
                                color: validasiColor,
                              ),
                            ],
                          )),
                          DataCell(Text(k.catatanValidasi ?? '-')),
                          DataCell(Text(_formatDate(k.tglValidasi))),
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

  Widget _buildAjukanButton() {
    return _HoverScaleTap(
      onTap: _showAjukanKRSDialog,
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 7),
        decoration: BoxDecoration(
          gradient: AppGradients.primary,
          borderRadius: BorderRadius.circular(20),
          boxShadow: [
            BoxShadow(color: AppColors.primary.withValues(alpha: 0.3), blurRadius: 10, offset: const Offset(0, 4)),
          ],
        ),
        child: const Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(Icons.add_rounded, size: 14, color: Colors.white),
            SizedBox(width: 4),
            Text('Ajukan KRS', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w600, fontSize: 11)),
          ],
        ),
      ),
    );
  }
}

/// Small scale-down-on-tap wrapper for a tactile button feel.
class _HoverScaleTap extends StatefulWidget {
  final Widget child;
  final VoidCallback onTap;
  const _HoverScaleTap({required this.child, required this.onTap});

  @override
  State<_HoverScaleTap> createState() => _HoverScaleTapState();
}

class _HoverScaleTapState extends State<_HoverScaleTap> {
  bool _pressed = false;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: widget.onTap,
      onTapDown: (_) => setState(() => _pressed = true),
      onTapUp: (_) => setState(() => _pressed = false),
      onTapCancel: () => setState(() => _pressed = false),
      child: AnimatedScale(
        scale: _pressed ? 0.94 : 1.0,
        duration: AppMotion.fast,
        child: widget.child,
      ),
    );
  }
}

class _AjukanKRSDialog extends StatefulWidget {
  final ApiService api;
  final int mahasiswaId;
  final String mahasiswaNama;
  final VoidCallback onSubmitted;

  const _AjukanKRSDialog({
    required this.api,
    required this.mahasiswaId,
    required this.mahasiswaNama,
    required this.onSubmitted,
  });

  @override
  State<_AjukanKRSDialog> createState() => _AjukanKRSDialogState();
}

class _AjukanKRSDialogState extends State<_AjukanKRSDialog> {
  final _formKey = GlobalKey<FormState>();
  int? _selectedMahasiswaId;
  int? _selectedMatakuliahId;
  int? _selectedDosenId;
  String? _selectedTahunAjaran;
  String _selectedSemester = 'Ganjil';
  String _selectedStatus = 'aktif';
  bool _loadingOptions = true;
  bool _isSubmitting = false;

  List<Mahasiswa> _mahasiswaList = [];
  List<Matakuliah> _matakuliahList = [];
  List<Dosen> _dosenList = [];
  List<String> _tahunAjaranList = [];

  @override
  void initState() {
    super.initState();
    _loadOptions();
  }

  Future<void> _loadOptions() async {
    final results = await Future.wait([
      widget.api.getMahasiswaList(),
      widget.api.getMatakuliahList(),
      widget.api.getDosenList(),
      widget.api.getTahunAjaranList(),
    ]);
    if (!mounted) return;
    setState(() {
      if (results[0]['status'] == 'success') {
        _mahasiswaList = (results[0]['data'] as List)
            .map((e) => Mahasiswa.fromJson(e)).toList();
        if (_mahasiswaList.any((m) => m.id == widget.mahasiswaId)) {
          _selectedMahasiswaId = widget.mahasiswaId;
        } else if (_mahasiswaList.isNotEmpty) {
          _selectedMahasiswaId = _mahasiswaList.first.id;
        }
      }
      if (results[1]['status'] == 'success') {
        _matakuliahList = (results[1]['data'] as List)
            .map((e) => Matakuliah.fromJson(e)).toList();
      }
      if (results[2]['status'] == 'success') {
        _dosenList = (results[2]['data'] as List)
            .map((e) => Dosen.fromJson(e)).toList();
      }
      if (results[3]['status'] == 'success') {
        _tahunAjaranList = (results[3]['data'] as List)
            .map((e) => e is Map ? (e['tahun']?.toString() ?? '') : e.toString())
            .where((s) => s.isNotEmpty)
            .toSet()
            .toList();
        if (_tahunAjaranList.isNotEmpty) {
          _selectedTahunAjaran = _tahunAjaranList.first;
        }
      }
      _loadingOptions = false;
    });
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    if (_selectedMahasiswaId == null || _selectedMatakuliahId == null || _selectedDosenId == null || _selectedTahunAjaran == null) return;

    setState(() => _isSubmitting = true);
    try {
      final result = await widget.api.createKRS(
        mahasiswaId: _selectedMahasiswaId!,
        matakuliahId: _selectedMatakuliahId!,
        dosenId: _selectedDosenId!,
        tahunAjaran: _selectedTahunAjaran!,
        semester: _selectedSemester,
        status: _selectedStatus,
      );
      if (!mounted) return;
      if (result['status'] == 'success') {
        Navigator.pop(context);
        widget.onSubmitted();
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: const Text('KRS berhasil diajukan'),
            backgroundColor: AppColors.green,
            behavior: SnackBarBehavior.floating,
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          ),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(result['message'] ?? 'Gagal mengajukan KRS'),
            backgroundColor: AppColors.danger,
            behavior: SnackBarBehavior.floating,
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          ),
        );
        setState(() => _isSubmitting = false);
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Terjadi kesalahan: $e'),
          backgroundColor: AppColors.danger,
          behavior: SnackBarBehavior.floating,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        ),
      );
      setState(() => _isSubmitting = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Dialog(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      child: ConstrainedBox(
        constraints: const BoxConstraints(maxWidth: 480, maxHeight: 600),
        child: _loadingOptions
            ? const Padding(
                padding: EdgeInsets.all(48),
                child: AppLoadingState(label: 'Memuat opsi formulir...'),
              )
            : Form(
                key: _formKey,
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
                            width: 34, height: 34,
                            decoration: BoxDecoration(
                              gradient: AppGradients.primary,
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: const Icon(Icons.assignment_rounded, size: 16, color: Colors.white),
                          ),
                          const SizedBox(width: 12),
                          const Text('Ajukan KRS', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16, color: AppColors.primary)),
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
                              initialValue: _selectedMahasiswaId,
                              items: _mahasiswaList.map((m) => DropdownMenuItem(
                                value: m.id,
                                child: Text(m.nama, style: const TextStyle(fontSize: 13)),
                              )).toList(),
                              onChanged: (v) => setState(() => _selectedMahasiswaId = v),
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
                              initialValue: _selectedMatakuliahId,
                              items: _matakuliahList.map((mk) => DropdownMenuItem(
                                value: mk.id,
                                child: Text('${mk.kodeMk} - ${mk.namaMk} (${mk.sks} SKS)', style: const TextStyle(fontSize: 13)),
                              )).toList(),
                              onChanged: (v) => setState(() => _selectedMatakuliahId = v),
                              validator: (v) => v == null ? 'Pilih mata kuliah' : null,
                              decoration: const InputDecoration(
                                hintText: '-- Pilih Mata Kuliah --',
                                contentPadding: EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                              ),
                              isExpanded: true,
                            ),
                            const SizedBox(height: 16),
                            const Text('Dosen Pengampu', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: AppColors.textDark)),
                            const SizedBox(height: 6),
                            DropdownButtonFormField<int>(
                              initialValue: _selectedDosenId,
                              items: _dosenList.map((d) => DropdownMenuItem(
                                value: d.id,
                                child: Text(d.nama, style: const TextStyle(fontSize: 13)),
                              )).toList(),
                              onChanged: (v) => setState(() => _selectedDosenId = v),
                              validator: (v) => v == null ? 'Pilih dosen' : null,
                              decoration: const InputDecoration(
                                hintText: '-- Pilih Dosen --',
                                contentPadding: EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                              ),
                              isExpanded: true,
                            ),
                            const SizedBox(height: 16),
                            const Text('Tahun Ajaran', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: AppColors.textDark)),
                            const SizedBox(height: 6),
                            DropdownButtonFormField<String>(
                              initialValue: _selectedTahunAjaran,
                              items: _tahunAjaranList.map((t) => DropdownMenuItem(
                                value: t,
                                child: Text(t, style: const TextStyle(fontSize: 13)),
                              )).toList(),
                              onChanged: (v) => setState(() => _selectedTahunAjaran = v),
                              validator: (v) => v == null ? 'Pilih tahun ajaran' : null,
                              decoration: const InputDecoration(
                                hintText: 'Pilih Tahun Ajaran',
                                contentPadding: EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                              ),
                              isExpanded: true,
                            ),
                            const SizedBox(height: 16),
                            const Text('Semester', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: AppColors.textDark)),
                            const SizedBox(height: 6),
                            DropdownButtonFormField<String>(
                              initialValue: _selectedSemester,
                              items: const [
                                DropdownMenuItem(value: 'Ganjil', child: Text('Ganjil', style: TextStyle(fontSize: 13))),
                                DropdownMenuItem(value: 'Genap', child: Text('Genap', style: TextStyle(fontSize: 13))),
                              ],
                              onChanged: (v) {
                                if (v != null) setState(() => _selectedSemester = v);
                              },
                              decoration: const InputDecoration(
                                hintText: '-- Pilih --',
                                contentPadding: EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                              ),
                              isExpanded: true,
                            ),
                            const SizedBox(height: 16),
                            const Text('Status', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: AppColors.textDark)),
                            const SizedBox(height: 6),
                            DropdownButtonFormField<String>(
                              initialValue: _selectedStatus,
                              items: const [
                                DropdownMenuItem(value: 'aktif', child: Text('Aktif', style: TextStyle(fontSize: 13))),
                                DropdownMenuItem(value: 'selesai', child: Text('Selesai', style: TextStyle(fontSize: 13))),
                              ],
                              onChanged: (v) {
                                if (v != null) setState(() => _selectedStatus = v);
                              },
                              decoration: const InputDecoration(
                                hintText: '-- Pilih --',
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
                            onPressed: _isSubmitting ? null : () => Navigator.pop(context),
                            child: const Text('Batal'),
                          ),
                          const SizedBox(width: 12),
                          ElevatedButton(
                            onPressed: _isSubmitting ? null : _submit,
                            child: _isSubmitting
                                ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                                : const Text('Ajukan'),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
      ),
    );
  }
}