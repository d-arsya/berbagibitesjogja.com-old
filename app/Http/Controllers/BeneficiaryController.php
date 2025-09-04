<?php

namespace App\Http\Controllers;

use App\Models\Donation\Donation;
use App\Models\Heroes\University;
use App\Models\Volunteer\Faculty;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    public function index(Request $request)
    {
        if ($request['variant']) {
            $beneficiaries = University::where('variant', $request['variant'])->with(['heroes'])->paginate(10);
        } else {
            $beneficiaries = University::with(['heroes'])->paginate(20);
        }

        return view('pages.beneficiary.index', compact('beneficiaries'));
    }

    public function show(University $beneficiary)
    {
        $heroes = $beneficiary->heroes->unique('donation_id')->pluck('donation_id');
        $donations = Donation::whereIn('id', $heroes)->with(['sponsor', 'foods'])->paginate(10, ['*'], 'donations');
        $faculties = $beneficiary->faculties()->paginate(10, ['*'], 'faculties');

        return view('pages.beneficiary.show', compact('beneficiary', 'donations', 'faculties'));
    }

    public function create()
    {
        return view('pages.beneficiary.create');
    }

    public function edit(University $beneficiary)
    {
        return view('pages.beneficiary.edit', compact('beneficiary'));
    }

    public function store(Request $request)
    {
        try {
            if ($request->university_id) {
                Faculty::create(['name' => $request->name, 'university_id' => $request->university_id]);

                return redirect()->back()->with('success', 'Berhasil menambahkan fakultas atau sektor');
            }
            $university = University::create($request->all());
            if ($university->variant == 'foundation') {
                Faculty::create(['name' => $university->name, 'university_id' => $university->id]);
            }

            return redirect()->route('beneficiary.index')->with('success', 'Berhasil menambahkan beneficiary');
        } catch (\Throwable $th) {
            return redirect()->route('beneficiary.index')->with('error', 'Gagal menambahkan beneficiary');
        }
    }

    public function update(Request $request, University $beneficiary)
    {
        try {
            $beneficiary->update($request->all());

            return redirect()->route('beneficiary.index')->with('success', 'Berhasil mengubah beneficiary');
        } catch (\Throwable $th) {
            return redirect()->route('beneficiary.index')->with('error', 'Gagal mengubah beneficiary');
        }
    }
    public function destroy(University $beneficiary)
    {
        if ($beneficiary->faculties->count() == 0) {
            $beneficiary->delete();
        } else {

            return redirect()->route('beneficiary.index')->with('error', 'Gagal menghapus beneficiary');
        }

        return redirect()->route('beneficiary.index')->with('success', 'Berhasil menghapus beneficiary');
    }
}
