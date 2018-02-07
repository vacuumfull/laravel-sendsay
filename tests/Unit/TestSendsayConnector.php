<?php

use SendsayMailer\SendsayConnector;
use SendsayMailer\Exceptions\SendsayException;
use GuzzleHttp\ClientInterface;

class TestSendsayConnector  extends \Tests\TestCase
{
    /**
     * @group Unit
     * @group Libraries
     * @group Sendsay
     * @group Connector
     */
    public function testUrlForConnect()
    {
        $connector = new SendsayConnector();

        $this->assertAttributeEquals('https://api.sendsay.ru', 'baseUrl', $connector);
    }

    /**
     * @group Unit
     * @group Libraries
     * @group Sendsay
     * @group Connector
     */
    public function testConnectHttpMethod()
    {
        $connector = new SendsayConnector();

        $this->assertAttributeEquals('POST', 'method', $connector);
    }

    /**
     * @group Unit
     * @group Libraries
     * @group Sendsay
     * @group Connector
     */
    public function testDefaultHttpClient()
    {
        $connector = new SendsayConnector();

        $this->assertAttributeInstanceOf(\GuzzleHttp\Client::class, 'http', $connector);
    }

    /**
     * @group Unit
     * @group Libraries
     * @group Sendsay
     * @group Connector
     */
    public function testSetupHttpClient()
    {
        $http = $this->getMockBuilder(ClientInterface::class)->getMock();

        $connector = new SendsayConnector($http);

        $this->assertAttributeInstanceOf(get_class($http), 'http', $connector);
    }

    /**
     * @group Unit
     * @group Libraries
     * @group Sendsay
     * @group Connector
     */
    public function testFailConnect()
    {
        $this->expectException(SendsayException::class);
        $http = $this->getMockBuilder(ClientInterface::class)->getMock();
        $http->method('request')->willThrowException(new Exception);
        $connector = new SendsayConnector($http);

        $connector->connect();
    }

    /**
     * @group Unit
     * @group Libraries
     * @group Sendsay
     * @group Connector
     */
    public function testSuccessConnect()
    {
        $data = str_random();
        $http = $this->getMockBuilder(ClientInterface::class)->getMock();
        $http->method('request')->willReturn($data);
        $connector = new SendsayConnector($http);

        $this->assertEquals($data, $connector->connect());
    }
}