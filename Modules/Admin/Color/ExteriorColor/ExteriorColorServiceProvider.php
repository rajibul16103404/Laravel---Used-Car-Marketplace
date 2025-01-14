<?php

namespace Modules\Admin\Color\ExteriorColor;

use Illuminate\Support\ServiceProvider;

class ExteriorColorServiceProvider extends ServiceProvider
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