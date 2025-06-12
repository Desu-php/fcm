<?php
declare(strict_types=1);

namespace App\Notifications\Channels;

use App\Clients\FcmClient;
use App\Interfaces\FcmNotifiableInterface;
use App\Interfaces\FcmNotificationInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

readonly class FcmChannel
{
    public function __construct(
        private FcmClient $client,
    )
    {
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function send(FcmNotifiableInterface $notifiable, FcmNotificationInterface $notification): void
    {
        $dto = $notification->toFcm();

        $this->client->send($notifiable->routeNotificationForFcm(), [
            'title' => $dto->title,
            'message' => $dto->message,
        ]);
    }
}
