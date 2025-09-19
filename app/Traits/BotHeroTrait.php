<?php

namespace App\Traits;

use App\Http\Controllers\ChatController;
use App\Models\AppConfiguration;
use App\Models\Donation\Donation;
use App\Models\Heroes\Hero;
use App\Models\Volunteer\Notify;
use Carbon\Carbon;

trait BotHeroTrait
{
    use SendWhatsapp;


    protected function getReplyFromHeroes($hero, $text)
    {
        $message = '> Balasan Heroes' . " \n\n" . $hero->name . "\n_Kode : " . $hero->code . "_\n\n" . $text;
        $this->send('120363350581821641@g.us', $message, 'SECOND');
    }

    protected function getAllActiveHero($sender)
    {
        $activeDonation = Donation::where('status', 'aktif')->first();
        $allHero = Hero::where('donation_id', $activeDonation->id)->get(['name', 'code']);
        $message = '';
        $message = $message . 'Daftar heroes hari ini' . " \n ";
        $message = $message . '_Jumlah : ' . $allHero->count() . '_' . " \n ";
        foreach ($allHero as $hero) {
            $message = $message . " \n " . $hero->name;
            $message = $message . " \n " . $hero->code;
        }
        $this->send($sender, $message, 'SECOND');
    }

    protected function getAllNotYetHero($sender)
    {
        $activeDonation = Donation::where('status', 'aktif')->first();
        $notyetHero = Hero::where('donation_id', $activeDonation->id)->where('status', 'belum')->get(['name', 'code']);
        $message = '';
        $message = $message . 'Daftar yang belum mengambil' . " \n ";
        $message = $message . '_Jumlah : ' . $notyetHero->count() . '_' . " \n ";
        foreach ($notyetHero as $hero) {
            $message = $message . " \n " . $hero->name;
            $message = $message . " \n " . $hero->code;
        }
        $this->send($sender, $message, 'SECOND');
    }

    protected function getReplyFromFoodDonator($foodDonator, $text)
    {
        $message = '> Balasan Donasi Surplus Food' . " \n\n" . $foodDonator->name . "\n" . $foodDonator->ticket . "\n\n" . $text;
        $this->send('120363301975705765@g.us', $message, 'SECOND');
    }

    protected function reminderToday($sender)
    {
        $activeDonation = Donation::where('status', 'aktif')->first();
        $allActiveHero = Hero::where('donation_id', $activeDonation->id)->get(['name', 'phone']);
        $delay = 30;
        foreach ($allActiveHero as $hero) {
            $message = 'Halo ' . $hero->name . ' kami dari BBJ mengingatkan bahwa pengambilan surplus food dimulai pada pukul ' . str_pad($activeDonation->hour, 2, '0', STR_PAD_LEFT) . '.' . str_pad($activeDonation->minute, 2, '0', STR_PAD_LEFT) . ' dan bisa diambil di ' . $activeDonation->location . '(' . $activeDonation->maps . ')' . "\n\nTerimakasih \n\n" . '_pesan ini dikirim dengan bot_';
            $phone = $hero->phone;
            dispatch(function () use ($phone, $message) {
                $this->send($phone, $message, AppConfiguration::useWhatsapp());
            })->delay(now()->addSeconds($delay));
            $delay += 30;
        }
        $message = 'Akan mengirimkan kepada ' . $allActiveHero->count() . ' hero secara bertahap';
        $this->send($sender, $message, 'SECOND');
        $message = 'Berhasil mengirimkan kepada ' . $allActiveHero->count() . ' hero';
        dispatch(function () use ($sender, $message) {
            $this->send($sender, $message, 'SECOND');
        })->delay(now()->addSeconds($delay));
    }

    protected function reminderLastCall($jam, $sender)
    {
        $activeDonation = Donation::where('status', 'aktif')->first();
        $notyetHero = Hero::where('donation_id', $activeDonation->id)->where('status', 'belum')->get(['name', 'phone']);
        $jam = str_replace('@BOT ingatkan hero yang belum ', '', $jam);
        $delay = 10;
        foreach ($notyetHero as $hero) {
            $message = 'Halo ' . $hero->name . ' kami dari BBJ mengingatkan untuk bisa mengambil makanan di ' . $activeDonation->location . '(' . $activeDonation->maps . '). ' . 'Kami tunggu hingga pukul ' . $jam . " yaaa\nTerimakasih \n\n" . '_pesan ini dikirim dengan bot_';
            $phone = $hero->phone;
            dispatch(function () use ($phone, $message) {
                $this->send($phone, $message, AppConfiguration::useWhatsapp());
            })->delay(now()->addSeconds($delay));
            $delay += 10;
        }
        $message = 'Akan mengirimkan kepada ' . $notyetHero->count() . ' hero secara bertahap';
        $this->send($sender, $message, 'SECOND');
        $message = 'Berhasil mengirimkan kepada ' . $notyetHero->count() . ' hero';
        dispatch(function () use ($sender, $message) {
            $this->send($sender, $message, 'SECOND');
        })->delay(now()->addSeconds($delay));
    }

    protected function gemini($sender, $text)
    {
        $gemini =  new ChatController();
        $response = $gemini->chat($text);
        $spam = str_starts_with($response[0], 'Maaf');
        if (!$spam) {
            $this->send($sender, $response[0], 'SECOND');
        }
    }

    protected function sendNotification(Donation $donation, string $hour)
    {
        $delay = 10;
        $notif = Notify::all();
        $date = Carbon::parse($donation->take)->locale('id');
        $formatted = $date->translatedFormat('l, d F Y');
        foreach ($notif as $hero) {
            $phone = $hero->phone;
            $message = "*📢 Ada donasi aktif BBJ dari {$donation->sponsor->name}!*\n\n🦸‍♂{$donation->quota} orang\n🗓$formatted\n🕧" . $hour . "WIB\n📍{$donation->location}\n{$donation->maps}\n\n🦸🏻 Ayo, jadi Food Heroes dan bantu BBJ menyelamatkan bumi dengan daftar di sini https://berbagibitesjogja.com/form" . "\n\nTerima kasih 🙏\n\n_pesan ini dikirim otomatis oleh bot_";
            dispatch(function () use ($phone, $message) {
                $this->send($phone, $message, AppConfiguration::useWhatsapp());
            })->delay(now()->addSeconds($delay));
            $delay += 10;
        }
        Notify::truncate();
    }
}
