<?php

namespace Chrisbjr\SmsBlaster\Sdk;

use Coreproc\MsisdnPh\Msisdn;
use GuzzleHttp\Client;

class SmsBlasterClient
{

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var Msisdn
     */
    public $msisdn;

    /**
     * @var Client
     */
    public $httpClient;

    /**
     * @param array $configuration
     */
    public function __construct($configuration)
    {
        foreach ($configuration as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        $this->msisdn = new Msisdn();
        $this->httpClient = new Client([
            'base_url' => $this->getBaseUrl(),
            'defaults' => [
                'headers'    => [
                    'X-Authorization' => $this->getApiKey()
                ]
            ]
        ]);
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

}