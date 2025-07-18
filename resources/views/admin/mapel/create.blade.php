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
        <div class="mt-2 flex justify-end gap-2">
            <a href="{{ route('admin.mapel.index') }}"
                class="btn btn-primary rounded-md py-2 px-6 text-base font-semibold">Batal</a>
            <button type="submit" class="btn btn-primary rounded-md py-2 px-6 text-base font-semibold">
                Simpan
            </button>
        </div>
    </form>
</x-app-layout>
