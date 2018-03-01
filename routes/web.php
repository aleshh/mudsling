<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Beverage;
use App\Serving;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/drink');
    } else {
        return view('welcome');
    }
});

Auth::routes();

Route::get('/account', 'AccountController@index')->middleware('auth');
Route::patch('/account', 'AccountController@update')->middleware('auth');

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/beverages', 'BeveragesController@index')->middleware('auth');
Route::get('/beverages/create', 'BeveragesController@create')->middleware('auth');
Route::get('/beverages/{beverage}', 'BeveragesController@show')->middleware('auth');
Route::post('/beverages', 'BeveragesController@store')->middleware('auth');

Route::get('/servings', 'ServingsController@index')->middleware('auth');
Route::get('/drink', 'ServingsController@create')->middleware('auth');
Route::post('/servings', 'ServingsController@store')->middleware('auth');
// Route::get('/servings', 'ServingsController@show');
// Route::get('/servings', 'ServingsController@edit');
// Route::get('/servings', 'ServingsController@destroy');

Route::get('about', function() {
    return view('about');
});