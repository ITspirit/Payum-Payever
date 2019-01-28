<?php

declare(strict_types=1);

namespace ItSpirit\Payum\Payever\Tests;

use Http\Message\MessageFactory\GuzzleMessageFactory;
use ItSpirit\Payum\Payever\Api;
use ItSpirit\Payum\Payever\lib\Payments\Api as PayeverApi;
use Payum\Core\HttpClientInterface;

class ApiTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @test
     *
     * @expectedException \Payum\Core\Exception\LogicException
     * @expectedExceptionMessage The boolean sandbox option must be set.
     *
     * @throws \Exception
     */
    public function throwIfRequiredOptionsNotSetInConstructor(): void
    {
        new Api(
            [],
            $this->createPayeverApiMock(),
            $this->createHttpClientMock(),
            $this->createHttpMessageFactory()
        );
    }

    /**
     * @test
     *
     * @expectedException \Payum\Core\Exception\LogicException
     * @expectedExceptionMessage The boolean sandbox option must be set.
     *
     * @throws \Exception
     */
    public function throwIfSandboxOptionsNotBooleanInConstructor(): void
    {
        new Api(
            [
                'identifier' => 'anId',
                'password' => 'aPass',
                'sandbox' => 'notABool'
            ],
            $this->createPayeverApiMock(),
            $this->createHttpClientMock(),
            $this->createHttpMessageFactory()
        );
    }

    /**
     * @return PayeverApi|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createPayeverApiMock()
    {
        return $this->createMock(PayeverApi::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|HttpClientInterface
     */
    protected function createHttpClientMock()
    {
        return $this->createMock(HttpClientInterface::class);
    }

    /**
     * @return GuzzleMessageFactory
     */
    protected function createHttpMessageFactory(): GuzzleMessageFactory
    {
        return new GuzzleMessageFactory();
    }
}
