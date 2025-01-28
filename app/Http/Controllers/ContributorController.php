<?php

namespace App\Http\Controllers;

use App\Models\Donation\Payment;
use Illuminate\Http\Request;

class ContributorController extends Controller
{
    public function wealth()
    {
        $payments = Payment::paginate(10);
        return view('pages.contributor.wealth', compact('payments'));
    }
    public function food()
    {
        // $payments = Payment::paginate(10);
        // return view('pages.contributor.food', compact('payments'));
        return redirect('contributors', 301);
    }
    public function people()
    {
        // $payments = Payment::paginate(10);
        // return view('pages.contributor.people', compact('payments'));
        return redirect('contributors', 301);
    }
}
