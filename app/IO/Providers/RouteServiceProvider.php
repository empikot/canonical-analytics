<?php

namespace App\IO\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        Route::group(['prefix' => '', 'namespace' => 'App\IO\Http\Controllers'], function () {
            $this->registerWebRoutes();
            $this->registerApiRoutes();
        });
    }

    /**
     * @return void
     */
    private function registerWebRoutes()
    {
        require __ROOT__ . '/routes/web.php';
    }

    /**
     * @return void
     */
    private function registerApiRoutes()
    {
        Route::group(['prefix' => 'api'], function () {
            require __ROOT__ . '/routes/api.php';
        });
    }
}
