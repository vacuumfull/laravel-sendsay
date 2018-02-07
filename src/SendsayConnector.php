<?php

namespace SendsayMailer;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use SendsayMailer\Exceptions\SendsayException;
use SendsayMailer\Interfaces\SendsayConnectorInterface;

class SendsayConnector implements SendsayConnectorInterface
{
    private $http;

    private $baseUrl;

    private $method = 'POST';

    private $response;

    /**
     * Connector constructor.
     *
     * @param ClientInterface|null $http
     */
    public function __construct(ClientInterface $http = null)
    {
        $this->http = $http ? $http : new Client();

        $this->baseUrl = 'https://api.sendsay.ru';
    }

    /**
     * Подсоединяемся
     *
     * @param string $url
     * @param array $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws SendsayException
     */
    public function connect($url = null, $data = [])
    {
        $requestUrl = $url ? $this->baseUrl . $url : $this->baseUrl;
        try {
            $this->response = $this->http->request($this->method,  $requestUrl, $this->prepareParams($data));
            return $this->response;
        } catch (\Exception $exception) {
            throw new SendsayException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function getContent()
    {
        return $this->response->getBody()->getContents();
    }
    /**
     * @param $data
     * @return array
     */
    private function prepareParams($data){
        return [
            'form_params' => [
                'apiversion' => 100,
                'json' => 1,
                'request' => json_encode($data)
            ]
        ];
    }
}