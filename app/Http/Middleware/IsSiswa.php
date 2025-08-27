<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsSiswa
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role == 'siswa' && Auth::user()->siswa) {
            return $next($request);
        }

        if (Auth::check() && Auth::user()->role == 'siswa' && !Auth::user()->siswa) {
            return redirect('/')->with('error', 'Data siswa tidak ditemukan. Silakan hubungi administrator.');
        }

        return redirect('/')->with('error', 'Akses Ditolak. Anda Bukan Siswa.');
    }
}
