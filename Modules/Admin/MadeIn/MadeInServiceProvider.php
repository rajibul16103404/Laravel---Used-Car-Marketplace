<?php

namespace Modules\Admin\MadeIn;

use Illuminate\Support\ServiceProvider;

class MadeInServiceProvider extends ServiceProvider
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