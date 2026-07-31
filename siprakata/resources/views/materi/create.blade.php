@extends('layouts.App')
@section('title','Upload Materi') @section('page-title','Upload Materi Kuliah')
@section('content')
<div class="card" style="max-width:640px">
    <div class="card-header"><i class="bi bi-upload me-2"></i>Form Upload Materi</div>
    <div class="card-body">
        <form action="{{ route('materi.store') }}" method="POST" enctype="multipart/form-data">@csrf
            <div class="row g-3">
                <div class="col-md-8"><label class="form-label">Jadwal / Mata Kuliah</label>
                    <select name="jadwal_id" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @foreach($jadwal as $j)
                        <option value="{{ $j->id }}" {{ old('jadwal_id')==$j->id?'selected':'' }}>
                            {{ $j->matakuliah->nama_mk }} – {{ $j->hari }} {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}
                        </option>@endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label">Pertemuan Ke-</label>
                    <input type="number" name="pertemuan_ke" class="form-control" value="{{ old('pertemuan_ke',1) }}" min="1" max="16" required></div>
                <div class="col-12"><label class="form-label">Judul Materi</label>
                    <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" placeholder="cth: Pengantar MVC" required></div>
                <div class="col-12"><label class="form-label">Deskripsi <span class="text-muted fw-normal">(opsional)</span></label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea></div>
                <div class="col-md-6"><label class="form-label">Upload File <span class="text-muted fw-normal">(pdf/doc/ppt/zip, max 10MB)</span></label>
                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip"></div>
                <div class="col-md-6"><label class="form-label">Link Materi <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="url" name="link_materi" class="form-control" value="{{ old('link_materi') }}" placeholder="https://drive.google.com/..."></div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('materi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection