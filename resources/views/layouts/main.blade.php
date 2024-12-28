@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>{{ $active ?? '' ? "$active |" : '' }}Berbagi Bites Jogja</title>

</head>

<body>
    @include('components.header')
    <main class="md:px-44 px-6 py-10">
        @yield('container')
    </main>
    @include('components.footer')

</body>

</html>
