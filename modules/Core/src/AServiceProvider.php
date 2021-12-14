<?php

namespace A;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AServiceProvider extends ServiceProvider
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
        Route::group(['prefix' => 'a'], function() {
            require __DIR__.'route.php';
        });
    }
}
