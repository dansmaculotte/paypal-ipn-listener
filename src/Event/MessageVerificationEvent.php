<?php

namespace DansMaCulotte\PayPal\Ipn\Event;

use DansMaCulotte\PayPal\Ipn\Message;
use Symfony\Contracts\EventDispatcher\Event;

abstract class MessageVerificationEvent extends Event
{
    /**
     * @var Message
     */
    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}
