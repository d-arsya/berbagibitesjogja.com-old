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
        $fourMonthsAgo = Carbon::parse(Carbon::now()->format('Y-m'))->subMonths(6);

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
        $faculties = Faculty::where('university_id', 1)->whereNotIn('name', ['Volunteer', 'RZIS', 'Lainnya'])->with('heroes')->get();

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
        try {
            $request['phone'] = str_replace('08', '628', $request->phone);
            User::create($request->all());

            return redirect()->route('volunteer.index')->with('success', 'Berhasil menambahkan volunteer');
        } catch (\Throwable $th) {
            return redirect()->route('volunteer.index')->with('error', 'Gagal menambahkan volunteer');
        }
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
        try {
            $volunteer->update($request->all());

            return redirect()->action([VolunteerController::class, 'logout'])->with('success', 'Berhasil mengubah data user');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal mengubah data user');
        }
    }

    public function destroy(User $volunteer)
    {
        try {
            $volunteer->delete();

            return back()->with('success', 'Berhasil menghapus user');
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal menghapus user');
        }
    }

    public function login()
    {
        return redirect()->route('auth.google');
    }

    public function logout(Request $request)
    {
        $volunteer = User::find(Auth::user()->id);
        activity()
        ->causedBy($volunteer)
        ->performedOn($volunteer)
        ->createdAt(now())
        ->event('authentication')
        ->log('Logout');
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
        $volunteer->name = $user->name;
        $volunteer->photo = $user->avatar;
        $volunteer->save();
        Auth::login($volunteer);
        activity()
        ->causedBy($volunteer)
        ->performedOn($volunteer)
        ->createdAt(now())
        ->event('authentication')
        ->log('Login');

        return redirect()->intended('/')->with('success', 'Berhasil login');
    }
}
