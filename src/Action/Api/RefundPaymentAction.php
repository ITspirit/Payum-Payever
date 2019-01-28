<?php

declare(strict_types=1);

namespace ItSpirit\Payum\Payever\Action\Api;

use ItSpirit\Payum\Payever\Request\Api\RefundPayment;

class RefundPaymentAction extends BaseApiAwareAction
{
    /**
     * @inheritdoc
     *
     * @param RefundPayment $request
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
        return $request instanceof RefundPayment;
    }
}
