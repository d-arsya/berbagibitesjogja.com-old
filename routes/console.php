<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
Schedule::command('backup:clean')->everyThreeMinutes();
Schedule::command('backup:run')->everyThreeMinutes();
Schedule::call(function () {
    $files = File::files(storage_path('app/private/' . env('APP_NAME', '')));

    if (! empty($files)) {
        $file = $files[0];
        $fileName = $file->getFilename();
        Storage::disk('google')->put('database/backups/' . $fileName, file_get_contents($file->getRealPath()));
        File::delete($file->getRealPath());

        return true;
    }
})->everyThreeMinutes();
