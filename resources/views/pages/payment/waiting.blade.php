@extends('layouts.form')
@section('container')
    @if (env('MIDTRANS_IS_PRODUCTION'))
        <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    @else
        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    @endif
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
