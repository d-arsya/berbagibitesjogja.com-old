@extends('layouts.main')

@section('container')
    <div class="max-w-6xl mx-auto py-10 px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- Form Ajukan Reimburse --}}
            <div class="bg-white rounded-2xl shadow p-6">
                <h1 class="text-xl font-semibold mb-6 text-gray-800">Ajukan Reimburse</h1>

                <form action="{{ route('reimburse.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode</label>
                        <input type="text" name="method" placeholder="Contoh: BCA/BNI/ShopeePay/GoPay"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan</label>
                        <input type="text" name="target" placeholder="Contoh: 08912134452/1110003333"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Invoice</label>
                        <input type="file" name="file"
                            class="w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-4 
                               file:rounded-lg file:border-0 
                               file:text-sm file:font-semibold 
                               file:bg-indigo-600 file:text-white 
                               hover:file:bg-indigo-700">
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-xl shadow hover:bg-indigo-700 transition">
                        Ajukan
                    </button>
                </form>
            </div>

            {{-- Riwayat Pengajuan --}}
            <div class="bg-white rounded-2xl shadow p-6">
                <h1 class="text-xl font-semibold mb-6 text-gray-800">Riwayat Pengajuan</h1>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="px-4 py-2">Metode</th>
                                <th class="px-4 py-2">Tujuan</th>
                                <th class="px-4 py-2">Jumlah</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reimburse as $item)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $item->method }}</td>
                                    <td class="px-4 py-2">{{ $item->target }}</td>
                                    <td class="px-4 py-2 font-medium">
                                        {{ 'Rp ' . number_format($item->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @if ($item->done)
                                            <span
                                                class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Selesai</span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">Proses</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <a href="{{ asset('storage/' . $item->file) }}" target="_blank"
                                            class="text-indigo-600 hover:underline">
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada pengajuan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
