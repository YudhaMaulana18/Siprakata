@extends('layouts.App')
@section('title','Edit Prodi')
@section('page-title','Edit Program Studi')
@section('content')
<div class="card" style="max-width:560px">
    <div class="card-header"><i class="bi bi-pencil-fill me-2"></i>Form Edit Program Studi</div>
    <div class="card-body">
        <form action="{{ route('prodi.update',$prodi->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kode Prodi</label>
                    <input type="text" name="kode_prodi" class="form-control" value="{{ $prodi->kode_prodi }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Nama Program Studi</label>
                    <input type="text" name="nama_prodi" class="form-control" value="{{ $prodi->nama_prodi }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenjang</label>
                    <select name="jenjang" class="form-select" required>
                        @foreach(['D3','S1','S2','S3'] as $j)
                        <option value="{{ $j }}" {{ $prodi->jenjang==$j?'selected':'' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Fakultas</label>
                    <input type="text" name="fakultas" class="form-control" value="{{ $prodi->fakultas }}" required>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('prodi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection