<?php

namespace Chrisbjr\SmsBlaster\Sdk;

use Chrisbjr\SmsBlaster\Sdk\Interfaces\SmsInterface;
use Exception;

class SampleSmsInterface implements SmsInterface
{

    public function onSuccess(Sms $sms)
    {
        echo $sms->getMessage();
    }

    public function onError(Exception $exception)
    {
        echo 'code: ' . $exception->getCode();
        echo '<br />';
        echo $exception->getMessage();
        echo '<br />';
    }

    public function onInvalid(Exception $exception)
    {
        echo 'code: ' . $exception->getCode();
        echo '<br />';
        echo $exception->getMessage();
        echo '<br />';
    }
}