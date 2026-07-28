<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN role-nya BUKAN customer
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'superadmin', 'organizer'])) {
            return $next($request);
        }

        // Jika Customer nekat mengakses dashboard admin, tendang ke halaman utama
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman Dashboard Admin.');
    }
}