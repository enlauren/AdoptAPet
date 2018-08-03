<?php
declare(strict_types=1);

namespace App\AppBundle\Services\Captcha;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class CaptchaValidator implements CaptchaValidatorInterface
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;
    protected $logger;
    protected $secret = "6LdW4xkTAAAAABTzXpXl-Ek5onV_w-dUrmBCPBeZ";
    protected $url    = "https://www.google.com/recaptcha/api/siteverify";

    /**
     * @param ClientInterface $httpClient
     * @param LoggerInterface $logger
     */
    public function __construct(
        ClientInterface $httpClient,
        LoggerInterface $logger
    )
    {
        $this->httpClient = $httpClient;
        $this->logger     = $logger;
    }

    public function validate(string $captcha, string $clientIp): bool
    {
        try {
            $response = $this->httpClient->request('POST', $this->url, [
                'form_params' => [
                    'remoteip' => $clientIp,
                    'secret'   => $this->secret,
                    'response' => $captcha,
                ]
            ]);

            $responseData = json_decode($response->getBody()->getContents());
        } catch (GuzzleException $e) {
            $this->logger->error("Unable to verify captcha on google.", [
                'exception' => $e,
                'response'  => $captcha,
                'remoteIp'  => $clientIp
            ]);

            return false;
        }

        return $responseData->success === true;
    }
}
