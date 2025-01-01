<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailJob;
use App\Mail\VolunteerRegister;
use App\Models\Donation\Donation;
use App\Models\Donation\Food;
use App\Models\Heroes\Hero;
use App\Models\Volunteer\Division;
use App\Models\Volunteer\Faculty;
use App\Models\Volunteer\Precence;
use App\Models\Volunteer\Program;
use App\Models\Volunteer\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

class VolunteerController extends Controller
{
    public function home()
    {

        $currentDate = Carbon::now();
        $fourMonthsAgo = Carbon::now()->subMonths(5);

        $donations = Donation::whereBetween('take', [$fourMonthsAgo, $currentDate])->with(['food', 'hero'])->get();
        $groupedData = $donations->groupBy(function ($donation) {
            return Carbon::parse($donation->take)->format('Y-m'); // Format tahun-bulan (YYYY-MM)
        });
        $lastData = [];
        foreach ($groupedData as $key => $item) {
            $hero_count = 0;
            $food_count = 0;

            foreach ($item as $data) {
                $hero_count += $data->hero->sum('quantity');
                $food_count += $data->food->sum('weight') / 1000;
            }
            $lastData[] = [
                'bulan' => Carbon::parse($key)->format('F'),
                'heroes' => $hero_count,
                'foods' => $food_count,
            ];
        }
        if (! auth()->user()) {
            return redirect()->action([HeroController::class, 'create']);
        }
        $user = auth()->user();
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
        if (auth()->user()->role == 'member') {
            return redirect()->route('volunteer.home');
        }
        $pendingMails = DB::table('jobs')->count();
        $users = User::with('attendances')->get();

        return view('pages.volunteer.index', compact('users', 'pendingMails'));
    }

    public function create()
    {
        $divisions = Division::all();
        $programs = Program::with('faculty')->get();

        return view('pages.volunteer.create', compact('programs', 'divisions'));
    }

    public function store(Request $request)
    {
        $password = $this->uniqueString();
        $request['phone'] = str_replace('08', '628', $request->phone);
        // $request['password'] = Hash::make('admin');
        $request['password'] = Hash::make($password);
        $user = User::create($request->all());
        // if ($request->hasFile('avatar')) {
        //     $photo = Storage::disk('public')->put('avatar', $request->file('photo'));
        //     $user->photo = $photo;
        //     $user->save();
        // }
        // Mail::to(['email' => $user->email])
        //     ->bcc('kamaluddinarsyadfadllillah@mail.ugm.ac.id')
        //     ->queue(new VolunteerRegister($user, $password));

        return redirect()->route('volunteer.index');
    }

    public function show(User $volunteer)
    {
        $divisions = Division::all();
        $programs = Program::with('faculty')->get();

        return view('pages.volunteer.show', compact('volunteer', 'divisions', 'programs'));
    }

    public function edit(string $id) {}

    public function send()
    {
        Bus::dispatch(new SendMailJob);

        return back();
    }

    public function update(Request $request, User $volunteer)
    {
        $volunteer->update($request->except('password'));
        if ($request->file('photo')) {
            if ($volunteer->photo) {
                Storage::disk('public')->delete($volunteer->photo);
            }
            $photo = Storage::disk('public')->put('avatar', $request->file('photo'));
            $volunteer->photo = $photo;
            $volunteer->save();
        }
        if ($request->password) {
            $volunteer->password = Hash::make($request->password);
            $volunteer->save();

            return redirect()->action([VolunteerController::class, 'logout']);
        }

        return redirect()->route('volunteer.home');
    }

    public function destroy(User $volunteer)
    {
        if ($volunteer->photo) {
            Storage::disk('public')->delete($volunteer->photo);
        }
        $volunteer->delete();

        return back();
    }

    public function login()
    {
        return redirect()->route('auth.google');
        // return view('pages.admin');
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
        // $ceredentials = $request->validate([
        //     'email' => 'required',
        //     'password' => 'required',
        // ]);
        // if (Auth::attempt($ceredentials)) {
        //     $request->session()->regenerate();

        //     return redirect()->intended('/');
        // }

        // return back()->withErrors(['error' => 'gagal']);
    }

    public function uniqueString($length = 20)
    {
        // Karakter yang akan digunakan (angka, huruf, dan simbol)
        // $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%&()-_=+[]{}|;:,.<>?';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz!@#$%&';
        $charactersLength = strlen($characters);
        $randomString = '';

        // Loop untuk membangun string acak
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
