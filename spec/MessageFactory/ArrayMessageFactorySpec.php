<?php

namespace spec\DansMaCulotte\PayPal\Ipn\MessageFactory;

use DansMaCulotte\PayPal\Ipn\Message;
use DansMaCulotte\PayPal\Ipn\MessageFactory;
use PhpSpec\ObjectBehavior;

class ArrayMessageFactorySpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(['foo' => 'bar']);
    }

    public function it_should_be_a_message_factory(): void
    {
        $this->shouldHaveType(MessageFactory::class);
    }

    public function it_should_create_a_message_from_an_array(): void
    {
        $this->createMessage()->shouldHaveType(Message::class);
    }
}
