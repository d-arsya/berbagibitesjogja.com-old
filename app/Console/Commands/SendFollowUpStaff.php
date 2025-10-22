<?php

namespace App\Console\Commands;

use App\Models\FormJob;
use App\Traits\SendWhatsapp;
use Illuminate\Console\Command;

class SendFollowUpStaff extends Command
{
    use SendWhatsapp;

    protected $signature = 'follow-up:staff';
    public function handle()
    {
        $datas = FormJob::all()->pluck('data');
        $data = collect($datas);
        $tomorrow = now()->addDay()->toDateString();
        $d = $data->firstWhere('date', $tomorrow);
        $output = '';
        $output .= "📢 *Pemberitahuan Tugas Volunteer Besok*\n\n";
        $output .= "📍 Donor: *{$d['sponsor']}*\n";
        $output .= "🎯 Tujuan: *{$d['receiver']}*\n\n";
        $output .= "Berikut kebutuhan tim dan jumlah orang yang dibutuhkan:\n";

        foreach ($d['jobs'] as $job) {
            $output .= "- {$job['name']} ({$job['place']}) — {$job['need']} orang\n";
        }

        $output .= "\n👥 *Daftar yang sudah bertugas:*\n";
        foreach ($d['jobs'] as $job) {
            $names = array_map(fn($p) => "{$p['name']} ({$p['phone']})", $job['persons']);
            $namesStr = !empty($names) ? implode("\n", $names) : '-';
            $output .= "\n🧩 {$job['name']}:\n{$namesStr}\n";
        }

        $output .= "\n⚠️ Mohon bantuan untuk segera melengkapi volunteer yang masih kosong. "
            . "Follow-up otomatis akan dikirim pukul *19.00* malam ini.\n\n"
            . "Terima kasih atas koordinasinya 🌱";

        $this->send('120363330280278639@g.us', $output);
    }
}
