<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission {
    public function handle(Request $request, Closure $next, string $permission): Response {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!auth()->user()->hasPermission($permission)) {
            abort(403, 'Anda tidak memiliki izin: ' . $permission);
        }

        return $next($request);
    }
}