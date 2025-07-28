<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Peran Guru (Wali Kelas)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <x-toast type="success" message="{{ session('success') }}" />
                @endif
                @if (session('error'))
                    <x-toast type="error" message="{{ session('error') }}" />
                @endif

                <h3 class="text-lg font-semibold mb-4">Tetapkan Wali Kelas</h3>
                <form action="{{ route('admin.roles.assignWaliKelas') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="wali_kelas_guru_id" class="block text-sm font-medium text-gray-700">Pilih Guru
                            sebagai Wali Kelas</label>
                        <select name="guru_id" id="wali_kelas_guru_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($availableWaliKelas as $guru)
                                <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama_lengkap }} (KODE GURU: {{ $guru->kode_guru }})
                                    @if ($guru->role === 'wali_kelas')
                                        (Saat ini Wali Kelas)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('guru_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700">Pilih Kelas yang
                            Diampu</label>
                        <select name="kelas_id" id="kelas_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $kls)
                                <option value="{{ $kls->id }}" {{ old('kelas_id') == $kls->id ? 'selected' : '' }}
                                    {{ $kls->guru_id ? 'disabled' : '' }}>
                                    {{ $kls->tingkat . ' - ' . $kls->nama_kelas }}
                                    {{ $kls->guru_id ? '(Sudah Ada Wali Kelas)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button
                        class="text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md text-sm transition w-full sm:w-auto"
                        type="submit">Tetapkan Wali Kelas</button>
                </form>

                <h4 class="text-md font-semibold mt-6 mb-2">Daftar Wali Kelas yang Ditugaskan</h4>
                @if ($waliKelasAssignments->isEmpty())
                    <p class="text-gray-600">Belum ada Wali Kelas yang ditetapkan untuk kelas manapun.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kelas
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Wali Kelas
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($waliKelasAssignments as $assignment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $assignment->tingkat . ' - ' . $assignment->nama_kelas }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $assignment->waliKelas ? $assignment->waliKelas->nama_lengkap : 'Belum Ditetapkan' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('admin.roles.removeWaliKelas') }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus penetapan Wali Kelas dari kelas ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="kelas_id" value="{{ $assignment->id }}">
                                                <x-danger-button type="submit">Hapus Wali Kelas</x-danger-button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
