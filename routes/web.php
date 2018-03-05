<?php

use App\Beverage;
use App\Serving;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/drink');
    } else {
        return view('welcome');
    }
});

Route::middleware('auth')->group(function() {
    Route::get('/account', 'AccountController@index');
    Route::patch('/account', 'AccountController@update');

    Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

    Route::get('/beverages', 'BeveragesController@index');
    Route::get('/beverages/create', 'BeveragesController@create');
    Route::get('/beverages/{beverage}', 'BeveragesController@show');
    Route::post('/beverages', 'BeveragesController@store');
    Route::patch('/beverages', 'BeveragesController@update');
    Route::get('/beverages/{beverage}/edit', 'BeveragesController@edit');
    Route::delete('/beverages/{beverage}', 'BeveragesController@destroy');

    Route::get('/drink', 'ServingsController@create');
    Route::get('/servings', 'ServingsController@index');
    Route::post('/servings', 'ServingsController@store');
});

Route::get('about', function() {
    return view('about');
});