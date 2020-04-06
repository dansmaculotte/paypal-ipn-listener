<?php

use DansMaCulotte\PayPal\Ipn\Listener;
use ListenerBuilder\Guzzle\ArrayListenerBuilder;

class GuzzleArrayListenerBuilderContext extends FeatureContext
{
    /**
     * {@inheritdoc}
     */
    protected function getListener(): Listener
    {
        $listenerBuilder = new ArrayListenerBuilder();

        $listenerBuilder->setServiceEndpoint(
            $this->getServiceEndpoint()
        );
        $listenerBuilder->setData($this->ipnMessageData);

        return $listenerBuilder->build();
    }
}
