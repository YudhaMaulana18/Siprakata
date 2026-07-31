@extends('layouts.App')
@section('title', 'Edit Presensi')
@section('page-title', 'Edit Presensi')

@section('content')
<div class="card" style="max-width:680px">
    <div class="card-header"><i class="bi bi-pencil-fill me-2"></i>Form Edit Presensi</div>
    <div class="card-body">
        <form action="{{ route('presensi.update', $presensi->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Jadwal / Mata Kuliah</label>
                    <select name="jadwal_id" class="form-select" required>
                        @foreach($jadwal as $j)
                        <option value="{{ $j->id }}" {{ $presensi->jadwal_id == $j->id ? 'selected' : '' }}>
                            {{ $j->matakuliah->nama_mk }} – {{ $j->hari }} {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mahasiswa</label>
                    <select name="mahasiswa_id" class="form-select" required>
                        @foreach($mahasiswa as $m)
                        <option value="{{ $m->id }}" {{ $presensi->mahasiswa_id == $m->id ? 'selected' : '' }}>
                            {{ $m->NIM }} – {{ $m->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $presensi->tanggal }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pertemuan Ke-</label>
                    <input type="number" name="pertemuan_ke" class="form-control" value="{{ $presensi->pertemuan_ke }}" min="1" max="16" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status Kehadiran</label>
                    <select name="status_hadir" class="form-select" required>
                        @foreach(['hadir','izin','sakit','alpha'] as $s)
                        <option value="{{ $s }}" {{ $presensi->status_hadir == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" value="{{ $presensi->keterangan }}">
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('presensi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection