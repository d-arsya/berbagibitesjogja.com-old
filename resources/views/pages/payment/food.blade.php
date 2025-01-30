@php
    use Carbon\Carbon;
@endphp
@extends('layouts.form')
@section('container')
<div class="mt-5 max-w-md mx-auto bg-navy p-5 text-center text-white font-bold rounded-t-md">
        Food Surplus
    </div>
    <form method="POST" action="{{ route('payment.foodStore') }}" class="max-w-md mx-auto shadow-md px-10  py-6 rounded-b-md">
        @csrf
        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
        <select id="sponsor" name="variant"
            class="mb-5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <option value="">Individu / Perusahaan</option>
            <option value="individual">Individu</option>
            <option value="company">Perusahaan</option>
        </select>
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
        <div class="relative z-0 w-full mt-8 group">
            <input type="text" name="location" id="location"
                class="block py-2.5 px-2.5 w-full text-sm text-gray-900 bg-transparent border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" " name="location" required />
            <label for="location"
                class="peer-focus:font-medium absolute text-md text-gray-700 duration-300 transform -translate-y-8 scale-75 top-2 left-6 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Alamat Pengambilan</label>
        </div>
        <div class="relative z-0 w-full mt-8 group">
            <input type="number" name="quota" id="quota"
                class="block py-2.5 px-2.5 w-full text-sm text-gray-900 bg-transparent border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" " name="quota" required />
            <label for="quota"
                class="peer-focus:font-medium absolute text-md text-gray-700 duration-300 transform -translate-y-8 scale-75 top-2 left-6 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Porsi Makanan</label>
        </div>
        <div class="relative z-0 w-full mt-8 group">
            <input type="text" name="description" id="description"
                class="block py-2.5 px-2.5 w-full text-sm text-gray-900 bg-transparent border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" " name="description" required />
            <label for="description"
                class="peer-focus:font-medium absolute text-md text-gray-700 duration-300 transform -translate-y-8 scale-75 top-2 left-6 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8">Deskripsi Makanan</label>
        </div>
        <button type="submit"
            class="mt-6 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
    </form>
    <h1 class="font-bold text-navy text-xl md:text-2xl my-12">BBJ Dalam Data</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-3">
        <div class="bg-white rounded-lg shadow-md p-4 w-full md:w-full flex gap-2">
            <div class="bg-tosca rounded-full px-2.5 py-2 flex justify-center items-center aspect-square">
                <img width="36px" src="{{ asset('assets/donate.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs sm:text-xs md:text-sm">Total Aksi</p>
                <p class="font-bold text-lg sm:text-lg md:text-2xl">{{ $donations_sum }} Aksi</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 w-full md:w-full flex gap-2">
            <div class="bg-tosca rounded-full px-2 pb-3 flex justify-center items-center aspect-square">
                <img width="40px" src="{{ asset('assets/food.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs sm:text-xs md:text-sm">Total Makanan</p>
                <p class="font-bold text-lg sm:text-lg md:text-2xl">{{ $foods }} Kg</p>
            </div>
        </div>
        <div class="bg-white rounded-lg col-span-1 sm:col-span-2 md:col-span-1 shadow-md p-4 w-full md:w-full flex gap-2">
            <div class="bg-tosca rounded-full p-2 flex justify-center items-center aspect-square">
                <img width="36px" src="{{ asset('assets/people.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs sm:text-xs md:text-sm">Total Heroes</p>
                <p class="font-bold text-lg sm:text-lg md:text-2xl">{{ $heroes }} Orang</p>
            </div>
        </div>
    </div>

    <main class="bg-gray-100 bg-opacity-25 mt-12">
        <div class="mb-8">
            <header class="flex flex-wrap items-center p-4 md:py-8">
                <div class="md:w-3/12 md:ml-16">
                    <img class="w-20 h-20 md:w-40 md:h-40 object-cover rounded-full
                     p-1"
                        src="{{ $ig_user['profile_picture_url'] }}" alt="profile">
                </div>
                <div class="w-8/12 md:w-7/12 ml-4">
                    <div class="md:flex md:flex-wrap md:items-center mb-4">
                        <h2 class="text-3xl inline-block font-light md:mr-2 mb-2 sm:mb-0">
                            {{ $ig_user['username'] }}
                        </h2>
                        </span>
                        <a href="https://www.instagram.com/berbagibitesjogja"
                            class="bg-blue-500 px-2 py-1 
                        text-white font-semibold text-sm rounded block text-center 
                        sm:inline-block block">Follow</a>
                    </div>
                    <ul class="hidden md:flex space-x-8 mb-4">
                        <li>
                            <span class="font-semibold">{{ $ig_user['media_count'] }}</span>
                            posts
                        </li>

                        <li>
                            <span class="font-semibold">{{ $ig_user['followers_count'] }}</span>
                            followers
                        </li>
                        <li>
                            <span class="font-semibold">{{ $ig_user['follows_count'] }}</span>
                            following
                        </li>
                    </ul>
                    <div class="hidden md:block">
                        <h1 class="font-semibold">{{ $ig_user['name'] }}</h1>
                        <span>{{ $ig_user['biography'] }}</span>
                    </div>

                </div>
                <div class="md:hidden text-sm my-2">
                    <h1 class="font-semibold">{{ $ig_user['name'] }}</h1>
                    <span>{{ $ig_user['biography'] }}</span>
                </div>

            </header>
            <div class="px-px md:px-3">
                <ul
                    class="flex md:hidden justify-around space-x-8 border-t 
                text-center p-2 text-gray-600 leading-snug text-sm">
                    <li>
                        <span class="font-semibold text-gray-800 block">{{ $ig_user['media_count'] }}</span>
                        posts
                    </li>

                    <li>
                        <span class="font-semibold text-gray-800 block">{{ $ig_user['followers_count'] }}</span>
                        followers
                    </li>
                    <li>
                        <span class="font-semibold text-gray-800 block">{{ $ig_user['follows_count'] }}</span>
                        following
                    </li>
                </ul>
                <div class="flex flex-wrap -mx-px md:-mx-3">

                </div>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-2 my-3">
            @foreach ($ig_media as $item)
                <a href="{{ $item['permalink'] }}">
                    @if ($item['media_type'] == 'VIDEO')
                        <img class="aspect-square object-cover" src="{{ $item['thumbnail_url'] }}" alt="">
                    @else
                        <img class="aspect-square object-cover" src="{{ $item['media_url'] }}" alt="">
                    @endif

                </a>
            @endforeach

        </div>
        <h1 class="font-bold text-navy text-xl md:text-2xl my-12">Statistik Heroes {{ count($lastData) }} Bulan Terakhir
        <div>
            <canvas id="heroStatistics" class="h-max"></canvas>
        </div>
        <h1 class="font-bold text-navy text-xl md:text-2xl my-12">Statistik Makanan {{ count($lastData) }} Bulan Terakhir
        </h1>
        <div>
            <canvas id="foodStatistics" class="h-max"></canvas>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const university = document.querySelectorAll('.university')
        university.forEach(function(target, index) {
            target.addEventListener('change', function(e) {
                const id = e.target.value
                const faculty = document.querySelectorAll('.facultyInput')[index]
                console.log(faculty)
                faculty.classList.remove('hidden')
                faculty.innerHTML = `<option value="">Fakultas/Bagian</option>`
                fetch(`/api/university/${id}/faculty`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(function(e) {
                            faculty.innerHTML += `<option value="${e.id}">${e.name}</option>`
                        })
                    })
                    .catch(error => console.error('Error:', error));
            })

        })
        const heroStatistics = document.getElementById('heroStatistics');
        const foodStatistics = document.getElementById('foodStatistics');

        let monthName = `{{!! json_encode(array_column($lastData, 'bulan')) !!}}`
        let heroSum = `{{!! json_encode(array_column($lastData, 'heroes')) !!}}`
        let foodSum = `{{!! json_encode(array_column($lastData, 'foods')) !!}}`
        monthName = JSON.parse(monthName.replace('{','').replace('}',''))
        heroSum = JSON.parse(heroSum.replace('{','').replace('}',''))
        foodSum = JSON.parse(foodSum.replace('{','').replace('}',''))
        new Chart(foodStatistics, {
            type: 'line',
            data: {
                labels: monthName,
                datasets: [{
                    label: 'Surplus Food (kg)',
                    data: foodSum,
                    fill: true,
                    borderColor: '#21568A',
                    tension: 0.25,
                    borderWidth:2.5,
                    backgroundColor:'rgba(33, 86, 138, 0.1)'
                }]
            }
        })
        new Chart(heroStatistics, {
            type: 'line',
            data: {
                labels: monthName,
                datasets: [{
                    label: 'Penerima',
                    data: heroSum,
                    fill: true,
                    borderColor: '#0395AF',
                    tension: 0.25,
                    borderWidth:2.5,
                    backgroundColor:'rgba(3, 149, 175, 0.1)'
                }]
            }
        })
    </script>
@endsection
