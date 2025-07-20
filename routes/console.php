<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\BotController;
use App\Models\AppConfiguration;
use App\Models\Donation\Donation;
use App\Models\Volunteer\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

Schedule::command('que:work --stop-when-empty')->timezone('Asia/Jakarta')->everyFiveSeconds();
Schedule::command('backup:clean')->timezone('Asia/Jakarta')->dailyAt('01.00');
Schedule::command('backup:run')->timezone('Asia/Jakarta')->dailyAt('01.00');
Schedule::call(function () {
    $files = File::files(storage_path('app/private/' . env('APP_NAME', '')));

    if (! empty($files)) {
        try {
            $file = $files[0];
            $fileName = $file->getFilename();
            Storage::disk('google')->put('database/backups/' . $fileName, file_get_contents($file->getRealPath()));
            File::delete($file->getRealPath());

            return true;
        } catch (\Throwable $th) {
            BotController::sendForPublic('120363399651067268@g.us', $th->getMessage(), 'SECOND');
        }
    }
})->timezone('Asia/Jakarta')->dailyAt('01.00');
Schedule::call(function () {
    $donation = Donation::where('reported', null)->where('status', 'selesai')->first();
    if (!$donation) {
        return true;
    }
    try {
        $fileName = ReportController::createReport($donation);
        $relativePath = storage_path('app/public/reports/') . $fileName;
        Storage::disk('google')->put('foods/Arsip Berita Acara/' . $donation->sponsor->name . "/" . $fileName, File::get($relativePath));
        $fileSize = filesize($relativePath);
        Storage::disk('public')->delete('reports/'.$fileName);
        $donation->reported = "sudah";
        $donation->save();
        $sudah = Donation::where('reported', 'sudah')->count();
        BotController::sendForPublic('120363315008311976@g.us', "Berhasil membuat laporan\n\n" . "Nama File : " . $fileName[1] . "\nUkuran file : " . round($fileSize / 1024) . " kb", 'SECOND');
    } catch (\Throwable $th) {
        BotController::sendForPublic('120363399651067268@g.us', $th->getMessage(), 'SECOND');
    }
})->timezone('Asia/Jakarta')->dailyAt('23.00')->name('Buat report')->withoutOverlapping();
