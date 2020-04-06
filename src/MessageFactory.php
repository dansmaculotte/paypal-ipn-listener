<?php

namespace DansMaCulotte\PayPal\Ipn;

interface MessageFactory
{
    public function createMessage(): Message;
}
