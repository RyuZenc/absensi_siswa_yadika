<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SiswaLoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login-siswa');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();

        if (Auth::user()->role !== 'siswa') {
            Auth::logout();

            return redirect()->route('siswa.login')->withErrors([
                'login' => 'Akun ini tidak memiliki akses sebagai siswa.',
            ]);
        }

        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }
}
