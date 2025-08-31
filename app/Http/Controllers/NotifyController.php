<?php

namespace App\Http\Controllers;

use App\Models\AppConfiguration;
use App\Models\Donation\Donation;
use App\Models\Volunteer\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotifyController extends Controller
{
    public function form(Request $request)
    {
        if ($request->phone) {
            session()->put('phone', "62" . $request->phone);
            return redirect()->route('auth.google');
        }
        return view('pages.notify');
    }

    public static function sendNotification(Donation $donation)
    {
        $delay = 10;
        $notif = Notify::all();
        $date = Carbon::parse($donation->take)->locale('id');
        $formatted = $date->translatedFormat('l, d F Y');
        foreach ($notif as $hero) {
            $phone = $hero->phone;
            $message = "*📢 Ada donasi aktif dari BBJ!*\n\nPendonor : {$donation->sponsor->name}\nKuota : {$donation->quota} orang\nTanggal : " . $formatted . "\nJam : " . str_pad($donation->hour, 2, '0', STR_PAD_LEFT) . ":" . str_pad($donation->minute, 2, '0', STR_PAD_LEFT) . " WIB\n\n👉 Untuk ikut serta, silakan daftar melalui form berikut:\n" . "https://berbagibitesjogja.com/form" . "\n\nTerima kasih 🙏\n\n_pesan ini dikirim otomatis oleh bot_";
            dispatch(function () use ($phone, $message) {
                BotController::sendForPublic($phone, $message, AppConfiguration::useWhatsapp());
            })->delay(now()->addSeconds($delay));
            $delay += 10;
        }
        Notify::truncate();
    }
}
