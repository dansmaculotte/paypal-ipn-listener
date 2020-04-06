<?php

namespace spec\DansMaCulotte\PayPal\Ipn\Event;

use DansMaCulotte\PayPal\Ipn\Event\MessageVerificationEvent;
use DansMaCulotte\PayPal\Ipn\Message;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\EventDispatcher\Event;

class MessageVerifiedEventSpec extends ObjectBehavior
{
    public function let(Message $message): void
    {
        $this->beConstructedWith($message);
    }

    public function it_should_be_an_event(): void
    {
        $this->shouldHaveType(Event::class);
        $this->shouldHaveType(MessageVerificationEvent::class);
    }

    public function it_should_retrieve_the_ipn_message(Message $message): void
    {
        $this->getMessage()->shouldReturn($message);
    }
}
