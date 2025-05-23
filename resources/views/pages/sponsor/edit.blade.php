@extends('layouts.main')
@section('container')
    <a class="bg-orange-300 hover:bg-orange-500 shadow-md px-5 py-2.5 rounded-md text-white"
        href="{{ route('donation.index') }}">
        < Kembali</a>

            <div class="max-w-md mx-auto bg-navy mt-10 p-5 text-center text-white font-bold rounded-t-md">
                Edit Sponsor BBJ
            </div>
            <form method="POST" action="{{ route('sponsor.update', $sponsor->id) }}"
                class="max-w-md mx-auto shadow-md px-10  py-6 rounded-b-md">
                @csrf
                @method('PUT')
                <div class="relative z-0 w-full mb-5 group">
                    <input value="{{ $sponsor->name }}" type="text" name="name" id="name"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " name="name" required />
                    <label for="name"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nama
                        Sponsor</label>
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <input value="{{ $sponsor->address }}" type="text" name="address" id="address"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " name="address" />
                    <label for="address"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Alamat</label>
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <input value="{{ $sponsor->phone }}" type="text" name="phone" id="phone"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " name="phone" />
                    <label for="phone"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Telepon</label>
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <input value="{{ $sponsor->email }}" type="email" name="email" id="email"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " name="email" />
                    <label for="email"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
                </div>
                <div class="flex gap-2 flex-row">
                    <div class="flex align-middle gap-2 my-2">
                        <input {{ $sponsor->variant == 'individual' ? 'checked' : '' }} type="checkbox" name="variant"
                            id="variant">
                        <label for="variant" class="text-xs ">Individu</label>

                    </div>

                </div>
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
            </form>
        @endsection
