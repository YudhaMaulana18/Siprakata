import 'matakuliah_model.dart';
import 'krs_model.dart';

class Jadwal {
  final int id;
  final int matakuliahId;
  final int dosenId;
  final int? ruanganId;
  final int? tahunAjaranId;
  final String hari;
  final String jamMulai;
  final String jamSelesai;
  final String? ruangan;
  final String? semester;
  final Matakuliah? matakuliah;
  final Dosen? dosen;
  final Ruangan? ruanganRef;
  final TahunAjaran? tahunAjaranRef;

  Jadwal({
    required this.id,
    required this.matakuliahId,
    required this.dosenId,
    this.ruanganId,
    this.tahunAjaranId,
    required this.hari,
    required this.jamMulai,
    required this.jamSelesai,
    this.ruangan,
    this.semester,
    this.matakuliah,
    this.dosen,
    this.ruanganRef,
    this.tahunAjaranRef,
  });

  factory Jadwal.fromJson(Map<String, dynamic> json) {
    return Jadwal(
      id: json['id'],
      matakuliahId: json['matakuliah_id'],
      dosenId: json['dosen_id'],
      ruanganId: json['ruangan_id'],
      tahunAjaranId: json['tahun_ajaran_id'],
      hari: json['hari'] ?? '',
      jamMulai: json['jam_mulai'] ?? '',
      jamSelesai: json['jam_selesai'] ?? '',
      ruangan: json['ruangan'],
      semester: json['semester'],
      matakuliah: json['matakuliah'] != null ? Matakuliah.fromJson(json['matakuliah']) : null,
      dosen: json['dosen'] != null ? Dosen.fromJson(json['dosen']) : null,
      ruanganRef: (json['ruangan_ref'] ?? json['ruanganRef']) != null
          ? Ruangan.fromJson(json['ruangan_ref'] ?? json['ruanganRef'])
          : null,
      tahunAjaranRef: (json['tahun_ajaran'] ?? json['tahunAjaran']) is Map
          ? TahunAjaran.fromJson(json['tahun_ajaran'] ?? json['tahunAjaran'])
          : null,
    );
  }
}

class Ruangan {
  final int id;
  final String kodeRuangan;
  final String namaRuangan;
  final int? kapasitas;
  final String? gedung;
  final int? lantai;
  final String? jenis;

  Ruangan({
    required this.id,
    required this.kodeRuangan,
    required this.namaRuangan,
    this.kapasitas,
    this.gedung,
    this.lantai,
    this.jenis,
  });

  factory Ruangan.fromJson(Map<String, dynamic> json) {
    return Ruangan(
      id: json['id'],
      kodeRuangan: json['kode_ruangan'] ?? '',
      namaRuangan: json['nama_ruangan'] ?? '',
      kapasitas: json['kapasitas'] is int ? json['kapasitas'] : int.tryParse(json['kapasitas']?.toString() ?? ''),
      gedung: json['gedung'],
      lantai: int.tryParse(json['lantai']?.toString() ?? ''),
      jenis: json['jenis'],
    );
  }
}

class TahunAjaran {
  final int id;
  final String tahun;
  final String semester;
  final String? tglMulai;
  final String? tglSelesai;
  final bool? statusAktif;

  TahunAjaran({
    required this.id,
    required this.tahun,
    required this.semester,
    this.tglMulai,
    this.tglSelesai,
    this.statusAktif,
  });

  factory TahunAjaran.fromJson(Map<String, dynamic> json) {
    return TahunAjaran(
      id: json['id'],
      tahun: json['tahun'] ?? '',
      semester: json['semester'] ?? '',
      tglMulai: json['tgl_mulai'],
      tglSelesai: json['tgl_selesai'],
      statusAktif: json['status_aktif'],
    );
  }
}
