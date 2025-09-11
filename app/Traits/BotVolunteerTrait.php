<?php

namespace App\Traits;

use App\Http\Controllers\BotController;
use App\Http\Controllers\ReportController;
use App\Models\AppConfiguration;
use App\Models\Donation\Donation;
use App\Models\Donation\Sponsor;
use App\Models\Heroes\Hero;
use App\Models\Volunteer\Availability;
use App\Models\Volunteer\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait BotVolunteerTrait
{
    use SendWhatsapp;
    protected function getAvailableVolunteer($sender, $message)
    {
        $days = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        $time = str_replace('@BOT avail ', '', $message);
        $timeRange = strpos($time, '-') !== false ? explode('-', $time) : [$time];

        $day = substr($timeRange[0], 0, 1);
        foreach ($timeRange as $key => $avail) {
            if (substr($avail, 0, 1) !== $day) {
                return $this->send($sender, 'Tidak boleh beda hari', 'SECOND');
            }
            $timeRange[$key] = substr($avail, 1);
        }

        if ($timeRange[0] > end($timeRange)) {
            return $this->send($sender, 'Waktu harus berurutan', 'SECOND');
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

        $this->send($sender, $message, 'SECOND');
    }

    protected function giveDocumentation($message)
    {
        $message = str_replace('@BOT dokumentasi ', '', $message);
        $message = explode(' ', $message);
        $donation = Donation::find(str_replace('#', '', $message[0]));
        $donation->update(["media" => $message[1]]);
        $this->send('120363313399113112@g.us', 'Terimakasih dokumentasinya', 'SECOND');
    }

    protected function notificationForDocumentation(Donation $donation)
    {
        $message = "*[NEED FOR DOCUMENTATION]*\n\nKode : #" . $donation->id . "\n" . \Carbon\Carbon::parse($donation->take)->isoFormat('D MMMM Y') . "\n\nHalo tim medinfo, kita ada aksi dari " . $donation->sponsor->name . " nih. Minta bantuannya buat kirimin link dokumentasi yaa biar tim Food lebih mudah dalam mencari dokumentasinya. Caranya ketik *@BOT dokumentasi <KODE> <LINK>*\n\nContoh : @BOT dokumentasi #35 https://drive/com";
        $this->send('120363313399113112@g.us', $message, 'SECOND');
    }
    protected function createReimburse($user, $reimburse)
    {
        $amount = "Rp " . number_format($reimburse->amount, 0, ',', '.');
        $this->send($user->phone, "Reimburse sebesar $amount sedang diajukan", AppConfiguration::useWhatsapp());
        $this->send('6289636055420', "Reimburse sebesar {$amount} sedang diajukan oleh {$user->name} melalui {$reimburse->method} dengan nomor {$reimburse->target}", 'SECOND');
    }

    protected function replyHero($sender, $message)
    {
        $code = substr(str_replace('@BOT balas ', '', $message), 0, 6);
        $hero = Hero::where('code', $code)->where('status', 'belum')->first();
        if ($hero) {
            $message = substr(str_replace('@BOT balas ', '', $message), 7);
            $this->send($hero->phone, $message . "\n\n_dikirim menggunakan bot_", AppConfiguration::useWhatsapp());
            $this->send($sender, 'Berhasil mengirimkan balasan kepada ' . $hero->name, 'SECOND');
        }
    }

    protected function getSponsorList($sender)
    {
        $sponsors = Sponsor::all();

        $text = "Daftar Donatur BBJ\n\n";
        foreach ($sponsors as $sponsor) {
            $text .= "#{$sponsor->id} - {$sponsor->name}\n";
        }
        $this->send($sender, $text, AppConfiguration::useWhatsapp());
    }

    protected function createMonthly($sender, $message)
    {
        $hasil = explode(" ", $message);
        $sponsor = Sponsor::find($hasil[3]);
        $month = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $month = $month[$hasil[4]];
        $filename = ReportController::createMonthlyReport($sponsor, $hasil[4]);
        $code = uniqid();
        DB::table('report_keys')->insert(compact('filename', 'code'));
        $link = route('monthlyReport', compact('code'));
        $res = "✅ *Berhasil membuat laporan bulanan!*\n\n"
            . "📌 Donatur: *{$sponsor->name}*\n"
            . "📅 Bulan: *{$month}*\n\n"
            . "⬇️ Silakan download di sini:\n{$link}\n\n"
            . "⚠️ _Link hanya bisa dipakai selama 5 menit, setelahnya hangus_";
        dispatch(function () use ($code) {
            $row = DB::table('report_keys')->where('code', $code)->first();
            if ($row) {
                Storage::delete('public/monthly/' . $row->filename);
                DB::table('report_keys')->where('code', $row->code)->delete();
            }
        })->delay(now()->addMinutes(5));
        $this->send($sender, $res, AppConfiguration::useWhatsapp());
    }
}
