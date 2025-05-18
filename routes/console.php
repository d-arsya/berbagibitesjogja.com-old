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
Schedule::command('backup:clean')->timezone('Asia/Jakarta')->dailyAt('01.00')->days([1, 4, 6]);
Schedule::command('backup:run')->timezone('Asia/Jakarta')->dailyAt('01.00')->days([1, 4, 6]);
// Schedule::call(function () {
//     $remind = User::withCount('availabilities')
//         ->having('availabilities_count', '>', 160)
//         ->get();
//     $delay = 60;
//     foreach ($remind as $user) {
//         $message = "🔥 Halo, " . $user->name . "! 🔥\n\nWah, sepertinya kamu memiliki kesediaan di lebih dari *160 waktu* ⏳⏰ Itu cukup banyak dan mungkin kurang wajar 🤔\n\nYuk, segera *perbarui waktu kesediaan* kamu agar lebih sesuai ya! ⚡📅\nKamu bisa langsung update di sini 👉 https://app.berbagibitesjogja.site/availability \n\nJika tidak diperbarui, kamu akan menerima notifikasi ini lagi 📩🔄 Jadi, pastikan untuk memperbaruinya sekarang ya! 🚀\n\nTerima kasih sudah menjadi bagian dari BBJ! 💚😊";
//         $phone = $user->phone;
//         $used = AppConfiguration::useWhatsapp();
//         dispatch(function () use ($phone, $message, $used) {
//             BotController::sendForPublic($phone, $message, $used);
//         })->delay(now()->addSeconds($delay));
//         $delay += 60;
//     }
// })->timezone('Asia/Jakarta')->dailyAt('06.00')->days([0, 2, 4, 6]);
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
})->timezone('Asia/Jakarta')->dailyAt('01.00')->days([1, 4, 6]);
Schedule::call(function () {
    $donations = Donation::where('reported', null)->where('status', 'selesai')->get();
    foreach ($donations as $donation) {
        try {
            $fileName = ReportController::createReport($donation);
            $relativePath = storage_path('app/public/reports/') . $fileName[1];
            Storage::disk('google')->put('foods/Arsip Berita Acara/' . $fileName[0] . "/" . $fileName[1], File::get($relativePath));
            BotController::sendForPublic('120363315008311976@g.us', "Berhasil membuat laporan donasi hari ini\n\n" . "Nama File : " . $fileName[1] . "\nUkuran file : " . round(filesize($relativePath) / 1024) . " kb", 'SECOND');
            File::delete($relativePath);
            $donation->reported = "sudah";
            $donation->save();
        } catch (\Throwable $th) {
            BotController::sendForPublic('120363399651067268@g.us', $th->getMessage(), 'SECOND');
        }
    }
})->timezone('Asia/Jakarta')->dailyAt('23.00');
