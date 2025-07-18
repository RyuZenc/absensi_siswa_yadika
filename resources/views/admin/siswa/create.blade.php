<x-app-layout>
    @section('header', 'Tambah Siswa Baru')
    <form action="{{ route('admin.siswa.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="mt-1 block w-full rounded-md"
                    value="{{ old('nama_lengkap') }}" required>
                @error('nama_lengkap')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label>NIS</label>
                <input type="text" name="nis" class="mt-1 block w-full rounded-md" value="{{ old('nis') }}"
                    required>
                @error('nis')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label>Kelas</label>
                <select name="kelas_id" class="mt-1 block w-full rounded-md">
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
                @error('kelas_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" class="mt-1 block w-full rounded-md" value="{{ old('email') }}"
                    required>
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" class="mt-1 block w-full rounded-md" required>
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md" required>
            </div>
        </div>
        <div class="mt-2 flex justify-end gap-2">
            <a href="{{ route('admin.siswa.index') }}"
                class="btn btn-primary rounded-md py-2 px-6 text-base font-semibold">Batal</a>
            <button type="submit" class="btn btn-primary rounded-md py-2 px-6 text-base font-semibold">
                Simpan
            </button>
        </div>
    </form>
</x-app-layout>
