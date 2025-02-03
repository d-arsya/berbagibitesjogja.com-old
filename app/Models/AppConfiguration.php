<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppConfiguration extends Model
{
    protected $table = "app_configuration";
    protected $guarded = [];
    public static function useWhatsapp()
    {
        $configuration = AppConfiguration::where('key', 'FONNTE_WHATSAPP_USED');
        $used = $configuration->first()->value + 1;
        $configuration->update(["value" => $used]);
        return $used % 2 == 1 ? "FIRST" : "SECOND";
    }
}
