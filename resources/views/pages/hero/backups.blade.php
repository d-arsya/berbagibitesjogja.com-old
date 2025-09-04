@extends('layouts.main')
@section('container')
    <a href="{{ route('hero.index') }}" class="bg-navy-500 hover:bg-navy-700 p-2 text-white rounded-md shadow-md">
        Heroes
    </a>
    <div class="mt-6">
        <div>
            {{ $backups->links() }}
        </div>
        <table class="mt-6 shadow-md sm:rounded-lg text-center w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Nama
                    </th>
                    <th scope="col" class="hidden sm:table-cell px-6 py-3">
                        Fakultas
                    </th>
                    <th scope="col" class="hidden sm:table-cell px-6 py-3">
                        Telepon
                    </th>
                    <th scope="col" class="hidden sm:table-cell px-6 py-3">
                        Donasi
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($backups as $item)
                    <tr class="odd:bg-white even:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $item->name }}
                        </th>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            {{ $item->faculty->name }}
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            {{ $item->phone }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $donation = $item->donation;
                                $sponsor = $donation->sponsor;
                            @endphp
                            <a href="{{ route('sponsor.show', $sponsor->id) }}" class="block">
                                {{ $sponsor->name }}
                            </a>
                            <a href="{{ route('donation.show', $donation->id) }}" class="block">
                                {{ $donation->take }}
                            </a>
                        </td>
                        <td class="px-6 py-4 flex justify-center gap-2">
                            <a href="{{ route('hero.restore', $item->id) }}"
                                class="p-2 rounded-md bg-yellow-300 hover:bg-yellow-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white"
                                    class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z" />
                                    <path
                                        d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466" />
                                </svg>
                            </a>
                            <form action="{{ route('hero.trash', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-md bg-red-300 hover:bg-red-600">
                                    <svg width="18" height="17" viewBox="0 0 18 17" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M13.1665 3.50008H17.3332V5.16675H15.6665V16.0001C15.6665 16.2211 15.5787 16.4331 15.4224 16.5893C15.2661 16.7456 15.0542 16.8334 14.8332 16.8334H3.1665C2.94549 16.8334 2.73353 16.7456 2.57725 16.5893C2.42097 16.4331 2.33317 16.2211 2.33317 16.0001V5.16675H0.666504V3.50008H4.83317V1.00008C4.83317 0.779068 4.92097 0.567106 5.07725 0.410826C5.23353 0.254545 5.44549 0.166748 5.6665 0.166748H12.3332C12.5542 0.166748 12.7661 0.254545 12.9224 0.410826C13.0787 0.567106 13.1665 0.779068 13.1665 1.00008V3.50008ZM13.9998 5.16675H3.99984V15.1667H13.9998V5.16675ZM6.49984 1.83341V3.50008H11.4998V1.83341H6.49984Z"
                                            fill="white" />
                                    </svg>


                                </button>

                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
