class Mahasiswa {
  final int id;
  final String nim;
  final String nama;
  final String? alamat;
  final String? email;
  final String? noHp;
  final String? jenisKelamin;
  final int? angkatan;
  final String? status;
  final int? prodiId;
  final ProgramStudi? prodi;

  Mahasiswa({
    required this.id,
    required this.nim,
    required this.nama,
    this.alamat,
    this.email,
    this.noHp,
    this.jenisKelamin,
    this.angkatan,
    this.status,
    this.prodiId,
    this.prodi,
  });

  factory Mahasiswa.fromJson(Map<String, dynamic> json) {
    return Mahasiswa(
      id: json['id'],
      nim: json['NIM'] ?? json['nim'] ?? '',
      nama: json['nama'] ?? '',
      alamat: json['alamat'],
      email: json['email'],
      noHp: json['no_hp'],
      jenisKelamin: json['jenis_kelamin'],
      angkatan: int.tryParse(json['angkatan']?.toString() ?? ''),
      status: json['status'],
      prodiId: json['prodi_id'],
      prodi: json['prodi'] != null ? ProgramStudi.fromJson(json['prodi']) : null,
    );
  }
}

class ProgramStudi {
  final int id;
  final String namaProdi;
  final String? kodeProdi;
  final String? jenjang;
  final String? fakultas;

  ProgramStudi({
    required this.id,
    required this.namaProdi,
    this.kodeProdi,
    this.jenjang,
    this.fakultas,
  });

  factory ProgramStudi.fromJson(Map<String, dynamic> json) {
    return ProgramStudi(
      id: json['id'],
      namaProdi: json['nama_prodi'] ?? '',
      kodeProdi: json['kode_prodi'],
      jenjang: json['jenjang'],
      fakultas: json['fakultas'],
    );
  }
}
