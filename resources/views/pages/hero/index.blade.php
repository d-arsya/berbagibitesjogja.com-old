@extends('layouts.main')
@section('container')
    @auth

        <a href="{{ route('hero.backups') }}" class="bg-orange-400 hover:bg-orange-600 p-2 text-white rounded-md shadow-md">
            Backups
        </a>
        <button onclick="addHeroes()" class="bg-navy hover:bg-navy-700 ml-3 p-2 text-white rounded-md shadow-md">
            + Kontributor
        </button>
    @endauth
    <div class="mt-6">
        <div>
            {{ $heroes->links() }}
        </div>
        <table
            class="shadow-md sm:rounded-lg mt-12 text-center w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Nama
                    </th>
                    @auth

                        <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                            Fakultas
                        </th>
                        <th scope="col" class="hidden sm:table-cell px-6 py-3">
                            Telepon
                        </th>
                    @endauth
                    <th scope="col" class="px-6 py-3">
                        Donasi
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($heroes as $item)
                    <tr class="odd:bg-white even:bg-gray-50 border-b">
                        <th scope="row" class="px-2 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $item->name }}
                            <span class="md:hidden italic font-normal text-gray-500 block">
                                <a href="{{ route('hero.faculty', $item->faculty) }}">
                                    {{ $item->faculty }}
                                </a>
                            </span>
                        </th>
                        @auth

                            <td class="px-2 py-4 hidden sm:table-cell">
                                <a href="{{ route('hero.faculty', $item->faculty) }}">
                                    {{ $item->faculty()->name }}
                                </a>

                            </td>
                            <td class="px-2 py-4 hidden sm:table-cell">
                                <a href="https://wa.me/{{ $item->phone }}">
                                    {{ $item->phone }}
                                </a>

                            </td>
                        @endauth
                        <td class="px-2 py-4 flex flex-col">
                            @php
                                $donation = $item->donation();
                                $sponsor = $donation->sponsor();
                            @endphp
                            <a href="{{ route('donation.show', $donation->id) }}" class="block">
                                <span class="block">
                                    {{ $sponsor->name }}

                                </span>
                                {{ \Carbon\Carbon::parse($donation->take)->format('d-m-Y') }}
                            </a>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="popup" class="hidden w-full h-full absolute left-0 top-0 z-50 bg-navy bg-opacity-50 pt-12">
        <form method="POST" action="{{ route('hero.contributor') }}"
            class="max-w-md mx-auto shadow-md px-10 bg-white  py-6 rounded-md">
            @csrf
            <label for="countries" class="block mb-2 text-sm font-medium text-gray-900">Pilih Sponsor</label>
            <select id="donation" name="donation"
                class="mb-5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="">Donasi</option>
                @foreach ($donations as $item)
                    <option value="{{ $item->id }}">{{ $item->sponsor()->name }}</option>
                @endforeach
            </select>
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="name" id="name"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder=" " required />
                <label for="name"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">name
                    Kontributor</label>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <input type="number" name="quantity" id="quantity"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder=" " name="quantity" required />
                <label for="quantity"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">quantity</label>
            </div>

            <button onclick="remove()"
                class="text-white bg-pink-700 hover:bg-pink-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Batal</button>
            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
        </form>


    </div>
    <script>
        function addHeroes() {
            document.querySelector('#popup').classList.remove('hidden')
        }

        function remove() {
            document.querySelector('#popup').classList.add('hidden')
            document.querySelector('#popup>form').reset()
        }
    </script>
@endsection
