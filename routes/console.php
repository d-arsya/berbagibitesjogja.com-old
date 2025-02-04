<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\BotController;
use App\Models\AppConfiguration;
use App\Models\Donation\Donation;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

Schedule::command('que:work --stop-when-empty')->timezone('Asia/Jakarta')->everyFiveSeconds();
Schedule::command('backup:clean')->timezone('Asia/Jakarta')->dailyAt('01.00')->days([1, 4, 6]);
Schedule::command('backup:run')->timezone('Asia/Jakarta')->dailyAt('01.00')->days([1, 4, 6]);
Schedule::call(function () {
    $files = File::files(storage_path('app/private/' . env('APP_NAME', '')));

    if (! empty($files)) {
        $file = $files[0];
        $fileName = $file->getFilename();
        Storage::disk('google')->put('database/backups/' . $fileName, file_get_contents($file->getRealPath()));
        File::delete($file->getRealPath());

        return true;
    }
})->timezone('Asia/Jakarta')->dailyAt('01.00')->days([1, 4, 6]);
Schedule::call(function () {
    $donations = Donation::where('reported', null)->where('status', 'selesai')->get();
    foreach ($donations as $donation) {
        $fileName = ReportController::createReport($donation);
        $relativePath = storage_path('app/public/reports/') . $fileName[1];
        Storage::disk('google')->put('foods/Arsip Berita Acara/' . $fileName[0] . "/" . $fileName[1], File::get($relativePath));
        BotController::sendForPublic('120363315008311976@g.us', "Berhasil membuat laporan donasi hari ini\n\n" . "Nama File : " . $fileName[1] . "\nUkuran file : " . round(filesize($relativePath) / 1024) . " kb", 'SECOND');
        File::delete($relativePath);
        $donation->reported = "sudah";
        $donation->save();
    }
})->timezone('Asia/Jakarta')->dailyAt('23.00');
