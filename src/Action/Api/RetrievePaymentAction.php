<?php

declare(strict_types=1);

namespace ItSpirit\Payum\Payever\Action\Api;

use ItSpirit\Payum\Payever\Request\Api\RetrievePayment;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;

class RetrievePaymentAction extends BaseApiAwareAction
{
    /**
     * @inheritdoc
     *
     * @param RetrievePayment $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        if ($details['transaction_id']) {
            throw new LogicException(sprintf('The transaction has already been created for this payment. transaction_id: %s', $details['transaction_id']));
        }
        $details->validateNotEmpty(['amount', 'currency_code', 'reason', 'success_url', 'notification_url']);
        $details->replace($this->api->createTransaction((array) $details));
        if ($details['payment_url']) {
            throw new HttpRedirect($details['payment_url']);
        }

        $this->api->retrievePayment($details['transaction_id']);
    }

    /**
     * @inheritdoc
     */
    public function supports($request): bool
    {
        return $request instanceof RetrievePayment;
    }
}
