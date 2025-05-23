<?php

namespace App\Http\Controllers;

use App\Models\Donation\Donation;
use App\Models\Donation\Food;
use App\Models\Heroes\Hero;
use App\Models\Volunteer\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class VolunteerController extends Controller
{
    public function home()
    {
        if (! Auth::user()) {
            return redirect()->action([HeroController::class, 'create']);
        }

        $currentDate = Carbon::now();
        $fourMonthsAgo = Carbon::parse(Carbon::now()->format('Y-m'))->subMonths(9);

        $donations = Donation::whereBetween('take', [$fourMonthsAgo, $currentDate])->with(['foods', 'heroes'])->get();
        $groupedData = $donations->groupBy(function ($donation) {
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

        $user = Auth::user();
        $donations = Donation::all();
        $foods = Food::whereIn('donation_id', $donations->pluck('id'))->get();
        $heroes = Hero::all();
        return view('pages.volunteer.home', compact('user', 'donations', 'foods', 'heroes', 'lastData'));
    }

    public function login()
    {
        return redirect()->route('auth.google');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('volunteer.home');
    }

    public function authenticate(Request $request)
    {
        $user = Socialite::driver('google')->user();
        $volunteer = User::where('email', $user->email)->first();
        if (! $volunteer) {
            return redirect()->route('volunteer.home')->with('error', 'Anda tidak terdaftar');
        }
        Auth::login($volunteer);

        return redirect()->intended('/')->with('success', 'Berhasil login');
    }
}
