<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);
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
