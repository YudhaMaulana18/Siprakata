@extends('layouts.App')
@section('title', 'Catat Presensi')
@section('page-title', 'Catat Presensi')

@section('content')
<div class="card" style="max-width:680px">
    <div class="card-header"><i class="bi bi-clipboard2-plus-fill me-2"></i>Form Presensi</div>
    <div class="card-body">
        <form action="{{ route('presensi.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Jadwal / Mata Kuliah</label>
                    <select name="jadwal_id" class="form-select" required>
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach($jadwal as $j)
                        <option value="{{ $j->id }}" {{ old('jadwal_id') == $j->id ? 'selected' : '' }}>
                            {{ $j->matakuliah->nama_mk }} – {{ $j->hari }} {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mahasiswa</label>
                    <select name="mahasiswa_id" class="form-select" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswa as $m)
                        <option value="{{ $m->id }}" {{ old('mahasiswa_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->NIM }} – {{ $m->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pertemuan Ke-</label>
                    <input type="number" name="pertemuan_ke" class="form-control" value="{{ old('pertemuan_ke',1) }}" min="1" max="16" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status Kehadiran</label>
                    <select name="status_hadir" class="form-select" required>
                        <option value="hadir" {{ old('status_hadir')=='hadir'?'selected':'' }}>Hadir</option>
                        <option value="izin" {{ old('status_hadir')=='izin'?'selected':'' }}>Izin</option>
                        <option value="sakit" {{ old('status_hadir')=='sakit'?'selected':'' }}>Sakit</option>
                        <option value="alpha" {{ old('status_hadir')=='alpha'?'selected':'' }}>Alpha</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Keterangan <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}" placeholder="cth: sakit demam">
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('presensi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection