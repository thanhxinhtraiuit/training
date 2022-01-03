<?php

namespace Accounting;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AccountingServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $arrRepoName = [
            'UserRepository',
            'PermissionRepository',
            'RoleRepository'
        ];

        foreach ($arrRepoName as $repoName) {
            $this->app->bind("Accounting\\Repositories\\Contracts\\{$repoName}Interface", "Accounting\\Repositories\\{$repoName}");
        }

    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::group(['namespace' => 'Accounting\\Controllers'], function() {
            require __DIR__."/routes.php";
        });
    }
}
