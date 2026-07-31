import 'krs_model.dart';

class Pengumuman {
  final int id;
  final int dosenId;
  final int? jadwalId;
  final String judul;
  final String isi;
  final String prioritas;
  final String tglPosting;
  final String? tglKadaluarsa;
  final String createdAt;
  final Dosen? dosen;
  final dynamic jadwal;

  Pengumuman({
    required this.id,
    required this.dosenId,
    this.jadwalId,
    required this.judul,
    required this.isi,
    required this.prioritas,
    required this.tglPosting,
    this.tglKadaluarsa,
    required this.createdAt,
    this.dosen,
    this.jadwal,
  });

  factory Pengumuman.fromJson(Map<String, dynamic> json) {
    return Pengumuman(
      id: json['id'],
      dosenId: json['dosen_id'],
      jadwalId: json['jadwal_id'],
      judul: json['judul'] ?? '',
      isi: json['isi'] ?? '',
      prioritas: json['prioritas'] ?? 'rendah',
      tglPosting: json['tgl_posting'] ?? '',
      tglKadaluarsa: json['tgl_kadaluarsa'],
      createdAt: json['created_at'] ?? '',
      dosen: json['dosen'] != null ? Dosen.fromJson(json['dosen']) : null,
      jadwal: json['jadwal'],
    );
  }
}
