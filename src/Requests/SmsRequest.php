<?php

namespace Chrisbjr\SmsBlaster\Sdk\Requests;

use Chrisbjr\SmsBlaster\Sdk\Interfaces\SmsInterface;
use Chrisbjr\SmsBlaster\Sdk\Sms;
use Chrisbjr\SmsBlaster\Sdk\SmsBlasterClient;
use Exception;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\ResponseInterface;

class SmsRequest
{

    /**
     * @var SmsBlasterClient
     */
    private $smsBlasterClient;

    /**
     * @var string
     */
    private $origin;

    /**
     * @var string
     */
    private $recipient;

    /**
     * @var string
     */
    private $message;

    /**
     * @var boolean
     */
    private $isMock = false;

    /**
     * @var SmsInterface
     */
    private $smsInterface;

    public function __construct(SmsBlasterClient $smsBlasterClient)
    {
        $this->smsBlasterClient = $smsBlasterClient;
    }

    /**
     * @param bool $async
     * @param SmsInterface $smsInterface
     * @return Sms|null
     * @throws Exception
     */
    public function send($async = false, SmsInterface $smsInterface = null)
    {
        if (empty($this->origin)) {
            $exception = new Exception("An origin is required to be set.", 400);
            $smsInterface->onInvalid($exception);
            throw $exception;
        }

        if (empty($this->recipient)) {
            $exception = new Exception("A recipient is required to be set.", 400);
            $smsInterface->onInvalid($exception);
            throw $exception;
        }

        if ($this->smsBlasterClient->msisdn->isValid($this->recipient) === false) {
            $exception = new Exception("The recipient you defined is not a valid mobile number.", 400);
            $smsInterface->onInvalid($exception);
            throw $exception;
        }

        if ($async === true && ! is_null($smsInterface)) {

            $this->smsInterface = $smsInterface;

            $this->smsBlasterClient->httpClient->post('sms', [
                'future' => true,
                'json'   => [
                    'origin'    => $this->origin,
                    'recipient' => $this->recipient,
                    'message'   => $this->message,
                    'mock'      => $this->isMock
                ]
            ])->then(
                function (ResponseInterface $response) {
                    // successful
                    $json = $response->json();
                    $sms = new Sms($json->data);
                    $this->smsInterface->onSuccess($sms);
                },
                function (RequestException $error) {
                    if ($error->hasResponse()) {
                        $errorResponse = $error->getResponse();
                        if ($errorResponse->getStatusCode() >= 400 && $errorResponse->getStatusCode() < 500) {
                            // 4xx means invalid
                            $json = $errorResponse->json();
                            $exception = new Exception($json->error->message, $errorResponse->getStatusCode());
                            $this->smsInterface->onInvalid($exception);
                        }
                    } else {
                        $this->smsInterface->onError($error);
                    }
                });

        } else {

            // normal guzzle call
            try {
                $response = $this->smsBlasterClient->httpClient->post('sms', [
                    'json' => [
                        'origin'    => $this->origin,
                        'recipient' => $this->recipient,
                        'message'   => $this->message,
                    ]
                ]);

                $json = $response->json();

                $sms = new Sms($json->data);

                return $sms;

            } catch (RequestException $error) {
                throw $error;
            }

        }

        return null;

    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }

    /**
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param string $recipient
     */
    public function setRecipient($recipient)
    {
        if ($this->smsBlasterClient->msisdn->set($recipient)) {
            $this->recipient = $this->smsBlasterClient->msisdn->get();
        } else {
            $this->recipient = null;
        }
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return boolean
     */
    public function isIsMock()
    {
        return $this->isMock;
    }

    /**
     * @param boolean $isMock
     */
    public function setIsMock($isMock)
    {
        $this->isMock = $isMock;
    }

}