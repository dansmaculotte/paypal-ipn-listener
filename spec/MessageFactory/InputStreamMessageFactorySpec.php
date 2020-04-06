<?php

namespace spec\DansMaCulotte\PayPal\Ipn\MessageFactory;

use DansMaCulotte\PayPal\Ipn\InputStream;
use DansMaCulotte\PayPal\Ipn\Message;
use DansMaCulotte\PayPal\Ipn\MessageFactory;
use PhpSpec\ObjectBehavior;

class InputStreamMessageFactorySpec extends ObjectBehavior
{
    public function let(InputStream $inputStream): void
    {
        $this->beConstructedWith($inputStream);
    }

    public function it_should_be_a_message_factory(): void
    {
        $this->shouldHaveType(MessageFactory::class);
    }

    public function it_should_create_a_message_from_the_input_stream(InputStream $inputStream): void
    {
        $streamContents = 'foo=bar&baz=quz';

        $inputStream->getContents()->willReturn($streamContents);

        $this->createMessage()->shouldHaveType(Message::class);
    }

    public function it_should_url_decode_values_from_the_input_stream(InputStream $inputStream): void
    {
        $streamContents = 'foo=bar&baz=quz%2Bfoo%2B%28bar%29';

        $inputStream->getContents()->willReturn($streamContents);

        $message = $this->createMessage();

        $message->__toString()->shouldReturn($streamContents);
    }
}
