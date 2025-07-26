<x-app-layout>
    @section('header', 'Edit Jadwal')

    <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="guru_id" class="block text-sm font-medium text-gray-700">Guru</label>
                <select name="guru_id" id="guru_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach ($gurus as $guru)
                        <option value="{{ $guru->id }}"
                            {{ old('guru_id', $jadwal->guru_id) == $guru->id ? 'selected' : '' }}>
                            {{ $guru->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('guru_id')" class="mt-2" />
            </div>

            <div>
                <label for="mapel_id" class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                <select name="mapel_id" id="mapel_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach ($mapels as $mapel)
                        <option value="{{ $mapel->id }}"
                            {{ old('mapel_id', $jadwal->mapel_id) == $mapel->id ? 'selected' : '' }}>
                            {{ $mapel->nama_mapel }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('mapel_id')" class="mt-2" />
            </div>

            <div>
                <label for="kelas_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                <select name="kelas_id" id="kelas_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}"
                            {{ old('kelas_id', $jadwal->kelas_id) == $k->id ? 'selected' : '' }}>
                            {{ $k->tingkat . ' - ' . $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('kelas_id')" class="mt-2" />
            </div>

            <div>
                <label for="hari" class="block text-sm font-medium text-gray-700">Hari</label>
                <select name="hari" id="hari"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                        <option value="{{ $hari }}"
                            {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('hari')" class="mt-2" />
            </div>

            <div>
                <label for="jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                <input type="time" name="jam_mulai" id="jam_mulai"
                    value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <x-input-error :messages="$errors->get('jam_mulai')" class="mt-2" />
            </div>

            <div>
                <label for="jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                <input type="time" name="jam_selesai" id="jam_selesai"
                    value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <x-input-error :messages="$errors->get('jam_selesai')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.jadwal.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-md text-base transition">
                Batal
            </a>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md text-base transition">
                Update
            </button>
        </div>
    </form>
</x-app-layout>
