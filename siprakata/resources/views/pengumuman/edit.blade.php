@extends('layouts.App')
@section('title','Edit Pengumuman') @section('page-title','Edit Pengumuman')
@section('content')
<div class="card" style="max-width:680px">
    <div class="card-header"><i class="bi bi-pencil-fill me-2"></i>Edit Pengumuman</div>
    <div class="card-body">
        <form action="{{ route('pengumuman.update',$pengumuman->id) }}" method="POST">@csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Dosen</label>
                    <select name="dosen_id" class="form-select" required>
                        @foreach($dosen as $d)<option value="{{ $d->id }}" {{ $pengumuman->dosen_id==$d->id?'selected':'' }}>{{ $d->nama }}</option>@endforeach
                    </select></div>
                <div class="col-md-6"><label class="form-label">Kelas / Jadwal</label>
                    <select name="jadwal_id" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($jadwal as $j)<option value="{{ $j->id }}" {{ $pengumuman->jadwal_id==$j->id?'selected':'' }}>{{ $j->matakuliah->nama_mk }} – {{ $j->hari }}</option>@endforeach
                    </select></div>
                <div class="col-12"><label class="form-label">Judul</label>
                    <input type="text" name="judul" class="form-control" value="{{ $pengumuman->judul }}" required></div>
                <div class="col-12"><label class="form-label">Isi</label>
                    <textarea name="isi" class="form-control" rows="4" required>{{ $pengumuman->isi }}</textarea></div>
                <div class="col-md-4"><label class="form-label">Prioritas</label>
                    <select name="prioritas" class="form-select" required>
                        @foreach(['rendah','sedang','tinggi'] as $pr)<option value="{{ $pr }}" {{ $pengumuman->prioritas==$pr?'selected':'' }}>{{ ucfirst($pr) }}</option>@endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label">Tanggal Posting</label>
                    <input type="date" name="tgl_posting" class="form-control" value="{{ $pengumuman->tgl_posting?->format('Y-m-d') }}" required></div>
                <div class="col-md-4"><label class="form-label">Kadaluarsa</label>
                    <input type="date" name="tgl_kadaluarsa" class="form-control" value="{{ $pengumuman->tgl_kadaluarsa?->format('Y-m-d') }}"></div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('pengumuman.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection