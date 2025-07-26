<x-app-layout>
    @section('header', 'Tambah Jadwal Baru')

    <form action="{{ route('admin.jadwal.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="kelas_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                <select name="kelas_id" id="kelas_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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
                <label for="mapel_id" class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                <select name="mapel_id" id="mapel_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @foreach ($mapels as $mapel)
                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                    @endforeach
                </select>
                @error('mapel_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <!-- Guru -->
            <div>
                <label for="guru_id" class="block text-sm font-medium text-gray-700">Guru</label>
                <select name="guru_id" id="guru_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @foreach ($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->nama_lengkap }}</option>
                    @endforeach
                </select>
                @error('guru_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <!-- Hari -->
            <div>
                <label for="hari" class="block text-sm font-medium text-gray-700">Hari</label>
                <select name="hari" id="hari" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option>Senin</option>
                    <option>Selasa</option>
                    <option>Rabu</option>
                    <option>Kamis</option>
                    <option>Jumat</option>
                    <option>Sabtu</option>
                </select>
                @error('hari')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <!-- Jam Mulai -->
            <div>
                <label for="jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                <input type="time" name="jam_mulai" id="jam_mulai"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('jam_mulai')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <!-- Jam Selesai -->
            <div>
                <label for="jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                <input type="time" name="jam_selesai" id="jam_selesai"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('jam_selesai')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="mt-2 flex justify-end gap-2">
            <a href="{{ route('admin.jadwal.index') }}"
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
