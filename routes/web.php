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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/drink', function() {
    $beverages = Beverage::orderBy('updated_at', 'desc')->get();
    $servingsCount = Serving::todayCount();
    $todayAlcohol = Serving::todayAlcohol();
    return view('home', compact('beverages', 'servingsCount', 'todayAlcohol'));
});

Route::get('/beverages', 'BeveragesController@index');
Route::get('/beverages/create', 'BeveragesController@create');
Route::get('/beverages/{beverage}', 'BeveragesController@show');
Route::post('/beverages', 'BeveragesController@store');

Route::get('/servings', 'ServingsController@index');
// Route::get('/servings', 'ServingsController@create');
Route::post('/servings', 'ServingsController@store');
// Route::get('/servings', 'ServingsController@show');
// Route::get('/servings', 'ServingsController@edit');
// Route::get('/servings', 'ServingsController@destroy');

Route::get('about', function() {
    return view('about');
});