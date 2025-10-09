<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait SendWhatsapp
{
    protected function send($target, $message, $from = 'FIRST')
    {
        Http::post(env('WHATSAPP_ENDPOINT', 'https://api.fonnte.com') . '/send', [
            'target' => $target,
            'message' => $message,
        ]);
    }
    protected function mentionAll($target)
    {
        Http::post(env('WHATSAPP_ENDPOINT', 'https://api.fonnte.com') . '/mention-all', [
            'target' => $target
        ]);
    }
}
