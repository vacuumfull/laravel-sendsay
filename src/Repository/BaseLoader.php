<?php

namespace SendsayMailer\Repository;

use SendsayMailer\Exceptions\SendsayException;


class BaseLoader
{
    public $filename;

    public function __construct($filename = null)
    {
        $this->filename = $filename ? $filename : 'Actions.php';
    }

    protected function load()
    {
        if (!$this->getPath()) {
            throw new SendsayException('Неверный путь к файлу конфигураций!');
        }

        return include $this->getPath();
    }

    protected function getPath()
    {
        return realpath(__DIR__ . "/../Configuration/{$this->filename}");
    }

    public function getParams($name)
    {
        $data = $this->load();

        return isset($data[$name]) ? $data[$name] : null;
    }

}