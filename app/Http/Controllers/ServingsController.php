<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Beverage;
use App\Serving;

class ServingsController extends Controller
{
    public function index()
    {
        $servings =    Serving::where('created_at', '>=', Carbon::today())
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $oldServings = Serving::where('created_at', '<',  Carbon::today())
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('servings.index',
            compact('servings', 'oldServings', 'todayCount', 'todayAlcohol'));
    }

    public function create() {
        $beverages = Beverage::where('user_id', Auth::id())
            ->where('deleted', 0)
            ->orderBy('updated_at', 'desc')
            ->get();

        // if we don't have any beverages, redirect them to create one
        if (!$beverages->count()) return redirect('/beverages/create');

        return view('servings.create',
            compact('beverages', 'todayCount', 'todayAlcohol'));
    }

    public function store(Request $request)
    {
        $request['user_id'] = Auth::id();

        Serving::create(request(['user_id', 'beverage_id']));

        $beverage = Beverage::findOrFail(request('beverage_id'));
        $beverage->updated_at = Carbon::now();
        $beverage->save();

        return redirect('/');
    }
}
