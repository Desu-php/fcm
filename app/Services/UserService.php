<?php
declare (strict_types=1);

namespace App\Services;

use App\Models\User;

class UserService
{
    public function create(string $email, string $password): User
    {
        return User::create([
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function findUserByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }
}
