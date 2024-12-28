@php
    use Carbon\Carbon;
@endphp
@extends('layouts.form')
@section('container')

    <div class="max-w-lg mx-auto mt-6">
        @if ($donations->count() > 0)
            @if ($donations->contains('id', session('donation')))
                @php
                    $donation = $donations->find(session('donation'));
                @endphp

                <h1 class="text-center text-xl font-bold text-tosca">BBJ X {{ $donation->sponsor()->name }}</h1>
                <h1 class="text-center text-tosca text-xs italic font-semibold rounded-md mt-2">
                    {{ Carbon::parse($donation->take)->isoFormat('dddd, DD MMMM Y') }}</h1>
                <div class="w-full rounded-lg bg-tosca mt-8 py-5 px-6">
                    <h1 class="text-xl text-white text-center italic">Terimakasih telah menjadi heroes hari ini
                    </h1>
                    @if ($donation->message)
                        <h1 class="text-center text-xs">
                            *{{ $donation->message }}
                        </h1>
                    @endif
                    <h1 class="text-3xl text-white font-medium text-center italic my-4">
                        {{ implode(' ', str_split(session('code'))) }}
                    </h1>
                    <a href="{{ route('hero.cancel') }}">
                        <div class="m-auto bg-red-600 hover:bg-red-800 w-max rounded-md p-2 text-white">
                            Batalkan
                        </div>
                    </a>
                    <h1 class="text-xs text-white text-center italic mt-1">tunjukkan untuk menukarkan makanan</h1>
                    <h1 class="text-xs text-white text-center italic mt-3">ikuti instagram kami</h1>
                    <a href="https://www.instagram.com/berbagibitesjogja/"
                        class="text-xs text-center block text-white font-medium text-center italic">@berbagibitesjogja</a>
                </div>
                <div class="w-full rounded-lg bg-tosca mt-8 py-5 px-6">
                    <h1 class="text-md text-white font-medium text-center italic mb-4">Informasi take
                    </h1>
                    <a href="{{ $donation->maps }}"
                        class="text-center text-white text-md italic font-medium rounded-md mt-3"><svg class="inline"
                            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
                        </svg> {{ $donation->location }}</a>
                    <h1 class="text-white text-md italic font-medium rounded-md mt-3"><svg class="inline mr-1"
                            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-clock-fill" viewBox="0 0 16 16">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
                        </svg>{{ $donation->hour }}.{{ str_pad($donation->minute, 2, '0', STR_PAD_LEFT) }} WIB</h1>
                </div>
            @else
                <div class="flex flex-row flex-wrap gap-3">
                    @foreach ($donations as $id => $donation)
                        <a href="#donatur{{ $id }}"
                            class="link-tab {{ $id == 0 ? 'bg-navy text-white' : 'text-navy' }} border-2 border-navy hover:bg-navy hover:text-white text-sm  p-2 rounded-md flex-grow text-center">
                            {{ $donation->sponsor()->name }}
                        </a>
                    @endforeach
                </div>
                <div class="p-4">
                    @foreach ($donations as $id => $donation)
                        <div id="donatur{{ $id }}" class="tab-content {{ $id != 0 ? 'hidden' : '' }}">
                            <h1 class="text-center text-xl font-bold text-tosca">BBJ X {{ $donation->sponsor()->name }}
                            </h1>
                            <h1 class="text-center text-tosca text-xs italic font-semibold rounded-md mt-2">
                                {{ Carbon::parse($donation->take)->isoFormat('dddd, DD MMMM Y') }}</h1>
                            @if ($donation->remain > 0)
                                <div class="w-full rounded-lg bg-white shadow-xl mt-4 py-5 px-6">
                                    <h1 class="text-lg text-tosca font-semibold text-center">RSVP Now</h1>
                                    <form action="{{ route('hero.store') }}" method="POST">
                                        @csrf
                                        <input type="number" name="donation" value="{{ $donation->id }}" class="hidden">
                                        <div class="relative z-0 w-full mt-8 group">
                                            <input type="text" name="name" id="name"
                                                class="block py-2.5 px-2.5 w-full text-sm text-gray-900 bg-transparent border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                placeholder=" " name="name" required />
                                            <label for="name"
                                                class="peer-focus:font-medium absolute text-md text-gray-700 duration-300 transform -translate-y-8 scale-75 top-2 left-6 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Nama
                                                Lengkap</label>
                                        </div>
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
                                        @error('phone')
                                            <p class="text-xs italic text-red-500">Silahkan masukkan format
                                                telepon yang
                                                benar</p>
                                        @enderror
                                        <select
                                            class="w-full text-slate-600 mt-8 p-2.5 bg-transparent border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600"
                                            placeholder="Nomor Whatsapp" name="faculty" required>
                                            <option value="">Fakultas</option>
                                            @foreach (App\Models\Volunteer\Faculty::all() as $item)
                                                @if (!in_array($item->name, ['Kontributor', 'Lainnya']))
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button
                                            class="w-full px-4 py-2 mt-10 text-sm font-medium text-white bg-blue-500 rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                                            type="submit">Submit</button>
                                    </form>
                                </div>
                            @else
                                <div class="w-full rounded-lg bg-tosca mt-8 py-5 px-6">
                                    <h1 class="text-md text-white font-medium text-center italic">Mohon maaf quota telah
                                        terpenuhi, datang lagi lain waktu
                                    </h1>
                                    <h1 class="text-xs text-white font-medium text-center italic mt-3">ikuti instagram
                                        kami</h1>
                                    <a href="https://www.instagram.com/berbagibitesjogja/"
                                        class="text-xs text-center block text-white font-medium text-center italic">@berbagibitesjogja</a>
                                </div>
                            @endif
                            <h1 class="text-pink-900 text-md font-semibold text-center mt-4">Heroes</h1>
                            <h1 class="text-tosca text-2xl font-bold text-center mt-2">
                                {{ $donation->quota - $donation->remain }}/{{ $donation->quota }}</h1>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="w-full rounded-lg bg-tosca mt-8 py-5 px-6">
                <h1 class="text-xl text-white font-medium text-center italic">Maaf, belum ada food rescue hari ini</h1>
                <h1 class="text-md text-white font-medium text-center italic mt-3">ikuti instagram kami</h1>
                <a href="https://www.instagram.com/berbagibitesjogja/"
                    class="text-sm text-center block text-white font-medium text-center italic">@berbagibitesjogja</a>
            </div>
        @endif
    </div>
    <h1 class="font-bold text-navy text-xl md:text-2xl my-12">BBJ Dalam Data</h1>
    <div class="flex flex-row justify-center md:justify-between flex-wrap gap-6 mt-3">
        <div class="bg-white rounded-lg shadow-md p-4 w-full md:w-80 flex gap-2">
            <div class="bg-tosca rounded-full px-2.5 py-2 w-max flex justify-center items-center">
                <img width="36px" src="{{ asset('assets/donate.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs md:text-sm">Total Aksi</p>
                <p class="font-bold text-xl md:text-2xl">{{ $donations_sum }} Aksi</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 w-full md:w-80 flex gap-2">
            <div class="bg-tosca rounded-full px-2 pb-3 w-max flex justify-center items-center">
                <img width="40px" src="{{ asset('assets/food.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs md:text-sm">Total Makanan</p>
                <p class="font-bold text-xl md:text-2xl">{{ $foods }} Kg</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 w-full md:w-80 flex gap-2">
            <div class="bg-tosca rounded-full p-2 w-max flex justify-center items-center">
                <img width="36px" src="{{ asset('assets/people.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs md:text-sm">Total Heroes</p>
                <p class="font-bold text-xl md:text-2xl">{{ $heroes }} Orang</p>
            </div>
        </div>
    </div>
@endsection
