<?php

namespace App\Http\Controllers;

use App\Models\Donation\Donation;
use App\Models\Donation\Sponsor;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    public function index(Request $request)
    {
        if ($request['variant']) {
            $sponsors = Sponsor::where('variant', $request['variant'])->with(['donation', 'heroes', 'foods'])->paginate(10);
        } else {
            $sponsors = Sponsor::with(['donation', 'heroes', 'foods'])->paginate(10);
        }

        return view('pages.sponsor.index', compact('sponsors'));
    }

    public function create()
    {
        return view('pages.sponsor.create');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data['variant'] = $request->variant == 'on' ? 'individual' : 'company';
            Sponsor::create($data);

            return redirect()->route('sponsor.index')->with('success', 'Berhasil menambahkan partner');
        } catch (\Throwable $th) {
            return redirect()->route('sponsor.index')->with('error', 'Gagal menambahkan partner');
        }
    }

    public function show(Sponsor $sponsor)
    {
        $sponsor = Sponsor::where('id', $sponsor->id)->with('foods')->first();
        $donations = Donation::where('sponsor_id', $sponsor->id)->with('foods')->paginate(10);

        return view('pages.sponsor.show', compact('sponsor', 'donations'));
    }

    public function edit(Sponsor $sponsor)
    {
        return view('pages.sponsor.edit', compact('sponsor'));
    }

    public function update(Request $request, Sponsor $sponsor)
    {
        try {
            $data = $request->all();
            $data['variant'] = $request->variant == 'on' ? 'individual' : 'company';
            $sponsor->update($data);
            return redirect()->route('sponsor.index')->with('success', 'Berhasil mengubah data partner');
        } catch (\Throwable $th) {
            return redirect()->route('sponsor.index')->with('error', 'Gagal mengubah data partner');
        }
    }

    public function destroy(Sponsor $sponsor)
    {
        try {
            //code...
            if ($sponsor->donation->count() == 0) {
                $sponsor->delete();
            }

            return redirect()->route('sponsor.index')->with('success', 'Berhasil menghapus data partner');
        } catch (\Throwable $th) {
            return redirect()->route('sponsor.index')->with('error', 'Gagal menghapus data partner');
        }
    }
}
