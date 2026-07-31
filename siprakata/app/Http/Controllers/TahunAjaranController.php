<?php
namespace App\Http\Controllers;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller {
    public function index() {
        $ta = TahunAjaran::orderByDesc('tahun')->get();
        return view('tahun_ajaran.index', compact('ta'));
    }
    public function create() { return view('tahun_ajaran.create'); }
    public function store(Request $request) {
        $request->validate([
            'tahun'        => 'required|string|max:20',
            'semester'     => 'required|in:Ganjil,Genap',
            'tgl_mulai'    => 'required|date',
            'tgl_selesai'  => 'required|date|after:tgl_mulai',
            'status_aktif' => 'boolean',
        ]);
        // Jika diaktifkan, nonaktifkan yang lain
        if ($request->status_aktif) TahunAjaran::query()->update(['status_aktif' => false]);
        TahunAjaran::create(array_merge($request->only('tahun','semester','tgl_mulai','tgl_selesai'), ['status_aktif' => $request->boolean('status_aktif')]));
        return redirect()->route('tahun_ajaran.index')->with('success','Tahun ajaran berhasil ditambahkan.');
    }
    public function edit(TahunAjaran $tahun_ajaran) { return view('tahun_ajaran.edit', compact('tahun_ajaran')); }
    public function update(Request $request, TahunAjaran $tahun_ajaran) {
        $request->validate([
            'tahun'        => 'required|string|max:20',
            'semester'     => 'required|in:Ganjil,Genap',
            'tgl_mulai'    => 'required|date',
            'tgl_selesai'  => 'required|date|after:tgl_mulai',
        ]);
        if ($request->status_aktif) TahunAjaran::where('id','!=',$tahun_ajaran->id)->update(['status_aktif' => false]);
        $tahun_ajaran->update(array_merge($request->only('tahun','semester','tgl_mulai','tgl_selesai'), ['status_aktif' => $request->boolean('status_aktif')]));
        return redirect()->route('tahun_ajaran.index')->with('success','Tahun ajaran berhasil diperbarui.');
    }
    public function destroy(TahunAjaran $tahun_ajaran) {
        $tahun_ajaran->delete();
        return redirect()->route('tahun_ajaran.index')->with('success','Tahun ajaran berhasil dihapus.');
    }
}