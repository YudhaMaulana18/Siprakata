<?php
namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class DosenController extends Controller {
    public function index() {
        $dosen = Dosen::with('prodi')->get();
        return view('dosen.Index', compact('dosen'));
    }
    public function create() {
        $prodi = ProgramStudi::all();
        return view('dosen.Create', compact('prodi'));
    }
    public function store(Request $request) {
        $request->validate([
            'NIDN'     => 'required|string|unique:dosen,NIDN',
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:dosen,email',
            'no_hp'    => 'nullable|string|max:20',
            'jabatan'  => 'nullable|string|max:100',
            'prodi_id' => 'nullable|exists:program_studi,id',
        ]);
        Dosen::create($request->only('NIDN','nama','email','no_hp','jabatan','prodi_id'));
        return redirect()->route('dosen.index')->with('success','Dosen berhasil ditambahkan.');
    }
    public function edit(Dosen $dosen) {
        $prodi = ProgramStudi::all();
        return view('dosen.Edit', compact('dosen','prodi'));
    }
    public function update(Request $request, Dosen $dosen) {
        $request->validate([
            'NIDN'     => 'required|string|unique:dosen,NIDN,'.$dosen->id,
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:dosen,email,'.$dosen->id,
            'no_hp'    => 'nullable|string|max:20',
            'jabatan'  => 'nullable|string|max:100',
            'prodi_id' => 'nullable|exists:program_studi,id',
        ]);
        $dosen->update($request->only('NIDN','nama','email','no_hp','jabatan','prodi_id'));
        return redirect()->route('dosen.index')->with('success','Data dosen berhasil diperbarui.');
    }
    public function destroy(Dosen $dosen) {
        $dosen->delete();
        return redirect()->route('dosen.index')->with('success','Dosen berhasil dihapus.');
    }
}