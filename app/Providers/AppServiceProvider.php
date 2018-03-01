<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Serving;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layouts.app', function($view) {
            $view->with('todayCount', Serving::todayCount());
            $view->with('todayAlcohol', Serving::todayAlcohol());
        });
        // dd(\Auth::id());
        // $todayCount = Serving::todayCount();
        // $todayAlcohol = Serving::todayAlcohol();
        // \View::share('todayCount', $todayCount);
        // \View::share('todayAlcohol', $todayAlcohol);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
