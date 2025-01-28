@extends('layouts.form')
@section('container')
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <div class="max-w-lg mx-auto mt-6">
        <div class="w-full" id="snap-container">
        </div>
    </div>
    <script>
        window.snap.embed("{{ $snapToken }}", {
            embedId: 'snap-container',
        });
    </script>
@endsection
