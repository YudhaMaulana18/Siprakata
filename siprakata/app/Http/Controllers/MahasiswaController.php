<?php
namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index() {
        $data = Mahasiswa::with('prodi')->get();
        return view('data-mahasiswa', compact('data'));
    }

    public function create() {
        $prodi = ProgramStudi::all();
        return view('create-mahasiswa', compact('prodi'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'alamat'   => 'required|string|max:255',
            'NIM'      => 'required|string|unique:mahasiswa,NIM',
            'email'    => 'nullable|email',
            'no_hp'    => 'nullable|string|max:20',
            'angkatan' => 'nullable|digits:4',
            'status'   => 'required|in:aktif,cuti,lulus,keluar',
            'prodi_id' => 'nullable|exists:program_studi,id',
        ]);
        Mahasiswa::create($request->only('nama','alamat','NIM','email','no_hp','angkatan','status','prodi_id'));
        return redirect()->route('data-mahasiswa')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function edit($id) {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $prodi = ProgramStudi::all();
        return view('edit-mahasiswa', compact('mahasiswa','prodi'));
    }

    public function update(Request $request, $id) {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $request->validate([
            'nama'     => 'required|string|max:255',
            'alamat'   => 'required|string|max:255',
            'NIM'      => 'required|string|unique:mahasiswa,NIM,'.$id,
            'email'    => 'nullable|email',
            'no_hp'    => 'nullable|string|max:20',
            'angkatan' => 'nullable|digits:4',
            'status'   => 'required|in:aktif,cuti,lulus,keluar',
            'prodi_id' => 'nullable|exists:program_studi,id',
        ]);
        $mahasiswa->update($request->only('nama','alamat','NIM','email','no_hp','angkatan','status','prodi_id'));
        return redirect()->route('data-mahasiswa')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy($id) {
        Mahasiswa::findOrFail($id)->delete();
        return redirect()->route('data-mahasiswa')->with('success', 'Mahasiswa berhasil dihapus.');
    }
}