<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsGuru
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role == 'guru' && Auth::user()->guru) {
            return $next($request);
        }

        if (Auth::check() && Auth::user()->role == 'guru' && !Auth::user()->guru) {
            return redirect('/')->with('error', 'Data guru tidak ditemukan. Silakan hubungi administrator.');
        }

        return redirect('/')->with('error', 'Akses Ditolak. Anda Bukan Guru.');
    }
}
