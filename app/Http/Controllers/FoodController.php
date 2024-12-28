<?php

namespace App\Http\Controllers;

use App\Models\Donation\Donation;
use App\Models\Donation\Food;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FoodController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    public function index()
    {
        $donations = Donation::where('status', 'aktif')->get();
        $foods = Food::orderBy('id', 'desc')->paginate(10);
        $total = Food::totalGram();

        return view('pages.food.index', compact('donations', 'foods', 'total'));
    }

    public function store(Request $request)
    {
        Food::create($request->all());

        return back();
    }

    public function edit(Food $food)
    {
        $donation = $food->donation();
        $foods = Food::orderBy('id', 'desc')->paginate(10);

        return view('pages.food.edit', compact('donations', 'food', 'foods'));
    }

    public function update(Request $request, Food $food)
    {
        $food->update($request->all());

        return redirect(route('food.index'));
    }

    public function destroy(Food $food)
    {
        $food->delete();

        return back();
    }
}
