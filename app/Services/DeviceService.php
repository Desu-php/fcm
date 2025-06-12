<?php

namespace App\Services;

use App\Models\Device;
use App\Models\User;

class DeviceService
{
    public function createDevice(User $user, string $token): Device
    {
        return Device::create([
            'device_token' => $token,
            'user_id' => $user->id
        ]);
    }
}
