<?php

namespace Common;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CommonServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $arrRepoName = [
            'GuidRepository',
        ];

        foreach ($arrRepoName as $repoName) {
            $this->app->bind("Common\\Repositories\\Contracts\\{$repoName}Interface", "Common\\Repositories\\{$repoName}");
        }

    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::group(['prefix' => 'product', 'namespace' => 'Common\\Controllers'], function() {
            require __DIR__."/routes.php";
        });
    }
}
