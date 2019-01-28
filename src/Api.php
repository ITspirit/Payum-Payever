<?php

declare(strict_types=1);

namespace ItSpirit\Payum\Payever;

use ItSpirit\Payum\Payever\lib\Payments\Api as PayeverApi;
use Payever\ExternalIntegration\Core\Http\Response;
use Payum\Core\Exception\LogicException;

class Api
{
    /** @var array */
    protected $options = [];

    /** @var PayeverApi */
    protected $payeverApi;

    /**
     * @param array $options
     * @param PayeverApi $payeverApi
     * @throws \Exception
     */
    public function __construct(array $options, PayeverApi $payeverApi)
    {
        if (empty($options['sandbox']) || is_bool($options['sandbox']) === false) {
            throw new LogicException('The boolean sandbox option must be set.');
        }

        $this->options = $options;
        $this->payeverApi = $payeverApi;
    }

    /**
     * @param array $orderData
     * @return mixed
     * @throws \Exception
     */
    public function createPayment(array $orderData)
    {
        return json_decode($this->payeverApi->createPaymentRequest($orderData)->getData());
    }

    /**
     * @param string $paymentId
     * @return mixed
     * @throws \Exception
     */
    public function retrievePayment(string $paymentId)
    {
        return json_decode($this->payeverApi->retrievePaymentRequest($paymentId)->getData());
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
     * @return mixed
     * @throws \Exception
     */
    public function authorizePayment(string $paymentId, array $data = [])
    {
        return json_decode($this->payeverApi->authorizePaymentRequest($paymentId, $data)->getData());
    }

    /**
     * @param string $paymentId
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function shippingGoodsPayment(string $paymentId, array $data = [])
    {
        return json_decode($this->payeverApi->shippingGoodsPaymentRequest($paymentId, $data)->getData());
    }

    /**
     * @param string $channelSetId
     * @param string $params
     * @return mixed
     * @throws \Exception
     */
    public function listPaymentOptions(string $channelSetId = '', string $params = '')
    {
        return json_decode($this->payeverApi->listPaymentOptionsRequest($this->options['slug'], $channelSetId, $params)->getData());
    }

    /**
     * @param string $callID
     * @return mixed
     * @throws \Exception
     */
    public function retrieveAPiCall(string $callID)
    {
        return json_decode($this->payeverApi->retrieveApiCallRequest($callID)->getData());
    }

    /**
     * @throws \Exception
     */
    public function listChannelSets()
    {
        return json_decode($this->payeverApi->listChannelSetsRequest($this->options['slug'])->getData());
    }
}
