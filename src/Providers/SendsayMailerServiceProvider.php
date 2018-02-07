<?php

namespace SendsayMailer\Providers;

use Illuminate\Support\ServiceProvider;
use SendsayMailer\SendsayFacade;

class SendsayMailerServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/sendsay.php' => config_path('sendsay.php'),
        ], 'search');
    }

    public function register()
    {
        $this->app->bind('SendsayMailer',function(){

            return new SendsayFacade();
        });
    }
}


