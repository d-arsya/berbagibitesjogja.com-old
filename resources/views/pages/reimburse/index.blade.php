@extends('layouts.main')

@section('container')
    <div class="max-w-7xl mx-auto py-10 px-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Manajemen Reimburse</h1>

        <div class="bg-white shadow rounded-2xl p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="px-4 py-2">User</th>
                            <th class="px-4 py-2">Metode</th>
                            <th class="px-4 py-2">Tujuan</th>
                            <th class="px-4 py-2">Jumlah</th>
                            <th class="px-4 py-2">File</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reimburse as $item)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $item->user->name ?? 'Unknown' }}</td>
                                <td class="px-4 py-2">{{ $item->method }}</td>
                                <td class="px-4 py-2">{{ $item->target }}</td>
                                <td class="px-4 py-2 font-medium">
                                    {{ 'Rp ' . number_format($item->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ asset('storage/' . $item->file) }}" target="_blank"
                                        class="text-indigo-600 hover:underline">
                                        Lihat
                                    </a>
                                </td>
                                <td class="px-4 py-2">
                                    @if ($item->done == true)
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Selesai</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">Proses</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 space-x-2">
                                    {{-- Tombol Selesai --}}
                                    @if ($item->done == false)
                                        <form action="{{ route('reimburse.update', $item->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-xs">
                                                Sudah
                                            </button>
                                        </form>

                                        {{-- Tombol Batal --}}
                                        <form action="{{ route('reimburse.destroy', $item->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-xs">
                                                Dibatalkan
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-4 text-center text-gray-500">
                                    Belum ada pengajuan reimburse
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
