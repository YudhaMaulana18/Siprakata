@extends('layouts.App')
@section('title', 'Input Nilai')
@section('page-title', 'Input Nilai Mahasiswa')

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><i class="bi bi-plus-circle-fill me-2"></i>Form Input Nilai</div>
    <div class="card-body">
        <form action="{{ route('nilai.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Mahasiswa / Mata Kuliah (KRS)</label>
                <select name="krs_id" class="form-select" required>
                    <option value="">-- Pilih KRS --</option>
                    @foreach($krs as $k)
                    <option value="{{ $k->id }}" {{ old('krs_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->mahasiswa->NIM }} – {{ $k->mahasiswa->nama }} | {{ $k->matakuliah->nama_mk }} ({{ $k->semester }} {{ $k->tahun_ajaran }})
                    </option>
                    @endforeach
                </select>
                @if($krs->isEmpty())
                    <small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>Semua KRS sudah memiliki nilai.</small>
                @endif
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nilai Tugas <span class="text-muted fw-normal">(30%)</span></label>
                    <input type="number" name="nilai_tugas" class="form-control" value="{{ old('nilai_tugas',0) }}" min="0" max="100" step="0.01" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nilai UTS <span class="text-muted fw-normal">(30%)</span></label>
                    <input type="number" name="nilai_uts" class="form-control" value="{{ old('nilai_uts',0) }}" min="0" max="100" step="0.01" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nilai UAS <span class="text-muted fw-normal">(40%)</span></label>
                    <input type="number" name="nilai_uas" class="form-control" value="{{ old('nilai_uas',0) }}" min="0" max="100" step="0.01" required>
                </div>
            </div>
            <div class="alert alert-info mt-3 py-2 d-flex align-items-center gap-2" style="font-size:0.85rem">
                <i class="bi bi-info-circle-fill"></i>
                Nilai akhir dan grade dihitung otomatis oleh sistem.
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('nilai.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection