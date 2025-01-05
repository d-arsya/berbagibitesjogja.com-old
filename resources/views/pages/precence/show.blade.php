@extends('layouts.main')
@section('container')
    <a href="{{ route('precence.index') }}" class="bg-orange-400 hover:bg-orange-600 p-2 text-white rounded-md shadow-md">
        < Kembali </a>
            <a href="{{ route('precence.qr', $precence->id) }}"
                class="bg-navy-500 hover:bg-navy-600 p-2 ml-3 text-white rounded-md shadow-md">
                Download QR </a>
            <div class="mt-6">
                <h1>Tanggal : {{ $precence->created_at->isoFormat('dddd, DD MMMM YYYY') }}</h1>
                <h1>Judul : {{ $precence->title }}</h1>
                <h1>Hadir : {{ $precence->attendance->count() }}</h1>
            </div>
            <table class="mt-6 shadow-md sm:rounded-lg text-center w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Nama
                        </th>
                        <th scope="col" class="hidden sm:table-cell px-6 py-3">
                            Waktu
                        </th>
                        <th scope="col" class="hidden sm:table-cell px-6 py-3">
                            Jarak
                        </th>
                        <th scope="col" class="hidden sm:table-cell px-6 py-3">
                            Poin
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($precence->attendance as $item)
                        <tr>
                            <td class="px-6 py-3">
                                {{ $item->user->name }}
                            </td>
                            <td class="px-6 py-3">
                                {{ $item->created_at->isoFormat('hh:mm') }}
                            </td>
                            <td class="px-6 py-3">
                                {{ $item->distance }} m
                            </td>
                            <td class="px-6 py-3">
                                <form action="{{ route('precence.update', $precence->id) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="attendance_id" value="{{ $item->id }}">
                                    <input type="number" class="border-2 rounded-xl py-1 w-20 px-2 text-center"
                                        placeholder="0" name="point" value="{{ $item->point }}">
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @endsection
