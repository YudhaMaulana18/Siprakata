import 'package:flutter/material.dart';
import '../../config/app_theme.dart';
import '../../config/api_config.dart';
import '../../services/api_service.dart';
import '../../models/materi_model.dart';
import '../../widgets/common_ui.dart';
import 'package:url_launcher/url_launcher.dart';

class MateriScreen extends StatefulWidget {
  final bool noScaffold;
  const MateriScreen({super.key, this.noScaffold = false});

  @override
  State<MateriScreen> createState() => _MateriScreenState();
}

class _MateriScreenState extends State<MateriScreen> {
  final ApiService _api = ApiService();
  List<Materi> _materiList = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    setState(() => _isLoading = true);
    try {
      final result = await _api.getMateriList();
      if (mounted) {
        if (result['status'] == 'success') {
          setState(() {
            _materiList = (result['data'] as List)
                .map((e) => Materi.fromJson(e))
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

  void _openLink(String url) async {
    final uri = Uri.parse(url);
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri, mode: LaunchMode.externalApplication);
    }
  }

  String get _baseHost => ApiConfig.baseUrl.replaceAll('/api/', '');

  void _openFile(String filePath) async {
    final url = '$_baseHost/storage/$filePath';
    final uri = Uri.parse(url);
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri, mode: LaunchMode.externalApplication);
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Tidak dapat membuka file'), backgroundColor: AppColors.danger),
      );
    }
  }

  Widget _buildBody() {
    if (_isLoading) return const AppLoadingState(label: 'Memuat materi...');
    if (_materiList.isEmpty) {
      return const AppEmptyState(
        icon: Icons.book_outlined,
        title: 'Belum ada materi',
        subtitle: 'Materi kuliah yang diunggah dosen akan tampil di sini.',
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
                  icon: Icons.menu_book_rounded,
                  title: 'Daftar Materi Kuliah',
                  gradient: AppColors.statMateri,
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
                      DataColumn(label: Text('Mata Kuliah')),
                      DataColumn(label: Text('Dosen')),
                      DataColumn(label: Text('Pertemuan')),
                      DataColumn(label: Text('Judul')),
                      DataColumn(label: Text('File / Link')),
                    ],
                    rows: List.generate(_materiList.length, (i) {
                      final m = _materiList[i];
                      final mk = m.jadwal?.matakuliah;
                      final dosen = m.jadwal?.dosen;
                      return DataRow(
                        color: WidgetStateProperty.all(zebraRowColor(i)),
                        cells: [
                          DataCell(Text('${i + 1}')),
                          DataCell(Text(mk?.namaMk ?? '-', style: const TextStyle(fontWeight: FontWeight.w500))),
                          DataCell(Text(dosen?.nama ?? '-')),
                          DataCell(Center(child: Text('Ke-${m.pertemuanKe}'))),
                          DataCell(Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Text(m.judul, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 12)),
                              if (m.deskripsi != null && m.deskripsi!.isNotEmpty)
                                Text(m.deskripsi!.length > 50 ? '${m.deskripsi!.substring(0, 50)}...' : m.deskripsi!,
                                  style: TextStyle(fontSize: 10, color: AppColors.textMuted)),
                            ],
                          )),
                          DataCell(Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              if (m.filePath != null && m.filePath!.isNotEmpty)
                                _buildActionChip(Icons.download_rounded, 'File', () => _openFile(m.filePath!), AppColors.green),
                              if (m.filePath != null && m.filePath!.isNotEmpty && m.linkMateri != null && m.linkMateri!.isNotEmpty)
                                const SizedBox(width: 6),
                              if (m.linkMateri != null && m.linkMateri!.isNotEmpty)
                                _buildActionChip(Icons.link_rounded, 'Link', () => _openLink(m.linkMateri!), AppColors.info),
                              if ((m.filePath == null || m.filePath!.isEmpty) && (m.linkMateri == null || m.linkMateri!.isEmpty))
                                const Text('-', style: TextStyle(color: AppColors.textMuted)),
                            ],
                          )),
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
      appBar: AppBar(title: const Text('Materi Kuliah')),
      body: body,
    );
  }

  Widget _buildActionChip(IconData icon, String label, VoidCallback onTap, Color color) {
    return _HoverChip(icon: icon, label: label, onTap: onTap, color: color);
  }
}

/// Filled, tactile chip with a hover-lift on web, replacing the old
/// plain outlined chip so file/link actions feel more clickable.
class _HoverChip extends StatefulWidget {
  final IconData icon;
  final String label;
  final VoidCallback onTap;
  final Color color;
  const _HoverChip({required this.icon, required this.label, required this.onTap, required this.color});

  @override
  State<_HoverChip> createState() => _HoverChipState();
}

class _HoverChipState extends State<_HoverChip> {
  bool _hover = false;

  @override
  Widget build(BuildContext context) {
    return MouseRegion(
      onEnter: (_) => setState(() => _hover = true),
      onExit: (_) => setState(() => _hover = false),
      child: GestureDetector(
        onTap: widget.onTap,
        child: AnimatedContainer(
          duration: AppMotion.fast,
          padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 5),
          decoration: BoxDecoration(
            color: widget.color.withValues(alpha: _hover ? 0.18 : 0.1),
            borderRadius: BorderRadius.circular(8),
            border: Border.all(color: widget.color.withValues(alpha: 0.35)),
          ),
          child: Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              Icon(widget.icon, size: 12, color: widget.color),
              const SizedBox(width: 4),
              Text(widget.label, style: TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: widget.color)),
            ],
          ),
        ),
      ),
    );
  }
}