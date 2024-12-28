@extends('layouts.main')
@section('container')
    <div class="flex justify-between flex-wrap-reverse flex-row gap-y-3">
        <div class="flex md:w-max w-full justify-start gap-2">
            <div class="text-start">
                <p>Divisi : {{ $user->division()->name }} ({{ $user->role }})</p>
                <p>{{ $user->point }} Aksi</p>
            </div>

        </div>
        <a href="{{ route('volunteer.show', $user->id) }}" class="flex md:w-max w-full justify-end gap-2">
            <div class="text-end">
                <p>{{ $user->name }}</p>
                <p>{{ $user->program()->name }}</p>
            </div>
            <div class="w-12 block rounded-full overflow-hidden">
                <img src="{{ $user->photo ? Storage::url($user->photo) : asset('assets/default_avatar.png') }}"
                    alt="">
            </div>

        </a>

    </div>
    <div class="flex flex-row justify-center md:justify-between flex-wrap gap-6 mt-3">
        <div class="bg-white rounded-lg shadow-md p-4 w-full md:w-60 flex gap-2">
            <div class="bg-tosca rounded-full px-2.5 py-2 w-max flex justify-center items-center">
                <img width="36px" src="{{ asset('assets/donate.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs md:text-sm">Total Aksi</p>
                <p class="font-bold text-xl md:text-2xl">{{ $donations->count() }} Aksi</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 w-full md:w-60 flex gap-2">
            <div class="bg-tosca rounded-full px-2 pb-3 w-max flex justify-center items-center">
                <img width="40px" src="{{ asset('assets/food.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs md:text-sm">Total Makanan</p>
                <p class="font-bold text-xl md:text-2xl">{{ round($foods->sum('weight') / 1000) }} Kg</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 w-full md:w-60 flex gap-2">
            <div class="bg-tosca rounded-full p-2 w-max flex justify-center items-center">
                <img width="36px" src="{{ asset('assets/people.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs md:text-sm">Total Heroes</p>
                <p class="font-bold text-xl md:text-2xl">{{ $heroes->count() }} Orang</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 w-full md:w-60 flex gap-2">
            <div class="bg-tosca rounded-full px-2.5 py-2 w-max flex justify-center items-center">
                <img width="36px" src="{{ asset('assets/hero.svg') }}" alt="">
            </div>
            <div>
                <p class="text-slate-600 italic text-xs md:text-sm">Total Volunteer
                    @if ($user->role == 'super')
                        <a class="bg-navy p-1 rounded-md text-white text-xs font-normal"
                            href="{{ route('volunteer.index') }}">Kelola</a>
                    @endif
                </p>
                <p class="font-bold text-xl md:text-2xl">{{ $volunteers->count() }} Orang</p>
            </div>
        </div>
    </div>
    <h1 class="font-bold text-navy text-xl md:text-2xl my-12">Statistik Berdasarkan Fakultas</h1>
    <div>
        <canvas id="myChart" class="h-max"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');

        const labels = [
            @foreach ($faculties as $item)
                "{{ $item->name }}",
            @endforeach
        ];
        const backgroundColors = [
            'rgb(255, 99, 132)', // Red
            'rgb(54, 162, 235)', // Blue
            'rgb(255, 205, 86)', // Yellow
            'rgb(75, 192, 192)', // Aqua
            'rgb(153, 102, 255)', // Purple
            'rgb(255, 159, 64)', // Orange
            'rgb(201, 203, 207)', // Grey
            'rgb(0, 128, 128)', // Teal
            'rgb(128, 0, 128)', // Dark Purple
            'rgb(128, 128, 0)', // Olive
            'rgb(0, 128, 0)', // Dark Green
            'rgb(255, 0, 255)', // Magenta
            'rgb(0, 255, 255)', // Cyan
            'rgb(255, 128, 0)', // Bright Orange
            'rgb(0, 255, 0)', // Bright Green
            'rgb(255, 0, 0)', // Bright Red
            'rgb(0, 0, 255)', // Bright Blue
            'rgb(192, 192, 192)', // Silver
            'rgb(128, 0, 0)', // Dark Red
            'rgb(0, 0, 128)', // Dark Blue
            'rgb(64, 224, 208)', // Turquoise
            'rgb(255, 215, 0)' // Gold
        ];
        const data = [
            @foreach ($faculties as $item)
                {{ $item->heroes->count() }},
            @endforeach
        ];

        const combinedData = labels.map((label, index) => ({
            label: label,
            value: data[index],
            color: backgroundColors[index]
        }));
        combinedData.sort((a, b) => b.value - a.value);

        // Pisahkan kembali menjadi label, data, dan warna
        const sortedLabels = combinedData.map(item => item.label);
        const sortedData = combinedData.map(item => item.value);
        const sortedColors = combinedData.map(item => item.color);

        new Chart(ctx, {
            type: window.innerWidth < 768 ? 'doughnut' : 'bar',
            options: {
                indexAxis: "y",
                plugins: {
                    tooltip: {
                        enabled: true
                    },
                },
                scales: {
                    y: {

                        beginAtZero: true
                    }
                },

            },
            plugins: [{
                id: 'displayNumbersInside',
                afterDatasetsDraw: function(chart) {
                    const ctx = chart.ctx;
                    chart.data.datasets.forEach((dataset, index) => {
                        const meta = chart.getDatasetMeta(index);
                        meta.data.forEach((bar, i) => {
                            const value = dataset.data[i];
                            const xPos = bar.x + 20; // Position inside bar
                            const yPos = bar.y + 2;
                            ctx.fillStyle = '#000000'; // Text color
                            ctx.font = '13px Outfit'; // Font style
                            ctx.textAlign = 'right'; // Text alignment
                            ctx.fillText(value, xPos, yPos);
                        });
                    });
                }
            }],
            data: {
                labels: sortedLabels,
                datasets: [{
                    axis: 'y',
                    label: 'Heroes Fakultas',
                    data: sortedData,
                    barThickness: 20,
                    borderRadius: window.innerWidth < 768 ? 0 : 5,
                    backgroundColor: sortedColors,
                    borderWidth: 1,
                    hoverOffset: 4
                }]
            },
        });
    </script>
@endsection
