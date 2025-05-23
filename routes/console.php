<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\BotController;
use App\Models\Donation\Donation;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

Schedule::command('que:work --stop-when-empty')->timezone('Asia/Jakarta')->everyFiveSeconds();
Schedule::call(function () {
    $donations = Donation::where('reported', null)->where('status', 'selesai')->get();
    foreach ($donations as $donation) {
        try {
            $fileName = ReportController::createReport($donation);
            $relativePath = storage_path('app/public/reports/') . $fileName[1];
            Storage::disk('google')->put('foods/BA PEMKOT/' . $fileName[0] . "/" . $fileName[1], File::get($relativePath));
            BotController::sendForPublic('120363315008311976@g.us', "Berhasil membuat laporan donasi hari ini\n\n" . "Nama File : " . $fileName[1] . "\nUkuran file : " . round(filesize($relativePath) / 1024) . " kb", 'SECOND');
            File::delete($relativePath);
            $donation->reported = "sudah";
            $donation->save();
        } catch (\Throwable $th) {
            BotController::sendForPublic('120363399651067268@g.us', $th->getMessage(), 'SECOND');
        }
    }
})->timezone('Asia/Jakarta')->dailyAt('23.00');
