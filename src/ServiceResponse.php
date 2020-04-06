<?php

namespace DansMaCulotte\PayPal\Ipn;

class ServiceResponse
{
    /**
     * @var string
     */
    private $body;

    public function __construct(string $body)
    {
        $this->body = $body;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
