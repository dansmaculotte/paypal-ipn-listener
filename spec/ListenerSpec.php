<?php

namespace spec\DansMaCulotte\PayPal\Ipn;

use DansMaCulotte\PayPal\Ipn\Event\MessageInvalidEvent;
use DansMaCulotte\PayPal\Ipn\Event\MessageVerificationFailureEvent;
use DansMaCulotte\PayPal\Ipn\Event\MessageVerifiedEvent;
use DansMaCulotte\PayPal\Ipn\Exception\ServiceException;
use DansMaCulotte\PayPal\Ipn\Listener;
use DansMaCulotte\PayPal\Ipn\Message;
use DansMaCulotte\PayPal\Ipn\MessageFactory;
use DansMaCulotte\PayPal\Ipn\Verifier;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ListenerSpec extends ObjectBehavior
{
    public function let(
        MessageFactory $messageFactory,
        Message $message,
        Verifier $verifier,
        EventDispatcher $eventDispatcher
    ): void {
        $messageFactory->createMessage()->willReturn($message);

        $this->beConstructedWith(
            $messageFactory,
            $verifier,
            $eventDispatcher
        );
    }

    public function it_should_dispatch_an_event_when_a_message_is_verified(
        Verifier $verifier,
        EventDispatcher $eventDispatcher
    ): void {
        $verifier->verify(
            Argument::type(Message::class)
        )->willReturn(true);

        $eventDispatcher->dispatch(
            Argument::type(MessageVerifiedEvent::class),
            Listener::IPN_VERIFIED_EVENT
        )->shouldBeCalled();

        $this->listen();
    }

    public function it_should_dispatch_an_event_when_a_message_is_invalid(
        Verifier $verifier,
        EventDispatcher $eventDispatcher
    ): void {
        $verifier->verify(
            Argument::type(Message::class)
        )->willReturn(false);

        $eventDispatcher->dispatch(
            Argument::type(MessageInvalidEvent::class),
            Listener::IPN_INVALID_EVENT
        )->shouldBeCalled();

        $this->listen();
    }

    public function it_should_dispatch_an_event_when_it_fails_to_verify_a_message_due_to_an_unexpected_response(
        Verifier $verifier,
        EventDispatcher $eventDispatcher
    ): void {
        $verifier->verify(
            Argument::type(Message::class)
        )->willThrow('UnexpectedValueException');

        $eventDispatcher->dispatch(
            Argument::type(MessageVerificationFailureEvent::class),
            Listener::IPN_VERIFICATION_FAILURE_EVENT
        )->shouldBeCalled();

        $this->listen();
    }

    public function it_should_dispatch_an_event_when_it_fails_to_verify_a_message_due_to_a_service_failure(
        Verifier $verifier,
        EventDispatcher $eventDispatcher
    ): void {
        $verifier->verify(
            Argument::type(Message::class)
        )->willThrow(ServiceException::class);

        $eventDispatcher->dispatch(
            Argument::type(MessageVerificationFailureEvent::class),
            Listener::IPN_VERIFICATION_FAILURE_EVENT
        )->shouldBeCalled();

        $this->listen();
    }

    public function it_should_attach_a_listener_for_the_message_verified_event(EventDispatcher $eventDispatcher): void
    {
        $eventDispatcher->addListener(
            Listener::IPN_VERIFIED_EVENT,
            Argument::type('callable')
        )->shouldBeCalled();

        $this->onVerified(function (MessageVerifiedEvent $event) {
        });
    }

    public function it_should_attach_a_listener_for_the_message_invalid_event(EventDispatcher $eventDispatcher): void
    {
        $eventDispatcher->addListener(
            Listener::IPN_INVALID_EVENT,
            Argument::type('callable')
        )->shouldBeCalled();

        $this->onInvalid(function (MessageInvalidEvent $event) {
        });
    }

    public function it_should_attach_a_listener_for_the_message_verification_failure_event(EventDispatcher $eventDispatcher): void
    {
        $eventDispatcher->addListener(
            Listener::IPN_VERIFICATION_FAILURE_EVENT,
            Argument::type('callable')
        )->shouldBeCalled();

        $this->onVerificationFailure(function (MessageVerificationFailureEvent $event) {
        });
    }
}
