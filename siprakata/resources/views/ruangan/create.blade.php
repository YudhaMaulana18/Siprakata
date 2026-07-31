@extends('layouts.App')
@section('title','Tambah Ruangan') @section('page-title','Tambah Ruangan')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><i class="bi bi-plus-circle-fill me-2"></i>Form Ruangan</div>
    <div class="card-body">
        <form action="{{ route('ruangan.store') }}" method="POST">@csrf
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Kode Ruangan</label>
                    <input type="text" name="kode_ruangan" class="form-control" value="{{ old('kode_ruangan') }}" placeholder="LAB-01" required></div>
                <div class="col-md-8"><label class="form-label">Nama Ruangan</label>
                    <input type="text" name="nama_ruangan" class="form-control" value="{{ old('nama_ruangan') }}" placeholder="Laboratorium Komputer 1" required></div>
                <div class="col-md-4"><label class="form-label">Jenis</label>
                    <select name="jenis" class="form-select" required>
                        @foreach(['Kelas','Laboratorium','Aula','Lainnya'] as $j)
                        <option value="{{ $j }}" {{ old('jenis')==$j?'selected':'' }}>{{ $j }}</option>@endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label">Kapasitas</label>
                    <input type="number" name="kapasitas" class="form-control" value="{{ old('kapasitas',30) }}" min="1" required></div>
                <div class="col-md-4"><label class="form-label">Gedung</label>
                    <input type="text" name="gedung" class="form-control" value="{{ old('gedung') }}" placeholder="Gedung A" required></div>
                <div class="col-md-4"><label class="form-label">Lantai</label>
                    <input type="text" name="lantai" class="form-control" value="{{ old('lantai') }}" placeholder="1" required></div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('ruangan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection