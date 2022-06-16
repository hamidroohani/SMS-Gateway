<?php


namespace App\Services\SmsProviders;


use App\Contracts\SmsProvider;
use App\Services\SmsProviders\Classes\SmsProviderException;
use GuzzleHttp\Handler\CurlMultiHandler;
use \GuzzleHttp\Promise\PromiseInterface;
use \GuzzleHttp\Client;

class Qasedak implements SmsProvider
{
    private string $api_key;
    private string $url;
    private string $status_url;

    /**
     * Qasedak constructor.
     */
    public function __construct(public string $number, private array $params, private CurlMultiHandler $handler)
    {
        $this->api_key = $this->params['api_key'];
        $this->url = $this->params['url'];
        $this->status_url = $this->params['status_url'];
    }

    /**
     * @inheritDoc
     */
    public function send(string $text, string $mobile_number): PromiseInterface
    {
        $client = new Client(['handler' => $this->handler]);
        return $client->postAsync($this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'apikey' => $this->api_key,
            ], 'json' => [
                'message' => $text,
                'number' => $this->number,
                'receptor' => $mobile_number,
            ]
        ])->then(function ($result) {
            $response = json_decode($result->getBody(), true);

            if (isset($response['messageids']) && intval($response['messageids']) >= 1000) {
                return $response['messageids'];
            } elseif (!isset($response['messageids'])) {
                $this->throw_exception();
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
        return $client->postAsync($this->status_url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'apikey' => $this->api_key
            ], 'json' => [
                'messageids' => $ref_code,
            ]
        ])->then(function ($result) {
            $response = json_decode($result->getBody(), true);

            if (isset($response['list']) && in_array($response['list'], [0, 1, 2, 8, 16, 27])) {
                return match ($response['list']) {
                    0, 8 => "sending",
                    1 => "delivered",
                    2, 16, 27 => "failed",
                    "default" => "unknown"
                };
            }
            $this->throw_exception();
        });
    }

    /**
     * The Qasedak provider has specific errors, and these service has it specific errors too,
     * The error codes must be translated to the service errors, that is unique for all providers
     * @throws SmsProviderException
     */
    public function throw_exception(int $code = 34)
    {
        $translated = match ($code) {
            1 => 8,
            2 => 30,
            3 => 14,
            4 => 19,
            5 => 31,
            6 => 1,
            7 => 12,
            8 => 11,
            9 => 10,
            10 => 9,
            11 => 16,
            20 => 32,
            21 => 33,
            default => 34
        };
        throw new SmsProviderException($translated);
    }
}
