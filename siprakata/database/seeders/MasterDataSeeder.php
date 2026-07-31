<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramStudi;
use App\Models\Ruangan;
use App\Models\TahunAjaran;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── 20 Program Studi ───────────────────────────────────────────
        $prodiData = [
            ['nama_prodi'=>'Teknik Elektro','kode_prodi'=>'TE','jenjang'=>'S1','fakultas'=>'Teknik'],
            ['nama_prodi'=>'Teknik Mesin','kode_prodi'=>'TM','jenjang'=>'S1','fakultas'=>'Teknik'],
            ['nama_prodi'=>'Teknik Sipil','kode_prodi'=>'TS','jenjang'=>'S1','fakultas'=>'Teknik'],
            ['nama_prodi'=>'Arsitektur','kode_prodi'=>'AR','jenjang'=>'S1','fakultas'=>'Teknik'],
            ['nama_prodi'=>'Teknik Kimia','kode_prodi'=>'TK','jenjang'=>'S1','fakultas'=>'Teknik'],
            ['nama_prodi'=>'Manajemen','kode_prodi'=>'MN','jenjang'=>'S1','fakultas'=>'Ekonomi'],
            ['nama_prodi'=>'Ekonomi Pembangunan','kode_prodi'=>'EP','jenjang'=>'S1','fakultas'=>'Ekonomi'],
            ['nama_prodi'=>'Akuntansi Syariah','kode_prodi'=>'AS','jenjang'=>'S1','fakultas'=>'Ekonomi'],
            ['nama_prodi'=>'Ilmu Hukum','kode_prodi'=>'IH','jenjang'=>'S1','fakultas'=>'Hukum'],
            ['nama_prodi'=>'Hukum Bisnis','kode_prodi'=>'HB','jenjang'=>'S1','fakultas'=>'Hukum'],
            ['nama_prodi'=>'Kedokteran Umum','kode_prodi'=>'KU','jenjang'=>'S1','fakultas'=>'Kedokteran'],
            ['nama_prodi'=>'Kedokteran Gigi','kode_prodi'=>'KG','jenjang'=>'S1','fakultas'=>'Kedokteran'],
            ['nama_prodi'=>'Farmasi','kode_prodi'=>'FP','jenjang'=>'S1','fakultas'=>'Kedokteran'],
            ['nama_prodi'=>'Ilmu Komunikasi','kode_prodi'=>'IK','jenjang'=>'S1','fakultas'=>'Ilmu Sosial'],
            ['nama_prodi'=>'Psikologi','kode_prodi'=>'PS','jenjang'=>'S1','fakultas'=>'Ilmu Sosial'],
            ['nama_prodi'=>'Pendidikan Guru SD','kode_prodi'=>'PG','jenjang'=>'S1','fakultas'=>'Keguruan'],
            ['nama_prodi'=>'Pendidikan Bahasa Inggris','kode_prodi'=>'PB','jenjang'=>'S1','fakultas'=>'Keguruan'],
            ['nama_prodi'=>'Teknologi Informasi','kode_prodi'=>'TI','jenjang'=>'D3','fakultas'=>'Teknik'],
            ['nama_prodi'=>'Sistem Informasi Manajemen','kode_prodi'=>'SM','jenjang'=>'D3','fakultas'=>'Ekonomi'],
            ['nama_prodi'=>'Perpustakaan dan Sains Informasi','kode_prodi'=>'LI','jenjang'=>'S2','fakultas'=>'Ilmu Sosial'],
        ];
        foreach ($prodiData as $p) {
            ProgramStudi::firstOrCreate(['kode_prodi'=>$p['kode_prodi']], $p);
        }

        // ── 20 Ruangan Tambahan ────────────────────────────────────────
        $ruangData = [
            ['kode_ruangan'=>'C101','nama_ruangan'=>'Ruang C101','kapasitas'=>60,'gedung'=>'Gedung C','lantai'=>1,'jenis'=>'Kelas'],
            ['kode_ruangan'=>'C102','nama_ruangan'=>'Ruang C102','kapasitas'=>60,'gedung'=>'Gedung C','lantai'=>1,'jenis'=>'Kelas'],
            ['kode_ruangan'=>'C201','nama_ruangan'=>'Ruang C201','kapasitas'=>45,'gedung'=>'Gedung C','lantai'=>2,'jenis'=>'Kelas'],
            ['kode_ruangan'=>'C202','nama_ruangan'=>'Ruang C202','kapasitas'=>45,'gedung'=>'Gedung C','lantai'=>2,'jenis'=>'Kelas'],
            ['kode_ruangan'=>'D101','nama_ruangan'=>'Lab Komputer 1','kapasitas'=>40,'gedung'=>'Gedung D','lantai'=>1,'jenis'=>'Laboratorium'],
            ['kode_ruangan'=>'D102','nama_ruangan'=>'Lab Komputer 2','kapasitas'=>40,'gedung'=>'Gedung D','lantai'=>1,'jenis'=>'Laboratorium'],
            ['kode_ruangan'=>'D201','nama_ruangan'=>'Lab Jaringan','kapasitas'=>35,'gedung'=>'Gedung D','lantai'=>2,'jenis'=>'Laboratorium'],
            ['kode_ruangan'=>'D202','nama_ruangan'=>'Lab Elektronika','kapasitas'=>35,'gedung'=>'Gedung D','lantai'=>2,'jenis'=>'Laboratorium'],
            ['kode_ruangan'=>'D301','nama_ruangan'=>'Lab Bahasa','kapasitas'=>30,'gedung'=>'Gedung D','lantai'=>3,'jenis'=>'Laboratorium'],
            ['kode_ruangan'=>'E101','nama_ruangan'=>'Ruang Sidang Utama','kapasitas'=>100,'gedung'=>'Gedung E','lantai'=>1,'jenis'=>'Aula'],
            ['kode_ruangan'=>'E102','nama_ruangan'=>'Auditorium','kapasitas'=>200,'gedung'=>'Gedung E','lantai'=>1,'jenis'=>'Aula'],
            ['kode_ruangan'=>'E201','nama_ruangan'=>'Ruang Rapat Senat','kapasitas'=>50,'gedung'=>'Gedung E','lantai'=>2,'jenis'=>'Aula'],
            ['kode_ruangan'=>'F101','nama_ruangan'=>'Ruang Dosen F101','kapasitas'=>20,'gedung'=>'Gedung F','lantai'=>1,'jenis'=>'Kelas'],
            ['kode_ruangan'=>'F102','nama_ruangan'=>'Ruang Dosen F102','kapasitas'=>20,'gedung'=>'Gedung F','lantai'=>1,'jenis'=>'Kelas'],
            ['kode_ruangan'=>'F201','nama_ruangan'=>'Ruang Koordinasi','kapasitas'=>15,'gedung'=>'Gedung F','lantai'=>2,'jenis'=>'Kelas'],
            ['kode_ruangan'=>'G101','nama_ruangan'=>'Lab Fisika','kapasitas'=>30,'gedung'=>'Gedung G','lantai'=>1,'jenis'=>'Laboratorium'],
            ['kode_ruangan'=>'G102','nama_ruangan'=>'Lab Kimia','kapasitas'=>30,'gedung'=>'Gedung G','lantai'=>1,'jenis'=>'Laboratorium'],
            ['kode_ruangan'=>'G201','nama_ruangan'=>'Lab Biologi','kapasitas'=>30,'gedung'=>'Gedung G','lantai'=>2,'jenis'=>'Laboratorium'],
            ['kode_ruangan'=>'H101','nama_ruangan'=>'Perpustakaan Pusat','kapasitas'=>150,'gedung'=>'Gedung H','lantai'=>1,'jenis'=>'Aula'],
            ['kode_ruangan'=>'H201','nama_ruangan'=>'Ruang Baca 2','kapasitas'=>80,'gedung'=>'Gedung H','lantai'=>2,'jenis'=>'Kelas'],
        ];
        foreach ($ruangData as $r) {
            Ruangan::firstOrCreate(['kode_ruangan'=>$r['kode_ruangan']], $r);
        }

        // ── 20 Tahun Ajaran ───────────────────────────────────────────
        $taData = [
            ['tahun'=>'2016/2017','semester'=>'Ganjil','tgl_mulai'=>'2016-08-01','tgl_selesai'=>'2017-01-31','status_aktif'=>false],
            ['tahun'=>'2016/2017','semester'=>'Genap','tgl_mulai'=>'2017-02-01','tgl_selesai'=>'2017-07-31','status_aktif'=>false],
            ['tahun'=>'2017/2018','semester'=>'Ganjil','tgl_mulai'=>'2017-08-01','tgl_selesai'=>'2018-01-31','status_aktif'=>false],
            ['tahun'=>'2017/2018','semester'=>'Genap','tgl_mulai'=>'2018-02-01','tgl_selesai'=>'2018-07-31','status_aktif'=>false],
            ['tahun'=>'2018/2019','semester'=>'Ganjil','tgl_mulai'=>'2018-08-01','tgl_selesai'=>'2019-01-31','status_aktif'=>false],
            ['tahun'=>'2018/2019','semester'=>'Genap','tgl_mulai'=>'2019-02-01','tgl_selesai'=>'2019-07-31','status_aktif'=>false],
            ['tahun'=>'2019/2020','semester'=>'Ganjil','tgl_mulai'=>'2019-08-01','tgl_selesai'=>'2020-01-31','status_aktif'=>false],
            ['tahun'=>'2019/2020','semester'=>'Genap','tgl_mulai'=>'2020-02-01','tgl_selesai'=>'2020-07-31','status_aktif'=>false],
            ['tahun'=>'2020/2021','semester'=>'Ganjil','tgl_mulai'=>'2020-08-01','tgl_selesai'=>'2021-01-31','status_aktif'=>false],
            ['tahun'=>'2020/2021','semester'=>'Genap','tgl_mulai'=>'2021-02-01','tgl_selesai'=>'2021-07-31','status_aktif'=>false],
            ['tahun'=>'2021/2022','semester'=>'Ganjil','tgl_mulai'=>'2021-08-01','tgl_selesai'=>'2022-01-31','status_aktif'=>false],
            ['tahun'=>'2021/2022','semester'=>'Genap','tgl_mulai'=>'2022-02-01','tgl_selesai'=>'2022-07-31','status_aktif'=>false],
            ['tahun'=>'2022/2023','semester'=>'Ganjil','tgl_mulai'=>'2022-08-01','tgl_selesai'=>'2023-01-31','status_aktif'=>false],
            ['tahun'=>'2022/2023','semester'=>'Genap','tgl_mulai'=>'2023-02-01','tgl_selesai'=>'2023-07-31','status_aktif'=>false],
            ['tahun'=>'2023/2024','semester'=>'Ganjil','tgl_mulai'=>'2023-08-01','tgl_selesai'=>'2024-01-31','status_aktif'=>false],
            ['tahun'=>'2023/2024','semester'=>'Genap','tgl_mulai'=>'2024-02-01','tgl_selesai'=>'2024-07-31','status_aktif'=>false],
            ['tahun'=>'2024/2025','semester'=>'Ganjil','tgl_mulai'=>'2024-08-01','tgl_selesai'=>'2025-01-31','status_aktif'=>false],
            ['tahun'=>'2024/2025','semester'=>'Genap','tgl_mulai'=>'2025-02-01','tgl_selesai'=>'2025-07-31','status_aktif'=>false],
            ['tahun'=>'2025/2026','semester'=>'Genap','tgl_mulai'=>'2026-02-01','tgl_selesai'=>'2026-07-31','status_aktif'=>false],
            ['tahun'=>'2026/2027','semester'=>'Ganjil','tgl_mulai'=>'2026-08-01','tgl_selesai'=>'2027-01-31','status_aktif'=>false],
        ];
        foreach ($taData as $t) {
            TahunAjaran::firstOrCreate(['tahun'=>$t['tahun'],'semester'=>$t['semester']], $t);
        }

        $this->command->info('Data master tambahan selesai!');
        $this->command->table(
            ['Entity','Count'],
            [
                ['Program Studi', ProgramStudi::count()],
                ['Ruangan', Ruangan::count()],
                ['Tahun Ajaran', TahunAjaran::count()],
            ]
        );
    }
}
