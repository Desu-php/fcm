<?php

namespace App\Models;

use App\Interfaces\FcmNotifiableInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Device extends Model implements FcmNotifiableInterface
{
    use Notifiable;

    protected $fillable = [
        'device_token',
        'user_id',
    ];

    public function routeNotificationForFcm(): string
    {
        return $this->device_token;
    }
}
