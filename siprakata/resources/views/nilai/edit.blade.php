@extends('layouts.App')
@section('title', 'Edit Nilai')
@section('page-title', 'Edit Nilai Mahasiswa')

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><i class="bi bi-pencil-fill me-2"></i>Form Edit Nilai</div>
    <div class="card-body">
        <div class="alert alert-light border mb-4 py-2">
            <strong>{{ $nilai->krs->mahasiswa->nama }}</strong> ({{ $nilai->krs->mahasiswa->NIM }})<br>
            <span class="text-muted">{{ $nilai->krs->matakuliah->nama_mk }} – {{ $nilai->krs->semester }} {{ $nilai->krs->tahun_ajaran }}</span>
        </div>
        <form action="{{ route('nilai.update', $nilai->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nilai Tugas <span class="text-muted fw-normal">(30%)</span></label>
                    <input type="number" name="nilai_tugas" class="form-control" value="{{ $nilai->nilai_tugas }}" min="0" max="100" step="0.01" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nilai UTS <span class="text-muted fw-normal">(30%)</span></label>
                    <input type="number" name="nilai_uts" class="form-control" value="{{ $nilai->nilai_uts }}" min="0" max="100" step="0.01" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nilai UAS <span class="text-muted fw-normal">(40%)</span></label>
                    <input type="number" name="nilai_uas" class="form-control" value="{{ $nilai->nilai_uas }}" min="0" max="100" step="0.01" required>
                </div>
            </div>
            <div class="mt-3 p-3 bg-light rounded">
                <small class="text-muted">Nilai akhir saat ini: <strong>{{ number_format($nilai->nilai_akhir, 2) }}</strong> | Grade: <strong>{{ $nilai->grade }}</strong></small>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('nilai.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection