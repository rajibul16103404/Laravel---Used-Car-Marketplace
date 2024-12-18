<?php

namespace Modules\Admin\Cylinders;

use Illuminate\Support\ServiceProvider;

class CylinderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/Views', 'cylinder');
    }

    public function register()
    {
        // Register bindings or services here if needed
    }
}