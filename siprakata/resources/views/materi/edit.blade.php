@extends('layouts.App')
@section('title','Edit Materi') @section('page-title','Edit Materi Kuliah')
@section('content')
<div class="card" style="max-width:640px">
    <div class="card-header"><i class="bi bi-pencil-fill me-2"></i>Edit Materi</div>
    <div class="card-body">
        <form action="{{ route('materi.update',$materi->id) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-8"><label class="form-label">Jadwal / Mata Kuliah</label>
                    <select name="jadwal_id" class="form-select" required>
                        @foreach($jadwal as $j)
                        <option value="{{ $j->id }}" {{ $materi->jadwal_id==$j->id?'selected':'' }}>
                            {{ $j->matakuliah->nama_mk }} – {{ $j->hari }} {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}
                        </option>@endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label">Pertemuan Ke-</label>
                    <input type="number" name="pertemuan_ke" class="form-control" value="{{ $materi->pertemuan_ke }}" min="1" max="16" required></div>
                <div class="col-12"><label class="form-label">Judul Materi</label>
                    <input type="text" name="judul" class="form-control" value="{{ $materi->judul }}" required></div>
                <div class="col-12"><label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ $materi->deskripsi }}</textarea></div>
                <div class="col-md-6"><label class="form-label">Ganti File <span class="text-muted fw-normal">(kosongkan jika tidak diganti)</span></label>
                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip">
                    @if($materi->file_path)<small class="text-muted">File saat ini: {{ basename($materi->file_path) }}</small>@endif</div>
                <div class="col-md-6"><label class="form-label">Link Materi</label>
                    <input type="url" name="link_materi" class="form-control" value="{{ $materi->link_materi }}"></div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('materi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection