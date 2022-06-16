<?php


namespace App\Services\SmsProviders;


use App\Contracts\SmsProvider;
use App\Services\SmsProviders\Classes\SmsProviderException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlMultiHandler;
use GuzzleHttp\Promise\PromiseInterface;

class KavehNegar implements SmsProvider
{
    private string $api_key;
    private string $url;
    private string $track_url;

    public function __construct(public string $number, private array $params, private CurlMultiHandler $handler)
    {
        $this->api_key = $this->params['api_key'];
        $this->url = $this->params['url'] ?? "https://api.kavenegar.com/v1/" . $this->api_key . "/sms/send.json/";
        $this->track_url = $this->params['track_url'] ?? "https://api.kavenegar.com/v1/" . $this->api_key . "/sms/status.json/";
    }

    /**
     * @inheritDoc
     */
    public function send(string $text, string $mobile_number): PromiseInterface
    {
        $client = new Client(['handler' => $this->handler]);
        return $client->postAsync($this->url, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'charset' => 'utf-8',
            ], 'body' => http_build_query([
                "receptor" => $mobile_number,
                "sender" => $this->number,
                "message" => $text,
                "date" => null,
                "type" => null,
                "localid" => null
            ])
        ])->then(function ($result) {
            $response = json_decode($result->getBody(), true);

            if (isset($response['entries']) && isset($response['entries']['messageid'])) {
                return $response['entries']['messageid'];
            } elseif (isset($response['return']['status'])) {
                $this->throw_exception($response['return']['status']);
            }
            $this->throw_exception($response['messageids']);
        });
    }

    /**
     * @inheritDoc
     */
    public function track(string $ref_code): PromiseInterface
    {
        $client = new Client(['handler' => $this->handler]);
        return $client->postAsync($this->track_url, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'charset' => 'utf-8',
            ], 'body' => http_build_query([
                "messageid" => $ref_code,
            ])
        ])->then(function ($result) {
            $response = json_decode($result->getBody(), true);

            if (is_array($response) && isset($response['entries']) && isset($response['entries'][0]['status']) && in_array($response['entries'][0]['status'], [1, 2, 4, 5, 6, 10, 11, 13, 14, 100])) {
                return match ($response['entries'][0]['status']) {
                    1, 2, 4, 5 => "sending",
                    10 => "delivered",
                    14 => "black list",
                    6, 11, 13 => "failed",
                    100, "default" => "unknown"
                };
            }
            $this->throw_exception();
        });
    }

    /**
     * The Kaveh Negar provider has specific errors, and these service has it specific errors too,
     * The error codes must be translated to the service errors, that is unique for all providers
     * @throws SmsProviderException
     */
    public function throw_exception(int $code = 34)
    {
        $translated = match ($code) {
            400 => 8,
            401 => 1,
            402 => 2,
            403 => 3,
            404 => 4,
            405 => 5,
            406 => 6,
            407, 428 => 7,
            418 => 10,
            409 => 9,
            411 => 11,
            412 => 12,
            413 => 13,
            414 => 14,
            415 => 15,
            416 => 16,
            417 => 17,
            419 => 19,
            420 => 20,
            422 => 22,
            424 => 24,
            426 => 26,
            427 => 27,
            429 => 29,
            430 => 30,
            431 => 31,
            432 => 32,
            433 => 33,
            default => 34
        };
        throw new SmsProviderException($translated);
    }
}
