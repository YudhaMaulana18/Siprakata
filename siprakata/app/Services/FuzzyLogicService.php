<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\TransaksiKrs;
use App\Models\Nilai;
use App\Models\Presensi;
use Illuminate\Support\Facades\DB;

class FuzzyLogicService
{
    // ════════════════════════════════════════════════════════════════════
    // FUNGSI KEANGGOTAAN: Kehadiran (0% – 100%)
    // Himpunan: Rendah, Sedang, Tinggi
    // ════════════════════════════════════════════════════════════════════

    // Rendah: Linear turun [0,1] pada 0–50, turun 1→0 pada 50–60
    public static function kehadiranRendah(float $x): float
    {
        if ($x <= 50) return 1.0;
        if ($x >= 60) return 0.0;
        return (60 - $x) / 10;
    }

    // Sedang: Trapesium [50,60,75,85]
    public static function kehadiranSedang(float $x): float
    {
        if ($x <= 50 || $x >= 85) return 0.0;
        if ($x <= 60) return ($x - 50) / 10;
        if ($x <= 75) return 1.0;
        return (85 - $x) / 10;
    }

    // Tinggi: Linear naik [0] pada 0–75, naik 0→1 pada 75–85
    public static function kehadiranTinggi(float $x): float
    {
        if ($x <= 75) return 0.0;
        if ($x >= 85) return 1.0;
        return ($x - 75) / 10;
    }

    // ════════════════════════════════════════════════════════════════════
    // FUNGSI KEANGGOTAAN: Nilai Tugas (0 – 100)
    // Himpunan: Rendah, Sedang, Tinggi
    // ════════════════════════════════════════════════════════════════════

    // Rendah: Linear turun [0,1] pada 0–50, turun 1→0 pada 50–60
    public static function tugasRendah(float $x): float
    {
        if ($x <= 50) return 1.0;
        if ($x >= 60) return 0.0;
        return (60 - $x) / 10;
    }

    // Sedang: Trapesium [50,60,75,85]
    public static function tugasSedang(float $x): float
    {
        if ($x <= 50 || $x >= 85) return 0.0;
        if ($x <= 60) return ($x - 50) / 10;
        if ($x <= 75) return 1.0;
        return (85 - $x) / 10;
    }

    // Tinggi: Linear naik [0] pada 0–75, naik 0→1 pada 75–85
    public static function tugasTinggi(float $x): float
    {
        if ($x <= 75) return 0.0;
        if ($x >= 85) return 1.0;
        return ($x - 75) / 10;
    }

    // ════════════════════════════════════════════════════════════════════
    // FUNGSI KEANGGOTAAN: Keaktifan Diskusi (0 – 100)
    // Himpunan: Rendah, Sedang, Tinggi
    // ════════════════════════════════════════════════════════════════════

    // Rendah: Linear turun [0,1] pada 0–40, turun 1→0 pada 40–50
    public static function diskusiRendah(float $x): float
    {
        if ($x <= 40) return 1.0;
        if ($x >= 50) return 0.0;
        return (50 - $x) / 10;
    }

    // Sedang: Trapesium [40,50,70,80]
    public static function diskusiSedang(float $x): float
    {
        if ($x <= 40 || $x >= 80) return 0.0;
        if ($x <= 50) return ($x - 40) / 10;
        if ($x <= 70) return 1.0;
        return (80 - $x) / 10;
    }

    // Tinggi: Linear naik [0] pada 0–70, naik 0→1 pada 70–80
    public static function diskusiTinggi(float $x): float
    {
        if ($x <= 70) return 0.0;
        if ($x >= 80) return 1.0;
        return ($x - 70) / 10;
    }

    // ════════════════════════════════════════════════════════════════════
    // FUNGSI KEANGGOTAAN OUTPUT: Skor Prediksi (0 – 100)
    // Himpunan: Tidak Lulus, Cukup, Lulus
    // ════════════════════════════════════════════════════════════════════

    private static function outputTidakLulus(float $x): float
    {
        if ($x <= 30) return 1.0;
        if ($x >= 40) return 0.0;
        return (40 - $x) / 10;
    }

    private static function outputCukup(float $x): float
    {
        if ($x <= 30 || $x >= 70) return 0.0;
        if ($x <= 40) return ($x - 30) / 10;
        if ($x <= 60) return 1.0;
        return (70 - $x) / 10;
    }

    private static function outputLulus(float $x): float
    {
        if ($x <= 60) return 0.0;
        if ($x >= 70) return 1.0;
        return ($x - 60) / 10;
    }

    // ════════════════════════════════════════════════════════════════════
    // PENGAMBILAN DATA
    // ════════════════════════════════════════════════════════════════════

    public static function hitungKehadiran(Mahasiswa $mahasiswa, Matakuliah $matakuliah): float
    {
        $jadwalIds = DB::table('jadwal_kuliah')
            ->where('matakuliah_id', $matakuliah->id)
            ->pluck('id');

        $total = Presensi::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('jadwal_id', $jadwalIds)
            ->count();

        if ($total == 0) return 100.0;

        $hadir = Presensi::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('jadwal_id', $jadwalIds)
            ->whereIn('status_hadir', ['hadir', 'izin', 'sakit'])
            ->count();

        return round(($hadir / $total) * 100, 2);
    }

    public static function hitungNilaiTugas(Mahasiswa $mahasiswa, Matakuliah $matakuliah): float
    {
        $krs = TransaksiKrs::where('mahasiswa_id', $mahasiswa->id)
            ->where('matakuliah_id', $matakuliah->id)
            ->where('status_validasi', 'disetujui')
            ->first();

        if (!$krs) return 0.0;

        $nilai = Nilai::where('krs_id', $krs->id)->first();
        return $nilai ? (float) $nilai->nilai_tugas : 0.0;
    }

    public static function hitungKeaktifanDiskusi(Mahasiswa $mahasiswa, Matakuliah $matakuliah): float
    {
        $jadwalIds = DB::table('jadwal_kuliah')
            ->where('matakuliah_id', $matakuliah->id)
            ->pluck('id');

        $total = Presensi::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('jadwal_id', $jadwalIds)
            ->count();

        if ($total == 0) return 50.0;

        // Keaktifan dihitung dari rasio kehadiran aktif (hadir tanpa izin/sakit)
        $aktif = Presensi::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('jadwal_id', $jadwalIds)
            ->where('status_hadir', 'hadir')
            ->count();

        return round(($aktif / $total) * 100, 2);
    }

    // ════════════════════════════════════════════════════════════════════
    // FUZZY INFERENCE (Mamdani) — 27 Rules (3×3×3)
    // ════════════════════════════════════════════════════════════════════

    public static function hitungPrediksi(float $kehadiran, float $tugas, float $diskusi): array
    {
        // ── Fuzzification ─────────────────────────────────────────────
        $kehR = self::kehadiranRendah($kehadiran);
        $kehS = self::kehadiranSedang($kehadiran);
        $kehT = self::kehadiranTinggi($kehadiran);

        $tugR = self::tugasRendah($tugas);
        $tugS = self::tugasSedang($tugas);
        $tugT = self::tugasTinggi($tugas);

        $disR = self::diskusiRendah($diskusi);
        $disS = self::diskusiSedang($diskusi);
        $disT = self::diskusiTinggi($diskusi);

        // ── Safety Check: Jika ada input = 0, otomatis tidak lulus ──
        if ($kehadiran == 0 || $tugas == 0 || $diskusi == 0) {
            return [
                'skor' => 0,
                'hasil' => 'tidak_lulus',
                'fuzzification' => [
                    'kehadiran' => ['rendah' => round($kehR, 2), 'sedang' => round($kehS, 2), 'tinggi' => round($kehT, 2)],
                    'tugas'     => ['rendah' => round($tugR, 2), 'sedang' => round($tugS, 2), 'tinggi' => round($tugT, 2)],
                    'diskusi'   => ['rendah' => round($disR, 2), 'sedang' => round($disS, 2), 'tinggi' => round($disT, 2)],
                ],
                'rules_aktif' => ['R1: Kehadiran Rendah & Tugas Rendah & Diskusi Rendah | μ=1.00 → tidak_lulus (safety: input kosong)'],
            ];
        }

        // ── Rule Base (27 rules) ─────────────────────────────────────
        $rules = [];

        // R1-R9: Kehadiran Rendah
        $rules[] = ['desc' => 'Kehadiran Rendah & Tugas Rendah & Diskusi Rendah',     'min' => min($kehR, $tugR, $disR), 'output' => 'tidak_lulus'];
        $rules[] = ['desc' => 'Kehadiran Rendah & Tugas Rendah & Diskusi Sedang',     'min' => min($kehR, $tugR, $disS), 'output' => 'tidak_lulus'];
        $rules[] = ['desc' => 'Kehadiran Rendah & Tugas Rendah & Diskusi Tinggi',     'min' => min($kehR, $tugR, $disT), 'output' => 'tidak_lulus'];
        $rules[] = ['desc' => 'Kehadiran Rendah & Tugas Sedang & Diskusi Rendah',     'min' => min($kehR, $tugS, $disR), 'output' => 'tidak_lulus'];
        $rules[] = ['desc' => 'Kehadiran Rendah & Tugas Sedang & Diskusi Sedang',     'min' => min($kehR, $tugS, $disS), 'output' => 'tidak_lulus'];
        $rules[] = ['desc' => 'Kehadiran Rendah & Tugas Sedang & Diskusi Tinggi',     'min' => min($kehR, $tugS, $disT), 'output' => 'cukup'];
        $rules[] = ['desc' => 'Kehadiran Rendah & Tugas Tinggi & Diskusi Rendah',     'min' => min($kehR, $tugT, $disR), 'output' => 'tidak_lulus'];
        $rules[] = ['desc' => 'Kehadiran Rendah & Tugas Tinggi & Diskusi Sedang',     'min' => min($kehR, $tugT, $disS), 'output' => 'cukup'];
        $rules[] = ['desc' => 'Kehadiran Rendah & Tugas Tinggi & Diskusi Tinggi',     'min' => min($kehR, $tugT, $disT), 'output' => 'cukup'];

        // R10-R18: Kehadiran Sedang
        $rules[] = ['desc' => 'Kehadiran Sedang & Tugas Rendah & Diskusi Rendah',     'min' => min($kehS, $tugR, $disR), 'output' => 'tidak_lulus'];
        $rules[] = ['desc' => 'Kehadiran Sedang & Tugas Rendah & Diskusi Sedang',     'min' => min($kehS, $tugR, $disS), 'output' => 'cukup'];
        $rules[] = ['desc' => 'Kehadiran Sedang & Tugas Rendah & Diskusi Tinggi',     'min' => min($kehS, $tugR, $disT), 'output' => 'cukup'];
        $rules[] = ['desc' => 'Kehadiran Sedang & Tugas Sedang & Diskusi Rendah',     'min' => min($kehS, $tugS, $disR), 'output' => 'cukup'];
        $rules[] = ['desc' => 'Kehadiran Sedang & Tugas Sedang & Diskusi Sedang',     'min' => min($kehS, $tugS, $disS), 'output' => 'cukup'];
        $rules[] = ['desc' => 'Kehadiran Sedang & Tugas Sedang & Diskusi Tinggi',     'min' => min($kehS, $tugS, $disT), 'output' => 'lulus'];
        $rules[] = ['desc' => 'Kehadiran Sedang & Tugas Tinggi & Diskusi Rendah',     'min' => min($kehS, $tugT, $disR), 'output' => 'cukup'];
        $rules[] = ['desc' => 'Kehadiran Sedang & Tugas Tinggi & Diskusi Sedang',     'min' => min($kehS, $tugT, $disS), 'output' => 'lulus'];
        $rules[] = ['desc' => 'Kehadiran Sedang & Tugas Tinggi & Diskusi Tinggi',     'min' => min($kehS, $tugT, $disT), 'output' => 'lulus'];

        // R19-R27: Kehadiran Tinggi
        $rules[] = ['desc' => 'Kehadiran Tinggi & Tugas Rendah & Diskusi Rendah',     'min' => min($kehT, $tugR, $disR), 'output' => 'cukup'];
        $rules[] = ['desc' => 'Kehadiran Tinggi & Tugas Rendah & Diskusi Sedang',     'min' => min($kehT, $tugR, $disS), 'output' => 'cukup'];
        $rules[] = ['desc' => 'Kehadiran Tinggi & Tugas Rendah & Diskusi Tinggi',     'min' => min($kehT, $tugR, $disT), 'output' => 'lulus'];
        $rules[] = ['desc' => 'Kehadiran Tinggi & Tugas Sedang & Diskusi Rendah',     'min' => min($kehT, $tugS, $disR), 'output' => 'cukup'];
        $rules[] = ['desc' => 'Kehadiran Tinggi & Tugas Sedang & Diskusi Sedang',     'min' => min($kehT, $tugS, $disS), 'output' => 'lulus'];
        $rules[] = ['desc' => 'Kehadiran Tinggi & Tugas Sedang & Diskusi Tinggi',     'min' => min($kehT, $tugS, $disT), 'output' => 'lulus'];
        $rules[] = ['desc' => 'Kehadiran Tinggi & Tugas Tinggi & Diskusi Rendah',     'min' => min($kehT, $tugT, $disR), 'output' => 'lulus'];
        $rules[] = ['desc' => 'Kehadiran Tinggi & Tugas Tinggi & Diskusi Sedang',     'min' => min($kehT, $tugT, $disS), 'output' => 'lulus'];
        $rules[] = ['desc' => 'Kehadiran Tinggi & Tugas Tinggi & Diskusi Tinggi',     'min' => min($kehT, $tugT, $disT), 'output' => 'lulus'];

        // ── Defuzzification (Weighted Average) ────────────────────────
        $centroidPoints = [
            'tidak_lulus' => 20,
            'cukup' => 50,
            'lulus' => 80,
        ];

        $numerator = 0;
        $denominator = 0;
        $detail = [];

        foreach ($rules as $i => $rule) {
            if ($rule['min'] > 0) {
                $numerator += $rule['min'] * $centroidPoints[$rule['output']];
                $denominator += $rule['min'];
                $detail[] = [
                    'rule' => 'R' . ($i + 1),
                    'kondisi' => $rule['desc'],
                    'mu' => round($rule['min'], 2),
                    'output' => $rule['output'],
                ];
            }
        }

        $skor = $denominator > 0 ? round($numerator / $denominator, 2) : 0;

        return [
            'skor' => $skor,
            'hasil' => $skor >= 60 ? 'lulus' : ($skor >= 40 ? 'cukup' : 'tidak_lulus'),
            'fuzzification' => [
                'kehadiran' => ['rendah' => round($kehR, 2), 'sedang' => round($kehS, 2), 'tinggi' => round($kehT, 2)],
                'tugas'     => ['rendah' => round($tugR, 2), 'sedang' => round($tugS, 2), 'tinggi' => round($tugT, 2)],
                'diskusi'   => ['rendah' => round($disR, 2), 'sedang' => round($disS, 2), 'tinggi' => round($disT, 2)],
            ],
            'rules_aktif' => $detail,
        ];
    }

    // ════════════════════════════════════════════════════════════════════
    // ANALISIS LENGKAP: 1 Mahasiswa × 1 Mata Kuliah
    // ════════════════════════════════════════════════════════════════════

    public static function analisis(Mahasiswa $mahasiswa, Matakuliah $matakuliah): array
    {
        $kehadiran = self::hitungKehadiran($mahasiswa, $matakuliah);
        $nilaiTugas = self::hitungNilaiTugas($mahasiswa, $matakuliah);
        $keaktifanDiskusi = self::hitungKeaktifanDiskusi($mahasiswa, $matakuliah);

        $hasil = self::hitungPrediksi($kehadiran, $nilaiTugas, $keaktifanDiskusi);

        return array_merge($hasil, [
            'input' => [
                'kehadiran' => $kehadiran,
                'nilai_tugas' => $nilaiTugas,
                'keaktifan_diskusi' => $keaktifanDiskusi,
            ],
        ]);
    }
}
