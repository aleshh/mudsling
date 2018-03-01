<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index() {
        return view('account');
    }

    public function update(Request $request) {
        $user = \Auth::user();
        $user->maximumConsumption = $request['maximumConsumption'];
        $user->save();

        return redirect('/');
    }
}
