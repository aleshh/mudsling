<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Beverage;

class BeveragesController extends Controller
{
    public function index() {
        $id = Auth::id();
        $beverages = Beverage::where('user_id', $id)
            ->where('deleted', 0)
            ->latest()
            ->get();
        return view('beverages.index', compact('beverages'));
    }

    public function show(Beverage $beverage) {
        return view('beverages.show', compact('beverage'));
    }

    public function create() {
        $beverage = new Beverage;
        return view('beverages.form', compact('beverage'));
    }

    public function edit(Beverage $beverage) {
        return view('beverages.form', compact('beverage'));
    }

    public function destroy(Beverage $beverage) {
        if ($beverage->servings->count()) {
            $beverage->deleted = true;
            $beverage->save();
        } else {
            $beverage->delete();
        }
        return redirect('/beverages');
    }

    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'required|unique:beverages',
            'category' => 'required',
            'size' => 'required|numeric',
            'strength' => 'required|numeric'
        ]);

        $request['user_id'] = Auth::id();

        Beverage::create(
            request(['user_id', 'name', 'category', 'size', 'strength'])
        );

        return redirect('/');
    }

    public function update(Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'category' => 'required',
            'size' => 'required|numeric',
            'strength' => 'required|numeric'
        ]);

        $beverage = Beverage::findOrFail($request['id']);

        $beverage->name = $request['name'];
        $beverage->category = $request['category'];
        $beverage->size = $request['size'];
        $beverage->strength = $request['strength'];
        $beverage->save();

        return redirect('/beverages');
    }
}
