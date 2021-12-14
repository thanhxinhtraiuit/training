<?php

namespace B;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class BServiceProvider extends ServiceProvider
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
        Route::group(['prefix' => 'b'], function() {
            require __DIR__.'/routes.php';
        });
    }
}
