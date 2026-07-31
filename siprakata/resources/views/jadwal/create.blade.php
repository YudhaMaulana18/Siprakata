@extends('layouts.App')
@section('title','Tambah Jadwal') @section('page-title','Tambah Jadwal Kuliah')
@section('content')
<div class="card" style="max-width:680px">
    <div class="card-header"><i class="bi bi-plus-circle-fill me-2"></i>Form Tambah Jadwal</div>
    <div class="card-body">
        <form action="{{ route('jadwal.store') }}" method="POST">@csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Mata Kuliah</label>
                    <select name="matakuliah_id" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @foreach($matakuliah as $mk)<option value="{{ $mk->id }}" {{ old('matakuliah_id')==$mk->id?'selected':'' }}>{{ $mk->kode_mk }} – {{ $mk->nama_mk }}</option>@endforeach
                    </select></div>
                <div class="col-md-6"><label class="form-label">Dosen</label>
                    <select name="dosen_id" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @foreach($dosen as $d)<option value="{{ $d->id }}" {{ old('dosen_id')==$d->id?'selected':'' }}>{{ $d->nama }}</option>@endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label">Hari</label>
                    <select name="hari" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)<option value="{{ $h }}" {{ old('hari')==$h?'selected':'' }}>{{ $h }}</option>@endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}" required></div>
                <div class="col-md-4"><label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}" required></div>
                <div class="col-md-6"><label class="form-label">Ruangan</label>
                    <select name="ruangan_id" class="form-select">
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach($ruangan as $r)<option value="{{ $r->id }}" {{ old('ruangan_id')==$r->id?'selected':'' }}>{{ $r->kode_ruangan }} – {{ $r->nama_ruangan }}</option>@endforeach
                    </select></div>
                <div class="col-md-6"><label class="form-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" class="form-select">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($tahunAjaran as $ta)<option value="{{ $ta->id }}" {{ old('tahun_ajaran_id')==$ta->id?'selected':'' }} {{ $ta->status_aktif?'style=font-weight:bold':'' }}>{{ $ta->tahun }} – {{ $ta->semester }}{{ $ta->status_aktif?' (Aktif)':'' }}</option>@endforeach
                    </select></div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection