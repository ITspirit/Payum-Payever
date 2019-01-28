<?php

declare(strict_types=1);

namespace ItSpirit\Payum\Payever\Tests\unit;

use ItSpirit\Payum\Payever\PayeverGatewayFactory;
use Payum\Core\Gateway;

class PayeverGatewayFactoryTest extends \Codeception\Test\Unit
{
    private const FACTORY_NAME = 'payever';
    private const FACTORY_TITLE = 'Payever';

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
     * @throws \ReflectionException
     */
    public function shouldSubClassGatewayFactory(): void
    {
        $rc = new \ReflectionClass(PayeverGatewayFactory::class);
        $this->assertTrue($rc->isSubclassOf(\Payum\Core\GatewayFactory::class));
    }

    /**
     * @test
     */
    public function couldBeConstructedWithoutAnyArguments(): void
    {
        new PayeverGatewayFactory();
    }

    /**
     * @test
     */
    public function shouldAllowCreateGateway(): void
    {
        $factory = new PayeverGatewayFactory();
        $gateway = $factory->create([
            'clientId' => 'foo:bar:baz',
            'clientSecret' => 'foo:bar:baz',
            'slug' => 'foo:bar:baz',
            'shopId' => 'foo:bar:baz'
        ]);
        $this->assertInstanceOf(Gateway::class, $gateway);
    }

    /**
     * @test
     */
    public function shouldAllowCreateGatewayWithCustomApi(): void
    {
        $factory = new PayeverGatewayFactory();
        $gateway = $factory->create(['payum.api' => new \stdClass()]);
        $this->assertInstanceOf(Gateway::class, $gateway);
    }

    /**
     * @test
     */
    public function shouldAllowCreateGatewayConfig(): void
    {
        $factory = new PayeverGatewayFactory();
        $config = $factory->createConfig();
        $this->assertIsArray($config);
        $this->assertNotEmpty($config);
    }

    /**
     * @test
     */
    public function shouldAddDefaultConfigPassedInConstructorWhileCreatingGatewayConfig(): void
    {
        $factory = new PayeverGatewayFactory([
            'foo' => 'fooVal',
            'bar' => 'barVal',
        ]);
        $config = $factory->createConfig();
        $this->assertIsArray($config);
        $this->assertArrayHasKey('foo', $config);
        $this->assertEquals('fooVal', $config['foo']);
        $this->assertArrayHasKey('bar', $config);
        $this->assertEquals('barVal', $config['bar']);
    }

    /**
     * @test
     */
    public function shouldConfigContainDefaultOptions(): void
    {
        $factory = new PayeverGatewayFactory();
        $config = $factory->createConfig();
        $this->assertIsArray($config);
        $this->assertArrayHasKey('payum.default_options', $config);
        $this->assertEquals(
            ['sandbox' => true],
            $config['payum.default_options']
        );
    }

    /**
     * @test
     */
    public function shouldConfigContainFactoryNameAndTitle(): void
    {
        $factory = new PayeverGatewayFactory();
        $config = $factory->createConfig();
        $this->assertIsArray($config);
        $this->assertArrayHasKey('payum.factory_name', $config);
        $this->assertEquals(self::FACTORY_NAME, $config['payum.factory_name']);
        $this->assertArrayHasKey('payum.factory_title', $config);
        $this->assertEquals(self::FACTORY_TITLE, $config['payum.factory_title']);
    }

    /**
     * @test
     *
     * @expectedException \Payum\Core\Exception\LogicException
     * @expectedExceptionMessage The clientId, clientSecret, slug, shopId fields are required.
     */
    public function shouldThrowIfRequiredOptionsNotPassed(): void
    {
        $factory = new PayeverGatewayFactory();
        $factory->create();
    }
}
