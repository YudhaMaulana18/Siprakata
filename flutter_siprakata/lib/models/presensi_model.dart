import 'jadwal_model.dart';
import 'mahasiswa_model.dart';

class Presensi {
  final int id;
  final int jadwalId;
  final int mahasiswaId;
  final String tanggal;
  final int pertemuanKe;
  final String statusHadir;
  final String? keterangan;
  final Jadwal? jadwal;
  final Mahasiswa? mahasiswa;

  Presensi({
    required this.id,
    required this.jadwalId,
    required this.mahasiswaId,
    required this.tanggal,
    required this.pertemuanKe,
    required this.statusHadir,
    this.keterangan,
    this.jadwal,
    this.mahasiswa,
  });

  factory Presensi.fromJson(Map<String, dynamic> json) {
    return Presensi(
      id: json['id'],
      jadwalId: json['jadwal_id'],
      mahasiswaId: json['mahasiswa_id'],
      tanggal: json['tanggal'] ?? '',
      pertemuanKe: json['pertemuan_ke'] ?? 0,
      statusHadir: json['status_hadir'] ?? '',
      keterangan: json['keterangan'],
      jadwal: json['jadwal'] != null ? Jadwal.fromJson(json['jadwal']) : null,
      mahasiswa: json['mahasiswa'] != null ? Mahasiswa.fromJson(json['mahasiswa']) : null,
    );
  }
}
