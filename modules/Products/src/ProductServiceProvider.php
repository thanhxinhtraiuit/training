<?php

namespace Products;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $arrRepoName = [
            'ProductRepository',
        ];

        foreach ($arrRepoName as $repoName) {
            $this->app->bind("Products\\Repositories\\Contracts\\{$repoName}Interface", "Products\\Repositories\\{$repoName}");
        }
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::group(['prefix' => 'api', 'namespace' => 'Products\\Controllers'], function () {
            require __DIR__ . "/routes.php";
        });
    }
}
