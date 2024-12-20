<?php

namespace Modules\Admin\CarLists;

use Illuminate\Support\ServiceProvider;

class CarlistServiceProvider extends ServiceProvider
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