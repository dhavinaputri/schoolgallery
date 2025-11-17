<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $admin = Auth::guard('admin')->user();
        
        // Super admin can access everything
        if ($admin->role === 'super_admin') {
            return $next($request);
        }

        // Check if admin has required role
        if (!in_array($admin->role, $roles)) {
            // Jangan tampilkan halaman 403 di admin biasa; alihkan diam-diam ke dashboard
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
