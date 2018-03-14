<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Beverage;
use App\Serving;
use App\User;

class ServingsController extends Controller
{
    public function index()
    {
        $servings = Serving::where('user_id', Auth::id())
            ->latest()
            ->get();

        $days = $servings->groupBy(function ($serving) {
            return substr($serving['local_time'], 0, 10);
        });

        $maxPercent = 100;

        foreach ($days as $day) {
            $dayDrinks = 0;
            $dayAlcohol = 0;
            $dayPercent = 0;

            foreach($day as $serving) {
                $alcohol = $serving->beverage->size *
                  $serving->beverage->strength / 100;

                  $dayDrinks ++;
                  $dayAlcohol += $alcohol;
            }

            $maxConsumption = Auth::user()->maximumConsumption;
            if ($maxConsumption) {
                $dayPercent = $dayAlcohol / $maxConsumption * 100;
            }

            $day->prepend([
                'drinks' => $dayDrinks,
                'alcohol' => $dayAlcohol,
                'percent' => $dayPercent
            ]);

            if($dayPercent > $maxPercent) $maxPercent = $dayPercent;

        }

        return view('servings.index',
            compact('days', 'maxPercent'));
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
