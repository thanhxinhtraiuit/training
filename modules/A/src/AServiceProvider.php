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
        $arrRepoName = [
            'ARepository',
        ];

        $arrServicesName = [
            'AService',
        ];

        foreach ($arrRepoName as $repoName) {
            $this->app->bind("A\\Repositories\\Contracts\\{$repoName}Interface", "A\\Repositories\\{$repoName}");
        }

        foreach ($arrServicesName as $service) {
            $this->app->bind("A\\Services\\Contracts\\{$service}Interface", "A\\Services\\{$service}");
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::group(['prefix' => 'a', 'namespace' => 'A\\Controllers'], function() {
            require __DIR__."/routes.php";
        });
    }
}
