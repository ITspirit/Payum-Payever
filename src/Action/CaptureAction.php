<?php

declare(strict_types=1);

namespace ItSpirit\Payum\Payever\Action;

use ItSpirit\Payum\Payever\Request\Api\CreatePayment;
use ItSpirit\Payum\Payever\Request\Api\RetrievePayment;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Security\GenericTokenFactoryAwareTrait;

class CaptureAction implements ActionInterface
{
    use GatewayAwareTrait;
    use GenericTokenFactoryAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Capture $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        if (false === $details['transaction_id']) {
            if (false === $details['success_url'] && $request->getToken()) {
                $details['success_url'] = $request->getToken()->getTargetUrl();
            }
            if (false === $details['abort_url'] && $request->getToken()) {
                $details['abort_url'] = $request->getToken()->getTargetUrl();
            }
            if ($this->tokenFactory && false === $details['notification_url'] && $request->getToken()) {
                $notifyToken = $this->tokenFactory->createNotifyToken(
                    $request->getToken()->getGatewayName(),
                    $request->getToken()->getDetails()
                );
                $details['notification_url'] = $notifyToken->getTargetUrl();
            }

            $this->gateway->execute(new CreatePayment($details));
        }

        $this->gateway->execute(new RetrievePayment($details));
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request): bool
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
