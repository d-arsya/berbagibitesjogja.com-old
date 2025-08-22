<x-mail::message>
# Introduction

Halo {{ $name }}, disini kamu sebagai {{ $role }} di Divisi {{ $division }} terimakasih
telah menjadi volunteer BBJ
<br>
Berikut adalah data akunmu

## Email : {{ $email }}
## Password : {{ $password }}
<x-mail::button :url="config('app.url') . '/login'">
    Login
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
