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
                <label class="block text-sm font-medium text-gray-700">Kelas</label>
                <select name="kelas_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->tingkat . ' - ' . $k->nama_kelas }}
                        </option>
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
                class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-md text-base transition">
                Batal
            </a>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md text-base transition">
                Simpan
            </button>
        </div>
    </form>
</x-app-layout>
