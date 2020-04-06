<?php

namespace spec\DansMaCulotte\PayPal\Ipn;

use PhpSpec\ObjectBehavior;

class ServiceResponseSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('foo');
    }

    public function it_should_retrieve_the_body(): void
    {
        $this->getBody()->shouldReturn('foo');
    }
}
