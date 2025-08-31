@extends('layouts.form')
@section('container')
    <div class="max-w-lg mx-auto mt-6">
        <div class="w-full rounded-lg bg-white shadow-lg mt-8 py-5 px-6">
            <form action="">
                <h1 class="text-xl text-tosca font-medium text-center italic">Dapatkan notifikasi dari kami ketika ada donasi
                    aktif
                </h1>
                <div class="relative z-0 mt-8 w-full group">
                    <input autocomplete="off" type="number" name="phone" id="phone"
                        class="block py-2.5 px-6 w-full text-xs text-gray-900 bg-transparent border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " phone="phone" required />
                    <p
                        class="absolute text-md text-gray-700 duration-300 transform -translate-y-0 scale-75 top-2 left-2 -z-10 origin-[0]">
                        62</p>
                    <label for="phone"
                        class="peer-focus:font-medium absolute text-md text-gray-700 duration-300 transform -translate-y-8 scale-75 top-2 left-6 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Nomor
                        Whatsapp</label>
                </div>
                <button type="submit"
                    class="text-sm bg-navy hover:bg-navy-600 mt-8 w-max rounded-md py-2 px-4 m-auto text-center block text-white font-medium text-center italic">Dapatkan
                    Notifikasi</button>
                <h1 class="text-xs text-slate-400 font-medium text-center italic mt-3">karena keterbatasan pendonor hanya
                    bisa digunakan dengan email UGM (mail.ugm.ac.id)</h1>

            </form>
        </div>
    </div>
@endsection
