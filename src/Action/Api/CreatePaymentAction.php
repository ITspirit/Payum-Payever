<?php

declare(strict_types=1);

namespace ItSpirit\Payum\Payever\Action\Api;

use ItSpirit\Payum\Payever\Request\Api\CreatePayment;

class CreatePaymentAction extends BaseApiAwareAction
{
    /**
     * @inheritdoc
     *
     * @param CreatePayment
     */
    public function execute($request)
    {
        // TODO: Implement execute() method.
    }

    /**
     * @inheritdoc
     */
    public function supports($request): bool
    {
        return $request instanceof CreatePayment;
    }
}
