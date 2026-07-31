<?php
namespace App\Http\Controllers;
use App\Models\Pengumuman;
use App\Models\Dosen;
use App\Models\JadwalKuliah;
use Illuminate\Http\Request;

class PengumumanController extends Controller {
    public function index() {
        $pengumuman = Pengumuman::with(['dosen','jadwal.matakuliah'])->orderByDesc('tgl_posting')->get();
        return view('pengumuman.index', compact('pengumuman'));
    }
    public function create() {
        $dosen  = Dosen::all();
        $jadwal = JadwalKuliah::with('matakuliah')->get();
        return view('pengumuman.create', compact('dosen','jadwal'));
    }
    public function store(Request $request) {
        $request->validate([
            'dosen_id'        => 'required|exists:dosen,id',
            'jadwal_id'       => 'nullable|exists:jadwal_kuliah,id',
            'judul'           => 'required|string|max:255',
            'isi'             => 'required|string',
            'prioritas'       => 'required|in:rendah,sedang,tinggi',
            'tgl_posting'     => 'required|date',
            'tgl_kadaluarsa'  => 'nullable|date|after:tgl_posting',
        ]);
        Pengumuman::create($request->only('dosen_id','jadwal_id','judul','isi','prioritas','tgl_posting','tgl_kadaluarsa'));
        return redirect()->route('pengumuman.index')->with('success','Pengumuman berhasil ditambahkan.');
    }
    public function edit(Pengumuman $pengumuman) {
        $dosen  = Dosen::all();
        $jadwal = JadwalKuliah::with('matakuliah')->get();
        return view('pengumuman.edit', compact('pengumuman','dosen','jadwal'));
    }
    public function update(Request $request, Pengumuman $pengumuman) {
        $request->validate([
            'dosen_id'        => 'required|exists:dosen,id',
            'jadwal_id'       => 'nullable|exists:jadwal_kuliah,id',
            'judul'           => 'required|string|max:255',
            'isi'             => 'required|string',
            'prioritas'       => 'required|in:rendah,sedang,tinggi',
            'tgl_posting'     => 'required|date',
            'tgl_kadaluarsa'  => 'nullable|date|after:tgl_posting',
        ]);
        $pengumuman->update($request->only('dosen_id','jadwal_id','judul','isi','prioritas','tgl_posting','tgl_kadaluarsa'));
        return redirect()->route('pengumuman.index')->with('success','Pengumuman berhasil diperbarui.');
    }
    public function destroy(Pengumuman $pengumuman) {
        $pengumuman->delete();
        return redirect()->route('pengumuman.index')->with('success','Pengumuman berhasil dihapus.');
    }
}