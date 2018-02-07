<?php

namespace Tests;

use SendsayMailer\Providers\SendsayMailerServiceProvider;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public $app;

    /**
     * Boots the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->register(SendsayMailerServiceProvider::class);

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        $sendsayConfigPath = __DIR__ . '/../src/config/sendsay.php';

        $app['config']->set('sendsay', include $sendsayConfigPath);

        return $app;
    }

    public function setUp()
    {
        parent::setUp();

        $this->app = $this->createApplication();
    }

}