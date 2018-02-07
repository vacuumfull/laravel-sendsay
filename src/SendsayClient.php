<?php

namespace SendsayMailer;

use SendsayMailer\Interfaces\SendsayClientInterface;
use SendsayMailer\SendsayConnector;
use SendsayMailer\Repository\ActionLoader;
use SendsayMailer\Repository\InitLoader;

class SendsayClient implements SendsayClientInterface
{

    /**
     * @var string | null
     */
    private $login;

    /**
     * @var string | null
     */
    private $password;

    /**
     * Передаваемые в запрос параметры
     *
     * @var array | null
     */
    private $params;

    /**
     *  Уникальный идентификатор сессии
     *
     * @var string|null
     */
    private $session = null;

    /**
     * @var \SendsayMailer\SendsayConnector
     */
    private $connector;

    /**
     * Репозиторий используемых методов
     *
     * @var ActionLoader
     */
    private $actionLoader;


    /**
     * Название анкеты для кода подписчика
     *
     * @var string
     */
    public $anketaAttrs;

    /**
     * Название анкеты
     *
     * @var string
     */
    private $anketaName = 'anketa_name';

    /**
     * Идентификатор анкеты
     *
     * @var string
     */
    private $anketaId = 'anketa_id';
    /**
     * Значение анкеты для рубрики подписчика
     *
     * @var string
     */
    public $anketaVariableFirstId;

    /**
     * Значение анкеты для кода подписчика
     *
     * @var string
     */
    public $anketaVariableSecondId;


    public $reply_name;

    public $to_name;

    /**
     * SendsayClient constructor.
     *
     * @param $connector
     */
    public function __construct($connector)
    {
        $this->connector =  $connector ? $connector : new SendsayConnector();
        $this->actionLoader = new ActionLoader();
        $this->login = config("sendsay.login");
        $this->password = config("sendsay.password");
        $this->anketaAttrs = [
            'id' => $this->anketaId,
            'name' => $this->anketaName
        ];
        $this->anketaVariableFirstId = config('sendsay.first_var');
        $this->anketaVariableSecondId = config('sendsay.second_var');
        $this->reply_name = config('sendsay.reply_name');
        $this->to_name = config('sendsay.to_name');
    }

    /**
     * Логирование пользователя в Sendsay
     *
     * @return string | bool
     */
    public function login()
    {
        $this->params = $this->actionLoader->getParams('login');
        $this->params['login'] = $this->login;
        $this->params['sublogin'] = $this->login;
        $this->params['passwd'] = $this->password;

        $response = $this->request(null, $this->params);

        return isset($response['session']) ? $this->session = $response['session'] : false;
    }

    /**
     * Добавляем анкету
     *
     * @param name
     * @return string | bool
     */
    public function addAnketa($attributes)
    {
        $this->params = $this->actionLoader->getParams('anketa_create');
        $this->params['name'] = $attributes['name'];
        $this->params['id'] = $attributes['id'];
        $this->params['session'] = $this->session;

        $response = $this->request(null, $this->params);

        return isset($response['id']) ? $response['id'] : false;
    }

    /**
     * Добавляем вопрос к анкете
     *
     * @return string | bool
     */
    public function addQuestion($anketaId, $name)
    {
        $this->params = $this->actionLoader->getParams('question_add');
        $this->params['session'] = $this->session;
        $this->params['anketa.id'] = $anketaId;
        $this->params['obj']['name'] = $name;

        $response = $this->request(null, $this->params);

        return isset($response['id']) ? $response['id'] : false;
    }


    /**
     * Импортируем подписчиков
     *
     * @param $subscribers
     * @return bool
     */
    public function importSubscribers($subscribers)
    {
        $this->params = $this->actionLoader->getParams('import_members');
        $this->params['session'] = $this->session;
        $this->params['users.list'] = $this->preparedSubscribers($subscribers);

        $response = $this->request(null, $this->params);
        var_dump($response);
        return isset($response['track.id']) ? $response['track.id'] : false;
    }

    /**
     * Проверяем статус запроса
     *
     * @param $requestId
     * @return mixed
     */
    public function checkRequest($requestId)
    {
        $this->params = $this->actionLoader->getParams('check_request');
        $this->params['session'] = $this->session;
        $this->params['id'] = $requestId;

        return $this->request(null, $this->params);
    }

    /**
     * Отправка писем подписчикам
     *
     * @param $mail
     * @param $subscribers
     * @return bool | string
     */
    public function sendMailToSubs($mail, $subscribers)
    {
        $this->params = $this->actionLoader->getParams('send_mail');
        $this->params['session'] = $this->session;
        $this->params['letter']['reply.name'] =  $this->reply_name;
        $this->params['letter']['to.name'] = $this->to_name;
        $this->params['letter']['message']['html'] = $mail;
        $this->params['letter']['message']['text'] = strip_tags($mail);
        $this->params['users.list'] = $this->preparedSubscribers($subscribers);

        $response = $this->request(null, $this->params);
;
        return isset($response['track.id']) ? $response['track.id'] : false;
    }

    /**
     * Осуществляем запрос
     *
     * @param $redirectUrl
     * @param $data
     * @return mixed
     */
    private function request($redirectUrl, $data)
    {
        $this->connector->connect($redirectUrl, $data);

        return $this->checkResponse();
    }

    /**
     * Проверяем есть ли редирект
     *
     * @return mixed
     */
    private function checkResponse()
    {
        $data = json_decode($this->connector->getContent(), true);

        return isset($data['REDIRECT']) ? $this->request($data['REDIRECT'], $this->params) : $data;
    }

    /**
     * Подготовка подписчиков перед отправкой на сервер Sendsay
     *
     * @param $subscribers
     * @return array
     */
    private function preparedSubscribers($subscribers)
    {
        $initLoader = new InitLoader();

        return [
            "caption" => [
                [
                    "anketa" => "member",
                    "quest" => "email"
                ],
                [
                    "anketa" => $initLoader->getParams('anketa_id'),
                    "quest" =>  $initLoader->getParams('first_var_id'),
                ],
                [
                    "anketa" => $initLoader->getParams('anketa_id'),
                    "quest" =>  $initLoader->getParams('second_var_id'),
                ],
            ],
            "rows" => $subscribers
        ];
    }



}