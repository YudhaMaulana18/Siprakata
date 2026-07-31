<?php
namespace App\Http\Controllers;
use App\Models\MateriKuliah;
use App\Models\JadwalKuliah;
use Illuminate\Http\Request;

class MateriKuliahController extends Controller {
    public function index() {
        $materi = MateriKuliah::with(['jadwal.matakuliah','jadwal.dosen'])->orderByDesc('created_at')->get();
        return view('materi.index', compact('materi'));
    }
    public function create() {
        $jadwal = JadwalKuliah::with('matakuliah')->get();
        return view('materi.create', compact('jadwal'));
    }
    public function store(Request $request) {
        $request->validate([
            'jadwal_id'     => 'required|exists:jadwal_kuliah,id',
            'pertemuan_ke'  => 'required|integer|min:1|max:16',
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'link_materi'   => 'nullable|url',
            'file'          => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ]);
        $data = $request->only('jadwal_id','pertemuan_ke','judul','deskripsi','link_materi');
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('materi', 'public');
        }
        MateriKuliah::create($data);
        return redirect()->route('materi.index')->with('success','Materi berhasil ditambahkan.');
    }
    public function edit(MateriKuliah $materi) {
        $jadwal = JadwalKuliah::with('matakuliah')->get();
        return view('materi.edit', compact('materi','jadwal'));
    }
    public function update(Request $request, MateriKuliah $materi) {
        $request->validate([
            'jadwal_id'    => 'required|exists:jadwal_kuliah,id',
            'pertemuan_ke' => 'required|integer|min:1|max:16',
            'judul'        => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'link_materi'  => 'nullable|url',
            'file'         => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ]);
        $data = $request->only('jadwal_id','pertemuan_ke','judul','deskripsi','link_materi');
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('materi', 'public');
        }
        $materi->update($data);
        return redirect()->route('materi.index')->with('success','Materi berhasil diperbarui.');
    }
    public function destroy(MateriKuliah $materi) {
        $materi->delete();
        return redirect()->route('materi.index')->with('success','Materi berhasil dihapus.');
    }
}