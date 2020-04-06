<?php

use Assert\Assertion;
use Behat\Behat\Context\Context;
use DansMaCulotte\PayPal\Ipn\Event\MessageVerificationEvent;
use DansMaCulotte\PayPal\Ipn\Event\MessageVerificationFailureEvent;
use DansMaCulotte\PayPal\Ipn\Listener;
use DansMaCulotte\PayPal\Ipn\MessageFactory\ArrayMessageFactory;
use DansMaCulotte\PayPal\Ipn\Service\GuzzleService;
use DansMaCulotte\PayPal\Ipn\Verifier;
use GuzzleHttp\Client;
use Symfony\Component\EventDispatcher\EventDispatcher;

class FeatureContext implements Context
{
    public const SERVICE_ENDPOINT = 'http://localhost';
    public const SERVICE_ENDPOINT_PORT_ENV_VAR = 'MOCK_SERVER_PORT';

    /**
     * @var array
     */
    protected $ipnMessageData = [];

    /**
     * @var bool
     */
    private $invalidIpn = false;

    /**
     * @var bool
     */
    private $verifiedIpn = false;

    /**
     * @beforeScenario @invalidIpn
     */
    public function willFailVerification(): void
    {
        $this->ipnMessageData = [
            '__verified' => 0,
        ];
    }

    /**
     * @beforeScenario @verifiedIpn
     */
    public function willPassVerification(): void
    {
        $this->ipnMessageData = [
            '__verified' => 1,
        ];
    }

    /**
     * @afterScenario
     */
    public function resetIpnStatus(): void
    {
        $this->invalidIpn = false;
        $this->verifiedIpn = false;
    }

    /**
     * @Given I have received an IPN message
     */
    public function iHaveReceivedAnIpnMessage(): void
    {
        $data = [
            'foo' => 'bar',
            'baz' => 'qux',
        ];

        $this->ipnMessageData = array_merge($this->ipnMessageData, $data);
    }

    /**
     * @When I verify the IPN message with PayPal
     */
    public function iVerifyTheIpnMessageWithPaypal(): void
    {
        $listener = $this->getListener();

        $that = $this;

        $listener->onInvalid(function (MessageVerificationEvent $event) use ($that) {
            $that->invalidIpn = true;
        });

        $listener->onVerified(function (MessageVerificationEvent $event) use ($that) {
            $that->verifiedIpn = true;
        });

        $listener->onVerificationFailure(function (MessageVerificationFailureEvent $event) {
            throw new Exception(
                sprintf('Failed to verify IPN: %s', $event->getError())
            );
        });

        $listener->listen();
    }

    /**
     * @Then PayPal should report that the IPN message is untrustworthy
     */
    public function paypalShouldReportThatTheIpnMessageIsUntrustworthy(): void
    {
        Assertion::true($this->invalidIpn);
    }

    /**
     * @Then PayPal should report that the IPN message is trustworthy
     */
    public function paypalShouldReportThatTheIpnMessageIsTrustworthy(): void
    {
        Assertion::true($this->verifiedIpn);
    }

    protected function getListener(): Listener
    {
        $service = new GuzzleService(
            new Client(),
            $this->getServiceEndpoint()
        );

        $verifier = new Verifier($service);

        return new Listener(
            new ArrayMessageFactory($this->ipnMessageData),
            $verifier,
            new EventDispatcher()
        );
    }

    protected function getServiceEndpoint(): string
    {
        return sprintf('%s:%s', self::SERVICE_ENDPOINT, getenv(self::SERVICE_ENDPOINT_PORT_ENV_VAR));
    }
}
