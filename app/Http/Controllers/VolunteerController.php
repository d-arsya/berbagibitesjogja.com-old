<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailJob;
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
use Laravel\Socialite\Facades\Socialite;

class VolunteerController extends Controller
{
    public function home()
    {
        if (! Auth::user()) {
            return redirect()->action([HeroController::class, 'create']);
        }

        $currentDate = Carbon::now();
        $fourMonthsAgo = Carbon::now()->subMonths(5);

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
        $users = User::with('attendances')->get();

        return view('pages.volunteer.index', compact('users'));
    }

    public function create()
    {
        $divisions = Division::all();
        $programs = Program::with('faculty')->get();

        return view('pages.volunteer.create', compact('programs', 'divisions'));
    }

    public function store(Request $request)
    {
        $request['phone'] = str_replace('08', '628', $request->phone);
        User::create($request->all());

        return redirect()->route('volunteer.index');
    }
    public function fromFonnte()
    {
        // header('Content-Type: application/json; charset=utf-8');
        // $json = file_get_contents('php://input');
        // $data = json_decode($json, true);
        // $sender = $data['sender'];
        // $message = $data['message'];
        // if ($message == "@BOT halo") {
        //     $reply = "Halo juga";
        // } elseif ($message == "@BOT tes") {
        //     $reply = "Jalan kok";
        // }
        return 1;
        // $this->kirimWa($sender, $reply);
    }
    public function sendWa()
    {
        $message = 'Gatau juga
ini apaan';
        $phone = '089636055420';
        $this->kirimWa($phone, $message);
    }
    public function kirimWa($target, $message)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $message,
                'schedule' => 0,
                'typing' => false,
                'delay' => '2',
                'countryCode' => '62',
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . env('FONNTE_KEY', null)
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if (isset($error_msg)) {
            echo $error_msg;
        }
        echo $response;
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
