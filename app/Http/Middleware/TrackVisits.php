<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hindari mencatat berulang dalam satu sesi terlalu sering
        $lastVisitAt = session('last_visit_at');
        $now = now();

        if (!$lastVisitAt || $now->diffInMinutes($lastVisitAt) >= 15) {
            DB::table('visits')->insert([
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            session(['last_visit_at' => $now]);
        }

        return $next($request);
    }
}


