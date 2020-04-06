<?php

namespace DansMaCulotte\PayPal\Ipn\ListenerBuilder\Guzzle;

use DansMaCulotte\PayPal\Ipn\InputStream;
use DansMaCulotte\PayPal\Ipn\ListenerBuilder\GuzzleListenerBuilder;
use DansMaCulotte\PayPal\Ipn\MessageFactory;
use DansMaCulotte\PayPal\Ipn\MessageFactory\InputStreamMessageFactory;

class InputStreamListenerBuilder extends GuzzleListenerBuilder
{
    protected function getMessageFactory(): MessageFactory
    {
        return new InputStreamMessageFactory(new InputStream());
    }
}
