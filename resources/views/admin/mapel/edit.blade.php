<x-app-layout>
    @section('header', 'Edit Mata Pelajaran')

    <form action="{{ route('admin.mapel.update', $mapel->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="nama_mapel" class="block text-sm font-medium text-gray-700">Nama Mata Pelajaran</label>
            <input type="text" name="nama_mapel" id="nama_mapel"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required>
            @error('nama_mapel')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-4">
            <label for="guru_id" class="block text-sm font-medium text-gray-700">Guru Pengajar (Opsional)</label>
            <select name="guru_id" id="guru_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">-- Pilih Guru --</option>
                @foreach ($gurus as $guru)
                    <option value="{{ $guru->id }}"
                        {{ old('guru_id', $mapel->guru_id) == $guru->id ? 'selected' : '' }}>
                        {{ $guru->nama_lengkap }}
                    </option>
                @endforeach
            </select>
            @error('guru_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-2 flex justify-end gap-2">
            <a href="{{ route('admin.mapel.index') }}"
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
