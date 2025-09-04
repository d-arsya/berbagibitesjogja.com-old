<?php

namespace App\Http\Controllers;

use App\Models\Donation\Donation;
use App\Models\Donation\Food;
use App\Models\Heroes\Hero;
use App\Models\Volunteer\Faculty;
use App\Models\Volunteer\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class HeroController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('guest', only: ['create', 'cancel']),
            new Middleware('auth', only: ['index', 'contributor', 'show', 'update', 'destroy', 'faculty']),
        ];
    }

    public function index()
    {
        $heroes = Hero::with(['faculty', 'donation'])->paginate(100);
        $donations = Donation::where('status', 'aktif')->get();
        $faculties = Faculty::all();

        return view('pages.hero.index', compact('donations', 'heroes', 'faculties'));
    }

    public function getJsonData($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);

        return $data;
    }

    public function create()
    {

        // $ig_media = collect($this->getJsonData('https://graph.instagram.com/me/media?fields=media_url,permalink,media_type,thumbnail_url&access_token=' . env('INSTAGRAM_ACCESS_TOKEN', null))['data'])->take(9);
        // $ig_user = collect($this->getJsonData('https://graph.instagram.com/me?fields=biography,followers_count,follows_count,media_count,name,profile_picture_url,username,website&access_token=' . env('INSTAGRAM_ACCESS_TOKEN', null)));
        $donations = Donation::where('status', 'aktif')->get();
        $donations_sum = Donation::all()->count();
        $foods = round(Food::all()->sum('weight') / 1000);
        $heroes = Hero::all()->sum('quantity');

        $currentDate = Carbon::now();
        $fourMonthsAgo = Carbon::parse(Carbon::now()->format('Y-m'))->subMonths(5);

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

        // return view('pages.form', compact('donations', 'donations_sum', 'foods', 'heroes', 'ig_media', 'ig_user', 'lastData'));
        return view('pages.form', compact('donations', 'donations_sum', 'foods', 'heroes', 'lastData'));
    }

    public function contributor(Request $request)
    {
        try {
            $donation = Donation::find($request['donation_id']);
            if ($donation->remain < $request['quantity']) {
                return back();
            }
            Hero::create([
                'name' => $request['name'],
                'faculty_id' => $request['faculty_id'],
                'donation_id' => $request['donation_id'],
                'quantity' => $request['quantity'],
                'status' => 'sudah',
            ]);
            $donation->remain = $donation->remain - $request['quantity'];

            $donation->save();

            return back()->with('success', 'Berhasil menambahkan kontributor');
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal menambahkan kontributor');
        }
    }

    public function store(Request $request)
    {
        $donation = Donation::find($request['donation']);
        if ($donation->remain == 0) {
            return back()->with('error', 'Gagal mendaftar');
        }
        $request->validate([
            'phone' => 'regex:/^8/',
        ]);
        $request['phone'] = '62' . $request['phone'];
        $code = $this->generate();
        $phone = $donation->heroes->pluck('phone');
        if ($phone->contains($request['phone'])) {
            return back()->with('error', 'Gagal mendaftar');
        }
        $voulunteer = User::all()->pluck('phone');
        if ($voulunteer->contains($request['phone'])) {
            return back()->with('error', 'Gagal mendaftar');
        }
        Hero::create([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'faculty_id' => $request['faculty'],
            'donation_id' => $request['donation'],
            'code' => $code,
            'status' => 'belum',
        ]);
        $donation->remain = $donation->remain - 1;
        $donation->save();
        session(['donation' => $donation->id]);
        session(['code' => $code]);

        return back()->with('success', 'Berhasil mendaftar');
    }

    public function show(Hero $hero)
    {
        return view('pages.hero.show');
    }

    public function update(Request $request, Hero $hero)
    {
        $hero->status = 'sudah';
        $hero->save();

        return back()->with('success', 'Hero telah datang');
    }

    public function destroy(Hero $hero)
    {
        $donation = $hero->donation;
        $donation->remain = $donation->remain + 1;
        $donation->save();
        $hero->delete();

        return back()->with('success', 'Hero batal mengambil');
    }

    public function generate()
    {
        $characters = '1234567890';
        $charactersLength = strlen($characters);
        $uniqueString = '';

        for ($i = 0; $i < 6; $i++) {
            $index = rand(0, $charactersLength - 1);
            $uniqueString .= $characters[$index];
        }

        return $uniqueString;
    }

    public function faculty(Faculty $faculty)
    {
        $heroes = $faculty->heroes()->paginate(50);

        return view('pages.hero.faculty', compact('heroes'));
    }

    public function cancel(Request $request)
    {
        $hero = Hero::where('donation_id', session('donation'))->where('code', session('code'))->first();
        $donation = $hero->donation;
        $donation->remain = $donation->remain + 1;
        $donation->save();
        $hero->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Terimakasih telah membatalkan');
    }
}
