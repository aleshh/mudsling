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

        return view('servings.index', compact('servings', 'oldServings'));
    }

    public function create() {
        $beverages = Beverage::where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get();

        $servingsCount = Serving::todayCount();
        $todayAlcohol = Serving::todayAlcohol();
        return view('servings.create',
            compact('beverages', 'servingsCount', 'todayAlcohol'));
    }

    public function store(Request $request)
    {
        // dd(Auth::id());
        $request['user_id'] = Auth::id();

        Serving::create(request(['user_id', 'beverage_id']));

        // $beverage = Beverage::find(request('beverage_id'));
        // $beverage->updated_at = Carbon::now();
        // $beverage->save();

        return redirect('/');
    }
}
