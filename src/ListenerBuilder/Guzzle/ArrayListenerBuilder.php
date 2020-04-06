<?php

namespace DansMaCulotte\PayPal\Ipn\ListenerBuilder\Guzzle;

use DansMaCulotte\PayPal\Ipn\ListenerBuilder\GuzzleListenerBuilder;
use DansMaCulotte\PayPal\Ipn\MessageFactory;
use DansMaCulotte\PayPal\Ipn\MessageFactory\ArrayMessageFactory;

class ArrayListenerBuilder extends GuzzleListenerBuilder
{
    /**
     * @var array
     */
    private $data = [];

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    protected function getMessageFactory(): MessageFactory
    {
        return new ArrayMessageFactory($this->data);
    }
}
