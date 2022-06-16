<?php


namespace App\Contracts;


use \GuzzleHttp\Promise\PromiseInterface;

interface SmsProvider
{
    /**
     * The method for send simple SMS
     *
     * This method use for sending simple message, the string of message and the string of mobile_number are the entries
     * The method uses guzzle promise to send messages and finally returns promise model to continue with the client (such as save ref_code to db),
     * So you can call then() method after send method.
     *
     * This `send` method always returns a ref_code;
     * Note: this value will return from this method and is available inside the then method as a parameter
     *
     * ex: $provider->send(params)->then(function($ref_code){ $ref_code } );
     *
     * In fact there are two then-method inside promise and the value of the first one are available on the second then-method
     * so the first one is inside the send-service and the second one is available after call send
     *
     *
     * @param string $text
     * @param string $mobile_number
     * @return PromiseInterface
     * @throws \Exception
     */
    public function send(string $text, string $mobile_number): PromiseInterface;

    /**
     * The method for track and check the delivery status of a SMS message
     *
     * In this method we request to sms provider (such as kaveh negar) to find delivery status of messages.
     * The method uses guzzle promise to check delivery and finally returns promise model to continue with the client (such as save ref_code to db),
     * So you can call then() method after track method
     *
     * This `track` method always returns a status.
     *
     * status -> is string and is the value of delivery status that the provider returned (ex: delivered).
     *
     * Note: this status will return from this method and is available inside the then method as a parameter
     *
     * another example: $provider->send(params)->then(function($status){ $status } );
     *
     * In fact there are two then-method inside promise and the value of the first one are available on the second then-method
     * so the first one is inside the track-service and the second one is available after call send
     *
     * @param string $ref_code
     * @return PromiseInterface
     * @throws \Exception
     */
    public function track(string $ref_code): PromiseInterface;

}
