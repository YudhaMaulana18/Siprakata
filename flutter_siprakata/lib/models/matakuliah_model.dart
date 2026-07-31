import 'mahasiswa_model.dart';

class Matakuliah {
  final int id;
  final String kodeMk;
  final String namaMk;
  final int sks;
  final int? semester;
  final int? prodiId;
  final ProgramStudi? prodi;

  Matakuliah({
    required this.id,
    required this.kodeMk,
    required this.namaMk,
    required this.sks,
    this.semester,
    this.prodiId,
    this.prodi,
  });

  factory Matakuliah.fromJson(Map<String, dynamic> json) {
    return Matakuliah(
      id: json['id'],
      kodeMk: json['kode_mk'] ?? '',
      namaMk: json['nama_mk'] ?? '',
      sks: json['sks'] is int ? json['sks'] : int.tryParse(json['sks']?.toString() ?? '') ?? 0,
      semester: int.tryParse(json['semester']?.toString() ?? ''),
      prodiId: json['prodi_id'],
      prodi: json['prodi'] != null ? ProgramStudi.fromJson(json['prodi']) : null,
    );
  }
}
