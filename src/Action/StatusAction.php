<?php

declare(strict_types=1);

namespace ItSpirit\Payum\Payever\Action;

use Payever\ExternalIntegration\Payments\Status;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;

class StatusAction implements ActionInterface
{
    /**
     * {@inheritDoc}
     *
     * @param GetStatusInterface $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        if (!isset($details['transaction_id']) || empty($details['transaction_id'])) {
            $request->markNew();

            return;
        }

        if (!isset($details['status'])) {
            $request->markNew();

            return;
        }

        switch ($details['status']) {
            case Status::STATUS_NEW:
                $request->markNew();
                break;
            case Status::STATUS_IN_PROCESS:
                $request->markPending();
                break;
            case Status::STATUS_ACCEPTED:
                $request->markCaptured();
                break;
            case Status::STATUS_FAILED:
                $request->markFailed();
                break;
            case Status::STATUS_DECLINED:
                $request->markFailed();
                break;
            case Status::STATUS_REFUNDED:
                $request->markRefunded();
                break;
            case Status::STATUS_PAID:
                $request->markPayedout();
                break;
            case Status::STATUS_CANCELLED:
                $request->markCanceled();
                break;
            default:
                $request->markUnknown();
                break;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request): bool
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
