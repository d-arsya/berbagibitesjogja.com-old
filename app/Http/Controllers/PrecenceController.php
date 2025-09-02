<?php

namespace App\Http\Controllers;

use App\Models\Volunteer\Attendance;
use App\Models\Volunteer\Precence;
use Ballen\Distical\Calculator;
use Ballen\Distical\Entities\LatLong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrecenceController extends Controller
{
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
            return response()->json(['message' => 'Presensi tidak ditemukan', 'data' => $request->all()], 404);
        }
        $precence = $precence[0];
        $distance = $this->calculateDistance($request->precenceLat, $request->precenceLong, $request->userLat, $request->userLong);
        if (Attendance::where('user_id', Auth::user()->id)->where('precence_id', $precence->id)->get()->count() == 1) {
            return response()->json([$request->all(), $precence], 200);
        }
        try {
            Attendance::create([
                'user_id' => Auth::user()->id,
                'precence_id' => $precence->id,
                'distance' => $distance,
            ]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 404);
        }

        return response()->json([$request->all(), $precence], 200);
    }

    public function index()
    {
        $precences = Precence::orderBy('status')->orderBy('created_at')->paginate(10);

        return view('pages.precence.index', compact('precences'));
    }

    public function create()
    {
        $user = Auth::user();

        return view('pages.precence.create', compact('user'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['code'] = $this->uniqueString();
        Precence::create($data);

        return redirect()->route('precence.index')->with('success', 'Berhasil membuat presensi baru');
    }

    public function show(Precence $precence)
    {
        $user = Auth::user();
        $attendances = $precence->attendance();

        return view('pages.precence.show', compact('precence', 'attendances'));
    }

    public function edit(Precence $precence)
    {
        $user = Auth::user();

        return view('pages.precence.edit', compact('precence', 'user'));
    }

    public function update(Request $request, Precence $precence)
    {
        if ($request->has('attendance_id')) {
            $attendance = Attendance::find($request->attendance_id);
            $attendance->point = $request->point;
            $attendance->save();

            return redirect()->back();
        }
        $precence->update($request->all());

        return redirect()->route('precence.index')->with('success', 'Berhasil mengubah data presensi');
    }

    public function getQrCode()
    {
        $precence = Precence::where('status', 'active')->get()[0];

        return view('pages.precence.downloadQr', compact('precence'));
    }

    public function uniqueString($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
