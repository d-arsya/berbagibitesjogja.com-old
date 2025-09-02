<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotifyController extends Controller
{
    public function form(Request $request)
    {
        if ($request->phone) {
            session()->put('phone', "62" . $request->phone);
            return redirect()->route('auth.google');
        }
        return view('pages.notify');
    }
}
