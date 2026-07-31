@extends('layouts.App')
@section('title','Buat Pengumuman') @section('page-title','Buat Pengumuman')
@section('content')
<div class="card" style="max-width:680px">
    <div class="card-header"><i class="bi bi-megaphone-fill me-2"></i>Form Pengumuman</div>
    <div class="card-body">
        <form action="{{ route('pengumuman.store') }}" method="POST">@csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Dosen</label>
                    <select name="dosen_id" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @foreach($dosen as $d)<option value="{{ $d->id }}" {{ old('dosen_id')==$d->id?'selected':'' }}>{{ $d->nama }}</option>@endforeach
                    </select></div>
                <div class="col-md-6"><label class="form-label">Kelas / Jadwal <span class="text-muted fw-normal">(opsional)</span></label>
                    <select name="jadwal_id" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($jadwal as $j)<option value="{{ $j->id }}" {{ old('jadwal_id')==$j->id?'selected':'' }}>{{ $j->matakuliah->nama_mk }} – {{ $j->hari }}</option>@endforeach
                    </select></div>
                <div class="col-12"><label class="form-label">Judul Pengumuman</label>
                    <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required></div>
                <div class="col-12"><label class="form-label">Isi Pengumuman</label>
                    <textarea name="isi" class="form-control" rows="4" required>{{ old('isi') }}</textarea></div>
                <div class="col-md-4"><label class="form-label">Prioritas</label>
                    <select name="prioritas" class="form-select" required>
                        @foreach(['rendah','sedang','tinggi'] as $pr)<option value="{{ $pr }}" {{ old('prioritas','sedang')==$pr?'selected':'' }}>{{ ucfirst($pr) }}</option>@endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label">Tanggal Posting</label>
                    <input type="date" name="tgl_posting" class="form-control" value="{{ old('tgl_posting',date('Y-m-d')) }}" required></div>
                <div class="col-md-4"><label class="form-label">Kadaluarsa <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="date" name="tgl_kadaluarsa" class="form-control" value="{{ old('tgl_kadaluarsa') }}"></div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('pengumuman.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection