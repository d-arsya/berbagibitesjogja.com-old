@extends('layouts.main')
@section('container')
    <form action="{{ route('report.download') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-12 md:gap-y-1 md:gap-x-12">
            <div>
                <div class="bg-white shadow-lg rounded-md p-6">
                    <select
                        class="w-full text-slate-600 mt-8 p-2.5 bg-transparent border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600"
                        placeholder="Nomor Whatsapp" required name="sponsor_id" id="sponsorId">
                        <option value="">Partner</option>
                        @foreach ($sponsors as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>

                    <div class="flex flex-row gap-2 items-center mt-6">
                        <input type="date" name="startDate" id="startDate"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <p>s/d</p>
                        <input type="date" name="endDate" id="endDate"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                    </div>
                    <div onclick="searchData()"
                        class="w-full rounded-md shadow-md bg-navy text-center text-white mt-12 py-2 hover:bg-navy-700">Cari
                        Data</div>
                </div>
                <div class="flex items-center flex-col mt-6 hidden" id="loading">
                    <h1>Mengambil data</h1>
                    <div role="status" class="mt-5">
                        <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="currentColor" />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentFill" />
                        </svg>
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div class="flex flex-row flex-wrap gap-3 mt-6 hidden" id="resultSum">
                    <h1 class="bg-tosca py-2 px-6 rounded-md w-max text-white font-semibold"><span id="actionSum">1</span>
                        Aksi
                    </h1>
                    <h1 class="bg-tosca py-2 px-6 rounded-md w-max text-white font-semibold"><span id="receiverSum">1</span>
                        Penerima</h1>
                    <h1 class="bg-tosca py-2 px-6 rounded-md w-max text-white font-semibold"><span id="foodsSum">1</span>
                        kg
                        makanan</h1>
                    <h1 class="bg-tosca py-2 px-6 rounded-md w-max text-white font-semibold"><span id="variantSum">1</span>
                        Jenis makanan</h1>

                </div>
            </div>
            <div id="donationContainer" class="overflow-scroll h-96">
            </div>
        </div>
        <button id="downloadButton" class="bg-navy w-full rounded-md py-2 text-white hover:bg-navy-700 hidden mt-12"
            type="submit">Download Laporan</button>
    </form>
    <script>
        const loading = document.querySelector("#loading")
        const downloadButton = document.querySelector("#downloadButton")
        const resultSum = document.querySelector("#resultSum")
        const sponsorId = document.querySelector("#sponsorId")
        const startDate = document.querySelector("#startDate")
        const donationContainer = document.querySelector("#donationContainer")
        const endDate = document.querySelector("#endDate")

        async function searchData() {
            donationContainer.innerHTML = ""
            loading.classList.remove('hidden')
            resultSum.classList.add('hidden')
            downloadButton.classList.add('hidden')
            const data = await fetch(`/api/sponsor/${sponsorId.value}/${startDate.value}/${endDate.value}`)
                .then(res => res.json())
                .then(res => {
                    $result = document.querySelectorAll('#resultSum span')
                    $result[0].innerHTML = res.totalAction
                    $result[1].innerHTML = res.totalHero
                    $result[2].innerHTML = Math.round(res.totalWeight / 1000)
                    $result[3].innerHTML = res.totalFood
                    loading.classList.add('hidden')
                    resultSum.classList.remove('hidden')
                    downloadButton.classList.remove('hidden')
                    return res.donations
                })
            data.forEach(e => {
                donationContainer.innerHTML += `<div class="bg-navy mb-6 rounded-md p-6 text-white shadow-md">
                    <h1 class="float-end bg-white rounded-md text-black py-1 px-2 text-xs">${e.take}</h1>
                    <div class="flex flex-row flex-wrap gap-2">
                        <h1 class="bg-tosca text-white py-1 px-3 rounded-md">${e.heroQuantity} Penerima</h1>
                        <h1 class="bg-tosca text-white py-1 px-3 rounded-md">${Math.round(e.foodWeight/1000)} kg makanan</h1>
                        <h1 class="bg-tosca text-white py-1 px-3 rounded-md">${e.foodQuantity} jenis makanan</h1>

                    </div>
                    <input type="text"
                        class="mt-6 block py-2.5 px-2 w-full text-sm text-black bg-white rounded-md border-0 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600"
                        placeholder="Penerima" name="receiver-${e.id}" required />
                    <input type="text"
                        class="mt-6 block py-2.5 px-2 w-full text-sm text-black bg-white rounded-md border-0 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600"
                        placeholder="Jabatan" name="role-${e.id}" required />
                </div>`
            });
        }
    </script>
@endsection
