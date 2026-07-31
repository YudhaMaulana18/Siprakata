@extends('layouts.App')

@section('title', 'Validasi KRS')
@section('page-title', 'Validasi KRS oleh Dosen')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-shield-check me-2"></i>Form Validasi KRS
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="140">Mahasiswa</td>
                                <td><strong>{{ $krs->mahasiswa->nama }}</strong> ({{ $krs->mahasiswa->NIM }})</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Mata Kuliah</td>
                                <td>{{ $krs->matakuliah->nama_mk }} ({{ $krs->matakuliah->kode_mk }})</td>
                            </tr>
                            <tr>
                                <td class="text-muted">SKS</td>
                                <td><span class="badge bg-warning text-dark">{{ $krs->matakuliah->sks }} SKS</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="140">Dosen</td>
                                <td>{{ $krs->dosen->nama }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tahun Ajaran</td>
                                <td>{{ $krs->tahun_ajaran }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Semester</td>
                                <td>{{ $krs->semester }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <form method="POST" action="{{ route('krs.proses_validasi', $krs->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Keputusan Validasi</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_validasi"
                                       id="setuju" value="disetujui" required>
                                <label class="form-check-label text-success fw-bold" for="setuju">
                                    <i class="bi bi-check-circle me-1"></i>Setuju
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_validasi"
                                       id="tolak" value="ditolak" required>
                                <label class="form-check-label text-danger fw-bold" for="tolak">
                                    <i class="bi bi-x-circle me-1"></i>Tolak
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea name="catatan_validasi" class="form-control" rows="3"
                                  placeholder="Tulis catatan untuk mahasiswa...">{{ old('catatan_validasi') }}</textarea>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i>Proses Validasi
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
