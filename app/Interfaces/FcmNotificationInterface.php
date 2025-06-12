<?php

namespace App\Interfaces;

use App\DTO\FcmMessageDTO;

interface FcmNotificationInterface
{
    public function toFcm(): FcmMessageDTO;
}
