@extends('layouts.main')
@section('container')
    <a href="{{ route('precence.index') }}" class="bg-orange-400 hover:bg-orange-600 p-2 text-white rounded-md shadow-md">
        < Kembali </a>
            <a href="{{ route('precence.qr', $precence->id) }}"
                class="bg-navy-500 hover:bg-navy-600 p-2 ml-3 text-white rounded-md shadow-md">
                Download QR </a>
            <div class="mt-6">
                <div>
                </div>
                <table
                    class="mt-6 shadow-md sm:rounded-lg text-center w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Tanggal
                            </th>
                            <th scope="col" class="hidden sm:table-cell px-6 py-3">
                                Judul
                            </th>
                            <th scope="col" class="hidden sm:table-cell px-6 py-3">
                                Hadir
                            </th>
                            <th scope="col" class="hidden sm:table-cell px-6 py-3">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        @endsection
