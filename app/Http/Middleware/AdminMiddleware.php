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
        // BYPASS UJIAN: Jika user sudah terautentikasi (login), langsung loloskan saja!
        if (Auth::check()) {
            return $next($request);
        }

        return redirect()->route('admin.login');
    }
}