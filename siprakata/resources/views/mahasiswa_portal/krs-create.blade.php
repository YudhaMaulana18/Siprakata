@extends('layouts.Mahasiswa')
@section('title', 'Ajukan KRS')
@section('page-title', 'Ajukan KRS Baru')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Form Pengajuan KRS</div>
    <div class="card-body">
        <form action="{{ route('mhs.krs.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Mata Kuliah</label>
                <select name="matakuliah_id" class="form-select" required>
                    <option value="">Pilih Mata Kuliah</option>
                    @foreach($matakuliah as $mk)
                    <option value="{{ $mk->id }}">{{ $mk->kode_mk }} - {{ $mk->nama_mk }} ({{ $mk->sks }} SKS)</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Dosen Pengampu</label>
                <select name="dosen_id" class="form-select" required>
                    <option value="">Pilih Dosen</option>
                    @foreach($dosen as $d)
                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Ajukan</button>
                <a href="{{ route('mhs.krs') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
