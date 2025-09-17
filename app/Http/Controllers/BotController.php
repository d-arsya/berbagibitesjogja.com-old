<?php

namespace App\Http\Controllers;

use App\Models\AppConfiguration;
use App\Models\Donation\Donation;
use App\Models\Donation\Sponsor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BotController extends Controller
{
    public function fromFonnte()
    {
        header('Content-Type: application/json; charset=utf-8');
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $sender = $data['sender'];
        $message = $data['message'];
        if ($message == '@BOT pemkot list donatur') {
            $this->getSponsorList($sender);
        } elseif (str_starts_with($message, '@BOT pemkot laporan bulanan')) {
            $this->createMonthly($sender, $message);
        }
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
                'Authorization: ' . env("WHATSAPP_FONNTE_FIRST"),
            ],
        ]);

        curl_exec($curl);
        curl_close($curl);
    }

    protected function getSponsorList($sender)
    {
        $used = Donation::all()->pluck('sponsor_id');
        $sponsors = Sponsor::whereIn('id', $used)->get();

        $text = "Daftar Donatur BBJ (Pemkot)\n_hanya menampilkan yang pernah bekerjasama_\n\n";
        foreach ($sponsors as $sponsor) {
            $text .= "#{$sponsor->id} - {$sponsor->name}\n";
        }
        BotController::sendForPublic($sender, $text, 'FIRST');
    }

    protected function createMonthly($sender, $message)
    {
        $hasil = explode(" ", $message);
        $sponsor = Sponsor::find($hasil[4]);
        $month = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $month = $month[$hasil[5]];
        try {
            $year = "20" . $hasil[6];
        } catch (\Throwable $th) {
            $year = now()->year;
        }
        $filename = ReportController::createMonthlyReport($sponsor, $hasil[5], $year);
        if ($filename == 1) {
            return BotController::sendForPublic($sender, "Maaf tidak ada donasi dari {$sponsor->name} di Bulan {$month} {$year}", AppConfiguration::useWhatsapp());
        }
        $code = uniqid();
        DB::table('report_keys')->insert(compact('filename', 'code'));
        $link = route('monthlyReport', compact('code'));
        $res = "✅ *Berhasil membuat laporan bulanan!*\n\n"
            . "📌 Donatur: *{$sponsor->name}*\n"
            . "📅 Bulan: *{$month} {$year}*\n\n"
            . "⬇️ Silakan download di sini:\n{$link}\n\n"
            . "⚠️ _Link hanya bisa dipakai selama 5 menit, setelahnya hangus_";
        dispatch(function () use ($code) {
            $row = DB::table('report_keys')->where('code', $code)->first();
            if ($row) {
                Storage::delete('public/monthly/' . $row->filename);
                DB::table('report_keys')->where('code', $row->code)->delete();
            }
        })->delay(now()->addMinutes(5));
        BotController::sendForPublic($sender, $res, AppConfiguration::useWhatsapp());
    }
}
