<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsWaliKelas
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->guru && Auth::user()->guru->role === 'wali_kelas') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Akses hanya untuk Wali Kelas.');
    }
}
