<?php

namespace Chrisbjr\SmsBlaster\Sdk;

use Carbon\Carbon;

class Sms
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $origin;

    /**
     * @var string
     */
    private $msisdn;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $clientTransactionId;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var Carbon
     */
    private $createdAt;

    /**
     * @var Carbon
     */
    private $updatedAt;

    /**
     * @param $smsResponse
     */
    public function __construct($smsResponse)
    {
        foreach ($smsResponse as $key => $value) {
            if ($key == 'created_at' || $key == 'updated_at') {
                $this->$key = Carbon::parse($smsResponse[$key]['date'], $smsResponse[$key]['timezone']);
            } else if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @return string
     */
    public function getMsisdn()
    {
        return $this->msisdn;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getClientTransactionId()
    {
        return $this->clientTransactionId;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

}