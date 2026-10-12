<?php

declare(strict_types=1);

namespace App\Clients;

use App\DTO\FcmMessageDTO;
use App\Interfaces\FcmTransportInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

readonly class FcmClient implements FcmTransportInterface
{
    private PendingRequest $http;

    public function __construct()
    {
        $this->http = Http::baseUrl('https://fcm.googleapis.com/fcm')
            ->withHeader('Authorization', 'key='.config('services.fcm.server_key'));
    }

    public function sendMessage(string $to, FcmMessageDTO $message): void
    {
        $this->send($to, $message->toArray());
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
