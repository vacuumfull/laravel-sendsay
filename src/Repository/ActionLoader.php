<?php

namespace SendsayMailer\Repository;

use SendsayMailer\Repository\BaseLoader;

class ActionLoader extends BaseLoader
{
    public $filename = 'Actions.php';

    public function __construct()
    {
        parent::__construct($this->filename);
    }
}