<?php

namespace App\DTO;

use App\Models\User;

class RegisterDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public string $deviceToken,
    )
    {
    }
}
