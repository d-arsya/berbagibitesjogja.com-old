<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailJob;
use App\Models\Donation\Donation;
use App\Models\Donation\Food;
use App\Models\Heroes\Hero;
use App\Models\Heroes\University;
use App\Models\Volunteer\Division;
use App\Models\Volunteer\Faculty;
use App\Models\Volunteer\Precence;
use App\Models\Volunteer\Program;
use App\Models\Volunteer\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Laravel\Socialite\Facades\Socialite;

class VolunteerController extends Controller
{
    public function home()
    {
        if (! Auth::user()) {
            return redirect()->action([HeroController::class, 'create']);
        }

        $currentDate = Carbon::now();
        $fourMonthsAgo = Carbon::now()->subMonths(7);

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
        $precence = Precence::where('status', 'active')->count();
        $foods = Food::all();
        $volunteers = User::all();
        $heroes = Hero::all();
        $faculties = Faculty::whereNotIn('name', ['Kontributor', 'Lainnya'])->with('heroes')->get();

        return view('pages.volunteer.home', compact('user', 'donations', 'foods', 'volunteers', 'heroes', 'faculties', 'lastData', 'precence'));
    }

    public function index()
    {
        if (Auth::user()->role == 'member') {
            return redirect()->route('volunteer.home');
        }
        $users = User::with(['attendances', 'faculty', 'division'])->get();

        return view('pages.volunteer.index', compact('users'));
    }

    public function create()
    {
        $divisions = Division::all();
        $universities = University::where('variant', 'student')->get();

        return view('pages.volunteer.create', compact('divisions', 'universities'));
    }

    public function store(Request $request)
    {
        $request['phone'] = str_replace('08', '628', $request->phone);
        User::create($request->all());

        return redirect()->route('volunteer.index');
    }

    public function show(User $volunteer)
    {
        $divisions = Division::all();
        $faculties = Faculty::where('university_id', $volunteer->faculty->university->id)->get();

        return view('pages.volunteer.show', compact('volunteer', 'divisions', 'faculties'));
    }

    public function edit(string $id) {}

    public function send()
    {
        Bus::dispatch(new SendMailJob);

        return back();
    }

    public function update(Request $request, User $volunteer)
    {
        $volunteer->update($request->all());

        return redirect()->action([VolunteerController::class, 'logout']);
    }

    public function destroy(User $volunteer)
    {
        $volunteer->delete();

        return back();
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
            return redirect()->route('volunteer.home');
        }
        $volunteer->name = $user->name;
        $volunteer->photo = $user->avatar;
        $volunteer->save();
        Auth::login($volunteer);

        return redirect()->intended('/');
    }
}
