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
        view()->composer('partials.servings-status', function($view) {
            $view->with('todayCount', Serving::todayCount());
            $view->with('todayAlcohol', Serving::todayAlcohol());
            $view->with('todayPercentage', Serving::todayPercentage());
        });
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
