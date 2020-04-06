<?php

namespace DansMaCulotte\PayPal\Ipn\MessageFactory;

use DansMaCulotte\PayPal\Ipn\InputStream;
use DansMaCulotte\PayPal\Ipn\Message;
use DansMaCulotte\PayPal\Ipn\MessageFactory;

class InputStreamMessageFactory implements MessageFactory
{
    /**
     * @var InputStream
     */
    private $inputStream;

    public function __construct(InputStream $inputStream)
    {
        $this->inputStream = $inputStream;
    }

    public function createMessage(): Message
    {
        $streamContents = $this->inputStream->getContents();

        return new Message($streamContents);
    }
}
