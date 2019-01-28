<?php

declare(strict_types=1);

namespace ItSpirit\Payum\Payever\lib\Payments;

use Payever\ExternalIntegration\Core\ChannelSet;

class Configuration extends \Payever\ExternalIntegration\Payments\Configuration
{
    /** @var array */
    protected $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @inheritdoc
     *
     * @throws \Exception
     */
    public function load(): void
    {
        $this->setApiMode($this->options['sandbox'] ? self::MODE_SANDBOX : self::MODE_LIVE);

        $this->setClientId($this->options['clientId']);
        $this->setClientSecret($this->options['clientSecret']);
        $this->setSlug($this->options['slug']);
        $this->setChannelSet($this->options['channel'] ?? ChannelSet::CHANNEL_OTHER_SHOPSYSTEM);

        if (array_key_exists('debug',$this->options) && is_bool($this->options['debug'])) {
            $this->setDebugMode($this->options['debug']);
        }

        if (!empty($this->options['sandboxUrl']) && $this->isUrl($this->options['sandboxUrl'])) {
            $this->setSandboxUrl($this->options['sandboxUrl']);
        }
    }

    protected function isUrl($text): bool
    {
        return filter_var($text, FILTER_VALIDATE_URL) !== false;
    }
}
