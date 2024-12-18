<?php

namespace Modules\Admin\Doors;

use Illuminate\Support\ServiceProvider;

class DoorServiceProvider extends ServiceProvider
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