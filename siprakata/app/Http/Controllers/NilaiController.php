<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\TransaksiKrs;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {
        $nilai = Nilai::with(['krs.mahasiswa','krs.matakuliah'])->get();
        return view('nilai.index', compact('nilai'));
    }

    public function create()
    {
        $krs = TransaksiKrs::with(['mahasiswa','matakuliah'])
                ->where('status_validasi', 'disetujui')
                ->whereDoesntHave('nilai')
                ->get();
        return view('nilai.create', compact('krs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'krs_id'       => 'required|exists:transaksi_krs,id|unique:nilai,krs_id',
            'nilai_tugas'  => 'required|numeric|min:0|max:100',
            'nilai_uts'    => 'required|numeric|min:0|max:100',
            'nilai_uas'    => 'required|numeric|min:0|max:100',
        ]);

        $krs = TransaksiKrs::findOrFail($request->krs_id);
        if ($krs->status_validasi !== 'disetujui') {
            return back()->withErrors(['krs_id' => 'KRS belum disetujui oleh dosen.'])->withInput();
        }

        Nilai::create($request->only('krs_id','nilai_tugas','nilai_uts','nilai_uas'));

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil ditambahkan.');
    }

    public function edit(Nilai $nilai)
    {
        $krs = TransaksiKrs::with(['mahasiswa','matakuliah'])->get();
        return view('nilai.edit', compact('nilai','krs'));
    }

    public function update(Request $request, Nilai $nilai)
    {
        $request->validate([
            'nilai_tugas' => 'required|numeric|min:0|max:100',
            'nilai_uts'   => 'required|numeric|min:0|max:100',
            'nilai_uas'   => 'required|numeric|min:0|max:100',
        ]);

        $nilai->update($request->only('nilai_tugas','nilai_uts','nilai_uas'));

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil diperbarui.');
    }

    public function destroy(Nilai $nilai)
    {
        $nilai->delete();
        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil dihapus.');
    }
}