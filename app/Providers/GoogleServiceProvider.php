<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Google\Client;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        if (function_exists('config_path')) {
            $this->publishes([
                dirname(dirname(dirname(__FILE__))) . '/config/google.php' => config_path('google.php'),
            ], 'config');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(dirname(dirname(dirname(__FILE__))) . '/config/google.php', 'google');
        $this->app->singleton('App\Google\Client', function ($app) {
            return new Client($app['config']['google']);
        });
    }
    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return ['App\Google\Client'];
    }
}
