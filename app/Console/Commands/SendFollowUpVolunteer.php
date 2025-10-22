<?php

namespace App\Console\Commands;

use App\Models\FormJob;
use App\Traits\SendWhatsapp;
use Illuminate\Console\Command;

class SendFollowUpVolunteer extends Command
{
    use SendWhatsapp;
    protected $signature = 'follow-up:volunteer';
    public function handle()
    {
        $datas = FormJob::all()->pluck('data');
        $data = collect($datas);
        $tomorrow = now()->addDay()->toDateString();
        $act = $data->firstWhere('date', $tomorrow);
        $jobs = $act['jobs'];

        $date = date('d F Y', strtotime($act['date']));
        $sponsor = $act['sponsor'];
        $receiver = $act['receiver'];

        foreach ($jobs as $job) {
            foreach ($job['persons'] as $person) {

                $message = "🌱 Halo {$person['name']}!\n\n"
                    . "Ini pengingat untuk kegiatan *Berbagi Bites Jogja* besok, *{$date}*.\n"
                    . "Kamu bertugas sebagai *{$job['name']}* untuk *{$sponsor}* dengan tujuan *{$receiver}*.\n\n"
                    . "🕒 Waktu: {$job['place']}\n"
                    . "Terima kasih sudah berkontribusi dalam gerakan mengurangi food waste 💚\n\n"
                    . "📞 Contact Person:\n082310547392 (Hasya)\n085175490728 (Tania)";

                $this->send($person['phone'], $message);
            }
        }
    }
}
