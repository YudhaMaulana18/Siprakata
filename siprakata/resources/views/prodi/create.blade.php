@extends('layouts.App')
@section('title','Tambah Prodi')
@section('page-title','Tambah Program Studi')
@section('content')
<div class="card" style="max-width:560px">
    <div class="card-header"><i class="bi bi-plus-circle-fill me-2"></i>Form Program Studi</div>
    <div class="card-body">
        <form action="{{ route('prodi.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kode Prodi</label>
                    <input type="text" name="kode_prodi" class="form-control" value="{{ old('kode_prodi') }}" placeholder="INF" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Nama Program Studi</label>
                    <input type="text" name="nama_prodi" class="form-control" value="{{ old('nama_prodi') }}" placeholder="Informatika" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenjang</label>
                    <select name="jenjang" class="form-select" required>
                        @foreach(['D3','S1','S2','S3'] as $j)
                        <option value="{{ $j }}" {{ old('jenjang','S1')==$j?'selected':'' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Fakultas</label>
                    <input type="text" name="fakultas" class="form-control" value="{{ old('fakultas') }}" placeholder="Fakultas Teknik" required>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('prodi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection