<?php

namespace spec\DansMaCulotte\PayPal\Ipn;

use DansMaCulotte\PayPal\Ipn\Message;
use DansMaCulotte\PayPal\Ipn\Service;
use DansMaCulotte\PayPal\Ipn\ServiceResponse;
use DansMaCulotte\PayPal\Ipn\Verifier;
use PhpSpec\ObjectBehavior;

class VerifierSpec extends ObjectBehavior
{
    public function let(
        Service $service,
        ServiceResponse $serviceResponse,
        Message $message
    ): void {
        $service->verifyIpnMessage($message)->willReturn($serviceResponse);

        $this->beConstructedWith($service);
    }

    public function it_should_return_true_when_an_ipn_message_is_verified(
        Message $message,
        ServiceResponse $serviceResponse
    ): void {
        $serviceResponse->getBody()->willReturn(Verifier::STATUS_KEYWORD_VERIFIED);

        $this->verify($message)->shouldReturn(true);
    }

    public function it_should_return_false_when_an_ipn_message_is_invalid(
        Message $message,
        ServiceResponse $serviceResponse
    ): void {
        $serviceResponse->getBody()->willReturn(Verifier::STATUS_KEYWORD_INVALID);

        $this->verify($message)->shouldReturn(false);
    }

    public function it_should_throw_an_exception_when_an_unexpected_status_keyword_is_encountered(
        Message $message,
        ServiceResponse $serviceResponse
    ): void {
        $serviceResponse->getBody()->willReturn('foo');

        $this->shouldThrow('UnexpectedValueException')->duringVerify($message);
    }
}
