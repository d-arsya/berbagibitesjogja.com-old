<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('que:work --stop-when-empty')->timezone('Asia/Jakarta')->everyFiveSeconds();
Schedule::command('calendar:send-notification')->timezone('Asia/Jakarta')->dailyAt('21.00')->name('Kirim notifikasi acara');
Schedule::command('report:daily')->timezone('Asia/Jakarta')->dailyAt('23.00')->name('Buat report');
Schedule::command('clear:backup')->timezone('Asia/Jakarta')->dailyAt('20.00')->name('Hapus backup');
