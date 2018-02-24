<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Beverage;
use App\Serving;
use Carbon\Carbon;

class ServingController extends Controller
{
    public function index()
    {
        $servings = Serving::where('created_at', '>=', Carbon::today())->latest()->get();
        $oldServings = Serving::where('created_at', '<', Carbon::today())->latest()->get();

        return view('servings.index', compact('servings', 'oldServings'));
    }

    public function store(Request $request)
    {
        Serving::create(request(['beverage_id']));

        $beverage = Beverage::find(request('beverage_id'));
        $beverage->updated_at = Carbon::now();
        $beverage->save();

        return redirect('/');
    }
}
