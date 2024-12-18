<?php

namespace Modules\Admin\Body_Type;

use Illuminate\Support\ServiceProvider;

class Body_TypeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/Views', 'body_type');
    }

    public function register()
    {
        // Register bindings or services here if needed
    }
}