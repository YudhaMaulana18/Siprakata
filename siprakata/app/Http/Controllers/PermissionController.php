<?php
namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller {
    public function index() {
        $permissions = Permission::orderBy('module')->get()->groupBy('module');
        return view('hak_akses.permissions.index', compact('permissions'));
    }
}