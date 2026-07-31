@extends('layouts.App')
@section('title','Edit Tahun Ajaran') @section('page-title','Edit Tahun Ajaran')
@section('content')
<div class="card" style="max-width:560px">
    <div class="card-header"><i class="bi bi-pencil-fill me-2"></i>Edit Tahun Ajaran</div>
    <div class="card-body">
        <form action="{{ route('tahun_ajaran.update',$tahun_ajaran->id) }}" method="POST">@csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Tahun</label>
                    <input type="text" name="tahun" class="form-control" value="{{ $tahun_ajaran->tahun }}" required></div>
                <div class="col-md-6"><label class="form-label">Semester</label>
                    <select name="semester" class="form-select" required>
                        <option value="Ganjil" {{ $tahun_ajaran->semester=='Ganjil'?'selected':'' }}>Ganjil</option>
                        <option value="Genap"  {{ $tahun_ajaran->semester=='Genap'?'selected':'' }}>Genap</option>
                    </select></div>
                <div class="col-md-6"><label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" class="form-control" value="{{ $tahun_ajaran->tgl_mulai?->format('Y-m-d') }}" required></div>
                <div class="col-md-6"><label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" class="form-control" value="{{ $tahun_ajaran->tgl_selesai?->format('Y-m-d') }}" required></div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status_aktif" id="status_aktif" value="1" {{ $tahun_ajaran->status_aktif?'checked':'' }}>
                        <label class="form-check-label" for="status_aktif">Jadikan tahun ajaran aktif</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('tahun_ajaran.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection