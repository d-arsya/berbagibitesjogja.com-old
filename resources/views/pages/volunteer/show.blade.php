@extends('layouts.main')
@section('container')
    <div class="mt-3 flex gap-3 w-max">
        <a class="bg-orange-300 hover:bg-orange-500 shadow-md p-2 rounded-md text-white" href="{{ url()->previous() }}">
            < Kembali</a>
    </div>

    <div class="mt-5 max-w-md mx-auto bg-navy p-5 text-center text-white font-bold rounded-t-md">
        Edit Volunteer
    </div>
    <form method="POST" action="{{ route('volunteer.update', $volunteer->id) }}"
        class="max-w-md mx-auto shadow-md px-10  py-6 rounded-b-md" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @if (auth()->user()->role == 'super')
            <label for="divisions" class="block mb-2 text-sm font-medium text-gray-900">Pilih Divisi</label>
            <select id="divisions" name="division_id"
                class="mb-5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="">Divisi</option>
                @foreach ($divisions as $item)
                    <option {{ $volunteer->division_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">
                        {{ $item->name }}</option>
                @endforeach
            </select>
            <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Pilih Role</label>
            <select id="role" name="role"
                class="mb-5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option {{ $volunteer->role == 'super' ? 'selected' : '' }} value="super">Super</option>
                <option {{ $volunteer->role == 'core' ? 'selected' : '' }} value="core">Inti</option>
                <option {{ $volunteer->role == 'staff' ? 'selected' : '' }} value="staff">Staff</option>
                <option {{ $volunteer->role == 'member' ? 'selected' : '' }} value="member">Volunteer</option>
            </select>
        @endif

        <div class="relative z-0 w-full mb-5 group">
            <input type="email" name="email" id="email" value="{{ $volunteer->email }}"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" " name="email" required />
            <label for="email"
                class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="tel" name="phone" id="phone" value="{{ $volunteer->phone }}"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" " name="phone" required />
            <label for="phone"
                class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Whatsapp</label>
        </div>
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center mt-2">Submit</button>
    </form>
@endsection
