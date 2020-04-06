<?php

namespace DansMaCulotte\PayPal\Ipn;

use DansMaCulotte\PayPal\Ipn\Event\MessageInvalidEvent;
use DansMaCulotte\PayPal\Ipn\Event\MessageVerificationFailureEvent;
use DansMaCulotte\PayPal\Ipn\Event\MessageVerifiedEvent;
use DansMaCulotte\PayPal\Ipn\Exception\ServiceException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Listener
{
    public const IPN_VERIFIED_EVENT = 'ipn.message.verified';
    public const IPN_INVALID_EVENT = 'ipn.message.invalid';
    public const IPN_VERIFICATION_FAILURE_EVENT = 'ipn.message.verification_failure';

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var Verifier
     */
    private $verifier;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        MessageFactory $messageFactory,
        Verifier $verifier,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->messageFactory = $messageFactory;
        $this->verifier = $verifier;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function listen(): void
    {
        $message = $this->messageFactory->createMessage();

        try {
            $result = $this->verifier->verify($message);

            if ($result) {
                $eventName = self::IPN_VERIFIED_EVENT;
                $event = new MessageVerifiedEvent($message);
            } else {
                $eventName = self::IPN_INVALID_EVENT;
                $event = new MessageInvalidEvent($message);
            }
        } catch (\UnexpectedValueException $e) {
            $eventName = self::IPN_VERIFICATION_FAILURE_EVENT;
            $event = new MessageVerificationFailureEvent($message, $e->getMessage());
        } catch (ServiceException $e) {
            $eventName = self::IPN_VERIFICATION_FAILURE_EVENT;
            $event = new MessageVerificationFailureEvent($message, $e->getMessage());
        }

        $this->eventDispatcher->dispatch($event, $eventName);
    }

    /**
     * @param callable $listener
     */
    public function onVerified($listener): void
    {
        $this->eventDispatcher->addListener(self::IPN_VERIFIED_EVENT, $listener);
    }

    /**
     * @param callable $listener
     */
    public function onInvalid($listener): void
    {
        $this->eventDispatcher->addListener(self::IPN_INVALID_EVENT, $listener);
    }

    /**
     * @param callable $listener
     */
    public function onVerificationFailure($listener): void
    {
        $this->eventDispatcher->addListener(self::IPN_VERIFICATION_FAILURE_EVENT, $listener);
    }
}
