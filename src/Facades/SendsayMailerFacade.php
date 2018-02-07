<?php

namespace SendsayMailer\Facades;

use Illuminate\Support\Facades\Facade;

class SendsayMailerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'SendsayMailer';
    }
}
