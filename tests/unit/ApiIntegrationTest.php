<?php

declare(strict_types=1);

namespace ItSpirit\Payum\Payever\Tests\api;

use ItSpirit\Payum\Payever\Api;
use ItSpirit\Payum\Payever\lib\Payments\Api as PayeverApi;

class ApiIntegrationTest extends \Codeception\Test\Unit
{
    private const SANDBOX_CLIENT_ID = '1454_2ax8i5chkvggc8w00g8g4sk80ckswkw0c8k8scss40o40ok4sk';
    private const SANDBOX_CLIENT_SECRET = '22uvxi05qlgk0wo8ws8s44wo8ccg48kwogoogsog4kg4s8k8k';
    private const SANDBOX_SLUG = 'payever';
    private const SANDBOX_SHOP_ID = 13619;

    /** @var \UnitTester */
    protected $tester;

    /** @var Api */
    private $api;

    /**
     * @throws \Exception
     */
    protected function _before()
    {
        $config = $this->getPayeverConfig();
        $this->api = new Api(
            $config,
            $this->getPayeverApi($config)
        );
    }

    protected function _after()
    {
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function createPaymentWillSuccessfulReturnPaymentId(): void
    {
        $orderData = $this->createOrderData();

        $result = $this->api->createPayment($orderData);

        $this->assertIsObject($result);
        $this->assertNotEmpty($result->call->id);
        $this->assertSame($orderData['channel'], $result->call->channel);
        $this->assertSame('new', $result->call->status);
        $this->assertSame('create', $result->call->type);
        $this->assertArrayNotHasKey('payment_method', (array) $result->call);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function createPaymentWillSetPaymentMethodOnSpecificRequest(): void
    {
        $orderData = $this->createOrderData('santander_installment');

        $result = $this->api->createPayment($orderData);

        $this->assertIsObject($result);
        $this->assertArrayHasKey('payment_method', (array) $result->call);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function createInvoicePayment(): string
    {
        $orderData = $this->createOrderData();

        $result = $this->api->createPayment($orderData);

        $this->assertIsObject($result);

        return $result->call->id;
    }

    #/**
    # * @test
    # * @depends createInvoicePayment
    # *
    # * @param string $paymentId
    # * @throws \Exception
    # */
    #public function retrievePaymentInformation(string $paymentId): void
    #{
    #    $result = $this->api->retrievePayment($paymentId);
    #    $this->assertIsObject($result);
    #    $this->assertSame('success', $result->call->status);
    #    $this->assertSame('authorize', $result->call->type);
    #}

    #/**
    # * @test
    # *
    # * @throws \Exception
    # */
    #public function resultOfShippingGoodsPaymentIsObjectAndHasCorrectAction(): void
    #{
    #    $result = $this->api->shippingGoodsPayment('3');
    #    $this->assertIsObject($result);
    #    $this->assertSame('list_payment_options', $result->call->action);
    #    $this->assertIsArray($result->result);
    #}

    /**
     * @test
     *
     * @throws \Exception
     */
    public function resultOfListPaymentOptionsIsObjectAndHasCorrectAction(): void
    {
        $result = $this->api->listPaymentOptions();
        $this->assertIsObject($result);
        $this->assertSame('list_payment_options', $result->call->action);
        $this->assertIsArray($result->result);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function resultOfListChannelSetsIsObjectAndHasCorrectAction(): string
    {
        $result = $this->api->listChannelSets();
        $this->assertIsObject($result);
        $this->assertSame('list_channel_sets', $result->call->action);
        $this->assertIsArray($result->result);
        return $result->call->id;
    }

    /**
     * @test
     * @depends resultOfListChannelSetsIsObjectAndHasCorrectAction
     *
     * @param string $paymentId
     * @throws \Exception
     */
    public function resultOfRetrieveApiCallHasCorrectAction(string $paymentId): void
    {
        $result = $this->api->retrieveAPiCall($paymentId);
        $this->assertIsObject($result);
        $this->assertSame('list_channel_sets', $result->action);
        $this->assertSame($paymentId, $result->id);
    }

    private function createOrderData(
        $paymentMethod = '',
        $channel = 'other_shopsystem'
    ): array
    {
        return [
           'channel' => $channel,
           'payment_method' => empty($paymentMethod) ? null : $paymentMethod,
           'amount' => '100',
           'fee' => '10',
           'order_id' => '900001291100',
           'currency' => 'EUR',
           'cart' => json_encode($this->createCartData()),
           'salutation' => 'mr',
           'first_name' => 'John',
           'last_name' => 'Doe',
           'city' => 'München',
           'zip' => '80333',
           'street' => 'Maximilianstraße 1',
           'country' => 'DE',
           'email' => 'john@payever.de',
           'phone' => '+49 (89) 123756',
           'success_url' => 'https://www.you.shop.tld/callback/success/--PAYMENT-ID--\call_id/--CALL-ID--',
           'failure_url' => 'https://www.you.shop.tld/callback/failure/--PAYMENT-ID--\call_id/--CALL-ID--',
           'cancel_url' => 'https://www.you.shop.tld/callback/notice/--PAYMENT-ID--\call_id/--CALL-ID--',
           'notice_url' => 'https://www.you.shop.tld/callback/success/--PAYMENT-ID--\call_id/--CALL-ID--',
           'pending_url' => 'https://www.you.shop.tld/callback/pending/--PAYMENT-ID--\call_id/--CALL-ID--',
           'x_frame_host' => 'https://your.shop.tld,',
        ];
    }

    private function createCartData(): array
    {
        return [
            [
               'name' => 'Some article',
               'price' => '15',
               'priceNetto' => '15',
               'vatRate' => '10',
               'quantity' => '3',
               'description' => 'The new article',
               'thumbnail' => 'https://someitem.com/thumbnail.jpg',
               'sku' => '123',
            ],
            [
               'name' => 'Some item',
               'price' => '15',
               'priceNetto' => '15',
               'vatRate' => '10',
               'quantity' => '3',
               'description' => 'The new item in black',
               'thumbnail' => 'https://someitem.com/thumbnail',
               'sku' => '124',
            ]
        ];
    }
    /**
     * @param array $config
     * @return PayeverApi
     * @throws \Exception
     */
    private function getPayeverApi(array $config): PayeverApi
    {
        return new PayeverApi($config);
    }

    private function getPayeverConfig(): array
    {
        return [
            'sandbox' => true,
            'clientId' => self::SANDBOX_CLIENT_ID,
            'clientSecret' => self::SANDBOX_CLIENT_SECRET,
            'slug' => self::SANDBOX_SLUG,
            'shopId' => self::SANDBOX_SHOP_ID
        ];
    }
}
