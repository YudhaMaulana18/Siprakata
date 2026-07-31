@extends('layouts.App')
@section('title','Edit Ruangan') @section('page-title','Edit Ruangan')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><i class="bi bi-pencil-fill me-2"></i>Edit Ruangan</div>
    <div class="card-body">
        <form action="{{ route('ruangan.update',$ruangan->id) }}" method="POST">@csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Kode Ruangan</label>
                    <input type="text" name="kode_ruangan" class="form-control" value="{{ $ruangan->kode_ruangan }}" required></div>
                <div class="col-md-8"><label class="form-label">Nama Ruangan</label>
                    <input type="text" name="nama_ruangan" class="form-control" value="{{ $ruangan->nama_ruangan }}" required></div>
                <div class="col-md-4"><label class="form-label">Jenis</label>
                    <select name="jenis" class="form-select" required>
                        @foreach(['Kelas','Laboratorium','Aula','Lainnya'] as $j)
                        <option value="{{ $j }}" {{ $ruangan->jenis==$j?'selected':'' }}>{{ $j }}</option>@endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label">Kapasitas</label>
                    <input type="number" name="kapasitas" class="form-control" value="{{ $ruangan->kapasitas }}" min="1" required></div>
                <div class="col-md-4"><label class="form-label">Gedung</label>
                    <input type="text" name="gedung" class="form-control" value="{{ $ruangan->gedung }}" required></div>
                <div class="col-md-4"><label class="form-label">Lantai</label>
                    <input type="text" name="lantai" class="form-control" value="{{ $ruangan->lantai }}" required></div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('ruangan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection