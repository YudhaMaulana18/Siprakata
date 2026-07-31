<?php
namespace App\Http\Controllers;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller {
    public function index() {
        $ruangan = Ruangan::all();
        return view('ruangan.index', compact('ruangan'));
    }
    public function create() { return view('ruangan.create'); }
    public function store(Request $request) {
        $request->validate([
            'kode_ruangan'  => 'required|string|unique:ruangan,kode_ruangan',
            'nama_ruangan'  => 'required|string|max:255',
            'kapasitas'     => 'required|integer|min:1',
            'gedung'        => 'required|string|max:100',
            'lantai'        => 'required|string|max:20',
            'jenis'         => 'required|in:Kelas,Laboratorium,Aula,Lainnya',
        ]);
        Ruangan::create($request->only('kode_ruangan','nama_ruangan','kapasitas','gedung','lantai','jenis'));
        return redirect()->route('ruangan.index')->with('success','Ruangan berhasil ditambahkan.');
    }
    public function edit(Ruangan $ruangan) { return view('ruangan.edit', compact('ruangan')); }
    public function update(Request $request, Ruangan $ruangan) {
        $request->validate([
            'kode_ruangan'  => 'required|string|unique:ruangan,kode_ruangan,'.$ruangan->id,
            'nama_ruangan'  => 'required|string|max:255',
            'kapasitas'     => 'required|integer|min:1',
            'gedung'        => 'required|string|max:100',
            'lantai'        => 'required|string|max:20',
            'jenis'         => 'required|in:Kelas,Laboratorium,Aula,Lainnya',
        ]);
        $ruangan->update($request->only('kode_ruangan','nama_ruangan','kapasitas','gedung','lantai','jenis'));
        return redirect()->route('ruangan.index')->with('success','Ruangan berhasil diperbarui.');
    }
    public function destroy(Ruangan $ruangan) {
        $ruangan->delete();
        return redirect()->route('ruangan.index')->with('success','Ruangan berhasil dihapus.');
    }
}