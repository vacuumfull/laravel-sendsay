<?php

namespace SendsayMailer\Interfaces;

interface SendsayClientInterface
{
    public function login();

    public function addAnketa($name);

    public function addQuestion($anketaId, $name);

    public function importSubscribers($subscribers);

    public function checkRequest($requestId);
}