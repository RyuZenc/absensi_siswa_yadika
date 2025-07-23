<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GuruLoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login-guru');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();

        if (Auth::user()->role !== 'guru') {
            Auth::logout();
            // Arahkan kembali ke halaman login guru yang benar
            return redirect()->route('guru.login')->withErrors([
                'email' => 'Akun ini tidak memiliki akses sebagai guru.',
            ]);
        }

        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }
}
