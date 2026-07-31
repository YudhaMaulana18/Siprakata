@extends('layouts.App')
@section('title','Edit User') @section('page-title','Edit User & Role')
@section('content')
<div class="card" style="max-width:540px">
    <div class="card-header"><i class="bi bi-pencil-fill me-2"></i>Edit User: <strong>{{ $user->name }}</strong></div>
    <div class="card-body">
        <form action="{{ route('user-roles.update',$user->id) }}" method="POST">@csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control bg-light" value="{{ $user->name }}" disabled>
                </div>
                <div class="col-12">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control bg-light" value="{{ $user->email }}" disabled>
                </div>
                <div class="col-12">
                    <label class="form-label">Role</label>
                    <select name="role_id" class="form-select" required>
                        @foreach($roles as $r)
                        <option value="{{ $r->id }}" {{ $user->role_id==$r->id?'selected':'' }}>
                            {{ $r->display_name }} ({{ $r->name }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <hr>
                    <label class="form-label">Ganti Password <span class="text-muted fw-normal">(kosongkan jika tidak diganti)</span></label>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('user-roles.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection