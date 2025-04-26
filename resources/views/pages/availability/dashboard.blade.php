@extends('layouts.main')
@php
    $days = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
@endphp
@section('container')
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="hidden md:block">
            <h1 class="text-center text-xl font-bold mb-6">Kesediaan Kamu</h1>
            <div class="grid grid-cols-8 items-center justify-center place-items-center gap-y-2 overflow-scroll">
                <div></div>
                @for ($i = 1; $i <= 7; $i++)
                    <div>
                        {{ $days[$i] }}
                    </div>
                @endfor
                @for ($i = 1; $i <= 15; $i++)
                    <div class="self-start">
                        {{ $i + 6 }} .00
                    </div>
                    @for ($j = 1; $j <= 7; $j++)
                        @php
                            $cod = array_shift($code);
                            $checked = in_array($cod, $availabilities);
                        @endphp
                        <input {{ $checked ? 'checked' : '' }} type="checkbox" value="{{ $cod }}" name=""
                            id="">
                    @endfor
                    <div class="self-start">
                        {{ $i + 6 }} .30
                    </div>
                    @for ($j = 1; $j <= 7; $j++)
                        @php
                            $cod = array_shift($code);
                            $checked = in_array($cod, $availabilities);
                        @endphp
                        <input {{ $checked ? 'checked' : '' }} type="checkbox" value="{{ $cod }}" name=""
                            id="">
                    @endfor
                @endfor
            </div>

        </div>
        <div>
            <h1 class="text-center text-xl font-bold mb-6">Kesediaan Volunteer</h1>
            <div id="accordion-collapse" data-accordion="collapse" class="mb-6">
                <h2 id="accordion-collapse-heading-1">
                    <button type="button"
                        class="flex items-center justify-between w-full p-5 font-medium text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 hover:bg-gray-100"
                        data-accordion-target="#accordion-collapse-body-1" aria-expanded="true"
                        aria-controls="accordion-collapse-body-1">
                        <span>{{ $days[1] }}</span>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5 5 1 1 5" />
                        </svg>
                    </button>
                </h2>
                <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
                    <div class="p-5 border border-b-0 border-gray-200 flex gap-1 flex-row flex-wrap">
                        @foreach ($avail->get(1) as $item)
                            <a href="{{ route('availability.time', $item->first()->code) }}"
                                class="bg-blue-100 text-blue-800 text-md font-medium px-3 py-1 rounded-sm">{{ $item->first()->hour }}.{{ str_pad($item->first()->minute, 2, '0') }}
                                ({{ $item->count() }})
                            </a>
                        @endforeach
                    </div>
                </div>
                @for ($day = 2; $day <= 7; $day++)
                    <h2 id="accordion-collapse-heading-{{ $day }}">
                        <button type="button"
                            class="flex items-center justify-between w-full p-5 font-medium text-gray-500 border border-b-0 border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 hover:bg-gray-100"
                            data-accordion-target="#accordion-collapse-body-{{ $day }}" aria-expanded="false"
                            aria-controls="accordion-collapse-body-2">
                            <span>{{ $days[$day] }}</span>
                            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5 5 1 1 5" />
                            </svg>
                        </button>
                    </h2>
                    <div id="accordion-collapse-body-{{ $day }}" class="hidden"
                        aria-labelledby="accordion-collapse-heading-{{ $day }}">
                        <div class="p-5 border border-b-0 border-gray-200 flex gap-1 flex-row flex-wrap">
                            @foreach ($avail->get($day) as $item)
                                <a href="{{ route('availability.time', $item->first()->code) }}"
                                    class="bg-blue-100 text-blue-800 text-md font-medium px-3 py-1 rounded-sm">{{ $item->first()->hour }}.{{ str_pad($item->first()->minute, 2, '0') }}
                                    ({{ $item->count() }})
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endfor
            </div>
            <a href="{{ route('availability.dashboard') }}" class="hidden bg-navy text-white rounded-md py-1 px-2">Atur
                Kesediaan</a>
        </div>
    </div>
    <script>
        document.querySelectorAll("input[type=checkbox]").forEach(function(d) {
            d.addEventListener('change', function(e) {
                fetch(`availability/${e.target.value}/${e.target.checked}`)
                    .then(res => res.json())
                    .then(res => console.log(res))
            })
        })
    </script>
@endsection
