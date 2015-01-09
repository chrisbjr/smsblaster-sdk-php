Globe Labs API - PHP Library
========

A PHP library for consuming Globe Labs API - brought to you by Coreproc.

## Quick start

### Required setup

The easiest way to install this library is via Composer.

Create a `composer.json` file and enter the following:

    {
        "require": {
            "chrisbjr/smsblaster-sdk": "0.1.*"
        }
    }

If you haven't yet downloaded your composer file, you can do so by executing the following in your command line:

    curl -sS https://getcomposer.org/installer | php

Once you've downloaded the composer.phar file, continue with your installation by running the following:

    php composer.phar install

## Sending SMS

You will need to set up an account to use this. Once you have an account, you can generate an API key in your account settings.

### Basic SMS sending

For a basic usage, you can do the following to send an SMS:

    <?php
    
    require 'vendor/autoload.php';
    
    use Chrisbjr\SmsBlaster\Sdk\Requests\SmsRequest;
    use Chrisbjr\SmsBlaster\Sdk\SmsBlasterClient;
    use Chrisbjr\SmsBlaster\Sdk\SampleSmsInterface;
    use GuzzleHttp\Exception\RequestException;
    
    $smsBlasterClient = new SmsBlasterClient([
    	'baseUrl'   => 'http://you-need-the-base-url-of-the-api-here.com',
        'apiKey'    => 'your-api-key-here'
    ]);
    
    $smsRequest = new SmsRequest($smsBlasterClient);
    $smsRequest->setOrigin('COOLCOMPANY');
    $smsRequest->setRecipient('+639221231234');
    $smsRequest->setMessage("Hello this is message #1");
    
    try {
        $sms = $smsRequest->send();
        
        // if the request is successful, you will be returned an SMS object.
        
    } catch (RequestException $e) {
        
        // Failed to send
        echo $e->getMessage();
        
        // This returns Guzzle's RequestException so do whatever you want with this.
    }

### Asynchronous SMS sending

This is what you would typically use in a blasting application to be able to send multiple SMS messages without having to wait for the response of each.

    <?php
    
    require 'vendor/autoload.php';
    
    use Chrisbjr\SmsBlaster\Sdk\Requests\SmsRequest;
    use Chrisbjr\SmsBlaster\Sdk\SmsBlasterClient;
    use Chrisbjr\SmsBlaster\Sdk\SampleSmsInterface;
    
    $smsBlasterClient = new SmsBlasterClient([
    	'baseUrl' 	=> 'http://you-need-the-base-url-of-the-api-here.com',
        'apiKey' 	=> 'your-api-key-here'
    ]);
    
    // You can customize your own custom callbacks by implementing the SmsInterface class.
    // A SampleSmsInterface is included in this library for reference.
    $smsInterface = new SampleSmsInterface();
    
    $smsRequest = new SmsRequest($smsBlasterClient);
    $smsRequest->setOrigin('COOLCOMPANY');
    $smsRequest->setRecipient('+639221231234');
    $smsRequest->setMessage("Hello this is test #1");
    $smsRequest->send(true, $smsInterface);
    
    $smsRequest = new SmsRequest($smsBlasterClient);
    $smsRequest->setOrigin('COOLCOMPANY');
    $smsRequest->setRecipient('09171231234');
    $smsRequest->setMessage("Hello this is test #2");
    $smsRequest->send(true, $smsInterface);
    
    $smsRequest = new SmsRequest($smsBlasterClient);
    $smsRequest->setOrigin('COOLCOMPANY');
    $smsRequest->setRecipient('09981231234');
    $smsRequest->setMessage("Hello this is test #3");
    $smsRequest->send(true, $smsInterface);
    
