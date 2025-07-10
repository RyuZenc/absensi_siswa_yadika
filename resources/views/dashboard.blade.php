<x-app-layout>
    @section('header', 'Dashboard Admin')

    <div class="max-w-6xl mx-auto space-y-10">
        <h2 class="text-2xl font-semibold mb-6">Selamat Datang, {{ Auth::user()->name }}!</h2>

        <!-- Kartu Statistik (Horizontal) -->
        <div class="flex flex-wrap gap-6">
            <!-- Card Jumlah Siswa -->
            <div
                class="bg-blue-100 p-6 rounded-lg shadow-md flex items-center flex-1 min-w-[250px] hover:bg-gray-50 hover:ring-2 hover:ring-blue-100 transition-all border border-gray-200">
                <div class="text-4xl text-blue-500 mr-4">🎓</div>
                <div>
                    <p class="text-sm text-blue-700 font-semibold">Jumlah Siswa</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $jumlahSiswa ?? 0 }}</p>
                </div>
            </div>

            <!-- Card Jumlah Guru -->
            <div
                class="bg-green-100 p-6 rounded-lg shadow-md flex items-center flex-1 min-w-[250px] hover:bg-gray-50 hover:ring-2 hover:ring-blue-100 transition-all border border-gray-200">
                <div class="text-4xl text-green-500 mr-4">👩‍🏫</div>
                <div>
                    <p class="text-sm text-green-700 font-semibold">Jumlah Guru</p>
                    <p class="text-2xl font-bold text-green-900">{{ $jumlahGuru ?? 0 }}</p>
                </div>
            </div>

            <!-- Card Jumlah Kelas -->
            <div
                class="bg-yellow-100 p-6 rounded-lg shadow-md flex items-center flex-1 min-w-[250px] hover:bg-gray-50 hover:ring-2 hover:ring-blue-100 transition-all border border-gray-200">
                <div class="text-4xl text-yellow-500 mr-4">🏫</div>
                <div>
                    <p class="text-sm text-yellow-700 font-semibold">Jumlah Kelas</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $jumlahKelas ?? 0 }}</p>
                </div>
            </div>

            <!-- Card Jumlah Mapel -->
            <div
                class="bg-purple-100 p-6 rounded-lg shadow-md flex items-center flex-1 min-w-[250px] hover:bg-gray-50 hover:ring-2 hover:ring-blue-100 transition-all border border-gray-200">
                <div class="text-4xl text-purple-500 mr-4">📚</div>
                <div>
                    <p class="text-sm text-purple-700 font-semibold">Jumlah Mapel</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $jumlahMapel ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Akses Cepat -->
        <div class="mt-5">
            <h3 class="text-xl font-semibold mb-2">Akses Cepat</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Tambah Siswa -->
                <a href="{{ route('admin.siswa.create') }}"
                    class="flex items-center gap-3 bg-white p-4 rounded-lg shadow-md hover:bg-gray-50 hover:ring-2 hover:ring-blue-100 transition-all border border-gray-200">
                    <div class="text-blue-500 text-2xl">👨‍🎓</div>
                    <p class="font-semibold text-gray-700">Tambah Siswa Baru</p>
                </a>

                <!-- Tambah Guru -->
                <a href="{{ route('admin.guru.create') }}"
                    class="flex items-center gap-3 bg-white p-4 rounded-lg shadow-md hover:bg-gray-50 hover:ring-2 hover:ring-blue-100 transition-all border border-gray-200">
                    <div class="text-green-500 text-2xl">👨‍🏫</div>
                    <p class="font-semibold text-gray-700">Tambah Guru Baru</p>
                </a>

                <!-- Tambah Jadwal -->
                <a href="{{ route('admin.jadwal.create') }}"
                    class="flex items-center gap-3 bg-white p-4 rounded-lg shadow-md hover:bg-gray-50 hover:ring-2 hover:ring-blue-100 transition-all border border-gray-200">
                    <div class="text-yellow-500 text-2xl">📅</div>
                    <p class="font-semibold text-gray-700">Tambah Jadwal Baru</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
