@extends('layouts.App')
@section('title','Tambah Mata Kuliah') @section('page-title','Tambah Mata Kuliah')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><i class="bi bi-plus-circle-fill me-2"></i>Form Mata Kuliah</div>
    <div class="card-body">
        <form action="{{ route('matakuliah.store') }}" method="POST">@csrf
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Kode MK</label>
                    <input type="text" name="kode_mk" class="form-control" value="{{ old('kode_mk') }}" placeholder="INF201" required></div>
                <div class="col-md-8"><label class="form-label">Nama Mata Kuliah</label>
                    <input type="text" name="nama_mk" class="form-control" value="{{ old('nama_mk') }}" required></div>
                <div class="col-md-3"><label class="form-label">SKS</label>
                    <input type="number" name="sks" class="form-control" value="{{ old('sks',3) }}" min="1" max="6" required></div>
                <div class="col-md-4"><label class="form-label">Semester</label>
                    <select name="semester" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @for($i=1;$i<=8;$i++)<option value="{{ $i }}" {{ old('semester')==$i?'selected':'' }}>Semester {{ $i }}</option>@endfor
                    </select></div>
                <div class="col-md-5"><label class="form-label">Program Studi</label>
                    <select name="prodi_id" class="form-select">
                        <option value="">-- Pilih Prodi --</option>
                        @foreach($prodi as $p)<option value="{{ $p->id }}" {{ old('prodi_id')==$p->id?'selected':'' }}>{{ $p->nama_prodi }}</option>@endforeach
                    </select></div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('matakuliah.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection