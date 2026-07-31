@extends('layouts.Mahasiswa')
@section('title', 'Prediksi Baru')
@section('page-title', 'Prediksi Kelayakan Baru')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Form Prediksi Kelayakan</div>
    <div class="card-body">
        <form action="{{ route('mhs.kelayakan.proses') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Mata Kuliah</label>
                <select name="matakuliah_id" class="form-select" required>
                    <option value="">Pilih Mata Kuliah</option>
                    @foreach($matakuliah as $mk)
                    <option value="{{ $mk->id }}">{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">IPK</label>
                    <input type="number" step="0.01" name="ipk" class="form-control" placeholder="0.00 - 4.00" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">IPS</label>
                    <input type="number" step="0.01" name="ips" class="form-control" placeholder="0.00 - 4.00" required>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Jumlah SKS</label>
                    <input type="number" name="jumlah_sks" class="form-control" placeholder="Contoh: 24" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kehadiran (%)</label>
                    <input type="number" name="kehadiran" class="form-control" placeholder="0 - 100" required>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-calculator me-1"></i>Proses</button>
                <a href="{{ route('mhs.kelayakan') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
