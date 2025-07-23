<x-guest-layout>
    <div class="login-card">
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="text-center mb-4">
            <h2 class="text-2xl fw-bold text-gray-800">Login Admin</h2>
        </div>

        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf

            <div class="mb-3 text-start">
                <x-input-label for="login" :value="__('Username / Email')" />
                <x-text-input id="login" class="form-control mt-1" type="text" name="login" :value="old('login')"
                    required autofocus autocomplete="login" />
                <x-input-error :messages="$errors->get('login')" class="mt-2" />
            </div>

            <div class="mb-3 text-start">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="form-control mt-1" type="password" name="password" required
                    autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mb-3 form-check text-start">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">{{ __('Ingat saya') }}</label>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                @if (Route::has('password.request'))
                    <a class="text-decoration-underline text-sm text-muted" href="{{ route('password.request') }}">
                        {{ __('Lupa Password?') }}
                    </a>
                @endif
                <button type="submit" class="btn btn-primary">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>
    </div>

    <div class="info-box">
        <h6><i class="bi bi-info-circle-fill me-1"></i> Informasi</h6>
        <ul class="mb-0 ps-3">
            <li>Hanya admin yang memiliki akses ke halaman ini.</li>
            <li>Gunakan username/email dan password yang valid.</li>
            <li>Laporkan ke developer jika terjadi kesalahan sistem.</li>
        </ul>
    </div>
</x-guest-layout>
