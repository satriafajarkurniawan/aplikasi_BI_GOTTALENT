<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Penilaian Peserta
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Nama Peserta</th>
                            <th class="px-4 py-2 text-left">Kategori Lomba</th>
                            <th class="px-4 py-2 text-left">Nilai</th>
                            <th class="px-4 py-2 text-left">Aksi / Input Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peserta as $item)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $item->user->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $item->lomba->nama_lomba ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $item->nilai ?? 'Belum Dinilai' }}</td>
                            <td class="px-4 py-2">
                                <form action="{{ route('juri.penilaian.store', $item->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="nilai" value="{{ $item->nilai }}" placeholder="0-100" class="border p-1 rounded w-20" required>
                                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded">Simpan</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>