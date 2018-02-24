<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Beverage;

class BeverageController extends Controller
{
    public function index() {
        // $beverages = Beverage::all();
        // $beverages = Beverage::orderBy('updated_at', 'desc')->get();
        $beverages = Beverage::latest()->get();
        return view('beverages.index', compact('beverages'));
    }

    public function show(Beverage $beverage) {
        return view('beverages.show', compact('beverage'));
    }

    public function create() {
        return view('beverages.create');
    }

    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'required|unique:beverages',
            'category' => 'required',
            'size' => 'required|numeric',
            'strength' => 'required|numeric'
        ]);

        // $beverage = new Beverage;
        // $beverage->name = request('name');
        // $beverage->category = request('category');
        // $beverage->size = request('size');
        // $beverage->strength = request('strength');
        // $beverage->save();

        Beverage::create(request(['name', 'category', 'size', 'strength']));

        return redirect('/');
    }
}
