<?php

namespace DansMaCulotte\PayPal\Ipn\Service;

use DansMaCulotte\PayPal\Ipn\Exception\ServiceException;
use DansMaCulotte\PayPal\Ipn\Message;
use DansMaCulotte\PayPal\Ipn\Service;
use DansMaCulotte\PayPal\Ipn\ServiceResponse;
use GuzzleHttp\ClientInterface;

class GuzzleService implements Service
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var string
     */
    private $serviceEndpoint;

    public function __construct(ClientInterface $httpClient, string $serviceEndpoint)
    {
        $this->httpClient = $httpClient;
        $this->serviceEndpoint = $serviceEndpoint;
    }

    public function verifyIpnMessage(Message $message): ServiceResponse
    {
        $requestBody = array_merge(
            ['cmd' => '_notify-validate'],
            $message->getAll()
        );

        try {
            $response = $this->httpClient->post(
                $this->serviceEndpoint,
                ['form_params' => $requestBody]
            );
        } catch (\Exception $e) {
            throw new ServiceException($e->getMessage());
        }

        return new ServiceResponse(
            (string) $response->getBody()
        );
    }
}
