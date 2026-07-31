<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\JadwalKuliah;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index()
    {
        $presensi = Presensi::with(['jadwal.matakuliah','mahasiswa'])->orderByDesc('tanggal')->get();
        return view('presensi.index', compact('presensi'));
    }

    public function create()
    {
        $jadwal = JadwalKuliah::with('matakuliah')->get();
        $mahasiswa = Mahasiswa::all();
        return view('presensi.create', compact('jadwal','mahasiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id'     => 'required|exists:jadwal_kuliah,id',
            'mahasiswa_id'  => 'required|exists:mahasiswa,id',
            'tanggal'       => 'required|date',
            'pertemuan_ke'  => 'required|integer|min:1|max:16',
            'status_hadir'  => 'required|in:hadir,izin,sakit,alpha',
            'keterangan'    => 'nullable|string|max:255',
        ]);

        $exists = Presensi::where('jadwal_id', $request->jadwal_id)
                          ->where('mahasiswa_id', $request->mahasiswa_id)
                          ->where('pertemuan_ke', $request->pertemuan_ke)
                          ->exists();

        if ($exists) {
            return back()->withErrors(['pertemuan_ke' => 'Presensi mahasiswa ini pada pertemuan tersebut sudah ada.'])->withInput();
        }

        Presensi::create($request->only('jadwal_id','mahasiswa_id','tanggal','pertemuan_ke','status_hadir','keterangan'));

        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil dicatat.');
    }

    public function edit(Presensi $presensi)
    {
        $jadwal = JadwalKuliah::with('matakuliah')->get();
        $mahasiswa = Mahasiswa::all();
        return view('presensi.edit', compact('presensi','jadwal','mahasiswa'));
    }

    public function update(Request $request, Presensi $presensi)
    {
        $request->validate([
            'jadwal_id'     => 'required|exists:jadwal_kuliah,id',
            'mahasiswa_id'  => 'required|exists:mahasiswa,id',
            'tanggal'       => 'required|date',
            'pertemuan_ke'  => 'required|integer|min:1|max:16',
            'status_hadir'  => 'required|in:hadir,izin,sakit,alpha',
            'keterangan'    => 'nullable|string|max:255',
        ]);

        $presensi->update($request->only('jadwal_id','mahasiswa_id','tanggal','pertemuan_ke','status_hadir','keterangan'));

        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil diperbarui.');
    }

    public function destroy(Presensi $presensi)
    {
        $presensi->delete();
        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil dihapus.');
    }
}