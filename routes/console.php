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
    $donation = Donation::where('reported', null)->where('status', 'selesai')->first();
    try {
        $fileName = ReportController::createReport($donation);
        $relativePath = storage_path('app/public/reports/') . $fileName;
        Storage::disk('google')->put('foods/arsyad/' . $donation->sponsor->name . "/" . $fileName, File::get($relativePath));
        Storage::disk('public')->delete('reports/'.$fileName);
        $donation->reported = "sudah";
        $donation->save();
        $sudah = Donation::where('reported', 'sudah')->count();
        if($sudah%10==0){
            BotController::sendForPublic('6289636055420','Sudah '. $sudah ,'SECOND');
        }elseif($sudah==184){
            BotController::sendForPublic('120363315008311976@g.us','Selesai membuat '.$sudah.' laporan','SECOND');
        }
    } catch (\Throwable $th) {
        BotController::sendForPublic('6289636055420', $th->getMessage(), 'SECOND');
    }
    // foreach ($donations as $donation) {
    // }
})->timezone('Asia/Jakarta')->everyTenSeconds()->name('Buat report')->withoutOverlapping();
