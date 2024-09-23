<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->app->singleton('firebase', function ($app) {
            return (new Factory)
                ->withServiceAccount('E:\wamp64\www\biriyani_palayam\storage\pushnoti-e459b-firebase-adminsdk-2v48c-7833c5a4f3.json')
                ->create();
        });
    }
}
