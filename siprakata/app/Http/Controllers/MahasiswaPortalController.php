<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\TransaksiKrs;
use App\Models\JadwalKuliah;
use App\Models\Presensi;
use App\Models\Nilai;
use App\Models\MateriKuliah;
use App\Models\Pengumuman;
use App\Models\Kelayakan;
use App\Models\Matakuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaPortalController extends Controller
{
    protected function getMahasiswa()
    {
        $user = Auth::user();
        return Mahasiswa::where('email', $user->email)->first()
            ?? Mahasiswa::where('nama', $user->name)->first();
    }

    public function dashboard()
    {
        $mhs = $this->getMahasiswa();

        $krsCount = TransaksiKrs::where('mahasiswa_id', $mhs?->id)->count();
        $jadwalCount = JadwalKuliah::whereIn('matakuliah_id', function ($q) use ($mhs) {
            $q->select('matakuliah_id')->from('transaksi_krs')
              ->where('mahasiswa_id', $mhs?->id)->where('status_validasi', 'disetujui');
        })->count();
        $presensiCount = Presensi::where('mahasiswa_id', $mhs?->id)->count();
        $nilaiCount = Nilai::whereIn('krs_id', function ($q) use ($mhs) {
            $q->select('id')->from('transaksi_krs')->where('mahasiswa_id', $mhs?->id)->where('status', 'selesai');
        })->count();

        $krsTerbaru = TransaksiKrs::with('matakuliah', 'dosen')
            ->where('mahasiswa_id', $mhs?->id)
            ->latest()
            ->take(5)
            ->get();

        return view('mahasiswa_portal.dashboard', compact('mhs', 'krsCount', 'jadwalCount', 'presensiCount', 'nilaiCount', 'krsTerbaru'));
    }

    public function krs()
    {
        $mhs = $this->getMahasiswa();
        $krs = TransaksiKrs::with('matakuliah', 'dosen')
            ->where('mahasiswa_id', $mhs?->id)
            ->latest()
            ->get();
        return view('mahasiswa_portal.krs', compact('krs'));
    }

    public function krsCreate()
    {
        $mhs = $this->getMahasiswa();
        $matakuliah = Matakuliah::all();
        $dosen = Dosen::all();
        return view('mahasiswa_portal.krs-create', compact('mhs', 'matakuliah', 'dosen'));
    }

    public function krsStore(Request $request)
    {
        $mhs = $this->getMahasiswa();
        if (!$mhs) return back()->withErrors('Data mahasiswa tidak ditemukan.');

        $request->validate([
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'dosen_id' => 'required|exists:dosen,id',
        ]);

        $exists = TransaksiKrs::where('mahasiswa_id', $mhs->id)
            ->where('matakuliah_id', $request->matakuliah_id)
            ->where('tahun_ajaran', '2025/2026')
            ->where('semester', 'Ganjil')
            ->exists();

        if ($exists) return back()->with('error', 'Mata kuliah sudah diambil.');

        $dosen = Dosen::find($request->dosen_id);
        if (!$dosen) return back()->withErrors('Dosen tidak ditemukan.');

        TransaksiKrs::create([
            'mahasiswa_id' => $mhs->id,
            'matakuliah_id' => $request->matakuliah_id,
            'dosen_id' => $request->dosen_id,
            'tahun_ajaran' => '2025/2026',
            'semester' => 'Ganjil',
            'status' => 'aktif',
            'status_validasi' => 'pending',
        ]);

        return redirect()->route('mhs.krs')->with('success', 'KRS berhasil diajukan.');
    }

    public function jadwal()
    {
        $mhs = $this->getMahasiswa();
        $matakuliahIds = TransaksiKrs::where('mahasiswa_id', $mhs?->id)
            ->where('status_validasi', 'disetujui')
            ->pluck('matakuliah_id');

        $jadwal = JadwalKuliah::with('matakuliah', 'dosen', 'ruanganRef')
            ->whereIn('matakuliah_id', $matakuliahIds)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('mahasiswa_portal.jadwal', compact('jadwal'));
    }

    public function presensi()
    {
        $mhs = $this->getMahasiswa();
        $presensi = Presensi::with('jadwal.matakuliah', 'jadwal.dosen')
            ->where('mahasiswa_id', $mhs?->id)
            ->latest()
            ->get();
        return view('mahasiswa_portal.presensi', compact('presensi'));
    }

    public function nilai()
    {
        $mhs = $this->getMahasiswa();
        $nilai = Nilai::with('krs.matakuliah', 'krs.dosen')
            ->whereIn('krs_id', TransaksiKrs::where('mahasiswa_id', $mhs?->id)
                ->where('status', 'selesai')
                ->pluck('id'))
            ->get();
        return view('mahasiswa_portal.nilai', compact('nilai'));
    }

    public function materi()
    {
        $mhs = $this->getMahasiswa();
        $matakuliahIds = TransaksiKrs::where('mahasiswa_id', $mhs?->id)
            ->where('status_validasi', 'disetujui')
            ->pluck('matakuliah_id');

        $materi = MateriKuliah::with('jadwal.matakuliah', 'jadwal.dosen')
            ->whereIn('jadwal_id', function ($q) use ($matakuliahIds) {
                $q->select('id')->from('jadwal_kuliah')
                  ->whereIn('matakuliah_id', $matakuliahIds);
            })
            ->latest()
            ->get();

        return view('mahasiswa_portal.materi', compact('materi'));
    }

    public function pengumuman()
    {
        $pengumuman = Pengumuman::with('dosen', 'jadwal.matakuliah')
            ->latest()
            ->get();
        return view('mahasiswa_portal.pengumuman', compact('pengumuman'));
    }

    public function kelayakan()
    {
        $mhs = $this->getMahasiswa();
        $kelayakan = Kelayakan::with('matakuliah')
            ->where('mahasiswa_id', $mhs?->id)
            ->latest()
            ->get();
        return view('mahasiswa_portal.kelayakan', compact('kelayakan'));
    }

    public function kelayakanCreate()
    {
        $mhs = $this->getMahasiswa();
        $matakuliah = Matakuliah::all();
        return view('mahasiswa_portal.kelayakan-create', compact('mhs', 'matakuliah'));
    }

    public function kelayakanProses(Request $request)
    {
        $mhs = $this->getMahasiswa();
        if (!$mhs) return back()->withErrors('Data mahasiswa tidak ditemukan.');

        $request->validate([
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'ips' => 'required|numeric|min:0|max:4',
            'ipk' => 'required|numeric|min:0|max:4',
            'jumlah_sks' => 'required|integer|min:0',
            'kehadiran' => 'required|integer|min:0|max:100',
        ]);

        $ipk = $request->ipk;
        $ips = $request->ips;
        $jumlah_sks = $request->jumlah_sks;
        $kehadiran = $request->kehadiran;

        // Fuzzy logic sederhana
        $skor = ($ipk * 0.35) + ($ips * 0.25) + min($jumlah_sks / 24, 1) * 0.2 + ($kehadiran / 100) * 0.2;
        $predikat = $skor >= 0.75 ? 'Layak' : ($skor >= 0.5 ? 'Cukup Layak' : 'Tidak Layak');

        Kelayakan::create([
            'mahasiswa_id' => $mhs->id,
            'matakuliah_id' => $request->matakuliah_id,
            'ips' => $ips,
            'ipk' => $ipk,
            'jumlah_sks' => $jumlah_sks,
            'kehadiran' => $kehadiran,
            'skor' => round($skor, 2),
            'predikat' => $predikat,
        ]);

        return redirect()->route('mhs.kelayakan')->with('success', 'Prediksi kelayakan berhasil.');
    }

    public function profile()
    {
        $mhs = $this->getMahasiswa();
        return view('mahasiswa_portal.profile', compact('mhs'));
    }
}
