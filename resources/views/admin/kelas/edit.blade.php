<x-app-layout>
    @section('header', 'Edit Kelas')
    <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="nama_kelas">Nama Kelas</label>
            <input type="text" name="nama_kelas" id="nama_kelas" class="mt-1 block w-full rounded-md border-gray-300"
                value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
            @error('nama_kelas')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-4">
            <label for="tingkat">Tingkat</label>
            <select name="tingkat" id="tingkat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                required>
                <option value="">Pilih Tingkat</option>
                @foreach ($tingkats as $tingkat_option)
                    <option value="{{ $tingkat_option }}"
                        {{ old('tingkat', $kelas->tingkat) == $tingkat_option ? 'selected' : '' }}>
                        {{ $tingkat_option }}
                    </option>
                @endforeach
            </select>
            @error('tingkat')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-2 flex justify-end gap-2">
            <a href="{{ route('admin.kelas.index') }}"
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
