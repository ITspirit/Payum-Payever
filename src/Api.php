<?php

declare(strict_types=1);

namespace ItSpirit\Payum\Payever;

use Http\Message\MessageFactory;
use ItSpirit\Payum\Payever\lib\Payments\Api as PayeverApi;
use Payever\ExternalIntegration\Core\Http\Response;
use Payum\Core\Exception\LogicException;
use Payum\Core\HttpClientInterface;

class Api
{
    /** @var HttpClientInterface */
    protected $client;

    /** @var MessageFactory */
    protected $messageFactory;

    /** @var array */
    protected $options = [];

    /** @var PayeverApi */
    protected $payeverApi;

    /**
     * @param array $options
     * @param PayeverApi $payeverApi
     * @param HttpClientInterface $client
     * @param MessageFactory $messageFactory
     * @throws \Exception
     */
    public function __construct(array $options, PayeverApi $payeverApi, HttpClientInterface $client, MessageFactory $messageFactory)
    {
        if (empty($options['sandbox']) || is_bool($options['sandbox']) === false) {
            throw new LogicException('The boolean sandbox option must be set.');
        }

        $this->options = $options;
        $this->payeverApi = $payeverApi;
        $this->client = $client;
        $this->messageFactory = $messageFactory;
    }

    /**
     * @param array $orderData
     * @return Response
     * @throws \Exception
     */
    public function createPayment(array $orderData): Response
    {
        return $this->payeverApi->createPaymentRequest($orderData);
    }

    /**
     * @param string $paymentId
     * @return Response
     * @throws \Exception
     */
    public function retrievePayment(string $paymentId): Response
    {
        return $this->payeverApi->retrievePaymentRequest($paymentId);
    }

    /**
     * @param string|null $paymentMethod
     * @param \DateTime|null $date
     * @param string|null $currency
     * @param string|null $state
     * @param int $limit
     * @return Response
     * @throws \Exception
     */
    public function listPayments(
        string $paymentMethod = null,
        \DateTime$date = null,
        string $currency = null,
        string $state = null,
        int $limit = 10
    ): Response
    {
        return $this->payeverApi->listPaymentsRequest($paymentMethod, $date, $currency, $state, $limit);
    }

    /**
     * @param string $paymentId
     * @return Response
     * @throws \Exception
     */
    public function cancelPayment(string $paymentId): Response
    {
        return $this->payeverApi->cancelPaymentRequest($paymentId);
    }

    /**
     * @param string $paymentId
     * @param float|null $amount
     * @return Response
     * @throws \Exception
     */
    public function refundPayment(string $paymentId, float $amount = null): Response
    {
        return $this->payeverApi->refundPaymentRequest($paymentId, $amount);
    }

    /**
     * @param string $paymentId
     * @param array $data
     * @return Response
     * @throws \Exception
     */
    public function authorizePayment(string $paymentId, array $data = []): Response
    {
        return $this->payeverApi->authorizePaymentRequest($paymentId, $data);
    }

    /**
     * @param string $paymentId
     * @param array $data
     * @return Response
     * @throws \Exception
     */
    public function shippingGoodsPayment(string $paymentId, array $data = []): Response
    {
        return $this->payeverApi->shippingGoodsPaymentRequest($paymentId, $data);
    }

    /**
     * @param string $channelSetId
     * @param string $params
     * @return Response
     * @throws \Exception
     */
    public function listPaymentOptions(string $channelSetId = '', string $params = ''): Response
    {
        return $this->payeverApi->listPaymentOptionsRequest($this->options['slug'], $channelSetId, $params);
    }

    /**
     * @param string $callID
     * @return Response
     * @throws \Exception
     */
    public function retrieveAPiCall(string $callID): Response
    {
        return $this->payeverApi->retrieveApiCallRequest($callID);
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function listChannelSets(): Response
    {
        return $this->payeverApi->listChannelSetsRequest($this->options['slug']);
    }
}
