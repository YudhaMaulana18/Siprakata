@extends('layouts.App')
@section('title','Edit Dosen') @section('page-title','Edit Dosen')
@section('content')
<div class="card" style="max-width:640px">
    <div class="card-header"><i class="bi bi-pencil-fill me-2"></i>Form Edit Dosen</div>
    <div class="card-body">
        <form action="{{ route('dosen.update',$dosen->id) }}" method="POST">@csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-5"><label class="form-label">NIDN</label>
                    <input type="text" name="NIDN" class="form-control" value="{{ $dosen->NIDN }}" required></div>
                <div class="col-md-7"><label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ $dosen->nama }}" required></div>
                <div class="col-md-6"><label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $dosen->email }}" required></div>
                <div class="col-md-6"><label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ $dosen->no_hp }}"></div>
                <div class="col-md-6"><label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control" value="{{ $dosen->jabatan }}"></div>
                <div class="col-md-6"><label class="form-label">Program Studi</label>
                    <select name="prodi_id" class="form-select">
                        <option value="">-- Pilih Prodi --</option>
                        @foreach($prodi as $p)<option value="{{ $p->id }}" {{ $dosen->prodi_id==$p->id?'selected':'' }}>{{ $p->nama_prodi }}</option>@endforeach
                    </select></div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection