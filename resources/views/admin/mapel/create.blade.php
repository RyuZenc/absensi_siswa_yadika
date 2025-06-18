<x-app-layout>
    @section('header', 'Tambah Mata Pelajaran Baru')

    <form action="{{ route('admin.mapel.store') }}" method="POST">
        @csrf
        <div>
            <label for="nama_mapel" class="block text-sm font-medium text-gray-700">Nama Mata Pelajaran</label>
            <input type="text" name="nama_mapel" id="nama_mapel"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                value="{{ old('nama_mapel') }}" required>
            @error('nama_mapel')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.mapel.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Batal</a>
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
        </div>
    </form>
</x-app-layout>
