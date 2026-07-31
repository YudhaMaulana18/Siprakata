@extends('layouts.App')

@section('title', 'Edit KRS')
@section('page-title', 'Edit KRS')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-square me-2"></i>Form Edit KRS
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('krs.update', $krs->id) }}">
                    @csrf 
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Mahasiswa</label>
                        <select name="mahasiswa_id" class="form-select" required>
                            @foreach ($mahasiswa as $mhs)
                                <option value="{{ $mhs->id }}"
                                    {{ old('mahasiswa_id', $krs->mahasiswa_id) == $mhs->id ? 'selected' : '' }}>
                                    {{ $mhs->nama }} ({{ $mhs->NIM }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mata Kuliah</label>
                        <select name="matakuliah_id" class="form-select" required>
                            @foreach ($matakuliah as $mk)
                                <option value="{{ $mk->id }}"
                                    {{ old('matakuliah_id', $krs->matakuliah_id) == $mk->id ? 'selected' : '' }}>
                                    {{ $mk->nama_mk }} ({{ $mk->kode_mk }}) — {{ $mk->sks }} SKS
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dosen Pengampu</label>
                        <select name="dosen_id" class="form-select" required>
                            @foreach ($dosen as $d)
                                <option value="{{ $d->id }}"
                                    {{ old('dosen_id', $krs->dosen_id) == $d->id ? 'selected' : '' }}>
                                    {{ $d->nama }} ({{ $d->NIDN }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control"
                                   value="{{ old('tahun_ajaran', $krs->tahun_ajaran) }}" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="Ganjil" {{ old('semester', $krs->semester) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap"  {{ old('semester', $krs->semester) == 'Genap'  ? 'selected' : '' }}>Genap</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="aktif"   {{ old('status', $krs->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="selesai" {{ old('status', $krs->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Update
                        </button>
                        <a href="{{ route('krs.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection