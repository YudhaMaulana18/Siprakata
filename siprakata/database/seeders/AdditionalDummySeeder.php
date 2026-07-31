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
use App\Models\MateriKuliah;
use App\Models\Pengumuman;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdditionalDummySeeder extends Seeder
{
    public function run(): void
    {
        $prodiTI = ProgramStudi::where('kode_prodi', 'TI')->first();
        $prodiSI = ProgramStudi::where('kode_prodi', 'SI')->first();
        $prodiAK = ProgramStudi::where('kode_prodi', 'AK')->first();

        $ta = TahunAjaran::where('tahun', '2025/2026')->where('semester', 'Ganjil')->first();

        $ruang = Ruangan::all();
        $dosenList = Dosen::all();
        $mkList = Matakuliah::all();
        $mhsExisting = Mahasiswa::pluck('NIM')->toArray();

        // ── 27 Mahasiswa (NIM 21552/24552) ────────────────────────────
        $mhsBatch1 = [
            ['NIM'=>'2155201110011','nama'=>'DICKI PRASTIA PAUZI','alamat'=>'BatuLicin','email'=>'DICKI@gmail.com','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2021,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110002','nama'=>'AKMAL MAULANA YUSUF','alamat'=>'KotaBaru','email'=>'AKMAL@gmail.com','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110003','nama'=>'HALIS ANNISA','alamat'=>'handil','email'=>'HALIS@gmail.com','no_hp'=>'087841879271','jenis_kelamin'=>'P','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110004','nama'=>'HARY NUR AFANDI','alamat'=>'KAPUAS','email'=>'HARY@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110005','nama'=>'I DEWA GEDE ARYA PRAMEISA','alamat'=>'KAPUAS','email'=>'DEWA@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110006','nama'=>'LUTHFI AHMAD FAHREZI','alamat'=>'handil','email'=>'UPI@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110007','nama'=>'LUTHFIANA SAFITRI','alamat'=>'TANJUNG','email'=>'FIA@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'P','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110009','nama'=>'MOCHAMMAD SYAHID FARIZ ABQARI','alamat'=>'BatuLicin','email'=>'ABI@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110010','nama'=>'MUHAMMAD FAJAR AULIA','alamat'=>'MARABAHAN','email'=>'FAJAR@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110012','nama'=>'MUHAMMAD RYAN HIDAYAT','alamat'=>'KotaBaru','email'=>'RYAN@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110013','nama'=>'MUHAMMAD SYAFIQ HUSIN','alamat'=>'kelua','email'=>'SAPIK@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110014','nama'=>'MUHAMMAD SAHID FADHILLAH','alamat'=>'SUKAMARA','email'=>'PADIL@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110015','nama'=>'MUHAMMAD SYARIF','alamat'=>'bjm','email'=>'SYARIF@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110016','nama'=>'NANDA SYALWA NAZELLA','alamat'=>'Barabai','email'=>'SYALWA@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'P','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110017','nama'=>'NAZWA AULIA PUTRI','alamat'=>'BatuLicin','email'=>'NAZWA@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'P','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110018','nama'=>'NORMAYANTI','alamat'=>'KAPUAS','email'=>'MAYA@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'P','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110019','nama'=>'NUR AISYAH','alamat'=>'SUKAMARA','email'=>'AISYAH@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'P','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110020','nama'=>'PENDRI MIKOLA','alamat'=>'KAPUAS','email'=>'PENDRI@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110021','nama'=>'RAIHAN','alamat'=>'KALTENG','email'=>'HAN@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110022','nama'=>'RIANTI','alamat'=>'PATAS','email'=>'RIANTI@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'P','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110023','nama'=>'RUDI GUNAWAN','alamat'=>'ANJIR PASAR','email'=>'RUDI@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110024','nama'=>'SITI HIDAYATUZ ZUHRO','alamat'=>'TANAH BUMBU','email'=>'ZUHRO@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'P','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110025','nama'=>'VIONA WINOLA SUPRAPTO','alamat'=>'BJM','email'=>'VIONA@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'P','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110026','nama'=>'YUDHA MAULANA DARHAM','alamat'=>'Barabai','email'=>'yudhadavino06@gmail.com','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110027','nama'=>'ZAINABUL ASKYAH','alamat'=>'TANJUNG','email'=>'KIYA@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'P','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110028','nama'=>'MUHAMMAD AGUS YULIANSYAH','alamat'=>'SAMPIT','email'=>'GUS@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2455201110030','nama'=>'GILANG HERNAWAN SALEM','alamat'=>'BatuLicin','email'=>'GIL@GMAIL.COM','no_hp'=>'087841879271','jenis_kelamin'=>'L','angkatan'=>2024,'status'=>'aktif','prodi_id'=>$prodiTI->id],
        ];
        foreach ($mhsBatch1 as $m) {
            $exists = Mahasiswa::where('NIM', $m['NIM'])->first();
            if (!$exists) {
                Mahasiswa::create($m);
                User::updateOrCreate(
                    ['email' => $m['email']],
                    ['name' => $m['nama'], 'password' => Hash::make($m['NIM']), 'role_id' => Role::where('name','mahasiswa')->first()->id]
                );
            }
        }

        // ── 20 Mahasiswa Tambahan ──────────────────────────────────────
        $mhsBaru = [
            ['NIM'=>'2023007','nama'=>'Fajar Nugroho','alamat'=>'Jl. Imam Bonjol','email'=>'fajar@mail.com','jenis_kelamin'=>'L','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2023008','nama'=>'Lestari Putri','alamat'=>'Jl. Pemuda No.3','email'=>'lestari@mail.com','jenis_kelamin'=>'P','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2023009','nama'=>'Hendra Wijaya','alamat'=>'Jl. Kartini','email'=>'hendra@mail.com','jenis_kelamin'=>'L','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiSI->id],
            ['NIM'=>'2023010','nama'=>'Putri Ayu','alamat'=>'Jl. Fatmawati','email'=>'putri@mail.com','jenis_kelamin'=>'P','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiSI->id],
            ['NIM'=>'2023011','nama'=>'Budi Santoso','alamat'=>'Jl. Hayam Wuruk','email'=>'budis@mail.com','jenis_kelamin'=>'L','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiAK->id],
            ['NIM'=>'2023012','nama'=>'Nina Sari','alamat'=>'Jl. Gajah Mada','email'=>'nina@mail.com','jenis_kelamin'=>'P','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiAK->id],
            ['NIM'=>'2023013','nama'=>'Rizki Pratama','alamat'=>'Jl. Sultan Agung','email'=>'rizki@mail.com','jenis_kelamin'=>'L','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2023014','nama'=>'Diana Putri','alamat'=>'Jl. Dr. Wahidin','email'=>'diana@mail.com','jenis_kelamin'=>'P','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2023015','nama'=>'Angga Prasetyo','alamat'=>'Jl. Mangkubumi','email'=>'angga@mail.com','jenis_kelamin'=>'L','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiSI->id],
            ['NIM'=>'2023016','nama'=>'Sinta Dewi','alamat'=>'Jl. Solo','email'=>'sinta@mail.com','jenis_kelamin'=>'P','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiSI->id],
            ['NIM'=>'2023017','nama'=>'Yoga Pratama','alamat'=>'Jl. Malioboro','email'=>'yoga@mail.com','jenis_kelamin'=>'L','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiAK->id],
            ['NIM'=>'2023018','nama'=>'Ratna Sari','alamat'=>'Jl. Braga','email'=>'ratna@mail.com','jenis_kelamin'=>'P','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiAK->id],
            ['NIM'=>'2023019','nama'=>'Dimas Aditya','alamat'=>'Jl. Asia Afrika','email'=>'dimas@mail.com','jenis_kelamin'=>'L','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2023020','nama'=>'Maya Angelina','alamat'=>'Jl. Pintu Besar','email'=>'maya2@mail.com','jenis_kelamin'=>'P','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2023021','nama'=>'Arief Rahman','alamat'=>'Jl. Kembang Kencana','email'=>'arief@mail.com','jenis_kelamin'=>'L','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiSI->id],
            ['NIM'=>'2023022','nama'=>'Winda Rahmawati','alamat'=>'Jl. Merdeka Raya','email'=>'winda@mail.com','jenis_kelamin'=>'P','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiSI->id],
            ['NIM'=>'2023023','nama'=>'Gilang Ramadhan','alamat'=>'Jl. Otto Iskandardinata','email'=>'gilang@mail.com','jenis_kelamin'=>'L','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiAK->id],
            ['NIM'=>'2023024','nama'=>'Citra Lestari','alamat'=>'Jl. Ahmad Yani No.10','email'=>'citra@mail.com','jenis_kelamin'=>'P','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiAK->id],
            ['NIM'=>'2023025','nama'=>'Reza Fahlevi','alamat'=>'Jl. Ir. H. Juanda','email'=>'reza@mail.com','jenis_kelamin'=>'L','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiTI->id],
            ['NIM'=>'2023026','nama'=>'Anisa Rahma','alamat'=>'Jl. Cut Nyak Dien','email'=>'anisa@mail.com','jenis_kelamin'=>'P','angkatan'=>2023,'status'=>'aktif','prodi_id'=>$prodiTI->id],
        ];

        $mhsList = [];
        foreach ($mhsBaru as $m) {
            if (!in_array($m['NIM'], $mhsExisting)) {
                $mhsList[] = Mahasiswa::create($m);
                User::updateOrCreate(
                    ['email' => $m['email']],
                    ['name' => $m['nama'], 'password' => Hash::make($m['NIM']), 'role_id' => Role::where('name','mahasiswa')->first()->id]
                );
            }
        }

        // ── 20 Dosen Tambahan ──────────────────────────────────────────
        $dosenBaru = [
            ['NIDN'=>'0426068907','nama'=>'Prof. Dr. Hadi Purwanto, M.Kom','email'=>'hadi@kampus.ac.id','jabatan'=>'Guru Besar','prodi_id'=>$prodiTI->id],
            ['NIDN'=>'0427068908','nama'=>'Dr. Ratna Sari, M.T.','email'=>'ratnas@kampus.ac.id','jabatan'=>'Lektor Kepala','prodi_id'=>$prodiTI->id],
            ['NIDN'=>'0428068909','nama'=>'Dr. Agus Setiawan, S.Kom','email'=>'agus@kampus.ac.id','jabatan'=>'Lektor','prodi_id'=>$prodiTI->id],
            ['NIDN'=>'0429068910','nama'=>'Ir. Mega Wati, M.Kom','email'=>'mega@kampus.ac.id','jabatan'=>'Lektor','prodi_id'=>$prodiSI->id],
            ['NIDN'=>'0430068911','nama'=>'Dr. Hendra Kusuma, S.E., M.M.','email'=>'hendrak@kampus.ac.id','jabatan'=>'Lektor Kepala','prodi_id'=>$prodiAK->id],
            ['NIDN'=>'0431068912','nama'=>'Siti Nurhaliza, S.Pd., M.Pd.','email'=>'sitin@kampus.ac.id','jabatan'=>'Asisten Ahli','prodi_id'=>$prodiTI->id],
            ['NIDN'=>'0432068913','nama'=>'Dr. Bambang Pamungkas, M.Si.','email'=>'bambang@kampus.ac.id','jabatan'=>'Lektor','prodi_id'=>$prodiSI->id],
            ['NIDN'=>'0433068914','nama'=>'Rina Susanti, S.T., M.T.','email'=>'rinas@kampus.ac.id','jabatan'=>'Dosen','prodi_id'=>$prodiTI->id],
            ['NIDN'=>'0434068915','nama'=>'Dr. Darmawan Salahudin, S.Kom','email'=>'darmawan@kampus.ac.id','jabatan'=>'Lektor Kepala','prodi_id'=>$prodiTI->id],
            ['NIDN'=>'0435068916','nama'=>'Eka Putri Ramadhani, S.E., M.Ak.','email'=>'eka@kampus.ac.id','jabatan'=>'Dosen','prodi_id'=>$prodiAK->id],
            ['NIDN'=>'0436068917','nama'=>'Dr. Ferdi Saputra, M.T.','email'=>'ferdi@kampus.ac.id','jabatan'=>'Lektor','prodi_id'=>$prodiSI->id],
            ['NIDN'=>'0437068918','nama'=>'Gita Puspita Sari, S.Kom., M.Kom','email'=>'gita@kampus.ac.id','jabatan'=>'Asisten Ahli','prodi_id'=>$prodiTI->id],
            ['NIDN'=>'0438068919','nama'=>'Dr. Irfan Nugroho, S.E., M.M.','email'=>'irfan@kampus.ac.id','jabatan'=>'Lektor','prodi_id'=>$prodiAK->id],
            ['NIDN'=>'0439068920','nama'=>'Joko Widodo, S.T., M.T.','email'=>'jokow@kampus.ac.id','jabatan'=>'Dosen','prodi_id'=>$prodiTI->id],
            ['NIDN'=>'0440068921','nama'=>'Dr. Kartika Dewi, M.Si.','email'=>'kartika@kampus.ac.id','jabatan'=>'Lektor Kepala','prodi_id'=>$prodiSI->id],
            ['NIDN'=>'0441068922','nama'=>'Lukman Hakim, S.Pd., M.Pd.','email'=>'lukman@kampus.ac.id','jabatan'=>'Dosen','prodi_id'=>$prodiTI->id],
            ['NIDN'=>'0442068923','nama'=>'Dr. Maya Indah Sari, M.T.','email'=>'mayai@kampus.ac.id','jabatan'=>'Lektor','prodi_id'=>$prodiTI->id],
            ['NIDN'=>'0443068924','nama'=>'Nanda Pratama, S.Kom., M.Kom','email'=>'nanda@kampus.ac.id','jabatan'=>'Asisten Ahli','prodi_id'=>$prodiSI->id],
            ['NIDN'=>'0444068925','nama'=>'Dr. Oki Firmansyah, S.E., M.M.','email'=>'oki@kampus.ac.id','jabatan'=>'Lektor','prodi_id'=>$prodiAK->id],
            ['NIDN'=>'0445068926','nama'=>'Putra Wijaya, S.T., M.T.','email'=>'putra@kampus.ac.id','jabatan'=>'Dosen','prodi_id'=>$prodiTI->id],
        ];

        $dosenTambahan = [];
        foreach ($dosenBaru as $d) {
            $existing = Dosen::where('NIDN', $d['NIDN'])->first();
            if (!$existing) {
                $dosenTambahan[] = Dosen::create($d);
                User::firstOrCreate(
                    ['email' => $d['email']],
                    ['name' => $d['nama'], 'password' => Hash::make('dosen123'), 'role_id' => Role::where('name','dosen')->first()->id]
                );
            }
        }

        // ── 20 Mata Kuliah Tambahan ────────────────────────────────────
        $mkBaru = [
            ['kode_mk'=>'MK013','nama_mk'=>'Pemrograman Mobile','sks'=>3,'semester'=>'5','prodi_id'=>$prodiTI->id],
            ['kode_mk'=>'MK014','nama_mk'=>'Kecerdasan Buatan','sks'=>3,'semester'=>'5','prodi_id'=>$prodiTI->id],
            ['kode_mk'=>'MK015','nama_mk'=>'Data Mining','sks'=>3,'semester'=>'6','prodi_id'=>$prodiTI->id],
            ['kode_mk'=>'MK016','nama_mk'=>'Keamanan Jaringan','sks'=>3,'semester'=>'5','prodi_id'=>$prodiTI->id],
            ['kode_mk'=>'MK017','nama_mk'=>'Grafika Komputer','sks'=>3,'semester'=>'5','prodi_id'=>$prodiTI->id],
            ['kode_mk'=>'MK018','nama_mk'=>'Sistem Terdistribusi','sks'=>3,'semester'=>'6','prodi_id'=>$prodiTI->id],
            ['kode_mk'=>'MK019','nama_mk'=>'Cloud Computing','sks'=>3,'semester'=>'6','prodi_id'=>$prodiTI->id],
            ['kode_mk'=>'MK020','nama_mk'=>'IoT dan Embedded System','sks'=>3,'semester'=>'6','prodi_id'=>$prodiTI->id],
            ['kode_mk'=>'MK021','nama_mk'=>'Big Data Analytics','sks'=>3,'semester'=>'6','prodi_id'=>$prodiSI->id],
            ['kode_mk'=>'MK022','nama_mk'=>'E-Business','sks'=>3,'semester'=>'5','prodi_id'=>$prodiSI->id],
            ['kode_mk'=>'MK023','nama_mk'=>'Manajemen Risiko TI','sks'=>3,'semester'=>'6','prodi_id'=>$prodiSI->id],
            ['kode_mk'=>'MK024','nama_mk'=>'Sistem Pendukung Keputusan','sks'=>3,'semester'=>'5','prodi_id'=>$prodiSI->id],
            ['kode_mk'=>'MK025','nama_mk'=>'Pengolahan Citra Digital','sks'=>3,'semester'=>'6','prodi_id'=>$prodiSI->id],
            ['kode_mk'=>'MK026','nama_mk'=>'Blockchain & Cryptocurrency','sks'=>3,'semester'=>'7','prodi_id'=>$prodiSI->id],
            ['kode_mk'=>'MK027','nama_mk'=>'Akuntansi Manajemen','sks'=>3,'semester'=>'4','prodi_id'=>$prodiAK->id],
            ['kode_mk'=>'MK028','nama_mk'=>'Pajak dan Perpajakan','sks'=>3,'semester'=>'4','prodi_id'=>$prodiAK->id],
            ['kode_mk'=>'MK029','nama_mk'=>'Auditing','sks'=>3,'semester'=>'5','prodi_id'=>$prodiAK->id],
            ['kode_mk'=>'MK030','nama_mk'=>'Akuntansi Syariah','sks'=>3,'semester'=>'5','prodi_id'=>$prodiAK->id],
            ['kode_mk'=>'MK031','nama_mk'=>'Laporan Keuangan Lanjut','sks'=>3,'semester'=>'6','prodi_id'=>$prodiAK->id],
            ['kode_mk'=>'MK032','nama_mk'=>'Etika Bisnis dan Profesi','sks'=>2,'semester'=>'3','prodi_id'=>$prodiAK->id],
        ];

        $mkTambahan = [];
        foreach ($mkBaru as $mk) {
            $existing = Matakuliah::where('kode_mk', $mk['kode_mk'])->first();
            if (!$existing) {
                $mkTambahan[] = Matakuliah::create($mk);
            }
        }

        // ── 20 KRS Tambahan ────────────────────────────────────────────
        $allMhs = Mahasiswa::where('status', 'aktif')->get();
        $allDosen = Dosen::all();
        $allMK = Matakuliah::all();

        $krsData = [];
        $usedPairs = [];
        foreach ($allMhs as $m) {
            if (count($krsData) >= 20) break;
            $mk = $allMK->random();
            $d = $allDosen->random();
            $pair = $m->id.'-'.$mk->id;
            if (in_array($pair, $usedPairs)) continue;
            $usedPairs[] = $pair;
            $krsData[] = [
                'mahasiswa_id' => $m->id,
                'matakuliah_id' => $mk->id,
                'dosen_id' => $d->id,
                'tahun_ajaran' => '2025/2026',
                'semester' => 'Ganjil',
                'status' => 'aktif',
                'status_validasi' => ['pending','disetujui','disetujui','disetujui'][array_rand([0,1,2,3])],
            ];
        }

        foreach ($krsData as $k) {
            TransaksiKrs::firstOrCreate(
                ['mahasiswa_id'=>$k['mahasiswa_id'],'matakuliah_id'=>$k['matakuliah_id'],'tahun_ajaran'=>$k['tahun_ajaran'],'semester'=>$k['semester']],
                array_merge($k, ['tgl_validasi' => $k['status_validasi'] !== 'pending' ? Carbon::now()->subDays(rand(1,5)) : null])
            );
        }

        // ── 20 Jadwal Kuliah Tambahan ──────────────────────────────────
        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat'];
        $jamMulai = ['07:00','08:00','09:00','10:00','11:00','13:00','14:00','15:00'];
        $jadwalCount = JadwalKuliah::count();

        foreach ($mkTambahan as $i => $mk) {
            if ($i >= 20) break;
            $jam = $jamMulai[array_rand($jamMulai)];
            JadwalKuliah::firstOrCreate(
                ['matakuliah_id'=>$mk->id,'tahun_ajaran'=>'2025/2026','semester'=>'Ganjil'],
                [
                    'dosen_id' => $allDosen->random()->id,
                    'ruangan_id' => $ruang->random()->id,
                    'hari' => $hariList[array_rand($hariList)],
                    'jam_mulai' => $jam,
                    'jam_selesai' => date('H:i', strtotime($jam) + 3600*2),
                    'ruangan' => $ruang->random()->kode_ruangan,
                    'semester' => 'Ganjil',
                ]
            );
        }

        // ── 20 Presensi Tambahan ───────────────────────────────────────
        $jadwalList = JadwalKuliah::all();
        $presensiCount = 0;
        foreach ($allMhs as $m) {
            if ($presensiCount >= 20) break;
            $jadwal = $jadwalList->random();
            $pertemuan = rand(1, 10);
            $exists = Presensi::where('jadwal_id',$jadwal->id)->where('mahasiswa_id',$m->id)->where('pertemuan_ke',$pertemuan)->exists();
            if (!$exists) {
                Presensi::create([
                    'jadwal_id' => $jadwal->id,
                    'mahasiswa_id' => $m->id,
                    'tanggal' => Carbon::now()->subDays(rand(1,60)),
                    'pertemuan_ke' => $pertemuan,
                    'status_hadir' => ['hadir','hadir','hadir','hadir','izin','sakit','alpha'][array_rand([0,1,2,3,4,5,6])],
                    'keterangan' => null,
                ]);
                $presensiCount++;
            }
        }

        // ── 20 Materi Kuliah Tambahan ──────────────────────────────────
        $judulMateri = [
            'Pengenalan Framework','Setup Lingkungan Development','Konsep OOP',
            'Design Pattern','REST API','Authentication & Authorization',
            'Database Migration','Testing & Debugging','Performance Optimization',
            'Deployment & CI/CD','Data Structures','Algorithm Analysis',
            'Machine Learning Basics','Neural Networks','Web Security',
            'Microservices','Docker & Container','Kubernetes Basics',
            'DevOps Pipeline','Clean Architecture',
        ];
        $materiCount = 0;
        foreach ($jadwalList as $j) {
            if ($materiCount >= 20) break;
            for ($p = 1; $p <= 3; $p++) {
                if ($materiCount >= 20) break;
                $exists = MateriKuliah::where('jadwal_id',$j->id)->where('pertemuan_ke',$p)->exists();
                if (!$exists) {
                    MateriKuliah::create([
                        'jadwal_id' => $j->id,
                        'pertemuan_ke' => $p,
                        'judul' => $judulMateri[$materiCount % count($judulMateri)],
                        'deskripsi' => 'Materi untuk pertemuan ke-'.$p,
                        'link_materi' => 'https://drive.google.com/file/d/example'.$materiCount,
                    ]);
                    $materiCount++;
                }
            }
        }

        // ── 20 Pengumuman Tambahan ─────────────────────────────────────
        $judulPengumuman = [
            'Jadwal UTS Semester Ganjil 2025','Pengumpulan Tugas Akhir',
            'Libur Nasional Hari Raya','Workshop Pemrograman Web',
            'Seminar Teknologi Informasi','Pendaftaran Magang 2026',
            'Jadwal Konsultasi Dosen','Perubahan Jadwal Kuliah',
            'Pengumuman Kelulusan Proposal','Batas Akhir Registrasi',
            'Kuliah Tamu Pakar AI','Lomba Hackathon 2026',
            'Sosialisasi Beasiswa','Pemilihan Ketua Himpunan',
            'Pengadaan Laboratorium Baru','Kerja Sama dengan Industri',
            'Jadwal Praktikum Semester Depan','Evaluasi Dosen Pengampu',
            'Renovasi Gedung Teknik','Pengumuman Cuti Bersama',
        ];
        $prioritasList = ['rendah','sedang','tinggi'];
        $pengumumanCount = 0;
        foreach ($allDosen as $d) {
            if ($pengumumanCount >= 20) break;
            Pengumuman::create([
                'dosen_id' => $d->id,
                'jadwal_id' => $jadwalList->random()->id,
                'judul' => $judulPengumuman[$pengumumanCount % count($judulPengumuman)],
                'isi' => 'Deskripsi dari pengumuman: '.$judulPengumuman[$pengumumanCount % count($judulPengumuman)],
                'prioritas' => $prioritasList[array_rand($prioritasList)],
                'tgl_posting' => Carbon::now()->subDays(rand(1,30)),
                'tgl_kadaluarsa' => Carbon::now()->addDays(rand(7,60)),
            ]);
            $pengumumanCount++;
        }

        // Update password user mahasiswa pakai NIM (kecuali akun shared)
        $mhsAll = Mahasiswa::all();
        foreach ($mhsAll as $mhs) {
            if ($mhs->email === 'mahasiswa@kampus.ac.id') continue;
            $user = User::where('email', $mhs->email)->first();
            if ($user) {
                $user->password = Hash::make($mhs->NIM);
                $user->save();
            }
        }

        $this->command->info('Penambahan 20 data dummy selesai!');
        $this->command->table(
            ['Entity', 'Count'],
            [
                ['Mahasiswa', Mahasiswa::count()],
                ['Dosen', Dosen::count()],
                ['Mata Kuliah', Matakuliah::count()],
                ['KRS', TransaksiKrs::count()],
                ['Jadwal Kuliah', JadwalKuliah::count()],
                ['Presensi', Presensi::count()],
                ['Materi Kuliah', MateriKuliah::count()],
                ['Pengumuman', Pengumuman::count()],
            ]
        );
    }
}
