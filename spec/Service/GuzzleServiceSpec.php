<?php

namespace spec\DansMaCulotte\PayPal\Ipn\Service;

use DansMaCulotte\PayPal\Ipn\Exception\ServiceException;
use DansMaCulotte\PayPal\Ipn\Message;
use DansMaCulotte\PayPal\Ipn\Service;
use DansMaCulotte\PayPal\Ipn\ServiceResponse;
use GuzzleHttp\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;

class GuzzleServiceSpec extends ObjectBehavior
{
    public function let(ClientStub $httpClient)
    {
        $this->beConstructedWith($httpClient, 'http://foo.bar');
    }

    public function it_should_be_a_service(): void
    {
        $this->shouldHaveType(Service::class);
    }

    public function it_should_return_a_service_response_when_verifying_an_ipn_message(ClientStub $httpClient, Message $message, ResponseInterface $response)
    {
        $response->getBody()->willReturn('foo');

        $httpClient->post(
            Argument::type('string'),
            Argument::type('array')
        )->willReturn($response);

        $message->getAll()->willReturn(['foo' => 'bar']);

        $response = $this->verifyIpnMessage($message);

        $response->shouldHaveType(ServiceResponse::class);
        $response->getBody()->shouldReturn('foo');
    }

    public function it_should_throw_a_service_exception_when_a_request_fails(ClientStub $httpClient, Message $message)
    {
        $httpClient->post(
            Argument::type('string'),
            Argument::type('array')
        )->willThrow('Exception');

        $message->getAll()->willReturn(['foo' => 'bar']);

        $this->shouldThrow(ServiceException::class)->during('verifyIpnMessage', [$message]);
    }
}

class ClientStub extends Client
{
    public function post($uri, array $options = []): ResponseInterface
    {
        return parent::post($uri, $options);
    }
}
