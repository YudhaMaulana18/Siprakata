@extends('layouts.App')
@section('title','Tambah Mahasiswa')
@section('page-title','Tambah Mahasiswa')
@section('content')
<div class="card" style="max-width:680px">
    <div class="card-header"><i class="bi bi-person-plus-fill me-2"></i>Form Tambah Mahasiswa</div>
    <div class="card-body">
        <form action="{{ route('store-mahasiswa') }}" method="POST">@csrf
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">NIM</label>
                    <input type="text" name="NIM" class="form-control" value="{{ old('NIM') }}" required>
                </div>
                <div class="col-md-7">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Program Studi</label>
                    <select name="prodi_id" class="form-select">
                        <option value="">-- Pilih Prodi --</option>
                        @foreach($prodi as $p)
                        <option value="{{ $p->id }}" {{ old('prodi_id')==$p->id?'selected':'' }}>{{ $p->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Angkatan</label>
                    <input type="number" name="angkatan" class="form-control" value="{{ old('angkatan', date('Y')) }}" min="2000" max="2099">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach(['aktif','cuti','lulus','keluar'] as $s)
                        <option value="{{ $s }}" {{ old('status','aktif')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('data-mahasiswa') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection