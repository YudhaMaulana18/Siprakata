@extends('layouts.App')
@section('title', 'Analisis Prediksi Kelulusan')
@section('page-title', 'Analisis Prediksi Kelulusan Mata Kuliah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-calculator me-2"></i>Form Analisis Prediksi Kelulusan
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-1"></i>
                    Sistem akan menghitung prediksi kelulusan menggunakan <strong>Logika Fuzzy Mamdani</strong> berdasarkan:
                    <ul class="mb-0 mt-1">
                        <li><strong>Kehadiran</strong> (0%–100%) — Rendah, Sedang, Tinggi</li>
                        <li><strong>Nilai Tugas</strong> (0–100) — Rendah, Sedang, Tinggi</li>
                        <li><strong>Keaktifan Diskusi</strong> (0–100) — Rendah, Sedang, Tinggi</li>
                    </ul>
                </div>

                <form method="POST" action="{{ route('kelayakan.proses') }}" id="formPrediksi">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Mahasiswa</label>
                        <select name="mahasiswa_id" id="mahasiswa_id" class="form-select @error('mahasiswa_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach ($mahasiswa as $mhs)
                                <option value="{{ $mhs->id }}" {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                                    {{ $mhs->nama }} ({{ $mhs->NIM }})
                                </option>
                            @endforeach
                        </select>
                        @error('mahasiswa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mata Kuliah</label>
                        <select name="matakuliah_id" id="matakuliah_id" class="form-select @error('matakuliah_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Mahasiswa Terlebih Dahulu --</option>
                        </select>
                        @error('matakuliah_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" class="form-control"
                               value="{{ old('tahun_ajaran', '2025/2026') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select" required>
                            <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-calculator me-1"></i>Hitung Prediksi
                        </button>
                        <a href="{{ route('kelayakan.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@php
    $matakuliahByMhsJson = json_encode($matakuliahByMhs);
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {
    const matakuliahByMhs = {!! $matakuliahByMhsJson !!};
    const mahasiswaSelect = document.getElementById('mahasiswa_id');
    const matakuliahSelect = document.getElementById('matakuliah_id');

    mahasiswaSelect.addEventListener('change', function () {
        const mhsId = this.value;
        matakuliahSelect.innerHTML = '<option value="">-- Memuat... --</option>';

        if (!mhsId || !matakuliahByMhs[mhsId]) {
            matakuliahSelect.innerHTML = '<option value="">-- Tidak ada mata kuliah --</option>';
            return;
        }

        const mkList = matakuliahByMhs[mhsId];
        let html = '<option value="">-- Pilih Mata Kuliah --</option>';
        mkList.forEach(function (mk) {
            html += '<option value="' + mk.id + '">' + mk.nama_mk + ' (' + mk.sks + ' SKS)</option>';
        });
        matakuliahSelect.innerHTML = html;
    });

    if (mahasiswaSelect.value) {
        mahasiswaSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
