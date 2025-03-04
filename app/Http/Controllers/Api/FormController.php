<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BotController;
use App\Http\Controllers\Controller;
use App\Models\AppConfiguration;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class FormController extends Controller
{
    public function fromGoogleSheets(Request $request, string $token)
    {
        if ($token == env('WEBHOOK_KEY')) {
            $data = $request->validate([
                "name" => "required",
                "phone" => "required",
                "total" => "required",
            ]);
            $pay = $data["total"] * 90 / 100;
            $message = "Halo " . $data["name"] . "\nTotal : Rp" . number_format($data["total"], 0, ',', '.') . "\nSetelah discount : Rp" . number_format($pay, 0, ',', '.') . "\n\nSilahkan bayar menggunakan QRIS dibawah\n" . "https://berbagibitesjogja.site/qris-podo";
            BotController::sendForPublic($data["phone"], $message, AppConfiguration::useWhatsapp());
            return response()->json($request->all());
        }
        return true;
    }
}
