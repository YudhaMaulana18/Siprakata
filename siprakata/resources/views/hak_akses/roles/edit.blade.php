@extends('layouts.App')
@section('title','Edit Role') @section('page-title','Edit Role & Permission')
@section('content')
<div class="card" style="max-width:750px">
    <div class="card-header"><i class="bi bi-pencil-fill me-2"></i>Edit Role: <strong>{{ $role->display_name }}</strong></div>
    <div class="card-body">
        <form action="{{ route('roles.update',$role->id) }}" method="POST">@csrf @method('PUT')
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Nama Role</label>
                    <input type="text" class="form-control bg-light" value="{{ $role->name }}" disabled>
                    <small class="text-muted">Tidak bisa diubah</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Display Name</label>
                    <input type="text" name="display_name" class="form-control" value="{{ $role->display_name }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Deskripsi</label>
                    <input type="text" name="description" class="form-control" value="{{ $role->description }}">
                </div>
            </div>
            <label class="form-label fw-bold mb-2">Permissions</label>
            <div class="row g-3">
                @foreach($permissions as $module => $perms)
                <div class="col-md-3">
                    <div class="card border">
                        <div class="card-header py-2 d-flex justify-content-between align-items-center" style="background:#f8f9ff">
                            <strong style="font-size:12px;text-transform:capitalize">{{ $module }}</strong>
                            <a href="#" onclick="toggleModule('{{ $module }}'); return false;" style="font-size:11px">Semua</a>
                        </div>
                        <div class="card-body py-2">
                            @foreach($perms as $perm)
                            <div class="form-check">
                                <input class="form-check-input perm-{{ $module }}" type="checkbox"
                                    name="permissions[]" value="{{ $perm->id }}" id="p{{ $perm->id }}"
                                    {{ in_array($perm->id, $rolePermissions) ? 'checked' : '' }}>
                                <label class="form-check-label" for="p{{ $perm->id }}" style="font-size:12px">
                                    {{ ucfirst(explode('.',$perm->name)[1] ?? $perm->name) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<script>
function toggleModule(m) {
    const cb = document.querySelectorAll('.perm-'+m);
    const all = [...cb].every(c=>c.checked);
    cb.forEach(c=>c.checked=!all);
}
</script>
@endsection