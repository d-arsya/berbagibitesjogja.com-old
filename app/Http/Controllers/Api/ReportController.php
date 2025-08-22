<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation\Sponsor;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getDonations(Sponsor $sponsor, $start, $end)
    {
        $donations = $sponsor->donation()->with(['foods', 'heroes'])->whereBetween('take', [$start, $end])->get();
        $totalWeight = 0;
        $totalFood = 0;
        $totalHero = 0;
        $totalAction = $donations->count();
        foreach ($donations as $item) {
            $total = $item->foods->sum('weight');
            $item->foodWeight = $total;
            $totalWeight += $total;
            $total = $item->foods->count();
            $item->foodQuantity += $total;
            $totalFood += $total;
            $total = $item->heroes->sum('quantity');
            $item->heroQuantity += $total;
            $totalHero += $total;
        }
        return response()->json(compact('totalWeight', 'totalFood', 'totalHero', 'totalAction', 'donations'));
    }
}
