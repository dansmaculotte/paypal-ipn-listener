<?php

namespace DansMaCulotte\PayPal\Ipn\ListenerBuilder;

use DansMaCulotte\PayPal\Ipn\ListenerBuilder;
use DansMaCulotte\PayPal\Ipn\Service;
use DansMaCulotte\PayPal\Ipn\Service\GuzzleService;
use GuzzleHttp\Client;

abstract class GuzzleListenerBuilder extends ListenerBuilder
{
    use ModeDependentServiceEndpoint;

    protected function getService(): Service
    {
        return new GuzzleService(
            new Client(),
            $this->getServiceEndpoint()
        );
    }
}
