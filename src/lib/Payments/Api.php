<?php

declare(strict_types=1);

namespace ItSpirit\Payum\Payever\lib\Payments;

use ItSpirit\Payum\Payever\lib\Core\Authorization\TokenList;

class Api extends \Payever\ExternalIntegration\Payments\Api
{
    /** @var array */
    protected $options;

    /**
     * @inheritdoc
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;

        parent::__construct();
    }

    /**
     * @inheritdoc
     *
     * @return $this|\Payever\ExternalIntegration\Payments\Api
     * @throws \Exception
     */
    protected function loadConfiguration()
    {
        $this->configuration = new Configuration($this->options);
        $this->configuration->load();

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return $this|\Payever\ExternalIntegration\Payments\Api
     */
    protected function loadTokens()
    {
        $this->tokens = new TokenList();
        $this->getTokens()->load();

        return $this;
    }
}
