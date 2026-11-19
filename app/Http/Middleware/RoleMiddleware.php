<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah user sudah login
        // 2. Bandingkan role user dengan role yang diizinkan (diubah ke lowercase agar aman)
        if (Auth::check() && strtolower(Auth::user()->role) === strtolower($role)) {
            return $next($request);
        }

        // Jika tidak sesuai, hentikan dan tampilkan pesan error 403
        abort(403, 'Stop! Anda tidak memiliki hak akses ke halaman ini.');
    }
}