<?php

namespace A17\Twill\API;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'A17\\Twill\API\\Http\\Controllers';

    protected $controllerNamespace = 'App\\Http\\Controllers\\API';

    public function register()
    {
        $this->registerMacros();

        parent::boot();
    }

    /**
     * Bootstraps the package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();

        parent::boot();
    }

    protected function registerMacros()
    {
        $controllerNamespace = $this->controllerNamespace;

        Route::macro('moduleResource', function ($moduleName) use ($controllerNamespace) {
            $versionPrefix = config('twill.api.version');
            $modelName = Str::ucfirst(Str::singular($moduleName));
            $route = $versionPrefix . '/'. $moduleName;
            $controller = $controllerNamespace.'\\'.$modelName.'Controller@list';

            Route::get($route, $controller);
        });
    }

    protected function registerRoutes()
    {
        Route::prefix('api')
            ->middleware(['api'])
            ->namespace($this->namespace)
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
            });
    }
}
