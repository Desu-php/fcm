<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\DTO\FcmMessageDTO;
use Throwable;

interface FcmTransportInterface
{
    /**
     * @throws Throwable
     */
    public function sendMessage(string $to, FcmMessageDTO $message): void;
}
