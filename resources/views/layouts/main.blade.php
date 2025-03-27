@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berbagi Bites Jogja</title>
    <meta name="description"
        content="Berbagi Bites Jogja (BBJ) adalah Gerakan food rescue pertama di Jogja. Bergerak mengatasi Food Waste melalui Food Rescue dan Food Bank di wilayah Yogyakarta">

    <meta property="og:url" content="https://heroes.berbagibitesjogja.site/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Berbagi Bites Jogja">
    <meta property="og:description"
        content="Berbagi Bites Jogja (BBJ) adalah Gerakan food rescue pertama di Jogja. Bergerak mengatasi Food Waste melalui Food Rescue dan Food Bank di wilayah Yogyakarta">
    <meta property="og:image"
        content="https://berbagibitesjogja.site/wp-content/uploads/2024/09/1000049401-e1726334186949.jpg">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="heroes.berbagibitesjogja.site">
    <meta property="twitter:url" content="https://heroes.berbagibitesjogja.site/">
    <meta name="twitter:title" content="Berbagi Bites Jogja">
    <meta name="twitter:description"
        content="Berbagi Bites Jogja (BBJ) adalah Gerakan food rescue pertama di Jogja. Bergerak mengatasi Food Waste melalui Food Rescue dan Food Bank di wilayah Yogyakarta">
    <meta name="twitter:image"
        content="https://berbagibitesjogja.site/wp-content/uploads/2024/09/1000049401-e1726334186949.jpg">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>{{ $active ?? '' ? "$active |" : '' }}Berbagi Bites Jogja</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/flowbite.min.js"></script>


</head>

<body>
    @include('components.header')
    <main class="sm:px-24 md:px-44 px-6 py-10">
        @yield('container')
    </main>
    @include('components.footer')
    @session('success')
        @include('components.toast_success')
    @endsession
    @session('error')
        @include('components.toast_error')
    @endsession

</body>

</html>
