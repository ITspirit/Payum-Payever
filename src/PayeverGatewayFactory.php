<?php

declare(strict_types=1);

namespace ItSpirit\Payum\Payever;

use ItSpirit\Payum\Payever\Action\{Api\CreatePaymentAction,
    Api\RefundPaymentAction,
    Api\RetrievePaymentAction,
    AuthorizeAction,
    CancelAction,
    ConvertPaymentAction,
    CaptureAction,
    NotifyAction,
    RefundAction,
    StatusAction,
    SyncAction};
use ItSpirit\Payum\Payever\lib\Payments\Api as PayeverApi;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\GatewayFactory;

class PayeverGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config): void
    {
        if (false === class_exists(\Payever\ExternalIntegration\Core\Engine::class)) {
            throw new LogicException('You must install "payever/sdk-php:^1.3" library.');
        }

        $config->defaults([
            'payum.factory_name' => 'payever',
            'payum.factory_title' => 'Payever',

            'payum.action.authorize' => new AuthorizeAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.capture' => new CaptureAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.sync' => new SyncAction(),

            'payum.action.api.retrieve_payment' => new RetrievePaymentAction(),
            'payum.action.api.create_payment' => new CreatePaymentAction(),
            'payum.action.api.refund_payment' => new RefundPaymentAction(),
        ]);

        if ($config['payum.api'] === null) {
            $config['payum.default_options'] = [
                'sandbox' => true,
            ];
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [
                'clientId',
                'clientSecret',
                'slug',
                'shopId'
            ];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                $payeverApi = new PayeverApi((array) $config);

                return new Api(
                    (array) $config,
                    $payeverApi
                );
            };

            $config['payum.paths'] = array_replace([
                'PayumPayever' => __DIR__ . '/Resources/views',
            ], $config['payum.paths'] ?: []);
        }
    }
}

