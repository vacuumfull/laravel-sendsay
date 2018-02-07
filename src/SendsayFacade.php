<?php

namespace SendsayMailer;

use SendsayMailer\SendsayClient;
use SendsayMailer\SendsayConnector;
use SendsayMailer\Repository\InitLoader;
use SendsayMailer\Interfaces\SendsayFacadeInterface;

class SendsayFacade implements SendsayFacadeInterface
{

    public $client;

    private $initial;

    private $variables;

    private $templateCode;

    /**
     * Статус успешно завершившегося запроса
     */
    const SUCCESS_STATUS = -1;

    /**
     * Время для повтора запроса
     */
    const RETRY_TIME = 30;


    public function __construct()
    {
        $this->client = new SendsayClient(new SendsayConnector());
        $this->initial = new InitLoader();
        $this->variables = [
            'rubric_id' => $this->client->anketaVariableFirstId,
            'code_id' => $this->client->anketaVariableSecondId
        ];
    }


    public function begin()
    {
        $this->auth();
        sleep(1);

        if (!$this->checkInitialVars()){
            $this->addVariables();
        };
    }

    public function auth()
    {
        return $this->client->login();
    }

    /**
     * Добавляем перменные и записываем их в Init
     */
    public function addVariables()
    {
        $attrs = [];
        $attrs['anketa_id'] = $this->client->addAnketa($this->client->anketaAttrs);
        sleep(2);

        foreach($this->variables as $key => $variable){
            $attrs[$key] = $this->client->addQuestion($attrs['anketa_id'], $variable);
            sleep(1);
        }

        $this->initial->writeParams($attrs);
        $this->templateCode = '[% anketa.' . $attrs['anketa_id'] . '.' . $attrs['code_id'] . ' %]';
    }

    /**
     * Импорт подписчиков
     *
     * @param $subscribers
     * @return bool
     */
    public function importSubscribers($subscribers)
    {
        $trackId = $this->client->importSubscribers($subscribers);

        return $this->requestChecker($trackId);
    }

    /**
     * Рассылка писем подписчикам
     *
     * @param $mail
     * @param $subscribers
     * @return bool
     */
    public function mailSend($mail, $subscribers)
    {
        $trackId = $this->client->sendMailToSubs($mail, $subscribers);
        var_dump($trackId);

        return $this->requestChecker($trackId);
    }

    /**
     * Проверятор выполнения запроса
     *
     * @param $trackId
     * @return bool
     */
    public function requestChecker($trackId)
    {
        $response = $this->client->checkRequest($trackId);
        var_dump($response);

        if ((int)$response['obj']['status'] == self::SUCCESS_STATUS){
            return true;
        } else {
            sleep(self::RETRY_TIME);
            return $this->requestChecker($trackId);
        }
    }

    /**
     * Проверка занчений переменных анкеты
     *
     * @return bool
     */
    private function checkInitialVars(){

        if ($this->initial->getParams('first_var_id') == null OR $this->initial->getParams('second_var_id') == null){
            return false;
        }

        $this->templateCode = '[% anketa.' . $this->initial->getParams('first_var_id') . '.' . $this->initial->getParams('second_var_id') . ' %]';

        return true;
    }
}