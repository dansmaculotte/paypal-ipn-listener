<?php

namespace DansMaCulotte\PayPal\Ipn;

interface Service
{
    public function verifyIpnMessage(Message $message): ServiceResponse;
}
