<?php

namespace App\Traits;

trait SendWhatsapp
{
    protected function send($target, $message, $from)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => env('WHATSAPP_ENDPOINT', 'https://api.fonnte.com/send'),
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
            ]
        ]);

        curl_exec($curl);
        curl_close($curl);
    }
}
