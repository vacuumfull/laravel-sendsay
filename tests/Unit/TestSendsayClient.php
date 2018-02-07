<?php

use SendsayMailer\SendsayClient;
use SendsayMailer\SendsayConnector;

class ClientTest extends \Tests\TestCase
{
    /**
     * @group Unit
     * @group Libraries
     * @group Client
     */
    public function testClientAttributes()
    {
        $this->assertClassHasAttribute('login', SendsayClient::class);
        $this->assertClassHasAttribute('password', SendsayClient::class);
        $this->assertClassHasAttribute('session', SendsayClient::class);
        $this->assertClassHasAttribute('connector', SendsayClient::class);
    }

    /**
     * @group Unit
     * @group Libraries
     * @group Client
     */
    public function testClientLoginMethodSuccess()
    {
        $mock = $this->mockClient();
        $mock->method('connect')->willReturnSelf();
        $mock->method('getContent')->willReturn($this->successFixture('login'));

        $session = (new SendsayClient($mock))->login();

        $this->assertInternalType('string', $session);
    }

    /**
     * @group Unit
     * @group Libraries
     * @group Client
     */
    public function testClientLoginMethodFail()
    {
        $mock = $this->mockClient();
        $mock->method('connect')->willReturnSelf();
        $mock->method('getContent')->willReturn($this->errorFixture());

        $session = (new SendsayClient($mock))->login();

        $this->assertFalse($session);
    }

    /**
     * @group Unit
     * @group Libraries
     * @group Client
     */
    public function testClientAddAnketaMethodSuccess()
    {
        $attributes = [
            'name'=>'anketa_name',
            'id' => 'anketa_id'
        ];
        $mock = $this->mockClient();
        $mock->method('connect')->willReturnSelf();
        $mock->method('getContent')->willReturn($this->successFixture('anketa'));

        $anketaId = (new SendsayClient($mock))->addAnketa($attributes);

        $this->assertInternalType('string', $anketaId);
    }


    /**
     * @group Unit
     * @group Libraries
     * @group Client
     */
    public function testClientAddAnketaMethodError()
    {
        $attributes = [
            'name'=>'anketa_name',
            'id' => 'anketa_id'
        ];
        $mock = $this->mockClient();
        $mock->method('connect')->willReturnSelf();
        $mock->method('getContent')->willReturn($this->errorFixture());

        $anketaId = (new SendsayClient($mock))->addAnketa($attributes);

        $this->assertFalse($anketaId);
    }

    /**
     * Мокируемый объект
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function mockClient()
    {
        $mock = $this->getMockBuilder(SendsayConnector::class)
            ->disableOriginalConstructor()
            ->setMethods(['connect', 'getContent'])
            ->getMock();
        return $mock;
    }


    private function errorFixture()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/common_error.json');
        return $fixture;
    }

    private function successFixture($type)
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/'.$type.'_success.json');
        return $fixture;
    }

}
