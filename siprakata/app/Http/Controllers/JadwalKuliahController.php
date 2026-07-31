<?php
namespace App\Http\Controllers;

use App\Models\JadwalKuliah;
use App\Models\Matakuliah;
use App\Models\Dosen;
use App\Models\Ruangan;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class JadwalKuliahController extends Controller {
    public function index() {
        $jadwal = JadwalKuliah::with(['matakuliah','dosen','ruanganRef','tahunAjaran'])->orderBy('hari')->get();
        return view('jadwal.index', compact('jadwal'));
    }
    public function create() {
        $matakuliah  = Matakuliah::all();
        $dosen       = Dosen::all();
        $ruangan     = Ruangan::all();
        $tahunAjaran = TahunAjaran::orderByDesc('tahun')->get();
        return view('jadwal.create', compact('matakuliah','dosen','ruangan','tahunAjaran'));
    }
    public function store(Request $request) {
        $request->validate([
            'matakuliah_id'   => 'required|exists:matakuliah,id',
            'dosen_id'        => 'required|exists:dosen,id',
            'hari'            => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'       => 'required',
            'jam_selesai'     => 'required|after:jam_mulai',
            'ruangan_id'      => 'nullable|exists:ruangan,id',
            'tahun_ajaran_id' => 'nullable|exists:tahun_ajaran,id',
        ]);
        // Simpan juga string untuk kompatibilitas tampilan
        $data = $request->only('matakuliah_id','dosen_id','hari','jam_mulai','jam_selesai','ruangan_id','tahun_ajaran_id');
        if ($request->ruangan_id) {
            $data['ruangan'] = Ruangan::find($request->ruangan_id)->nama_ruangan;
        }
        if ($request->tahun_ajaran_id) {
            $ta = TahunAjaran::find($request->tahun_ajaran_id);
            $data['tahun_ajaran'] = $ta->tahun;
            $data['semester']     = $ta->semester;
        }
        JadwalKuliah::create($data);
        return redirect()->route('jadwal.index')->with('success','Jadwal berhasil ditambahkan.');
    }
    public function edit(JadwalKuliah $jadwal) {
        $matakuliah  = Matakuliah::all();
        $dosen       = Dosen::all();
        $ruangan     = Ruangan::all();
        $tahunAjaran = TahunAjaran::orderByDesc('tahun')->get();
        return view('jadwal.edit', compact('jadwal','matakuliah','dosen','ruangan','tahunAjaran'));
    }
    public function update(Request $request, JadwalKuliah $jadwal) {
        $request->validate([
            'matakuliah_id'   => 'required|exists:matakuliah,id',
            'dosen_id'        => 'required|exists:dosen,id',
            'hari'            => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'       => 'required',
            'jam_selesai'     => 'required|after:jam_mulai',
            'ruangan_id'      => 'nullable|exists:ruangan,id',
            'tahun_ajaran_id' => 'nullable|exists:tahun_ajaran,id',
        ]);
        $data = $request->only('matakuliah_id','dosen_id','hari','jam_mulai','jam_selesai','ruangan_id','tahun_ajaran_id');
        if ($request->ruangan_id) {
            $data['ruangan'] = Ruangan::find($request->ruangan_id)->nama_ruangan;
        }
        if ($request->tahun_ajaran_id) {
            $ta = TahunAjaran::find($request->tahun_ajaran_id);
            $data['tahun_ajaran'] = $ta->tahun;
            $data['semester']     = $ta->semester;
        }
        $jadwal->update($data);
        return redirect()->route('jadwal.index')->with('success','Jadwal berhasil diperbarui.');
    }
    public function destroy(JadwalKuliah $jadwal) {
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success','Jadwal berhasil dihapus.');
    }
}