<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKrs;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;

class TransaksiKrsController extends Controller
{
    public function index()
    {
        $krs = TransaksiKrs::with(['mahasiswa', 'matakuliah', 'dosen'])->get();
        return view('krs.index', compact('krs'));
    }

    public function create()
    {
        $mahasiswa   = Mahasiswa::all();
        $matakuliah  = Matakuliah::all();
        $dosen       = Dosen::all();
        return view('krs.create', compact('mahasiswa', 'matakuliah', 'dosen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id'  => 'required|exists:mahasiswa,id',
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'dosen_id'      => 'required|exists:dosen,id',
            'tahun_ajaran'  => 'required|string|max:20',
            'semester'      => 'required|string|max:20',
        ]);

        $exists = TransaksiKrs::where('mahasiswa_id',  $request->mahasiswa_id)
                               ->where('matakuliah_id', $request->matakuliah_id)
                               ->where('tahun_ajaran',  $request->tahun_ajaran)
                               ->where('semester',      $request->semester)
                               ->exists();

        if ($exists) {
            return back()->withErrors(['matakuliah_id' => 'Mahasiswa sudah mengambil mata kuliah ini pada tahun dan semester yang sama.'])->withInput();
        }

        TransaksiKrs::create(array_merge(
            $request->only('mahasiswa_id', 'matakuliah_id', 'dosen_id', 'tahun_ajaran', 'semester'),
            ['status' => 'aktif', 'status_validasi' => 'pending']
        ));

        return redirect()->route('krs.index')->with('success', 'KRS berhasil diajukan. Menunggu validasi dosen.');
    }

    public function edit(TransaksiKrs $krs)
    {
        $mahasiswa   = Mahasiswa::all();
        $matakuliah  = Matakuliah::all();
        $dosen       = Dosen::all();
        return view('krs.edit', compact('krs', 'mahasiswa', 'matakuliah', 'dosen'));
    }

    public function update(Request $request, TransaksiKrs $krs)
    {
        $request->validate([
            'mahasiswa_id'  => 'required|exists:mahasiswa,id',
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'dosen_id'      => 'required|exists:dosen,id',
            'tahun_ajaran'  => 'required|string|max:20',
            'semester'      => 'required|string|max:20',
            'status'        => 'required|in:aktif,selesai',
        ]);

        $krs->update($request->only(
            'mahasiswa_id', 'matakuliah_id', 'dosen_id', 'tahun_ajaran', 'semester', 'status'
        ));

        return redirect()->route('krs.index')->with('success', 'KRS berhasil diperbarui.');
    }

    public function destroy(TransaksiKrs $krs)
    {
        $krs->delete();
        return redirect()->route('krs.index')->with('success', 'KRS berhasil dihapus.');
    }

    // ── Validasi oleh Dosen ─────────────────────────────────────────────
    public function validasi(TransaksiKrs $krs)
    {
        return view('krs.validasi', compact('krs'));
    }

    public function prosesValidasi(Request $request, TransaksiKrs $krs)
    {
        $request->validate([
            'status_validasi' => 'required|in:disetujui,ditolak',
            'catatan_validasi' => 'nullable|string|max:500',
        ]);

        $krs->update([
            'status_validasi'  => $request->status_validasi,
            'catatan_validasi' => $request->catatan_validasi,
            'tgl_validasi'     => now(),
        ]);

        $status = $request->status_validasi === 'disetujui' ? 'disetujui' : 'ditolak';
        return redirect()->route('krs.index')->with('success', "KRS berhasil {$status}.");
    }
}
