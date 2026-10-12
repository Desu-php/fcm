<?php

declare(strict_types=1);

namespace App\Notifications\Channels;

use App\Interfaces\FcmNotifiableInterface;
use App\Interfaces\FcmNotificationInterface;
use App\Interfaces\FcmTransportInterface;
use Throwable;

readonly class FcmChannel
{
    public function __construct(
        private FcmTransportInterface $client,
    ) {}

    /**
     * @throws Throwable
     */
    public function send(FcmNotifiableInterface $notifiable, FcmNotificationInterface $notification): void
    {
        $this->client->sendMessage(
            $notifiable->routeNotificationForFcm(),
            $notification->toFcm(),
        );
    }
}
