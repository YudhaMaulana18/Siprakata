@extends('layouts.App')

@section('title', 'Tambah KRS')
@section('page-title', 'Tambah KRS')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">

            <div class="card-header">
                <i class="bi bi-plus-square me-2"></i>Form Tambah KRS
            </div>

            <div class="card-body p-4">

                <!-- ✅ VALIDASI ERROR GLOBAL (dari code 1) -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('krs.store') }}">
                    @csrf

                    <!-- Mahasiswa -->
                    <div class="mb-3">
                        <label class="form-label">Mahasiswa</label>
                        <select name="mahasiswa_id"
                                class="form-select @error('mahasiswa_id') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach ($mahasiswa as $mhs)
                                <option value="{{ $mhs->id }}"
                                    {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                                    {{ $mhs->nama }} ({{ $mhs->NIM }})
                                </option>
                            @endforeach
                        </select>
                        @error('mahasiswa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Mata Kuliah -->
                    <div class="mb-3">
                        <label class="form-label">Mata Kuliah</label>
                        <select name="matakuliah_id"
                                class="form-select @error('matakuliah_id') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @foreach ($matakuliah as $mk)
                                <option value="{{ $mk->id }}"
                                    {{ old('matakuliah_id') == $mk->id ? 'selected' : '' }}>
                                    {{ $mk->nama_mk }} ({{ $mk->kode_mk }}) — {{ $mk->sks }} SKS
                                </option>
                            @endforeach
                        </select>
                        @error('matakuliah_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dosen -->
                    <div class="mb-3">
                        <label class="form-label">Dosen Pengampu</label>
                        <select name="dosen_id"
                                class="form-select @error('dosen_id') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Dosen --</option>
                            @foreach ($dosen as $d)
                                <option value="{{ $d->id }}"
                                    {{ old('dosen_id') == $d->id ? 'selected' : '' }}>
                                    {{ $d->nama }} ({{ $d->NIDN }})
                                </option>
                            @endforeach
                        </select>
                        @error('dosen_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tahun / Semester / Status -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran"
                                   class="form-control @error('tahun_ajaran') is-invalid @enderror"
                                   value="{{ old('tahun_ajaran', '2025/2026') }}"
                                   placeholder="2025/2026"
                                   required>
                            @error('tahun_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Semester</label>
                            <select name="semester"
                                    class="form-select @error('semester') is-invalid @enderror"
                                    required>
                                <option value="">-- Pilih --</option>
                                <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap"  {{ old('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required>
                                <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan
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