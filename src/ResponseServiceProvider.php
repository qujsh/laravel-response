<?php

namespace Qujsh\Response;

use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider{

    /**
     *
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/message.php' => config_path('response/message.php'),
        ], 'config');

    }

    public function register()
    {
        $this->mergeConfigFrom(
                __DIR__.'/../config/message.php',
                'response.message'
        );
    }
}

