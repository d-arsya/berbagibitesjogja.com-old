<?php

namespace App\Http\Controllers;

use App\Models\Volunteer\Availability;
use App\Models\Volunteer\User;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    protected $timecode = ["170", "175", "180", "185", "190", "195", "1100", "1105", "1110", "1115", "1120", "1125", "1130", "1135", "1140", "1145", "1150", "1155", "1160", "1165", "1170", "1175", "1180", "1185", "1190", "1195", "1200", "1205", "1210", "1215", "270", "275", "280", "285", "290", "295", "2100", "2105", "2110", "2115", "2120", "2125", "2130", "2135", "2140", "2145", "2150", "2155", "2160", "2165", "2170", "2175", "2180", "2185", "2190", "2195", "2200", "2205", "2210", "2215", "370", "375", "380", "385", "390", "395", "3100", "3105", "3110", "3115", "3120", "3125", "3130", "3135", "3140", "3145", "3150", "3155", "3160", "3165", "3170", "3175", "3180", "3185", "3190", "3195", "3200", "3205", "3210", "3215", "470", "475", "480", "485", "490", "495", "4100", "4105", "4110", "4115", "4120", "4125", "4130", "4135", "4140", "4145", "4150", "4155", "4160", "4165", "4170", "4175", "4180", "4185", "4190", "4195", "4200", "4205", "4210", "4215", "570", "575", "580", "585", "590", "595", "5100", "5105", "5110", "5115", "5120", "5125", "5130", "5135", "5140", "5145", "5150", "5155", "5160", "5165", "5170", "5175", "5180", "5185", "5190", "5195", "5200", "5205", "5210", "5215", "670", "675", "680", "685", "690", "695", "6100", "6105", "6110", "6115", "6120", "6125", "6130", "6135", "6140", "6145", "6150", "6155", "6160", "6165", "6170", "6175", "6180", "6185", "6190", "6195", "6200", "6205", "6210", "6215", "770", "775", "780", "785", "790", "795", "7100", "7105", "7110", "7115", "7120", "7125", "7130", "7135", "7140", "7145", "7150", "7155", "7160", "7165", "7170", "7175", "7180", "7185", "7190", "7195", "7200", "7205", "7210", "7215"];
    public function dashboard()
    {
        $avail = Availability::all()->groupBy(['day', 'code']);
        $availabilities = Auth::user()->availabilities->pluck('code')->toArray();
        $code = $this->timecode;
        return view('pages.availability.dashboard', compact('availabilities', 'code', 'avail'));
    }
    public function time($time)
    {
        $avails = Availability::where('code', $time)->get();
        $users = User::whereIn('id', $avails->pluck('user_id'))->get();
        $avail = Availability::all()->groupBy(['day', 'code']);
        $code = $this->timecode;
        return view('pages.availability.time', compact('code', 'avail', 'avails', 'users', 'time'));
    }
    public function update($codes, $status)
    {
        $data = $this->getCode($codes);
        $data["user_id"] = Auth::user()->id;
        $avail = Availability::where('code', $codes)->where('user_id', Auth::user()->id)->first();
        if ($status == "true") {
            if ($avail) {
                return true;
            }
            Availability::create($data);
            return true;
        }
        if (!$avail) {
            return true;
        }
        $avail->delete();
    }

    protected function getCode($codes)
    {
        $code = str_split($codes);
        $day = array_shift($code);
        $minute = array_pop($code) == 0 ? 0 : 30;
        $hour = implode($code);
        $code = $codes;
        return compact('code', 'day', 'minute', 'hour');
    }
}
