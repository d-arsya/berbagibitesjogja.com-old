@extends('layouts.main')
@section('container')
    <a class="bg-orange-300 hover:bg-orange-500 shadow-md px-5 py-2.5 rounded-md text-white"
        href="{{ route('beneficiary.index') }}">
        < Kembali</a>

            <h1 class="mt-6">Nama : {{ $beneficiary->name }}</h1>
            <h1>Jumlah Penerima : {{ $beneficiary->heroes->sum('quantity') }} Orang</h1>
            <h1>Makanan Diterima : {{ ceil($beneficiary->foods() / 1000) }} Kg</h1>
            <h1 class="text-center text-xl font-bold my-10">Donasi Untuk {{ $beneficiary->name }}</h1>
            <div class="mt-6 shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-center text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Tanggal
                            </th>
                            <th scope="col" class="hidden sm:table-cell px-6 py-3">
                                Penerima
                            </th>
                            <th scope="col" class="hidden sm:table-cell px-6 py-3">
                                Donatur
                            </th>
                            <th scope="col" class="hidden sm:table-cell px-6 py-3">
                                Makanan
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($donations->sortBy('take') as $item)
                            <tr class="odd:bg-white text-center even:bg-gray-50 border-b">
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($item->take)->isoFormat('dddd, D MMMM Y') }}
                                    <span class="block md:hidden">
                                        {{ $item->quota - $item->remain }} Orang</span>
                                </td>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    {{ $item->quota - $item->remain }} Orang
                                </td>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    {{ $item->sponsor->name }}
                                </td>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    {{ ceil($item->foods->sum('weight') / 1000) }} Kg
                                </td>
                                <td class="px-6 py-4 flex justify-center gap-2">
                                    <a href="{{ route('donation.show', $item->id) }}"
                                        class="p-2 rounded-md bg-tosca-300 hover:bg-tosca-600">
                                        <svg width="20" height="15" viewBox="0 0 20 15" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.99972 0C14.4931 0 18.2314 3.23333 19.0156 7.5C18.2322 11.7667 14.4931 15 9.99972 15C5.50639 15 1.76805 11.7667 0.983887 7.5C1.76722 3.23333 5.50639 0 9.99972 0ZM9.99972 13.3333C11.6993 13.333 13.3484 12.7557 14.6771 11.696C16.0058 10.6363 16.9355 9.15689 17.3139 7.5C16.9341 5.84442 16.0038 4.36667 14.6752 3.30835C13.3466 2.25004 11.6983 1.67377 9.99972 1.67377C8.30113 1.67377 6.65279 2.25004 5.3242 3.30835C3.9956 4.36667 3.06536 5.84442 2.68555 7.5C3.06397 9.15689 3.99361 10.6363 5.32234 11.696C6.65106 12.7557 8.30016 13.333 9.99972 13.3333V13.3333ZM9.99972 11.25C9.00516 11.25 8.05133 10.8549 7.34807 10.1516C6.64481 9.44839 6.24972 8.49456 6.24972 7.5C6.24972 6.50544 6.64481 5.55161 7.34807 4.84835C8.05133 4.14509 9.00516 3.75 9.99972 3.75C10.9943 3.75 11.9481 4.14509 12.6514 4.84835C13.3546 5.55161 13.7497 6.50544 13.7497 7.5C13.7497 8.49456 13.3546 9.44839 12.6514 10.1516C11.9481 10.8549 10.9943 11.25 9.99972 11.25ZM9.99972 9.58333C10.5523 9.58333 11.0822 9.36384 11.4729 8.97314C11.8636 8.58244 12.0831 8.05253 12.0831 7.5C12.0831 6.94747 11.8636 6.41756 11.4729 6.02686C11.0822 5.63616 10.5523 5.41667 9.99972 5.41667C9.44719 5.41667 8.91728 5.63616 8.52658 6.02686C8.13588 6.41756 7.91639 6.94747 7.91639 7.5C7.91639 8.05253 8.13588 8.58244 8.52658 8.97314C8.91728 9.36384 9.44719 9.58333 9.99972 9.58333Z"
                                                fill="white" />
                                        </svg>


                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $donations->links() }}
            </div>
            @if ($beneficiary->variant != 'foundation')
                <button onclick="addSector()"
                    class="bg-lime-400 hover:bg-lime-600 p-2 my-10 text-white rounded-md shadow-md">
                    + Tambah
                </button>
                <h1 class="text-center text-xl font-bold">Persebaran Di {{ $beneficiary->name }}</h1>
                <div class="mt-6 shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-center text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Nama
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Jumlah
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($faculties as $item)
                                <tr class="odd:bg-white text-center even:bg-gray-50 border-b">
                                    <td class="px-6 py-4">
                                        {{ $item->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $item->heroes->sum('quantity') }} Orang
                                    </td>
                                    <td class="px-6 py-4 flex justify-center gap-2">
                                        <a href="{{ route('hero.faculty', $item->id) }}"
                                            class="p-2 rounded-md bg-tosca-300 hover:bg-tosca-600">
                                            <svg width="20" height="15" viewBox="0 0 20 15" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.99972 0C14.4931 0 18.2314 3.23333 19.0156 7.5C18.2322 11.7667 14.4931 15 9.99972 15C5.50639 15 1.76805 11.7667 0.983887 7.5C1.76722 3.23333 5.50639 0 9.99972 0ZM9.99972 13.3333C11.6993 13.333 13.3484 12.7557 14.6771 11.696C16.0058 10.6363 16.9355 9.15689 17.3139 7.5C16.9341 5.84442 16.0038 4.36667 14.6752 3.30835C13.3466 2.25004 11.6983 1.67377 9.99972 1.67377C8.30113 1.67377 6.65279 2.25004 5.3242 3.30835C3.9956 4.36667 3.06536 5.84442 2.68555 7.5C3.06397 9.15689 3.99361 10.6363 5.32234 11.696C6.65106 12.7557 8.30016 13.333 9.99972 13.3333V13.3333ZM9.99972 11.25C9.00516 11.25 8.05133 10.8549 7.34807 10.1516C6.64481 9.44839 6.24972 8.49456 6.24972 7.5C6.24972 6.50544 6.64481 5.55161 7.34807 4.84835C8.05133 4.14509 9.00516 3.75 9.99972 3.75C10.9943 3.75 11.9481 4.14509 12.6514 4.84835C13.3546 5.55161 13.7497 6.50544 13.7497 7.5C13.7497 8.49456 13.3546 9.44839 12.6514 10.1516C11.9481 10.8549 10.9943 11.25 9.99972 11.25ZM9.99972 9.58333C10.5523 9.58333 11.0822 9.36384 11.4729 8.97314C11.8636 8.58244 12.0831 8.05253 12.0831 7.5C12.0831 6.94747 11.8636 6.41756 11.4729 6.02686C11.0822 5.63616 10.5523 5.41667 9.99972 5.41667C9.44719 5.41667 8.91728 5.63616 8.52658 6.02686C8.13588 6.41756 7.91639 6.94747 7.91639 7.5C7.91639 8.05253 8.13588 8.58244 8.52658 8.97314C8.91728 9.36384 9.44719 9.58333 9.99972 9.58333Z"
                                                    fill="white" />
                                            </svg>


                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-6">
                {{ $faculties->links() }}
            </div>
            <div id="popup" class="hidden w-full h-full absolute left-0 top-0 z-50 bg-navy bg-opacity-50 pt-12">
                <form method="POST" action="{{ route('beneficiary.store') }}"
                    class="max-w-md mx-auto shadow-md px-10 bg-white  py-6 rounded-md transform transition-all duration-300 opacity-0 ease-in-out translate-y-[-20px] scale-95">
                    @csrf
                    <input type="hidden" name="university_id" value="{{ $beneficiary->id }}">
                    <div class="relative z-0 w-full mb-5 group">
                        <input type="text" name="name" id="name"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nama
                            Sektor</label>
                    </div>

                    <button onclick="remove()"
                        class="text-white bg-pink-700 hover:bg-pink-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Batal</button>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
                </form>


            </div>
            <script>
                function addSector() {
                    document.querySelector('#popup').classList.remove('hidden')
                    document.querySelector('#popup>form').classList.remove('opacity-0', 'translate-y-[-20px]',
                        'scale-95');
                    document.querySelector('#popup>form').classList.add('opacity-100', 'translate-y-0', 'scale-100');
                }

                function remove() {
                    document.querySelector('#popup').classList.add('hidden')
                    document.querySelector('#popup>form').reset()
                }
            </script>
        @endsection
