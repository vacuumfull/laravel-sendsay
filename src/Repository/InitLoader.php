<?php

namespace SendsayMailer\Repository;

use SendsayMailer\Repository\BaseLoader;
use SendsayMailer\Exceptions\SendsayException;

class InitLoader extends BaseLoader
{

    /**
     * Название используемого файла
     *
     * @var string
     */
    public $filename = 'Init.php';

    /**
     * InitLoader constructor.
     */
    public function __construct()
    {
        parent::__construct($this->filename);
    }

    /**
     * Записываем  значения идентификаторов рубрики и кода
     *
     * @param $attributes
     * @throws SendsayException
     */
    public function writeParams($attributes)
    {
        $filepath = parent::getPath();

        if (!isset($attributes['first_var_id']) OR !isset($attributes['second_var_id'])){
            throw new SendsayException('Отсутсвует одно из значнений идентификаторов переменных!');
        }

        file_put_contents($filepath, '<?php return ' . var_export($attributes, true) . ';');
    }
}