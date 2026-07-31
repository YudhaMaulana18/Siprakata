<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\ProgramStudi;
use App\Models\TahunAjaran;
use App\Models\Ruangan;
use App\Models\TransaksiKrs;
use App\Models\JadwalKuliah;
use App\Models\Presensi;
use App\Models\Nilai;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Program Studi ──────────────────────────────────────────────
        $prodiTI = ProgramStudi::firstOrCreate(
            ['kode_prodi' => 'TI'],
            ['nama_prodi' => 'Teknik Informatika', 'jenjang' => 'S1', 'fakultas' => 'Teknik']
        );
        $prodiSI = ProgramStudi::firstOrCreate(
            ['kode_prodi' => 'SI'],
            ['nama_prodi' => 'Sistem Informasi', 'jenjang' => 'S1', 'fakultas' => 'Teknik']
        );
        $prodiAK = ProgramStudi::firstOrCreate(
            ['kode_prodi' => 'AK'],
            ['nama_prodi' => 'Akuntansi', 'jenjang' => 'S1', 'fakultas' => 'Ekonomi']
        );

        // ── Tahun Ajaran ──────────────────────────────────────────────
        $ta = TahunAjaran::firstOrCreate(
            ['tahun' => '2025/2026', 'semester' => 'Ganjil'],
            ['tgl_mulai' => '2025-09-01', 'tgl_selesai' => '2026-01-31', 'status_aktif' => true]
        );
        TahunAjaran::firstOrCreate(
            ['tahun' => '2025/2026', 'semester' => 'Genap'],
            ['tgl_mulai' => '2026-02-01', 'tgl_selesai' => '2026-06-30', 'status_aktif' => false]
        );

        // ── Ruangan ───────────────────────────────────────────────────
        $ruang = [];
        foreach (['A101','A102','A201','A202','B101','B102'] as $i => $kode) {
            $ruang[] = Ruangan::firstOrCreate(
                ['kode_ruangan' => $kode],
                ['nama_ruangan' => 'Ruang ' . $kode, 'kapasitas' => 40, 'gedung' => 'Gedung ' . $kode[0], 'lantai' => (int)$kode[1], 'jenis' => 'Kelas']
            );
        }

        // ── Dosen (tambah 6 dosen jika belum ada) ─────────────────────
        $dosenData = [
            ['NIDN' => '0412068901', 'nama' => 'Dr. Budi Santoso, M.Kom',    'email' => 'budi@kampus.ac.id',    'jabatan' => 'Guru Besar',       'prodi_id' => $prodiTI->id],
            ['NIDN' => '0415068902', 'nama' => 'Ir. Ani Wijaya, M.T.',       'email' => 'ani@kampus.ac.id',     'jabatan' => 'Lektor Kepala',    'prodi_id' => $prodiTI->id],
            ['NIDN' => '0418068903', 'nama' => 'Dr. Dedi Kurniawan, S.Kom',  'email' => 'dedi@kampus.ac.id',    'jabatan' => 'Lektor',           'prodi_id' => $prodiSI->id],
            ['NIDN' => '0420068904', 'nama' => 'Siti Rahmawati, M.Si.',      'email' => 'siti@kampus.ac.id',    'jabatan' => 'Asisten Ahli',     'prodi_id' => $prodiSI->id],
            ['NIDN' => '0422068905', 'nama' => 'Rudi Hermawan, S.E., M.M.',  'email' => 'rudi@kampus.ac.id',    'jabatan' => 'Dosen',            'prodi_id' => $prodiAK->id],
            ['NIDN' => '0425068906', 'nama' => 'Dewi Lestari, S.E., M.Ak.',  'email' => 'dewi@kampus.ac.id',    'jabatan' => 'Dosen',            'prodi_id' => $prodiAK->id],
        ];
        $dosenList = [];
        foreach ($dosenData as $d) {
            $dosenList[] = Dosen::firstOrCreate(['NIDN' => $d['NIDN']], $d);
        }

        // ── Mata Kuliah (12 mata kuliah) ──────────────────────────────
        $mkData = [
            ['kode_mk' => 'MK001', 'nama_mk' => 'Pemrograman Web',       'sks' => 3, 'semester' => '3', 'prodi_id' => $prodiTI->id],
            ['kode_mk' => 'MK002', 'nama_mk' => 'Basis Data',            'sks' => 3, 'semester' => '3', 'prodi_id' => $prodiTI->id],
            ['kode_mk' => 'MK003', 'nama_mk' => 'Algoritma & Struktur Data', 'sks' => 3, 'semester' => '2', 'prodi_id' => $prodiTI->id],
            ['kode_mk' => 'MK004', 'nama_mk' => 'Jaringan Komputer',     'sks' => 3, 'semester' => '4', 'prodi_id' => $prodiTI->id],
            ['kode_mk' => 'MK005', 'nama_mk' => 'Sistem Operasi',        'sks' => 3, 'semester' => '4', 'prodi_id' => $prodiTI->id],
            ['kode_mk' => 'MK006', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'semester' => '5', 'prodi_id' => $prodiTI->id],
            ['kode_mk' => 'MK007', 'nama_mk' => 'Analisis & Perancangan SI', 'sks' => 3, 'semester' => '4', 'prodi_id' => $prodiSI->id],
            ['kode_mk' => 'MK008', 'nama_mk' => 'Basis Data Lanjut',     'sks' => 3, 'semester' => '5', 'prodi_id' => $prodiSI->id],
            ['kode_mk' => 'MK009', 'nama_mk' => 'Manajemen Proyek TI',  'sks' => 3, 'semester' => '6', 'prodi_id' => $prodiSI->id],
            ['kode_mk' => 'MK010', 'nama_mk' => 'Akuntansi Dasar',       'sks' => 3, 'semester' => '2', 'prodi_id' => $prodiAK->id],
            ['kode_mk' => 'MK011', 'nama_mk' => 'Akuntansi Menengah',    'sks' => 3, 'semester' => '3', 'prodi_id' => $prodiAK->id],
            ['kode_mk' => 'MK012', 'nama_mk' => 'Manajemen Keuangan',    'sks' => 3, 'semester' => '4', 'prodi_id' => $prodiAK->id],
        ];
        $mkList = [];
        foreach ($mkData as $mk) {
            $mkList[] = Matakuliah::firstOrCreate(['kode_mk' => $mk['kode_mk']], $mk);
        }

        // ── Mahasiswa (tetap, sudah ada atau buat baru) ───────────────
        $mhsData = [
            ['NIM' => '2023001', 'nama' => 'YUDHA SAPUTRA',          'alamat' => 'Jl. Merdeka No.1',  'email' => 'mahasiswa@kampus.ac.id',  'jenis_kelamin' => 'L', 'angkatan' => 2023, 'status' => 'aktif', 'prodi_id' => $prodiTI->id],
            ['NIM' => '2023002', 'nama' => 'RINA MARLINA',          'alamat' => 'Jl. Sudirman No.2', 'email' => 'rina@mail.com',   'jenis_kelamin' => 'P', 'angkatan' => 2023, 'status' => 'aktif', 'prodi_id' => $prodiTI->id],
            ['NIM' => '2023003', 'nama' => 'ANDI PRATAMA',          'alamat' => 'Jl. Gatot Subroto', 'email' => 'andi@mail.com',   'jenis_kelamin' => 'L', 'angkatan' => 2023, 'status' => 'aktif', 'prodi_id' => $prodiSI->id],
            ['NIM' => '2023004', 'nama' => 'SARI DEWI',             'alamat' => 'Jl. Ahmad Yani',    'email' => 'sari@mail.com',   'jenis_kelamin' => 'P', 'angkatan' => 2023, 'status' => 'aktif', 'prodi_id' => $prodiSI->id],
            ['NIM' => '2023005', 'nama' => 'DWI SAPUTRA',           'alamat' => 'Jl. Thamrin No.5',  'email' => 'dwi@mail.com',    'jenis_kelamin' => 'L', 'angkatan' => 2023, 'status' => 'aktif', 'prodi_id' => $prodiAK->id],
            ['NIM' => '2023006', 'nama' => 'MAYA PUTRI',            'alamat' => 'Jl. Diponegoro',    'email' => 'maya@mail.com',   'jenis_kelamin' => 'P', 'angkatan' => 2023, 'status' => 'aktif', 'prodi_id' => $prodiAK->id],
        ];
        $mhsList = [];
        foreach ($mhsData as $m) {
            $mhsList[] = Mahasiswa::updateOrCreate(
                ['NIM' => $m['NIM']],
                $m
            );
        }

        // Buat user mahasiswa
        $mhsRole = Role::where('name', 'mahasiswa')->first();
        foreach ($mhsList as $mhs) {
            $pass = $mhs->email === 'mahasiswa@kampus.ac.id' ? 'mhs123' : $mhs->NIM;
            User::updateOrCreate(
                ['email' => $mhs->email],
                ['name' => $mhs->nama, 'password' => Hash::make($pass), 'role_id' => $mhsRole->id]
            );
        }

        // Buat user dosen
        $dosenRole = Role::where('name', 'dosen')->first();
        foreach ($dosenList as $d) {
            User::firstOrCreate(
                ['email' => $d->email],
                ['name' => $d->nama, 'password' => Hash::make('dosen123'), 'role_id' => $dosenRole->id]
            );
        }

        // ── 25 Data Dummy KRS ─────────────────────────────────────────
        $krsEntries = [
            // Yudha (MK001, MK002, MK003) - 9 SKS
            ['mahasiswa_id' => $mhsList[0]->id, 'matakuliah_id' => $mkList[0]->id, 'dosen_id' => $dosenList[0]->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'aktif', 'status_validasi' => 'disetujui'],
            ['mahasiswa_id' => $mhsList[0]->id, 'matakuliah_id' => $mkList[1]->id, 'dosen_id' => $dosenList[1]->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'aktif', 'status_validasi' => 'disetujui'],
            ['mahasiswa_id' => $mhsList[0]->id, 'matakuliah_id' => $mkList[2]->id, 'dosen_id' => $dosenList[0]->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'aktif', 'status_validasi' => 'disetujui'],

            // Rina (MK001, MK004) - 6 SKS
            ['mahasiswa_id' => $mhsList[1]->id, 'matakuliah_id' => $mkList[0]->id, 'dosen_id' => $dosenList[0]->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'aktif', 'status_validasi' => 'disetujui'],
            ['mahasiswa_id' => $mhsList[1]->id, 'matakuliah_id' => $mkList[3]->id, 'dosen_id' => $dosenList[1]->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'aktif', 'status_validasi' => 'pending'],

            // Andi (MK007, MK008, MK009) - 9 SKS
            ['mahasiswa_id' => $mhsList[2]->id, 'matakuliah_id' => $mkList[6]->id, 'dosen_id' => $dosenList[2]->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'aktif', 'status_validasi' => 'disetujui'],
            ['mahasiswa_id' => $mhsList[2]->id, 'matakuliah_id' => $mkList[7]->id, 'dosen_id' => $dosenList[2]->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'aktif', 'status_validasi' => 'disetujui'],
            ['mahasiswa_id' => $mhsList[2]->id, 'matakuliah_id' => $mkList[8]->id, 'dosen_id' => $dosenList[3]->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'aktif', 'status_validasi' => 'disetujui'],

            // Sari (MK007, MK003) - 6 SKS
            ['mahasiswa_id' => $mhsList[3]->id, 'matakuliah_id' => $mkList[6]->id, 'dosen_id' => $dosenList[2]->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'aktif', 'status_validasi' => 'pending'],
            ['mahasiswa_id' => $mhsList[3]->id, 'matakuliah_id' => $mkList[2]->id, 'dosen_id' => $dosenList[0]->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'aktif', 'status_validasi' => 'disetujui'],

            // Dwi (MK010, MK011, MK012) - 9 SKS
            ['mahasiswa_id' => $mhsList[4]->id, 'matakuliah_id' => $mkList[9]->id, 'dosen_id' => $dosenList[4]->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'aktif', 'status_validasi' => 'disetujui'],
            ['mahasiswa_id' => $mhsList[4]->id, 'matakuliah_id' => $mkList[10]->id, 'dosen_id' => $dosenList[4]->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'aktif', 'status_validasi' => 'disetujui'],
            ['mahasiswa_id' => $mhsList[4]->id, 'matakuliah_id' => $mkList[11]->id, 'dosen_id' => $dosenList[5]->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'aktif', 'status_validasi' => 'ditolak', 'catatan_validasi' => 'SKS terlalu banyak untuk semester ini'],

            // Maya (MK010, MK011) - 6 SKS
            ['mahasiswa_id' => $mhsList[5]->id, 'matakuliah_id' => $mkList[9]->id, 'dosen_id' => $dosenList[4]->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'aktif', 'status_validasi' => 'disetujui'],
            ['mahasiswa_id' => $mhsList[5]->id, 'matakuliah_id' => $mkList[10]->id, 'dosen_id' => $dosenList[5]->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil', 'status' => 'aktif', 'status_validasi' => 'disetujui'],

            // Semester sebelumnya (Genap 2024/2025) - untuk data nilai/history
            ['mahasiswa_id' => $mhsList[0]->id, 'matakuliah_id' => $mkList[2]->id, 'dosen_id' => $dosenList[0]->id, 'tahun_ajaran' => '2024/2025', 'semester' => 'Genap', 'status' => 'selesai', 'status_validasi' => 'disetujui'],
            ['mahasiswa_id' => $mhsList[0]->id, 'matakuliah_id' => $mkList[3]->id, 'dosen_id' => $dosenList[1]->id, 'tahun_ajaran' => '2024/2025', 'semester' => 'Genap', 'status' => 'selesai', 'status_validasi' => 'disetujui'],

            ['mahasiswa_id' => $mhsList[1]->id, 'matakuliah_id' => $mkList[2]->id, 'dosen_id' => $dosenList[0]->id, 'tahun_ajaran' => '2024/2025', 'semester' => 'Genap', 'status' => 'selesai', 'status_validasi' => 'disetujui'],
            ['mahasiswa_id' => $mhsList[1]->id, 'matakuliah_id' => $mkList[4]->id, 'dosen_id' => $dosenList[1]->id, 'tahun_ajaran' => '2024/2025', 'semester' => 'Genap', 'status' => 'selesai', 'status_validasi' => 'disetujui'],

            ['mahasiswa_id' => $mhsList[2]->id, 'matakuliah_id' => $mkList[6]->id, 'dosen_id' => $dosenList[2]->id, 'tahun_ajaran' => '2024/2025', 'semester' => 'Genap', 'status' => 'selesai', 'status_validasi' => 'disetujui'],

            ['mahasiswa_id' => $mhsList[3]->id, 'matakuliah_id' => $mkList[7]->id, 'dosen_id' => $dosenList[3]->id, 'tahun_ajaran' => '2024/2025', 'semester' => 'Genap', 'status' => 'selesai', 'status_validasi' => 'disetujui'],

            ['mahasiswa_id' => $mhsList[4]->id, 'matakuliah_id' => $mkList[9]->id, 'dosen_id' => $dosenList[4]->id, 'tahun_ajaran' => '2024/2025', 'semester' => 'Genap', 'status' => 'selesai', 'status_validasi' => 'disetujui'],
            ['mahasiswa_id' => $mhsList[4]->id, 'matakuliah_id' => $mkList[10]->id, 'dosen_id' => $dosenList[5]->id, 'tahun_ajaran' => '2024/2025', 'semester' => 'Genap', 'status' => 'selesai', 'status_validasi' => 'disetujui'],

            ['mahasiswa_id' => $mhsList[5]->id, 'matakuliah_id' => $mkList[10]->id, 'dosen_id' => $dosenList[5]->id, 'tahun_ajaran' => '2024/2025', 'semester' => 'Genap', 'status' => 'selesai', 'status_validasi' => 'disetujui'],
        ];

        foreach ($krsEntries as $i => $krs) {
            TransaksiKrs::firstOrCreate(
                [
                    'mahasiswa_id'  => $krs['mahasiswa_id'],
                    'matakuliah_id' => $krs['matakuliah_id'],
                    'tahun_ajaran'  => $krs['tahun_ajaran'],
                    'semester'      => $krs['semester'],
                ],
                array_merge($krs, [
                    'catatan_validasi' => $krs['catatan_validasi'] ?? null,
                    'tgl_validasi'     => $krs['status_validasi'] !== 'pending' ? Carbon::now()->subDays(rand(1, 10)) : null,
                ])
            );
        }

        // ── Jadwal Kuliah ─────────────────────────────────────────────
        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat'];
        $jamList = ['08:00','09:30','11:00','13:00','14:30'];
        $jadwalList = [];

        foreach ($mkList as $i => $mk) {
            $jadwal = JadwalKuliah::firstOrCreate(
                ['matakuliah_id' => $mk->id, 'tahun_ajaran' => '2025/2026', 'semester' => 'Ganjil'],
                [
                    'dosen_id'    => $dosenList[$i % count($dosenList)]->id,
                    'ruangan_id'  => $ruang[$i % count($ruang)]->id,
                    'hari'        => $hariList[$i % count($hariList)],
                    'jam_mulai'   => $jamList[$i % count($jamList)],
                    'jam_selesai' => $jamList[$i % count($jamList)] === '14:30' ? '16:00' : Carbon::parse($jamList[$i % count($jamList)])->addHours(1)->addMinutes(30)->format('H:i'),
                    'ruangan'     => $ruang[$i % count($ruang)]->kode_ruangan,
                    'semester'    => 'Ganjil',
                ]
            );
            $jadwalList[] = $jadwal;
        }

        // ── Presensi & Nilai untuk KRS yang sudah selesai ─────────────
        $selesaiKrs = TransaksiKrs::where('semester', 'Genap')
            ->where('tahun_ajaran', '2024/2025')
            ->where('status', 'selesai')
            ->get();

        $gradeOptions = ['A', 'A', 'B+', 'B+', 'B', 'B', 'C+', 'C'];
        $pertemuan = 14;

        foreach ($selesaiKrs as $krs) {
            $mhs = $krs->mahasiswa;

            // Buat jadwal untuk mk ini
            $jadwal = JadwalKuliah::firstOrCreate(
                ['matakuliah_id' => $krs->matakuliah_id, 'tahun_ajaran' => '2024/2025', 'semester' => 'Genap'],
                [
                    'dosen_id'    => $krs->dosen_id,
                    'ruangan_id'  => $ruang[0]->id,
                    'hari'        => 'Rabu',
                    'jam_mulai'   => '08:00',
                    'jam_selesai' => '10:30',
                    'ruangan'     => $ruang[0]->kode_ruangan,
                    'semester'    => 'Genap',
                ]
            );

            // Presensi
            for ($p = 1; $p <= $pertemuan; $p++) {
                $statusOptions = ['hadir','hadir','hadir','hadir','hadir','hadir','hadir','izin','sakit','alpha'];
                Presensi::firstOrCreate(
                    ['jadwal_id' => $jadwal->id, 'mahasiswa_id' => $mhs->id, 'pertemuan_ke' => $p],
                    [
                        'tanggal'     => Carbon::parse('2025-02-03')->addWeeks(($p - 1) / 2)->addDays(($p % 2) * 3),
                        'status_hadir' => $statusOptions[array_rand($statusOptions)],
                        'keterangan'  => null,
                    ]
                );
            }

            // Nilai
            $grade = $gradeOptions[array_rand($gradeOptions)];
            $nilaiMap = [
                'A'  => ['tugas' => 90, 'uts' => 92, 'uas' => 95],
                'B+' => ['tugas' => 82, 'uts' => 78, 'uas' => 85],
                'B'  => ['tugas' => 75, 'uts' => 70, 'uas' => 72],
                'C+' => ['tugas' => 65, 'uts' => 60, 'uas' => 58],
                'C'  => ['tugas' => 55, 'uts' => 50, 'uas' => 48],
            ];
            $n = $nilaiMap[$grade] ?? ['tugas' => 70, 'uts' => 68, 'uas' => 72];

            Nilai::firstOrCreate(
                ['krs_id' => $krs->id],
                [
                    'nilai_tugas' => $n['tugas'],
                    'nilai_uts'   => $n['uts'],
                    'nilai_uas'   => $n['uas'],
                ]
            );
        }

        $this->command->info('Dummy data selesai!');
        $this->command->table(
            ['Entity', 'Count'],
            [
                ['Program Studi', ProgramStudi::count()],
                ['Dosen', Dosen::count()],
                ['Mata Kuliah', Matakuliah::count()],
                ['Mahasiswa', Mahasiswa::count()],
                ['KRS (Total)', TransaksiKrs::count()],
                ['KRS Pending', TransaksiKrs::where('status_validasi', 'pending')->count()],
                ['KRS Disetujui', TransaksiKrs::where('status_validasi', 'disetujui')->count()],
                ['KRS Ditolak', TransaksiKrs::where('status_validasi', 'ditolak')->count()],
                ['Jadwal Kuliah', JadwalKuliah::count()],
                ['Presensi', Presensi::count()],
                ['Nilai', Nilai::count()],
            ]
        );
    }
}
