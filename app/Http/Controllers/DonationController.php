<?php

namespace App\Http\Controllers;

use App\Models\Donation\Donation;
use App\Models\Donation\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DonationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', only: ['create', 'store', 'show', 'edit', 'update', 'destroy']),
        ];
    }

    public function index()
    {
        $donations = Donation::orderByRaw("CASE WHEN status = 'aktif' THEN 0 WHEN status = 'selesai' THEN 1 ELSE 2 END")
            ->orderBy('take')
            ->paginate(10);

        return view('pages.donation.index', compact('donations'));
    }

    public function create()
    {
        $sponsors = Sponsor::whereNot('status', 'done')->get();

        return view('pages.donation.create', compact('sponsors'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['remain'] = $request->quota;
        $data['status'] = 'aktif';
        $donation = Donation::create($data);
        $sponsor = $donation->sponsor();
        if ($sponsor->status == 'pending') {
            $sponsor->status = 'done';
            $sponsor->save();
        }

        return redirect(route('donation.index'));
    }

    public function show(Donation $donation)
    {
        $heroes = $donation->heroes();
        $foods = $donation->foods();

        return view('pages.donation.show', compact('donation', 'foods', 'heroes'));
    }

    public function edit(Donation $donation)
    {
        return view('pages.donation.edit', compact('donation'));
    }

    public function update(Request $request, Donation $donation)
    {
        if ($request->notes) {
            $donation->notes = $request->notes;
            $donation->save();

            return back();
        }
        $donation->take = $request->take;
        $donation->status = $request->status;
        $donation->hour = $request->hour;
        $donation->minute = $request->minute;
        $donation->message = $request->message;

        if ($request->add) {
            $donation->remain = $donation->remain + $request->add;
            $donation->quota = $donation->quota + $request->add;
        }
        if ($request->diff) {
            $donation->remain = $donation->remain - $request->diff;
            $donation->quota = $donation->quota - $request->diff;
        }
        $donation->save();

        return redirect(route('donation.index'));
    }

    public function destroy(Donation $donation)
    {
        if ($donation->heroes()->count() == 0 && $donation->foods()->count() == 0) {
            $donation->delete();
        }

        return redirect(route('donation.index'));
    }
}
