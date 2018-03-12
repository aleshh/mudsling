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

        $clientTime = new Carbon($_COOKIE['clientTime']);
        $clientOffset = $clientTime->timezone->getName();
        $drinkTime = new Carbon();
        $drinkTime->setTimezone($clientOffset);

        $request['local_time'] = $drinkTime;

        Serving::create(request(['user_id', 'beverage_id', 'local_time']));

        return redirect('/');
    }
}
