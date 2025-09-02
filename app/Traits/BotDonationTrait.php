<?php

namespace App\Traits;

use App\Models\Donation\Donation;

trait BotDonationTrait
{
    use SendWhatsapp;

    public function getActiveDonation($sender)
    {
        $activeDonation = Donation::where('status', 'aktif')->first();
        $message = "*[AKSI HARI INI]*\n\n" . 'Donatur : ' . $activeDonation->sponsor->name . "\nKuota : " . $activeDonation->quota . "\nTersisa : " . $activeDonation->remain . "\nHero terdaftar : " . $activeDonation->heroes->count() . ' Orang';
        $this->send($sender, $message, 'SECOND');
    }
}
