import 'jadwal_model.dart';

class Materi {
  final int id;
  final int jadwalId;
  final int pertemuanKe;
  final String judul;
  final String? deskripsi;
  final String? filePath;
  final String? linkMateri;
  final Jadwal? jadwal;

  Materi({
    required this.id,
    required this.jadwalId,
    required this.pertemuanKe,
    required this.judul,
    this.deskripsi,
    this.filePath,
    this.linkMateri,
    this.jadwal,
  });

  factory Materi.fromJson(Map<String, dynamic> json) {
    return Materi(
      id: json['id'],
      jadwalId: json['jadwal_id'],
      pertemuanKe: json['pertemuan_ke'] ?? 0,
      judul: json['judul'] ?? '',
      deskripsi: json['deskripsi'],
      filePath: json['file_path'],
      linkMateri: json['link_materi'],
      jadwal: json['jadwal'] != null ? Jadwal.fromJson(json['jadwal']) : null,
    );
  }
}
