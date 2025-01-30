<?php

namespace App\Http\Controllers;

use App\Models\Donation\Booking;
use App\Models\Donation\Payment;

class ContributorController extends Controller
{
    public function wealth()
    {
        $payments = Payment::paginate(10);
        return view('pages.contributor.wealth', compact('payments'));
    }
    public function food()
    {
        $foodDonators = Booking::orderByRaw("CASE WHEN status = 'waiting' THEN 0 ELSE 1 END")->paginate(10);
        return view('pages.contributor.food', compact('foodDonators'));
    }
    public function people()
    {
        $payments = Payment::paginate(10);
        return view('pages.contributor.people', compact('payments'));
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
