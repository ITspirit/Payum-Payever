<?php

declare(strict_types=1);

namespace ItSpirit\Payum\Payever\Tests\unit;

use ItSpirit\Payum\Payever\Api;
use ItSpirit\Payum\Payever\lib\Payments\Api as PayeverApi;

class ApiTest extends \Codeception\Test\Unit
{
    /** @var \UnitTester */
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
            $this->createPayeverApiMock()
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
            $this->createPayeverApiMock()
        );
    }

    /**
     * @return PayeverApi|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createPayeverApiMock()
    {
        return $this->createMock(PayeverApi::class);
    }
}
