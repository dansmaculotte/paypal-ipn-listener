<?php

require '../../vendor/autoload.php';

use DansMaCulotte\PayPal\Ipn\Event\MessageInvalidEvent;
use DansMaCulotte\PayPal\Ipn\Event\MessageVerificationFailureEvent;
use DansMaCulotte\PayPal\Ipn\Event\MessageVerifiedEvent;
use DansMaCulotte\PayPal\Ipn\ListenerBuilder\Guzzle\ArrayListenerBuilder as ListenerBuilder;

$listenerBuilder = new ListenerBuilder();

// make sure this is actually the data you recieved from PayPal...
$data = [
    'foo' => 'bar',
    'bar' => 'baz',
];

$listenerBuilder->setData($data);
$listenerBuilder->useSandbox();

$listener = $listenerBuilder->build();

$listener->onInvalid(function (MessageInvalidEvent $event) {
    $ipnMessage = $event->getMessage();

    file_put_contents('outcome.txt', "INVALID\n\n$ipnMessage");
});

$listener->onVerified(function (MessageVerifiedEvent $event) {
    $ipnMessage = $event->getMessage();

    file_put_contents('outcome.txt', "VERIFIED\n\n$ipnMessage");
});

$listener->onVerificationFailure(function (MessageVerificationFailureEvent $event) {
    $error = $event->getError();

    file_put_contents('outcome.txt', "VERIFICATION FAILURE\n\n$error");
});

$listener->listen();
