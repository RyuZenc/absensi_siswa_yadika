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
            <input type="text" name="tingkat" id="tingkat" class="mt-1 block w-full rounded-md border-gray-300"
                value="{{ old('tingkat', $kelas->tingkat) }}" required>
            @error('tingkat')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">Update</button>
        </div>
    </form>
</x-app-layout>
