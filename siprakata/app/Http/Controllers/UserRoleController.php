<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRoleController extends Controller {
    public function index() {
        $users = User::with('role')->get();
        return view('hak_akses.users.index', compact('users'));
    }

    public function create() {
        $roles = Role::all();
        return view('hak_akses.users.create', compact('roles'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role_id'  => 'required|exists:roles,id',
        ]);
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id,
        ]);
        return redirect()->route('user-roles.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user) {
        $roles = Role::all();
        return view('hak_akses.users.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'role_id'  => 'required|exists:roles,id',
            'password' => 'nullable|min:6|confirmed',
        ]);
        $data = ['role_id' => $request->role_id];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return redirect()->route('user-roles.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user) {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        $user->delete();
        return redirect()->route('user-roles.index')->with('success', 'User berhasil dihapus.');
    }
}