@extends('layouts.main')
@section('container')
    <div class="mt-3 flex gap-3 w-max">
        <a class="bg-orange-400 hover:bg-orange-600 shadow-md p-2 rounded-md text-white" href="{{ route('precence.index') }}">
            < Kembali</a>

    </div>


    <div class="mt-5 max-w-md mx-auto bg-navy p-5 text-center text-white font-bold rounded-t-md">
        Edit Presensi
    </div>
    <form method="POST" action="{{ route('precence.update', $precence->id) }}"
        class="max-w-md mx-auto shadow-md px-10  py-6 rounded-b-md">
        @csrf
        @method('PUT')
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="title" id="title" value="{{ $precence->title }}"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" " name="title" required />
            <label for="title"
                class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Judul</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="description" id="description" value="{{ $precence->description }}"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" " name="description" />
            <label for="description"
                class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Deskripsi
                (opsional)</label>
        </div>
        @if ($user->role == 'super')
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="latitude" id="latitude" value="{{ $precence->latitude }}"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder=" " name="latitude" />
                <label for="latitude"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Latitude</label>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="longitude" id="longitude" value="{{ $precence->longitude }}"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder=" " name="longitude" />
                <label for="longitude"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Longitude</label>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <input type="number" name="max_distance" id="max_distance" value="{{ $precence->max_distance }}"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder=" " name="max_distance" />
                <label for="max_distance"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Jarak
                    maximal</label>
            </div>
        @endif
        <fieldset class="grid grid-cols-2">
            <div class="flex items-center mb-4">
                <input id="country-option-1" type="radio" name="status" value="active"
                    class="w-4 h-4 border-gray-300 focus:ring-2 focus:ring-blue-300"
                    {{ $precence->status == 'active' ? 'checked' : '' }}>
                <label for="country-option-1" class="block ms-2  text-sm font-medium text-gray-900">
                    Aktif
                </label>
            </div>
            <div class="flex items-center mb-4">
                <input id="country-option-2" type="radio" name="status" value="end"
                    class="w-4 h-4 border-gray-300 focus:ring-2 focus:ring-blue-300"
                    {{ $precence->status == 'end' ? 'checked' : '' }}>
                <label for="country-option-2" class="block ms-2 text-sm font-medium text-gray-900">
                    Selesai
                </label>
            </div>
        </fieldset>
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
    </form>
@endsection
