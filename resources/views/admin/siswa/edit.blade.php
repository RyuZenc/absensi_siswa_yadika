<x-app-layout>
    @section('header', 'Edit Siswa')
    <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST">
        @csrf
        @method('PUT')
        <!-- Logika form mirip dengan create, tapi dengan value yang sudah ada -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="mt-1 block w-full rounded-md"
                    value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" required>
            </div>
            <div>
                <label>NIS</label>
                <input type="text" name="nis" class="mt-1 block w-full rounded-md"
                    value="{{ old('nis', $siswa->nis) }}" required>
            </div>
            <div>
                <label>Kelas</label>
                <select name="kelas_id" class="mt-1 block w-full rounded-md">
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" {{ $siswa->kelas_id == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" class="mt-1 block w-full rounded-md"
                    value="{{ old('email', $siswa->user->email) }}" required>
            </div>
            <div>
                <label>Password Baru (Opsional)</label>
                <input type="password" name="password" class="mt-1 block w-full rounded-md">
            </div>
            <div>
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md">
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">Update</button>
        </div>
    </form>
</x-app-layout>
