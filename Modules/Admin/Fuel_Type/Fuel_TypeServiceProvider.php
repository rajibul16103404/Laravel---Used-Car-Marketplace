<?php

namespace Modules\Admin\Fuel_Type;

use Illuminate\Support\ServiceProvider;

class Fuel_TypeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/Views', 'color');
    }

    public function register()
    {
        // Register bindings or services here if needed
    }
}