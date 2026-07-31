<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller {
    public function index() {
        $roles = Role::withCount('permissions')->with('permissions')->get();
        return view('hak_akses.roles.index', compact('roles'));
    }

    public function create() {
        $permissions = Permission::orderBy('module')->get()->groupBy('module');
        return view('hak_akses.roles.create', compact('permissions'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'         => 'required|string|unique:roles,name',
            'display_name' => 'required|string|max:100',
            'description'  => 'nullable|string',
        ]);
        $role = Role::create($request->only('name','display_name','description'));
        if ($request->permissions) {
            $role->permissions()->sync($request->permissions);
        }
        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(Role $role) {
        $permissions = Permission::orderBy('module')->get()->groupBy('module');
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('hak_akses.roles.edit', compact('role','permissions','rolePermissions'));
    }

    public function update(Request $request, Role $role) {
        $request->validate([
            'display_name' => 'required|string|max:100',
            'description'  => 'nullable|string',
        ]);
        $role->update($request->only('display_name','description'));
        $role->permissions()->sync($request->permissions ?? []);
        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role) {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }
}