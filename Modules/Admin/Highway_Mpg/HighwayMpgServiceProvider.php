<?php

namespace Modules\Admin\Highway_Mpg;

use Illuminate\Support\ServiceProvider;

class HighwayMpgServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/Views', 'category');
    }

    public function register()
    {
        // Register bindings or services here if needed
    }
}