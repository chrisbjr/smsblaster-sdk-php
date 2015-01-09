<?php

namespace Chrisbjr\SmsBlaster\Sdk\Interfaces;

use Chrisbjr\SmsBlaster\Sdk\Sms;
use Exception;

interface SmsInterface
{

    /**
     * This is called when the SMS being sent is invalid. Use this
     * method to invalidate the SMS. Attempting to send this SMS
     * again is futile.
     *
     * @param Exception $exception
     * @return mixed
     */
    public function onInvalid(Exception $exception);

    /**
     * This method is called when a server exception is encountered.
     * This means that the SMS should be marked as pending and sending
     * should be retried.
     *
     * @param Exception $exception
     * @return mixed
     */
    public function onError(Exception $exception);

    /**
     * Called when everything goes well.
     *
     * @param Sms $sms
     * @return mixed
     */
    public function onSuccess(Sms $sms);

}