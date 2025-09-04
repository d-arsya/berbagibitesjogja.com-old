@extends('layouts.main')
@section('container')
    <div class="flex justify-between flex-wrap-reverse flex-row gap-y-3">
        <div class="flex md:w-max w-full justify-start gap-2">
            <div class="text-start">
            </div>

        </div>
        <a href="{{ route('volunteer.show', $user->id) }}" class="flex md:w-max w-full justify-end gap-2">
            <div class="text-end">
            </div>
            <div class="w-12 block rounded-full overflow-hidden">
            </div>

        </a>

    </div>
    <div class="grid sm:grid-cols-2 md:grid-cols-3 grid-cols-1 gap-2 mt-3">
        <div class="bg-white rounded-lg shadow-md p-4 w-full flex gap-2">
            <div class="bg-tosca rounded-full px-2.5 py-2 w-max flex justify-center items-center">
                <img width="36px" src="{{ asset('assets/donate.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs sm:text-xs md:text-sm">Total Aksi</p>
                <p class="font-bold text-md sm:text-lg md:text-xl">{{ $donations->count() }} Aksi</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 w-full flex gap-2">
            <div class="bg-tosca rounded-full px-2 pb-3 w-max flex justify-center items-center">
                <img width="40px" src="{{ asset('assets/food.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs sm:text-xs md:text-sm">Total Makanan</p>
                <p class="font-bold text-md sm:text-lg md:text-xl">{{ round($foods->sum('weight') / 1000) }} Kg</p>
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
        
    </div>
    <h1 class="font-bold text-navy text-xl md:text-2xl my-12">Statistik Heroes {{ count($lastData) }} Bulan Terakhir</h1>
    <div>
        <canvas id="heroStatistics" class="h-max"></canvas>
    </div>
    <h1 class="font-bold text-navy text-xl md:text-2xl my-12">Statistik Makanan {{ count($lastData) }} Bulan Terakhir</h1>
    <div>
        <canvas id="foodStatistics" class="h-max"></canvas>
    </div>
    
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
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
