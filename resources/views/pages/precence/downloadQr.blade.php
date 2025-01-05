@extends('layouts.main')
@section('container')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (array_keys(request()->query())[0] == 'download')
        Otomatis kembali ke halaman awal
        <script type="module">
            import "https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js";
            const data =
                "{{ env('APP_URL', 'https://heroes.berbagibitesjogja.site') }}/heroes/abcent/attendance?datat={{ $precence->latitude }}!{{ $precence->code }}!{{ $precence->longitude }}";

            const qrCode = new QRCodeStyling({
                width: 1000,
                height: 1000,
                type: "svg",
                data: data,
                image: "https://media.berbagibitesjogja.site/logo_transparan.png",
                dotsOptions: {
                    color: "#1A446D",
                    type: "rounded",
                },
                backgroundOptions: {
                    color: "#FFFFFF",
                },
                imageOptions: {
                    crossOrigin: "anonymous",
                    margin: 5,
                },
            });
            qrCode.download({
                name: "QR Code Absensi {{ $precence->created_at }}",
                extension: "png"
            });
            setTimeout(() => {
                window.history.back()

            }, 1000);
        </script>
    @elseif (array_keys(request()->query())[0] == 'view')
        <div class="flex justify-center">
            <div id="canvas"></div>

        </div>
        <script type="module">
            import "https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js";
            const data =
                "{{ env('APP_URL', 'https://heroes.berbagibitesjogja.site') }}/heroes/abcent/attendance?datat={{ $precence->latitude }}!{{ $precence->code }}!{{ $precence->longitude }}";

            const qrCode = new QRCodeStyling({
                width: 300,
                height: 300,
                type: "svg",
                data: data,
                image: "https://media.berbagibitesjogja.site/logo_transparan.png",
                dotsOptions: {
                    color: "#1A446D",
                    type: "rounded",
                },
                backgroundOptions: {
                    color: "#FFFFFF",
                },
                imageOptions: {
                    crossOrigin: "anonymous",
                    margin: 10,
                },
            });
            qrCode.append(document.getElementById("canvas"));
        </script>
    @else
        <div class="flex items-center flex-col hidden" id="loading">
            <h1>Menunggu status presensi</h1>
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
        <div class="flex items-center flex-col hidden" id="success">
            <h1>Presensi Berhasil</h1>
            <div role="status" class="mt-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="animate-ping" width="32" height="32" fill="green"
                    class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="flex items-center flex-col hidden" id="failed">
            <h1>Presensi Gagal</h1>
            <div role="status" class="mt-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="animate-ping"
                    viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <video></video>
        <script type="module">
            import QrScanner from 'https://cdn.jsdelivr.net/npm/qr-scanner@1.4.2/+esm'
            let userLat;
            let userLong;
            navigator.geolocation.getCurrentPosition(res => {
                userLat = res.coords.latitude
                userLong = res.coords.longitude
            })
            const qrScanner = new QrScanner(
                document.querySelector('video'),
                (result) => {
                    document.querySelector('#loading').classList.remove('hidden')
                    qrScanner.stop()
                    let scanned = result.data;
                    const startIndex = scanned.indexOf('datat=') + 6
                    scanned = scanned.substring(startIndex, scanned.length)
                    scanned = scanned.split('!')
                    scanned.push(`${userLat}`, `${userLong}`)
                    const data = {
                        precenceLat: scanned[0],
                        precenceLong: scanned[2],
                        precenceCode: scanned[1],
                        userLat: scanned[3],
                        userLong: scanned[4]
                    }
                    fetch('/abcence/distance', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => {
                            document.querySelector('#loading').classList.add('hidden')
                            if (!response.ok) {
                                document.querySelector('#failed').classList.remove('hidden')
                            } else {
                                document.querySelector('#success').classList.remove('hidden')
                            }
                            setTimeout(() => {
                                window.history.back()
                            }, 800)
                        })
                }, {
                    onDecode: (res) => {
                        console.log()
                    },
                    onDecodeError: (err) => {
                        console.log(err)
                    },
                    maxScansPerSecond: 1,
                    highlightScanRegion: true,
                    highlightCodeOutline: true,
                });
            qrScanner.start()
        </script>
    @endif
@endsection
