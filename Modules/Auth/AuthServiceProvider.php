<?php

namespace Modules\Auth;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/Views', 'auth');
    }

    public function register()
    {
        // Register bindings or services here if needed
    }
}
