<?php

namespace App\Http\Controllers;

use App\Models\Volunteer\Attendance;
use App\Models\Volunteer\Precence;
use Ballen\Distical\Calculator;
use Ballen\Distical\Entities\LatLong;
use Illuminate\Http\Request;

class PrecenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function calculateDistance($startLat, $startLong, $endLat, $endLong)
    {
        $ipswich = new LatLong($startLat, $startLong);
        $london = new LatLong($endLat, $endLong);
        $distanceCalculator = new Calculator($ipswich, $london);
        $distance = $distanceCalculator->get();
        return round($distance->asKilometres() * 1000);
    }
    public function userAttendance(Request $request)
    {
        $precence = Precence::where('code', $request->precenceCode)->where('status', 'active')->get();
        if ($precence->count() == 0) {
            return response()->json(["message" => "Presensi tidak ditemukan", "data" => $request->all()], 404);
        }
        $precence = $precence[0];
        $distance = $this->calculateDistance($request->precenceLat, $request->precenceLong, $request->userLat, $request->userLong);
        if ($distance > $precence->max_distance) {
            return response()->json(["message" => "Presensi tidak ditemukan", "data" => $request->all()], 404);
        }
        if (Attendance::where('user_id', auth()->user()->id)->where('precence_id', $precence->id)->get()->count() == 1) {
            return response()->json([$request->all(), $precence], 200);
        }
        try {
            Attendance::create([
                'user_id' => auth()->user()->id,
                'precence_id' => $precence->id,
                'distance' => $distance
            ]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 404);
        }
        return response()->json([$request->all(), $precence], 200);
    }
    public function index()
    {
        $precences = Precence::orderBy('status')->paginate(10);
        return view('pages.precence.index', compact('precences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        return view('pages.precence.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['code'] = $this->uniqueString();
        Precence::create($data);
        return redirect()->route('precence.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Precence $precence)
    {
        $user = auth()->user();
        return view('pages.precence.show', compact('precence'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Precence $precence)
    {
        $user = auth()->user();
        return view('pages.precence.edit', compact('precence', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Precence $precence)
    {
        $precence->update($request->all());
        return redirect()->route('precence.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function getQrCode()
    {
        $precence = Precence::where('status', 'active')->get()[0];
        return view('pages.precence.downloadQr', compact('precence'));
    }
    public function uniqueString($length = 20)
    {
        // Karakter yang akan digunakan (angka, huruf, dan simbol)
        // $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%&()-_=+[]{}|;:,.<>?';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        // Loop untuk membangun string acak
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
