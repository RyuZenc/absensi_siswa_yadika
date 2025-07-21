<x-guest-layout>
    <div class="login-card">
        <div class="mb-4 text-sm text-gray-600">
            {{ __('Silakan masukkan alamat email Anda untuk mereset password akun Anda.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3 text-start">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="form-control mt-1" type="email" name="email" :value="old('email')"
                    required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="d-flex justify-content-end">
                <button class="btn btn-primary" type="submit">
                    {{ __('Kirim Link Reset Password') }}
                </button>
            </div>
        </form>
    </div>

    <div class="info-box">
        <h6><i class="bi bi-info-circle-fill me-1"></i> Informasi</h6>
        <ul class="mb-0 ps-3">
            <li>Pastikan alamat email yang dimasukkan valid dan terdaftar.</li>
            <li>Cek folder spam jika tidak menerima email reset dalam beberapa menit.</li>
            <li>Hubungi admin jika kamu butuh bantuan lebih lanjut.</li>
        </ul>
    </div>
</x-guest-layout>
