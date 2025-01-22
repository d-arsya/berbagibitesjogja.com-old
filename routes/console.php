<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

Schedule::command('backup:clean')->timezone('Asia/Jakarta')->dailyAt('01.00')->days([1, 4, 6]);
Schedule::command('backup:run')->timezone('Asia/Jakarta')->dailyAt('01.00')->days([1, 4, 6]);
Schedule::call(function () {
    $files = File::files(storage_path('app/private/'.env('APP_NAME', '')));

    if (! empty($files)) {
        $file = $files[0];
        $fileName = $file->getFilename();
        Storage::disk('google')->put('database/backups/'.$fileName, file_get_contents($file->getRealPath()));
        File::delete($file->getRealPath());

        return true;
    }
})->timezone('Asia/Jakarta')->dailyAt('01.00')->days([1, 4, 6]);
