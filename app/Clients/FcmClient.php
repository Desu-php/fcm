<?php

namespace App\Clients;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

readonly class FcmClient
{
    private PendingRequest $http;

    public function __construct()
    {
        $this->http = Http::baseUrl('https://fcm.googleapis.com/fcm')
            ->withHeader('Authorization', 'key=' . config('services.fcm.server_key'));
    }

    /**
     * @throws ConnectionException
     * @throws RequestException
     */
    public function send(string $to, array $data): PromiseInterface|Response
    {
        return $this->http->post('send', [
            'to' => $to,
            'data' => $data,
        ])->throw();
    }
}
