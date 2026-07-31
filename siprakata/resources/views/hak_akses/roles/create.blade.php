@extends('layouts.App')
@section('title','Tambah Role') @section('page-title','Tambah Role')
@section('content')
<div class="card" style="max-width:750px">
    <div class="card-header"><i class="bi bi-plus-circle-fill me-2"></i>Form Tambah Role</div>
    <div class="card-body">
        <form action="{{ route('roles.store') }}" method="POST">@csrf
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Nama Role <small class="text-muted">(lowercase)</small></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="cth: operator" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Display Name</label>
                    <input type="text" name="display_name" class="form-control" value="{{ old('display_name') }}" placeholder="cth: Operator" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Deskripsi</label>
                    <input type="text" name="description" class="form-control" value="{{ old('description') }}">
                </div>
            </div>
            <label class="form-label fw-bold mb-2">Assign Permissions</label>
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
                                <input class="form-check-input perm-{{ $module }}" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="p{{ $perm->id }}"
                                    {{ old('permissions') && in_array($perm->id, old('permissions',[])) ? 'checked' : '' }}>
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
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
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