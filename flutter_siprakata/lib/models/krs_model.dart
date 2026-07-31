import 'mahasiswa_model.dart';
import 'matakuliah_model.dart';

class KRS {
  final int id;
  final int mahasiswaId;
  final int matakuliahId;
  final int dosenId;
  final String tahunAjaran;
  final String semester;
  final String? status;
  final String? statusValidasi;
  final String? catatanValidasi;
  final String? tglValidasi;
  final Mahasiswa? mahasiswa;
  final Matakuliah? matakuliah;
  final Dosen? dosen;
  final Nilai? nilai;

  KRS({
    required this.id,
    required this.mahasiswaId,
    required this.matakuliahId,
    required this.dosenId,
    required this.tahunAjaran,
    required this.semester,
    this.status,
    this.statusValidasi,
    this.catatanValidasi,
    this.tglValidasi,
    this.mahasiswa,
    this.matakuliah,
    this.dosen,
    this.nilai,
  });

  factory KRS.fromJson(Map<String, dynamic> json) {
    return KRS(
      id: json['id'],
      mahasiswaId: json['mahasiswa_id'],
      matakuliahId: json['matakuliah_id'],
      dosenId: json['dosen_id'],
      tahunAjaran: json['tahun_ajaran'] ?? '',
      semester: json['semester'] ?? '',
      status: json['status'],
      statusValidasi: json['status_validasi'],
      catatanValidasi: json['catatan_validasi'],
      tglValidasi: json['tgl_validasi'],
      mahasiswa: json['mahasiswa'] != null ? Mahasiswa.fromJson(json['mahasiswa']) : null,
      matakuliah: json['matakuliah'] != null ? Matakuliah.fromJson(json['matakuliah']) : null,
      dosen: json['dosen'] != null ? Dosen.fromJson(json['dosen']) : null,
      nilai: json['nilai'] != null ? Nilai.fromJson(json['nilai']) : null,
    );
  }
}

class Dosen {
  final int id;
  final String nidn;
  final String nama;
  final String? email;
  final String? noHp;
  final String? jabatan;
  final int? prodiId;
  final ProgramStudi? prodi;

  Dosen({
    required this.id,
    required this.nidn,
    required this.nama,
    this.email,
    this.noHp,
    this.jabatan,
    this.prodiId,
    this.prodi,
  });

  factory Dosen.fromJson(Map<String, dynamic> json) {
    return Dosen(
      id: json['id'],
      nidn: json['NIDN'] ?? json['nidn'] ?? '',
      nama: json['nama'] ?? '',
      email: json['email'],
      noHp: json['no_hp'],
      jabatan: json['jabatan'],
      prodiId: json['prodi_id'],
      prodi: json['prodi'] != null ? ProgramStudi.fromJson(json['prodi']) : null,
    );
  }
}

class Nilai {
  final int id;
  final int krsId;
  final double? nilaiTugas;
  final double? nilaiUts;
  final double? nilaiUas;
  final double? nilaiAkhir;
  final String? grade;
  final KRS? krs;

  Nilai({
    required this.id,
    required this.krsId,
    this.nilaiTugas,
    this.nilaiUts,
    this.nilaiUas,
    this.nilaiAkhir,
    this.grade,
    this.krs,
  });

  factory Nilai.fromJson(Map<String, dynamic> json) {
    return Nilai(
      id: json['id'],
      krsId: json['krs_id'],
      nilaiTugas: _parseDouble(json['nilai_tugas']),
      nilaiUts: _parseDouble(json['nilai_uts']),
      nilaiUas: _parseDouble(json['nilai_uas']),
      nilaiAkhir: _parseDouble(json['nilai_akhir']),
      grade: json['grade'],
      krs: json['krs'] != null ? KRS.fromJson(json['krs']) : null,
    );
  }

  static double? _parseDouble(dynamic value) {
    if (value == null) return null;
    if (value is num) return value.toDouble();
    if (value is String) return double.tryParse(value);
    return null;
  }
}
