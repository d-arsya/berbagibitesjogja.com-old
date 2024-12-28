<?php

namespace App\Http\Controllers;

use App\Models\Donation\Donation;
use App\Models\Donation\Food;
use App\Models\Heroes\Backup;
use App\Models\Heroes\Hero;
use App\Models\Volunteer\Faculty;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class HeroController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('guest', only: ['create', 'cancel']),
            new Middleware('auth', only: ['index', 'backups', 'contributor', 'show', 'update', 'restore', 'destroy', 'faculty']),
        ];
    }

    public function index()
    {
        $heroes = Hero::paginate(30);
        $donations = Donation::where('status', 'aktif')->get();

        return view('pages.hero.index', compact('donations', 'heroes'));
    }

    public function backups()
    {
        $backups = Backup::orderBy('updated _at', 'desc')->paginate(30);

        return view('pages.hero.backups', ['backups' => $backups]);
    }

    public function create()
    {
        $donations = Donation::where('status', 'aktif')->get();

        $donations_sum = Donation::all()->count();
        $foods = round(Food::all()->sum('weight') / 1000);
        $heroes = Hero::all()->count();

        return view('pages.form', compact('donations', 'donations_sum', 'foods', 'heroes'));
    }

    public function contributor(Request $request)
    {
        $donation = Donation::find($request['donation']);
        if ($donation->remain < $request['quantity']) {
            return back();
        }
        for ($i = 0; $i < $request['quantity']; $i++) {
            Hero::create([
                'name' => $request['name'],
                'faculty' => Faculty::all()->where('name', 'Kontributor')->pluck('id')[0],
                'donation' => $request['donation'],
                'status' => 'sudah',
            ]);
        }
        $donation->remain = $donation->remain - $request['quantity'];

        $donation->save();

        return back();
    }

    public function store(Request $request)
    {
        $donation = Donation::find($request['donation']);
        if ($donation->remain == 0) {
            return back();
        }
        $request->validate([
            'phone' => 'regex:/^8/',
        ]);
        $request['phone'] = '62' . $request['phone'];
        $code = $this->generate();
        $phone = $donation->heroes()->pluck('phone');
        if ($phone->contains($request['phone'])) {
            return back();
        }
        Hero::create([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'faculty' => $request['faculty'],
            'donation' => $request['donation'],
            'code' => $code,
            'status' => 'belum',
        ]);
        $donation->remain = $donation->remain - 1;
        $donation->save();
        session(['donation' => $donation->id]);
        session(['code' => $code]);

        return back();
    }

    public function show(Hero $hero)
    {
        return view('pages.hero.show');
    }

    public function update(Request $request, Hero $hero)
    {
        $hero->status = 'sudah';
        $hero->save();

        return back();
    }

    public function restore(Backup $backup)
    {
        $donation = $backup->donation();
        if ($donation->remain > 0) {
            Hero::create([
                'name' => $backup->name,
                'phone' => $backup->phone,
                'faculty' => $backup->faculty,
                'donation' => $backup->donation,
                'code' => $backup->code,
                'status' => 'belum',
            ]);
            $donation->remain = $donation->remain - 1;
            $donation->save();
            $backup->delete();
        }

        return back();
    }

    public function trash(Backup $backup)
    {
        $backup->delete();

        return back();
    }

    public function destroy(Hero $hero)
    {
        $donation = $hero->donation();
        $donation->remain = $donation->remain + 1;
        $donation->save();
        Backup::create([
            'name' => $hero->name,
            'phone' => $hero->phone,
            'faculty' => $hero->faculty,
            'donation' => $hero->donation,
            'code' => $hero->code,
        ]);
        $hero->delete();

        return back();
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

    public function faculty($faculty)
    {
        $heroes = Hero::where('faculty', $faculty)->get();

        return view('pages.hero.faculty', ['heroes' => $heroes]);
    }

    public function cancel(Request $request)
    {
        $hero = Hero::where('donation', session('donation'))->where('code', session('code'))->first();
        $donation = $hero->donation();
        $donation->remain = $donation->remain + 1;
        $donation->save();
        $hero->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
