@extends('layouts.main')
@section('container')
    <a class="bg-orange-400 hover:bg-orange-600 shadow-md px-5 py-2.5 rounded-md text-white"
        href="{{ route('report.index') }}">
        < Kembali</a>
            <a class="bg-red-600 hover:bg-red-800 shadow-md px-5 py-2.5 rounded-md text-white"
                href="{{ route('report.clean') }}">
                Hapus Semua File Laporan</a>
            <div class="flex flex-row flex-wrap gap-2 mt-12">
                @foreach ($reportFiles as $item)
                    <a class="text-white bg-tosca rounded-md py-2 px-3 hover:bg-tosca-700" download="{{ $item }}"
                        href="/reports/{{ $item }}">{{ $item }}</a>
                @endforeach

            </div>
        @endsection
