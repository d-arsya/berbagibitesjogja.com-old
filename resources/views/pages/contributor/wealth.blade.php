@extends('layouts.main')
@section('container')
    <div class="mt-6">
        <div>
            {{ $payments->links() }}
        </div>

        <table class="mt-6 shadow-md sm:rounded-lg text-center w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Nama
                    </th>
                    <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                        Nominal
                    </th>
                    <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $item)
                    <tr class="odd:bg-white even:bg-gray-50 border-b">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $item->name }}
                            <span class="md:hidden block my-2">
                                Rp {{ number_format($item->amount, 0, ',', '.') }}
                            </span>
                            <span class="md:hidden">
                                <span
                                    class="bg-{{ $item->status == 'waiting' ? 'orange-400' : ($item->status == 'cancel' ? 'red-600' : 'tosca') }} py-1 px-6 rounded-full text-white ">{{ $item->status }}</span>

                            </span>
                        </th>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            {{ \Carbon\Carbon::parse($item->created_at)->isoFormat('D MMMM Y hh:mm') }}
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            Rp {{ number_format($item->amount, 0, ',', '.') }}
                        </td>
                        <td class="py-4 hidden sm:table-cell">
                            <span
                                class="bg-{{ $item->status == 'waiting' ? 'orange-400' : ($item->status == 'cancel' ? 'red-600' : 'tosca') }} py-1 px-6 rounded-full text-white ">{{ $item->status }}</span>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
