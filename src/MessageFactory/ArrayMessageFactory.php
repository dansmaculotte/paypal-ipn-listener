<?php

namespace DansMaCulotte\PayPal\Ipn\MessageFactory;

use DansMaCulotte\PayPal\Ipn\Message;
use DansMaCulotte\PayPal\Ipn\MessageFactory;

class ArrayMessageFactory implements MessageFactory
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function createMessage(): Message
    {
        return new Message($this->data);
    }
}
