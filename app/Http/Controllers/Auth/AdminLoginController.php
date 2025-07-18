<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login-admin');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();

        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            // Arahkan kembali ke halaman login admin yang benar
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Akun ini tidak memiliki akses sebagai admin.',
            ]);
        }

        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }
}
