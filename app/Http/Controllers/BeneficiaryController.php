<?php

namespace App\Http\Controllers;

use App\Models\Heroes\University;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    public function index(Request $request)
    {
        if ($request["variant"]) {
            $beneficiaries = University::where('variant', $request["variant"])->with(['heroes'])->paginate(10);
        } else {
            $beneficiaries = University::with(['heroes'])->paginate(10);
        }
        return view('pages.beneficiary.index', compact('beneficiaries'));
    }
}
