<?php

namespace SendsayMailer\Interfaces;


interface SendsayConnectorInterface
{
    public function connect($url = null, $data = []);

    public function getContent();

}