<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminGuest
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Selalu izinkan akses ke halaman login admin,
        // bahkan jika sudah terotentikasi sebagai admin.
        // Ini memenuhi kebutuhan untuk selalu menampilkan form login terlebih dahulu.
        return $next($request);
    }
}
