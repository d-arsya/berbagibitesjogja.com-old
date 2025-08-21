<?php

namespace App\Http\Controllers;

use App\Models\AppConfiguration;
use App\Models\Chat;
use App\Models\Donation\Booking;
use App\Models\Donation\Donation;
use App\Models\Heroes\Hero;
use App\Models\Volunteer\Availability;
use App\Models\Volunteer\User;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function sendWa()
    {
        return true;
    }

    private function cekStart($message)
    {
        $mess = strtolower($message);
        return str_starts_with($mess, 'bot ');
    }

    public function fromFonnte()
    {
        header('Content-Type: application/json; charset=utf-8');
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $sender = $data['sender'];
        $message = $data['message'];
        $group = explode(',', env('GROUP_WA'));
        if (in_array($sender, $group)) {
            if ($message == '@BOT donasi hari ini') {
                $this->getActiveDonation($sender);
            } elseif ($message == '@BOT hero hari ini') {
                $this->getAllActiveHero($sender);
            } elseif ($message == '@BOT hero yang belum') {
                $this->getAllNotYetHero($sender);
            } elseif ($message == '@BOT ingatkan hero hari ini') {
                $this->reminderToday($sender);
            } elseif (str_starts_with($message, '@BOT ingatkan hero yang belum')) {
                $this->reminderLastCall($message, $sender);
            } elseif (str_starts_with($message, '@BOT balas')) {
                $this->replyHero($sender, $message);
            } elseif (str_starts_with($message, '@BOT dokumentasi')) {
                $this->giveDocumentation($message);
            } elseif (str_starts_with($message, '@BOT avail')) {
                $this->getAvailableVolunteer($sender, $message);
            }
        } else {
            $this->getReplyFromStranger($sender, $message);
        }
    }

    public function getAvailableVolunteer($sender, $message)
    {
        $days = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        $time = str_replace('@BOT avail ', '', $message);
        $timeRange = strpos($time, '-') !== false ? explode('-', $time) : [$time];

        $day = substr($timeRange[0], 0, 1);
        foreach ($timeRange as $key => $avail) {
            if (substr($avail, 0, 1) !== $day) {
                return $this->kirimWa($sender, 'Tidak boleh beda hari', 'SECOND');
            }
            $timeRange[$key] = substr($avail, 1);
        }

        if ($timeRange[0] > end($timeRange)) {
            return $this->kirimWa($sender, 'Waktu harus berurutan', 'SECOND');
        }

        $take = [];
        for ($i = $timeRange[0]; $i <= end($timeRange); $i += 5) {
            $take[] = $day . $i;
        }

        $availUsers = Availability::whereIn('code', $take)
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw("COUNT(user_id) = ?", [count($take)])
            ->pluck('user_id');

        $users = User::whereIn('id', $availUsers)->get();

        $startHour = substr($timeRange[0], 0, -1);
        $startMinute = substr($timeRange[0], -1) == 0 ? "00" : "30";
        $endHour = substr(end($timeRange), 0, -1);
        $endMinute = substr(end($timeRange), -1) == 0 ? "00" : "30";

        $message = "*[Available Volunteer]*\n\n{$days[$day]} $startHour.$startMinute - $endHour.$endMinute\nJumlah : " . count($users) . " Volunteer\n\n";

        foreach ($users as $user) {
            $message .= "{$user->name}\n{$user->phone}\n";
        }

        $this->kirimWa($sender, $message, 'SECOND');
    }

    public function getReplyFromStranger($sender, $text)
    {
        $activeDonation = Donation::where('status', 'aktif')->pluck('id');
        $hero = Hero::where('phone', $sender)->where('status', 'belum')->whereIn('donation_id', $activeDonation)->first();
        $foodDonator = Booking::where('phone', $sender)->where('status', 'waiting')->first();
        if (str_starts_with($text, "verify")) {
            return true;
        }
        if ($hero) {
            $this->getReplyFromHeroes($hero, $text);
        } elseif ($foodDonator) {
            $this->getReplyFromFoodDonator($foodDonator, $text);
        } else {
            $this->gemini($sender, $text);
        }
        return true;
    }

    public function gemini($sender, $text)
    {
        $gemini =  new ChatController();
        $response = $gemini->chat($text);
        $spam = str_starts_with($response[0], 'Maaf');
        Chat::create([
            "phone" => $sender,
            "question" => $text,
            "answer" => $spam ? null : $response[0],
            "token" => $response[1],
            "spam" => $spam
        ]);
        if (!$spam) {
            $this->kirimWa($sender, $response[0], 'SECOND');
        }
    }

    public function getReplyFromHeroes($hero, $text)
    {
        $message = '> Balasan Heroes' . " \n\n" . $hero->name . "\n_Kode : " . $hero->code . "_\n\n" . $text;
        $this->kirimWa('120363350581821641@g.us', $message, 'SECOND');
    }
    public function getReplyFromFoodDonator($foodDonator, $text)
    {
        $message = '> Balasan Donasi Surplus Food' . " \n\n" . $foodDonator->name . "\n" . $foodDonator->ticket . "\n\n" . $text;
        $this->kirimWa('120363301975705765@g.us', $message, 'SECOND');
    }

    public function giveDocumentation($message)
    {
        $message = str_replace('@BOT dokumentasi ', '', $message);
        $message = explode(' ', $message);
        $donation = Donation::find(str_replace('#', '', $message[0]));
        $donation->update(["media" => $message[1]]);
        BotController::sendForPublic('120363313399113112@g.us', 'Terimakasih dokumentasinya', 'SECOND');
    }

    public static function notificationForDocumentation(Donation $donation)
    {
        $message = "*[NEED FOR DOCUMENTATION]*\n\nKode : #" . $donation->id . "\n" . \Carbon\Carbon::parse($donation->take)->isoFormat('D MMMM Y') . "\n\nHalo tim medinfo, kita ada aksi dari " . $donation->sponsor->name . " nih. Minta bantuannya buat kirimin link dokumentasi yaa biar tim Food lebih mudah dalam mencari dokumentasinya. Caranya ketik *@BOT dokumentasi <KODE> <LINK>*\n\nContoh : @BOT dokumentasi #35 https://drive/com";
        BotController::sendForPublic('120363313399113112@g.us', $message, 'SECOND');
    }

    public function kirimWa($target, $message, $from)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'target' => $target,
                'message' => $message,
                'schedule' => 0,
                'typing' => false,
                'delay' => '5',
                'countryCode' => '62',
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . env("WHATSAPP_FONNTE_" . $from),
            ],
        ]);
        curl_exec($curl);
        curl_close($curl);
    }

    public function getAllActiveHero($sender)
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
        $this->kirimWa($sender, $message, 'SECOND');
    }

    public function getAllNotYetHero($sender)
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
        $this->kirimWa($sender, $message, 'SECOND');
    }

    public function reminderLastCall($jam, $sender)
    {
        $activeDonation = Donation::where('status', 'aktif')->first();
        $notyetHero = Hero::where('donation_id', $activeDonation->id)->where('status', 'belum')->get(['name', 'phone']);
        $jam = str_replace('@BOT ingatkan hero yang belum ', '', $jam);
        $delay = 10;
        foreach ($notyetHero as $hero) {
            $message = 'Halo ' . $hero->name . ' kami dari BBJ mengingatkan untuk bisa mengambil makanan di ' . $activeDonation->location . '(' . $activeDonation->maps . '). ' . 'Kami tunggu hingga pukul ' . $jam . " yaaa\nTerimakasih \n\n" . '_pesan ini dikirim dengan bot_';
            $phone = $hero->phone;
            dispatch(function () use ($phone, $message) {
                $this->kirimWa($phone, $message, AppConfiguration::useWhatsapp());
            })->delay(now()->addSeconds($delay));
            $delay += 10;
        }
        $message = 'Akan mengirimkan kepada ' . $notyetHero->count() . ' hero secara bertahap';
        $this->kirimWa($sender, $message, 'SECOND');
        $message = 'Berhasil mengirimkan kepada ' . $notyetHero->count() . ' hero';
        dispatch(function () use ($sender, $message) {
            $this->kirimWa($sender, $message, 'SECOND');
        })->delay(now()->addSeconds($delay));
    }

    public function batalin($sender)
    {
        $activeDonation = Donation::where('status', 'aktif')->first();
        $allActiveHero = Hero::where('donation_id', $activeDonation->id)->get(['name', 'phone']);
        $delay = 10;
        foreach ($allActiveHero as $hero) {
            $message = "Selamat pagi, food heroes 🌻💙\nKami dari *Berbagi Bites Jogja* menyampaikan permohonan maaf karena distribusi hari ini dibatalkan.\nBuat teman-teman yang sudah daftar, tenang aja kalian tetap akan dapat surplus food besok dari Artotel, pada jam pengambilan yang sama✨\n\nJika besok kalian *berhalangan hadir* karena sudah ada jadwal lain di jam tersebut, boleh banget konfirmasi ke kami. Mohon maaf atas ketidaknyamanannya. Terima kasih atas pengertiannya, sampai ketemu besok, ya!!!\n\nSalam hangat,\nBerbagi Bites Jogja \n\n" . '_pesan ini dikirim dengan bot_';
            $phone = $hero->phone;
            dispatch(function () use ($phone, $message) {
                $this->kirimWa($phone, $message, AppConfiguration::useWhatsapp());
            })->delay(now()->addSeconds($delay));
            $delay += 30;
        }
        $message = 'Akan mengirimkan pembatalan kepada ' . $allActiveHero->count() . ' hero secara bertahap';
        $this->kirimWa($sender, $message, 'SECOND');
        $message = 'Berhasil mengirimkan pembatalan kepada ' . $allActiveHero->count() . ' hero';
        dispatch(function () use ($sender, $message) {
            $this->kirimWa($sender, $message, 'SECOND');
        })->delay(now()->addSeconds($delay));
    }
    public function reminderToday($sender)
    {
        $activeDonation = Donation::where('status', 'aktif')->first();
        $allActiveHero = Hero::where('donation_id', $activeDonation->id)->get(['name', 'phone']);
        $delay = 30;
        foreach ($allActiveHero as $hero) {
            $message = 'Halo ' . $hero->name . ' kami dari BBJ mengingatkan bahwa pengambilan surplus food dimulai pada pukul ' . str_pad($activeDonation->hour, 2, '0', STR_PAD_LEFT) . '.' . str_pad($activeDonation->minute, 2, '0', STR_PAD_LEFT) . ' dan bisa diambil di ' . $activeDonation->location . '(' . $activeDonation->maps . ')' . "\n\nTerimakasih \n\n" . '_pesan ini dikirim dengan bot_';
            $phone = $hero->phone;
            dispatch(function () use ($phone, $message) {
                $this->kirimWa($phone, $message, AppConfiguration::useWhatsapp());
            })->delay(now()->addSeconds($delay));
            $delay += 30;
        }
        $message = 'Akan mengirimkan kepada ' . $allActiveHero->count() . ' hero secara bertahap';
        $this->kirimWa($sender, $message, 'SECOND');
        $message = 'Berhasil mengirimkan kepada ' . $allActiveHero->count() . ' hero';
        dispatch(function () use ($sender, $message) {
            $this->kirimWa($sender, $message, 'SECOND');
        })->delay(now()->addSeconds($delay));
    }

    public function getActiveDonation($sender)
    {
        $activeDonation = Donation::where('status', 'aktif')->first();
        $message = "*[AKSI HARI INI]*\n\n" . 'Donatur : ' . $activeDonation->sponsor->name . "\nKuota : " . $activeDonation->quota . "\nTersisa : " . $activeDonation->remain . "\nHero terdaftar : " . $activeDonation->heroes->count() . ' Orang';
        $this->kirimWa($sender, $message, 'SECOND');
    }

    public function getHelp($sender)
    {
        $message = "@BOT help\n" . "@BOT donasi hari ini\n" . "@BOT hero hari ini\n" . "@BOT hero yang belum\n" . "@BOT ingatkan hero hari ini\n" . "@BOT ingatkan hero yang belum <JAM> <PESAN OPSIONAL>\n" . '@BOT balas <KODE> <PESAN>';
        $this->kirimWa($sender, $message, 'SECOND');
    }

    public function replyHero($sender, $message)
    {
        $code = substr(str_replace('@BOT balas ', '', $message), 0, 6);
        $hero = Hero::where('code', $code)->where('status', 'belum')->first();
        if ($hero) {
            $message = substr(str_replace('@BOT balas ', '', $message), 7);
            $this->kirimWa($hero->phone, $message . "\n\n_dikirim menggunakan bot_", AppConfiguration::useWhatsapp());
            $this->kirimWa($sender, 'Berhasil mengirimkan balasan kepada ' . $hero->name, 'SECOND');
        }

        return false;
    }

    public static function sendForPublic($target, $message, $from)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'target' => $target,
                'message' => $message,
                'schedule' => 0,
                'typing' => false,
                'delay' => '2',
                'countryCode' => '62',
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . env("WHATSAPP_FONNTE_" . $from),
            ],
        ]);

        curl_exec($curl);
        curl_close($curl);
    }

    public static function createReimburse($user, $reimburse)
    {
        $amount = "Rp " . number_format($reimburse->amount, 0, ',', '.');
        BotController::sendForPublic($user->phone, "Reimburse sebesar $amount sedang diajukan", AppConfiguration::useWhatsapp());
        BotController::sendForPublic('6289636055420', "Reimburse sebesar {$amount} sedang diajukan oleh {$user->name} melalui {$reimburse->method} dengan nomor {$reimburse->target}", 'SECOND');
    }
}
