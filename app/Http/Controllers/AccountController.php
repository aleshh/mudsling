<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AccountController extends Controller
{
    public function index() {

        $hour = now()->hour;
        if ($hour > 12) {
          $hour -= 12;
          $amPm = "pm";
        } else {
          $amPm = "am";
        }

        $minute = now()->minute;
        if ($minute < 10) {
          $minute = "0".$minute;
        }

        return view('account', compact('hour', 'minute', 'amPm'));
    }

    public function update(Request $request) {
        $user = \Auth::user();
        $user->maximumConsumption = $request['maximumConsumption'];

        $hourDiff   = $request['userHour'] - $request['originalHour'];
        $minuteDiff = $request['userMinute'] - $request['originalMinute'];

        dd($hourDiff.":".$minuteDiff);
        $userTime = new Carbon($request['userTime']);

        dd("change!");
        $user->save();

        return redirect('/');
    }
}
