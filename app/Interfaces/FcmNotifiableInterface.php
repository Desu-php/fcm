<?php

namespace App\Interfaces;

interface FcmNotifiableInterface
{
    public function routeNotificationForFcm(): string;
}
