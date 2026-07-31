<?php
namespace App\Http\Controllers;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller {
    public function index() {
        $prodi = ProgramStudi::all();
        return view('prodi.index', compact('prodi'));
    }
    public function create() { return view('prodi.create'); }
    public function store(Request $request) {
        $request->validate([
            'nama_prodi'  => 'required|string|max:255',
            'kode_prodi'  => 'required|string|unique:program_studi,kode_prodi',
            'jenjang'     => 'required|in:D3,S1,S2,S3',
            'fakultas'    => 'required|string|max:255',
        ]);
        ProgramStudi::create($request->only('nama_prodi','kode_prodi','jenjang','fakultas'));
        return redirect()->route('prodi.index')->with('success','Program studi berhasil ditambahkan.');
    }
    public function edit(ProgramStudi $prodi) { return view('prodi.edit', compact('prodi')); }
    public function update(Request $request, ProgramStudi $prodi) {
        $request->validate([
            'nama_prodi'  => 'required|string|max:255',
            'kode_prodi'  => 'required|string|unique:program_studi,kode_prodi,'.$prodi->id,
            'jenjang'     => 'required|in:D3,S1,S2,S3',
            'fakultas'    => 'required|string|max:255',
        ]);
        $prodi->update($request->only('nama_prodi','kode_prodi','jenjang','fakultas'));
        return redirect()->route('prodi.index')->with('success','Program studi berhasil diperbarui.');
    }
    public function destroy(ProgramStudi $prodi) {
        $prodi->delete();
        return redirect()->route('prodi.index')->with('success','Program studi berhasil dihapus.');
    }
}