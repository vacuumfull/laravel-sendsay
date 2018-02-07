<?php

namespace SendsayMailer\Interfaces;

interface SendsayFacadeInterface
{
    public function importSubscribers($subscribers);

    public function addVariables();

    public function requestChecker($trackId);

    public function mailSend($mail, $subscribers);

    public function auth();

    public function begin();

}