import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../config/app_theme.dart';
import '../../services/api_service.dart';
import '../../providers/auth_provider.dart';
import '../../models/krs_model.dart';
import '../../widgets/common_ui.dart';

class ValidasiKRSScreen extends StatefulWidget {
  final bool noScaffold;
  const ValidasiKRSScreen({super.key, this.noScaffold = false});

  @override
  State<ValidasiKRSScreen> createState() => _ValidasiKRSScreenState();
}

class _ValidasiKRSScreenState extends State<ValidasiKRSScreen> {
  final ApiService _api = ApiService();
  List<KRS> _allKrs = [];
  bool _isLoading = true;
  String _search = '';
  String _filter = 'Semua';

  static const List<String> _filters = ['Semua', 'Pending', 'Disetujui', 'Ditolak'];

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
          _allKrs = (result['data'] as List)
              .map((e) => KRS.fromJson(e))
              .toList();
          _isLoading = false;
        });
      } else {
        if (mounted) setState(() => _isLoading = false);
      }
    } catch (e) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  int get _countPending => _allKrs.where((k) => k.statusValidasi == 'pending').length;
  int get _countDisetujui => _allKrs.where((k) => k.statusValidasi == 'disetujui').length;
  int get _countDitolak => _allKrs.where((k) => k.statusValidasi == 'ditolak').length;

  List<KRS> get _filtered {
    final q = _search.toLowerCase();
    final list = _allKrs.where((k) {
      final mhs = (k.mahasiswa?.nama ?? '').toLowerCase();
      final mk = (k.matakuliah?.namaMk ?? '').toLowerCase();
      final matchSearch = q.isEmpty || mhs.contains(q) || mk.contains(q);
      if (!matchSearch) return false;
      switch (_filter) {
        case 'Pending':
          return k.statusValidasi == 'pending';
        case 'Disetujui':
          return k.statusValidasi == 'disetujui';
        case 'Ditolak':
          return k.statusValidasi == 'ditolak';
        default:
          return true;
      }
    }).toList();
    list.sort((a, b) {
      final pa = a.statusValidasi == 'pending' ? 0 : 1;
      final pb = b.statusValidasi == 'pending' ? 0 : 1;
      if (pa != pb) return pa.compareTo(pb);
      return (a.mahasiswa?.nama ?? '').compareTo(b.mahasiswa?.nama ?? '');
    });
    return list;
  }

  Color _getValidasiColor(String? status) {
    switch (status) {
      case 'disetujui':
        return AppColors.green;
      case 'ditolak':
        return AppColors.danger;
      default:
        return AppColors.warning;
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

  Future<void> _openValidasiDialog(KRS krs) async {
    final ok = await showDialog<bool>(
      context: context,
      builder: (_) => _ValidasiDialog(api: _api, krs: krs),
    );
    if (ok == true && mounted) {
      await _loadData();
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: const Text('Validasi KRS berhasil diproses'),
          backgroundColor: AppColors.green,
          behavior: SnackBarBehavior.floating,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        ),
      );
    }
  }

  Future<void> _logout() async {
    final ok = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Logout'),
        content: const Text('Yakin ingin keluar?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Batal')),
          TextButton(onPressed: () => Navigator.pop(ctx, true), child: const Text('Ya', style: TextStyle(color: Colors.red))),
        ],
      ),
    );
    if (ok == true && mounted) {
      await context.read<AuthProvider>().logout();
    }
  }

  @override
  Widget build(BuildContext context) {
    final body = _buildBody();
    if (widget.noScaffold) return body;
    return Scaffold(
      appBar: AppBar(
        title: const Text('Validasi KRS'),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout_rounded, color: AppColors.textMuted),
            tooltip: 'Logout',
            onPressed: _logout,
          ),
        ],
      ),
      body: body,
    );
  }

  Widget _buildBody() {
    if (_isLoading) return const AppLoadingState(label: 'Memuat data KRS...');

    final filtered = _filtered;

    return RefreshIndicator(
      onRefresh: _loadData,
      color: AppColors.primary,
      child: Column(
        children: [
          Padding(
            padding: const EdgeInsets.fromLTRB(12, 12, 12, 4),
            child: Column(
              children: [
                _buildStatCards(),
                const SizedBox(height: 12),
                _buildSearchAndFilter(),
              ],
            ),
          ),
          Expanded(
            child: filtered.isEmpty
                ? const AppEmptyState(
                    icon: Icons.verified_user_outlined,
                    title: 'Tidak ada data KRS',
                    subtitle: 'Belum ada pengajuan KRS yang cocok dengan filter.',
                  )
                : LayoutBuilder(
                    builder: (context, constraints) {
                      if (constraints.maxWidth >= 700) return _buildTable(filtered);
                      return _buildCardList(filtered);
                    },
                  ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatCards() {
    final cards = [
      ('Total KRS', _allKrs.length, Icons.assignment_rounded, AppColors.statKrs.first),
      ('Pending', _countPending, Icons.access_time_rounded, AppColors.warning),
      ('Disetujui', _countDisetujui, Icons.check_circle_rounded, AppColors.green),
      ('Ditolak', _countDitolak, Icons.cancel_rounded, AppColors.danger),
    ];
    return LayoutBuilder(
      builder: (context, constraints) {
        final width = constraints.maxWidth;
        final itemW = width >= 900 ? (width - 36) / 4 : (width - 12) / 2;
        return Wrap(
          spacing: 12,
          runSpacing: 12,
          children: cards.map((c) => SizedBox(
            width: itemW,
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
              decoration: cardDecoration(elevated: true),
              child: Row(
                children: [
                  Container(
                    width: 34, height: 34,
                    decoration: BoxDecoration(
                      gradient: LinearGradient(colors: [c.$4, c.$4.withValues(alpha: 0.7)]),
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Icon(c.$3, size: 16, color: Colors.white),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('${c.$2}', style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 16, color: AppColors.textDark)),
                        Text(c.$1, style: const TextStyle(fontSize: 11, color: AppColors.textMuted), maxLines: 1, overflow: TextOverflow.ellipsis),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          )).toList(),
        );
      },
    );
  }

  Widget _buildSearchAndFilter() {
    return Column(
      children: [
        TextField(
          onChanged: (v) => setState(() => _search = v.trim()),
          decoration: const InputDecoration(
            hintText: 'Cari mahasiswa atau mata kuliah...',
            prefixIcon: Icon(Icons.search_rounded, size: 20),
            contentPadding: EdgeInsets.symmetric(horizontal: 14, vertical: 12),
          ),
        ),
        const SizedBox(height: 10),
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          child: Row(
            children: _filters.map((f) {
              final selected = _filter == f;
              return Padding(
                padding: const EdgeInsets.only(right: 8),
                child: FilterChip(
                  label: Text(f),
                  selected: selected,
                  onSelected: (_) => setState(() => _filter = f),
                  selectedColor: AppColors.primary.withValues(alpha: 0.15),
                  checkmarkColor: AppColors.primary,
                  labelStyle: TextStyle(
                    color: selected ? AppColors.primary : AppColors.textMuted,
                    fontWeight: FontWeight.w600,
                    fontSize: 12,
                  ),
                  side: BorderSide(color: selected ? AppColors.primary : AppColors.border),
                  showCheckmark: false,
                ),
              );
            }).toList(),
          ),
        ),
      ],
    );
  }

  Widget _buildTable(List<KRS> list) {
    return SingleChildScrollView(
      padding: const EdgeInsets.fromLTRB(12, 8, 12, 12),
      child: AppFadeIn(
        child: Container(
          decoration: cardDecoration(elevated: true),
          clipBehavior: Clip.antiAlias,
          child: SingleChildScrollView(
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
                DataColumn(label: Text('Validasi')),
                DataColumn(label: Text('Aksi')),
              ],
              rows: List.generate(list.length, (i) {
                final k = list[i];
                final mk = k.matakuliah;
                final isPending = k.statusValidasi == 'pending';
                return DataRow(
                  color: WidgetStateProperty.all(zebraRowColor(i)),
                  cells: [
                    DataCell(Text('${i + 1}')),
                    DataCell(Text(k.mahasiswa?.nama ?? '-', style: const TextStyle(fontWeight: FontWeight.w500))),
                    DataCell(Text(mk?.kodeMk ?? '-')),
                    DataCell(Text(mk?.namaMk ?? '-', style: const TextStyle(fontWeight: FontWeight.w500))),
                    DataCell(Text('${mk?.sks ?? 0} SKS')),
                    DataCell(Text(k.dosen?.nama ?? '-')),
                    DataCell(Text(k.tahunAjaran)),
                    DataCell(Text(k.semester)),
                    DataCell(AppStatusPill(
                      label: k.statusValidasi == 'disetujui' ? 'Disetujui' :
                             k.statusValidasi == 'ditolak' ? 'Ditolak' : 'Pending',
                      color: _getValidasiColor(k.statusValidasi),
                    )),
                    DataCell(isPending
                        ? _ValidasiButton(onTap: () => _openValidasiDialog(k))
                        : const Text('Selesai', style: TextStyle(fontSize: 11, color: AppColors.textLight))),
                  ],
                );
              }),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildCardList(List<KRS> list) {
    return ListView.builder(
      padding: const EdgeInsets.fromLTRB(12, 8, 12, 12),
      itemCount: list.length,
      itemBuilder: (context, i) {
        final k = list[i];
        final mk = k.matakuliah;
        final isPending = k.statusValidasi == 'pending';
        return AppFadeIn(
          index: i % 5,
          child: Container(
            margin: const EdgeInsets.only(bottom: 10),
            padding: const EdgeInsets.all(14),
            decoration: cardDecoration(elevated: true),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Container(
                      width: 34, height: 34,
                      decoration: BoxDecoration(
                        gradient: AppGradients.primary,
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: Center(
                        child: Text(
                          (k.mahasiswa?.nama ?? '?').isNotEmpty ? (k.mahasiswa!.nama)[0].toUpperCase() : '?',
                          style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 13),
                        ),
                      ),
                    ),
                    const SizedBox(width: 10),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(k.mahasiswa?.nama ?? '-',
                            style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 13, color: AppColors.textDark),
                            maxLines: 1, overflow: TextOverflow.ellipsis),
                          Text('${k.tahunAjaran} • ${k.semester}',
                            style: const TextStyle(fontSize: 11, color: AppColors.textMuted)),
                        ],
                      ),
                    ),
                    AppStatusPill(
                      label: k.statusValidasi == 'disetujui' ? 'Disetujui' :
                             k.statusValidasi == 'ditolak' ? 'Ditolak' : 'Pending',
                      color: _getValidasiColor(k.statusValidasi),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(mk?.namaMk ?? '-',
                            style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 12.5, color: AppColors.textDark)),
                          Text('${mk?.kodeMk ?? '-'} • ${mk?.sks ?? 0} SKS',
                            style: const TextStyle(fontSize: 11, color: AppColors.textMuted)),
                        ],
                      ),
                    ),
                    Text('Dosen: ${k.dosen?.nama ?? '-'}',
                      style: const TextStyle(fontSize: 11, color: AppColors.textMuted)),
                  ],
                ),
                if (!isPending && k.catatanValidasi != null && k.catatanValidasi!.isNotEmpty) ...[
                  const SizedBox(height: 10),
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(10),
                    decoration: BoxDecoration(
                      color: _getValidasiColor(k.statusValidasi).withValues(alpha: 0.08),
                      borderRadius: BorderRadius.circular(10),
                      border: Border.all(color: _getValidasiColor(k.statusValidasi).withValues(alpha: 0.2)),
                    ),
                    child: Text(
                      'Catatan: ${k.catatanValidasi}\nTanggal: ${_formatDate(k.tglValidasi)}',
                      style: const TextStyle(fontSize: 11, color: AppColors.textMuted),
                    ),
                  ),
                ],
                if (isPending) ...[
                  const SizedBox(height: 12),
                  SizedBox(
                    width: double.infinity,
                    child: _ValidasiButton(onTap: () => _openValidasiDialog(k)),
                  ),
                ],
              ],
            ),
          ),
        );
      },
    );
  }
}

class _ValidasiButton extends StatelessWidget {
  final VoidCallback onTap;
  const _ValidasiButton({required this.onTap});

  @override
  Widget build(BuildContext context) {
    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(10),
        child: Ink(
          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 7),
          decoration: BoxDecoration(
            gradient: AppGradients.accent,
            borderRadius: BorderRadius.circular(10),
            boxShadow: [
              BoxShadow(color: AppColors.accent.withValues(alpha: 0.3), blurRadius: 8, offset: const Offset(0, 3)),
            ],
          ),
          child: const Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              Icon(Icons.shield_rounded, size: 14, color: Colors.white),
              SizedBox(width: 5),
              Text('Validasi', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w600, fontSize: 11)),
            ],
          ),
        ),
      ),
    );
  }
}

class _ValidasiDialog extends StatefulWidget {
  final ApiService api;
  final KRS krs;
  const _ValidasiDialog({required this.api, required this.krs});

  @override
  State<_ValidasiDialog> createState() => _ValidasiDialogState();
}

class _ValidasiDialogState extends State<_ValidasiDialog> {
  final _catatanController = TextEditingController();
  String _status = 'disetujui';
  bool _isSubmitting = false;

  @override
  void initState() {
    super.initState();
    _catatanController.text = widget.krs.catatanValidasi ?? '';
  }

  @override
  void dispose() {
    _catatanController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    setState(() => _isSubmitting = true);
    try {
      final result = await widget.api.prosesValidasiKRS(
        id: widget.krs.id,
        statusValidasi: _status,
        catatan: _catatanController.text.trim(),
      );
      if (!mounted) return;
      if (result['status'] == 'success') {
        Navigator.pop(context, true);
      } else {
        setState(() => _isSubmitting = false);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(result['message'] ?? 'Gagal memproses validasi'),
            backgroundColor: AppColors.danger,
            behavior: SnackBarBehavior.floating,
          ),
        );
      }
    } catch (e) {
      if (!mounted) return;
      setState(() => _isSubmitting = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Terjadi kesalahan: $e'),
          backgroundColor: AppColors.danger,
          behavior: SnackBarBehavior.floating,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final k = widget.krs;
    final mk = k.matakuliah;
    return Dialog(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      child: ConstrainedBox(
        constraints: const BoxConstraints(maxWidth: 440),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: double.infinity,
              padding: const EdgeInsets.fromLTRB(24, 20, 24, 14),
              decoration: const BoxDecoration(
                border: Border(bottom: BorderSide(color: AppColors.border)),
              ),
              child: const Row(
                children: [
                  Icon(Icons.shield_rounded, color: AppColors.accent, size: 22),
                  SizedBox(width: 10),
                  Text('Validasi KRS', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16, color: AppColors.primary)),
                ],
              ),
            ),
            Flexible(
              child: SingleChildScrollView(
                padding: const EdgeInsets.fromLTRB(24, 16, 24, 8),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: AppColors.primary.withValues(alpha: 0.06),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(k.mahasiswa?.nama ?? '-',
                            style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 13, color: AppColors.textDark)),
                          const SizedBox(height: 4),
                          Text('${mk?.namaMk ?? '-'} (${mk?.kodeMk ?? '-'}) • ${mk?.sks ?? 0} SKS',
                            style: const TextStyle(fontSize: 12, color: AppColors.textMuted)),
                          const SizedBox(height: 2),
                          Text('Dosen: ${k.dosen?.nama ?? '-'} • ${k.tahunAjaran} ${k.semester}',
                            style: const TextStyle(fontSize: 12, color: AppColors.textMuted)),
                        ],
                      ),
                    ),
                    const SizedBox(height: 16),
                    const Text('Keputusan Validasi', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: AppColors.textDark)),
                    const SizedBox(height: 4),
                    RadioGroup<String>(
                      groupValue: _status,
                      onChanged: (v) => setState(() => _status = v!),
                      child: Column(
                        children: [
                          RadioListTile<String>(
                            value: 'disetujui',
                            activeColor: AppColors.green,
                            title: const Text('Setujui', style: TextStyle(fontSize: 13)),
                            subtitle: const Text('KRS diterima', style: TextStyle(fontSize: 11)),
                            contentPadding: EdgeInsets.zero,
                            dense: true,
                          ),
                          RadioListTile<String>(
                            value: 'ditolak',
                            activeColor: AppColors.danger,
                            title: const Text('Tolak', style: TextStyle(fontSize: 13)),
                            subtitle: const Text('KRS ditolak', style: TextStyle(fontSize: 11)),
                            contentPadding: EdgeInsets.zero,
                            dense: true,
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 12),
                    const Text('Catatan / Alasan (opsional)', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: AppColors.textDark)),
                    const SizedBox(height: 6),
                    TextField(
                      controller: _catatanController,
                      maxLines: 3,
                      maxLength: 300,
                      decoration: const InputDecoration(
                        hintText: 'Contoh: Silakan lengkapi prasyarat...',
                      ),
                    ),
                  ],
                ),
              ),
            ),
            Container(
              width: double.infinity,
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
                    style: ElevatedButton.styleFrom(
                      backgroundColor: _status == 'disetujui' ? AppColors.green : AppColors.danger,
                    ),
                    child: _isSubmitting
                        ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                        : Text(_status == 'disetujui' ? 'Setujui' : 'Tolak'),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
