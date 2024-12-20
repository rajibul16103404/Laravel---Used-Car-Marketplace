<?php

namespace Modules\WhatsappBot;

use Illuminate\Support\ServiceProvider;

class WhatsappBotServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/Views', 'blog');
    }

    public function register()
    {
        // Register bindings or services here if needed
    }
}
