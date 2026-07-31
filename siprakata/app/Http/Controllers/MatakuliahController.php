<?php
namespace App\Http\Controllers;

use App\Models\Matakuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class MatakuliahController extends Controller {
    public function index() {
        $matakuliah = Matakuliah::with('prodi')->get();
        return view('matakuliah.index', compact('matakuliah'));
    }
    public function create() {
        $prodi = ProgramStudi::all();
        return view('matakuliah.Create', compact('prodi'));
    }
    public function store(Request $request) {
        $request->validate([
            'kode_mk'  => 'required|string|unique:matakuliah,kode_mk',
            'nama_mk'  => 'required|string|max:255',
            'sks'      => 'required|integer|min:1|max:6',
            'semester' => 'required|string',
            'prodi_id' => 'nullable|exists:program_studi,id',
        ]);
        Matakuliah::create($request->only('kode_mk','nama_mk','sks','semester','prodi_id'));
        return redirect()->route('matakuliah.index')->with('success','Mata kuliah berhasil ditambahkan.');
    }
    public function edit(Matakuliah $matakuliah) {
        $prodi = ProgramStudi::all();
        return view('matakuliah.Edit', compact('matakuliah','prodi'));
    }
    public function update(Request $request, Matakuliah $matakuliah) {
        $request->validate([
            'kode_mk'  => 'required|string|unique:matakuliah,kode_mk,'.$matakuliah->id,
            'nama_mk'  => 'required|string|max:255',
            'sks'      => 'required|integer|min:1|max:6',
            'semester' => 'required|string',
            'prodi_id' => 'nullable|exists:program_studi,id',
        ]);
        $matakuliah->update($request->only('kode_mk','nama_mk','sks','semester','prodi_id'));
        return redirect()->route('matakuliah.index')->with('success','Mata kuliah berhasil diperbarui.');
    }
    public function destroy(Matakuliah $matakuliah) {
        $matakuliah->delete();
        return redirect()->route('matakuliah.index')->with('success','Mata kuliah berhasil dihapus.');
    }
}