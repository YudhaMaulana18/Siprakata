import 'mahasiswa_model.dart';
import 'matakuliah_model.dart';

class Kelayakan {
  final int id;
  final int mahasiswaId;
  final int matakuliahId;
  final String tahunAjaran;
  final String semester;
  final double? kehadiran;
  final double? nilaiTugas;
  final double? keaktifanDiskusi;
  final double? skorPrediksi;
  final String? hasilPrediksi;
  final dynamic detailPerhitungan;
  final String? createdAt;
  final Mahasiswa? mahasiswa;
  final Matakuliah? matakuliah;

  Kelayakan({
    required this.id,
    required this.mahasiswaId,
    required this.matakuliahId,
    required this.tahunAjaran,
    required this.semester,
    this.kehadiran,
    this.nilaiTugas,
    this.keaktifanDiskusi,
    this.skorPrediksi,
    this.hasilPrediksi,
    this.detailPerhitungan,
    this.createdAt,
    this.mahasiswa,
    this.matakuliah,
  });

  factory Kelayakan.fromJson(Map<String, dynamic> json) {
    return Kelayakan(
      id: json['id'],
      mahasiswaId: json['mahasiswa_id'],
      matakuliahId: json['matakuliah_id'],
      tahunAjaran: json['tahun_ajaran'] ?? '',
      semester: json['semester'] ?? '',
      kehadiran: (json['kehadiran'] as num?)?.toDouble(),
      nilaiTugas: (json['nilai_tugas'] as num?)?.toDouble(),
      keaktifanDiskusi: (json['keaktifan_diskusi'] as num?)?.toDouble(),
      skorPrediksi: (json['skor_prediksi'] as num?)?.toDouble(),
      hasilPrediksi: json['hasil_prediksi'],
      detailPerhitungan: json['detail_perhitungan'],
      createdAt: json['created_at'],
      mahasiswa: json['mahasiswa'] != null ? Mahasiswa.fromJson(json['mahasiswa']) : null,
      matakuliah: json['matakuliah'] != null ? Matakuliah.fromJson(json['matakuliah']) : null,
    );
  }
}
