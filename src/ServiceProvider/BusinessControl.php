<?php

namespace MadeITBelgium\BusinessControl\ServiceProvider;

use Illuminate\Support\ServiceProvider;
use MadeITBelgium\BusinessControl\BusinessControl as BC;

/**
 * Business Control PHP SDK
 *
 * @version    0.0.1
 *
 * @copyright  Copyright (c) 2018 Made I.T. (http://www.madeit.be)
 * @author     Made I.T. <info@madeit.be>
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-3.txt    LGPL
 */
class BusinessControl extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/business-control.php' => config_path('business-control.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('businesscontrol', function ($app) {
            $config = $app->make('config')->get('business-control');
            return new BC($config['api_key'], $config['api_secret'], $config['access_token']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['businesscontrol'];
    }
}