<x-app-layout>
    @section('header', 'Riwayat Absensi')

    <h2 class="text-xl font-semibold mb-4">Riwayat Absensi Siswa</h2>

    @php
        $groupedSesi = $sesiAbsens->groupBy('tanggal');
    @endphp

    @foreach ($groupedSesi as $tanggal => $items)
        <div x-data="{ open: false }" class="mb-4 border rounded-lg">
            <button @click="open = !open"
                class="w-full text-left p-4 bg-gray-100 font-semibold text-gray-700 flex justify-between items-center">
                <span>📅 {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</span>
                <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor">
                    <path d="M5 15l7-7 7 7" />
                </svg>
                <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor">
                    <path d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-collapse class="p-4 bg-white space-y-4">
                @foreach ($items as $sesi)
                    <div class="border p-4 rounded-lg shadow-sm">
                        <div class="font-bold">{{ $sesi->jadwal->mapel->nama_mapel }}</div>
                        <div class="text-sm">{{ $sesi->jadwal->kelas->tingkat }} -
                            {{ $sesi->jadwal->kelas->nama_kelas }}</div>
                        <div class="text-sm text-gray-500">{{ $sesi->jadwal->jam_mulai }} -
                            {{ $sesi->jadwal->jam_selesai }}</div>
                        <p class="text-sm text-gray-500">
                            <strong>Hadir:</strong> {{ $sesi->absensiCounts['hadir'] }} |
                            <strong>Sakit:</strong> {{ $sesi->absensiCounts['sakit'] }} |
                            <strong>Izin:</strong> {{ $sesi->absensiCounts['izin'] }} |
                            <strong>Alpha:</strong> {{ $sesi->absensiCounts['alpha'] }}
                        </p>
                        <a href="{{ route('guru.riwayat.detail', $sesi->id) }}"
                            class="inline-block mt-2 text-blue-600 hover:underline text-sm">Lihat Detail</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach



    @if ($sesiAbsens->isEmpty())
        <p>Belum ada riwayat absensi yang tercatat.</p>
    @endif
</x-app-layout>
