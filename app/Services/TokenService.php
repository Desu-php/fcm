<?php

namespace App\Services;

use App\Models\User;

class TokenService
{
    public function createToken(User $user, string $name): string
    {
        return $user->createToken($name)->plainTextToken;
    }
}
