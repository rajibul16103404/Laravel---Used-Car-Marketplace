<?php

namespace Modules\Admin\Color\InteriorColor;

use Illuminate\Support\ServiceProvider;

class InteriorColorServiceProvider extends ServiceProvider
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