@extends('layouts.App')
@section('title','Tambah Tahun Ajaran') @section('page-title','Tambah Tahun Ajaran')
@section('content')
<div class="card" style="max-width:560px">
    <div class="card-header"><i class="bi bi-plus-circle-fill me-2"></i>Form Tahun Ajaran</div>
    <div class="card-body">
        <form action="{{ route('tahun_ajaran.store') }}" method="POST">@csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Tahun</label>
                    <input type="text" name="tahun" class="form-control" value="{{ old('tahun','2025/2026') }}" placeholder="2025/2026" required></div>
                <div class="col-md-6"><label class="form-label">Semester</label>
                    <select name="semester" class="form-select" required>
                        <option value="Ganjil" {{ old('semester')=='Ganjil'?'selected':'' }}>Ganjil</option>
                        <option value="Genap"  {{ old('semester')=='Genap'?'selected':'' }}>Genap</option>
                    </select></div>
                <div class="col-md-6"><label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" class="form-control" value="{{ old('tgl_mulai') }}" required></div>
                <div class="col-md-6"><label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" class="form-control" value="{{ old('tgl_selesai') }}" required></div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status_aktif" id="status_aktif" value="1" {{ old('status_aktif')?'checked':'' }}>
                        <label class="form-check-label" for="status_aktif">Jadikan tahun ajaran aktif</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('tahun_ajaran.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection