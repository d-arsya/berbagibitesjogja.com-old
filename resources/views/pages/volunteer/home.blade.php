@extends('layouts.main')
@section('container')
    <div class="flex justify-between flex-wrap-reverse flex-row gap-y-3">
        <div class="flex md:w-max w-full justify-start gap-2">
            <div class="text-start">
                <p>Divisi : {{ $user->division->name }}</p>
                <p>{{ $user->attendances->count() }} Aksi</p>
            </div>

        </div>
        <a href="{{ route('volunteer.show', $user->id) }}" class="flex md:w-max w-full justify-end gap-2">
            <div class="text-end">
                <p>{{ $user->name }}</p>
                <p>{{ $user->faculty->name }} ({{ $user->faculty->university->name }})</p>
            </div>
            <div class="w-12 block rounded-full overflow-hidden">
                <img src="{{ $user->photo}}"
                    alt="">
            </div>

        </a>

    </div>
    <div class="flex justify-start flex-row gap-y-3 my-4 gap-x-4 flex-wrap">
        @if ($user->role=='super' || $user->division->name=='PSDM')
        <a class="bg-navy hover:shadow-xl hover:bg-navy-600 py-1 px-6 text-white rounded-md" href="{{ route('precence.index') }}">Presensi</a>
        @endif
        @if ($user->role!='member')
            
        <a class="bg-navy hover:shadow-xl hover:bg-navy-600 py-1 px-6 text-white rounded-md" href="{{ route('volunteer.index') }}">Volunteer</a>
        @endif
        @if ($precence==1)
        <a class="bg-navy hover:shadow-xl hover:bg-navy-600 py-1 px-6 text-white rounded-md" href="{{ route('precence.qr', 'scan') }}">Scan QR</a>
     
                <a href="{{ route('precence.qr', 'view') }}"
                    class="bg-navy-500 hover:bg-navy-600 p-2 text-white rounded-md shadow-md">
                    Lihat QR Code
                </a>
            
        @endif
    </div>
    <div class="grid sm:grid-cols-2 md:grid-cols-4 grid-cols-1 gap-2 mt-3">
        <div class="bg-white rounded-lg shadow-md p-4 w-full flex gap-2">
            <div class="bg-tosca rounded-full px-2.5 py-2 w-max flex justify-center items-center">
                <img width="36px" src="{{ asset('assets/donate.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs sm:text-xs md:text-sm">Food Rescue</p>
                <p class="font-bold text-md sm:text-lg md:text-xl">{{ $donations->count() }} Aksi</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 w-full flex gap-2">
            <div class="bg-tosca rounded-full px-2 pb-3 w-max flex justify-center items-center">
                <img width="40px" src="{{ asset('assets/food.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs sm:text-xs md:text-sm">Food Rescue</p>
                <p class="font-bold text-md sm:text-lg md:text-xl">{{ round($foods->sum('weight') / 1000) }} Kg</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 w-full flex gap-2">
            <div class="bg-tosca rounded-full px-2.5 py-2 w-max flex justify-center items-center">
                <img width="36px" src="{{ asset('assets/donate.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs sm:text-xs md:text-sm">Food Donate</p>
                <p class="font-bold text-md sm:text-lg md:text-xl">{{ $donationsCharity->count() }} Aksi</p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 w-full flex gap-2">
            <div class="bg-tosca rounded-full px-2 pb-3 w-max flex justify-center items-center">
                <img width="40px" src="{{ asset('assets/food.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs sm:text-xs md:text-sm">Food Donate</p>
                <p class="font-bold text-md sm:text-lg md:text-xl">{{ round($foodsCharity->sum('weight') / 1000) }} Kg</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 w-full flex gap-2">
            <div class="bg-tosca rounded-full p-2 w-max flex justify-center items-center">
                <img width="36px" src="{{ asset('assets/people.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs sm:text-xs md:text-sm">Total Heroes</p>
                <p class="font-bold text-md sm:text-lg md:text-xl">{{ $heroes->sum('quantity') }} Orang</p>
            </div>
        </div>
        {{-- <div class="bg-white rounded-lg shadow-md p-4 w-full flex gap-2">
            <div class="bg-tosca rounded-full px-2.5 py-2 w-max flex justify-center items-center">
                <img width="36px" src="{{ asset('assets/hero.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs sm:text-xs md:text-sm">Total Volunteer
                    
                </p>
                <p class="font-bold text-md sm:text-lg md:text-xl">{{ $volunteers->count() }} Orang</p>
            </div>
        </div> --}}
    </div>
    {{-- <h1 class="font-bold text-navy text-xl md:text-2xl my-12">Statistik Berdasarkan Fakultas</h1>
    <div>
        <canvas id="facultyStatictics" class="h-max"></canvas>
    </div> --}}
    <h1 class="font-bold text-navy text-xl md:text-2xl my-12">Statistik Heroes {{ count($lastData) }} Bulan Terakhir</h1>
    <div>
        <canvas id="heroStatistics" class="h-max"></canvas>
    </div>
    <h1 class="font-bold text-navy text-xl md:text-2xl my-12">Statistik Makanan {{ count($lastData) }} Bulan Terakhir</h1>
    <div>
        <canvas id="foodStatistics" class="h-max"></canvas>
    </div>
    <h1 class="font-bold text-navy text-xl md:text-2xl my-12">Statistik Heroes {{ count($lastData) }} Bulan Terakhir (Donasi)</h1>
    <div>
        <canvas id="heroStatisticsCharity" class="h-max"></canvas>
    </div>
    <h1 class="font-bold text-navy text-xl md:text-2xl my-12">Statistik Makanan {{ count($lastData) }} Bulan Terakhir (Donasi)</h1>
    <div>
        <canvas id="foodStatisticsCharity" class="h-max"></canvas>
    </div>
    {{-- @foreach ($faculties as $item)
    "{{ $item->name }}",
@endforeach --}}
{{-- @foreach ($faculties as $item)
{{ $item->heroes->sum('quantity') }},
@endforeach --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // const facultyStatictics = document.getElementById('facultyStatictics');
        const heroStatistics = document.getElementById('heroStatistics');
        const foodStatistics = document.getElementById('foodStatistics');
        const heroStatisticsCharity = document.getElementById('heroStatisticsCharity');
        const foodStatisticsCharity = document.getElementById('foodStatisticsCharity');

//         const labels = [
            
//         ];
//         const backgroundColors = [
//   "rgb(255, 255, 255)", "rgb(20, 20, 20)", "rgb(255, 255, 0)", "rgb(0, 255, 255)", "rgb(255, 0, 255)",
//   "rgb(255, 128, 0)", "rgb(0, 128, 128)", "rgb(0, 255, 0)", "rgb(128, 0, 128)", "rgb(255, 0, 0)",
//   "rgb(128, 255, 0)", "rgb(0, 128, 255)", "rgb(255, 64, 0)", "rgb(255, 64, 128)", "rgb(64, 128, 255)",
//   "rgb(255, 192, 0)", "rgb(0, 192, 192)", "rgb(0, 64, 0)", "rgb(128, 0, 255)", "rgb(255, 200, 150)", "rgb(64, 64, 64)"
// ];
//         const data = [

//         ];

//         const combinedData = labels.map((label, index) => ({
//             label: label,
//             value: data[index],
//             color: backgroundColors[index]
//         }));
//         combinedData.sort((a, b) => b.value - a.value);
        
//         const sortedLabels = combinedData.map(item => item.label);
//         const sortedData = combinedData.map(item => item.value);
//         const sortedColors = combinedData.map(item => item.color);

//         new Chart(facultyStatictics, {
//             type: window.innerWidth < 768 ? 'doughnut' : 'bar',
//             options: {
//                 indexAxis: "y",
//                 plugins: {
//                     tooltip: {
//                         enabled: true
//                     },
//                 },
//                 scales: {
//                     y: {

//                         beginAtZero: true
//                     }
//                 },

//             },
//             plugins: [{
//                 id: 'displayNumbersInside',
//                 afterDatasetsDraw: function(chart) {
//                     const ctx = chart.ctx;
//                     chart.data.datasets.forEach((dataset, index) => {
//                         const meta = chart.getDatasetMeta(index);
//                         meta.data.forEach((bar, i) => {
//                             const value = dataset.data[i];
//                             const xPos = bar.x + 20; // Position inside bar
//                             const yPos = bar.y + 2;
//                             ctx.fillStyle = '#000000'; // Text color
//                             ctx.font = '13px Outfit'; // Font style
//                             ctx.textAlign = 'right'; // Text alignment
//                             ctx.fillText(value, xPos, yPos);
//                         });
//                     });
//                 }
//             }],
//             data: {
//                 labels: sortedLabels,
//                 datasets: [{
//                     axis: 'y',
//                     label: 'Heroes Fakultas',
//                     data: sortedData,
//                     barThickness: 20,
//                     borderRadius: window.innerWidth < 768 ? 0 : 5,
//                     backgroundColor: window.innerWidth < 768 ?sortedColors:['#21568A'],
//                     borderWidth: 1,
//                     hoverOffset: 4
//                 }]
//             },
//         });
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
        let monthNameCharity = `{{!! json_encode(array_column($lastDataCharity, 'bulan')) !!}}`
        let heroSumCharity = `{{!! json_encode(array_column($lastDataCharity, 'heroes')) !!}}`
        let foodSumCharity = `{{!! json_encode(array_column($lastDataCharity, 'foods')) !!}}`
        monthNameCharity = JSON.parse(monthNameCharity.replace('{','').replace('}',''))
        heroSumCharity = JSON.parse(heroSumCharity.replace('{','').replace('}',''))
        foodSumCharity = JSON.parse(foodSumCharity.replace('{','').replace('}',''))
        new Chart(foodStatisticsCharity, {
            type: 'line',
            data: {
                labels: monthNameCharity,
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
        new Chart(heroStatisticsCharity, {
            type: 'line',
            data: {
                labels: monthNameCharity,
                datasets: [{
                    label: 'Penerima',
                    data: heroSumCharity,
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
