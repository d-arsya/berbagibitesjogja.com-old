<?php

namespace App\Http\Controllers;

use App\Models\AppConfiguration;
use App\Models\Donation\Booking;
use App\Models\Donation\Donation;
use App\Models\Donation\Food;
use App\Models\Heroes\Hero;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContributorController extends Controller
{
    public function foodCreate(Request $request)
    {
        $donations_sum = Donation::all()->count();
        $foods = round(Food::all()->sum('weight') / 1000);
        $heroes = Hero::all()->sum('quantity');

        $currentDate = Carbon::now();
        $fourMonthsAgo = Carbon::now()->subMonths(7);

        $donationsStat = Donation::whereBetween('take', [$fourMonthsAgo, $currentDate])->with(['foods', 'heroes'])->get();
        $groupedData = $donationsStat->groupBy(function ($donation) {
            return Carbon::parse($donation->take)->format('Y-m'); // Format tahun-bulan (YYYY-MM)
        });
        $lastData = [];
        foreach ($groupedData as $key => $item) {
            $hero_count = 0;
            $food_count = 0;

            foreach ($item as $data) {
                $hero_count += $data->heroes->sum('quantity');
                $food_count += $data->foods->sum('weight') / 1000;
            }
            $lastData[] = [
                'bulan' => Carbon::parse($key)->format('F'),
                'heroes' => $hero_count,
                'foods' => $food_count,
            ];
        }

        return view('pages.payment.food', compact('donations_sum', 'foods', 'heroes', 'lastData'));
    }
    public function foodStore(Request $request)
    {
        $request->validate([
            'phone' => 'regex:/^8/',
            'name' => 'required',
            'variant' => 'required',
            'description' => 'required',
        ]);
        $data = $request->except(['hour', 'minute']);
        $data["status"] = "waiting";
        $data["phone"] = "62" . $request->phone;
        $data["take"] = $data["take"] . " " . $request["hour"] . ":" . $request["minute"] . ":00";
        do {
            $data["ticket"] = $this->createFoodTicket();
        } while (Booking::where('ticket', $data["ticket"])->first());
        $booking = Booking::create($data);
        BotController::sendForPublic($booking->phone, "*[" . $booking->ticket . "]*\n\nSilahkan tunggu admin mengonfirmasi donasi anda. Terimakasih atas kontribusinya" . "\n\n🌱 Empowering sustainability through collective action\n🍽 Partnering with local businesses & communities\n📍 Yogyakarta-based food rescue initiative\n\n📷 Instagram: @berbagibitesjogja\n🌐 Website: https://berbagibitesjogja.site", AppConfiguration::useWhatsapp());
        BotController::sendForPublic("120363301975705765@g.us", "*[DONASI MAKANAN]*\n\nNomor Tiket : " . $booking->ticket . "\nNama : " . $booking->name . "\nPorsi : " . $booking->quota . " Orang\nDeskripsi : " . $booking->description . "\nAlamat Pengambilan : " . $booking->location . "\nWaktu Pengambilan : " . Carbon::parse($booking->take)->isoFormat('dddd, D MMMM Y hh:mm') . " WIB" . "\nNomor Telepon : " . "wa.me/" . $booking->phone . "\n\nMohon MinJe untuk melakukan follow up", 'SECOND');
        return redirect()->route('form.create')->with('success', 'Berhasil mendonasikan makanan');
    }
    private function createFoodTicket()
    {
        return "FSD-" . Carbon::now()->isoFormat('YYYYMMDD') . "-" . rand(100, 999);
    }
    public function food()
    {
        $foodDonators = Booking::orderByRaw("CASE WHEN status = 'waiting' THEN 0 ELSE 1 END")->paginate(10);
        return view('pages.contributor.food', compact('foodDonators'));
    }
    public function foodCancel(Booking $booking)
    {
        $booking->update(["status" => "cancel"]);
        return redirect()->route('contributor.food')->with('success', 'Berhasil membatalkan donasi');
    }
    public function foodDone(Booking $booking)
    {
        $booking->update(["status" => "done"]);
        return redirect()->route('contributor.food')->with('success', 'Berhasil menerima donasi');
    }
}
